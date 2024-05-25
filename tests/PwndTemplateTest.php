<?php
/**
 * Class PwndTemplateTest
 *
 * @created      23.05.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
declare(strict_types=1);

namespace BuildWars\GWTemplatesTest;

use BuildWars\GWTemplates\PwndTemplate;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use function array_column;

/**
 * Tests the `PwndTemplate` class
 */
class PwndTemplateTest extends TestCase{

	public static function pwndTemplateProvider():array{
		return [
			[
				<<<PWND
				pwnd0000?download paw·ned² @ www.gw-tactics.de Copyright numma_cway aka Redeemer
				>aOwFj0xfzITOMMMHMie4O0k6PxZaPkpxFP9FzSqAA5AAJBAZBApBAJAAACgJIUGxheWVyAMMSAtIFdv
				dEEKZOAOj4wiM5MXTMm3cZS9dJOu5BpPkppFFEqtEAFEqncAFEaqmAFEaY7/EEaYRIHeqXjEAAACAgIW
				GFuZHJhATMiAtIFNvUy9TbWl0ZQoZOQNEApwT2zQDmemuhQOIDQEQjoPgp5PCicJCDBR6JzigItw4SQk
				htDIIyMgJHeqXjEPPgpghmZ9phOzriUAACIhGR3dlbgAOMyAtIFBhbml4CgZOQNDAcw9QvAIg5ZjOkAc
				QOBoRoPgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQPkpwRNz6TjdMvKSBAABMHTm9yZ3UAONCA
				tIEluZXAxCgZOQNDAawDSvAIg5ZrAFgZAEBoRoPgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQP
				kpwRNz6TjdMvKSBAACMBbUmF6YWggb3IgW01lcmNlbmFyeV0AONSAtIEluZXAyCgbOAhkQkGZIfMzdwQ
				M0qqSzJnw7iBoPgpZRCi8JiYBR6JXsgI7wMWQkhtDLISOALHeqXjELPkZwUP9akeKAACgJHTGl2aWEAL
				NiAtIEJpUAoZOAWiQyhMp7INN5I8Y5wJOOZNBpPkpxUP96Xfq4npI908npIDLropIvV3npIDr7npITFA
				AACEBbUmF6YWggb3IgW01lcmNlbmFyeV0AONyAtIFJlc3RvCgXOAOiAyk8gNtehzWilD56MvYpPkp5EF
				EKuEAFEqncAFEaqmAFEaY7/EEaYBIHiKbkILPkZAIP9akeKAACgBIWmVpIFJpAKOCAtIFNUCgYOABCY4
				xEAglAj4ngdQVFAQZAoPgpxlne9rPVaYKSPNvMFJYJRmiEKtATRGW7ipI7AAAAAHT2xpYXMBgNSAtIE1
				vUApzZWNvbmRhcnkgcHJvZmVzc2lvbiBhbmQgZWxpdGUgc2tpbGwgYXJlIGZyZWUsIGJhcmJzIGlzIG9
				wdGlvbmFsYOgNDwcjvOkk6hWEqtp9H0iaBpPkpBUPbTkiqwmpI900mpIDLbipIvSvmpIDrzmpINBAAAD
				AAgUWmhlZCBTaGFkb3dob29mAMNyAtIEUvTW8K<
				PWND,
				[
					'OwFj0xfzITOMMMHMie4O0k6PxZ',
					'OAOj4wiM5MXTMm3cZS9dJOu5B',
					'OQNEApwT2zQDmemuhQOIDQEQj',
					'OQNDAcw9QvAIg5ZjOkAcQOBoR',
					'OQNDAawDSvAIg5ZrAFgZAEBoR',
					'OAhkQkGZIfMzdwQM0qqSzJnw7iB',
					'OAWiQyhMp7INN5I8Y5wJOOZNB',
					'OAOiAyk8gNtehzWilD56MvY',
					'OABCY4xEAglAj4ngdQVFAQZA',
					'OgNDwcjvOkk6hWEqtp9H0iaB',
				],
				'pwnd0001?pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates'."\r\n".
				'>aOwFj0xfzITOMMMHMie4O0k6PxZaPkpxFP9FzSqAA5AAJBAZBApBAJAAAAIUGxheWVyAMMSAtIFdvdE'."\r\n".
				'EKZOAOj4wiM5MXTMm3cZS9dJOu5BpPkppFFEqtEAFEqncAFEaqmAFEaY7/EEaYRIHeqXjEAAAAIWGFuZ'."\r\n".
				'HJhATMiAtIFNvUy9TbWl0ZQoZOQNEApwT2zQDmemuhQOIDQEQjoPgp5PCicJCDBR6JzigItw4SQkhtDI'."\r\n".
				'IyMgJHeqXjEPPgpghmZ9phOzriUAAAGR3dlbgAOMyAtIFBhbml4CgZOQNDAcw9QvAIg5ZjOkAcQOBoRo'."\r\n".
				'PgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQPkpwRNz6TjdMvKSBAAAHTm9yZ3UAONCAtIEluZX'."\r\n".
				'AxCgZOQNDAawDSvAIg5ZrAFgZAEBoRoPgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQPkpwRNz6'."\r\n".
				'TjdMvKSBAAAbUmF6YWggb3IgW01lcmNlbmFyeV0AONSAtIEluZXAyCgbOAhkQkGZIfMzdwQM0qqSzJnw'."\r\n".
				'7iBoPgpZRCi8JiYBR6JXsgI7wMWQkhtDLISOALHeqXjELPkZwUP9akeKAAAHTGl2aWEALNiAtIEJpUAo'."\r\n".
				'ZOAWiQyhMp7INN5I8Y5wJOOZNBpPkpxUP96Xfq4npI908npIDLropIvV3npIDr7npITFAAAAbUmF6YWg'."\r\n".
				'gb3IgW01lcmNlbmFyeV0AONyAtIFJlc3RvCgXOAOiAyk8gNtehzWilD56MvYpPkp5EFEKuEAFEqncAFE'."\r\n".
				'aqmAFEaY7/EEaYBIHiKbkILPkZAIP9akeKAAAIWmVpIFJpAKOCAtIFNUCgYOABCY4xEAglAj4ngdQVFA'."\r\n".
				'QZAoPgpxlne9rPVaYKSPNvMFJYJRmiEKtATRGW7ipI7AAAAAHT2xpYXMBgNSAtIE1vUApzZWNvbmRhcn'."\r\n".
				'kgcHJvZmVzc2lvbiBhbmQgZWxpdGUgc2tpbGwgYXJlIGZyZWUsIGJhcmJzIGlzIG9wdGlvbmFsYOgNDw'."\r\n".
				'cjvOkk6hWEqtp9H0iaBpPkpBUPbTkiqwmpI900mpIDLbipIvSvmpIDrzmpINBAAAAUWmhlZCBTaGFkb3'."\r\n".
				'dob29mAMNyAtIEUvTW8K<'
			]
		];
	}

	#[Test]
	#[DataProvider('pwndTemplateProvider')]
	public function decodePwnd(string $pwnd, array $expected):void{
		$team = (new PwndTemplate)->decode($pwnd);

		$this::assertSame($expected, array_column($team, 'skills'));
	}

	#[Test]
	#[DataProvider('pwndTemplateProvider')]
	public function encodePwnd(string $pwnd, array $expected, string $expectedCode):void{
		$pwndTemplate = new PwndTemplate;

		$team = $pwndTemplate->decode($pwnd);

		foreach($team as $build){
			$pwndTemplate->addBuild(
				$build['skills'],
				$build['equipment'],
				$build['weaponsets'],
				$build['player'],
				$build['description'],
			);
		}

		$code = $pwndTemplate->encode();

		$this::assertSame($expectedCode, $code);

		$team = $pwndTemplate->decode($code);

		$this::assertSame($expected, array_column($team, 'skills'));
	}

}
