<?php
/**
 * Class TemplateAbstract
 *
 * @created      21.05.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace BuildWars\GWTemplates;

use InvalidArgumentException;
use function array_map;
use function bindec;
use function decbin;
use function implode;
use function pack;
use function preg_match;
use function sodium_base642bin;
use function sodium_bin2base64;
use function sprintf;
use function str_pad;
use function str_split;
use function strlen;
use function strrev;
use function strtr;
use function substr;
use function trim;
use function unpack;
use const SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING;

/**
 * Abstract Guild Wars template encoding/decoding
 */
abstract class TemplateAbstract{

	final protected const TEMPLATE_SKILL_OLD     = 0b0000;
	final protected const TEMPLATE_SKILL_NEW     = 0b1110;
	final protected const TEMPLATE_EQUIPMENT_OLD = 0b0001;
	final protected const TEMPLATE_EQUIPMENT_NEW = 0b1111;

	protected int    $offset = 0;
	protected string $string = '';

#	public function __construct(){
#		// https://stackoverflow.com/a/24785578
#		if(unpack('S',"\x01\x00")[1] !== 1){
#			throw new RuntimeException('machine byte order is not little endian');
#		}
#	}

	/**
	 * Reverses the given binary number string and converts it to an integer
	 */
	protected function bindec_flip(string $bin):int{
		return (int)bindec(strrev($bin));
	}

	/**
	 * Converts the given integer into a binary number string and reverses it
	 */
	protected function decbin_flip(int $dec):string{
		return strrev(decbin($dec));
	}

	/**
	 * Converts the given integer into a binary number string, reverses it,
	 * and adds the given amount of zero padding to the right
	 */
	protected function decbin_pad(int $dec, int $padding):string{
		return str_pad($this->decbin_flip($dec), $padding, '0');
	}

	/**
	 * Checks if the given string is a valid base64 string
	 */
	protected function checkCharacterSet(string $base64):string{
		// nasty fix for urlencode and padded strings
		$base64 = trim(strtr($base64, [' ' => '+', '=' => '']));

		if($base64 === ''){
			return '';
		}

		/** @noinspection RegExpRedundantEscape */
		if(!preg_match('/^[A-Za-z0-9\+\/]*$/', $base64)){
			throw new InvalidArgumentException('Base64 must match RFC3548 character set');
		}

		return $base64;
	}

	/**
	 * Determines the minimum pad size
	 *
	 * @param int[] $nums
	 */
	protected function getPadSize(array $nums, int $min_pad):int{

		foreach($nums as $num){
			if($num >= (2 ** $min_pad)){
				$min_pad++;
			}
		}

		return $min_pad;
	}

	/**
	 * Decodes a string from unpadded base64
	 *
	 * @throws \SodiumException
	 */
	protected function base64decode(string $base64):string{
		$base64 = $this->checkCharacterSet($base64);

		// PHP's sodium base64 decode is a bit picky, so we're gonna add zeroes until the bit count is divisible by 8
		while((strlen($base64) % 8) !== 0){ // phpcs:ignore
			$base64 .= 'A';
		}

		return sodium_base642bin($base64, SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);
	}

	/**
	 * Encodes a string into unpadded base64
	 *
	 * @throws \SodiumException
	 */
	protected function base64encode(string $string):string{
		return sodium_bin2base64($string, SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);
	}

	/**
	 * Reads the given amount of bits from the set string
	 */
	protected function read(int $length):int{
		$dec           = $this->bindec_flip(substr($this->string, $this->offset, $length));
		$this->offset += $length;

		return $dec;
	}

	/**
	 * Decodes a template from the base64 format into a binary number (base2) string
	 *
	 * @throws \InvalidArgumentException
	 * @throws \SodiumException
	 * @throws \UnhandledMatchError
	 */
	protected function decodeTemplate(string $base64):string{

		if($base64 === ''){
			throw new InvalidArgumentException('invalid base64 template');
		}

		// decode the template into 8-bit characters (unsigned char)
		$chars = $this->base64decode($base64);
		$base2 = $this->decodeBinaryToBase2($chars);
		// get the first 4 bits and decide what to do
		return match($this->bindec_flip(substr($base2, 0, 4))){
			// new format, remove leading template type and version number
			self::TEMPLATE_SKILL_NEW, self::TEMPLATE_EQUIPMENT_NEW => substr($base2, 8),
			// old format prior to April 5, 2007, remove version number
			self::TEMPLATE_SKILL_OLD, self::TEMPLATE_EQUIPMENT_OLD => substr($base2, 4),
		};
	}

	/**
	 * Encodes a binary number (base2) template to base64 format
	 *
	 * @throws \SodiumException
	 */
	protected function encodeTemplate(string $base2):string{

		if($base2 === ''){
			throw new InvalidArgumentException('invalid binary template');
		}

		$bin8 = $this->encodeBase2ToBinary($base2);
		// convert to base64
		return $this->base64encode($bin8);
	}

	/**
	 * Decodes the given raw 8-bit binary (unsigned char) string from the decoded base64
	 * into a base2 string suitable for reading the template data.
	 *
	 * @see https://wiki.guildwars.com/wiki/Talk:Skill_template_format#I_don't_get_it
	 */
	protected function decodeBinaryToBase2(string $chars):string{
		// unpack the string from unsigned char
		$uint8 = unpack('C*', $chars);
		// base convert 10 to 2 (8 bits each value, zero padded to the left)
		$bin8  = array_map(fn(int $num):string => sprintf('%08b', $num), $uint8);
		// now split the string into chunks of 6 bits and reverse each chunk
		$bin6  = array_map(strrev(...), str_split(implode('', $bin8), 6));
		// glue the string back together and return the result
		return implode('', $bin6);
	}

	/**
	 * Encodes the given base2 template data string into an 8-bit binary string.
	 */
	protected function encodeBase2ToBinary(string $base2):string{
		// fill the string with zeroes until it is divisible by 6 and 8
		while(strlen($base2) % 8 !== 0 || strlen($base2) % 6 !== 0){ // phpcs:ignore
			$base2 .= '0';
		}
		// split into chunks of 6 and reverse each block
		$bin6  = implode('', array_map(strrev(...), str_split($base2, 6)));
		// split the string into chunks of 8 and base convert each chunk from 2 to 10
		$uint8 = array_map(bindec(...), str_split($bin6, 8));
		// convert the uint8 into an 8-bit binary string (unsigned char)
		return pack('C*', ...$uint8);
	}

}
