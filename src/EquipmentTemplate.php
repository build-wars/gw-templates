<?php
/**
 * Class EquipmentTemplate
 *
 * @created      21.05.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace BuildWars\GWTemplates;

use InvalidArgumentException;
use RuntimeException;
use function array_merge;
use function is_int;
use function ksort;
use function preg_match;
use function substr;
use const SORT_NUMERIC;

/**
 * @link https://wiki.guildwars.com/wiki/Equipment_template_format
 */
final class EquipmentTemplate extends TemplateAbstract{

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
	 *
	 * @var int[]
	 */
	public const ITEM_TO_SLOT = [
		1   => 5,
		2   => 5,
		3   => 5,
		4   => 5,
		5   => 5,
		6   => 5,
		8   => 5,
		9   => 5,
		10  => 5,
		11  => 5,
		12  => 5,
		13  => 5,
		14  => 5,
		15  => 5,
		16  => 5,
		17  => 5,
		18  => 5,
		19  => 5,
		20  => 5,
		21  => 5,
		22  => 2,
		23  => 2,
		24  => 2,
		25  => 2,
		26  => 2,
		27  => 2,
		28  => 2,
		29  => 2,
		30  => 2,
		31  => 2,
		32  => 2,
		33  => 2,
		34  => 2,
		35  => 2,
		36  => 2,
		37  => 2,
		38  => 2,
		39  => 2,
		40  => 2,
		41  => 2,
		42  => 6,
		43  => 6,
		44  => 6,
		45  => 6,
		46  => 6,
		47  => 6,
		48  => 6,
		49  => 6,
		50  => 6,
		51  => 6,
		52  => 6,
		53  => 6,
		54  => 6,
		55  => 6,
		56  => 6,
		57  => 6,
		59  => 6,
		60  => 6,
		61  => 6,
		62  => 6,
		63  => 4,
		64  => 4,
		65  => 4,
		66  => 4,
		67  => 4,
		68  => 4,
		69  => 4,
		70  => 4,
		71  => 4,
		72  => 4,
		73  => 4,
		74  => 4,
		75  => 4,
		76  => 4,
		77  => 4,
		78  => 4,
		79  => 4,
		80  => 4,
		81  => 4,
		83  => 4,
		84  => 4,
		85  => 4,
		86  => 4,
		87  => 4,
		88  => 4,
		89  => 4,
		90  => 3,
		91  => 3,
		92  => 3,
		93  => 3,
		94  => 3,
		95  => 3,
		96  => 3,
		97  => 3,
		98  => 3,
		99  => 3,
		100 => 3,
		101 => 3,
		102 => 3,
		103 => 3,
		104 => 3,
		105 => 3,
		106 => 3,
		107 => 3,
		108 => 3,
		109 => 3,
		110 => 0,
		111 => 0,
		112 => 0,
		113 => 0,
		114 => 0,
		115 => 0,
		116 => 1,
		117 => 1,
		118 => 1,
		119 => 1,
		120 => 1,
		121 => 1,
		122 => 1,
		123 => 1,
		124 => 1,
		125 => 1,
		126 => 1,
		127 => 1,
		128 => 1,
		129 => 1,
		130 => 1,
		131 => 1,
		132 => 1,
		133 => 0,
		134 => 0,
		135 => 0,
		136 => 0,
		137 => 0,
		138 => 0,
		139 => 0,
		140 => 0,
		141 => 0,
		142 => 0,
		143 => 0,
		144 => 0,
		145 => 1,
		146 => 1,
		147 => 0,
		148 => 0,
		149 => 0,
		150 => 0,
		151 => 0,
		152 => 0,
		153 => 0,
		154 => 0,
		155 => 0,
		156 => 0,
		157 => 0,
		158 => 0,
		159 => 5,
		160 => 2,
		161 => 6,
		162 => 3,
		163 => 5,
		164 => 2,
		165 => 6,
		166 => 3,
		167 => 5,
		168 => 2,
		169 => 6,
		170 => 3,
		171 => 5,
		172 => 2,
		173 => 6,
		174 => 3,
		175 => 5,
		176 => 2,
		177 => 6,
		178 => 3,
		179 => 5,
		180 => 2,
		181 => 6,
		182 => 3,
		183 => 5,
		184 => 2,
		185 => 6,
		186 => 3,
		187 => 5,
		188 => 2,
		189 => 6,
		190 => 3,
		191 => 5,
		192 => 2,
		193 => 6,
		194 => 3,
		195 => 5,
		196 => 2,
		197 => 6,
		198 => 3,
		199 => 5,
		200 => 2,
		201 => 6,
		202 => 3,
		203 => 5,
		204 => 2,
		205 => 6,
		206 => 3,
		207 => 5,
		208 => 2,
		209 => 6,
		210 => 3,
		211 => 5,
		212 => 2,
		213 => 6,
		214 => 3,
		215 => 5,
		216 => 2,
		217 => 6,
		218 => 3,
		219 => 5,
		220 => 2,
		221 => 6,
		222 => 3,
		223 => 5,
		224 => 2,
		225 => 6,
		226 => 3,
		227 => 5,
		228 => 2,
		229 => 6,
		230 => 3,
		231 => 5,
		232 => 2,
		233 => 6,
		234 => 3,
		235 => 5,
		236 => 2,
		237 => 6,
		238 => 3,
		239 => 5,
		240 => 2,
		241 => 6,
		242 => 3,
		243 => 5,
		244 => 2,
		245 => 6,
		246 => 3,
		247 => 5,
		248 => 2,
		249 => 6,
		250 => 3,
		251 => 5,
		252 => 2,
		253 => 6,
		254 => 3,
		255 => 5,
		256 => 2,
		257 => 6,
		258 => 3,
		259 => 5,
		260 => 2,
		261 => 6,
		262 => 3,
		263 => 5,
		264 => 2,
		265 => 6,
		266 => 3,
		267 => 5,
		268 => 2,
		269 => 6,
		270 => 3,
		271 => 4,
		272 => 4,
		273 => 4,
		274 => 4,
		275 => 4,
		276 => 4,
		277 => 4,
		278 => 4,
		279 => 0,
		280 => 1,
		281 => 1,
		282 => 1,
		283 => 1,
		284 => 0,
		285 => 0,
		286 => 0,
		287 => 0,
		288 => 0,
		289 => 0,
		290 => 4,
		291 => 4,
		292 => 4,
		293 => 4,
		294 => 5,
		295 => 2,
		296 => 6,
		297 => 3,
		298 => 5,
		299 => 2,
		300 => 6,
		301 => 3,
		302 => 5,
		303 => 2,
		304 => 6,
		305 => 3,
		306 => 4,
		307 => 4,
		308 => 4,
		309 => 4,
		310 => 5,
		311 => 2,
		312 => 6,
		313 => 3,
		314 => 5,
		315 => 2,
		316 => 6,
		317 => 3,
		318 => 5,
		319 => 2,
		320 => 6,
		321 => 3,
		322 => 0,
		323 => 1,
		324 => 1,
		325 => 0,
		326 => 0,
		327 => 0,
		328 => 0,
		329 => 0,
		330 => 0,
		331 => 0,
		332 => 0,
		333 => 0,
		334 => 0,
		335 => 0,
		336 => 0,
		337 => 0,
		338 => 0,
		339 => 0,
	];

