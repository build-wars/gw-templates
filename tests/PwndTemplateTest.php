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
use function str_replace;

/**
 * Tests the `PwndTemplate` class
 */
class PwndTemplateTest extends TestCase{

	/**
	 * @return array{0: string, 1: string[], 2: string[]}[]
	 */
	public static function pwndTemplateProvider():array{
		return [
			[
				<<<PWND1
				pwnd0000
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
				PWND1,
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
				[
					'1 - WotA',
					'2 - SoS/Smite',
					'3 - Panix',
					'4 - Inep1',
					'5 - Inep2',
					'6 - BiP',
					'7 - Resto',
					'8 - ST',
					'5 - MoP',
					'7 - E/Mo',
				],
				[
					'Player',
					'Xandra',
					'Gwen',
					'Norgu',
					'Razah or [Mercenary]',
					'Livia',
					'Razah or [Mercenary]',
					'Zei Ri',
					'Olias',
					'Zhed Shadowhoof',
				],
				[
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'secondary profession and elite skill are free, barbs is optional',
					'',
				],
				[
					[4, 0, 1, 1, 0],
					[0, 0, 4, 0, 0],
					[1, 0, 4, 1, 0],
					[1, 4, 0, 0, 0],
					[1, 4, 0, 1, 0],
					[4, 0, 1, 1, 0],
					[0, 4, 0, 1, 0],
					[4, 0, 0, 1, 0],
					[0, 0, 0, 0, 0],
					[0, 0, 0, 0, 4],
				],
				[
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
				],
			],
			[
				<<<PWND2
				pwnd0001
				>ZOACiQyiMVNxMNAa5YsdN5DWOBpPkpRIPZzXjq4npI908npIDLtopItV3npIDr7npITFAAAGAhA//PA
				AGU29TCgZOQNEAqwD2yQDmeDhLQOIDQEQjoPgpxkne9rPVYYKSPNuMFZY5PmicJdATRmBzipItAAAACI
				hAAIUGFuaXgKZOQNDAcw9QvAIg5ZrAkAcQOBoRoPgpBlne9rPVYYKSPNuMFZYZQmikJdATRmBzipItAA
				AABMAATSW5lcC9FcGlkZW1pYwobOQNEAawD2C9CgAmntCUAmBQEgGBoPgpBlne9rPVYYKSPNuMFZYZQm
				ikJdATRmBzipItAAAACMBAAPSW5lcC9JbnNwaQocOAhkUwG4hEqUMzXgC4Wodg00kTVFoPgphlne9rPV
				EbKSPNjNFZYZRmusGdYTRGWXspI7AAAACEIAAWRGlzY29yZC9SZXN0byAxCgcOAhkUwG4hEqUMzXgC4W
				owj00kTVFoPgphlne9rPVEbKSPNjNFZYZRmusGdYTRGWXspI7AAAACEIAAWRGlzY29yZC9SZXN0byAyC
				gcOAhkQoGYIfI0dwQjdAnowj00kTVFoPgpRlnsxSPVEbiWPNjNRbY5QmolGdYT0yBXsJa7AAAACYJAAO
				QmlQL1Jlc3RvCgXOAOiAyk8gNtehTLXLB56MvYpPkpxHPZzXto4npI908npIDLnopIxV3npIDr7npITF
				AAACgCAALU1QgUHJvdAo<
				PWND2,
				[
					'OACiQyiMVNxMNAa5YsdN5DWOB',
					'OQNEAqwD2yQDmeDhLQOIDQEQj',
					'OQNDAcw9QvAIg5ZrAkAcQOBoR',
					'OQNEAawD2C9CgAmntCUAmBQEgGB',
					'OAhkUwG4hEqUMzXgC4Wodg00kTVF',
					'OAhkUwG4hEqUMzXgC4Wowj00kTVF',
					'OAhkQoGYIfI0dwQjdAnowj00kTVF',
					'OAOiAyk8gNtehTLXLB56MvY',
				],
				[
					'SoS',
					'Panix',
					'Inep/Epidemic',
					'Inep/Inspi',
					'Discord/Resto 1',
					'Discord/Resto 2',
					'BiP/Resto',
					'ST Prot',
				],
				[
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
				],
				[
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
				],
				[
					[0, 0, 4, 1, 0],
					[1, 0, 4, 1, 0],
					[1, 4, 0, 0, 0],
					[1, 4, 0, 1, 0],
					[0, 4, 1, 0, 0],
					[0, 4, 1, 0, 0],
					[3, 0, 1, 1, 0],
					[4, 0, 0, 2, 0],
				],
				[
					[true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
				],
			],
			[
				<<<PWND3
				pwnd0001
				>cOwFkMyd534lkDjzzBjoHuDNZcm+DoPkpxFP9FySqIlpI90MlpIDLfYpI7oMFZpcpMFpoALPkZATPZj
				lsILPcZg8z6QJpCRPgpgnnN4SJNSauVlCGjLA//PrxMTExMTExMTExMTExMTExMTExMTExMTExMTExMT
				ExMQFWRU5D1kRJTkctVMRTVAowMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3OD
				lhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzND
				U2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMD
				EyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2
				RlZjAxMjM0NTY3ODlhYmNkZWYwMQQOQBAAAAAAAAAAAAAAAAAGAAA//BAACCgQOQBAAAAAAAAAAAAAAA
				AAAAACCgQOABAAAAAAAAAAAAAAAAAAAACCg<
				PWND3,
				[
					'OwFkMyd534lkDjzzBjoHuDNZcm+D',
					'OQBAAAAAAAAAAAAA',
					'OQBAAAAAAAAAAAAA',
					'OABAAAAAAAAAAAAA',
				],
				[
					'ENCÖDING-TÄST',
					'',
					'',
					'',
				],
				[
					'ÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄ',
					'',
					'',
					'',
				],
				[
					'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01', // phpcs:ignore
					'',
					'',
					'',
				],
				[
					[4, 3, 1, 3, 0],
					[0, 0, 0, 0, 0],
					[0, 0, 0, 0, 0],
					[0, 0, 0, 0, 0],
				],
				[
					[true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true],
					[true, true, true, true, true, true, true, true, true, true, true, true, true, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
				],
			],
			[
				<<<PWND4
				pwnd0002
				>cOwFkMyd534lkDjzzBjoHuDNZcm+DoPkpxFP9FySqIlpI90MlpIDLfYpI7oMFZpcpMFpoALPkZATPZj
				lsILPcZg8z6QJpCRPgpgnnN4SJNSauVlCGjLA//P+w4TDhMOEw4TDhMOEw4TDhMOEw4TDhMOEw4TDhMO
				Ew4TDhMOEw4TDhMOEw4TDhAFYRU5Dw5ZESU5HLVTDhFNUCjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg
				5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ
				1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjA
				xMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmN
				kZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxQOQBAAAAAAAAAAAAAAAAAGAAA//Bo5L
				it5paH5Lit5paH5Lit5paH5Lit5paH5Lit5paHFS5Lit5paHCuS4reaWh+S4reaWh+S4reaWh+S4reaW
				h+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaW
				h+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaW
				h+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaW
				h+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWhwQOQBAAAAAAAAAAAAAAAAAA
				o57K16Kqe57K16Kqe57K16Kqe57K16Kqe57K16KqeFS57K16KqeCueyteiqnueyteiqnueyteiqnueyt
				eiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyt
				eiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyt
				eiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyt
				eiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqngQOABAAAAAAAAAAAAAAA
				AAAo7ZWc6rWt7Ja07ZWc6rWt7Ja07ZWc6rWt7Ja07ZWcFS7ZWc6rWt7Ja0Cu2VnOq1reyWtO2VnOq1re
				yWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnO
				q1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO
				2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1re
				yWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtA<
				PWND4,
				[
					'OwFkMyd534lkDjzzBjoHuDNZcm+D',
					'OQBAAAAAAAAAAAAA',
					'OQBAAAAAAAAAAAAA',
					'OABAAAAAAAAAAAAA',
				],
				[
					'ENCÖDING-TÄST',
					'中文',
					'粵語',
					'한국어',
				],
				[
					'ÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄÄ',
					'中文中文中文中文中文',
					'粵語粵語粵語粵語粵語',
					'한국어한국어한국어한',
				],
				[
					'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01', // phpcs:ignore
					'中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文中文',
					'粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語粵語',
					'한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어한국어',
				],
				[
					[4, 3, 1, 3, 0],
					[0, 0, 0, 0, 0],
					[0, 0, 0, 0, 0],
					[0, 0, 0, 0, 0],
				],
				[
					[true, true, true, true, true, true, true, true, true, true, true, true, true, true, true, true],
					[true, true, true, true, true, true, true, true, true, true, true, true, true, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
				],
			],
			[
				<<<PWND5
				pwnd0001
				>dOQAVEZpNNqVNSsGqosYqZuAAAAAAAAAAAGsaIBAOCVwADVwobOgAUcZrhtsRj1bGzG4aAAAAAAAAAA
				AAElaABCUgADUgobOwAUA5m5snRh1+D4EBEAAAAAAAAAAAAEjVABDTW8AETW8KbOABEYMZldG9VzF4FY
				VBAAAAAAAAAAAAEjqABCTgADTgobOQBEAcYiNG5VjAO9UBAAAAAAAAAAAAAEsaABDTWUAETWUKcOgBFw
				Mapp2aEY93ATTvg6AAAAAAAAAAAEjRoBCRQADRQocOwBkMydmn9ZEZtxnwHuDAAAAAAAAAAAAEjVABCQ
				QADQQocOACkQygWoJaUZN0En4lDAAAAAAAAAAAAEjVABDUnQAEUnQKcOQCkgylmpda0Z9CG9WGGAAAAA
				AAAAAAAEjVABCUAADUAocOgCkwypmqtakZtAW3n5FAAAAAAAAAAAAEjVABCRAADRAo<
				PWND5,
				[
					'OQAVEZpNNqVNSsGqosYqZuAAAAAAA',
					'OgAUcZrhtsRj1bGzG4aAAAAAAAA',
					'OwAUA5m5snRh1+D4EBEAAAAAAAA',
					'OABEYMZldG9VzF4FYVBAAAAAAAA',
					'OQBEAcYiNG5VjAO9UBAAAAAAAAA',
					'OgBFwMapp2aEY93ATTvg6AAAAAAA',
					'OwBkMydmn9ZEZtxnwHuDAAAAAAAA',
					'OACkQygWoJaUZN0En4lDAAAAAAAA',
					'OQCkgylmpda0Z9CG9WGGAAAAAAAA',
					'OgCkwypmqtakZtAW3n5FAAAAAAAA',
				],
				[
					'W',
					'R',
					'Mo',
					'N',
					'Me',
					'E',
					'A',
					'Rt',
					'P',
					'D',
				],
				[
					'W',
					'R',
					'Mo',
					'N',
					'Me',
					'E',
					'A',
					'Rt',
					'P',
					'D',
				],
				[
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
				],
				[
					[5, 4, 3, 2, 1],
					[4, 5, 3, 2, 0],
					[4, 3, 2, 5, 0],
					[4, 3, 5, 2, 0],
					[5, 4, 3, 2, 0],
					[4, 3, 2, 1, 5],
					[4, 3, 2, 5, 0],
					[4, 3, 2, 5, 0],
					[4, 3, 2, 5, 0],
					[4, 3, 2, 5, 0],
				],
				[
					[true, false, false, false, false, false, false, false, false, false, false, false, false, true, true, true],
					[true, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[true, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[true, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[true, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[true, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[true, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[true, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[true, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[true, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
				],
			],
			[
				<<<PWND6
				pwnd0002
				>ZOACiQyiMVNxMNAa5YsdN5DWOBpPkpRIPZzXjq4npI908npIDLtopItV3npIDr7npITFAAAGAhAAAOo
				5Lit5paH5Lit5paH5Lit5paH5Lit5paH5Lit5paHFWU29TCjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nz
				g5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMz
				Q1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZj
				AxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYm
				NkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYgZOQNEAqwD2yQDmeD
				hLQOIDQEQjoPgpxkne9rPVYYKSPNuMFZY5PmicJdATRmBzipItAPPgpghmZ9phOzriUAAEQjAHo57K16
				Kqe57K16Kqe57K16Kqe57K16Kqe57K16KqeFWUGFuaXgKMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODl
				hYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU
				2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDE
				yMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2R
				lZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OQZOQNDAcw9QvAIg5ZrAkAc
				QOBoRoPgpBlne9rPVYYKSPNuMFZYZQmikJdATRmBzipItAQPkpwRNz6TjdMvKSBAAEUAAHo7ZWc6rWt7
				Ja07ZWc6rWt7Ja07ZWc6rWt7Ja07ZWcFWSW5lcC9FcGlkZW1pYwowMTIzNDU2Nzg5YWJjZGVmMDEyMzQ
				1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjA
				xMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmN
				kZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg
				5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMQbOQNEAawD2C9CgAmntCUAmBQE
				gGBoPgpBlne9rPVYYKSPNuMFZYZQmikJdATRmBzipItAQPkpwRNz6TjdMvKSBAAEUDAHo5Lit5paH5Li
				t5paH5Lit5paH5Lit5paH5Lit5paHFWSW5lcC9JbnNwaQowMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4O
				WFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0N
				TY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwM
				TIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZ
				GVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNAcOAhkUwG4hEqUMzXgC4Wodg00kT
				VFoPgphlne9rPVEbKSPNjNFZYZRmusGdYTRGWXspI7AAAACERo57K16Kqe57K16Kqe57K16Kqe57K16K
				qe57K16KqeFWRGlzY29yZC9SZXN0byAxCjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEy
				MzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2Rl
				ZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlh
				YmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2
				Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZgcOAhkUwG4hEqUMzXgC4Wowj00kTVFoPgphlne9rPVEbKSP
				NjNFZYZRmusGdYTRGWXspI7AAAACEIo7ZWc6rWt7Ja07ZWc6rWt7Ja07ZWc6rWt7Ja07ZWcFWRGlzY29
				yZC9SZXN0byAyCjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjA
				xMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmN
				kZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg
				5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ
				1Njc4OWFiY2RlZgcOAhkQoGYIfI0dwQjdAnowj00kTVFoPgpRlnsxSPVEbiWPNjNRbY5QmolGdYT0yBX
				sJa7AAAACYJo5Lit5paH5Lit5paH5Lit5paH5Lit5paH5Lit5paHFWQmlQL1Jlc3RvCjAxMjM0NTY3OD
				lhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzND
				U2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMD
				EyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2
				RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NQXOAO
				iAyk8gNtehTLXLB56MvYpPkpxHPZzXto4npI908npIDLnopIxV3npIDr7npITFAAACgCo57K16Kqe57K
				16Kqe57K16Kqe57K16Kqe57K16KqeFWU1QgUHJvdAowMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY
				2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3O
				DlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzN
				DU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmM
				DEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nw<
				PWND6,
				[
					'OACiQyiMVNxMNAa5YsdN5DWOB',
					'OQNEAqwD2yQDmeDhLQOIDQEQj',
					'OQNDAcw9QvAIg5ZrAkAcQOBoR',
					'OQNEAawD2C9CgAmntCUAmBQEgGB',
					'OAhkUwG4hEqUMzXgC4Wodg00kTVF',
					'OAhkUwG4hEqUMzXgC4Wowj00kTVF',
					'OAhkQoGYIfI0dwQjdAnowj00kTVF',
					'OAOiAyk8gNtehTLXLB56MvY',
				],
				[
					'SoS',
					'Panix',
					'Inep/Epidemic',
					'Inep/Inspi',
					'Discord/Resto 1',
					'Discord/Resto 2',
					'BiP/Resto',
					'ST Prot',
				],
				[
					'中文中文中文中文中文',
					'粵語粵語粵語粵語粵語',
					'한국어한국어한국어한',
					'中文中文中文中文中文',
					'粵語粵語粵語粵語粵語',
					'한국어한국어한국어한',
					'中文中文中文中文中文',
					'粵語粵語粵語粵語粵語',
				],
				[
					'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789ab', // phpcs:ignore
					'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789', // phpcs:ignore
					'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01', // phpcs:ignore
					'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01234', // phpcs:ignore
					'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef', // phpcs:ignore
					'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef', // phpcs:ignore
					'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef012345', // phpcs:ignore
					'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01234567', // phpcs:ignore
				],
				[
					[0, 0, 4, 1, 0],
					[2, 0, 4, 3, 0],
					[2, 4, 0, 0, 0],
					[2, 4, 0, 3, 0],
					[0, 4, 2, 1, 0],
					[0, 4, 1, 0, 0],
					[3, 0, 1, 1, 0],
					[4, 0, 0, 2, 0],
				],
				[
					[false, false, false, false, false, false, false, false, false, false, false, false, false, true, true, true],
					[true, true, true, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[true, true, true, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[true, true, true, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
					[false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false],
				],
			],
		];
	}

	/**
	 * @param string[] $expectedSkills
	 * @param string[] $expectedTemplatenames
	 * @param string[] $expectedPlayers
	 * @param string[] $expectedDescriptions
	 * @param int[]    $expectedAttributes
	 * @param bool[]   $expectedFlags
	 */
	#[Test]
	#[DataProvider('pwndTemplateProvider')]
	public function encodePwnd(
		string $pwnd,
		array $expectedSkills,
		array $expectedTemplatenames,
		array $expectedPlayers,
		array $expectedDescriptions,
		array $expectedAttributes,
		array $expectedFlags,
	):void{
		// first decode and compare
		$team = (new PwndTemplate)->decode($pwnd);

		$this::assertSame($expectedSkills, array_column($team, 'skills'));
		$this::assertSame($expectedTemplatenames, array_column($team, 'templatename'));

		// now encode the given template
		// use the given template's encoding, otherwise we might run into length issues
		$headerflags  = PwndTemplate::parseHeader($pwnd);
		$pwndTemplate = new PwndTemplate($headerflags[3]);

		foreach($team as $build){
			$pwndTemplate->addBuild(...$build);
		}

		$code = $pwndTemplate->encode();

		$this::assertSame(str_replace("\r", '', $pwnd), str_replace('?'.PwndTemplate::PWND_HEADER_COMMENT, '', $code));

		// decode the freshly encoded template and check again
		$team = $pwndTemplate->decode($code);

		$this::assertSame($expectedSkills, array_column($team, 'skills'));
		$this::assertSame($expectedTemplatenames, array_column($team, 'templatename'));
		$this::assertSame($expectedDescriptions, array_column($team, 'description'));
		$this::assertSame($expectedPlayers, array_column($team, 'player'));
		$this::assertSame($expectedAttributes, array_column($team, 'attributes'));
		$this::assertSame($expectedFlags, array_column($team, 'flags'));
	}

}
