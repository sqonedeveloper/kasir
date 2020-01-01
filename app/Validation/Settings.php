<?php namespace App\Validation;

class Settings {

   public function generated() {
      return [
         'nama_toko' => [
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
         'prefix_nota' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'prefix_kode' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
         'alamat' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Tidak boleh kosong.'
            ]
         ],
      ];
   }

}