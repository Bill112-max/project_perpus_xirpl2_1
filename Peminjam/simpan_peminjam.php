<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$_id = $_SESSION['id'];
include __DIR__ . '/../inc/conect.php';
$id_buku    = $_POST['id_buku'];
$jumlah_pinjam  = $_POST['jumlah'];
$tanggal_pinjam  = $_POST['tgl_pinjam'];
$tanggal_kembali  = $_POST['tgl_kembali'];


$sql = "INSERT INTO tbl_peminjaman (id, id_buku, jumlah_pinjam, tanggal_pinjam, tanggal_kembali) 
        VALUES ('$_id', '$id_buku', '$jumlah_pinjam', '$tanggal_pinjam', '$tanggal_kembali')";

if (mysqli_query($koneksi, $sql)) {
    echo "✅ Data berhasil disimpan. <a href='dashboard.php?page=peminjam'>Lihat Data</a>";
} else {
    echo "❌ Error: " . mysqli_error($koneksi);
}
mysqli_close($koneksi);
?>