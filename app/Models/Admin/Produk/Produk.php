<?php namespace App\Models\Admin\Produk;

use CodeIgniter\Model;

class Produk extends Model {

   protected $db;

   public function __construct() {
      $this->db = \Config\Database::connect();
   }

   function getDetailEdit($id) {
      $table = $this->db->table('tb_produk');
      $table->select('kode, nama, harga, id_kategori, id_satuan');
      $table->where('id', $id);

      $get = $table->get();
      $data = $get->getRowArray();
      $fieldNames = $get->getFieldNames();

      if (isset($data)) {
         $response = [];
         foreach ($fieldNames as $key) {
            if ($key === 'harga') {
               $response[$key] = number_format($data[$key], 0, '', '.');
            } else {
               $response[$key] = (string) $data[$key];
            }
         }
         return $response;
      } else {
         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
      }
   }

   function submit($post = []) {
      $table = $this->db->table('tb_produk');

      $post['modified'] = date('Y-m-d H:i:s');
      if ($post['pageType'] === 'insert') {
         unset($post['pageType'], $post['id']);

         $post['uploaded'] = date('Y-m-d H:i:s');
         $post['harga'] = replaceDotWithEmptyString($post['harga']);
         $table->insert($post);
      } else if ($post['pageType'] === 'update') {
         unset($post['pageType']);

         $table->where('id', $post['id']);
         $post['harga'] = replaceDotWithEmptyString($post['harga']);
         $table->update($post);
      }
   }

   function getListsKategori() {
      $table = $this->db->table('tb_kategori');
      $table->select('id, nama');

      $get = $table->get();

      $response = [];
      foreach ($get->getResultArray() as $data) {
         array_push($response, [
            'value' => $data['id'],
            'label' => $data['nama']
         ]);
      }
      return $response;
   }
   
   function getListsSatuan() {
      $table = $this->db->table('tb_satuan');
      $table->select('id, nama');

      $get = $table->get();

      $response = [];
      foreach ($get->getResultArray() as $data) {
         array_push($response, [
            'value' => $data['id'],
            'label' => $data['nama']
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
      $table = $this->db->table('views_produk');
      $get = $table->get();
      return count($get->getResult());
   }
   
   function filteredData() {
      $table = $this->_queryData();
      $get = $table->get();
      return count($get->getResult());
   }
   
   private function _queryData() {
      $table = $this->db->table('views_produk');
   
      $i = 0;
      $column_search = ['produk', 'harga', 'satuan', 'kategori'];
      $column_order = ['produk', 'harga', 'satuan', 'kategori'];
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