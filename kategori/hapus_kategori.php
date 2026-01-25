<?php
include '../inc/conect.php';
$id_kategori = $_GET['id_kategori'];
$sql = "DELETE FROM tbl_kategori WHERE id_kategori='$id_kategori'";
if (mysqli_query($koneksi, $sql)) {
    echo "✅ Data berhasil dihapus. <a href='view_kategori.php'>Kembali ke kategori </a>";
} else {
    echo "❌ Error: " . mysqli_error($koneksi);
}   
?>