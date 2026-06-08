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
use function array_unshift;
use function implode;
use function intdiv;
use function is_string;
use function str_replace;
use function str_split;
use function str_starts_with;
use function strlen;
use function strpos;
use function strrpos;
use function substr;
use function trim;

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

	/**
	 * @var array{skills: string, equipment:string, weaponsets: string[], player: string, description: string}[]
	 */
	private array $builds = [];

	/**
	 * Decodes the given paw-ned² template into an array
	 *
	 * @return array{skills: string, equipment:string, weaponsets: string[], player: string, description: string, flags: string}[]
	 */
	public function decode(string $pwnd):array{
		$pwnd  = str_replace(["\r", "\n"], '', trim($pwnd));
		$start = strrpos($pwnd, '>');
		$_end  = strpos($pwnd, '<', $start);
		$end   = ($_end - 1);

		if(!str_starts_with($pwnd, 'pwnd000') || $start === false || $_end === false || $end <= $start){
			throw new InvalidArgumentException('invalid paw-ned² template');
		}

#		$header = substr($pwnd, 0, $start);
		$b64    = str_replace(' ', '+', substr($pwnd, ($start + 1), ($end - $start)));
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
			// (i think it's additional skill points and pcons in the UI)
			$build['flags']  = $read($this->base64_ord($read(1)));
			$player_length   = $this->base64_ord($read(1));
			$build['player'] = $this->base64decode($read($player_length));

			$desc_length  = ($this->base64_ord($read(1)) * 64);
			$desc_length += $this->base64_ord($read(1));

			$build['description'] = $this->base64decode($read($desc_length));

			$builds[] = $build;
		}

		return $builds;
	}

	/**
	 * Encodes the given build(s) into a paw-ned² template
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
			'equipment'   => $this->checkCharacterSet(($equipment ?? '')),
			'weaponsets'  => $this->normalizeWeaponsets($weaponsets),
			'player'      => $this->base64encode(($player ?? '')),
			'description' => $this->base64encode(($description ?? "\r\n")),
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
	 *
	 * @param  string[] $weaponsets
	 * @return string[]
	 */
	private function normalizeWeaponsets(array $weaponsets):array{
		$normalizedWeaponsets = ['', '', ''];

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
