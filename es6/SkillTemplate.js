/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

import TemplateAbstract from './TemplateAbstract.js';
import {ATTR_TO_PROF, PROF_TO_PRI, TEMPLATE_SKILL_NEW} from './constants.js';
import PHPJS from './PHPJS.js';

/**
 * @link https://wiki.guildwars.com/wiki/Skill_template_format
 *
 * @final
 */
export default class SkillTemplate extends TemplateAbstract{

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
	 * @param {string} $template
	 * @returns {*}
	 */
	decode($template){
		let bin    = this.decodeTemplate($template);
		let offset = 0;

		let read = ($length) => this.bindec_flip(bin.substring(offset, (offset += $length)));

		// profession length code, seems to be unused and will always be 00
		let pl    = read(2);
		// primary profession id
		let pri   = read(4);
		// secondary profession id
		let sec   = read(4);
		// attribute count
		let attrc = read(4);
		// attribute id length code
		let attrl = (read(4) + 4);

		let attributes = {};

		// get the attributes
		for(let i = 0; i < attrc; i++){
			attributes[read(attrl)] = read(4);
		}

		// get the skillbar
		let skill_id_len = (read(4) + 8);
		let skills       = [0, 0, 0, 0, 0, 0, 0, 0].map(() => read(skill_id_len));

		return {code: $template, prof_pri: pri, prof_sec: sec, attributes: attributes, skills: skills};
	}

	/**
	 * Encodes the given values into a skill template code
	 *
	 * @param {number|int} $prof_pri
	 * @param {number|int} $prof_sec
	 * @param {*} $attributes
	 * @param {number[]} $skills
	 * @returns {string}
	 */
	encode($prof_pri, $prof_sec, $attributes, $skills){
		[$prof_pri, $prof_sec] = this.normalizeProfessions($prof_pri, $prof_sec);
		$attributes            = this.normalizeAttributes($attributes, $prof_pri, $prof_sec);
		$skills                = this.normalizeSkills($skills);

		// start of the binary string:
		// type (14,4)
		let $bin = this.decbin_pad(TEMPLATE_SKILL_NEW, 4);
		// version (0,4)
		$bin += this.decbin_pad(0, 4);
		// profession length code (0,2)
		$bin += this.decbin_pad(0, 2);
		// add professions
		$bin += this.decbin_pad($prof_pri, 4);
		$bin += this.decbin_pad($prof_sec, 4);
		// add attribute count
		let attributeIDs = Object.keys($attributes);
		$bin += this.decbin_pad(attributeIDs.length, 4);
		// get attribute pad size
		let $attr_pad = this.getPadSize(attributeIDs, 5);

		// add attribute length code
		$bin += this.decbin_pad(($attr_pad - 4), 4);

		// add attribute ids and corresponding values
		for(let id in $attributes){
			$bin += this.decbin_pad(PHPJS.intval(id), $attr_pad);
			$bin += this.decbin_pad($attributes[id], 4);
		}

		// get skill pad size
		let $skill_pad = this.getPadSize($skills, 10);
		// add skill length code
		$bin += this.decbin_pad(($skill_pad - 8), 4);
		// add skill ids
		for(let id of $skills){
			$bin += this.decbin_pad(id, $skill_pad);
		}

		return this.encodeTemplate($bin);
	}

	/**
	 * Clamps the given profession IDs
	 *
	 * @param {number|int} $pri
	 * @param {number|int} $sec
	 * @returns {number[]}
	 * @private
	 */
	normalizeProfessions($pri, $sec){

		if(PROF_TO_PRI[$pri.toString()] === undefined){
			$pri = 0;
		}

		if(PROF_TO_PRI[$sec.toString()] === undefined || $sec === $pri){
			$sec = 0;
		}

		return [$pri, $sec];
	}

	/**
	 * Clamps the given set of attributes
	 *
	 * @link https://wiki.guildwars.com/wiki/Skill_template_format#Attribute_index
	 *
	 * @param {*} $attributes
	 * @param {number|int} $pri
	 * @param {number|int} $sec
	 * @private
	 */
	normalizeAttributes($attributes, $pri, $sec){
		let normalizedAttributes = {};

		for(let id in $attributes){

			// exclude invalid attributes
			if(ATTR_TO_PROF[id] === undefined){
				continue;
			}

			let profession = ATTR_TO_PROF[id];

			// attribute profession is neither primary or secondary
			if(profession !== $pri && profession !== $sec){
				continue;
			}

			// primary attribute of secondary profession
			let sec = $sec.toString(); // object key weirdness

			if(PROF_TO_PRI[sec] !== undefined && profession === PROF_TO_PRI[sec]){
				continue;
			}

			// clamp attribute levels
			normalizedAttributes[id] = Math.max(0, Math.min($attributes[id], 12));
		}

		return normalizedAttributes;
	}

	/**
	 * Clamps the given set of skill IDs
	 *
	 * @link https://wiki.guildwars.com/wiki/Guild_Wars_Wiki:Game_integration/Skills
	 *
	 * @param {number[]} $skills
	 * @returns {number[]}
	 * @private
	 */
	normalizeSkills($skills){
		let normalizedSkills = [0, 0, 0, 0, 0, 0, 0, 0];

		let i = 0;

		for(let skill of $skills){

			// stop at 8 skills
			if(i > 7){
				break;
			}

			// you don't belong here
			if(typeof skill !== 'number'){
				continue;
			}

			skill = PHPJS.intval(skill);

			// the highest known skill ID is currently 3431
			if(skill > 0 && skill < 0xfff){
				normalizedSkills[i] = skill;
			}

			i++;
		}

		return normalizedSkills;
	}

}
