'use strict';

var windows1252 = require('windows-1252');

function _interopNamespaceDefault(e) {
	var n = Object.create(null);
	if (e) {
		Object.keys(e).forEach(function (k) {
			if (k !== 'default') {
				var d = Object.getOwnPropertyDescriptor(e, k);
				Object.defineProperty(n, k, d.get ? d : {
					enumerable: true,
					get: function () { return e[k]; }
				});
			}
		});
	}
	n.default = e;
	return Object.freeze(n);
}

var windows1252__namespace = /*#__PURE__*/_interopNamespaceDefault(windows1252);

/**
 * @created      12.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

class PHPJS{

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

/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

/**
 * Abstract Guild Wars template encoding/decoding
 *
 * @abstract
 */
class TemplateAbstract{

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
	 * @param {string} $template
	 * @returns{*}
	 * @abstract
	 */
	decode($template){
		throw new Error('abstract nethod');
	}

	/**
	 * @param {string} $template
	 * @abstract
	 */
	static fromTemplate($template){
		// stackoverflow, how do i get the constructor name of a child class from a static method in the parent?
		// PHP: (new static)->decode()
		throw new Error('not dealing with this shit here');
	}

	/**
	 * @param {string} $chatCode
	 * @abstract
	 */
	static fromChatCode($chatCode){
		// PHP: static::fromTemplate()
		throw new Error('not dealing with this shit here');
	}

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

/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

/**
 * @link https://wiki.guildwars.com/wiki/Equipment_template_format
 *
 * @final
 */
class EquipmentTemplate extends TemplateAbstract{

