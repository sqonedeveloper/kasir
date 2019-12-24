<?php namespace App\Validation\Stok;

class Keluar {

   public $generated = [];

   public $pageType = [
      'pageType' => 'required|in_list[insert,update,delete]'
   ];

   public function __construct($post = []) {
      $this->generated = array_merge($this->pageType, [
         'tanggal' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'id_produk' => [
            'rules' => 'required|numeric',
            'errors' => [
               'required' => 'Tidak boleh kosong.',
               'numeric' => 'Hanya boleh diisi dengan angka.'
            ]
         ],
         'sisa_stok' => [
            'rules' => 'required|numeric',
            'errors' => [
               'required' => 'Tidak boleh kosong.',
               'numeric' => 'Hanya boleh diisi dengan angka.'
            ]
         ],
         'stok' => [
            'rules' => 'required|numeric|checkJumlahStokKeluar',
            'errors' => [
               'required' => 'Tidak boleh kosong.',
               'numeric' => 'Hanya boleh diisi dengan angka.'
            ]
         ],
      ]);
   }

}