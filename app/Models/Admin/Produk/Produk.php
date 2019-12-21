<?php namespace App\Models\Admin\Produk;

use CodeIgniter\Model;

class Produk extends Model {

   protected $db;

   public function __construct() {
      $this->db = \Config\Database::connect();
   }

   function submit($post = []) {
      $table = $this->db->table('tb_produk');

      $post['modified'] = date('Y-m-d H:i:s');
      if ($post['pageType'] === 'insert') {
         unset($post['pageType'], $post['id']);

         $post['uploaded'] = date('Y-m-d H:i:s');
         $post['harga'] = replaceDotWithEmptyString($post['harga']);
         $table->insert($post);
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

}