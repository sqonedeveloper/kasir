<?php namespace App\Validation;

class Callback {

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

}