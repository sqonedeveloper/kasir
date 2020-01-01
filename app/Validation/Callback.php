<?php namespace App\Validation;

class Callback {

   public function checkJumlahBayar(string $str, string &$error = null) : bool {
      $jumlah_bayar = (int) str_replace('.', '', $str);
      $total_bayar = (int) $_POST['total_bayar'];

      if ($jumlah_bayar < $total_bayar) {
         $error = 'Jumlah bayar lebih kecil dari total harga.';
         return false;
      }

      return true;
   }

   public function validasiHarga(string $str, string &$error = null) : bool {
      $jumlah_bayar = str_replace('.', '', $str);

      if (is_numeric($jumlah_bayar)) {
         $error = 'Hanya boleh diisi dengan angka.';
         return true;
      } else {
         return false;
      }
   }

   public function checkJumlahPemesanan(string $str, string &$error = null) : bool {
      $jumlah = (int) $str;

      if ($jumlah < 1) {
         $error = 'Jumlah barang pesanan minimal 1.';
         return false;
      }
      return true;
   }

   public function checkSisaProduk(string $str, string &$error = null) : bool {
      $id_produk = $_POST['id_produk'];

      if (!empty($id_produk)) {
         $db = \Config\Database::connect();
         $table = $db->table('tb_produk a');
         $table->select('b.total_stok_masuk, c.total_stok_keluar, d.jumlah_pesanan');
         $table->join('(select id_produk, sum(stok) as total_stok_masuk from tb_stok
            where jenis = \'1\' group by id_produk) b', 'b.id_produk = a.id', 'left');
         $table->join('(select id_produk, sum(stok) as total_stok_keluar from tb_stok
            where jenis = \'2\' group by id_produk) c', 'c.id_produk = a.id', 'left');
         $table->join('(select id_produk, sum(jumlah) as jumlah_pesanan from tb_transaksi_detail
            group by id_produk) d', 'd.id_produk = a.id', 'left');
         $table->where('a.id', $id_produk);

         $get = $table->get();
         $data = $get->getRowArray();

         $total_stok_masuk = (int) $data['total_stok_masuk'];
         $total_stok_keluar = (int) $data['total_stok_keluar'];
         $jumlah_pesanan = (int) $data['jumlah_pesanan'];
         $sisa_stok = ($total_stok_masuk - $total_stok_keluar) - $jumlah_pesanan;
         $jumlah = (int) $str;

         if ($sisa_stok < $jumlah) {
            $error = 'Stok produk tidak cukup. Sisa stok ' . $sisa_stok;
            return false;
         }
      }
      return true;
   }

   public function checkJumlahStokKeluar(string $str, string &$error = null) : bool {
      $stok = (int) $str;
      $sisa_stok = (int) $_POST['sisa_stok'];
      $error = $sisa_stok . ' ' . $stok;
      if ($stok > $sisa_stok) {
         $error = 'Stok anda masukkan lebih besar dari sisa stok.';
         return false;
      }
      return true;
   }

   public function chechExistsUsername(string $str) : bool {
      $db = \Config\Database::connect();
      $table = $db->table('tb_users');
      $table->where('username', $str);

      $get = $table->get();
      $data = $get->getRowArray();

      if (isset($data)) {
         return true;
      } else {
         return false;
      }
   }

}