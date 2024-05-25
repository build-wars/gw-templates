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

use RuntimeException;
use function array_fill;
use function array_keys;
use function intval;
use function is_numeric;
use function max;
use function min;
use function preg_match;
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
		0  => -1,
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
		$bin = $this->decodeTemplate($template);

		// try to read the profession and attribute info
		// (pl, profession length code, seems to be unused yet and will always be 00)
		if(!preg_match('/^(?P<pl>[01]{2})(?P<pri>[01]{4})(?P<sec>[01]{4})(?P<attrc>[01]{4})(?P<attrl>[01]{4})/', $bin, $data)){
			throw new RuntimeException('invalid skill template');
		}

		// cut 2+4+4+4+4 bits just matched
		$bin = substr($bin, 18);

		// get the attributes
		$attributeCount  = $this->bindec_flip($data['attrc']);
		$attributeLength = ($this->bindec_flip($data['attrl']) + 4);

		$attributes = [];

		for($i = 0; $i < $attributeCount; $i++){

			if(!preg_match('/^(?P<id>[01]{'.$attributeLength.'})(?P<val>[01]{4})/', $bin, $attribute)){
				throw new RuntimeException('invalid attributes');
			}

			// cut the current attribute's bits
			$bin = substr($bin, ($attributeLength + 4));

			$attributes[$this->bindec_flip($attribute['id'])] = $this->bindec_flip($attribute['val']);
		}

		// get the skillbar
		if(!preg_match('/^(?P<length>[01]{4})/', $bin, $skill)){
			throw new RuntimeException('invalid skill length bits');
		}

		// cut skill length bits
		$bin = substr($bin, 4);

		$skillLength = ($this->bindec_flip($skill['length']) + 8);

		$skills = [];

		for($i = 0; $i < 8; $i++){

			if(!preg_match('/^(?P<id>[01]{'.$skillLength.'})/', $bin, $skill)){
				throw new RuntimeException('invalid skill id');
			}

			// cut current skill's bits
			$bin = substr($bin, $skillLength);

			$skills[$i] = $this->bindec_flip($skill['id']);
		}

		return [
			'code'       => $template,
			'prof_pri'   => $this->bindec_flip($data['pri']),
			'prof_sec'   => $this->bindec_flip($data['sec']),
			'attributes' => $attributes,
			'skills'     => $skills,
		];
	}

	/**
	 * Encodes the given values into a skill template code
	 *
	 * @param array<int, int> $attributes
	 * @param int[]           $skills
	 */
	public function encode(int $prof_pri, int $prof_sec, array $attributes, array $skills):string{
		[$prof_pri, $prof_sec] = $this->normalizeProfessions($prof_pri, $prof_sec);
		$attributes  = $this->normalizeAttributes($attributes, $prof_pri, $prof_sec);
		$skills      = $this->neormalizeSkills($skills);

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

		$attr_pad = $this->getPadSize(array_keys($attributes), 5);

		// add attribute length code
		$bin .= $this->decbin_pad(($attr_pad - 4), 4);

		// add attribute ids and corresponding values
		foreach($attributes as $id => $level){
			$bin .= $this->decbin_pad($id, $attr_pad);
			$bin .= $this->decbin_pad($level, 4);
		}

		$skill_pad = $this->getPadSize($skills, 9);

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

		// invalid secondarc profession or secondary profession is same as primary
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
			if($profession === self::PROF_TO_PRI[$sec]){
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
	private function neormalizeSkills(array $skills):array{
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
