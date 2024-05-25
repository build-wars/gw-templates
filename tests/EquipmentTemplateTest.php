<?php
/**
 * Class EquipmentTemplateTest
 *
 * @created      22.05.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
declare(strict_types = 1);

namespace BuildWars\GWTemplatesTest;

use BuildWars\GWTemplates\EquipmentTemplate;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Tests the `EquipmentTemplate` class
 */
class EquipmentTemplateTest extends TestCase{

	public static function equipmentTemplateProvider():array{
		return [
			[
				'PkpBUnYjleqwmkI900mkIDLbikIHRvmkILhzmkINBA',
				[
					0 => [
						'id'    => 336,
						'slot'  => 0,
						'color' => 4,
						'mods'  => [108, 150, 335],
					],
					2 => [
						'id'    => 216,
						'slot'  => 2,
						'color' => 4,
						'mods'  => [290, 158],
					],
					3 => [
						'id'    => 218,
						'slot'  => 3,
						'color' => 4,
						'mods'  => [290, 353],
					],
					4 => [
						'id'    => 77,
						'slot'  => 4,
						'color' => 4,
						'mods'  => [290, 35],
					],
					5 => [
						'id'    => 215,
						'slot'  => 5,
						'color' => 4,
						'mods'  => [290, 37],
					],
					6 => [
						'id'    => 217,
						'slot'  => 6,
						'color' => 4,
						'mods'  => [290, 38],
					],
				],
			],
			[
				'Pk5hSly6TjgkuKSF2kEpnm2kEZYZTkE5I61kEZJc2kEpJA',
				[
					0 => [
						'id'    => 330,
						'slot'  => 0,
						'color' => 4,
						'mods'  => [345, 335],
					],
					1 => [
						'id'    => 130,
						'slot'  => 1,
						'color' => 4,
						'mods'  => [343, 328],
					],
					2 => [
						'id'    => 216,
						'slot'  => 2,
						'color' => 4,
						'mods'  => [290, 158],
					],
					3 => [
						'id'    => 218,
						'slot'  => 3,
						'color' => 4,
						'mods'  => [290, 353],
					],
					4 => [
						'id'    => 77,
						'slot'  => 4,
						'color' => 4,
						'mods'  => [290, 35],
					],
					5 => [
						'id'    => 215,
						'slot'  => 5,
						'color' => 4,
						'mods'  => [290, 37],
					],
					6 => [
						'id'    => 217,
						'slot'  => 6,
						'color' => 4,
						'mods'  => [290, 38],
					],
				],

			],
			[
				'Pgp5PCjcJCXhRCWnrwItw0VYkgt3KMSwiJHsIb+K',
				[
					0 => [
						'id'    => 147,
						'slot'  => 0,
						'color' => 0,
						'mods'  => [278, 108, 351],
					],
					2 => [
						'id'    => 184,
						'slot'  => 2,
						'color' => 0,
						'mods'  => [291, 352],
					],
					3 => [
						'id'    => 186,
						'slot'  => 3,
						'color' => 0,
						'mods'  => [291, 352],
					],
					4 => [
						'id'    => 63,
						'slot'  => 4,
						'color' => 0,
						'mods'  => [291, 75],
					],
					5 => [
						'id'    => 183,
						'slot'  => 5,
						'color' => 0,
						'mods'  => [291, 352],
					],
					6 => [
						'id'    => 185,
						'slot'  => 6,
						'color' => 0,
						'mods'  => [291, 22],
					],
				],
			],

		];
	}

	#[Test]
	#[DataProvider('equipmentTemplateProvider')]
	public function decodeEquipment(string $template, array $expected):void{
		$equipment = (new EquipmentTemplate)->decode($template);

		$this::assertSame($expected, $equipment);
	}

	#[Test]
	#[DataProvider('equipmentTemplateProvider')]
	public function encodeEquipment(string $template, array $expected):void{
		$equipmentTemplate = new EquipmentTemplate;

		foreach($expected as $item){
			$equipmentTemplate->addItem($item['id'], $item['color'], $item['mods']);
		}

		$code = $equipmentTemplate->encode();

		$equipment = (new EquipmentTemplate)->decode($code);

		$this::assertSame($expected, $equipment);
	}

}
