<?php
include __DIR__ . '/../inc/conect.php';

$id_peminjaman = $_GET['id_peminjaman'];
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'ditolak'; 

$peminjaman = mysqli_query($koneksi, "SELECT * FROM tbl_peminjaman WHERE id_peminjaman='$id_peminjaman'");
$data = mysqli_fetch_assoc($peminjaman);

if ($data) {
    mysqli_query($koneksi, "INSERT INTO tbl_history 
        (id_peminjaman, id, id_buku, status, admin) 
        VALUES (
            '{$data['id_peminjaman']}',
            '{$data['id']}',
            '{$data['id_buku']}',
            '$aksi',
            '{$_SESSION['username']}'
        )");

    mysqli_query($koneksi, "DELETE FROM tbl_peminjaman WHERE id_peminjaman='$id_peminjaman'");

    echo "<script>alert('Pengajuan berhasil $aksi dan dipindahkan ke histori');window.location='?page=peminjam';</script>";
} else {
    echo "<script>alert('Data peminjaman tidak ditemukan');window.location='?page=peminjam';</script>";
}
?>