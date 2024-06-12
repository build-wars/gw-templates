/**
 * @created      12.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

export default class PHPJS{

	/**
	 * @link  https://locutus.io/php/var/intval/
	 *
	 * @param {*} $var
	 * @param {number|null} $base
	 * @returns {number|int}
	 */
	static intval($var, $base = null){
		let tmp;
		let type = typeof $var;

		if(type === 'boolean'){
			return +$var;
		}

		if(type === 'string'){
			tmp = parseInt($var, $base || 10);

			return (isNaN(tmp) || !isFinite(tmp)) ? 0 : tmp;
		}

		if(type === 'number' && isFinite($var)){
			return $var|0;
		}

		return 0;
	}

	/**
	 * @link https://locutus.io/php/strings/str_split/
	 *
	 * @param {string} string
	 * @param {number|int} splitLength
	 * @returns {string[]}
	 */
	static str_split(string, splitLength){
		let type = typeof string;

		if(type === 'number' || type === 'bigint'){
			string += '';
		}
		else if(type !== 'string'){
			throw new Error('invalid string');
		}

		if(splitLength === undefined){
			splitLength = 1;
		}

		if(typeof splitLength !== 'number' || splitLength < 1){
			throw new Error('invalid split length');
		}

		let chunks = [];
		let pos    = 0;

		while(pos < string.length){
			chunks.push(string.slice(pos, (pos += splitLength)));
		}

		return chunks;
	}

}
