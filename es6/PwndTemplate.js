/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

import TemplateAbstract from './TemplateAbstract.js';
import PHPJS from './PHPJS.js';

const PWND_PREFIX = 'pwnd0001';
const PWND_HEADER = 'pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates';

/**
 * Encodes and decodes paw·ned² team build templates
 *
 * Thanks to Antodias (formerly gwcom.de)
 *
 * @link https://memorial.redeemer.biz/pawned2/
 *
 * @final
 */
export default class PwndTemplate extends TemplateAbstract{

	builds = [];

	/**
	 * Decodes the given paw-ned² template into an array
	 *
	 * @returns {{}[]}
	 */
	decode($pwnd){
		$pwnd = $pwnd.trim().replace(/[\r\n]/g, '');
		let start = $pwnd.indexOf('>');
		let end   = $pwnd.indexOf('<', start);

		if(end <= start || $pwnd.substring(0, 7) !== 'pwnd000'){
			throw new Error('invalid pwnd template');
		}

//		let header = $pwnd.substring(0, start);
		let b64    = $pwnd.substring(start + 1, end).replace(' ', '+');
		let builds = [];
		let offset = 0;

		while(offset < b64.length){
			let build = {skills: '', equipment: '', weaponsets: [], player: '', description: '', flags: ''};
			let length;

			length = this.base64_ord(b64.substring(offset, (offset += 1)));
			build.skills = b64.substring(offset, (offset += length));

			length = this.base64_ord(b64.substring(offset, (offset += 1)));
			build.equipment = b64.substring(offset, (offset += length));

			for(let i = 0; i < 3; i++){
				length = this.base64_ord(b64.substring(offset, (offset += 1)));
				build.weaponsets[i] = b64.substring(offset, (offset += length));
			}

			length = this.base64_ord(b64.substring(offset, (offset += 1)));
			// nobody knows what the flags are or how they're encoded, so we may as well ignore them
			// (i think it's additional skill points in the UI)
			build.flags = b64.substring(offset, (offset += length));

			length = this.base64_ord(b64.substring(offset, (offset += 1)));
			build.player = atob(b64.substring(offset, (offset += length)));

			length  = this.base64_ord(b64.substring(offset, (offset += 1))) * 64;
			length += this.base64_ord(b64.substring(offset, (offset += 1)));
			build.description = atob(b64.substring(offset, (offset += length)));

			builds.push(build);
		}

		return builds;
	}

	/**
	 * Encodes the given build(s) into a pwnd template
	 *
	 * @returns {string}
	 */
	encode(){
		let pwnd = '';

		for(let build of this.builds){
			pwnd += this.base64_chr(build.skills.length);
			pwnd += build.skills;

			pwnd += this.base64_chr(build.equipment.length);
			pwnd += build.equipment;

			for(let weaponset of build.weaponsets){
				pwnd += this.base64_chr(weaponset.length);
				pwnd += weaponset;
			}

			pwnd += this.base64_chr(0); // we're setting the flags to zero-length
//			pwnd += ''; // noop

			pwnd += this.base64_chr(build.player.length);
			pwnd += build.player;

			pwnd += this.base64_chr(Math.floor(build.description.length / 64));
			pwnd += this.base64_chr(build.description.length % 64);
			pwnd += build.description;
		}

		pwnd = PHPJS.str_split(`>${pwnd}<`, 80).join('\r\n');

		return `${PWND_PREFIX}?${PWND_HEADER}\r\n${pwnd}`;
	}

	/**
	 * Adds a build item
	 *
	 * @param {string} $skills
	 * @param {string|null} $equipment
	 * @param {string[]} $weaponsets
	 * @param {string|null} $player
	 * @param {string|null} $description
	 * @returns {PwndTemplate}
	 */
	addBuild($skills, $equipment = null, $weaponsets = [], $player = null, $description = null){

		this.builds.push({
			skills     : this.checkCharacterSet($skills),
			equipment  : this.checkCharacterSet($equipment ?? ''),
			weaponsets : this.normalizeWeaponsets($weaponsets),
			player     : this.base64encode($player ?? ''),
			description: this.base64encode($description ?? '\r\n'),
		});

		return this;
	}

	/**
	 * Clears all currently added build items
	 *
	 * @returns {PwndTemplate}
	 */
	clearBuilds(){
		this.builds = [];

		return this;
	}

	/**
	 * Checks/normalizes the given weapon sets, limits input to 3 items
	 *
	 * @param {string[]} $weaponsets
	 * @private
	 */
	normalizeWeaponsets($weaponsets){
		let normalizedWeaponsets = ['', '', ''];

		let i = 0;

		for(let weaponset of $weaponsets){

			if(i > 2){
				break;
			}

			// nope
			if(typeof weaponset !== 'string'){
				continue;
			}

			weaponset = weaponset.trim();

			// skip empty
			if(weaponset === ''){
				continue;
			}

			// we're being generous and just skip invalid items
			try{
				weaponset = this.checkCharacterSet(weaponset);
			}
			catch(e){
				continue;
			}

			normalizedWeaponsets[i] = weaponset;

			i++;
		}

		return normalizedWeaponsets;
	}

	/**
	 * Encode a string to base64
	 *
	 * @param {string} $string
	 * @returns {string}
	 * @private
	 */
	base64encode($string){
		return btoa($string).replace(/=+$/, '');
	}

}
