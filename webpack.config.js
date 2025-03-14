const path = require('path');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');
const MinifyJsPlugin = require("babel-minify-webpack-plugin");
const CopyPlugin = require("copy-webpack-plugin");
const ImageminPlugin = require('imagemin-webpack-plugin').default;

const entries = {};
const moduleScss = [];
const plugins = [];

module.exports = (env, argv) => {
	const isProd = () => argv.mode === 'production';
	process.env.NODE_ENV = isProd() ? 'production' : 'development';

	if (isProd()) {
		plugins.push(new MinifyJsPlugin())
	}

	return {
		entry: {
			"js/index": "./resources/js/index.js",
			"css/styles": "./resources/scss/styles.scss"
		},
		output: {
			filename: isProd() ? '[name].[contentHash].js' : '[name].js',
			path: path.resolve(__dirname, 'public/'),
		},
		devServer: {
			contentBase: path.join(__dirname, 'public/'),
			proxy: {
				'/': 'http://127.0.0.1:8080'
			},
			port: 9001,
			writeToDisk: true
		},
		module: {
			rules: [
				{
					test: /\.js$/,
					exclude: /(node_modules|bower_components)/,
					use: [
						{loader: 'babel-loader',},
						{loader: 'eslint-loader',}
					]
				},
				{
					test: /\.scss$/,
					use: [
						{loader: MiniCssExtractPlugin.loader},
						{loader: 'css-loader', options: {url: true, importLoaders: 1}},
						{loader: 'postcss-loader'},
						{loader: 'sass-loader'},
					],
				},
				{
					test: /\.(png|jpe?g|gif|svg)$/i,
					loader: 'file-loader',
					options: {
						outputPath: '/images/',
						name: isProd() ? '[name].[contentHash].[ext]' : '[name].[ext]',
					}
				},
				{
					test: /\.(eot|otf|ttf|woff|woff2)$/i,
					loader: 'file-loader',
					options: {
						outputPath: '/fonts/',
						name: isProd() ? '[name].[contentHash].[ext]' : '[name].[ext]',
					}
				},
			]
		},
		plugins: [
			new CleanWebpackPlugin({
				protectWebpackAssets: false,
				cleanOnceBeforeBuildPatterns: ['js/', 'css/', 'fonts/', 'images/', 'img/', 'manifest.json'],
				cleanAfterEveryBuildPatterns: ['css/*.js']
			}),
			new StylelintPlugin(),
			new CopyPlugin([{from: 'img', to: 'img'}], {context: "resources/"}),
			new ImageminPlugin({
				test: /\.(jpe?g|png|gif|svg)$/i,
				disable: !isProd(),
				svgo: {
					plugins: [
						{removeTitle: false}
					]
				}
			}),
			...plugins,
			new MiniCssExtractPlugin({filename: isProd() ? '[name].[contentHash].css' : '[name].css',}),
			new ManifestPlugin({
				filter: (file) => file.name.match(/^css\/.*\.css$/) || file.name.match(/^js\/.*\.js$/)
			})
		],
		optimization: {
			minimizer: []
		},
		performance: {
			hints: isProd() ? "warning" : false,
		},
		stats: {
			excludeAssets: (name) => {
				return name.match(/css\/.*\.js/)
					|| name.match(/manifest\.json$/)
					|| name.match(/\.(eot|otf|ttf|woff|woff2)$/i)
					|| name.match(/\.(jpe?g|png|gif|svg)$/i);
			},
			entrypoints: false,
			excludeModules: true,
			children: false,
			hash: false,
			version: false,
			timings: false
		}
	};
};
