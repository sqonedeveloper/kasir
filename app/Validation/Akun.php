<?php namespace App\Validation;

class Akun {

   public $pageType = [
      'pageType' => 'required|in_list[insert,update,delete]'
   ];

   public function generated($post = []) {
      if ($post['pageType'] === 'delete') {
         return array_merge($this->pageType, [
            'id' => 'required|numeric'
         ]);
      } else {
         return array_merge($this->pageType, [
            'id' => $post['pageType'] === 'update' ? 'required|numeric' : 'permit_empty',
            'nama' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ],
            'username' => [
               'rules' => $post['pageType'] === 'insert' ? 'required|is_unique[tb_users.username]' : 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.',
                  'is_unique' => 'Username sudah terdaftar.'
               ]
            ],
            'password' => [
               'rules' => $post['pageType'] === 'insert' ? 'required' : 'permit_empty',
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
            'role' => [
               'rules' => 'required|numeric|in_list[1,2]',
               'errors' => [
                  'required' => 'Tidak boleh kosong.',
                  'numeric' => 'Hanya boleh diisi dengan angka.',
                  'in_list' => 'Apakah anda hacker.'
               ]
            ],
         ]);
      }
   }

}