	/**
	 * Item colors
	 * @var array<int, string>
	 */
	public const ITEM_COLORS = [
		0 => 'default',
		2 => 'blue',
		3 => 'green',
		4 => 'purple',
		5 => 'red',
		6 => 'yellow',
		7 => 'brown',
		8 => 'orange',
		9 => 'grey',
	];

	private array $items = [];

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
	 */
	public function decode(string $template):array{
		$bin = $this->decodeTemplate($template);

		// get item length code, mod length code and item count
		if(!preg_match('/^(?P<iteml>[01]{4})(?P<modl>[01]{4})(?P<itemc>[01]{3})/', $bin, $info)){
			throw new RuntimeException('invalid equipment template');
		}

		// cut 4+4+3 bits
		$bin = substr($bin, 11);

		$item_count  = $this->bindec_flip($info['itemc']);
		$item_length = $this->bindec_flip($info['iteml']);
		$mod_length  = $this->bindec_flip($info['modl']);

		$this->items = [];

		// loop through the items
		for($i = 0; $i < $item_count; $i++){

			// get item type, id, number of mods and item color
			if(!preg_match('/^(?P<slot>[01]{3})(?P<id>[01]{'.$item_length.'})(?P<modc>[01]{2})(?P<color>[01]{4})/', $bin, $data)){
				throw new RuntimeException('invalid equipment item');
			}

			$bin = substr($bin, ($item_length + 9));

			// 0. Weapon, 1. Off-hand, 2. Chest, 3. Legs, 4. Head, 5. Feet, 6. Hands
			$slot = $this->bindec_flip($data['slot']);

			$this->items[$slot] = [
				'id'    => $this->bindec_flip($data['id']),
				'slot'  => $slot,
				'color' => $this->bindec_flip($data['color']),
				'mods'  => [],
			];

			// loop through the mods
			$mod_count = $this->bindec_flip($data['modc']);

			for($j = 0; $j < $mod_count; $j++){
				$this->items[$slot]['mods'][] = $this->bindec_flip(substr($bin, 0, $mod_length));
				$bin                          = substr($bin, $mod_length);
			}

		}

		ksort($this->items, SORT_NUMERIC);

		return $this->items;
	}

