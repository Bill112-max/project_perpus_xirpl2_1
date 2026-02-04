<?php

include __DIR__ . '/../inc/conect.php';

if (!isset($_GET['id_peminjaman']) || !is_numeric($_GET['id_peminjaman'])) {
    echo "<script>
        alert('ID peminjaman tidak valid');
        window.location='?page=peminjam';
    </script>";
    exit;
}

$id_peminjaman = (int) $_GET['id_peminjaman'];

$peminjaman = mysqli_query($koneksi, "SELECT * FROM tbl_peminjaman WHERE id_peminjaman='$id_peminjaman'");
$data = mysqli_fetch_assoc($peminjaman);

if ($data) {
  
    $update = mysqli_query($koneksi, "UPDATE tbl_peminjaman 
        SET status='disetujui' 
        WHERE id_peminjaman='$id_peminjaman'");

    $admin = isset($_SESSION['username']) ? $_SESSION['username'] : 'system';
    $insert = mysqli_query($koneksi, "INSERT INTO tbl_history 
        (id_peminjaman, id, id_buku, jumlah_pinjam, status, admin, waktu) 
        VALUES (
            '{$data['id_peminjaman']}',
            '{$data['id']}',
            '{$data['id_buku']}',
            '{$data['jumlah_pinjam']}',
            'disetujui',
            '$admin',
            NOW()
        )");

    $jumlah_pinjam = (int) $data['jumlah_pinjam'];
    $update_buku = mysqli_query($koneksi, "UPDATE tbl_buku 
        SET jumlah_buku = jumlah_buku - $jumlah_pinjam 
        WHERE id_buku='{$data['id_buku']}'");

    if ($update && $insert && $update_buku) {
        echo "<script>
            alert('Pengajuan berhasil disetujui, stok berkurang, data tetap ada untuk pengembalian');
            window.location='?page=peminjam';
        </script>";
    } else {
        echo "<script>
            alert('Terjadi kesalahan saat menyimpan data: " . mysqli_error($koneksi) . "');
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