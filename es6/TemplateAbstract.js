/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
import PHPJS from './PHPJS.js';

/**
 * Abstract Guild Wars template encoding/decoding
 *
 * @abstract
 */
export default class TemplateAbstract{

	get TEMPLATE_SKILL_OLD    (){return 0b0000}
	get TEMPLATE_SKILL_NEW    (){return 0b1110}
	get TEMPLATE_EQUIPMENT_OLD(){return 0b0001}
	get TEMPLATE_EQUIPMENT_NEW(){return 0b1111}

	/**
	 * @type {number}
	 * @protected
	 */
	_offset = 0;

	/**
	 * @type {string}
	 * @protected
	 */
	_string = '';

	/**
	 * Reverses the given binary number string and converts it to an integer
	 *
	 * @param {string} $bin
	 * @returns {number|int}
	 * @protected
	 */
	_bindec_flip($bin){
		return PHPJS.intval($bin.split('').reverse().join(''), 2);
	}

	/**
	 * Converts the given integer into a binary number string and reverses it
	 *
	 * @param {number|int} $dec
	 * @returns {string}
	 * @protected
	 */
	_decbin_flip($dec){
		return ($dec >>> 0).toString(2).split('').reverse().join('');
	}

	/**
	 * Converts the given integer into a binary number string, reverses it,
	 * and adds the given amount of zero padding to the right
	 *
	 * @param {number|int} $dec
	 * @param {number|int} $padding
	 * @returns {string}
	 * @protected
	 */
	_decbin_pad($dec, $padding){
		return this._decbin_flip($dec).padEnd($padding, '0');
	}

	/**
	 * Checks if the given string is a valid base64 string
	 *
	 * @param {string} $base64
	 * @returns {string}
	 * @throws {Error}
	 * @protected
	 */
	_checkCharacterSet($base64){
		// nasty fix for urlencode and padded strings
		$base64 = $base64.trim().replaceAll(' ', '+').replaceAll('=', '');

		if($base64 === ''){
			return '';
		}

		// noinspection RegExpRedundantEscape
		if($base64.match(/^[A-Za-z0-9\+\/]*$/) === null){
			throw new Error('Base64 must match RFC3548 character set');
		}

		return $base64;
	}

	/**
	 * Determines the minimum pad size
	 *
	 * @param {number[]} $nums
	 * @param {number|int} $min_pad
	 * @returns {number|int}
	 * @protected
	 */
	_getPadSize($nums, $min_pad){

		for(let num of $nums){
			if(PHPJS.intval(num) >= (2 ** $min_pad)){
				$min_pad++;
			}
		}

		return $min_pad;
	}

	/**
	 * Decodes a string from unpadded base64
	 *
	 * @param {string} $base64
	 * @returns {string}
	 * @protected
	 */
	_base64decode($base64){
		$base64 = this._checkCharacterSet($base64);

		// we're gonna add zeroes until the bit count is divisible by 8
		while(($base64.length % 8) !== 0){
			$base64 += 'A';
		}

		return atob($base64);
	}

	/**
	 * Encodes a string into unpadded base64
	 *
	 * @param {Uint8Array|string} $string
	 * @returns {string}
	 * @protected
	 */
	_base64encode($string){

		let b64 = ($string instanceof Uint8Array)
			? $string.toBase64()
			: btoa($string);

		return b64.replaceAll('=', '');
	}

	/**
	 * Reads the given amount of bits from the set string
	 *
	 * @param {number|int} $length
	 * @returns {number|int}
	 * @protected
	 */
	_read($length){
		return this._bindec_flip(this._string.substring(this._offset, (this._offset += $length)));
	}

	/**
	 * Decodes a template from the base64 format into a binary number (base2) string
	 *
	 * @param {string} $base64
	 * @returns {string}
	 * @protected
	 */
	_decodeTemplate($base64){

		if($base64 === ''){
			throw new Error('invalid base64 template');
		}

		// decode the template into 8-bit characters (unsigned char)
		let chars = this._base64decode($base64);
		let base2 = this._decodeBinaryToBase2(chars);
		// get the first 4 bits and decide what to do
		switch(this._bindec_flip(base2.substring(0, 4))){
			// new format, remove leading template type and version number
			case this.TEMPLATE_SKILL_NEW:
			case this.TEMPLATE_EQUIPMENT_NEW:
				return base2.substring(8);
			// old format prior to April 5, 2007, remove version number
			case this.TEMPLATE_SKILL_OLD:
			case this.TEMPLATE_EQUIPMENT_OLD:
				return base2.substring(4);
		}

		throw new Error('invalid template');
	}

	/**
	 * Encodes a binary number (base2) template to base64 format
	 *
	 * @param {string} $base2
	 * @returns {string}
	 * @throws {Error}
	 * @protected
	 */
	_encodeTemplate($base2){

		if($base2 === ''){
			throw new Error('invalid binary template');
		}

		let $bin8 = this._encodeBase2ToBinary($base2);
		// convert to base64
		return this._base64encode($bin8);
	}

	/**
	 * Decodes the given raw 8-bit binary (unsigned char) string from the decoded base64
	 * into a base2 string suitable for reading the template data.
	 *
	 * @see https://wiki.guildwars.com/wiki/Talk:Skill_template_format#I_don't_get_it
	 *
	 * @param {string} $chars
	 * @protected
	 */
	_decodeBinaryToBase2($chars){
		// unpack the string from unsigned char
		let $uint8 = $chars.split('').map(c => c.charCodeAt(0));
		// base convert 10 to 2 (8 bits each value, zero padded to the left)
		let $bin8  = $uint8.map(o => o.toString(2).padStart(8, '0')).join('');
		// now split the string into chunks of 6 bits and reverse each chunk
		let $bin6  = PHPJS.str_split($bin8, 6).map(c => c.split('').reverse().join(''));
		// glue the string back together and return the result
		return $bin6.join('');
	}

	/**
	 * Encodes the given base2 template data string into an 8-bit binary string.
	 *
	 * @param {string} $base2
	 * @protected
	 */
	_encodeBase2ToBinary($base2){
		// fill the string with zeroes until it is divisible by 6 and 8
		while($base2.length % 8 !== 0 || $base2.length % 6 !== 0){
			$base2 += '0';
		}
		// split into chunks of 6 and reverse each block
		let $bin6  = PHPJS.str_split($base2, 6).map(c => c.split('').reverse().join(''));
		// split the string into chunks of 8 and base convert each chunk from 2 to 10
		let $uint8 = PHPJS.str_split($bin6.join(''), 8).map(b => PHPJS.intval(b, 2));
		// convert the uint8 into an 8-bit binary string (unsigned char)
		return $uint8.map(o => String.fromCharCode(o)).join('');
	}

}
