<?php
include __DIR__ . '/../inc/conect.php';

// Pastikan session hanya dipanggil sekali
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_peminjaman = isset($_GET['id_peminjaman']) ? (int) $_GET['id_peminjaman'] : 0;

$peminjaman = mysqli_query($koneksi, "SELECT * FROM tbl_peminjaman WHERE id_peminjaman='$id_peminjaman'");
$data = mysqli_fetch_assoc($peminjaman);

if ($data) {
    // Update status jadi dikembalikan
    mysqli_query($koneksi, "UPDATE tbl_peminjaman 
        SET status='dikembalikan', tanggal_kembali=NOW() 
        WHERE id_peminjaman='$id_peminjaman'");

    // Tambah stok buku kembali (pastikan jumlah_pinjam integer)
    $jumlah_pinjam = (int) $data['jumlah_pinjam'];
    $id_buku = mysqli_real_escape_string($koneksi, $data['id_buku']);

    mysqli_query($koneksi, "UPDATE tbl_buku 
        SET jumlah_buku = jumlah_buku + $jumlah_pinjam 
        WHERE id_buku='$id_buku'");

    // Catat ke history
    $admin = isset($_SESSION['username']) ? $_SESSION['username'] : 'system';

    mysqli_query($koneksi, "INSERT INTO tbl_history 
        (id_peminjaman, id, id_buku, jumlah_pinjam, status, admin, waktu) 
        VALUES (
            '{$data['id_peminjaman']}',
            '{$data['id']}',
            '{$data['id_buku']}',
            '{$data['jumlah_pinjam']}',
            'dikembalikan',
            '$admin',
            NOW()
        )");

    echo "<script>
        alert('Pengembalian disetujui, stok bertambah, status jadi dikembalikan');
        window.location='?page=peminjam';
    </script>";
} else {
    echo "<script>
        alert('Data peminjaman tidak ditemukan');
        window.location='?page=peminjam';
    </script>";
}
?>