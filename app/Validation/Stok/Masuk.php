<?php namespace App\Validation\Stok;

class Masuk {

   public $pageType = [
      'pageType' => 'required|in_list[insert,update,delete]'
   ];

   public $generated = [];

   public function __construct($post = []) {
      $this->generated = array_merge($this->pageType, [
         'tanggal' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'id_produk' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'kode' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'nama' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'detail' => [
            'rules' => 'required|numeric',
            'errors' => [
               'required' => 'Tidak boleh kosong.',
               'numeric' => 'Hanya boleh diisi dengan angka.'
            ]
         ],
         'id_supplier' => [
            'rules' => $post['detail'] === '1' ? 'required|numeric' : 'permit_empty',
            'errors' => [
               'required' => 'Tidak boleh kosong.',
               'numeric' => 'Hanya boleh diisi dengan angka.'
            ]
         ],
         'detail_lainnya' => [
            'rules' => $post['detail'] === '2' ? 'required' : 'permit_empty',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'stok' => [
            'rules' => 'required|numeric',
            'errors' => [
               'required' => 'Tidak boleh kosong.',
               'numeric' => 'Hanya boleh diisi dengan angka.'
            ]
         ],
      ]);
   }

}