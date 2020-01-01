<?php namespace App\Validation;

class Login {

   public function generated($post = []) {
      return [
         'username' => [
            'rules' => 'required|chechExistsUsername',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'password' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ]
      ];
   }

}