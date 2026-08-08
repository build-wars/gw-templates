/**
 * @created      12.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

export default class PHPJS{

	/**
	 * not an exact implementation, we're ignoring the $start_index parameter, which is always 0 here
	 *
	 * @param {number|int} $count
	 * @param {*} $value
	 * @returns {Array}
	 */
	static array_fill($count, $value){
		let arr = [];

		for(let key = 0; key < $count; key++){
			arr[key] = structuredClone($value);
		}

		return arr;
	}


	/**
	 * @link https://locutus.io/php/array_sum/
	 *
	 * @param {{}|[]} array
	 * @returns {number}
	 */
	static array_sum(array){
		let sum = 0;

		if(array === null){
			return 0;
		}

		if(typeof array !== 'object'){
			array = {};
		}

		for(let value of Object.values(array)){
			let parsed = value;

			if(typeof value === 'boolean'){
				parsed = value ? 1 : 0;
			}
			else if(value === null || value === undefined){
				parsed = 0;
			}
			else if(typeof value !== 'number'){
				parsed = parseFloat(String(value));
			}

			if(!isNaN(parsed)){
				sum += parsed;
			}
		}

		return sum;
	}

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
