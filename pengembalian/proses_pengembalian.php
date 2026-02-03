<?php
include __DIR__ . '/../inc/conect.php';
session_start();

$id_peminjaman = $_GET['id_peminjaman'];

$peminjaman = mysqli_query($koneksi, "SELECT * FROM tbl_peminjaman WHERE id_peminjaman='$id_peminjaman'");
$data = mysqli_fetch_assoc($peminjaman);

if ($data) {
    // Hanya bisa ajukan pengembalian kalau status sudah disetujui
    if ($data['status'] === 'disetujui') {
        mysqli_query($koneksi, "UPDATE tbl_peminjaman 
            SET status='menunggu pengembalian' 
            WHERE id_peminjaman='$id_peminjaman'");

        // Catat ke history
        mysqli_query($koneksi, "INSERT INTO tbl_history 
            (id_peminjaman, id, id_buku, jumlah_pinjam, status, admin, waktu) 
            VALUES (
                '{$data['id_peminjaman']}',
                '{$data['id']}',
                '{$data['id_buku']}',
                '{$data['jumlah_pinjam']}',
                'menunggu pengembalian',
                '{$_SESSION['username']}',
                NOW()
            )");

        echo "<script>
            alert('Pengembalian diajukan, menunggu persetujuan admin');
            window.location='?page=peminjam';
        </script>";
    } else {
        echo "<script>
            alert('Pengembalian hanya bisa dilakukan jika status sudah disetujui');
            window.location='?page=peminjam';
        </script>";
    }
} else {
    echo "<script>
        alert('Data peminjaman tidak ditemukan');
        window.location='?page=peminjam';
    </script>";
}
?>