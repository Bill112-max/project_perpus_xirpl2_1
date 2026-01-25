<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../inc/conect.php';

$id_buku    = $_POST['id_buku'];
$judul_buku  = $_POST['judul_buku'];
$sinopsis    = $_POST['sinopsis'];
$jumlah_halaman = $_POST['jumlah_halaman'];
$jumlah_buku  = $_POST['jumlah_buku'];
$id_kategori  = $_POST['id_kategori'];
$id_penerbit  = $_POST['id_penerbit'];
$thn_terbit   = $_POST['tahun_terbit'];


$sql = "INSERT INTO tbl_buku (id_buku, judul_buku, sinopsis, jumlah_halaman, jumlah_buku, id_kategori, id_penerbit, tahun_terbit)
        VALUES ('$id_buku', '$judul_buku', '$sinopsis', '$jumlah_halaman', '$jumlah_buku', '$id_kategori', '$id_penerbit', '$thn_terbit')";

if (mysqli_query($koneksi, $sql)) {
    echo "✅ Data berhasil disimpan. <a href='dashboard.php?page=buku'>Lihat Data</a>";
} else {
    echo "❌ Error: " . mysqli_error($koneksi);
}
