import json from '@rollup/plugin-json';

/**
 * @type {import('rollup').RollupOptions}
 */
export default {
	input  : 'es6/index.js',
	output : [
		{
			file     : 'dist/gw-templates-es6-src.js',
			format   : 'es',
			sourcemap: true,
		},
		{
			file     : 'dist/gw-templates-node-src.cjs',
			format   : 'cjs',
			sourcemap: true,
		},
	],
	plugins: [
		json(),
	],
};
