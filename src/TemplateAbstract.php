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
use function chr;
use function decbin;
use function implode;
use function ord;
use function pow;
use function sodium_base642bin;
use function sodium_bin2base64;
use function sprintf;
use function str_pad;
use function str_replace;
use function str_split;
use function strlen;
use function strrev;
use function substr;
use function trim;
use const SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING;

/**
 * Abstract Guild Wars template encoding/decoding
 */
abstract class TemplateAbstract{

	final protected const TEMPLATE_SKILL_OLD     = 0b0000;
	final protected const TEMPLATE_SKILL_NEW     = 0b1110;
	final protected const TEMPLATE_EQUIPMENT_OLD = 0b0001;
	final protected const TEMPLATE_EQUIPMENT_NEW = 0b1111;

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
	 * Determines the minimum pad size
	 */
	protected function getPadSize(array $nums, int $min_pad):int{

		foreach($nums as $num){
			if($num >= pow(2, $min_pad)){
				$min_pad++;
			}
		}

		return $min_pad;
	}

	/**
	 * Decodes a template from the base64 format into a binary number string
	 *
	 * @throws \InvalidArgumentException
	 * @throws \SodiumException
	 * @throws \UnhandledMatchError
	 */
	protected function decodeTemplate(string $template):string{
		// nasty fix for urlencode
		$template = str_replace(' ', '+', trim($template));

		if($template === ''){
			throw new InvalidArgumentException('invalid base64 template');
		}

		// PHP's base64 decode is a bit picky, so we're gonna add zeroes until the bit count is divisible by 8
		// PHPCS:ignore
		while(((strlen($template) * 6) % 8) !== 0){
			$template .= 'A';
		}

		// decode the template and split the 8-bit characters into an array
		$chars = str_split(sodium_base642bin($template, SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING));
		// convert to 8-bit binary numbers (base convert 10 to 2 with 0 padding to the left)
		$bin8 = array_map(fn(string $chr):string => sprintf('%08b', ord($chr)), $chars);
		// now split into chunks of 6 and reverse each block
		$bin6 = array_map('strrev', str_split(implode('', $bin8), 6));
		// glue it back together
		$bin = implode('', $bin6);

		// get the first 4 bits and decide what to do
		return match($this->bindec_flip(substr($bin, 0, 4))){
			// new format, remove leading template type and version number
			self::TEMPLATE_SKILL_NEW, self::TEMPLATE_EQUIPMENT_NEW => substr($bin, 8),
			// old format prior to April 5, 2007, remove version number
			self::TEMPLATE_SKILL_OLD, self::TEMPLATE_EQUIPMENT_OLD => substr($bin, 4),
		};
	}

	/**
	 * Encodes a binary number template to base64 format
	 *
	 * @throws \SodiumException
	 */
	protected function encodeTemplate(string $bin):string{

		if($bin === ''){
			throw new InvalidArgumentException('invalid binary template');
		}

		// fill the string with zeroes until it is divisible by 8
		// PHPCS:ignore
		while(strlen($bin) % 8 !== 0){
			$bin .= '0';
		}

		// split into chunks of 6 and reverse each block
		$bin6 = implode('', array_map('strrev', str_split($bin, 6)));
		// split into chunks of 8, convert base from 2 to 10 and generate an 8-bit byte character from the result
		$bin8 = array_map(fn(string $bin):string => chr(bindec($bin)), str_split($bin6, 8));

		// convert to base64
		return sodium_bin2base64(implode('', $bin8), SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);
	}

}
