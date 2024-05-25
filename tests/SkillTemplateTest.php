<?php
/**
 * Class SkillTemplateTest
 *
 * @created      21.05.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace BuildWars\GWTemplatesTest;

use BuildWars\GWTemplates\SkillTemplate;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Tests the `SkillTemplate` class
 */
class SkillTemplateTest extends TestCase{

	public static function skillTemplateProvider():array{
		return [
			'A/W'  => [
				'OwFj0xfzITOMMMHMie4O0kxZ6PA',
				7,
				1,
				[29 => 12, 31 => 3, 35 => 12],
				[782, 780, 775, 1954, 952, 2356, 1649, 1018],
			],
			'Me/E' => [
				'OQZDAswzQqDuNmOTP2kBBiOwlA',
				5,
				6,
				[0 => 12, 2 => 12, 3 => 3],
				[234, 878, 934, 979, 2358, 65, 930, 2416],
			],
			'W'    => [
				'OQARUhAAAAAAAAAAAB',
				1,
				0,
				[21 => 0],
				[0, 0, 0, 0, 0, 0, 0, 1],
			],
		];
	}

	#[Test]
	#[DataProvider('skillTemplateProvider')]
	public function decodeSkills(string $template, int $pri, int $sec, array $attributes, array $skills):void{
		$build = (new SkillTemplate)->decode($template);
		$this::assertSame($pri, $build['prof_pri']);
		$this::assertSame($sec, $build['prof_sec']);
		$this::assertSame($attributes, $build['attributes']);
		$this::assertSame($skills, $build['skills']);
	}

	#[Test]
	#[DataProvider('skillTemplateProvider')]
	public function encodeSkills(string $template, int $pri, int $sec, array $attributes, array $skills):void{
		$code  = (new SkillTemplate)->encode($pri, $sec, $attributes, $skills);
		// the template codes may not necessarily match
		$build = (new SkillTemplate)->decode($code);

		$this::assertSame($pri, $build['prof_pri']);
		$this::assertSame($sec, $build['prof_sec']);
		$this::assertSame($attributes, $build['attributes']);
		$this::assertSame($skills, $build['skills']);
	}

}
