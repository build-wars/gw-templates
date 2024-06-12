/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

import {PwndTemplate} from '../es6/index.js';
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
			'pwnd0000?download paw·ned² @ www.gw-tactics.de Copyright numma_cway aka Redeemer\n' +
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
			'AAgUWmhlZCBTaGFkb3dob29mAMNyAtIEUvTW8K<\n',
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
			'pwnd0001?pwnd-encoder by @codemasher: https://github.com/build-wars/gw-templates\r\n' +
			'>aOwFj0xfzITOMMMHMie4O0k6PxZaPkpxFP9FzSqAA5AAJBAZBApBAJAAAAIUGxheWVyAMMSAtIFdvdE\r\n' +
			'EKZOAOj4wiM5MXTMm3cZS9dJOu5BpPkppFFEqtEAFEqncAFEaqmAFEaY7/EEaYRIHeqXjEAAAAIWGFuZ\r\n' +
			'HJhATMiAtIFNvUy9TbWl0ZQoZOQNEApwT2zQDmemuhQOIDQEQjoPgp5PCicJCDBR6JzigItw4SQkhtDI\r\n' +
			'IyMgJHeqXjEPPgpghmZ9phOzriUAAAGR3dlbgAOMyAtIFBhbml4CgZOQNDAcw9QvAIg5ZjOkAcQOBoRo\r\n' +
			'PgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQPkpwRNz6TjdMvKSBAAAHTm9yZ3UAONCAtIEluZX\r\n' +
			'AxCgZOQNDAawDSvAIg5ZrAFgZAEBoRoPgpZQCikJCXBR6JnrgItw0VQkht3KIywCKHeqXjEQPkpwRNz6\r\n' +
			'TjdMvKSBAAAbUmF6YWggb3IgW01lcmNlbmFyeV0AONSAtIEluZXAyCgbOAhkQkGZIfMzdwQM0qqSzJnw\r\n' +
			'7iBoPgpZRCi8JiYBR6JXsgI7wMWQkhtDLISOALHeqXjELPkZwUP9akeKAAAHTGl2aWEALNiAtIEJpUAo\r\n' +
			'ZOAWiQyhMp7INN5I8Y5wJOOZNBpPkpxUP96Xfq4npI908npIDLropIvV3npIDr7npITFAAAAbUmF6YWg\r\n' +
			'gb3IgW01lcmNlbmFyeV0AONyAtIFJlc3RvCgXOAOiAyk8gNtehzWilD56MvYpPkp5EFEKuEAFEqncAFE\r\n' +
			'aqmAFEaY7/EEaYBIHiKbkILPkZAIP9akeKAAAIWmVpIFJpAKOCAtIFNUCgYOABCY4xEAglAj4ngdQVFA\r\n' +
			'QZAoPgpxlne9rPVaYKSPNvMFJYJRmiEKtATRGW7ipI7AAAAAHT2xpYXMBgNSAtIE1vUApzZWNvbmRhcn\r\n' +
			'kgcHJvZmVzc2lvbiBhbmQgZWxpdGUgc2tpbGwgYXJlIGZyZWUsIGJhcmJzIGlzIG9wdGlvbmFsYOgNDw\r\n' +
			'cjvOkk6hWEqtp9H0iaBpPkpBUPbTkiqwmpI900mpIDLbipIvSvmpIDrzmpINBAAAAUWmhlZCBTaGFkb3\r\n' +
			'dob29mAMNyAtIEUvTW8K<',
		]
	];

	pwndTemplateProvider.forEach(([$pwnd, $expected, $expectedCode]) => {

		test('decodePwnd', function(){
			let team   = _pwndTemplate.decode($pwnd);
			let actual = team.map(build => build.skills);

			assert.deepEqual(actual, $expected);
		});

		test('encodePwnd', function(){
			let team = _pwndTemplate.decode($pwnd);

			for(let build of team){
				_pwndTemplate.addBuild(
					build.skills,
					build.equipment,
					build.weaponsets,
					build.player,
					build.description,
				);
			}

			let code = _pwndTemplate.encode();

			assert.strictEqual(code, $expectedCode);
		});

	});

});
