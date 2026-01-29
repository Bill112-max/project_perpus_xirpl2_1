<?php
// Proteksi: Hanya admin yang boleh memproses pengembalian
if ($tampil['akses'] != 'admin') {
    die("Akses ditolak!");
}

$id_peminjaman = $_GET['id_peminjaman'];
$id_buku       = $_GET['id_buku'];
$tgl_kembali   = date('Y-m-d');

// 1. Update status peminjaman
$update_pinjam = mysqli_query($conn, "UPDATE tbl_peminjaman SET 
    status = 'Kembali', 
    tgl_kembali = '$tgl_kembali' 
    WHERE id_peminjaman = '$id_peminjaman'");

if ($update_pinjam) {
    // 2. Tambahkan kembali stok buku (+1)
    mysqli_query($conn, "UPDATE tbl_buku SET jumlah_buku = jumlah_buku + 1 WHERE id_buku = '$id_buku'");

    echo "<script>alert('Buku telah berhasil dikembalikan!'); window.location='dashboard.php?page=daftar_peminjaman';</script>";
}
?>