<?php namespace App\Models\Admin\Stok;

use CodeIgniter\Model;

class Keluar extends Model {

   protected $db;

   public function __construct() {
      $this->db = \Config\Database::connect();
   }

   function submit($post = []) {
      $table = $this->db->table('tb_stok');

      if ($post['pageType'] === 'insert') {
         $table->insert([
            'id_produk' => $post['id_produk'],
            'tanggal' => $post['tanggal'],
            'detail_lainnya' => $post['detail_lainnya'],
            'jenis' => '2',
            'uploaded' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
            'detail' => '2',
            'stok' => $post['stok']
         ]);
      }
   }

   function getListsProduk() {
      $table = $this->db->table('tb_produk a');
      $table->select('a.id, a.kode, a.nama, b.total_stok_masuk, c.total_stok_keluar');
      $table->join('(select sum(stok) as total_stok_masuk, id_produk from tb_stok
         where jenis = \'1\'
         group by id_produk
      ) b', 'b.id_produk = a.id', 'left');
      $table->join('(select sum(stok) as total_stok_keluar, id_produk from tb_stok
         where jenis = \'2\'
         group by id_produk
      ) c', 'c.id_produk = a.id', 'left');
      $table->orderBy('kode', 'asc');

      $get = $table->get();

      $response = [];
      foreach ($get->getResultArray() as $data) {
         array_push($response, [
            'value' => $data['id'],
            'label' => $data['kode'] . ' : ' . $data['nama'],
            'total_stok_masuk' => (int) $data['total_stok_masuk'],
            'total_stok_keluar' => (int) $data['total_stok_keluar']
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
      $table = $this->db->table('tb_stok');
      $table->where('jenis', '2');
      $get = $table->get();
      return count($get->getResult());
   }
   
   function filteredData() {
      $table = $this->_queryData();
      $get = $table->get();
      return count($get->getResult());
   }
   
   private function _queryData() {
      $table = $this->db->table('tb_stok a');
      $table->select('b.kode as kode_produk, b.nama as nama_produk, a.stok, a.detail_lainnya as keterangan, a.tanggal');
      $table->join('tb_produk b', 'b.id = a.id_produk', 'left');
      $table->where('a.jenis', '2');
   
      $i = 0;
      $column_search = ['b.kode', 'b.nama', 'a.detail_lainnya'];
      $column_order = ['b.kode', 'b.nama', 'a.stok', 'a.detail_lainnya'];
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