<?php
include __DIR__ . '/../inc/conect.php';
session_start();

$id_peminjaman = $_GET['id_peminjaman'];

// Ambil data peminjaman
$peminjaman = mysqli_query($koneksi, "SELECT * FROM tbl_peminjaman WHERE id_peminjaman='$id_peminjaman'");
$data = mysqli_fetch_assoc($peminjaman);

if ($data) {
    // Insert ke history dengan status 'disetujui'
    mysqli_query($koneksi, "INSERT INTO tbl_history 
        (id_peminjaman, id, id_buku, status, admin, waktu) 
        VALUES (
            '{$data['id_peminjaman']}',
            '{$data['id']}',
            '{$data['id_buku']}',
            'disetujui',
            '{$_SESSION['username']}',
            NOW()
        )");

    // Hapus dari tabel peminjaman (karena sudah diproses)
    mysqli_query($koneksi, "DELETE FROM tbl_peminjaman WHERE id_peminjaman='$id_peminjaman'");

    echo "<script>alert('Pengajuan berhasil disetujui dan dipindahkan ke histori');window.location='?page=peminjaman';</script>";
} else {
    echo "<script>alert('Data peminjaman tidak ditemukan');window.location='?page=peminjaman';</script>";
}
?>