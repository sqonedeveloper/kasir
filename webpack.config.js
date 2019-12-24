const path = require('path');
const webpack = require('webpack');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
	mode: 'development',

	entry: {
		adminTopbar: './src/Admin/Topbar.js',
		adminSidebar: './src/Admin/Sidebar.js',
		adminDashboard: './src/Admin/Dashboard.js',
		adminSupplierLists: './src/Admin/Supplier/Lists.js',
		adminSupplierForms: './src/Admin/Supplier/Forms.js',
		adminProdukKategori: './src/Admin/Produk/Kategori.js',
		adminProdukSatuan: './src/Admin/Produk/Satuan.js',
		adminProdukLists: './src/Admin/Produk/DataProduk/Lists.js',
		adminProdukForms: './src/Admin/Produk/DataProduk/Forms.js',
		adminStokMasukLists: './src/Admin/Stok/Masuk/Lists.js',
		adminStokMasukForms: './src/Admin/Stok/Masuk/Forms.js',
		adminStokKeluarLists: './src/Admin/Stok/Keluar/Lists.js',
		adminStokKeluarForms: './src/Admin/Stok/Keluar/Forms.js',
		adminAkunLists: './src/Admin/Akun/Lists.js',
		adminAkunForms: './src/Admin/Akun/Forms.js',
		adminAkunProfile: './src/Admin/Akun/Profile.js',
		adminSettings: './src/Admin/Settings.js',
	},

	output: {
		filename: '[name].js',
		path: path.resolve(__dirname, 'public/bundle')
	},

	plugins: [
		new webpack.ProgressPlugin(),
		new HtmlWebpackPlugin(),
		new webpack.DefinePlugin({
			'process.env.NODE_ENV': JSON.stringify('production')
		})
	],

	module: {
		rules: [
			{
				test: /.(js|jsx)$/,
				include: [path.resolve(__dirname, 'src')],
				loader: 'babel-loader',

				options: {
					plugins: ['syntax-dynamic-import'],

					presets: [
						[
							'@babel/preset-env',
							{
								modules: false
							}
						]
					]
				}
			}
		]
	},

	optimization: {
		splitChunks: {
			chunks: 'all',
			automaticNameDelimiter: '.',
			cacheGroups: {
				vendors: {
					test: /[\\/]node_modules[\\/]/,
					name: 'vendor'
				}
			}
		},
		minimizer: [
			new UglifyJsPlugin({
				uglifyOptions: {
					output: {
						comments: false
					}
				}
			})
		]
	},

	devServer: {
		open: true,
		disableHostCheck: true
	}
};
