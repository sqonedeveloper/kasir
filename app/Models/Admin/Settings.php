<?php namespace App\Models\Admin;

use CodeIgniter\Model;

class Settings extends Model {

   protected $db;

   public function __construct() {
      $this->db = \Config\Database::connect();
   }

   function getDetailContent() {
      $table = $this->db->table('tb_settings');

      $get = $table->get();
      $data = $get->getRowArray();

      $fieldNames = $get->getFieldNames();
      $response = [];
      foreach ($fieldNames as $key) {
         $response[$key] = (string) $data[$key];
      }
      return $response;
   }

   function submit($post = []) {
      $table = $this->db->table('tb_settings');

      $get = $table->get();
      $data = $get->getRowArray();

      if (isset($data)) {
         $table->update($post);
      } else {
         $table->insert($post);
      }
   }

}