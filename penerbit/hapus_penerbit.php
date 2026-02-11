<?php
include __DIR__ . '/../inc/conect.php';
$id_penerbit = $_GET['id_penerbit'];
$sql = "DELETE FROM tbl_penerbit WHERE id_penerbit ='$id_penerbit'";
if (mysqli_query($koneksi, $sql)) {
    echo "✅ Data berhasil dihapus. <a href='view_penerbit.php'>Kembali ke Daftar penerbit</a>";
} else {
    echo "❌ Error: " . mysqli_error($koneksi);
}   
?>