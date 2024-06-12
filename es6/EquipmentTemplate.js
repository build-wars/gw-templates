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
		let bin = this.decodeTemplate($template);

		this.items = {};

		// get item length code, mod length code and item count
		let item_length = this.bindec_flip(bin.substring(0, 4));
		let mod_length  = this.bindec_flip(bin.substring(4, 8));
		let item_count  = this.bindec_flip(bin.substring(8, 11));

		// cut 4+4+3 bits
		bin = bin.substring(11);

		// loop through the items
		for(let i = 0; i < item_count; i++){
			// get item type, id, number of mods and item color

			// 0. Weapon, 1. Off-hand, 2. Chest, 3. Legs, 4. Head, 5. Feet, 6. Hands

			let slot      = this.bindec_flip(bin.substring(0, 3));
			let mod_count = this.bindec_flip(bin.substring((item_length + 3), (item_length + 5)));

			this.items[slot] = {
				id   : this.bindec_flip(bin.substring(3, (item_length + 3))),
				slot : slot,
				color: this.bindec_flip(bin.substring((item_length + 5), (item_length + 9))),
				mods : [],
			};

			// cut item length + 9 bits
			bin = bin.substring(item_length + 9);

			// loop through the mods
			for(let j = 0; j < mod_count; j++){
				this.items[slot].mods.push(this.bindec_flip(bin.substring(0, mod_length)));

				bin = bin.substring(mod_length);
			}

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
