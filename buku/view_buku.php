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
/* === BODY === */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #eaf2ff, #f4f8ff);
    margin: 0;
    padding: 0;
}

/* === JUDUL === */
h2 {
    text-align: center;
    margin: 35px 0;
    font-size: 2rem;
    font-weight: 700;
    color: #0d6efd;
    letter-spacing: 0.5px;
}

/* === TOMBOL UMUM === */
.btn {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 18px;
    font-size: 0.95rem;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    background: #0d6efd;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
}

.btn:hover {
    background: #0b5ed7;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(13, 110, 253, 0.3);
}

/* === TABEL === */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
    box-shadow: 0 6px 20px rgba(13, 110, 253, 0.08);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 50px;
}

/* === HEADER TABEL === */
table th {
    background: linear-gradient(135deg, #0d6efd, #3d8bfd);
    color: white;
    font-weight: 600;
    padding: 14px;
    text-align: left;
    font-size: 0.95rem;
}

/* === ISI TABEL === */
table td {
    padding: 12px 14px;
    border-bottom: 1px solid #e3ecff;
    vertical-align: middle;
    color: #333;
    font-size: 0.92rem;
}

/* === HOVER BARIS === */
table tr {
    transition: background 0.2s ease;
}

table tr:hover {
    background-color: #f0f6ff;
}

/* === TOMBOL AKSI DALAM TABEL === */
table .btn-primary {
    padding: 6px 12px;
    font-size: 0.8rem;
    border-radius: 6px;
    background-color: #0d6efd;
    color: white;
    border: none;
    transition: 0.2s ease;
}

table .btn-primary:hover {
    background-color: #0b5ed7;
    transform: scale(1.05);
}

/* === RESPONSIVE === */
@media (max-width: 768px) {

    table, thead, tbody, th, td, tr {
        display: block;
    }

    table thead {
        display: none;
    }

    table tr {
        margin-bottom: 15px;
        border-radius: 10px;
        padding: 12px;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.08);
    }

    table td {
        padding: 8px 0;
        text-align: left;
        position: relative;
    }

    table td::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        margin-bottom: 4px;
        color: #0d6efd;
        font-size: 0.85rem;
    }
}
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan data buku</title>
    <link rel="stylesheet" href="../css/view.css">
</head>

<body>
      <?php if ($tampil['akses'] == 'admin') { ?>
    <h2>Daftar Data buku</h2>
    <?php

  
        echo '<a href="?page=tambah_buku" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Tambah Buku
          </a>';
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
                        <a href="?page=hapus_buku&id_buku=<?= $row['id_buku']; ?> " class="btn btn-primary mb-3"  onclick=" return confirm('Yakin ingin menghapus data ini?')">HAPUS</a>
                </td>
            <?php } else {
                        echo "Tidak ada aksi";
                    } ?>
            </tr>
        <?php } ?>
    </table>
     <?php } ?>
</body>

</html>
<?php if ($tampil['akses'] == 'anggota') { ?>

<style>

/* === CONTAINER GRID === */
.shop-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 28px;
    padding: 40px;
}

/* === CARD PRODUK === */
.shop-card {
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: 0.3s ease;
    display: flex;
    flex-direction: column;
}

.shop-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 35px rgba(0,0,0,0.15);
}

/* === GAMBAR BUKU === */
.shop-img {
    height: 260px;
    overflow: hidden;
}

.shop-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.3s ease;
}

.shop-card:hover .shop-img img {
    transform: scale(1.05);
}

/* === BODY CARD === */
.shop-body {
    padding: 18px;
    flex: 1;
}

.shop-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #222;
    height: 48px;
    overflow: hidden;
}

.shop-info {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 6px;
}

.shop-stock {
    font-weight: bold;
    font-size: 0.95rem;
    color: #0d6efd;
    margin: 12px 0;
}

/* === BUTTON === */
.btn-pinjam {
    background: linear-gradient(135deg,#0d6efd,#084298);
    color: #fff;
    text-align: center;
    padding: 10px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: 0.2s ease;
    display: block;
}

.btn-pinjam:hover {
    opacity: 0.9;
}

h2 {
    text-align:center;
    margin-top:40px;
    font-weight:700;
}

@media(max-width:600px){
    .shop-container{
        padding:20px;
        gap:18px;
    }

    .shop-img{
        height:200px;
    }
}

</style>

<?php if ($tampil['akses'] == 'anggota') { ?>

<style>

/* === CONTAINER GRID === */
.shop-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 22px;
    padding: 30px;
}

/* === CARD === */
.shop-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: 0.3s ease;
    display: flex;
    flex-direction: column;
}

.shop-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 28px rgba(0,0,0,0.12);
}

/* === IMAGE === */
.shop-img {
    height: 210px;   /* lebih kecil */
    overflow: hidden;
}

.shop-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.3s ease;
}

.shop-card:hover .shop-img img {
    transform: scale(1.05);
}

/* === BODY === */
.shop-body {
    padding: 14px;
    flex: 1;
}

.shop-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 6px;
    color: #222;
    height: 42px;
    overflow: hidden;
}

.shop-info {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 4px;
}

.shop-stock {
    font-weight: bold;
    font-size: 0.9rem;
    color: #0d6efd;
    margin: 8px 0 12px 0;
}

/* === BUTTON WRAPPER === */
.shop-buttons {
    display: flex;
    gap: 8px;
}

/* === BUTTON DETAIL === */
.btn-detail {
    flex: 1;
    background: #e9ecef;
    color: #333;
    text-align: center;
    padding: 8px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.8rem;
    transition: 0.2s;
}

.btn-detail:hover {
    background: #d6d8db;
}

/* === BUTTON PINJAM === */
.btn-pinjam {
    flex: 1;
    background: #0d6efd;
    color: #fff;
    text-align: center;
    padding: 8px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.8rem;
    transition: 0.2s;
}

.btn-pinjam:hover {
    background: #084298;
}

h2 {
    text-align:center;
    margin-top:35px;
    font-weight:700;
}

@media(max-width:600px){
    .shop-container{
        padding:15px;
    }
    .shop-img{
        height:180px;
    }
}

</style>

<h2>Katalog Buku</h2>

<div class="shop-container">

<?php 
mysqli_data_seek($data, 0);
while ($row = mysqli_fetch_array($data)) { 
?>
    <div class="shop-card">

        <div class="shop-img">
            <?php if (!empty($row['gambar'])) { ?>
                <img src="uploads/<?= $row['gambar']; ?>" alt="gambar buku">
            <?php } else { ?>
                <img src="uploads/default.jpg" alt="no image">
            <?php } ?>
        </div>

        <div class="shop-body">

            <div class="shop-title">
                <?= $row['judul_buku']; ?>
            </div>

            <div class="shop-info">
                <?= $row['kategori']; ?>
            </div>

            <div class="shop-stock">
                Stok: <?= $row['jumlah_buku']; ?>
            </div>

            <div class="shop-buttons">
                <a href="?page=detail_buku&id_buku=<?= $row['id_buku']; ?>" class="btn-detail">
                    Detail
                </a>

                <a href="?page=pinjam_buku&id_buku=<?= $row['id_buku']; ?>" class="btn-pinjam">
                    Pinjam
                </a>
            </div>

        </div>
    </div>
<?php } ?>

</div>

<?php }}?>


