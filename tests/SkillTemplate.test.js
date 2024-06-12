/**
 * @created      11.06.2024
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2024 smiley
 * @license      MIT
 */

import {SkillTemplate} from '../es6/index.js';
import {beforeEach, suite, test} from 'mocha';
import {assert} from 'chai';

suite('SkillTemplateTest', function(){

	let _skillTemplate;

	beforeEach(function(){
		_skillTemplate = new SkillTemplate();
	});

	test('instance', function(){
		assert.instanceOf(_skillTemplate, SkillTemplate);
	});


	let skillTemplateProvider = [
		[
			'OwFj0xfzITOMMMHMie4O0kxZ6PA',
			7,
			1,
			{'29': 12, '31': 3, '35': 12},
			[782, 780, 775, 1954, 952, 2356, 1649, 1018],
		],
		[
			'OQZDAswzQqDuNmOTP2kBBiOwlA',
			5,
			6,
			{'0': 12, '2': 12, '3': 3},
			[234, 878, 934, 979, 2358, 65, 930, 2416],
		],
		[
			'OQARUhAAAAAAAAAAAB',
			1,
			0,
			{'21': 0},
			[0, 0, 0, 0, 0, 0, 0, 1],
		],
	];

	skillTemplateProvider.forEach(([$template, $pri, $sec, $attributes, $skills]) => {

		test('decodeSkills', function(){
			let build = _skillTemplate.decode($template);

			assert.strictEqual(build.prof_pri, $pri);
			assert.strictEqual(build.prof_sec, $sec);
			assert.deepEqual(build.attributes, $attributes);
			assert.deepEqual(build.skills, $skills);
		});

		test('encodeSkills', function(){
			let code  = _skillTemplate.encode($pri, $sec, $attributes, $skills);
			// the template codes may not necessarily match
			let build = _skillTemplate.decode(code);

			assert.strictEqual(build.prof_pri, $pri);
			assert.strictEqual(build.prof_sec, $sec);
			assert.deepEqual(build.attributes, $attributes);
			assert.deepEqual(build.skills, $skills);
		});

	});

});
