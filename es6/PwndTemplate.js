/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
import TemplateAbstract from './TemplateAbstract.js';
import PHPJS from './PHPJS.js';
import * as windows1252 from 'windows-1252';

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
export default class PwndTemplate extends TemplateAbstract{
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
			else{
				flag_b2 += this._decbin_pad(ord, 6);
			}
		}

		// the last value is unused and always 0
		bonuses.splice(5, 1)

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
			return new Uint8Array(windows1252.encode($data));
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