	/**
	 * Encodes the currently added equipment items into a template code
	 */
	public function encode():string{
		ksort($this->items, SORT_NUMERIC);

		// start of the binary string:
		// type (15,4)
		$bin  = $this->decbin_pad(dec: self::TEMPLATE_EQUIPMENT_NEW, padding: 4);
		// version (0,4)
		$bin .= $this->decbin_pad(0, 4);

		$itemIDs = [];
		$modIDs  = [];

		foreach($this->items as $item){
			$itemIDs[] = $item['id'];
			$modIDs    = array_merge($modIDs, $item['mods']);
		}

		$item_count  = count($this->items);
		$item_length = $this->getPadSize($itemIDs, 8);
		$mod_length  = $this->getPadSize($modIDs, 8);

		// add length codes and item count
		$bin .= $this->decbin_pad($item_length, 4);
		$bin .= $this->decbin_pad($mod_length, 4);
		$bin .= $this->decbin_pad($item_count, 3);

		// add items
		foreach($this->items as $item){
			$bin .= $this->decbin_pad($item['slot'], 3);
			$bin .= $this->decbin_pad($item['id'], $item_length);
			$bin .= $this->decbin_pad(count($item['mods']), 2);
			$bin .= $this->decbin_pad($item['color'], 4);

			// add mods for current item
			foreach($item['mods'] as $mod){
				$bin .= $this->decbin_pad($mod, $mod_length);
			}
		}

		return $this->encodeTemplate($bin);
	}

	/**
	 * Adds an equipment item
	 */
	public function addItem(int $id, int $color = 0, array $mods = []):self{

		if(!isset(self::ITEM_TO_SLOT[$id])){
			throw new InvalidArgumentException('invalid item id');
		}

		if(!isset(self::ITEM_COLORS[$color])){
			throw new InvalidArgumentException('invalid color id');
		}

		$slot = self::ITEM_TO_SLOT[$id];

		$this->items[$slot] = [
			'id'    => $id,
			'slot'  => $slot,
			'color' => $color,
			'mods'  => $this->normalizeMods($mods),
		];

		return $this;
	}

	/**
	 * Clears all currently added equipment items
	 */
	public function clearItems():self{
		$this->items = [];

		return $this;
	}

	/**
	 * Normalizes/clamps mod IDs
	 */
	private function normalizeMods(array $mods):array{
		$normalizedMods = [];

		foreach($mods as $modID){

			// invalid
			if(!is_int($modID)){
				continue;
			}

			// out of range
			if($modID < 1 || $modID >= 0x200){
				continue;
			}

			$normalizedMods[] = $modID;
		}

		return $normalizedMods;
	}

}
