<?php
include __DIR__ . '/../inc/conect.php';

$data = mysqli_query($koneksi, "SELECT 
    tbl_peminjaman.*, 
    tbl_buku.judul_buku,
    tbl_users.nama
    FROM tbl_peminjaman
JOIN
    tbl_users ON tbl_peminjaman.id = tbl_users.id
JOIN
    tbl_buku ON tbl_peminjaman.id_buku = tbl_buku.id_buku
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f9;
    margin: 0;
    padding: 0;
}

/* Judul halaman */
h2 {
    text-align: center;
    margin: 30px 0;
    font-size: 1.8rem;
    color: #0d6efd;
}

/* Tombol tambah buku */
.btn {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 16px;
    font-size: 0.95rem;
    font-weight: 600;
    border-radius: 6px;
    text-decoration: none;
}

/* Tabel styling */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fdfdfd; /* 1% lebih gelap dari putih */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 40px;
}

/* Header tabel */
table th {
    background-color: #e9ecef;
    color: #333;
    font-weight: 600;
    padding: 12px;
    text-align: left;
    border-bottom: 2px solid #dee2e6;
}

/* Baris tabel */
table td {
    padding: 10px 12px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: top;
    color: #444;
}

/* Hover baris */
table tr:hover {
    background-color: #f1f3f5;
}

/* Tombol aksi */
table .btn-primary {
    padding: 6px 12px;
    font-size: 0.85rem;
    margin-right: 6px;
    border-radius: 4px;
}

/* Responsif */
@media (max-width: 768px) {
    table, thead, tbody, th, td, tr {
        display: block;
    }

    table thead {
        display: none;
    }

    table tr {
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px;
        background-color: #fff;
    }

    table td {
        padding: 8px;
        text-align: left;
        position: relative;
    }

    table td::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        margin-bottom: 4px;
        color: #0d6efd;
    }
}
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan data Peminjam</title>
    
</head>

<body>
    <h2>Daftar Data Peminjam</h2>
    <?php

    if ($tampil['akses'] == 'anggota') {
        echo '<a href="?page=tambah_peminjam" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Tambah Buku
          </a>';
    }
    ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID peminjam</th>
            <th>id buku</th>
            <th>judul_buku</th>
            <th>Jumlah pinjam</th>
            <th>tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>status</th>
            <th>AKSI</th>
        </tr>
        <?php while ($row = mysqli_fetch_array($data)) { ?>
            <tr>
                <td><?= $row['id_peminjaman']; ?></td>
                <td><?= $row['id_buku']; ?></td>
                <td><?= $row['judul_buku']; ?></td>
                <td><?= $row['jumlah_pinjam']; ?></td>
                <td><?= $row['tanggal_pinjam']; ?></td>
                <td><?= $row['tanggal_kembali']; ?></td>
                <td><?= $row['status']; ?></td>

              <td>
    <?php if ($tampil['akses'] === 'admin') { ?>
        <a href="?page=setujui&id_peminjaman=<?= $row['id_peminjaman']; ?>" 
           class="btn btn-success mb-3"
           onclick="return confirm('Yakin ingin menyetujui data ini?')">SETUJUI</a>
        <a href="?page=tolak_peminjam&id_peminjaman=<?= $row['id_peminjaman']; ?>" 
           class="btn btn-danger mb-3"
           onclick="return confirm('Yakin ingin menolak data ini?')">TOLAK</a>
    <?php } else { ?>
        Tidak ada aksi
    <?php } ?>
</td>
        <?php } ?>
    </table>
</body>

</html>