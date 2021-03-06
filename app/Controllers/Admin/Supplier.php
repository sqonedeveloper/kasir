<?php namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Controllers\AdminController;
use App\Validation\Supplier as Validate;
use App\Models\Admin\Supplier as Model;

class Supplier extends AdminController {

   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
      parent::initController($request, $response, $logger);
   }

   public function index() {
      $this->data = [
         'title' => 'Supplier',
         'internalCss' => $this->app->datatable['css'],
         'internalJs' => [
            $this->app->datatable['js'],
            'http://localhost:8080/adminSupplierLists.js'
         ]
      ];

      $this->template($this->data);
   }
   
   public function tambah() {
      $this->data = [
         'title' => 'Tambah Supplier',
         'pageType' => 'insert',
         'internalJs' => ['http://localhost:8080/adminSupplierForms.js']
      ];

      $this->template($this->data);
   }
   
   public function edit($id) {
      $model = new Model();
      $footerJs['detail'] = $model->getDetailEdit($id);

      $this->data = [
         'title' => 'Edit Supplier',
         'pageType' => 'update',
         'internalJs' => ['http://localhost:8080/adminSupplierForms.js'],
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

   public function delete() {
      if ($this->request->isAJAX()) {
         $response = ['status' => false, 'errors' => [], 'msg_response' => ''];
         $post = $this->request->getVar();
         $validate = new Validate($post);
      
         if ($this->validate($validate->generated)) {
            $model = new Model();
            $model->deleteSupplier($post);

            $response['status'] = true;
            $response['msg_response'] = 'Data berhasil dihapus.';
         } else {
            $response['msg_response'] = 'Gagal menghapus data?';
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
   
            $action = '<div class="row-actions">';
            $action .= '<span class="edit"><a data-id="'.$data['id'].'">Edit</a></span>';
            $action .= '<span class="delete"><a data-id="'.$data['id'].'" data-type="delete">Delete</a></span>';
            $action .= '</div>';
   
            $result = [];
            $result[] = $data['nama'] . '<br/>' . $action;
            $result[] = $data['email'] . '<br/>' . $data['telp'];
            $result[] = $data['alamat'];
            $result[] = $data['keterangan'];
   
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