<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'db_sekolah_xirpl1_99'; //Pastikan nama db sesuai dengan database yang baru kalain buat
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { die('Koneksi gagal: ' . mysqli_connect_error()); }
?>
