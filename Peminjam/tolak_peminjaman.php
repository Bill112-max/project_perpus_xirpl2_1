<?php
include __DIR__ . '/../inc/conect.php';
$id = $_GET['id_peminjaman'];

$query = "UPDATE tbl_peminjaman SET status='ditolak' WHERE id_peminjaman='$id'";
$result = mysqli_query($koneksi, $query);
$peminjaman = mysqli_query($koneksi, "SELECT * FROM tbl_peminjaman WHERE id_peminjaman='$id'");
$data = mysqli_fetch_assoc($peminjaman);

if ($data) {

    mysqli_query($koneksi, "INSERT INTO tbl_history 
        (id_peminjaman, id, id_buku, status, admin) 
        VALUES (
            '{$data['id_peminjaman']}', 
            '{$data['id']}',
            '{$data['id_buku']}',
            -- '{$data['tanggal_pinjam']}',
            'ditolak',
            '{$_SESSION['username']}'
        )");
    mysqli_query($koneksi, "DELETE FROM tbl_peminjaman WHERE id_peminjaman='$id'");
}

echo "<script>alert('Pengajuan ditolak dan dipindahkan ke histori');window.location='?page=peminjaman';</script>";
?>

if ($result) {
    echo "<script>alert('Peminjaman berhasil ditolak');window.location='?page=peminjaman';</script>";
} else {
    echo "<script>alert('Gagal menolak peminjaman');window.location='?page=peminjaman';</script>";
}
?>
