<?php namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Controllers\AdminController;
use App\Models\Admin\Transaksi as Model;
use App\Validation\Transaksi as Validate;

class Transaksi extends AdminController {

   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
      parent::initController($request, $response, $logger);
   }

   public function index() {
      $this->data = [
         'title' => 'Transaksi',
         'internalCss' => $this->app->datatable['css'],
         'internalJs' => [
            $this->app->datatable['js'],
            'http://localhost:8080/adminTransaksiLists.js'
         ]
      ];

      $this->template($this->data);
   }

   public function tambah() {
      $session = \Config\Services::session();
      $model = new Model();

      $footerJs['detail'] = [
         'id_transaksi' => $session->get('id_transaksi'),
         'nota' => $session->get('nota'),
         'status_bayar' => '0'
      ];
      $footerJs['daftarPesanan'] = $model->getDaftarPesanan($session->get('id_transaksi'));
      $footerJs['tgl_transaksi'] = date('Y-m-d');

      $this->data = [
         'title' => 'Tambah Transaksi',
         'internalJs' => ['http://localhost:8080/adminTransaksiForms.js'],
         'footerJs' => $footerJs
      ];

      $this->template($this->data);
   }

   public function insertTransaksi() {
      $model = new Model($this->idUsers);
      $content = $model->insertTransaksi();

      $session = \Config\Services::session();
      $session->set([
         'id_transaksi' => $content['id'],
         'nota' => $content['nota']
      ]);

      return redirect()->to(site_url('admin/transaksi/tambah'));
   }

   public function getListsProduk() {
      $slug = $this->request->getVar('query');

      $model = new Model();
      $response['listsProduk'] = $model->getListsProduk($slug);
      return $this->response->setJSON($response);
   }

   public function submit() {
      if ($this->request->isAJAX()) {
         $response = ['status' => false, 'errors' => [], 'msg_response' => ''];
         $post = $this->request->getVar();
         $validate = new Validate();
      
         if ($this->validate($validate->generated($post))) {
            $model = new Model();
            $content = $model->submit($post);

            $response['status'] = true;
            $response['msg_response'] = 'Data berhasil disimpan.';
            $response['daftarPesanan'] = $content;
         } else {
            $response['msg_response'] = 'Terjadi sesuatu kesalahan?';
            $response['errors'] = \Config\Services::validation()->getErrors();
         }
         return $this->response->setJSON($response);
      } else {
         $this->notFound();
      }
   }

   public function getIDProduk() {
      $kode = $this->request->getVar('kode');

      $model = new Model();
      $response = $model->getIDProduk($kode);
      return $this->response->setJSON($response);
   }

   public function delete() {
      if ($this->request->isAJAX()) {
         $response = ['status' => false, 'errors' => [], 'msg_response' => ''];
         $post = $this->request->getVar();
         $validate = new Validate();
      
         if ($this->validate($validate->generated($post))) {
            $model = new Model();
            $model->deleteTransaksi($post);

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
            $action .= '<span class="edit"><a data-id="'.$data['id'].'">Detail</a></span>';
            $action .= '<span class="delete"><a data-id="'.$data['id'].'" data-type="delete">Delete</a></span>';
            $action .= '</div>';

            $set_nama_produk = '<ol style="margin: 0; padding-left: 15px;">';
            if (!empty($data['nama_produk'])) {
               foreach (json_decode($data['nama_produk'], true) as $key => $val) {
                  $set_nama_produk .= '<li>' . $val . '</li>';
               }
            }
            $set_nama_produk .= '</ol>';
   
            $result = [];
            $result[] = $data['nota'] . '<br/>' . $action;
            $result[] = $set_nama_produk;
            $result[] = tanggalIndonesia($data['tanggal']);
            $result[] = $data['users'];
            $result[] = $data['status_bayar'];
   
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

   public function submitBayar() {
      if ($this->request->isAJAX()) {
         $response = ['status' => false, 'errors' => [], 'msg_response' => ''];
         $post = $this->request->getVar();
         $validate = new Validate();
      
         if ($this->validate($validate->generated($post))) {
            $model = new Model();
            $model->submitBayar($post);

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

   public function detail($id) {
      $session = \Config\Services::session();
      $model = new Model();

      $footerJs['detail'] = $model->getDetailTransaksi($id);
      $footerJs['daftarPesanan'] = $model->getDaftarPesanan($id);
      $footerJs['tgl_transaksi'] = date('Y-m-d');

      $this->data = [
         'title' => $footerJs['detail']['nota'],
         'internalJs' => ['http://localhost:8080/adminTransaksiForms.js'],
         'footerJs' => $footerJs
      ];

      $this->template($this->data);
   }

   public function deleteDaftarPesanan() {
      if ($this->request->isAJAX()) {
         $response = ['status' => false, 'errors' => [], 'msg_response' => ''];
         $post = $this->request->getVar();
         $validate = new Validate();
      
         if ($this->validate($validate->generated($post))) {
            $model = new Model();
            $content = $model->deleteDaftarPesanan($post);

            $response['status'] = true;
            $response['msg_response'] = 'Data berhasil dihapus.';
            $response['daftarPesanan'] = $content;
         } else {
            $response['msg_response'] = 'Gagal menghapus data?';
            $response['errors'] = \Config\Services::validation()->getErrors();
         }
         return $this->response->setJSON($response);
      } else {
         $this->notFound();
      }
   }

   public function cetak($id) {
      $model = new Model();

      $footerJs['settings'] = $this->getAppSettings();
      $footerJs['detail'] = $model->getDetailPrintTransaksi($id);
      
      $this->data = [
         'internalJs' => ['http://localhost:8080/adminTransaksiCetak.js'],
         'footerJs' => $footerJs
      ];

      $this->cetakStruk($this->data);
   }

}