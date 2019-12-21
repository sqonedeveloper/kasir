<?php namespace App\Validation;

class Supplier {

   public $generated = [
      'pageType' => 'required|in_list[insert,update,delete]'
   ];

   public function __construct($post = []) {
      if ($post['pageType'] === 'insert') {
         $this->generated = array_merge($this->generated, [
            'nama' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ],
            'telp' => [
               'rules' => 'required|numeric|is_unique[tb_supplier.telp]',
               'errors' => [
                  'required' => 'Tidak boleh kosong.',
                  'numeric' => 'Hanya boleh diisi dengan angka.'
               ]
            ],
            'alamat' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ],
            'email' => [
               'rules' => 'valid_email|is_unique[tb_supplier.email]|permit_empty',
               'errors' => [
                  'is_unique' => 'Email sudah terdaftar.',
                  'valid_email' => 'Email tidak valid.'
               ]
            ]
         ]);
      } else if ($post['pageType'] === 'update') {
         $this->generated = array_merge($this->generated, [
            'pageType' => 'required|in_list[insert,update]',
            'id' => 'required|numeric',
            'nama' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ],
            'telp' => [
               'rules' => 'required|numeric',
               'errors' => [
                  'required' => 'Tidak boleh kosong.',
                  'numeric' => 'Hanya boleh diisi dengan angka.'
               ]
            ],
            'alamat' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ]
         ]);
      } else if ($post['pageType'] === 'delete') {
         $this->generated = array_merge($this->generated, [
            'id' => 'required|numeric'
         ]);
      }
   }

}