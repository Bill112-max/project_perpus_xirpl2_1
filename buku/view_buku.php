<?php
include __DIR__ . '/../inc/conect.php';

$data = mysqli_query($koneksi, "SELECT 
    tbl_buku.*, 
    tbl_kategori.kategori,
    tbl_penerbit.nama_penerbit 
    FROM tbl_buku
JOIN
    tbl_kategori ON tbl_buku.id_kategori = tbl_kategori.id_kategori
JOIN
    tbl_penerbit ON tbl_buku.id_penerbit = tbl_penerbit.id_penerbit
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
    <title>Tampilan data buku</title>
    <link rel="stylesheet" href="../css/view.css">
</head>

<body>
    <h2>Daftar Data buku</h2>
    <?php

    if ($tampil['akses'] == 'admin') {
        echo '<a href="?page=tambah_buku" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Tambah Buku
          </a>';
    }
    ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID buku</th>
            <th>Judul buku</th>
            <th>sinopsis</th>
            <th>Jumlah halaman</th>
            <th>jumlah buku</th>
            <th>kategori</th>
            <th>penerbit</th>
            <th>tahun terbit</th>
            <th>AKSI</th>
        </tr>
        <?php while ($row = mysqli_fetch_array($data)) { ?>
            <tr>
                <td><?= $row['id_buku']; ?></td>
                <td><?= $row['judul_buku']; ?></td>
                <td><?= $row['sinopsis']; ?></td>
                <td><?= $row['jumlah_halaman']; ?></td>
                <td><?= $row['jumlah_buku']; ?></td>
                <td><?= $row['kategori']; ?></td>
                <td><?= $row['nama_penerbit']; ?></td>
                <td><?= $row['tahun_terbit']; ?></td>

                <td>

                    <?php if ($tampil['akses'] == 'admin') { ?>
                        <a href="?page=edit_buku&id_buku=<?= $row['id_buku']; ?>" class="btn btn-primary mb-3">EDIT</a>
                        <a href="?page=hapus_buku&id_buku=<?= $row['id_buku']; ?> " class="btn btn-primary mb-3" " onclick=" return confirm('Yakin ingin menghapus data ini?')">HAPUS</a>
                </td>
            <?php } else {
                        echo "Tidak ada aksi";
                    } ?>
            </tr>
        <?php } ?>
    </table>
</body>

</html>