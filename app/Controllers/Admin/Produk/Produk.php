<?php namespace App\Controllers\Admin\Produk;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Controllers\AdminController;
use App\Models\Admin\Produk\Produk as Model;
use App\Validation\Produk\Produk as Validate;

class Produk extends AdminController {

   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
      parent::initController($request, $response, $logger);
   }

   public function index() {
      $this->data = [
         'title' => 'Data Produk',
         'internalCss' => $this->app->datatable['css'],
         'internalJs' => [
            $this->app->datatable['js'],
            'http://localhost:8080/adminProdukLists.js'
         ]
      ];

      $this->template($this->data);
   }

   public function tambah() {
      $model = new Model();
      $footerJs['listsKategori'] = $model->getListsKategori();
      $footerJs['listsSatuan'] = $model->getListsSatuan();

      $this->data = [
         'title' => 'Tambah Produk',
         'pageType' => 'insert',
         'internalJs' => ['http://localhost:8080/adminProdukForms.js'],
         'footerJs' => $footerJs
      ];

      $this->template($this->data);
   }

   public function submit() {
      if ($this->request->isAJAX()) {
         $response = ['status' => false, 'errors' => [], 'msg_response' => ''];
         $post = $this->request->getVar();
         $validate = new Validate($post);
      
         if ($this->validate($validate->generated)) {
            $model = new Model();
            $model->submit($post);

            $response['status'] = true;
            $response['msg_response'] = 'Data berhasil disimpan.';
            $response['emptyPost'] = $this->emptyPost($post);
         } else {
            $response['msg_response'] = 'Terjadi sesuatu kesalahan?';
            $response['errors'] = \Config\Services::validation()->getErrors();
         }
         return $this->response->setJSON($response);
      } else {
         $this->notFound();
      }
   }

}