<?php namespace Config;

/**
 * --------------------------------------------------------------------
 * URI Routing
 * --------------------------------------------------------------------
 * This file lets you re-map URI requests to specific controller functions.
 *
 * Typically there is a one-to-one relationship between a URL string
 * and its corresponding controller class/method. The segments in a
 * URL normally follow this pattern:
 *
 *    example.com/class/method/id
 *
 * In some instances, however, you may want to remap this relationship
 * so that a different class/function is called than the one
 * corresponding to the URL.
 */

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 * The RouteCollection object allows you to modify the way that the
 * Router works, by acting as a holder for it's configuration settings.
 * The following methods can be called on the object to modify
 * the default operations.
 *
 *    $routes->defaultNamespace()
 *
 * Modifies the namespace that is added to a controller if it doesn't
 * already have one. By default this is the global namespace (\).
 *
 *    $routes->defaultController()
 *
 * Changes the name of the class used as a controller when the route
 * points to a folder instead of a class.
 *
 *    $routes->defaultMethod()
 *
 * Assigns the method inside the controller that is ran when the
 * Router is unable to determine the appropriate method to run.
 *
 *    $routes->setAutoRoute()
 *
 * Determines whether the Router will attempt to match URIs to
 * Controllers when no specific route has been defined. If false,
 * only routes that have been defined here will be available.
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
$routes->group('login', function($routes) {
	$routes->post('submit', 'Login::submit');
	$routes->get('logout', 'Login::logout');
});

$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
	$routes->get('dashboard', 'Dashboard::index');

	$routes->get('supplier', 'Supplier::index');
	$routes->group('supplier', function($routes) {
		$routes->get('tambah', 'Supplier::tambah');
		$routes->get('edit/(:num)', 'Supplier::edit/$1');
		$routes->post('submit', 'Supplier::submit');
		$routes->post('getData', 'Supplier::getData');
		$routes->post('delete', 'Supplier::delete');
	});

	$routes->group('produk', ['namespace' => 'App\Controllers\Admin\Produk'], function($routes) {
		$routes->get('kategori', 'Kategori::index');
		$routes->group('kategori', function($routes) {
			$routes->get('edit/(:num)', 'Kategori::edit/$1');
			$routes->post('submit', 'Kategori::submit');
			$routes->post('getData', 'Kategori::getData');
			$routes->post('delete', 'Kategori::delete');
		});

		$routes->get('satuan', 'Satuan::index');
		$routes->group('satuan', function($routes) {
			$routes->get('edit/(:num)', 'Satuan::edit/$1');
			$routes->post('submit', 'Satuan::submit');
			$routes->post('getData', 'Satuan::getData');
			$routes->post('delete', 'Satuan::delete');
		});

		$routes->get('dataProduk', 'Produk::index');
		$routes->group('dataProduk', function($routes) {
			$routes->get('tambah', 'Produk::tambah');
			$routes->get('edit/(:num)', 'Produk::edit/$1');
			$routes->get('generateKode', 'Produk::generateKode');
			$routes->post('submit', 'Produk::submit');
			$routes->post('getData', 'Produk::getData');
			$routes->post('delete', 'Produk::delete');
		});
	});

	$routes->group('stok', ['namespace' => 'App\Controllers\Admin\Stok'], function($routes) {
		$routes->get('masuk', 'Masuk::index');
		$routes->group('masuk', function($routes) {
			$routes->get('tambah', 'Masuk::tambah');
			$routes->post('submit', 'Masuk::submit');
			$routes->post('getData', 'Masuk::getData');
		});
		
		$routes->get('keluar', 'Keluar::index');
		$routes->group('keluar', function($routes) {
			$routes->get('tambah', 'Keluar::tambah');
			$routes->post('submit', 'Keluar::submit');
			$routes->post('getData', 'Keluar::getData');
		});
	});

	$routes->get('akun', 'Akun::index');
	$routes->group('akun', function($routes) {
		$routes->get('tambah', 'Akun::tambah');
		$routes->get('profile', 'Akun::profile');
		$routes->post('updateProfile', 'Akun::updateProfile');
		$routes->get('edit/(:num)', 'Akun::edit/$1');
		$routes->post('getData', 'Akun::getData');
		$routes->post('submit', 'Akun::submit');
		$routes->post('delete', 'Akun::delete');
	});

	$routes->get('settings', 'Settings::index');
	$routes->group('settings', function($routes) {
		$routes->post('submit', 'Settings::submit');
	});

	$routes->get('transaksi', 'Transaksi::index');
	$routes->group('transaksi', function($routes) {
		$routes->get('insertTransaksi', 'Transaksi::insertTransaksi');
		$routes->get('tambah', 'Transaksi::tambah');
		$routes->get('getIDProduk', 'Transaksi::getIDProduk');
		$routes->get('getListsProduk', 'Transaksi::getListsProduk');
		$routes->get('detail/(:num)', 'Transaksi::detail/$1');
		$routes->get('cetak/(:num)', 'Transaksi::cetak/$1');
		$routes->post('submit', 'Transaksi::submit');
		$routes->post('getData', 'Transaksi::getData');
		$routes->post('delete', 'Transaksi::delete');
		$routes->post('submitBayar', 'Transaksi::submitBayar');
		$routes->post('deleteDaftarPesanan', 'Transaksi::deleteDaftarPesanan');
	});

	$routes->group('laporan', ['namespace' => 'App\Controllers\Admin\Laporan'], function($routes) {
		$routes->get('penjualan', 'Penjualan::index');
	});
});

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
