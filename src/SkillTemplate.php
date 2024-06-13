<?php
/**
 * Class SkillTemplate
 *
 * @created      21.05.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace BuildWars\GWTemplates;

use function array_fill;
use function array_keys;
use function array_map;
use function intval;
use function is_numeric;
use function max;
use function min;
use function substr;

/**
 * @link https://wiki.guildwars.com/wiki/Skill_template_format
 */
final class SkillTemplate extends TemplateAbstract{

	/**
	 * profession id => primary attribute id
	 *
	 * @var int[]
	 */
	public const PROF_TO_PRI = [
		1  => 17,
		2  => 23,
		3  => 16,
		4  =>  6,
		5  =>  0,
		6  => 12,
		7  => 35,
		8  => 36,
		9  => 40,
		10 => 44,
	];

	/**
	 * attribute id => profession id
	 *
	 * @var int[]
	 */
	public const ATTR_TO_PROF = [
		0  => 5,
		1  => 5,
		2  => 5,
		3  => 5,
		4  => 4,
		5  => 4,
		6  => 4,
		7  => 4,
		8  => 6,
		9  => 6,
		10 => 6,
		11 => 6,
		12 => 6,
		13 => 3,
		14 => 3,
		15 => 3,
		16 => 3,
		17 => 1,
		18 => 1,
		19 => 1,
		20 => 1,
		21 => 1,
		22 => 2,
		23 => 2,
		24 => 2,
		25 => 2,
		29 => 7,
		30 => 7,
		31 => 7,
		32 => 8,
		33 => 8,
		34 => 8,
		35 => 7,
		36 => 8,
		37 => 9,
		38 => 9,
		39 => 9,
		40 => 9,
		41 => 10,
		42 => 10,
		43 => 10,
		44 => 10,
	];

	/**
	 * Decodes the given skill template into an array
	 *
	 *   array{
	 *     code:       string,
	 *     prof_pri:   int,
	 *     prof_sec:   int,
	 *     attributes: array<int, int>,
	 *     skills:     int[]
	 *   }
	 *
	 */
	public function decode(string $template):array{
		$bin    = $this->decodeTemplate($template);
		$offset = 0;

		$read = function(int $length) use ($bin, &$offset):int{
			$dec     = $this->bindec_flip(substr($bin, $offset, $length));
			$offset += $length;

			return $dec;
		};

		// profession length code, seems to be unused and will always be 00
		$pl    = $read(2);
		// primary profession id
		$pri   = $read(4);
		// secondary profession id
		$sec   = $read(4);
		// attribute count
		$attrc = $read(4);
		// attribute id length code
		$attrl = ($read(4) + 4);

		$attributes = [];

		// get the attributes
		for($i = 0; $i < $attrc; $i++){
			$attributes[$read($attrl)] = $read(4);
		}

		// get the skillbar
		$skill_id_len = ($read(4) + 8);
		$skills       = array_map(fn(int $i):int => $read($skill_id_len), array_fill(0, 8, 0));

		return ['code' => $template, 'prof_pri' => $pri, 'prof_sec' => $sec, 'attributes' => $attributes, 'skills' => $skills];
	}

	/**
	 * Encodes the given values into a skill template code
	 *
	 * @param array<int, int> $attributes
	 * @param int[]           $skills
	 */
	public function encode(int $prof_pri, int $prof_sec, array $attributes, array $skills):string{
		[$prof_pri, $prof_sec] = $this->normalizeProfessions($prof_pri, $prof_sec);
		$attributes            = $this->normalizeAttributes($attributes, $prof_pri, $prof_sec);
		$skills                = $this->normalizeSkills($skills);

		// start of the binary string:
		// type (14,4)
		$bin = $this->decbin_pad(self::TEMPLATE_SKILL_NEW, 4);
		// version (0,4)
		$bin .= $this->decbin_pad(0, 4);
		// profession length code (0,2)
		$bin .= $this->decbin_pad(0, 2);
		// add professions
		$bin .= $this->decbin_pad($prof_pri, 4);
		$bin .= $this->decbin_pad($prof_sec, 4);
		// add attribute count
		$bin .= $this->decbin_pad(count($attributes), 4);
		// get attribute pad size
		$attr_pad = $this->getPadSize(array_keys($attributes), 5);

		// add attribute length code
		$bin .= $this->decbin_pad(($attr_pad - 4), 4);

		// add attribute ids and corresponding values
		foreach($attributes as $id => $level){
			$bin .= $this->decbin_pad($id, $attr_pad);
			$bin .= $this->decbin_pad($level, 4);
		}

		// get skill pad size
		$skill_pad = $this->getPadSize($skills, 10);
		// add skill length code
		$bin .= $this->decbin_pad(($skill_pad - 8), 4);
		// add skill ids
		foreach($skills as $id){
			$bin .= $this->decbin_pad($id, $skill_pad);
		}

		return $this->encodeTemplate($bin);
	}

	/**
	 * Clamps the given profession IDs
	 */
	private function normalizeProfessions(int $pri, int $sec):array{

		// invalid primary profession
		if(!isset(self::PROF_TO_PRI[$pri])){
			$pri = 0;
		}

		// invalid secondary profession or secondary profession is same as primary
		if(!isset(self::PROF_TO_PRI[$sec]) || $sec === $pri){
			$sec = 0;
		}

		return [$pri, $sec];
	}

	/**
	 * Clamps the given set of attributes
	 *
	 * @link https://wiki.guildwars.com/wiki/Skill_template_format#Attribute_index
	 */
	private function normalizeAttributes(array $attributes, int $pri, int $sec):array{
		$normalizedAttributes = [];

		foreach($attributes as $id => $level){

			// exclude invalid attributes
			if(!isset(self::ATTR_TO_PROF[$id])){
				continue;
			}

			$profession = self::ATTR_TO_PROF[$id];

			// attribute profession is neither primary or secondary
			if($profession !== $pri && $profession !== $sec){
				continue;
			}

			// primary attribute of secondary profession
			if(isset(self::PROF_TO_PRI[$sec]) && $profession === self::PROF_TO_PRI[$sec]){
				continue;
			}

			// clamp attribute levels
			$normalizedAttributes[$id] = max(0, min($level, 12));
		}

		return $normalizedAttributes;
	}

	/**
	 * Clamps the given set of skill IDs
	 *
	 * @link https://wiki.guildwars.com/wiki/Guild_Wars_Wiki:Game_integration/Skills
	 */
	private function normalizeSkills(array $skills):array{
		$normalizedSkills = array_fill(0, 8, 0);

		$i = 0;

		foreach($skills as $skill){

			// stop at 8 skills
			if($i > 7){
				break;
			}

			// you don't belong here
			if(!is_numeric($skill)){
				continue;
			}

			$skill = intval($skill);

			// the highest known skill ID is currently 3431
			if($skill > 0 && $skill < 0xfff){
				$normalizedSkills[$i] = $skill;
			}

			$i++;
		}

		return $normalizedSkills;
	}

}
