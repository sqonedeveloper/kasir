<?php namespace App\Models\Admin\Stok;

use CodeIgniter\Model;

class Masuk extends Model {

   protected $db;

   public function __construct() {
      $this->db = \Config\Database::connect();
   }

   function getListsProduk() {
      $table = $this->db->table('tb_produk');
      $table->select('id, kode, nama');
      $table->orderBy('kode', 'asc');

      $get = $table->get();

      $response = [];
      foreach ($get->getResultArray() as $data) {
         array_push($response, [
            'value' => $data['id'],
            'label' => $data['kode'],
            'kode' => $data['kode'],
            'nama' => $data['nama']
         ]);
      }
      return $response;
   }

   function submit($post = []) {
      $table = $this->db->table('tb_stok');

      unset($post['kode'], $post['nama']);
      $post['jenis'] = 1;
      $post['modified'] = date('Y-m-d H:i:s');
      if ($post['detail'] === '1') {
         unset($post['detail_lainnya']);
      } else if ($post['detail'] === '2') {
         unset($post['id_supplier']);
      }

      if ($post['pageType'] === 'insert') {
         unset($post['pageType']);

         $post['uploaded'] = date('Y-m-d H:i:s');
         $table->insert($post);
      }
   }

   function getListSupplier() {
      $table = $this->db->table('tb_supplier');
      $table->select('id, nama, telp');

      $get = $table->get();
      
      $response = [];
      foreach ($get->getResultArray() as $data) {
         array_push($response, [
            'value' => $data['id'],
            'label' => $data['nama'] . ' : ' . $data['telp']
         ]);
      }
      return $response;
   }

   function getData() {
      $table = $this->_queryData();
      if ($_POST['length'] != -1)
         $table->limit($_POST['length'], $_POST['start']);
      return $table->get();
   }
   
   function countData() {
      $table = $this->db->table('views_stok_masuk');
      $get = $table->get();
      return count($get->getResult());
   }
   
   function filteredData() {
      $table = $this->_queryData();
      $get = $table->get();
      return count($get->getResult());
   }
   
   private function _queryData() {
      $table = $this->db->table('views_stok_masuk');
   
      $i = 0;
      $column_search = ['kode_produk', 'nama_produk', 'stok', 'supplier', 'detail_lainnya'];
      $column_order = ['kode_produk', 'nama_produk', 'stok', 'tanggal'];
      foreach ($column_search as $item) {
         if ($_POST['search']['value']) {
            if ($i === 0) {
               $table->groupStart();
               $table->like($item, $_POST['search']['value']);
            } else {
               $table->orLike($item, $_POST['search']['value']);
            }
   
            if (count($column_search) - 1 == $i)
               $table->groupEnd();
         }
         $i++;
      }
   
      $column = $_POST['order'][0]['column'];
      $dir = $_POST['order'][0]['dir'];
      $table->orderBy($column_order[$column], $dir);
   
      return $table;
   }

}