<?php
include __DIR__ . '/../inc/conect.php';

$viewType = 'list';
$pageTitle = 'Koleksi Buku';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $viewType = 'detail';
    $bookId = intval($_GET['id']);
    $detailQuery = mysqli_query($koneksi, "SELECT * FROM tbl_buku WHERE id_buku = $bookId AND jumlah_buku > 0");
    $bookDetail = mysqli_fetch_assoc($detailQuery);
    $pageTitle = $bookDetail ? htmlspecialchars($bookDetail['judul_buku']) : 'Buku Tidak Ditemukan';
} elseif (isset($_GET['id_buku']) && !empty($_GET['id_buku'])) {
    $viewType = 'borrow';
    $borrowBookId = intval($_GET['id_buku']);
    $borrowBookQuery = mysqli_query($koneksi, "SELECT judul_buku FROM tbl_buku WHERE id_buku = $borrowBookId");
    $borrowBook = mysqli_fetch_assoc($borrowBookQuery);
    $pageTitle = 'Form Peminjaman';
} else {
    $itemsPerPage = 12;
    $currentPage = isset($_GET['halaman']) ? max(1, intval($_GET['halaman'])) : 1;
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    $countResult = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tbl_buku WHERE jumlah_buku > 0");
    $countRow = mysqli_fetch_assoc($countResult);
    $totalBooks = $countRow['total'];
    $totalPages = ceil($totalBooks / $itemsPerPage);
    
    $data = mysqli_query($koneksi, "SELECT * FROM tbl_buku WHERE jumlah_buku > 0 ORDER BY id_buku DESC LIMIT $itemsPerPage OFFSET $offset");
    $booksList = array();
    while ($row = mysqli_fetch_assoc($data)) {
        $booksList[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { background: #f5f5f5; font-family: 'Segoe UI', Tahoma, Geneva, sans-serif; }
        
        .wrapper { background: #f5f5f5; padding: 30px 20px; min-height: calc(100vh - 200px); }
        .content { max-width: 1300px; margin: 0 auto; }
        
        .header-box { background: white; padding: 30px 40px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 1px 4px rgba(0,0,0,0.05); }
        .header-box h2 { font-size: 1.8rem; font-weight: 700; color: #333; margin: 0; display: flex; align-items: center; gap: 12px; }
        
        .search-box { background: white; padding: 25px 40px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 1px 4px rgba(0,0,0,0.05); }
        .search-row { display: flex; gap: 20px; align-items: flex-end; }
        .search-group { flex: 1; min-width: 250px; }
        .search-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #666; font-size: 0.9rem; }
        .search-group input, .search-group select { width: 100%; padding: 12px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; transition: 0.3s; }
        .search-group input:focus, .search-group select:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.1); }
        
        .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; margin-bottom: 40px; }
        
        .card-book { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.05); transition: 0.3s; display: flex; flex-direction: column; }
        .card-book:hover { transform: translateY(-4px); box-shadow: 0 6px 12px rgba(0,0,0,0.1); }
        
        .card-img-wrapper { position: relative; height: 280px; background: #f0f4ff; overflow: hidden; }
        .card-img { width: 100%; height: 100%;object-fit: cover; transition: 0.3s; }
        .card-book:hover .card-img { transform: scale(1.05); }
        
        .badge-stok { position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; box-shadow: 0 2px 8px rgba(102,126,234,0.3); }
        
        .card-text-wrapper { padding: 20px; flex: 1; display: flex; flex-direction: column; }
        .card-title { font-size: 1rem; font-weight: 700; color: #333; margin-bottom: 10px; line-height: 1.4; min-height: 50px; word-wrap: break-word; }
        
        .info-line { font-size: 0.85rem; color: #666; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .info-line i { color: #667eea; width: 16px; }
        
        .btn-group { display: flex; gap: 10px; margin-top: auto; padding-top: 15px; }
        .btn-detail { flex: 1; padding: 11px 12px; border: 1px solid #667eea; background: white; color: #667eea; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; text-align: center; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 5px; }
        .btn-detail:hover { background: #f0f4ff; }
        
        .btn-pinjam { flex: 1; padding: 11px 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; text-align: center; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 5px; }
        .btn-pinjam:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102,126,234,0.3); color: white; }
        
        .pagination-wrap { display: flex; justify-content: center; gap: 8px; margin-bottom: 20px; }
        .pagination-wrap a, .pagination-wrap span { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem; text-decoration: none; color: #667eea; background: white; transition: 0.3s; }
        .pagination-wrap a:hover { background: #667eea; color: white; border-color: #667eea; }
        .pagination-wrap .active { background: #667eea; color: white; border-color: #667eea; }
        .pagination-wrap .disabled { color: #999; cursor: not-allowed; }
        
        .pagination-info { text-align: center; color: #999; font-size: 0.9rem; margin-top: 15px; }
        
        .detail-box { max-width: 1100px; margin: 30px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.05); }
        .detail-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 40px; color: white; }
        .btn-back { display: inline-block; color: white; text-decoration: none; font-weight: 600; font-size: 0.95rem; padding: 10px 0; transition: 0.3s; margin-bottom: 20px; }
        .btn-back:hover { opacity: 0.85; }
        
        .detail-body { display: grid; grid-template-columns: 300px 1fr; gap: 40px; padding: 40px; }
        .book-cover img { width: 100%; height: auto; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .book-detail-title { font-size: 2rem; font-weight: 700; color: #333; margin-bottom: 25px; line-height: 1.3; }
        
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px; padding-bottom: 30px; border-bottom: 1px solid #eee; }
        .meta-item { display: flex; flex-direction: column; }
        .meta-label { font-size: 0.85rem; font-weight: 700; color: #667eea; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .meta-value { font-size: 1rem; color: #333; font-weight: 600; }
        
        .sinopsis-box { margin-bottom: 30px; }
        .sinopsis-box h4 { font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: 15px; }
        .sinopsis-box p { color: #666; line-height: 1.8; font-size: 0.95rem; }
        
        .stock-info { background: #f0f4ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .stock-label { color: #667eea; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; margin-bottom: 5px; }
        .stock-value { font-size: 1.5rem; font-weight: 700; color: #333; }
        
        .action-btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 25px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; border: none; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,0.3); color: white; }
        
        .form-box { max-width: 600px; margin: 40px auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.05); }
        .form-box h3 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 30px; text-align: center; font-weight: 700; font-size: 1.5rem; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { font-weight: 600; margin-bottom: 8px; display: block; color: #333; font-size: 0.95rem; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 14px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; font-family: inherit; transition: 0.3s; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.1); }
        .form-group input[readonly] { background-color: #f8f9fa; cursor: not-allowed; color: #666; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        
        .form-buttons { display: flex; gap: 12px; margin-top: 30px; }
        .form-buttons button, .form-buttons a { flex: 1; padding: 14px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; transition: 0.3s; font-size: 0.95rem; }
        .btn-submit { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,0.3); }
        .btn-cancel { background-color: #e9ecef; color: #333; }
        .btn-cancel:hover { background-color: #dee2e6; }
        
        @media (max-width: 1200px) { .grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { .grid { grid-template-columns: repeat(2, 1fr); gap: 15px; } .search-row { flex-direction: column; gap: 15px; } .search-group { width: 100%; } .detail-body { grid-template-columns: 1fr; gap: 30px; padding: 30px 20px; } .meta-grid { grid-template-columns: 1fr; } .form-box { margin: 20px 15px; padding: 30px 20px; } }
        @media (max-width: 480px) { .grid { grid-template-columns: 1fr; } .header-box, .search-box { padding: 20px 15px; } .header-box h2 { font-size: 1.4rem; } .wrapper { padding: 15px 10px; } }
    </style>
</head>
<body>
<?php if ($viewType === 'detail' && $bookDetail) { ?>
<div class="detail-box">
    <div class="detail-header">
        <a href="view_Tampil_buku.php" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="detail-body">
        <div class="book-cover">
            <img src="<?= !empty($bookDetail['gambar_buku']) ? htmlspecialchars($bookDetail['gambar_buku']) : 'https://via.placeholder.com/300x400?text='.urlencode($bookDetail['judul_buku']) ?>" alt="<?= htmlspecialchars($bookDetail['judul_buku']) ?>">
        </div>
        <div>
            <h1 class="book-detail-title"><?= htmlspecialchars($bookDetail['judul_buku']) ?></h1>
            <div class="meta-grid">
                <div class="meta-item"><div class="meta-label">Penerbit</div><div class="meta-value"><?= htmlspecialchars($bookDetail['penerbit'] ?? '—') ?></div></div>
                <div class="meta-item"><div class="meta-label">Kategori</div><div class="meta-value"><?= htmlspecialchars($bookDetail['kategori'] ?? '—') ?></div></div>
                <div class="meta-item"><div class="meta-label">Tahun</div><div class="meta-value"><?= htmlspecialchars($bookDetail['tahun_terbit'] ?? '—') ?></div></div>
                <div class="meta-item"><div class="meta-label">Halaman</div><div class="meta-value"><?= htmlspecialchars($bookDetail['jumlah_halaman'] ?? '—') ?></div></div>
            </div>
            <div class="sinopsis-box">
                <h4>Sinopsis</h4>
                <p><?= htmlspecialchars($bookDetail['sinopsis'] ?? 'Tidak ada sinopsis') ?></p>
            </div>
            <div class="stock-info">
                <div class="stock-label"><i class="bi bi-box2"></i> Stok Tersedia</div>
                <div class="stock-value"><?= htmlspecialchars($bookDetail['jumlah_buku']) ?> Eksemplar</div>
            </div>
            <a href="view_Tampil_buku.php?id_buku=<?= $bookDetail['id_buku'] ?>" class="action-btn"><i class="bi bi-bookmark-plus"></i> Pinjam</a>
        </div>
    </div>
</div>
<?php } elseif ($viewType === 'borrow' && $borrowBook) { ?>
<div class="form-box">
    <h3>Form Peminjaman</h3>
    <form method="POST">
        <div class="form-group"><label>Judul Buku</label><input type="text" value="<?= htmlspecialchars($borrowBook['judul_buku']) ?>" readonly><input type="hidden" name="id_buku" value="<?= $borrowBookId ?>"></div>
        <div class="form-group"><label>Nama Peminjam *</label><input type="text" name="nama_peminjam" placeholder="Nama Anda" required></div>
        <div class="form-group"><label>NIS/NIM *</label><input type="text" name="nomor_identitas" placeholder="Nomor identitas" required></div>
        <div class="form-group"><label>Jumlah *</label><input type="number" name="jumlah_pinjam" value="1" min="1" max="3" required></div>
        <div class="form-group"><label>Tanggal Pinjam *</label><input type="date" name="tgl_pinjam" required></div>
        <div class="form-group"><label>Tanggal Kembali *</label><input type="date" name="tgl_kembali" required></div>
        <div class="form-group"><label>Catatan</label><textarea name="catatan" placeholder="Catatan..."></textarea></div>
        <div class="form-buttons"><button type="submit" class="btn-submit"><i class="bi bi-check-circle"></i> Ajukan</button><a href="view_Tampil_buku.php" class="btn-cancel"><i class="bi bi-x-circle"></i> Batal</a></div>
    </form>
</div>
<?php } else { ?>
<div class="wrapper">
    <div class="content">
        <div class="header-box"><h2><i class="bi bi-book-half" style="color: #667eea;"></i> Koleksi Buku</h2></div>
        <div class="search-box">
            <div class="search-row">
                <div class="search-group" style="flex: 2;">
                    <label for="search">Cari Buku</label>
                    <input type="text" id="search" placeholder="Judul, penerbit, kategori...">
                </div>
                <div class="search-group" style="flex: 1;">
                    <label for="sort">Urutkan</label>
                    <select id="sort">
                        <option value="">Semua</option>
                        <option value="title">Judul A-Z</option>
                        <option value="title-desc">Judul Z-A</option>
                        <option value="stock">Stok Terbanyak</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="grid" id="grid">
            <?php if (!empty($booksList)) { 
                foreach ($booksList as $b) { 
                    $img = (!empty($b['gambar_buku'])) ? htmlspecialchars($b['gambar_buku']) : 'https://via.placeholder.com/300x400?text='.urlencode($b['judul_buku']);
            ?>
            <div class="card-book" data-title="<?= htmlspecialchars($b['judul_buku']) ?>" data-pub="<?= htmlspecialchars($b['penerbit'] ?? '') ?>">
                <div class="card-img-wrapper">
                    <img src="<?= $img ?>" alt="<?= htmlspecialchars($b['judul_buku']) ?>" class="card-img">
                    <div class="badge-stok">Stok: <?= htmlspecialchars($b['jumlah_buku']) ?></div>
                </div>
                <div class="card-text-wrapper">
                    <div class="card-title"><?= htmlspecialchars($b['judul_buku']) ?></div>
                    <div class="info-line"><i class="bi bi-shop"></i> <?= htmlspecialchars($b['penerbit'] ?? '—') ?></div>
                    <div class="info-line"><i class="bi bi-tag"></i> <?= htmlspecialchars($b['kategori'] ?? '—') ?></div>
                    <div class="info-line"><i class="bi bi-calendar"></i> <?= htmlspecialchars($b['tahun_terbit'] ?? '—') ?></div>
                    <div class="btn-group">
                        <a href="?id=<?= $b['id_buku'] ?>" class="btn-detail"><i class="bi bi-eye"></i> Detail</a>
                        <a href="?id_buku=<?= $b['id_buku'] ?>" class="btn-pinjam"><i class="bi bi-bookmark-plus"></i> Pinjam</a>
                    </div>
                </div>
            </div>
            <?php } } else { ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #999;"><i class="bi bi-book-half" style="font-size: 3rem; display: block; margin-bottom: 15px;"></i><p>Tidak ada buku tersedia</p></div>
            <?php } ?>
        </div>
        <?php if ($totalPages > 1) { ?>
        <div class="pagination-wrap">
            <?php if ($currentPage > 1) { ?><a href="?halaman=<?= $currentPage - 1 ?>"><i class="bi bi-chevron-left"></i></a><?php } else { ?><span class="disabled"><i class="bi bi-chevron-left"></i></span><?php } ?>
            <?php if ($currentPage > 3) { ?><a href="?halaman=1">1</a><?php if ($currentPage > 4) { ?><span class="disabled">...</span><?php } ?><?php } ?>
            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++) { ?>
                <?php if ($i === $currentPage) { ?><span class="active"><?= $i ?></span><?php } else { ?><a href="?halaman=<?= $i ?>"><?= $i ?></a><?php } ?>
            <?php } ?>
            <?php if ($currentPage < $totalPages - 2) { ?><?php if ($currentPage < $totalPages - 3) { ?><span class="disabled">...</span><?php } ?><a href="?halaman=<?= $totalPages ?>"><?= $totalPages ?></a><?php } ?>
            <?php if ($currentPage < $totalPages) { ?><a href="?halaman=<?= $currentPage + 1 ?>"><i class="bi bi-chevron-right"></i></a><?php } else { ?><span class="disabled"><i class="bi bi-chevron-right"></i></span><?php } ?>
        </div>
        <div class="pagination-info">Halaman <?= $currentPage ?> dari <?= $totalPages ?> | Total: <?= $totalBooks ?> buku</div>
        <?php } ?>
    </div>
</div>
<?php } ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const search = document.getElementById('search');
if (search) {
    search.addEventListener('keyup', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.card-book').forEach(card => {
            const title = card.getAttribute('data-title').toLowerCase();
            const pub = card.getAttribute('data-pub').toLowerCase();
            card.style.display = (title.includes(term) || pub.includes(term)) ? '' : 'none';
        });
    });
}
const sort = document.getElementById('sort');
if (sort) {
    sort.addEventListener('change', function() {
        const container = document.getElementById('grid');
        const cards = Array.from(container.querySelectorAll('.card-book'));
        if (this.value === 'title') {
            cards.sort((a, b) => a.getAttribute('data-title').localeCompare(b.getAttribute('data-title')));
        } else if (this.value === 'title-desc') {
            cards.sort((a, b) => b.getAttribute('data-title').localeCompare(a.getAttribute('data-title')));
        } else if (this.value === 'stock') {
            cards.sort((a, b) => parseInt(b.querySelector('.badge-stok').textContent) - parseInt(a.querySelector('.badge-stok').textContent));
        }
        cards.forEach(card => container.appendChild(card));
    });
}
const tglPinjam = document.querySelector('input[name="tgl_pinjam"]');
if (tglPinjam) {
    const today = new Date().toISOString().split('T')[0];
    tglPinjam.min = today;
    tglPinjam.value = today;
}
</script>
</body>
</html>