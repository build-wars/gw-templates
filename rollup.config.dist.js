import babel from '@rollup/plugin-babel';
import json from '@rollup/plugin-json';
import terser from '@rollup/plugin-terser';

/**
 * @type {import('rollup').RollupOptions}
 */
export default {
	input  : 'es6/index.js',
	output : [
		{
			file     : 'dist/gw-templates-es6.js',
			format   : 'es',
			sourcemap: true,
		},
		{
			file     : 'dist/gw-templates-node.cjs',
			format   : 'cjs',
			sourcemap: true,
		},
	],
	plugins: [
		babel({
			babelHelpers: 'bundled',
			configFile  : './babel.config.json',
		}),
		json(),
		terser({
			format: {
				comments         : false,
				keep_quoted_props: true,
//				max_line_len: 130,
				quote_style: 1,
				preamble   :
					  '/*\n'
					+ ' * buildwars/gw-templates\n'
					+ ' * @copyright  2024 smiley\n'
					+ ' * @license    MIT\n'
					+ ' * @link       https://github.com/build-wars/gw-templates\n'
					+ ' */',
			},
		}),
	],
};