	/**
	 * item id => equipment slot id
	 *
	 *   0 => 2-hand Weapon, 1-hand main
	 *   1 => Off-hand
	 *   2 => Chest
	 *   3 => Legs
	 *   4 => Head
	 *   5 => Feet
	 *   6 => Hands
	 */
	#ITEM_TO_SLOT = {
		'1'   : 5, '2'   : 5, '3'   : 5, '4'   : 5, '5'   : 5, '6'   : 5, '8'   : 5, '9'   : 5, '10'  : 5, '11'  : 5,
		'12'  : 5, '13'  : 5, '14'  : 5, '15'  : 5, '16'  : 5, '17'  : 5, '18'  : 5, '19'  : 5, '20'  : 5, '21'  : 5,
		'22'  : 2, '23'  : 2, '24'  : 2, '25'  : 2, '26'  : 2, '27'  : 2, '28'  : 2, '29'  : 2, '30'  : 2, '31'  : 2,
		'32'  : 2, '33'  : 2, '34'  : 2, '35'  : 2, '36'  : 2, '37'  : 2, '38'  : 2, '39'  : 2, '40'  : 2, '41'  : 2,
		'42'  : 6, '43'  : 6, '44'  : 6, '45'  : 6, '46'  : 6, '47'  : 6, '48'  : 6, '49'  : 6, '50'  : 6, '51'  : 6,
		'52'  : 6, '53'  : 6, '54'  : 6, '55'  : 6, '56'  : 6, '57'  : 6, '59'  : 6, '60'  : 6, '61'  : 6, '62'  : 6,
		'63'  : 4, '64'  : 4, '65'  : 4, '66'  : 4, '67'  : 4, '68'  : 4, '69'  : 4, '70'  : 4, '71'  : 4, '72'  : 4,
		'73'  : 4, '74'  : 4, '75'  : 4, '76'  : 4, '77'  : 4, '78'  : 4, '79'  : 4, '80'  : 4, '81'  : 4, '83'  : 4,
		'84'  : 4, '85'  : 4, '86'  : 4, '87'  : 4, '88'  : 4, '89'  : 4, '90'  : 3, '91'  : 3, '92'  : 3, '93'  : 3,
		'94'  : 3, '95'  : 3, '96'  : 3, '97'  : 3, '98'  : 3, '99'  : 3, '100' : 3, '101' : 3, '102' : 3, '103' : 3,
		'104' : 3, '105' : 3, '106' : 3, '107' : 3, '108' : 3, '109' : 3, '110' : 0, '111' : 0, '112' : 0, '113' : 0,
		'114' : 0, '115' : 0, '116' : 1, '117' : 1, '118' : 1, '119' : 1, '120' : 1, '121' : 1, '122' : 1, '123' : 1,
		'124' : 1, '125' : 1, '126' : 1, '127' : 1, '128' : 1, '129' : 1, '130' : 1, '131' : 1, '132' : 1, '133' : 0,
		'134' : 0, '135' : 0, '136' : 0, '137' : 0, '138' : 0, '139' : 0, '140' : 0, '141' : 0, '142' : 0, '143' : 0,
		'144' : 0, '145' : 1, '146' : 1, '147' : 0, '148' : 0, '149' : 0, '150' : 0, '151' : 0, '152' : 0, '153' : 0,
		'154' : 0, '155' : 0, '156' : 0, '157' : 0, '158' : 0, '159' : 5, '160' : 2, '161' : 6, '162' : 3, '163' : 5,
		'164' : 2, '165' : 6, '166' : 3, '167' : 5, '168' : 2, '169' : 6, '170' : 3, '171' : 5, '172' : 2, '173' : 6,
		'174' : 3, '175' : 5, '176' : 2, '177' : 6, '178' : 3, '179' : 5, '180' : 2, '181' : 6, '182' : 3, '183' : 5,
		'184' : 2, '185' : 6, '186' : 3, '187' : 5, '188' : 2, '189' : 6, '190' : 3, '191' : 5, '192' : 2, '193' : 6,
		'194' : 3, '195' : 5, '196' : 2, '197' : 6, '198' : 3, '199' : 5, '200' : 2, '201' : 6, '202' : 3, '203' : 5,
		'204' : 2, '205' : 6, '206' : 3, '207' : 5, '208' : 2, '209' : 6, '210' : 3, '211' : 5, '212' : 2, '213' : 6,
		'214' : 3, '215' : 5, '216' : 2, '217' : 6, '218' : 3, '219' : 5, '220' : 2, '221' : 6, '222' : 3, '223' : 5,
		'224' : 2, '225' : 6, '226' : 3, '227' : 5, '228' : 2, '229' : 6, '230' : 3, '231' : 5, '232' : 2, '233' : 6,
		'234' : 3, '235' : 5, '236' : 2, '237' : 6, '238' : 3, '239' : 5, '240' : 2, '241' : 6, '242' : 3, '243' : 5,
		'244' : 2, '245' : 6, '246' : 3, '247' : 5, '248' : 2, '249' : 6, '250' : 3, '251' : 5, '252' : 2, '253' : 6,
		'254' : 3, '255' : 5, '256' : 2, '257' : 6, '258' : 3, '259' : 5, '260' : 2, '261' : 6, '262' : 3, '263' : 5,
		'264' : 2, '265' : 6, '266' : 3, '267' : 5, '268' : 2, '269' : 6, '270' : 3, '271' : 4, '272' : 4, '273' : 4,
		'274' : 4, '275' : 4, '276' : 4, '277' : 4, '278' : 4, '279' : 0, '280' : 1, '281' : 1, '282' : 1, '283' : 1,
		'284' : 0, '285' : 0, '286' : 0, '287' : 0, '288' : 0, '289' : 0, '290' : 4, '291' : 4, '292' : 4, '293' : 4,
		'294' : 5, '295' : 2, '296' : 6, '297' : 3, '298' : 5, '299' : 2, '300' : 6, '301' : 3, '302' : 5, '303' : 2,
		'304' : 6, '305' : 3, '306' : 4, '307' : 4, '308' : 4, '309' : 4, '310' : 5, '311' : 2, '312' : 6, '313' : 3,
		'314' : 5, '315' : 2, '316' : 6, '317' : 3, '318' : 5, '319' : 2, '320' : 6, '321' : 3, '322' : 0, '323' : 1,
		'324' : 1, '325' : 0, '326' : 0, '327' : 0, '328' : 0, '329' : 0, '330' : 0, '331' : 0, '332' : 0, '333' : 0,
		'334' : 0, '335' : 0, '336' : 0, '337' : 0, '338' : 0, '339' : 0,
	};

