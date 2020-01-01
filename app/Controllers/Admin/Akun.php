<?php namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Controllers\AdminController;
use App\Validation\Akun as Validate;
use App\Models\Admin\Akun as Model;

class Akun extends AdminController {

   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
      parent::initController($request, $response, $logger);
   }

   public function profile() {
      $model = new Model($this->idUsers);
      $footerJs['detail'] = $model->getDetailProfile();

      $this->data = [
         'title' => 'Profile',
         'internalJs' => ['http://localhost:8080/adminAkunProfile.js'],
         'footerJs' => $footerJs
      ];

      $this->template($this->data);
   }

   public function updateProfile() {
      if ($this->request->isAJAX()) {
         $response = ['status' => false, 'errors' => [], 'msg_response' => ''];
         $post = $this->request->getVar();
         $validate = new Validate();
      
         if ($this->validate($validate->generated($post))) {
            $model = new Model($this->idUsers);
            $model->submitUpdateProfile($post);

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

   public function index() {
      $this->data = [
         'title' => 'Akun',
         'internalCss' => $this->app->datatable['css'],
         'internalJs' => [
            $this->app->datatable['js'],
            'http://localhost:8080/adminAkunLists.js'
         ]
      ];

      $this->template($this->data);
   }

   public function tambah() {
      $this->data = [
         'title' => 'Tambah',
         'pageType' => 'insert',
         'internalJs' => ['http://localhost:8080/adminAkunForms.js']
      ];

      $this->template($this->data);
   }
   
   public function edit($id) {
      $model = new Model($this->idUsers);
      $footerJs['detail'] = $model->getDetailEdit($id);

      $this->data = [
         'title' => 'Edit',
         'pageType' => 'update',
         'internalJs' => ['http://localhost:8080/adminAkunForms.js'],
         'footerJs' => $footerJs
      ];

      $this->template($this->data);
   }

   public function submit() {
      if ($this->request->isAJAX()) {
         $response = ['status' => false, 'errors' => [], 'msg_response' => ''];
         $post = $this->request->getVar();
         $validate = new Validate();
      
         if ($this->validate($validate->generated($post))) {
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
         $validate = new Validate();
      
         if ($this->validate($validate->generated($post))) {
            $model = new Model();
            $model->deleteAkun($post);

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
         $model = new Model($this->idUsers);
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
            $result[] = $data['username'];
            $result[] = $data['telp'];
            $result[] = tanggalIndonesia(date('Y-m-d', strtotime($data['uploaded'])));
   
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