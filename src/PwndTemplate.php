<?php
/**
 * Class PwndTemplate
 *
 * @created      21.05.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace BuildWars\GWTemplates;

use InvalidArgumentException;
use Throwable;
use function array_fill;
use function chunk_split;
use function intdiv;
use function is_string;
use function preg_match;
use function sodium_base642bin;
use function sodium_bin2base64;
use function sprintf;
use function str_replace;
use function str_starts_with;
use function strlen;
use function strpos;
use function substr;
use function trim;
use const SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING;

/**
 * Encodes and decodes paw·ned² team build templates
 *
 * @link https://memorial.redeemer.biz/pawned2/
 */
final class PwndTemplate extends TemplateAbstract{

	private const PWND_PREFIX = 'pwnd0001';
	private const PWND_HEADER = 'pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates';
	private const BASE64      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

	private array $builds = [];

	/**
	 * Returns the ordinal for the given base64 character
	 */
	private function base64_ord(string $chr):int{
		/** @phan-suppress-next-line PhanParamSuspiciousOrder */
		$ord = strpos(self::BASE64, $chr);

		if($ord === false){
			throw new InvalidArgumentException(sprintf('invalid character given: "%s"', $chr));
		}

		return $ord;
	}

	/**
	 * Returns the base64 character for the given ordinal
	 */
	private function base64_chr(int $ord):string{

		if(!isset(self::BASE64[$ord])){
			throw new InvalidArgumentException(sprintf('invalid ordinal given: "%s"', $ord));
		}

		return self::BASE64[$ord];
	}

	/**
	 * Decodes the given paw-ned² template into an array
	 */
	public function decode(string $pwnd):array{
		$pwnd  = str_replace(["\r", "\n"], '', trim($pwnd));
		$start = strpos($pwnd, '>');
		$end   = strpos($pwnd, '<', $start) - 1;

		if($end <= $start || !str_starts_with($pwnd, 'pwnd000')){
			throw new InvalidArgumentException('invalid pwnd template');
		}

#		$header = substr($pwnd, 0, $start);
		$b64    = str_replace(' ', '', substr($pwnd, $start + 1, $end - $start));

		$total  = strlen($b64);
		$builds = [];
		$offset = 0;

		while($offset < $total){
			$build = [];

			$length = $this->base64_ord(substr($b64, $offset, 1));
			$offset++;
			$build['skills'] = substr($b64, $offset, $length);
			$offset += $length;

			$length = $this->base64_ord(substr($b64, $offset, 1));
			$offset++;
			$build['equipment'] = substr($b64, $offset, $length);
			$offset += $length;

			for($i = 0; $i < 3; $i++){
				$length = $this->base64_ord(substr($b64, $offset, 1));
				$offset++;
				$build['weaponsets'][$i] = substr($b64, $offset, $length);
				$offset += $length;
			}

			$length = $this->base64_ord(substr($b64, $offset, 1));
			$offset++;
			// nobody knows what the flags are or how they're encoded, so we may as well ignore them
			// (i think it's additional skill points in the UI)
#			$build['flags'] = substr($b64, $offset, $length);
			$offset += $length;

			$length = $this->base64_ord(substr($b64, $offset, 1));
			$offset++;
			$build['player'] = sodium_base642bin(substr($b64, $offset, $length), SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);
			$offset += $length;

			$length = $this->base64_ord(substr($b64, $offset, 1)) * 64;
			$offset++;
			$length += $this->base64_ord(substr($b64, $offset, 1));
			$offset++;
			$build['description'] = sodium_base642bin(substr($b64, $offset, $length), SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);
			$offset += $length;

			$builds[] = $build;
		}

		return $builds;
	}

	/**
	 * Encodes the given build(s) into a pwnd template
	 */
	public function encode():string{
		$pwnd = '';

		foreach($this->builds as $build){
			$pwnd .= $this->base64_chr(strlen($build['skills']));
			$pwnd .= $build['skills'];

			$pwnd .= $this->base64_chr(strlen($build['equipment']));
			$pwnd .= $build['equipment'];

			foreach($build['weaponsets'] as $set){
				$pwnd .= $this->base64_chr(strlen($set));
				$pwnd .= $set;
			}

			$pwnd .= $this->base64_chr(0); // we're setting the flags to zero-length
#			$pwnd .= ''; // noop

			$pwnd .= $this->base64_chr(strlen($build['player']));
			$pwnd .= $build['player'];

			$pwnd .= $this->base64_chr(intdiv(strlen($build['description']), 64));
			$pwnd .= $this->base64_chr(strlen($build['description']) % 64);
			$pwnd .= $build['description'];
		}

		return self::PWND_PREFIX.'?'.self::PWND_HEADER."\r\n".trim(chunk_split('>'.$pwnd.'<', 80));
	}

	/**
	 * Adds a build item
	 *
	 * @param string[] $weaponsets
	 */
	public function addBuild(
		string      $skills,
		string|null $equipment = null,
		array       $weaponsets = [],
		string|null $player = null,
		string|null $description = null,
	):self{

		$this->builds[] = [
			'skills'      => $this->checkCharacterSet($skills),
			'equipment'   => $this->checkCharacterSet($equipment ?? ''),
			'weaponsets'  => $this->normalizeWeaponsets($weaponsets),
			'player'      => sodium_bin2base64(($player ?? ''), SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING),
			'description' => sodium_bin2base64(($description ?? "\r\n"), SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING),
		];

		return $this;
	}

	/**
	 * Clears all currently added build items
	 */
	public function clearBuilds():self{
		$this->builds = [];

		return $this;
	}

	/**
	 * Checks/normalizes the given weapon sets, limits input to 3 items
	 */
	private function normalizeWeaponsets(array $weaponsets):array{
		$normalizedWeaponsets = array_fill(0, 3, '');

		$i = 0;

		foreach($weaponsets as $weaponset){

			if($i > 2){
				break;
			}

			// nope
			if(!is_string($weaponset)){
				continue;
			}

			$weaponset = trim($weaponset);

			// skip empty
			if($weaponset == ''){
				continue;
			}

			// we're being generous and just skip invalid items
			try{
				$weaponset = $this->checkCharacterSet($weaponset);
			}
			catch(Throwable){
				continue;
			}

			$normalizedWeaponsets[$i] = $weaponset;

			$i++;
		}


		return $normalizedWeaponsets;
	}

	/**
	 * Checks if the given string is a valid base64 string
	 */
	private function checkCharacterSet(string $base64):string{
		$base64 = str_replace('=', '', $base64);

		if(!preg_match('#^['.self::BASE64.']*$#', $base64)){
			throw new InvalidArgumentException('Base64 must match RFC3548 character set');
		}

		return $base64;
	}

}
