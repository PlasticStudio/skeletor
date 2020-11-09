// webpack.config.js
const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const UglifyJsPlugin = require("uglifyjs-webpack-plugin");
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const devMode = process.env.NODE_ENV !== 'production';
const output_dir = __dirname +"/app/production";

module.exports = {
	entry: "./app/js/index.js",
	output: {
		path: output_dir,
		filename: (devMode ? 'index.js' : 'index.min.js')
	},
	devtool: (devMode ? 'source-map' : false),
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: "babel-loader",
					options: {
						presets: ['@babel/preset-env']
					}
				}
			},
			{
				test: /\.css$/,
				use: [
					'style-loader',
					MiniCssExtractPlugin.loader,
					'postcss-loader',
					'css-loader'
				]
			},
			{
				test: /\.scss$/,
				use: [
					'style-loader',
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: { url: false },
					},
					'resolve-url-loader',
					'postcss-loader',
					{
						loader: "sass-loader",
						options: {}
					}
				]
			},
			{
				test: require.resolve('jquery'),
				exclude: [
					/node_modules/
				],
				use: 'expose-loader?jQuery!expose?$'
			}
		]
	},
	watchOptions: {
		ignored: /node_modules/,
		aggregateTimeout: 300,
		poll: 500
	},
	optimization: {
		minimizer: [
			new UglifyJsPlugin(),
			new OptimizeCSSAssetsPlugin()
		],
	},
	plugins: [
		new FixStyleOnlyEntriesPlugin(),
		new MiniCssExtractPlugin({
			path: output_dir,
			filename: (devMode ? 'index.css' : 'index.min.css'),
			sourceMap: true
		}),
		new CleanWebpackPlugin(),
	]
};
