<?php
include __DIR__ . '/../inc/conect.php';
$id_buku = $_GET['id_buku'];
$sql = "DELETE FROM tbl_buku WHERE id_buku='$id_buku'";
if (mysqli_query($koneksi, $sql)) {
    echo "✅ Data berhasil dihapus. <a href='dashboard.php?page=buku'>Kembali ke Daftar buku</a>";
} else {
    echo "❌ Error: " . mysqli_error($koneksi);
}
