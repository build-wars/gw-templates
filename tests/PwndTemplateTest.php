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

	/**
	 * @return array{0: string, 1: string[], 2: string[], 3: string}[]
	 */
	public static function pwndTemplateProvider():array{
		return [
			[
				<<<PWND1
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
				'pwnd0000?pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates'."\n".
				'>aOwFj0xfzITOMMMHMie4O0k6PxZaPkpxFP9FzSqAA5AAJBAZBApBAJAAAEgJAAIUGxheWVyAMMSAtIF'."\n".
				'dvdEEKZOAOj4wiM5MXTMm3cZS9dJOu5BpPkppFFEqtEAFEqncAFEaqmAFEaY7/EEaYRIHeqXjEAAAEAg'."\n".
				'AAIWGFuZHJhATMiAtIFNvUy9TbWl0ZQoZOQNEApwT2zQDmemuhQOIDQEQjoPgp5PCicJCDBR6JzigItw'."\n".
				'4SQkhtDIIyMgJHeqXjEPPgpghmZ9phOzriUAAEIhAAGR3dlbgAOMyAtIFBhbml4CgZOQNDAcw9QvAIg5'."\n".
				'ZjOkAcQOBoRoPgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQPkpwRNz6TjdMvKSBAAEMAAAHTm9'."\n".
				'yZ3UAONCAtIEluZXAxCgZOQNDAawDSvAIg5ZrAFgZAEBoRoPgpZQCikJCXBR6JnrgItw0VQkht3KIywC'."\n".
				'KHeqXjEQPkpwRNz6TjdMvKSBAAEMBAAbUmF6YWggb3IgW01lcmNlbmFyeV0AONSAtIEluZXAyCgbOAhk'."\n".
				'QkGZIfMzdwQM0qqSzJnw7iBoPgpZRCi8JiYBR6JXsgI7wMWQkhtDLISOALHeqXjELPkZwUP9akeKAAEg'."\n".
				'JAAHTGl2aWEALNiAtIEJpUAoZOAWiQyhMp7INN5I8Y5wJOOZNBpPkpxUP96Xfq4npI908npIDLropIvV'."\n".
				'3npIDr7npITFAAAEEBAAbUmF6YWggb3IgW01lcmNlbmFyeV0AONyAtIFJlc3RvCgXOAOiAyk8gNtehzW'."\n".
				'ilD56MvYpPkp5EFEKuEAFEqncAFEaqmAFEaY7/EEaYBIHiKbkILPkZAIP9akeKAAEgBAAIWmVpIFJpAK'."\n".
				'OCAtIFNUCgYOABCY4xEAglAj4ngdQVFAQZAoPgpxlne9rPVaYKSPNvMFJYJRmiEKtATRGW7ipI7AAAAA'."\n".
				'HT2xpYXMBgNSAtIE1vUApzZWNvbmRhcnkgcHJvZmVzc2lvbiBhbmQgZWxpdGUgc2tpbGwgYXJlIGZyZW'."\n".
				'UsIGJhcmJzIGlzIG9wdGlvbmFsYOgNDwcjvOkk6hWEqtp9H0iaBpPkpBUPbTkiqwmpI900mpIDLbipIv'."\n".
				'SvmpIDrzmpINBAAAAUWmhlZCBTaGFkb3dob29mAMNyAtIEUvTW8K<',
			],
			[
				<<<PWND2
				pwnd0001?download pawned2 @ memorial.redeemer.biz | Copyright 2008-2018 Redeemer
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
				'pwnd0001?pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates'."\n".
				'>ZOACiQyiMVNxMNAa5YsdN5DWOBpPkpRIPZzXjq4npI908npIDLtopItV3npIDr7npITFAAAIAhA//PA'."\n".
				'AAAGU29TCgZOQNEAqwD2yQDmeDhLQOIDQEQjoPgpxkne9rPVYYKSPNuMFZY5PmicJdATRmBzipItAAAA'."\n".
				'EIhAAAAIUGFuaXgKZOQNDAcw9QvAIg5ZrAkAcQOBoRoPgpBlne9rPVYYKSPNuMFZYZQmikJdATRmBzip'."\n".
				'ItAAAAEMAAAAATSW5lcC9FcGlkZW1pYwobOQNEAawD2C9CgAmntCUAmBQEgGBoPgpBlne9rPVYYKSPNu'."\n".
				'MFZYZQmikJdATRmBzipItAAAAEMBAAAAPSW5lcC9JbnNwaQocOAhkUwG4hEqUMzXgC4Wodg00kTVFoPg'."\n".
				'phlne9rPVEbKSPNjNFZYZRmusGdYTRGWXspI7AAAAEEIAAAAWRGlzY29yZC9SZXN0byAxCgcOAhkUwG4'."\n".
				'hEqUMzXgC4Wowj00kTVFoPgphlne9rPVEbKSPNjNFZYZRmusGdYTRGWXspI7AAAAEEIAAAAWRGlzY29y'."\n".
				'ZC9SZXN0byAyCgcOAhkQoGYIfI0dwQjdAnowj00kTVFoPgpRlnsxSPVEbiWPNjNRbY5QmolGdYT0yBXs'."\n".
				'Ja7AAAAEYJAAAAOQmlQL1Jlc3RvCgXOAOiAyk8gNtehTLXLB56MvYpPkpxHPZzXto4npI908npIDLnop'."\n".
				'IxV3npIDr7npITFAAAEgCAAAALU1QgUHJvdAo<',
			],
			[
				<<<PWND3
				pwnd0001?download pawned2 @ memorial.redeemer.biz | Copyright 2008-2018 Redeemer
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
				'pwnd0001?pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates'."\n".
				'>cOwFkMyd534lkDjzzBjoHuDNZcm+DoPkpxFP9FySqIlpI90MlpIDLfYpI7oMFZpcpMFpoALPkZATPZj'."\n".
				'lsILPcZg8z6QJpCRPgpgnnN4SJNSauVlCIjLA//PAArxMTExMTExMTExMTExMTExMTExMTExMTExMTEx'."\n".
				'MTExMQFWRU5D1kRJTkctVMRTVAowMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3'."\n".
				'ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIz'."\n".
				'NDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVm'."\n".
				'MDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFi'."\n".
				'Y2RlZjAxMjM0NTY3ODlhYmNkZWYwMQQOQBAAAAAAAAAAAAAAAAAIAAA//BAAAACCgQOQBAAAAAAAAAAA'."\n".
				'AAAAAAAAACCgQOABAAAAAAAAAAAAAAAAAAAACCg<',
			],
			[
				<<<PWND4
				pwnd0002?download pawned2 @ memorial.redeemer.biz | Copyright 2008-2018 Redeemer
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
				'pwnd0002?pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates'."\n".
				'>cOwFkMyd534lkDjzzBjoHuDNZcm+DoPkpxFP9FySqIlpI90MlpIDLfYpI7oMFZpcpMFpoALPkZATPZj'."\n".
				'lsILPcZg8z6QJpCRPgpgnnN4SJNSauVlCIjLA//PAA+w4TDhMOEw4TDhMOEw4TDhMOEw4TDhMOEw4TDh'."\n".
				'MOEw4TDhMOEw4TDhMOEw4TDhAFYRU5Dw5ZESU5HLVTDhFNUCjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2N'."\n".
				'zg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyM'."\n".
				'zQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZ'."\n".
				'jAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhY'."\n".
				'mNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxQOQBAAAAAAAAAAAAAAAAAIAAA//BA'."\n".
				'Ao5Lit5paH5Lit5paH5Lit5paH5Lit5paH5Lit5paHFU5Lit5paHCuS4reaWh+S4reaWh+S4reaWh+S4'."\n".
				'reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4'."\n".
				'reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4'."\n".
				'reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4'."\n".
				'reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWhwAAQOQBAAAAAAAAAAAA'."\n".
				'AAAAAAo57K16Kqe57K16Kqe57K16Kqe57K16Kqe57K16KqeFU57K16KqeCueyteiqnueyteiqnueytei'."\n".
				'qnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueytei'."\n".
				'qnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueytei'."\n".
				'qnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueytei'."\n".
				'qnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqngAAQOABAAAAAAA'."\n".
				'AAAAAAAAAAAo7ZWc6rWt7Ja07ZWc6rWt7Ja07ZWc6rWt7Ja07ZWcFU7ZWc6rWt7Ja0Cu2VnOq1reyWtO'."\n".
				'2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1re'."\n".
				'yWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnO'."\n".
				'q1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO'."\n".
				'2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtAAA<',
			],
		];
	}

	/**
	 * @param string[] $expectedSkills
	 * @param string[] $expectedTemplatenames
	 */
	#[Test]
	#[DataProvider('pwndTemplateProvider')]
	public function encodePwnd(
		string $pwnd,
		array $expectedSkills,
		array $expectedTemplatenames,
		string $expectedCode,
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

		$this::assertSame($expectedCode, $code);

		// decode the freshly encoded template and check again
		$team = $pwndTemplate->decode($code);

		$this::assertSame($expectedSkills, array_column($team, 'skills'));
		$this::assertSame($expectedTemplatenames, array_column($team, 'templatename'));
	}

}
