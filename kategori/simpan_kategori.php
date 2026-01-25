<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../inc/conect.php';

$id_kategori    = $_POST['id_kategori'];
$kategori       = $_POST['kategori'];



$sql = "INSERT INTO tbl_kategori (id_kategori, kategori)
        VALUES ('$id_kategori', '$kategori')";

if (mysqli_query($koneksi, $sql)) {
    echo "✅ Data berhasil disimpan. <a href='view_kategori.php'>Lihat Data</a>";
} else {
    echo "❌ Error: " . mysqli_error($koneksi);
}
?>
