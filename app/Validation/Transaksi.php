<?php namespace App\Validation;

class Transaksi {

   public $pageType = [
      'pageType' => 'required|in_list[tambahPesanan,delete,bayarTransaksi,deleteDaftarPesanan]'
   ];

   public function generated($post = []) {
      if ($post['pageType'] === 'tambahPesanan') {
         return array_merge($this->pageType, [
            'id_produk' => 'required|numeric',
            'id_transaksi' => 'required|numeric',
            'kode' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.',
               ]
            ],
            'jumlah' => [
               'rules' => 'required|numeric|checkJumlahPemesanan|checkSisaProduk',
               'errors' => [
                  'required' => 'Tidak boleh kosong.',
                  'numeric' => 'Hanya boleh diisi dengan angka.'
               ]
            ]
         ]);
      } else if ($post['pageType'] === 'delete') {
         return array_merge($this->pageType, [
            'id' => 'required|numeric'
         ]);
      } else if ($post['pageType'] === 'bayarTransaksi') {
         return array_merge($this->pageType, [
            'id_transaksi' => 'required|numeric',
            'tanggal' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ],
            'diskon' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ],
            'total_bayar' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ],
            'grand_total' => [
               'rules' => 'required',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ],
            'jumlah_uang' => [
               'rules' => 'required|checkJumlahBayar|validasiHarga',
               'errors' => [
                  'required' => 'Tidak boleh kosong.'
               ]
            ],
         ]);
      } else if ($post['pageType'] === 'deleteDaftarPesanan') {
         return array_merge($this->pageType, [
            'id' => 'required|numeric',
            'id_transaksi' => 'required|numeric'
         ]);
      }
   }

}