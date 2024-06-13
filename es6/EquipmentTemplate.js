/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

import TemplateAbstract from './TemplateAbstract.js';
import {ITEM_COLORS, ITEM_TO_SLOT, TEMPLATE_EQUIPMENT_NEW} from './constants.js';
import PHPJS from './PHPJS.js';

/**
 * @link https://wiki.guildwars.com/wiki/Equipment_template_format
 *
 * @final
 */
export default class EquipmentTemplate extends TemplateAbstract{

	items = {};

	/**
	 * Decodes the given equipment template into an array
	 *
	 *    array{
	 *      id:    int,
	 *      slot:  int,
	 *      color: int,
	 *      mods:  int[]
	 *    }
	 *
	 * @param {string} $template
	 * @returns {*}
	 */
	decode($template){
		this.items = {};

		let bin    = this.decodeTemplate($template);
		let offset = 0;

		let read = ($length) => this.bindec_flip(bin.substring(offset, (offset += $length)));

		// get item id length code, mod id length code and item count
		let item_id_length = read(4);
		let mod_id_length  = read(4);
		let item_count     = read(3);

		// loop through the items
		for(let i = 0; i < item_count; i++){
			// get item type, id, number of mods and item color
			let slot      = read(3);
			let id        = read(item_id_length);
			let mod_count = read(2);
			let color     = read(4);

			// loop through the mods
			let mods = [];

			for(let j = 0; j < mod_count; j++){
				mods.push(read(mod_id_length));
			}

			this.items[slot] = {id: id, slot: slot, color: color, mods: mods};
		}

		return this.items;
	}

	/**
	 * Encodes the currently added equipment items into a template code
	 *
	 * @returns {string}
	 */
	encode(){
		// start of the binary string:
		// type (15,4)
		let bin  = this.decbin_pad(TEMPLATE_EQUIPMENT_NEW, 4);
		// version (0,4)
		bin += this.decbin_pad(0, 4);

		let itemIDs = [];
		let modIDs  = [];


		for(let slot in this.items){
			itemIDs.push(this.items[slot].id);
			modIDs = modIDs.concat(this.items[slot].mods)
		}

		let item_length = this.getPadSize(itemIDs, 8);
		let mod_length  = this.getPadSize(modIDs, 8);

		// add length codes and item count
		bin += this.decbin_pad(item_length, 4);
		bin += this.decbin_pad(mod_length, 4);
		bin += this.decbin_pad(Object.keys(this.items).length, 3);

		for(let slot in this.items){
			bin += this.decbin_pad(this.items[slot].slot, 3);
			bin += this.decbin_pad(this.items[slot].id, item_length);
			bin += this.decbin_pad(this.items[slot].mods.length, 2);
			bin += this.decbin_pad(this.items[slot].color, 4);

			for(let mod of this.items[slot].mods){
				bin += this.decbin_pad(mod, mod_length);
			}
		}

		return this.encodeTemplate(bin);
	}

	/**
	 * Adds an equipment item
	 *
	 * @param {number|int} $id
	 * @param {number|int} $color
	 * @param {int[]} $mods
	 * @returns {EquipmentTemplate}
	 */
	addItem($id, $color = 0, $mods = []){
		let idStr = $id.toString(); // js object key weirdness

		if(ITEM_TO_SLOT[idStr] === undefined){
			throw new Error('invalid item id');
		}

		if(ITEM_COLORS[$color.toString()] === undefined){
			throw new Error('invalid color id');
		}

		let slot = ITEM_TO_SLOT[idStr];

		this.items[slot] = {
			id   : $id,
			slot : slot,
			color: $color,
			mods : this.normalizeMods($mods),
		}

		return this;
	}

	/**
	 * Clears all currently added equipment items
	 *
	 * @returns {EquipmentTemplate}
	 */
	clearItems(){
		this.items = {};

		return this;
	}

	/**
	 * Normalizes/clamps mod IDs
	 *
	 * @param {int[]} $mods
	 * @returns {int[]}
	 * @private
	 */
	normalizeMods($mods){
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
