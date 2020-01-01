<?php namespace App\Models\Admin;

use CodeIgniter\Model;

class Transaksi extends Model {

   protected $db;
   protected $idUsers;

   public function __construct($idUsers = null) {
      $this->db = \Config\Database::connect();
      $this->idUsers = $idUsers;
   }

   function getDetailPrintTransaksi($id) {
      $table = $this->db->table('tb_transaksi_detail a');
      $table->select('b.nota, to_char(b.tanggal, \'dd-mm-yyyy HH12:MI\') as tgl_transaksi, c.nama as users,
         a.harga, a.diskon, a.jumlah, d.nama as produk, b.diskon as diskon_pembayaran, b.jumlah_bayar');
      $table->join('tb_transaksi b', 'b.id = a.id_transaksi');
      $table->join('tb_users c', 'c.id = b.id_users', 'left');
      $table->join('tb_produk d', 'd.id = a.id_produk', 'left');
      $table->where('a.id_transaksi', $id);
      $table->orderBy('a.id', 'asc');

      $get = $table->get();
      $data = $get->getRowArray();

      if (isset($data)) {
         $response['nota'] = $data['nota'];
         $response['tgl_transaksi'] = $data['tgl_transaksi'];
         $response['users'] = $data['users'];
         $response['diskon_pembayaran'] = (int) $data['diskon_pembayaran'];
         $response['jumlah_bayar'] = (int) $data['jumlah_bayar'];
         $response['lists'] = [];

         $harga_jual = 0;
         $diskon = 0;

         foreach ($get->getResultArray() as $data) {
            $harga_jual += $data['harga'] * $data['jumlah'];
            $diskon += $data['diskon'];

            array_push($response['lists'], [
               'produk' => $data['produk'],
               'harga' => (int) $data['harga'],
               'diskon' => (int) $data['diskon'],
               'jumlah' => (int) $data['jumlah']
            ]);
         }

         $response['terbilang'] = strtoupper(terbilang($harga_jual - ($diskon + $data['diskon_pembayaran'])));
         return $response;
      } else {
         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
      }
   }

   function deleteDaftarPesanan($post = []) {
      $table = $this->db->table('tb_transaksi_detail');
      $table->where('id', $post['id']);
      $table->delete();

      return $this->getDaftarPesanan($post['id_transaksi']);
   }

   function getDetailTransaksi($id) {
      $table = $this->db->table('tb_transaksi');
      $table->select('id as id_transaksi, nota, status_bayar');
      $table->where('id', $id);

      $get = $table->get();
      $data = $get->getRowArray();

      if (isset($data)) {
         return $data;
      } else {
         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
      }
   }

   function submitBayar($post = []) {
      $table = $this->db->table('tb_transaksi');
      $table->where('id', $post['id_transaksi']);
      $table->update([
         'tanggal' => $post['tanggal'],
         'status_bayar' => '1',
         'diskon' => replaceDotWithEmptyString($post['diskon']),
         'total_bayar' => replaceDotWithEmptyString($post['total_bayar']),
         'grand_total' => replaceDotWithEmptyString($post['grand_total']),
         'jumlah_bayar' => replaceDotWithEmptyString($post['jumlah_uang'])
      ]);
   }

   function deleteTransaksi($post = []) {
      $table = $this->db->table('tb_transaksi');
      $table->where('id', $post['id']);
      $table->delete();
   }

   function getDaftarPesanan($id_transaksi) {
      $table = $this->db->table('tb_transaksi_detail a');
      $table->select('a.id, b.nama, a.harga, a.diskon, a.jumlah');
      $table->join('tb_produk b', 'b.id = a.id_produk', 'left');
      $table->where('a.id_transaksi', $id_transaksi);

      $get = $table->get();
      $data = $get->getRowArray();

      $response = [];
      $content = [];
      $grand_total = 0;
      foreach ($get->getResultArray() as $data) {
         $harga = (int) $data['harga'];
         $diskon = (int) $data['diskon'];
         $jumlah = (int) $data['jumlah'];

         array_push($content, [
            'id' => (int) $data['id'],
            'nama' => $data['nama'],
            'harga' => (string) $harga,
            'diskon' => (string) $diskon,
            'jumlah' => $jumlah,
            'total' => (string) (($harga * $jumlah) - $diskon)
         ]);

         $grand_total += ($harga * $jumlah);
      }

      $response['results'] = $content;
      $response['grand_total'] = (string) $grand_total;
      return $response;
   }

   function getIDProduk($kode) {
      $table = $this->db->table('tb_produk');
      $table->select('id, kode, harga');
      $table->where('kode', $kode);

      $get = $table->get();
      $data = $get->getRowArray();

      if (isset($data)) {
         $response = [
            'status' => true,
            'id_produk' => $data['id'],
            'kode' => $data['kode'],
            'harga' => $data['harga']
         ];
      } else {
         $response['status'] = false;
      }
      return $response;
   }

   function submit($post = []) {
      $table = $this->db->table('tb_transaksi_detail');
      $table->where('id_produk', $post['id_produk']);
      $table->where('id_transaksi', $post['id_transaksi']);

      $get = $table->get();
      $data = $get->getRowArray();

      if (isset($data)) {
         $this->db
            ->table('tb_transaksi_detail')
            ->where('id_produk', $post['id_produk'])
            ->where('id_transaksi', $post['id_transaksi'])
            ->update([
               'jumlah' => (int) $data['jumlah'] + (int) $post['jumlah']
            ]);
      } else {
         $table->insert([
            'id_produk' => $post['id_produk'],
            'id_transaksi' => $post['id_transaksi'],
            'harga' => $post['harga'],
            'diskon' => 0,
            'jumlah' => $post['jumlah']
         ]);
      }

      return $this->getDaftarPesanan($post['id_transaksi']);
   }

   function insertTransaksi() {
      $table = $this->db->table('tb_transaksi a, tb_settings b');
      $table->select('replace(a.nota, concat(b.prefix_nota, to_char(current_date, \'yymmdd\')), \'\') as no_urut,
         left(a.nota, -9) as prefix_nota, left(replace(a.nota, b.prefix_nota, \'\'), -7) as tahun,
	      left(replace(a.nota, concat(b.prefix_nota, to_char(current_date, \'yy\')), \'\'), -5) as bulan,
	      left(replace(a.nota, concat(b.prefix_nota, to_char(current_date, \'yymm\')), \'\'), -3) as tanggal');
      $table->where('left(a.nota, -9) = b.prefix_nota');
      $table->where('left(replace(a.nota, b.prefix_nota, \'\'), -7)::numeric = ' . date('y'));
      $table->where('left(replace(a.nota, concat(b.prefix_nota, to_char(current_date, \'yy\')), \'\'), -5)::numeric = ' . date('m'));
      $table->where('left(replace(a.nota, concat(b.prefix_nota, to_char(current_date, \'yymm\')), \'\'), -3)::numeric = ' . date('d'));
      $table->orderBy('replace(a.nota, concat(b.prefix_nota, to_char(current_date, \'yymmdd\')), \'\') desc');
      $table->limit(1);

      $get = $table->get();
      $data = $get->getRowArray();

      $before_no_urut = (int) $data['no_urut'];
      $next_no_urut = sprintf('%03s', $before_no_urut + 1);

      $set_nota = '';
      if (isset($data)) {
         $set_nota = $data['prefix_nota'].$data['tahun'].$data['bulan'].$data['tanggal'].$next_no_urut;
      } else {
         $_prefixNota = $this->_prefixNota();
         $set_nota = $_prefixNota.date('y').date('m').date('d').'001';
      }

      $this->db
         ->table('tb_transaksi')
         ->insert([
            'nota' => $set_nota,
            'id_users' => $this->idUsers
         ]);
      
      $id_transaksi = $this->db->insertID('tb_transaksi_id_seq');

      $response = [
         'id' => $id_transaksi,
         'nota' => $set_nota
      ];
      return $response;
   }

   function getListsProduk($slug) {
      $table = $this->db->table('tb_produk a');
      $table->select('a.id, a.kode, a.nama, a.harga, b.nama as satuan');
      $table->join('tb_satuan b', 'b.id = a.id_satuan', 'left');
      if ($slug !== 'all') {
         $table->like('a.kode', $slug);
         $table->orLike('lower(a.nama)', $slug);
      }

      $get = $table->get();
      return $get->getResultArray();
   }

   private function _prefixNota() {
      $table = $this->db->table('tb_settings');
      $table->select('prefix_nota');
      $get = $table->get();
      $data = $get->getRowArray();

      return $data['prefix_nota'];
   }

   function getData() {
      $table = $this->_queryData();
      if ($_POST['length'] != -1)
         $table->limit($_POST['length'], $_POST['start']);
      return $table->get();
   }
   
   function countData() {
      $table = $this->db->table('views_transaksi');
      $get = $table->get();
      return count($get->getResult());
   }
   
   function filteredData() {
      $table = $this->_queryData();
      $get = $table->get();
      return count($get->getResult());
   }
   
   private function _queryData() {
      $table = $this->db->table('views_transaksi');
   
      $i = 0;
      $column_search = ['nota'];
      $column_order = ['nota', null, 'tanggal', 'users', 'status_bayar'];
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