	/**
	 * Item colors
	 */
	#ITEM_COLORS = {
		'0': 'default', '2': 'blue',  '3': 'green',  '4': 'purple', '5': 'red',
		'6': 'yellow',  '7': 'brown', '8': 'orange', '9': 'grey',
	};

	/**
	 * @type {{id: int, slot: int, color: int, mods: int[]}[]}
	 */
	#items = {};

	static fromTemplate($template){
		return new EquipmentTemplate().decode($template);
	}

	static fromChatCode($chatCode){
		// noinspection RegExpRedundantEscape
		let match = $chatCode.trim().match(/^\[(?<name>[^;]*);(?<code>[A-Za-z0-9\+\/ ]+)\]$/);

		if(match === null || !match.groups){
			throw new Error('invalid chat code');
		}

		return EquipmentTemplate.fromTemplate(match.groups.code);
	}

	/**
	 * Decodes the given equipment template into an array
	 *
	 * @param {string} $template
	 * @returns {{id: number, slot: number, color: number, mods: number[]}[]}
	 */
	decode($template){
		this._string = this._decodeTemplate($template);
		this._offset = 0;
		this.#items  = {};

		// get item id length code, mod id length code and item count
		let item_id_length = this._read(4);
		let mod_id_length  = this._read(4);
		let item_count     = this._read(3);

		// loop through the items
		for(let i = 0; i < item_count; i++){
			// get item type, id, number of mods and item color
			let slot      = this._read(3);
			let id        = this._read(item_id_length);
			let mod_count = this._read(2);
			let color     = this._read(4);

			// loop through the mods
			let mods = [];

			for(let j = 0; j < mod_count; j++){
				mods.push(this._read(mod_id_length));
			}

			this.#items[String(slot)] = {id: id, slot: slot, color: color, mods: mods};
		}

		return this.#items;
	}

	/**
	 * Encodes the currently added equipment items into a template code
	 *
	 * @returns {string}
	 */
	encode(){
		// start of the binary string:
		// type (15,4)
		let bin  = this._decbin_pad(this.TEMPLATE_EQUIPMENT_NEW, 4);
		// version (0,4)
		bin += this._decbin_pad(0, 4);

		let itemIDs = [];
		let modIDs  = [];

		for(let slot in this.#items){
			itemIDs.push(this.#items[slot].id);
			modIDs = modIDs.concat(this.#items[slot].mods);
		}

		let item_length = this._getPadSize(itemIDs, 8);
		let mod_length  = this._getPadSize(modIDs, 8);

		// add length codes and item count
		bin += this._decbin_pad(item_length, 4);
		bin += this._decbin_pad(mod_length, 4);
		bin += this._decbin_pad(Object.keys(this.#items).length, 3);

		for(let slot in this.#items){
			bin += this._decbin_pad(this.#items[slot].slot, 3);
			bin += this._decbin_pad(this.#items[slot].id, item_length);
			bin += this._decbin_pad(this.#items[slot].mods.length, 2);
			bin += this._decbin_pad(this.#items[slot].color, 4);

			for(let mod of this.#items[slot].mods){
				bin += this._decbin_pad(mod, mod_length);
			}
		}

		return this._encodeTemplate(bin);
	}

	/**
	 * Adds an equipment item
	 *
	 * @param {number|int} $id
	 * @param {number|int} $color
	 * @param {int[]} $mods
	 * @returns {EquipmentTemplate}
	 * @throws {Error}
	 */
	addItem($id, $color = 0, $mods = []){

		if(this.#ITEM_TO_SLOT[String($id)] === undefined){
			throw new Error('invalid item id');
		}

		if(this.#ITEM_COLORS[String($color)] === undefined){
			throw new Error('invalid color id');
		}

		let slot = this.#ITEM_TO_SLOT[String($id)];

		this.#items[String(slot)] = {
			id   : $id,
			slot : slot,
			color: $color,
			mods : this.#normalizeMods($mods),
		};

		return this;
	}

	/**
	 * Clears all currently added equipment items
	 *
	 * @returns {EquipmentTemplate}
	 */
	clearItems(){
		this.#items = {};

		return this;
	}

	/**
	 * Normalizes/clamps mod IDs
	 *
	 * @param {int[]} $mods
	 * @returns {int[]}
	 */
	#normalizeMods($mods){
		let normalizedMods = [];

		for(let modID of $mods){

			// invalid
			if(typeof modID !== 'number'){
				continue;
			}

			// we don't know whether it's an int or float - thanks javascript!
			modID = PHPJS.intval(modID);

			// out of range
			if(modID < 1 || modID >= 0x200){
				continue;
			}

			normalizedMods.push(modID);
		}

		return normalizedMods;
	}

}

/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

/**
 * Biblically accurate paw·ned² team build encoder/decoder
 *
 * Thanks to Redeemer (paw·ned² developer) and Antodias (formerly gwcom.de)!
 *
 * @link https://memorial.redeemer.biz/pawned2/
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/API/Encoding_API
 * @see https://github.com/mathiasbynens/windows-1252
 *
 * @final
 */
class PwndTemplate extends TemplateAbstract{
	// we're using static getters as class constants here
	static get PWND_HEADER_COMMENT(){return 'pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates'}

	static get PAWNED_CHARSET_UNDEFINED  (){return 0}
	static get PAWNED_CHARSET_WINDOWS1252(){return 1}
	static get PAWNED_CHARSET_UTF8       (){return 2}

	static get ENCODING_UNDEFINED  (){return 'undefined'}
	static get ENCODING_ASCII      (){return 'ascii'}
	static get ENCODING_WINDOWS1252(){return 'windows-1252'}
	static get ENCODING_UTF8       (){return 'utf-8'}

	static get CHARSETS(){return [
		PwndTemplate.ENCODING_UNDEFINED,
		PwndTemplate.ENCODING_WINDOWS1252,
		PwndTemplate.ENCODING_UTF8,
	]}

	static get CON_LUNAR_FORTUNE      (){return 0}
	static get CON_CANDY_CORN         (){return 1}
	static get CON_GOLDEN_EGG         (){return 2}
	static get CON_BDAY_CUPCAKE       (){return 3}
	static get CON_PUMPKIN_PIE        (){return 4}
	static get CON_CANDY_APPLE        (){return 5}
	static get CON_WAR_SUPPLIES       (){return 6}
	static get CON_DRAKE_KABOB        (){return 7}
	static get CON_SKALEFIN_SOUP      (){return 8}
	static get CON_PAHNAI_SALAD       (){return 9}
	static get CON_GREEN_CANDY        (){return 10}
	static get CON_BLUE_CANDY         (){return 11}
	static get CON_RED_CANDY          (){return 12}
	static get CON_ESSENCE_OF_CELERITY(){return 13}
	static get CON_ARMOR_OF_SALVATION (){return 14}
	static get CON_GRAIL_OF_MIGHT     (){return 15}
	// not yet implemented
//	static get MOD_OF_THE_WARRIOR     (){return 16}
//	static get MOD_OF_THE_RANGER      (){return 17}
//	static get MOD_OF_THE_MONK        (){return 18}
//	static get MOD_OF_THE_NECROMACER  (){return 19}
//	static get MOD_OF_THE_MESMER      (){return 20}
//	static get MOD_OF_THE_ELEMENTALIST(){return 21}
//	static get MOD_OF_THE_ASSASSIN    (){return 22}
//	static get MOD_OF_THE_RITUALIST   (){return 23}
//	static get MOD_OF_THE_PARAGON     (){return 24}
//	static get MOD_OF_THE_DERVISH     (){return 25}

	static get FLAGS(){return [
		'buLunarFortune',
		'buCandyCorn',
		'buGoldenEgg',
		'buBirthdayCupcake',
		'buSliceOfPumpkinPie',
		'buCandyApple',
		'buWarSupplies',
		'buDrakeKabob',
		'buBowlOfSkalefinSoup',
		'buPahnaiSalad',
		'buGreenRockCandy',
		'buBlueRockCandy',
		'buRedRockCandy',
		'buEssenceOfCelerity',
		'buArmorOfSalvation',
		'buGrailOfMight',
		// not yet implemented
//		'buOfTheWarrior',
//		'buOfTheRanger',
//		'buOfTheMonk',
//		'buOfTheNecromancer',
//		'buOfTheMesmer',
//		'buOfTheElementalist',
//		'buOfTheAsssassin',
//		'buOfTheRitualist',
//		'buOffTheParagon',
//		'buOffTheDervish',
	]}

	#BASE64             = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
	#PWND_HEADER_PREFIX = 'pwnd';

	/**
	 * @type {{skills: string, equipment: string, weaponsets: string[], flags: string, player: string, description: string}[]}
	 */
	#builds = [];

	/** @type {string} */
	#encoding;
	/** @type {number|int} */
	#encodingFlag;

	/**
	 * @param {number|int} $encodingFlag
	 */
	constructor($encodingFlag = PwndTemplate.PAWNED_CHARSET_UTF8){
		super();

		this.#encoding     = PwndTemplate.getEncoding($encodingFlag);
		this.#encodingFlag = $encodingFlag;
	}

	static fromTemplate($template){
		return new PwndTemplate().decode($template);
	}

	static fromChatCode($chatCode){
		throw new Error('not supported');
	}

	/**
	 * Returns the character encoding given in the header flag
	 *
	 * @param {number|int} $encodingFlag
	 * @returns {string}
	 * @throws {Error}
	 */
	static getEncoding($encodingFlag){

		if(!PwndTemplate.CHARSETS[$encodingFlag]){
			throw new Error('invalid encoding flag');
		}

		return PwndTemplate.CHARSETS[$encodingFlag];
	}

	/**
	 * Parses the paw-ned² header flags
	 *
	 *   0: breaking change
	 *   1: unused
	 *   2: unused
	 *   3: character encoding
	 *
	 * @param {string} $pwnd
	 * @returns {int[]}
	 * @throws {Error}
	 */
	static parseHeader($pwnd){
		let header = $pwnd.substring(0, 8);

		if(header.substring(0, 4) !== 'pwnd'){
			throw new Error('invalid paw-ned² template');
		}

		// read header flags, skip over the "pwnd"
		let headerFlags = header.substring(4, 8).split('').map(PHPJS.intval);

		if(headerFlags[0] !== 0){
			throw new Error('paw-ned² breaking change detected');
		}

		return headerFlags;
	}

	/**
	 * Decodes the given paw-ned² template into an array
	 *
	 * @returns {{skills: string, equipment:string, weaponsets: string[], templatename: string, description: string, player: string, attributes: int[], flags: bool[]}[]}
	 * @throws {Error}
	 */
	decode($pwnd){
		// detect encoding (we're doing this first as the header parser will throw on invalid template)
		let headerFlags = PwndTemplate.parseHeader($pwnd);
		let encoding    = PwndTemplate.getEncoding(headerFlags[3]);

		// find the template string
		$pwnd = $pwnd.trim().replace(/[\r\n]/g, '');
		let start = $pwnd.indexOf('>');
		let end   = $pwnd.indexOf('<', start);

		if(start === -1 || end === -1 || end <= start){
			throw new Error('invalid paw·ned² template');
		}

		this._string = this._checkCharacterSet($pwnd.substring(start + 1, end));
		this._offset = 0;

		let builds = [];

		while(this._offset < this._string.length){
			let skills     = this.#readString();
			let equipment  = this.#readString();
			let weaponsets = [];

			for(let i = 0; i < 3; i++){
				weaponsets[i] = this.#readString();
			}

			let extra  = this.#readString();
			let player = this.#readString();
			let desc   = this.#readString(true);

			let [templatename, description] = this.#decodeDescription(desc, encoding);
			let [attributeBonuses, flags]   = this.#decodeFlags(extra);

			builds.push({
				skills      : skills,
				equipment   : equipment,
				weaponsets  : weaponsets,
				templatename: templatename,
				description : description,
				player      : this.#decodePlayerName(player, encoding),
				attributes  : attributeBonuses,
				flags       : flags,
			});
		}

		return builds;
	}

	/**
	 * Encodes the given build(s) into a paw-ned² template
	 *
	 * @returns {string}
	 */
	encode(){
		let pwnd = '>';

		for(let build of this.#builds){
			pwnd += this.#writeString(build.skills);
			pwnd += this.#writeString(build.equipment);

			for(let weaponset of build.weaponsets){
				pwnd += this.#writeString(weaponset);
			}

			pwnd += this.#writeString(build.flags);
			pwnd += this.#writeString(build.player);
			pwnd += this.#writeString(build.description, true);
		}

		pwnd += '<';

		pwnd = PHPJS.str_split(pwnd, 80).join('\n');

		return `${this.#PWND_HEADER_PREFIX}${this.#writeHeaderFlags()}?${PwndTemplate.PWND_HEADER_COMMENT}\n${pwnd}`;
	}

	/**
	 * Adds a build item
	 *
	 * @param {string} $skills
	 * @param {string} $equipment
	 * @param {string[]} $weaponsets
	 * @param {string} $templatename
	 * @param {string} $description
	 * @param {string} $player
	 * @param {int[]} $attributes
	 * @param {bool[]} $flags
	 * @returns {PwndTemplate}
	 */
	addBuild(
		$skills,
		$equipment = '',
		$weaponsets = [],
		$templatename = '',
		$description = '',
		$player = '',
		$attributes = [],
		$flags = [],
	){

		this.#builds.push({
			skills     : this._checkCharacterSet($skills),
			equipment  : this._checkCharacterSet($equipment),
			weaponsets : this.#normalizeWeaponsets($weaponsets),
			flags      : this.#encodeFlags($attributes, $flags),
			player     : this.#encodePlayername($player, this.#encoding),
			description: this.#encodeDescription($templatename, $description, this.#encoding),
		});

		return this;
	}

	/**
	 * Clears all currently added build items
	 *
	 * @returns {PwndTemplate}
	 */
	clearBuilds(){
		this.#builds = [];

		return this;
	}

	/**
	 * Checks/normalizes the given weapon sets, limits input to 3 items
	 *
	 * @param {string[]} $weaponsets
	 * @returns {string[]}
	 */
	#normalizeWeaponsets($weaponsets){
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
				weaponset = this._checkCharacterSet(weaponset);
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
	 * Decodes the player name field
	 *
	 * @param {string} $playerB64
	 * @param {string} $from_encoding
	 * @returns {string}
	 */
	#decodePlayerName($playerB64, $from_encoding){
		let player = this._base64decode($playerB64);

		return this.#decodeField(player, $from_encoding);
	}

	/**
	 * Encodes the player name field according to the given character encoding
	 *
	 * @param {string} $name
	 * @param {string} $to_encoding
	 * @returns {string}
	 * @throws {Error}
	 */
	#encodePlayername($name, $to_encoding){
		$name = $name.trim();

		if($name === ''){
			return '';
		}

		let encodedName = this.#encodeField($name, $to_encoding);

		// 32 in windows-1252/ASCII, 48 in utf-8, apparently
		if(this.#encodingFlag !== PwndTemplate.PAWNED_CHARSET_UTF8 && encodedName.length > 32){
			throw new Error('player name cannot be longer than 32 bytes');
		}
		else if(encodedName.length > 48){
			throw new Error('player name cannot be longer than 48 bytes in UTF-8 mode');
		}

		return this._base64encode(encodedName);
	}

	/**
	 * Decodes the template name/description field
	 *
	 * @param {string} $descB64
	 * @param {string} $from_encoding
	 * @returns {[string, string]}
	 */
	#decodeDescription($descB64, $from_encoding){
		let desc = this._base64decode($descB64);

		// the LF should always be present, even if both fields are empty
		if(desc.length <= 1){
			return ['', ''];
		}

		desc = this.#decodeField(desc, $from_encoding);

		// for some reason there was no LF character (trimmed from the end while decoding),
		// we'll assume that whatever the string is as template name
		if(!desc.includes('\n')){
			return [desc, ''];
		}

		return desc.split('\n', 2);
	}

	/**
	 * Encodes the description field from the given template name and description
	 *
	 * @param {string} $templatename
	 * @param {string} $description
	 * @param {string} $to_encoding
	 * @returns {string}
	 * @throws {Error}
	 */
	#encodeDescription($templatename, $description, $to_encoding){
		// field is empty, always return a line break
		if($templatename + $description === ''){
			return 'Cg'; // "\n" in base64
		}

		let field = this.#encodeField($templatename + '\n' + $description, $to_encoding);

		// for some reason the field can be longer than 255, up to 2-3 bytes [citation needed]
		if(field.length > 258){
			throw new Error('template name and description combined cannot be longer than 255 bytes');
		}

		return this._base64encode(field);
	}

	/**
	 * Decodes Attribute bonuses and flags
	 *
	 * @param {string} $flagsB64
	 * @returns {[int[], bool[]]}
	 */
	#decodeFlags($flagsB64){

		if($flagsB64 === ''){
			// return an "empty" array
			return [
				PHPJS.array_fill(5, 0),
				PHPJS.array_fill(PwndTemplate.FLAGS.length, false)
			]
		}

		let bonuses = [];
		let flag_b2 = '';

		// we're reading a maximum of 6 bytes (6 bits each, for a total of 36 - the flag length is 34 bits)
		// the first 3 bytes for attribute bonuses
		for(let i = 0; i < 6; i++){
			// we're going to zero-fill here
			let ord = this.#base64_ord(($flagsB64[i] ?? 'A'));

			if(i < 3){
				bonuses.push((ord & 0b111000) >> 3);
				bonuses.push(ord & 0b000111);
			}
			// the bytes for the consumable flags are read as big-endian, aka we need to flip them
			else {
				flag_b2 += this._decbin_pad(ord, 6);
			}
		}

		// the last value is unused and always 0
		bonuses.splice(5, 1);

		let flag_val = flag_b2.split('');
		let flags    = [];

		for(let flag of PwndTemplate.FLAGS.keys()){
			flags[flag] = (flag_val[flag] === '1');
		}

		// the last value of bonuses is unused and always 0
		return [bonuses, flags];
	}

	#encodeFlags($attributes, $flags){
		$attributes  = Object.values($attributes);
		$flags       = Object.values($flags);

		let flag_sum = PHPJS.array_sum($flags);

		if(PHPJS.array_sum($attributes) === 0 && flag_sum === 0){
			return '';
		}

		// zero-fill possibly missing values
		while($attributes.length < 6){
			$attributes.push(0);
		}

		let b64 = '';

		for(let i = 0; i < 6; i += 2){
			b64 += this.#base64_chr(($attributes[i] << 3) | $attributes[i + 1]);
		}

		// we'll only write flags if any of them are set
		if(flag_sum > 0){
			let base2 = '';

			for(let flag of Object.keys(PwndTemplate.FLAGS)){
				base2 += $flags[flag] ? 1 : 0;
			}

			let bin = this._encodeBase2ToBinary(base2);
			b64 += this._base64encode(bin);
		}

		// cut off unnecessary zero padding
		// noinspection RegExpSimplifiable
		return b64.replace(/[A]+$/, '');
	}

	/**
	 * Converts the given string from the given encoding to internal encoding
	 *
	 * @param {string} $data
	 * @param {string} $from_encoding
	 * @returns {string}
	 */
	#decodeField($data, $from_encoding){

		if($from_encoding === PwndTemplate.ENCODING_UNDEFINED){
			$from_encoding = PwndTemplate.ENCODING_ASCII; // do we have detect_encoding here???
		}
		// we need to remove some null bytes that we added before
		let uint8 = $data.replaceAll('\x00', '').split('').map(c => c.charCodeAt(0));
		// Uint8Array.fromBase64() is not available yet
		return new TextDecoder($from_encoding, {fatal: true}).decode(new Uint8Array(uint8)).trim();
	}

	/**
	 * Converts the given string to the given encoding from internal encoding
	 *
	 * @param {string} $data
	 * @param {string} $to_encoding
	 * @returns {Uint8Array}
	 * @throws {Error}
	 */
	#encodeField($data, $to_encoding){

		if($data === ''){
			return new Uint8Array([]);
		}

		if($to_encoding === PwndTemplate.ENCODING_UTF8){
			return new TextEncoder().encode($data);
		}

		if([
			PwndTemplate.ENCODING_UNDEFINED,
			PwndTemplate.ENCODING_ASCII,
			PwndTemplate.ENCODING_WINDOWS1252,
		].includes($to_encoding)){
			// evil. (remove once Uint16Array.toBase64() is supported)
			return new Uint8Array(windows1252__namespace.encode($data));
		}

		throw new Error('invalid $to_encoding');
	}

	/**
	 * Returns the ordinal for the given base64 character
	 *
	 * @param {string} $chr
	 * @returns {number|int}
	 * @throws {Error}
	 */
	#base64_ord($chr){
		let $ord = this.#BASE64.indexOf($chr);

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
	 * @throws {Error}
	 */
	#base64_chr($ord){

		if($ord < 0 || $ord > 63){
			throw new Error('invalid base64 ordinal');
		}

		return this.#BASE64.substring($ord, $ord + 1)
	}

	/**
	 * Reads a base64 encoded field from the template string
	 *
	 * @param {boolean} $isVariableField
	 * @returns {string}
	 */
	#readString($isVariableField = false){
		let length = this._read(1);

		if($isVariableField){
			length *= 64;
			length += this._read(1);
		}

		return this._string.substring(this._offset, (this._offset += length));
	}

	/**
	 * Writes a base64 encoded field to the template string
	 *
	 * @param {string} $str
	 * @param {boolean} $isVariableField
	 * @returns {string}
	 */
	#writeString($str, $isVariableField = false){

		if($isVariableField){
			let $len = this.#base64_chr(Math.floor($str.length / 64));
			$len += this.#base64_chr($str.length % 64);

			return $len + $str;
		}

		return this.#base64_chr($str.length) + $str;
	}

	/**
	 * Encodes the header flag string
	 */
	#writeHeaderFlags(){
		// not much to do here yet
		let headerFlags = [0, 0, 0, this.#encodingFlag];

		return headerFlags.join('');
	}

	/**
	 * Reads a length byte from the template string
	 *
	 * @param {number|int} $length
	 * @returns {number|int}
	 */
	_read($length){
		return this.#base64_ord(this._string.substring(this._offset, (this._offset += $length)));
	}

}

