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
use function array_unshift;
use function implode;
use function intdiv;
use function is_string;
use function sodium_base642bin;
use function sodium_bin2base64;
use function str_replace;
use function str_split;
use function str_starts_with;
use function strlen;
use function strpos;
use function strrpos;
use function substr;
use function trim;
use const SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING;

/**
 * Encodes and decodes paw·ned² team build templates
 *
 * Thanks to Antodias (formerly gwcom.de)
 *
 * @link https://memorial.redeemer.biz/pawned2/
 */
final class PwndTemplate extends TemplateAbstract{

	private const PWND_PREFIX = 'pwnd0001';
	private const PWND_HEADER = 'pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates';

	private array $builds = [];

	/**
	 * Decodes the given paw-ned² template into an array
	 */
	public function decode(string $pwnd):array{
		$pwnd  = str_replace(["\r", "\n"], '', trim($pwnd));
		$start = strrpos($pwnd, '>');
		$end   = strpos($pwnd, '<', $start) - 1;

		if($end <= $start || !str_starts_with($pwnd, 'pwnd000')){
			throw new InvalidArgumentException('invalid pwnd template');
		}

#		$header = substr($pwnd, 0, $start);
		$b64    = str_replace(' ', '+', substr($pwnd, $start + 1, $end - $start));
		$total  = strlen($b64);
		$builds = [];
		$offset = 0;

		$read = function(int $length) use ($b64, &$offset):string{
			$str     = substr($b64, $offset, $length);
			$offset += $length;

			return $str;
		};

		while($offset < $total){

			$build = [
				'skills'      => $read($this->base64_ord($read(1))),
				'equipment'   => $read($this->base64_ord($read(1))),
				'weaponsets'  => [],
				'player'      => '',
				'description' => '',
				'flags'       => '',
			];

			for($i = 0; $i < 3; $i++){
				$build['weaponsets'][$i] = $read($this->base64_ord($read(1)));
			}

			// nobody knows what the flags are or how they're encoded, so we may as well ignore them
			// (i think it's additional skill points in the UI)
			$build['flags']  = $read($this->base64_ord($read(1)));
			$build['player'] = sodium_base642bin($read($this->base64_ord($read(1))), SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);

			$length  = $this->base64_ord($read(1)) * 64;
			$length += $this->base64_ord($read(1));

			$build['description'] = sodium_base642bin($read($length), SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);

			$builds[] = $build;
		}

		return $builds;
	}

	/**
	 * Encodes the given build(s) into a pwnd template
	 */
	public function encode():string{
		$write = fn(string $str):string => $this->base64_chr(strlen($str)).$str;
		$pwnd  = '';

		foreach($this->builds as $build){
			$pwnd .= $write($build['skills']);
			$pwnd .= $write($build['equipment']);

			foreach($build['weaponsets'] as $set){
				$pwnd .= $write($set);
			}

			$pwnd .= $write(''); // we're setting the flags to zero-length
			$pwnd .= $write($build['player']);

			$pwnd .= $this->base64_chr(intdiv(strlen($build['description']), 64));
			$pwnd .= $this->base64_chr(strlen($build['description']) % 64);
			$pwnd .= $build['description'];
		}

		$pwnd = str_split('>'.$pwnd.'<', 80);

		array_unshift($pwnd, self::PWND_PREFIX.'?'.self::PWND_HEADER);

		return implode("\r\n", $pwnd);
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
			if($weaponset === ''){
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

}
