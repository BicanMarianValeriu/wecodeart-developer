// You can add other webpack configuration (plugins, loaders, etc).
// Apart from ES6 Import/Export, Gulp was able to do all my other work so this file is mainly empty.
const entry = require('./entry');

module.exports = {
	entry,
	mode: 'development',
	devtool: 'inline-cheap-source-map',
	output: {
		filename: '[name].js',
	},
	externals: {
		jquery: 'jQuery'
	}
	/* module: {
		rules: [
			{ // This tests js files and bundles them
				test: /\.js?$/,
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env'],
						plugins: [
							'@babel/plugin-proposal-object-rest-spread',
							'@babel/plugin-transform-async-to-generator'
						]
					}
				}
			}
		]
	} */
};