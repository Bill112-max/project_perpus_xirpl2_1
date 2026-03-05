<?php
include __DIR__ . '/../inc/conect.php';

$limit = 12;
$current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $limit;

$query_count = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_buku");
$row_count = mysqli_fetch_assoc($query_count);
$total_rows = $row_count['total'];
$total_pages = ceil($total_rows / $limit);

$sql = "SELECT 
    tbl_buku.*, 
    tbl_kategori.kategori,
    tbl_penerbit.nama_penerbit 
    FROM tbl_buku
JOIN tbl_kategori ON tbl_buku.id_kategori = tbl_kategori.id_kategori
JOIN tbl_penerbit ON tbl_buku.id_penerbit = tbl_penerbit.id_penerbit
LIMIT $offset, $limit";

$data = mysqli_query($koneksi, $sql);

$search   = isset($_GET['search'])   ? $_GET['search']   : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan data buku</title>
    <link rel="stylesheet" href="../css/view.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #eaf2ff, #f4f8ff);
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin: 35px 0;
            font-size: 2rem;
            font-weight: 700;
            color: #19864a;
            letter-spacing: 0.5px;
        }

        .btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            background: linear-gradient(135deg, #10894d, #0aa251);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        }

        .btn:hover {
            background: #467134;
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.08);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 50px;
        }

        table th {
            background: #19864a;
            border: 0.0025px dotted #06359a;
            color: white;
            font-weight: 600;
            padding: 14px;
            text-align: left;
            font-size: 0.95rem;
        }

        table td {
            padding: 12px 14px;
            border-bottom: 0.5px solid #06359a;
            vertical-align: middle;
            color: #333;
            font-size: 0.92rem;
        }

        table tr:hover {
            background-color: #f0f6ff;
        }

        table .btn-primary {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
            background: linear-gradient(135deg, #10894d, #0aa251);
            color: white;
            border: none;
            transition: 0.2s ease;
            text-decoration: none;
            display: inline-block;
            margin: 2px;
        }

        table .btn-primary:hover {
            background: #467134;
        }

        /* KATALOG ANGGOTA */
        .shop-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 22px;
            padding: 30px;
        }

        .shop-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .shop-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.12);
        }

        .shop-img {
           height: 210px;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f0f0f0;
        }

        .shop-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #f8f8f8;
            /* ← opsional: biar ruang kosongnya tidak abu gelap */
        }

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
            color: #1a8f18;
            margin: 8px 0 12px 0;
        }

        .shop-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-detail {
            flex: 1;
            background: #e9ecef;
            color: #333;
            text-align: center;
            padding: 8px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.8rem;
        }

        .btn-detail:hover {
            background: #e6edb0;
        }

        .btn-pinjam {
            flex: 1;
            background: #79a171;
            color: #fff;
            text-align: center;
            padding: 8px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.8rem;
        }

        .btn-pinjam:hover {
            background: #8fe66a;
            color: #000;
        }

        /* PAGINATION */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 20px 0;
            gap: 5px;
        }

        .page-link {
            padding: 8px 16px;
            border: 1px solid #0d6efd;
            color: #0d6efd;
            text-decoration: none;
            border-radius: 5px;
        }

        .page-item.active .page-link {
            background: #0d6efd;
            color: white;
        }

        .page-item.disabled .page-link {
            border-color: #ccc;
            color: #ccc;
            pointer-events: none;
        }

        @media (max-width: 768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            table thead {
                display: none;
            }

            table tr {
                margin-bottom: 15px;
                border-radius: 10px;
                padding: 12px;
                background: #fff;
            }

            table td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                color: #0d6efd;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>

    <?php if ($tampil['akses'] == 'admin') { ?>
        <h2>Daftar Data Buku</h2>
        <a href="?page=tambah_buku" class="btn">+ Tambah Buku</a>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>ID Buku</th>
                    <th>Judul Buku</th>
                    <th>Sinopsis</th>
                    <th>Jml Halaman</th>
                    <th>Jml Buku</th>
                    <th>Kategori</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($data)) { ?>
                    <tr>
                        <td data-label="ID Buku"><?= $row['id_buku']; ?></td>
                        <td data-label="Judul Buku"><?= $row['judul_buku']; ?></td>
                        <td data-label="Sinopsis"><?= $row['sinopsis']; ?></td>
                        <td data-label="Jml Halaman"><?= $row['jumlah_halaman']; ?></td>
                        <td data-label="Jml Buku"><?= $row['jumlah_buku']; ?></td>
                        <td data-label="Kategori"><?= $row['kategori']; ?></td>
                        <td data-label="Penerbit"><?= $row['nama_penerbit']; ?></td>
                        <td data-label="Tahun"><?= $row['tahun_terbit']; ?></td>
                        <td data-label="AKSI">
                            <a href="?page=edit_buku&id_buku=<?= $row['id_buku']; ?>" class="btn-primary">EDIT</a>
                            <a href="?page=hapus_buku&id_buku=<?= $row['id_buku']; ?>" class="btn-primary" onclick="return confirm('Yakin ingin menghapus?')">HAPUS</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>


    <?php if ($tampil['akses'] == 'anggota') { ?>

        <h2>Katalog Buku</h2>
        <div class="shop-container">
            <?php
            mysqli_data_seek($data, 0);
            while ($row = mysqli_fetch_array($data)) {
            ?>
                <div class="shop-card">
                    <div class="shop-img">

                        <?php if (!empty($row['gambar_buku'])): ?>
                            <!-- ✅ Tampil gambar dari folder uploads -->
                            <img src="uploads/buku/<?= $row['gambar_buku']; ?>"
                                alt="<?= htmlspecialchars($row['judul_buku']); ?>">
                        <?php else: ?>
                            <!-- Jika belum ada gambar -->
                            <img src="default.jpg" alt="Belum ada cover">
                        <?php endif; ?>

                    </div>
                    <div class="shop-body">
                        <div class="shop-title"><?= $row['judul_buku']; ?></div>
                        <div class="shop-info"><?= $row['kategori']; ?></div>
                        <div class="shop-stock">Stok: <?= $row['jumlah_buku']; ?></div>
                        <div class="shop-buttons">
                            <a href="?page=detail_buku&id_buku=<?= $row['id_buku']; ?>" class="btn-detail">Detail</a>
                            <a href="?page=tambah_peminjam" class="btn-pinjam">Pinjam</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    <?php } ?>


    <?php if ($total_pages > 1) {
        $base_url = '?page=buku';
        if (!empty($search))   $base_url .= '&search='   . urlencode($search);
        if (!empty($kategori)) $base_url .= '&kategori=' . urlencode($kategori);
    ?>
        <nav>
            <ul class="pagination">
                <li class="page-item <?= ($current_page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= $base_url . '&p=' . ($current_page - 1); ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                        <a class="page-link" href="<?= $base_url . '&p=' . $i; ?>"><?= $i; ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?= $base_url . '&p=' . ($current_page + 1); ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php } ?>

</body>

</html>

<?php
mysqli_free_result($data);
mysqli_close($koneksi);
?>