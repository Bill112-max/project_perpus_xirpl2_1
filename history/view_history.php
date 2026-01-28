<?php
include __DIR__ . '/../inc/conect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['id']     = $_SESSION['id']     ?? '';
$_SESSION['akses']  = $_SESSION['akses']  ?? 'anggota';

$id_login    = $_SESSION['id'];
$akses_login = $_SESSION['akses'];

$data = null;
if ($akses_login === 'anggota') {
    $data = mysqli_query($koneksi, "
        SELECT p.*, b.judul_buku, u.nama
        FROM tbl_peminjaman p
        JOIN tbl_users u ON p.id = u.id
        JOIN tbl_buku b ON p.id_buku = b.id_buku
        WHERE p.id = '$id_login'
    ");
}
if ($akses_login === 'admin') {
    if (isset($_GET['id_user']) && trim($_GET['id_user']) !== '') {
        $id_user = mysqli_real_escape_string($koneksi, trim($_GET['id_user']));
        $data = mysqli_query($koneksi, "
            SELECT p.*, b.judul_buku, u.nama
            FROM tbl_peminjaman p
            JOIN tbl_users u ON p.id = u.id
            JOIN tbl_buku b ON p.id_buku = b.id_buku
            WHERE p.id = '$id_user'
        ");
    } else {
        $data = false; 
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>History Peminjaman</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        h2 { text-align: center; margin: 30px 0; font-size: 1.8rem; color: #0d6efd; }
        form { text-align: center; margin-bottom: 20px; }
        input[type="text"] { padding: 8px; width: 200px; }
        button { padding: 8px 12px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 40px; }
        th, td { padding: 12px; border-bottom: 1px solid #dee2e6; text-align: left; }
        th { background: #e9ecef; font-weight: 600; }
        tr:hover { background: #f1f3f5; }
        p { text-align: center; color: #666; }
    </style>
</head>
<body>
    <h2>History</h2>
    <?php if ($akses_login === 'admin') { ?>
        <form method="get" action="dashboard.php">
            <input type="hidden" name="page" value="history">
            <label>Cari history berdasarkan ID User:</label>
            <input type="text" name="id_user" placeholder="Masukkan ID User">
            <button type="submit">Cari</button>
        </form>
    <?php } ?>
    <?php if ($data && mysqli_num_rows($data) > 0) { ?>
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
            </tr>
            <?php while ($row = mysqli_fetch_assoc($data)) { ?>
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
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>Tidak ada data history.</p>
    <?php } ?>
</body>
</html>