// webpack.config.js
const webpack = require('webpack');
const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const devMode = process.env.NODE_ENV !== 'production';
const output_dir = __dirname +"/dist";
//
// console.log(process.env.NODE_ENV);

module.exports = {
	entry: {
		index: __dirname +"/src/js/index.js",
		editor: __dirname +"/src/scss/cms/_editor.scss"
	},
	output: {
		path: output_dir,
		filename: '[name].js'
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
						options: { url: false, sourceMap: true },
					},
					'resolve-url-loader',
					'postcss-loader',
					{
						loader: "sass-loader",
						options: {
							sourceMap: true
						}
					}
				]
			},
			{
				test: require.resolve('jquery'),
				use: [{
					loader: 'expose-loader',
					options: 'jQuery'
				},{
					loader: 'expose-loader',
					options: '$'
				}]
			}
		]
	},
	watchOptions: {
		ignored: /node_modules/,
		aggregateTimeout: 300,
		poll: 500
	},
	plugins: [
		new FixStyleOnlyEntriesPlugin(),
		new MiniCssExtractPlugin({
			path: output_dir,
			filename: '[name].css',
			sourceMap: true
		}),
		new CleanWebpackPlugin(),
		new webpack.ProvidePlugin({
			$: "jquery",
			jQuery: "jquery",
			"window.jQuery": "jquery"
		})
	],
	optimization: {
		minimize: !devMode,
		minimizer: [
			new TerserPlugin({
				test: /\.js(\?.*)?$/i,
				parallel: true,
				extractComments: true,
			}),
			new OptimizeCSSAssetsPlugin({})
		]
	}
};
