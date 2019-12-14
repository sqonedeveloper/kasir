<?php

function jenjangProdi($key) {
	$config = [
		'1' => 'S-3',
		'2' => 'S-2',
		'3' => 'S-1',
		'4' => 'D-4',
		'5' => 'D-3',
		'6' => 'D-2',
		'7' => 'D-1',
		'8' => 'Sp-1',
		'9' => 'Sp-2',
		'10' => 'Profesi'
	];
	return $config[$key];
}

function tanggalIndonesia($tanggal, $separator = ' ') {
	$content = explode('-', $tanggal);
	$tahun = $content[0];
	$bulan = $content[1];
	$tanggal = $content[2];

	$config_bulan = [
		'01' => 'Januari',
		'02' => 'Februari',
		'03' => 'Maret',
		'04' => 'April',
		'05' => 'Mei',
		'06' => 'Juni',
		'07' => 'Juli',
		'08' => 'Agustus',
		'09' => 'September',
		'10' => 'Oktober',
		'11' => 'November',
		'12' => 'Desember'
	];

	return $tanggal.$separator.$config_bulan[$bulan].$separator.$tahun;
}

function settings($key) {
   $db = \Config\Database::connect();

   $table = $db->table('tb_settings');
   $table->select($key);
   $query = $table->get();
   $data = $query->getRowArray();

   return $data[$key];
}

function hari($content) {
   $hari = date('D', strtotime($content));
 
	switch($hari){
		case 'Sun':
			$hari_ini = "Minggu";
		break;
 
		case 'Mon':			
			$hari_ini = "Senin";
		break;
 
		case 'Tue':
			$hari_ini = "Selasa";
		break;
 
		case 'Wed':
			$hari_ini = "Rabu";
		break;
 
		case 'Thu':
			$hari_ini = "Kamis";
		break;
 
		case 'Fri':
			$hari_ini = "Jumat";
		break;
 
		case 'Sat':
			$hari_ini = "Sabtu";
		break;
		
		default:
			$hari_ini = "Tidak di ketahui";		
		break;
	}
 
	return $hari_ini;
}

function namaLengkap($depan, $nama, $belakang) {
	$html = $depan;
	if (!empty($depan))
		$html .= '. ';
	$html .= $nama;
	if (!empty($belakang))
		$html .= ', ';
	$html .= $belakang;
	return $html;
}

function jekel($key) {
	$config = [
		'1' => 'Laki - Laki',
		'2' => 'Perempuan'
	];

	return $config[$key];
}

function stsKawin($key) {
	$config = [
		'1' => 'Kawin',
		'2' => 'Belum Kawin',
		'3' => 'Duda',
		'4' => 'Janda'
	];
	return $config[$key];
}