const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const postcssMixins = require('postcss-mixins');
const postcssPresetEnv = require('postcss-preset-env');
const devMode = process.env.NODE_ENV !== 'production';

module.exports = {
	entry: {
		frontend: path.resolve(process.cwd(), './src/frontend.js'),
		bootstrap: path.resolve(process.cwd(), './src/bootstrap.js'),
	},
	output: {
		path: path.resolve(__dirname, 'assets'),
		filename: !devMode ? './js/[name].min.js' : './js/[name].js',
		clean: true,
	},
	watch: devMode,
	devtool: 'eval-cheap-source-map',
	resolve: {
		alias: {
			lib: path.resolve(process.cwd(), './src/js/lib/'),
			blocks: path.resolve(process.cwd(), './src/js/blocks/'),
			modules: path.resolve(process.cwd(), './src/js/modules/'),
		},
		extensions: ['.js'],
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /(node_modules)/,
				resolve: {
					extensions: ['.js'],
				},
				use: {
					loader: 'babel-loader',
				},
			},
			{
				test: /\.(scss)$/,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: true,
						},
					},
					{
						// Run postcss actions
						loader: 'postcss-loader',
						options: {
							postcssOptions: {
								plugins: [require('autoprefixer'), require('postcss-import')],
							},
						},
					},
					{
						loader: 'sass-loader',
					},
				],
			},
			{
				test: /\.css$/,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: true,
						},
					},
					{
						loader: 'postcss-loader',
						options: {
							postcssOptions: {
								plugins: [
									require('autoprefixer'),
									require('postcss-import'),
									postcssMixins({
										mixinsDir: path.join(__dirname, 'src/postcss/mixins'),
									}),
									postcssPresetEnv({
										importFrom: path.join(
											__dirname,
											'src/postcss/variables.css'
										),
										exportTo: 'variables.css',
										stage: 1,
										features: {
											'custom-media-queries': true,
											'nesting-rules': true,
										},
									}),
								],
							},
						},
					},
				],
			},
		],
	},
	optimization: {
		minimizer: [new CssMinimizerPlugin()],
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: devMode ? './css/[name].css' : './css/[name].min.css',
		}),
		new CopyWebpackPlugin({
			patterns: [
				{
					from: path.resolve(__dirname, 'src/static-assets'),
					to: path.resolve(__dirname, 'static-assets'),
				},
			],
		}),
		// Lint CSS.
		new StyleLintPlugin({
			context: path.resolve(process.cwd(), './src/postcss/'),
			files: '**/*.css',
			failOnError: false,
		}),
		...(devMode
			? [
					new BrowserSyncPlugin({
						host: 'localhost',
						port: 3000,
						watch: true,
						proxy: {
							target: 'http://cordyceps.test',
							proxyReq: [
								(proxyReq) => {
									proxyReq.setHeader(
										'X-Cordyceps-Theme-Env',
										process.env.NODE_ENV
									);
								},
							],
						},
					}),
				]
			: []),
	],
	externals: {
		jQuery: 'jQuery',
	},
};
