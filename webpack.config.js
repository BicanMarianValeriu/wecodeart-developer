const path = require('path');
const postcssPresetEnv = require('postcss-preset-env');
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const defaultConfig = require('./node_modules/@wordpress/scripts/config/webpack.config.js');

const devMode = process.env.NODE_ENV !== 'production';

module.exports = {
	...defaultConfig,
	entry: {
		'js/frontend': path.resolve(process.cwd(), 'src', 'js', 'frontend.js'),
		'css/frontend': path.resolve(process.cwd(), 'src', 'scss', 'style.scss'),
	},
	output: {
		path: path.resolve(process.cwd(), 'assets'),
		publicPath: `${devMode ? '/wca-docs' : ''}/wp-content/themes/${__dirname.split('\\').pop()}/assets/`,
		filename: '[name].js',
		chunkFilename: '[name].js'
	},
	optimization: {
		...defaultConfig.optimization,
		namedChunks: true,
		splitChunks: {
			cacheGroups: {
				frontend: {
					name: 'css/frontend',
					test: /style\.(sc|sa|c)ss$/,
					chunks: 'all',
					enforce: true,
				},
				default: false,
			},
		},
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.(sc|sa|c)ss$/,
				exclude: /node_modules/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader,
						options: {
							hmr: devMode,
							reloadAll: true,
						}
					},
					{
						loader: 'css-loader',
						options: {
							sourceMap: devMode,
							url: false,
						},
					},
					{
						loader: 'sass-loader',
						options: {
							sourceMap: devMode,
						},
					},
					{
						loader: 'postcss-loader',
						options: {
							ident: 'postcss',
							plugins: () => [
								postcssPresetEnv({
									autoprefixer: {
										remove: false,
										flexbox: true,
										grid: true
									},
									stage: 3,
									features: {
										'custom-media-queries': {
											preserve: false,
										},
										'custom-properties': {
											preserve: true,
										},
										'nesting-rules': true,
									},
								}),
							],
						},
					},
				],
			},
		],
	},
	externals: {
		jquery: 'jQuery'
	},
	plugins: [
		/* ...defaultConfig.plugins, */ // We Use custom similar setup but with BSync
		process.env.WP_BUNDLE_ANALYZER && new BundleAnalyzerPlugin(),
		new MiniCssExtractPlugin(),
		new IgnoreEmitPlugin([
			'css/frontend.js',
			'css/frontend.asset.php',
			'css/frontend.deps.json',
		]),
		/* This should not run on dev for hotreloading since is generating some PHP files for deps */
		!devMode ? new DependencyExtractionWebpackPlugin({
			injectPolyfill: true
		}) : false,
		devMode ? new BrowserSyncPlugin({
			host: 'localhost',
			proxy: `http://localhost/wca-docs`,
			port: 3000,
		}, {
			injectCss: true
		}) : false
	].filter(Boolean),
};