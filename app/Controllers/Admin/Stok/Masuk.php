<?php namespace App\Controllers\Admin\Stok;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Controllers\AdminController;

class Masuk extends AdminController {

   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
      parent::initController($request, $response, $logger);
   }

   public function index() {
      $this->data = [
         'title' => 'Stok Masuk',
         'internalCss' => $this->app->datatable['css'],
         'internalJs' => [
            $this->app->datatable['js'],
            'http://localhost:8080/adminStokMasukLists.js'
         ]
      ];

      $this->template($this->data);
   }

   public function tambah() {
      $this->data = [
         'title' => 'Tambah Stok',
         'internalJs' => ['http://localhost:8080/adminStokMasukForms.js']
      ];

      $this->template($this->data);
   }

}