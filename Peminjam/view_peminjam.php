<?php
include __DIR__ . '/../inc/conect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['akses'] === 'anggota') {
    $id_user = $_SESSION['id'];
    // Ambil hanya data yang belum selesai (tidak dikembalikan)
    $data = mysqli_query($koneksi, "
        SELECT p.*, b.judul_buku, u.nama
        FROM tbl_peminjaman p
        JOIN tbl_users u ON p.id = u.id
        JOIN tbl_buku b ON p.id_buku = b.id_buku
        WHERE p.id = '$id_user' 
          AND p.status IN ('pending','disetujui','menunggu pengembalian')
    ");
} else {
    // Admin hanya lihat data yang butuh persetujuan
    $data = mysqli_query($koneksi, "
        SELECT p.*, b.judul_buku, u.nama
        FROM tbl_peminjaman p
        JOIN tbl_users u ON p.id = u.id
        JOIN tbl_buku b ON p.id_buku = b.id_buku
        WHERE p.status IN ('pending','menunggu pengembalian')
    ");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Data Peminjam</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        h2 { text-align: center; margin: 30px 0; font-size: 1.8rem; color: #0d6efd; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 40px; }
        th, td { padding: 12px; border-bottom: 1px solid #dee2e6; text-align: left; }
        th { background: #e9ecef; font-weight: 600; }
        tr:hover { background: #f1f3f5; }
        .btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; margin-right: 6px; }
        .btn-success { background: #198754; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-warning { background: #ffc107; color: #000; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; }
        .bg-info { background: #0dcaf0; color: #000; }
        .bg-success { background: #198754; color: #fff; }
    </style>
</head>
<body>
    <h2>Daftar Data Peminjam</h2>
    <?php if ($_SESSION['akses'] === 'anggota') { ?>
        <a href="?page=tambah_peminjam" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Tambah Buku
        </a>
    <?php } ?>
    <table>
        <tr>
            <th>ID Peminjaman</th>
            <th>ID Anggota</th>
            <th>Nama</th>
            <th>ID Buku</th>
            <th>Judul Buku</th>
            <th>Jumlah Pinjam</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_array($data)) { ?>
            <tr>
                <td><?= $row['id_peminjaman']; ?></td>
                <td><?= $row['id']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['id_buku']; ?></td>
                <td><?= $row['judul_buku']; ?></td>
                <td><?= $row['jumlah_pinjam']; ?></td>
                <td><?= $row['tanggal_pinjam']; ?></td>
                <td><?= $row['tanggal_kembali']; ?></td>
                <td><?= $row['status']; ?></td>
                <td>
                    <?php if ($_SESSION['akses'] === 'admin') { ?>
                        <?php if ($row['status'] === 'pending') { ?>
                            <a href="?page=setujui_peminjam&id_peminjaman=<?= $row['id_peminjaman']; ?>" 
                               class="btn btn-success"
                               onclick="return confirm('Yakin ingin menyetujui data ini?')">SETUJUI</a>
                            <a href="?page=tolak_peminjam&id_peminjaman=<?= $row['id_peminjaman']; ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Yakin ingin menolak data ini?')">TOLAK</a>
                        <?php } elseif ($row['status'] === 'menunggu pengembalian') { ?>
                            <a href="?page=setujui_pengembalian&id_peminjaman=<?= $row['id_peminjaman']; ?>" 
                               class="btn btn-success"
                               onclick="return confirm('Yakin ingin menyetujui pengembalian ini?')">SETUJUI PENGEMBALIAN</a>
                        <?php } ?>
                    <?php } elseif ($_SESSION['akses'] === 'anggota') { ?>
                        <?php if ($row['status'] === 'disetujui') { ?>
                            <a href="?page=pengembalian&id_peminjaman=<?= $row['id_peminjaman']; ?>" 
                               class="btn btn-warning"
                               onclick="return confirm('Yakin ingin mengembalikan buku ini?')">KEMBALIKAN</a>
                        <?php } elseif ($row['status'] === 'menunggu pengembalian') { ?>
                            <span class="badge bg-info">Menunggu persetujuan admin</span>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>