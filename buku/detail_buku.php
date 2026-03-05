<?php
include __DIR__ . '/../inc/conect.php';
$id = $_GET['id_buku'];
$query = "SELECT buku.*, penerbit.nama_penerbit 
          FROM tbl_buku AS buku 
          JOIN tbl_penerbit AS penerbit 
          ON buku.id_penerbit = penerbit.id_penerbit 
          WHERE buku.id_buku = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Detail Buku</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 0;
    }
    .wrapper {
        max-width: 900px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        display: flex;
        gap: 30px;
    }
    .book-info {
        flex: 2;
    }
    .book-info h1 {
        font-size: 1.8rem;
        color: #36903e;
        margin-bottom: 20px;
        border-bottom: 2px solid #36903e;
        padding-bottom: 10px;
    }
    .detail-row {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        gap: 20px;
    }
    .detail-row div {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-row strong {
        color: #495057;
    }
    .sinopsis {
        margin-top: 20px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        line-height: 1.6;
    }
    .btn {
        display: inline-block;
        margin-top: 25px;
        padding: 12px 20px;
        background: #36903e;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s;
    }
    .btn:hover {
        background: #4ad056;
        box-shadow: 0 6px 14px rgba(54, 144, 62, 0.3);
    }
    .book-image {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        min-height: 250px;
    }
    .book-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
    }
    .book-image span {
        color: #aaa;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .wrapper {
            flex-direction: column;
        }
        .book-image {
            min-height: 200px;
        }
    }
</style>
</head>
<body>
<div class="wrapper">

    <div class="book-info">
        <h1>Detail Buku</h1>

        <div class="detail-row">
            <div><strong>Judul:</strong> <span><?= $data['judul_buku'] ?></span></div>
        </div>
        <div class="detail-row">
            <div><strong>Penerbit:</strong> <span><?= $data['nama_penerbit'] ?></span></div>
            <div><strong>Tahun Terbit:</strong> <span><?= $data['tahun_terbit'] ?></span></div>
        </div>
        <div class="detail-row">
            <div><strong>Jumlah Halaman:</strong> <span><?= $data['jumlah_halaman'] ?></span></div>
            <div><strong>Stok:</strong> <span><?= $data['jumlah_buku'] ?></span></div>
        </div>

        <div class="sinopsis">
            <strong>Sinopsis:</strong><br>
            <?= nl2br($data['sinopsis']) ?>
        </div>

        <a href="dashboard.php?page=buku" class="btn">⬅ Kembali ke Daftar Buku</a>
    </div>

    <!-- GAMBAR COVER -->
    <div class="book-image">
        <?php if (!empty($data['gambar_buku'])): ?>
           <img src="uploads/buku/<?= $data['gambar_buku'] ?>" alt="Cover Buku">
        <?php else: ?>
            <span>Belum ada cover</span>
        <?php endif; ?>
    </div>

</div>
</body>
</html>