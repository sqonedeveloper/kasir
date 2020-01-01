<?php namespace App\Validation\Produk;

class Produk {

   public $pageType = [
      'pageType' => 'required|in_list[insert,update,delete]'
   ];

   public function generated($post = []) {
      return array_merge($this->pageType, [
         'id' => $post['pageType'] === 'update' ? 'required|numeric' : 'permit_empty',
         'kode' => [
            'rules' => $post['pageType'] === 'insert' ? 'required|is_unique[tb_produk.kode]' : 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.',
               'is_unique' => 'Barcode/kode sudah terdaftar.'
            ]
         ],
         'nama' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'harga' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'id_kategori' => [
            'rules' => 'required|numeric',
            'errors' => [
               'required' => 'Tidak boleh kosong.',
               'numeric' => 'Hanya boleh diisi dengan angka.'
            ]
         ],
         'id_satuan' => [
            'rules' => 'required|numeric',
            'errors' => [
               'required' => 'Tidak boleh kosong.',
               'numeric' => 'Hanya boleh diisi dengan angka.'
            ]
         ],
      ]);
   }

}