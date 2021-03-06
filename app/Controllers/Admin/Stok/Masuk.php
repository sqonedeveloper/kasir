<?php namespace App\Controllers\Admin\Stok;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Controllers\AdminController;
use App\Models\Admin\Stok\Masuk as Model;
use App\Validation\Stok\Masuk as Validate;

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
      $model = new Model();
      $footerJs['listsSupplier'] = $model->getListSupplier();
      $footerJs['listsProduk'] = $model->getListsProduk();

      $this->data = [
         'title' => 'Tambah Stok',
         'pageType' => 'insert',
         'internalJs' => ['http://localhost:8080/adminStokMasukForms.js'],
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
         } else {
            $response['msg_response'] = 'Terjadi sesuatu kesalahan?';
            $response['errors'] = \Config\Services::validation()->getErrors();
         }
         return $this->response->setJSON($response);
      } else {
         $this->notFound();
      }
   }

   public function getData() {
      if ($this->request->isAJAX()) {
         $model = new Model();
         $query = $model->getData();
   
         $i = $_POST['start'];
         $response = [];
         foreach ($query->getResultArray() as $data) {
            $i++;
   
            $result = [];
            $result[] = $data['kode_produk'];
            $result[] = $data['nama_produk'];
            $result[] = $data['stok'];
            $result[] = tanggalIndonesia($data['tanggal']);
            $result[] = $data['detail'] === '1' ? $data['supplier'] : $data['detail_lainnya'];
   
            $response[] = $result;
         }
   
         $output = [
            'draw' => intval($_POST['draw']),
            'recordsTotal' => intval($model->countData()),
            'recordsFiltered' => intval($model->filteredData()),
            'data' => $response
         ];
   
         return $this->response->setJSON($output);
      } else {
         $this->notFound();
      }
   }

}