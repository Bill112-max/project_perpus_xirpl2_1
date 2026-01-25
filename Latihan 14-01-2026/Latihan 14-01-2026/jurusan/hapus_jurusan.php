<?php
$kode = $_GET['id_jurusan'];
//echo $nis;
//query menghapus data
$sql = "Delete from tbl_jurusan where kode_jurusan='$kode'";
//program mengeksekusi/manjalankan query
if (mysqli_query($koneksi, $sql)) {
    header("Location: ?page=jurusan&status=deleted");
} else {
    header("Location: ?page=jurusan&status=deleted");
}
?>
