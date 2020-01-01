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
   
   public function edit($id) {
      $model = new Model();
      $footerJs['listsKategori'] = $model->getListsKategori();
      $footerJs['listsSatuan'] = $model->getListsSatuan();
      $footerJs['detail'] = $model->getDetailEdit($id);

      $this->data = [
         'title' => 'Edit Produk',
         'pageType' => 'update',
         'internalJs' => ['http://localhost:8080/adminProdukForms.js'],
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

   public function generateKode() {
      $db = \Config\Database::connect();
      $tb_settings = $db->table('tb_settings');
      $tb_settings->select('prefix_kode');
      $get_settings = $tb_settings->get();
      $data_settings = $get_settings->getRowArray();

      $string = random_string('123456789', 8);
      $prefix_kode = $data_settings['prefix_kode'];

      $tb_produk = $db->table('tb_produk');
      $tb_produk->where('kode', $prefix_kode . $string);
      $get_produk = $tb_produk->get();
      $data_produk = $get_produk->getRowArray();

      if (isset($data_produk)) {
         $response['kode'] = $prefix_kode . ((int) $string + 1);
      } else {
         $response['kode'] = $prefix_kode . $string;
      }
      return $this->response->setJSON($response);
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
            $result[] = $data['produk'] . '<br/>' . $action;
            $result[] = number_format($data['harga'], 0, '', '.');
            $result[] = $data['satuan'];
            $result[] = $data['kategori'];
            $result[] = ((int) $data['total_stok_masuk'] - (int) $data['total_stok_keluar']);
   
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