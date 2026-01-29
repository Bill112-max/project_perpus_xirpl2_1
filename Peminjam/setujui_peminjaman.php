<?php
include __DIR__ . '/../inc/conect.php';

$id_peminjaman = $_GET['id_peminjaman'];

$peminjaman = mysqli_query($koneksi, "SELECT * FROM tbl_peminjaman WHERE id_peminjaman='$id_peminjaman'");
$data = mysqli_fetch_assoc($peminjaman);

if ($data) {

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

    mysqli_query($koneksi, "UPDATE tbl_buku 
        SET jumlah_buku = jumlah_buku - {$data['jumlah_pinjam']} 
        WHERE id_buku='{$data['id_buku']}'");

    mysqli_query($koneksi, "DELETE FROM tbl_peminjaman WHERE id_peminjaman='$id_peminjaman'");

    echo "<script>
        alert('Pengajuan berhasil disetujui, stok berkurang, dan data dipindahkan ke histori');
        window.location='?page=peminjam';
    </script>";
} else {
    echo "<script>
        alert('Data peminjaman tidak ditemukan');
        window.location='?page=peminjam';
    </script>";
}
?>