<?php namespace App\Validation\Produk;

class Satuan {

   public $pageType = [
      'pageType' => 'required|in_list[insert,update,delete]'
   ];

   public $generated = [];

   public function __construct($post = []) {
      if ($post['pageType'] === 'insert') {
         $this->generated = array_merge($this->pageType, [
            'nama' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ]
         ]);
      } else if ($post['pageType'] === 'update') {
         $this->generated = array_merge($this->pageType, [
            'nama' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ],
            'id' => 'required|numeric'
         ]);
      } else if ($post['pageType'] === 'delete') {
         $this->generated = array_merge($this->pageType, [
            'id' => 'required|numeric'
         ]);
      }
   }

}