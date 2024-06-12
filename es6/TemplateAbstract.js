/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
import {TEMPLATE_EQUIPMENT_NEW, TEMPLATE_EQUIPMENT_OLD, TEMPLATE_SKILL_NEW, TEMPLATE_SKILL_OLD} from './constants.js';
import PHPJS from './PHPJS.js';

const BASE64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

/**
 * Abstract Guild Wars template encoding/decoding
 *
 * @abstract
 */
export default class TemplateAbstract{

	/**
	 * Reverses the given binary number string and converts it to an integer
	 *
	 * @param {string} $bin
	 * @returns {number|int}
	 * @protected
	 */
	bindec_flip($bin){
		return PHPJS.intval($bin.split('').reverse().join(''), 2);
	}

	/**
	 * Converts the given integer into a binary number string and reverses it
	 *
	 * @param {number|int} $dec
	 * @returns {string}
	 * @protected
	 */
	decbin_flip($dec){
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
	decbin_pad($dec, $padding){
		return this.decbin_flip($dec).padEnd($padding, '0');
	}

	/**
	 * Returns the ordinal for the given base64 character
	 *
	 * @param {string} $chr
	 * @returns {number|int}
	 * @protected
	 */
	base64_ord($chr){
		let $ord = BASE64.indexOf($chr);

		if($ord === -1){
			throw new Error('invalid base64 character');
		}

		return $ord;
	}

	/**
	 * Returns the base64 character for the given ordinal
	 *
	 * @param {number|int} $ord
	 * @returns {string}
	 * @protected
	 */
	base64_chr($ord){

		if($ord < 0 || $ord > 63){
			throw new Error('invalid base64 ordinal');
		}

		return BASE64.substring($ord, $ord + 1)
	}

	/**
	 * Determines the minimum pad size
	 *
	 * @param {number[]} $nums
	 * @param {number|int} $min_pad
	 * @returns {number|int}
	 */
	getPadSize($nums, $min_pad){

		for(let num of $nums){
			if(PHPJS.intval(num) >= Math.pow(2, $min_pad)){
				$min_pad++;
			}
		}

		return $min_pad;
	}

	/**
	 * Checks if the given string is a valid base64 string
	 *
	 * @param {string} $base64
	 * @returns {string}
	 * @protected
	 */
	checkCharacterSet($base64){
		$base64 = $base64.replace(/=+$/, '');

		if($base64.match(/^[A-Za-z0-9+\/]+$/) === null){
			throw new Error('Base64 must match RFC3548 character set');
		}

		return $base64;
	}

	/**
	 * Decodes a template from the base64 format into a binary number string
	 *
	 * @param {string} $template
	 * @returns {string}
	 * @protected
	 */
	decodeTemplate($template){
		// nasty fix for urlencode
		$template = $template.replace(' ', '+', $template.trim());

		if($template === ''){
			throw new Error('invalid base64 template');
		}

		let $bin = $template
			.split('')
			.map($char => this.decbin_flip(this.base64_ord($char)).padEnd(6, '0'))
			.join('')
		;

		switch(this.bindec_flip($bin.substring(0, 4))){
			case TEMPLATE_SKILL_NEW:
			case TEMPLATE_EQUIPMENT_NEW:
				return $bin.substring(8);
			case TEMPLATE_SKILL_OLD:
			case TEMPLATE_EQUIPMENT_OLD:
				return $bin.substring(4);
		}

		throw new Error('invalid template');
	}

	/**
	 * Encodes a binary number template to base64 format
	 *
	 * @param {string} $bin
	 * @returns {string}
	 * @protected
	 */
	encodeTemplate($bin){

		if($bin === ''){
			throw new Error('invalid binary template');
		}

		// fill the string with zeroes until it is divisible by 8 (PHP base64_decode compatibility)
		while($bin.length % 8 !== 0){
			$bin += '0';
		}

		return PHPJS.str_split($bin, 6)
			.map(this.bindec_flip)
			.map(this.base64_chr)
			.join('')
		;
	}

}
