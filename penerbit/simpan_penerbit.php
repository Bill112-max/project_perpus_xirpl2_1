<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../inc/conect.php';

$id_penerbit        = $_POST['id_penerbit'];
$nama_penerbit      = $_POST['nama_penerbit'];
$no_tlp_penerbit    = $_POST['no_tlp_penerbit'];
$nama_sales         = $_POST['nama_sales'];
$no_tlp_sales       = $_POST['no_tlp_sales'];



$sql = "INSERT INTO tbl_penerbit (id_penerbit, nama_penerbit, no_tlp_penerbit, nama_sales, no_tlp_sales)
        VALUES ('$id_penerbit', '$nama_penerbit', '$no_tlp_penerbit', '$nama_sales', '$no_tlp_sales')";

if (mysqli_query($koneksi, $sql)) {
    echo "✅ Data berhasil disimpan. <a href='view_penerbit.php'>Lihat Data</a>";
} else {
    echo "❌ Error: " . mysqli_error($koneksi);
}
?>