/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

/**
 * @link https://wiki.guildwars.com/wiki/Skill_template_format
 *
 * @final
 */
class SkillTemplate extends TemplateAbstract{

	/**
	 * profession id => primary attribute id
	 */
	#PROF_TO_PRI = {
		'1' : 17, '2' : 23, '3' : 16, '4' : 6, '5' : 0, '6' : 12, '7' : 35, '8' : 36, '9' : 40, '10': 44,
	};

	/**
	 * attribute id => profession id
	 */
	#ATTR_TO_PROF = {
		'0' : 5, '1' : 5, '2' : 5, '3' : 5, '4' : 4, '5' : 4, '6' : 4, '7' : 4, '8' : 6, '9' : 6,
		'10': 6, '11': 6, '12': 6, '13': 3, '14': 3, '15': 3, '16': 3, '17': 1, '18': 1, '19': 1,
		'20': 1, '21': 1, '22': 2, '23': 2, '24': 2, '25': 2, '29': 7, '30': 7, '31': 7, '32': 8,
		'33': 8, '34': 8, '35': 7, '36': 8, '37': 9, '38': 9, '39': 9, '40': 9, '41': 10, '42': 10,
		'43': 10, '44': 10,
	};

	static fromTemplate($template){
		return new SkillTemplate().decode($template);
	}

	static fromChatCode($chatCode){
		// noinspection RegExpRedundantEscape
		let match = $chatCode.trim().match(/^\[(?<name>[^;]*);(?<code>[A-Za-z0-9\+\/ ]+)\]$/);

		if(match === null || !match.groups){
			throw new Error('invalid chat code');
		}

		return SkillTemplate.fromTemplate(match.groups.code);
	}

	/**
	 * Decodes the given skill template into an array
	 *
	 * @param {string} $template
	 * @returns {{code: string, prof_pri: number|int, prof_sec: number|int, attributes: {}, skills: number[]}}
	 */
	decode($template){
		this._string = this._decodeTemplate($template);
		this._offset = 0;

		// profession length code, seems to be unused and will always be 00
		// noinspection JSUnusedLocalSymbols
		this._read(2);
		// primary profession id
		let pri   = this._read(4);
		// secondary profession id
		let sec   = this._read(4);
		// attribute count
		let attrc = this._read(4);
		// attribute id length code
		let attrl = (this._read(4) + 4);

		let attributes = {};

		// get the attributes
		for(let i = 0; i < attrc; i++){
			attributes[this._read(attrl)] = this._read(4);
		}

		// get the skillbar
		let skill_id_len = (this._read(4) + 8);
		let skills       = [0, 0, 0, 0, 0, 0, 0, 0].map(() => this._read(skill_id_len));

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
		let $bin = this._decbin_pad(this.TEMPLATE_SKILL_NEW, 4);
		// version (0,4)
		$bin += this._decbin_pad(0, 4);
		// profession length code (0,2)
		$bin += this._decbin_pad(0, 2);
		// add professions
		$bin += this._decbin_pad($prof_pri, 4);
		$bin += this._decbin_pad($prof_sec, 4);
		// add attribute count
		let attributeIDs = Object.keys($attributes);
		$bin += this._decbin_pad(attributeIDs.length, 4);
		// get attribute pad size
		let $attr_pad = this._getPadSize(attributeIDs, 5);

		// add attribute length code
		$bin += this._decbin_pad(($attr_pad - 4), 4);

		// add attribute ids and corresponding values
		for(let id in $attributes){
			$bin += this._decbin_pad(PHPJS.intval(id), $attr_pad);
			$bin += this._decbin_pad($attributes[id], 4);
		}

		// get skill pad size
		let $skill_pad = this._getPadSize($skills, 10);
		// add skill length code
		$bin += this._decbin_pad(($skill_pad - 8), 4);
		// add skill ids
		for(let id of $skills){
			$bin += this._decbin_pad(id, $skill_pad);
		}

		return this._encodeTemplate($bin);
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

		if(this.#PROF_TO_PRI[String($pri)] === undefined){
			$pri = 0;
		}

		if(this.#PROF_TO_PRI[String($sec)] === undefined || $sec === $pri){
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
	 * @returns {}
	 * @private
	 */
	normalizeAttributes($attributes, $pri, $sec){
		let normalizedAttributes = {};

		for(let id in $attributes){

			// exclude invalid attributes
			if(this.#ATTR_TO_PROF[id] === undefined){
				continue;
			}

			let profession = this.#ATTR_TO_PROF[id];

			// attribute profession is neither primary or secondary
			if(profession !== $pri && profession !== $sec){
				continue;
			}

			// primary attribute of secondary profession
			if(this.#PROF_TO_PRI[String($sec)] !== undefined && profession === this.#PROF_TO_PRI[String($sec)]){
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

exports.EquipmentTemplate = EquipmentTemplate;
exports.PwndTemplate = PwndTemplate;
exports.SkillTemplate = SkillTemplate;
//# sourceMappingURL=gw-templates-node-src.cjs.map
