<?php namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\Controller;

class AdminController extends Controller {

   protected $helpers = [
      'style',
      'autoload',
      'text'
   ];

   var $app;
   var $idUsers;

   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
      parent::initController($request, $response, $logger);

      $this->app = new \Config\App();
      $this->_checkIsLogin();
   }

   private function _checkIsLogin() {
      $session = \Config\Services::session();

      if (!$session->get('isLogin')) {
         $msg = 'Anda harus <a href="'.site_url('login').'">login</a> terlebih dahulu?';
         die($msg);
      } else {
         $db = \Config\Database::connect();

         $username = $session->get('username');
         $telp = $session->get('telp');

         $table = $db->table('tb_users');
         $table->where('username', $username);
         $table->where('telp', $telp);

         $get = $table->get();
         $data = $get->getRowArray();

         if (isset($data)) {
            $this->idUsers = $data['id'];
         } else {
            $msg = 'Anda harus <a href="'.site_url('login').'">login</a> terlebih dahulu?';
            die($msg);
         }
      }
   }

   public function template($content = []) {
      $internalCss = [];
      if (!empty($content['internalCss'])) {
         foreach ($content['internalCss'] as $key) {
            $internalCss[] = $key;
         }
      }

      $internalJs = [
         'http://localhost:8080/vendor.js',
         'http://localhost:8080/adminTopbar.js',
         'http://localhost:8080/adminSidebar.js'
      ];
      
      if (!empty($content['internalJs'])) {
         foreach ($content['internalJs'] as $key) {
            $internalJs[] = $key;
         }
      }

      $footerJs['navigation'] = $this->_generateNavigation();
      $footerJs['sites'] = $this->_getDefaultSitesConfig();
      $footerJs['users'] = $this->_getUsersLogin();
      if (!empty($content['footerJs'])) {
         foreach ($content['footerJs'] as $key => $val) {
            $footerJs[$key] = $val;
         }
      }

      $data['title'] = $content['title'];
      $data['internalCss'] = css_tag($internalCss);
      $data['internalJs'] = script_tag($internalJs);
      $data['segment'] = $this->_generateSegment();
      $data['pageType'] = @$content['pageType'];
      $data['footerJs'] = json_encode($footerJs);

      echo view('AdminPanel', $data);
   }

   public function cetakStruk($content = []) {
      $internalJs = ['http://localhost:8080/vendor.js'];
      
      if (!empty($content['internalJs'])) {
         foreach ($content['internalJs'] as $key) {
            $internalJs[] = $key;
         }
      }

      $footerJs = [];
      if (!empty($content['footerJs'])) {
         foreach ($content['footerJs'] as $key => $val) {
            $footerJs[$key] = $val;
         }
      }

      $data['internalJs'] = script_tag($internalJs);
      $data['footerJs'] = json_encode($footerJs);

      echo view('CetakStruk', $data);
   }

   private function _getUsersLogin() {
      $db = \Config\Database::connect();
      $session = \Config\Services::session();

      $username = $session->get('username');
      $telp = $session->get('telp');

      $table = $db->table('tb_users');
      $table->where('username', $username);
      $table->where('telp', $telp);

      $get = $table->get();
      $data = $get->getRowArray();

      if (isset($data)) {
         return [
            'nama' => $data['nama'],
            'telp' => $data['telp']
         ];
      } else {
         $msg = 'Anda harus <a href="'.site_url('login').'">login</a> terlebih dahulu?';
         die($msg);
      }
   }

   private function _getDefaultSitesConfig() {
      $db = \Config\Database::connect();
      $table = $db->table('tb_settings');

      $get = $table->get();
      $data = $get->getRowArray();

      $fieldNames = $get->getFieldNames();
      $response = [];
      foreach ($fieldNames as $key) {
         $response[$key] = (string) $data[$key];
      }
      return $response;
   }

   private function _generateNavigation() {
      $config = [
         [
            'label' => 'Dashboard',
            'icon' => 'mdi mdi-gauge',
            'active' => ['dashboard'],
            'url' => '/admin/dashboard',
            'sub' => false
         ],
         [
            'label' => 'Supplier',
            'icon' => 'mdi mdi-gauge',
            'active' => ['supplier'],
            'url' => '/admin/supplier',
            'sub' => false
         ],
         [
            'label' => 'Produk',
            'icon' => 'mdi mdi-gauge',
            'active' => ['produk'],
            'url' => '#',
            'sub' => true,
            'child' => [
               [
                  'label' => 'Kategori',
                  'active' => ['kategori'],
                  'url' => '/admin/produk/kategori'
               ],
               [
                  'label' => 'Satuan',
                  'active' => ['satuan'],
                  'url' => '/admin/produk/satuan'
               ],
               [
                  'label' => 'Data Produk',
                  'active' => ['dataProduk'],
                  'url' => '/admin/produk/dataProduk'
               ]
            ]
         ],
         [
            'label' => 'Stok',
            'icon' => 'mdi mdi-gauge',
            'active' => ['stok'],
            'url' => '#',
            'sub' => true,
            'child' => [
               [
                  'label' => 'Masuk',
                  'active' => ['masuk'],
                  'url' => '/admin/stok/masuk'
               ],
               [
                  'label' => 'Keluar',
                  'active' => ['keluar'],
                  'url' => '/admin/stok/keluar'
               ]
            ]
         ],
         [
            'label' => 'Transaksi',
            'icon' => 'mdi mdi-gauge',
            'active' => ['transaksi'],
            'url' => '/admin/transaksi',
            'sub' => false
         ],
         [
            'label' => 'Laporan',
            'icon' => 'mdi mdi-gauge',
            'active' => ['laporan'],
            'url' => '#',
            'sub' => true,
            'child' => [
               [
                  'label' => 'Penjualan',
                  'active' => ['penjualan'],
                  'url' => '/admin/laporan/penjualan'
               ],
               [
                  'label' => 'Stok Masuk',
                  'active' => ['stokMasuk'],
                  'url' => '/admin/laporan/stokMasuk'
               ],
               [
                  'label' => 'Stok Keluar',
                  'active' => ['stokKeluar'],
                  'url' => '/admin/laporan/stokKeluar'
               ]
            ]
         ],
         [
            'label' => 'Akun',
            'icon' => 'mdi mdi-gauge',
            'active' => ['akun'],
            'url' => '/admin/akun',
            'sub' => false
         ],
         [
            'label' => 'Settings',
            'icon' => 'mdi mdi-gauge',
            'active' => ['settings'],
            'url' => '/admin/settings',
            'sub' => false
         ],
      ];
      return $config;
   }

   private function _generateSegment() {
      $string = uri_string();
      $exp_string = explode('/', $string);

      $set_segment = [];
      for ($i = 0; $i < count($exp_string); $i++) {
         $set_segment[$i + 1] = $exp_string[$i];
      }

      return json_encode($set_segment);
   }

   public function emptyPost($post = []) {
      $response = [];
      foreach ($post as $key => $val) {
         $response[$key] = '';
      }
      return $response;
   }

   public function notFound() {
      throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
   }

   public function getAppSettings() {
      $db = \Config\Database::connect();

      $table = $db->table('tb_settings');
      
      $get = $table->get();
      $data = $get->getRowArray();

      return $data;
   }

}