<?php namespace App\Validation\Produk;

class Kategori {

   public $generated = [
      'pageType' => 'required|in_list[insert,update,delete]'
   ];

   public function __construct($post = []) {
      if ($post['pageType'] === 'delete') {
         $this->generated = array_merge($this->generated, [
            'id' => 'required|numeric'
         ]);
      } else {
         $this->generated = array_merge($this->generated, [
            'nama' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ]
         ]);
      }
   }

}