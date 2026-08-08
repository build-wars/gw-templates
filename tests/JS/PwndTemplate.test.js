/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */
import {PwndTemplate} from '../../es6/index.js';

import {beforeEach, suite, test} from 'mocha';
import {assert} from 'chai';

suite('SkillTemplateTest', function(){

	let _pwndTemplate;

	beforeEach(function(){
		_pwndTemplate = new PwndTemplate();
	});

	test('instance', function(){
		assert.instanceOf(_pwndTemplate, PwndTemplate);
	});

	let pwndTemplateProvider = [
		[
			'pwnd0000' + '?' + PwndTemplate.PWND_HEADER_COMMENT + '\n' +
			'>aOwFj0xfzITOMMMHMie4O0k6PxZaPkpxFP9FzSqAA5AAJBAZBApBAJAAACgJIUGxheWVyAMMSAtIFdv\n' +
			'dEEKZOAOj4wiM5MXTMm3cZS9dJOu5BpPkppFFEqtEAFEqncAFEaqmAFEaY7/EEaYRIHeqXjEAAACAgIW\n' +
			'GFuZHJhATMiAtIFNvUy9TbWl0ZQoZOQNEApwT2zQDmemuhQOIDQEQjoPgp5PCicJCDBR6JzigItw4SQk\n' +
			'htDIIyMgJHeqXjEPPgpghmZ9phOzriUAACIhGR3dlbgAOMyAtIFBhbml4CgZOQNDAcw9QvAIg5ZjOkAc\n' +
			'QOBoRoPgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQPkpwRNz6TjdMvKSBAABMHTm9yZ3UAONCA\n' +
			'tIEluZXAxCgZOQNDAawDSvAIg5ZrAFgZAEBoRoPgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQP\n' +
			'kpwRNz6TjdMvKSBAACMBbUmF6YWggb3IgW01lcmNlbmFyeV0AONSAtIEluZXAyCgbOAhkQkGZIfMzdwQ\n' +
			'M0qqSzJnw7iBoPgpZRCi8JiYBR6JXsgI7wMWQkhtDLISOALHeqXjELPkZwUP9akeKAACgJHTGl2aWEAL\n' +
			'NiAtIEJpUAoZOAWiQyhMp7INN5I8Y5wJOOZNBpPkpxUP96Xfq4npI908npIDLropIvV3npIDr7npITFA\n' +
			'AACEBbUmF6YWggb3IgW01lcmNlbmFyeV0AONyAtIFJlc3RvCgXOAOiAyk8gNtehzWilD56MvYpPkp5EF\n' +
			'EKuEAFEqncAFEaqmAFEaY7/EEaYBIHiKbkILPkZAIP9akeKAACgBIWmVpIFJpAKOCAtIFNUCgYOABCY4\n' +
			'xEAglAj4ngdQVFAQZAoPgpxlne9rPVaYKSPNvMFJYJRmiEKtATRGW7ipI7AAAAAHT2xpYXMBgNSAtIE1\n' +
			'vUApzZWNvbmRhcnkgcHJvZmVzc2lvbiBhbmQgZWxpdGUgc2tpbGwgYXJlIGZyZWUsIGJhcmJzIGlzIG9\n' +
			'wdGlvbmFsYOgNDwcjvOkk6hWEqtp9H0iaBpPkpBUPbTkiqwmpI900mpIDLbipIvSvmpIDrzmpINBAAAD\n' +
			'AAgUWmhlZCBTaGFkb3dob29mAMNyAtIEUvTW8K<',
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
			'pwnd0001' + '?' + PwndTemplate.PWND_HEADER_COMMENT + '\n' +
			'>cOwFkMyd534lkDjzzBjoHuDNZcm+DoPkpxFP9FySqIlpI90MlpIDLfYpI7oMFZpcpMFpoALPkZATPZj\n'+
			'lsILPcZg8z6QJpCRPgpgnnN4SJNSauVlCGjLA//PrxMTExMTExMTExMTExMTExMTExMTExMTExMTExMT\n'+
			'ExMQFWRU5D1kRJTkctVMRTVAowMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3OD\n'+
			'lhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzND\n'+
			'U2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMD\n'+
			'EyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2\n'+
			'RlZjAxMjM0NTY3ODlhYmNkZWYwMQQOQBAAAAAAAAAAAAAAAAAGAAA//BAACCgQOQBAAAAAAAAAAAAAAA\n'+
			'AAAAACCgQOABAAAAAAAAAAAAAAAAAAAACCg<',
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
				'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01', // eslint-disable-line
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
			'pwnd0002'+ '?' + PwndTemplate.PWND_HEADER_COMMENT + '\n' +
			'>cOwFkMyd534lkDjzzBjoHuDNZcm+DoPkpxFP9FySqIlpI90MlpIDLfYpI7oMFZpcpMFpoALPkZATPZj\n'+
			'lsILPcZg8z6QJpCRPgpgnnN4SJNSauVlCGjLA//P+w4TDhMOEw4TDhMOEw4TDhMOEw4TDhMOEw4TDhMO\n'+
			'Ew4TDhMOEw4TDhMOEw4TDhAFYRU5Dw5ZESU5HLVTDhFNUCjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg\n'+
			'5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ\n'+
			'1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjA\n'+
			'xMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmN\n'+
			'kZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxQOQBAAAAAAAAAAAAAAAAAGAAA//Bo5L\n'+
			'it5paH5Lit5paH5Lit5paH5Lit5paH5Lit5paHFS5Lit5paHCuS4reaWh+S4reaWh+S4reaWh+S4reaW\n'+
			'h+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaW\n'+
			'h+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaW\n'+
			'h+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaW\n'+
			'h+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWh+S4reaWhwQOQBAAAAAAAAAAAAAAAAAA\n'+
			'o57K16Kqe57K16Kqe57K16Kqe57K16Kqe57K16KqeFS57K16KqeCueyteiqnueyteiqnueyteiqnueyt\n'+
			'eiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyt\n'+
			'eiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyt\n'+
			'eiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyt\n'+
			'eiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqnueyteiqngQOABAAAAAAAAAAAAAAA\n'+
			'AAAo7ZWc6rWt7Ja07ZWc6rWt7Ja07ZWc6rWt7Ja07ZWcFS7ZWc6rWt7Ja0Cu2VnOq1reyWtO2VnOq1re\n'+
			'yWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnO\n'+
			'q1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO\n'+
			'2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1re\n'+
			'yWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtO2VnOq1reyWtA<',
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
				'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01', // eslint-disable-line
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
			'pwnd0001' + '?' + PwndTemplate.PWND_HEADER_COMMENT + '\n' +
			'>dOQAVEZpNNqVNSsGqosYqZuAAAAAAAAAAAGsaIBAOCVwADVwobOgAUcZrhtsRj1bGzG4aAAAAAAAAAA\n'+
			'AAElaABCUgADUgobOwAUA5m5snRh1+D4EBEAAAAAAAAAAAAEjVABDTW8AETW8KbOABEYMZldG9VzF4FY\n'+
			'VBAAAAAAAAAAAAEjqABCTgADTgobOQBEAcYiNG5VjAO9UBAAAAAAAAAAAAAEsaABDTWUAETWUKcOgBFw\n'+
			'Mapp2aEY93ATTvg6AAAAAAAAAAAEjRoBCRQADRQocOwBkMydmn9ZEZtxnwHuDAAAAAAAAAAAAEjVABCQ\n'+
			'QADQQocOACkQygWoJaUZN0En4lDAAAAAAAAAAAAEjVABDUnQAEUnQKcOQCkgylmpda0Z9CG9WGGAAAAA\n'+
			'AAAAAAAEjVABCUAADUAocOgCkwypmqtakZtAW3n5FAAAAAAAAAAAAEjVABCRAADRAo<',
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
			'pwnd0002' + '?' + PwndTemplate.PWND_HEADER_COMMENT + '\n' +
			'>ZOACiQyiMVNxMNAa5YsdN5DWOBpPkpRIPZzXjq4npI908npIDLtopItV3npIDr7npITFAAAGAhAAAOo\n'+
			'5Lit5paH5Lit5paH5Lit5paH5Lit5paH5Lit5paHFWU29TCjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nz\n'+
			'g5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMz\n'+
			'Q1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZj\n'+
			'AxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYm\n'+
			'NkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYgZOQNEAqwD2yQDmeD\n'+
			'hLQOIDQEQjoPgpxkne9rPVYYKSPNuMFZY5PmicJdATRmBzipItAPPgpghmZ9phOzriUAAEQjAHo57K16\n'+
			'Kqe57K16Kqe57K16Kqe57K16Kqe57K16KqeFWUGFuaXgKMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODl\n'+
			'hYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU\n'+
			'2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDE\n'+
			'yMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2R\n'+
			'lZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OQZOQNDAcw9QvAIg5ZrAkAc\n'+
			'QOBoRoPgpBlne9rPVYYKSPNuMFZYZQmikJdATRmBzipItAQPkpwRNz6TjdMvKSBAAEUAAHo7ZWc6rWt7\n'+
			'Ja07ZWc6rWt7Ja07ZWc6rWt7Ja07ZWcFWSW5lcC9FcGlkZW1pYwowMTIzNDU2Nzg5YWJjZGVmMDEyMzQ\n'+
			'1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjA\n'+
			'xMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmN\n'+
			'kZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg\n'+
			'5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMQbOQNEAawD2C9CgAmntCUAmBQE\n'+
			'gGBoPgpBlne9rPVYYKSPNuMFZYZQmikJdATRmBzipItAQPkpwRNz6TjdMvKSBAAEUDAHo5Lit5paH5Li\n'+
			't5paH5Lit5paH5Lit5paH5Lit5paHFWSW5lcC9JbnNwaQowMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4O\n'+
			'WFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0N\n'+
			'TY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwM\n'+
			'TIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZ\n'+
			'GVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNAcOAhkUwG4hEqUMzXgC4Wodg00kT\n'+
			'VFoPgphlne9rPVEbKSPNjNFZYZRmusGdYTRGWXspI7AAAACERo57K16Kqe57K16Kqe57K16Kqe57K16K\n'+
			'qe57K16KqeFWRGlzY29yZC9SZXN0byAxCjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEy\n'+
			'MzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2Rl\n'+
			'ZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlh\n'+
			'YmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2\n'+
			'Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZgcOAhkUwG4hEqUMzXgC4Wowj00kTVFoPgphlne9rPVEbKSP\n'+
			'NjNFZYZRmusGdYTRGWXspI7AAAACEIo7ZWc6rWt7Ja07ZWc6rWt7Ja07ZWc6rWt7Ja07ZWcFWRGlzY29\n'+
			'yZC9SZXN0byAyCjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjA\n'+
			'xMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmN\n'+
			'kZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg\n'+
			'5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ\n'+
			'1Njc4OWFiY2RlZgcOAhkQoGYIfI0dwQjdAnowj00kTVFoPgpRlnsxSPVEbiWPNjNRbY5QmolGdYT0yBX\n'+
			'sJa7AAAACYJo5Lit5paH5Lit5paH5Lit5paH5Lit5paH5Lit5paHFWQmlQL1Jlc3RvCjAxMjM0NTY3OD\n'+
			'lhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzND\n'+
			'U2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMD\n'+
			'EyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2\n'+
			'RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NQXOAO\n'+
			'iAyk8gNtehTLXLB56MvYpPkpxHPZzXto4npI908npIDLnopIxV3npIDr7npITFAAACgCo57K16Kqe57K\n'+
			'16Kqe57K16Kqe57K16Kqe57K16KqeFWU1QgUHJvdAowMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY\n'+
			'2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3O\n'+
			'DlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzN\n'+
			'DU2Nzg5YWJjZGVmMDEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nzg5YWJjZGVmM\n'+
			'DEyMzQ1Njc4OWFiY2RlZjAxMjM0NTY3ODlhYmNkZWYwMTIzNDU2Nw<',
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
				'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789ab', // eslint-disable-line
				'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789', // eslint-disable-line
				'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01', // eslint-disable-line
				'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01234', // eslint-disable-line
				'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef', // eslint-disable-line
				'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef', // eslint-disable-line
				'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef012345', // eslint-disable-line
				'0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef01234567', // eslint-disable-line
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

	pwndTemplateProvider.forEach(([
		$pwnd, $expectedSkills, $expectedTemplatenames, $expectedPlayers,
		$expectedDescriptions, $expectedAttributes, $expectedFlags,
	]) => {

		test('encodePwnd', function(){
			let team = _pwndTemplate.decode($pwnd);

			assert.deepEqual(Object.values(array_column(team, 'skills')), $expectedSkills);
			assert.deepEqual(Object.values(array_column(team, 'templatename')), $expectedTemplatenames);

			// now encode the given template
			// use the given template's encoding, otherwise we might run into length issues

			let headerflags  = PwndTemplate.parseHeader($pwnd);
			let pwndTemplate = new PwndTemplate(headerflags[3]);

			for(let build in team){
				pwndTemplate.addBuild(...Object.values(team[build]));
			}

			let code = pwndTemplate.encode();

			assert.strictEqual(code.replaceAll('\r', ''), $pwnd);

			// decode the freshly encoded template and check again
			team = PwndTemplate.fromTemplate(code);

			assert.deepEqual(Object.values(array_column(team, 'description')), $expectedDescriptions);
			assert.deepEqual(Object.values(array_column(team, 'player')), $expectedPlayers);
			assert.deepEqual(Object.values(array_column(team, 'attributes')), $expectedAttributes);
			assert.deepEqual(Object.values(array_column(team, 'flags')), $expectedFlags);
		});

	});

});

/**
 * @link https://locutus.io/php/array_column/
 *
 * @param {[]|{}}input
 * @param {number|string} columnKey
 * @param {number|string|null} indexKey
 * @returns {{}|undefined}
 */
function array_column(input, columnKey, indexKey = null){

	if(input === null || typeof input !== 'object'){
		return undefined;
	}

	let toPhpArrayObject = function(value){
		if(typeof value === 'object' && value !== null){
			return value;
		}

		return {};
	};

	let normalizedInput = Array.isArray(input) ? input : Object.values(toPhpArrayObject(input));
	let result = {};
	let fallbackIndex = 0;

	for(let rowValue of normalizedInput){
		let row = toPhpArrayObject(rowValue);
		let indexCandidate = indexKey === null ? undefined : row[String(indexKey)];

		let value = columnKey === null ? rowValue : row[String(columnKey)];
		if(indexCandidate !== undefined && indexCandidate !== null){
			result[String(indexCandidate)] = value;
		}
		else{
			result[String(fallbackIndex)] = value;
			fallbackIndex += 1;
		}
	}

	return result;
}
