<?php namespace App\Models\Admin;

use CodeIgniter\Model;

class Supplier extends Model {

   protected $db;

   public function __construct() {
      $this->db = \Config\Database::connect();
   }

   function deleteSupplier($post = []) {
      $table = $this->db->table('tb_supplier');
      $table->where('id', $post['id']);
      $table->delete();
   }

   function getDetailEdit($id) {
      $table = $this->db->table('tb_supplier');
      $table->select('nama, email, telp, alamat, keterangan');
      $table->where('id', $id);

      $get = $table->get();
      $data = $get->getRowArray();

      if (isset($data)) {
         $response = [];
         foreach ($get->getFieldNames() as $row) {
            $response[$row] = (string) $data[$row];
         }
         return $response;
      } else {
         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
      }
   }

   function submit($post = []) {
      $table = $this->db->table('tb_supplier');

      $post['modified'] = date('Y-m-d H:i:s');

      if ($post['pageType'] === 'insert') {
         unset($post['pageType'], $post['id']);

         $post['uploaded'] = date('Y-m-d H:i:s');
         $table->insert($post);
      } else if ($post['pageType'] === 'update') {
         unset($post['pageType']);

         $table->where('id', $post['id']);
         $table->update($post);
      }
   }

   function getData() {
      $table = $this->_queryData();
      if ($_POST['length'] != -1)
         $table->limit($_POST['length'], $_POST['start']);
      return $table->get();
   }
   
   function countData() {
      $table = $this->db->table('tb_supplier');
      $get = $table->get();
      return count($get->getResult());
   }
   
   function filteredData() {
      $table = $this->_queryData();
      $get = $table->get();
      return count($get->getResult());
   }
   
   private function _queryData() {
      $table = $this->db->table('tb_supplier');
   
      $i = 0;
      $column_search = ['nama', 'email', 'telp', 'keterangan'];
      $column_order = ['nama', 'email', 'keterangan'];
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