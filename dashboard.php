<?php
include "inc/conect.php";
session_start();

if (empty($_SESSION['id']) || empty($_SESSION['akses'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['username'];
$query = "SELECT * FROM tbl_users WHERE username='$user'";
$hasil = mysqli_query($koneksi, $query);
$tampil = mysqli_fetch_array($hasil);

$page = isset($_GET['page']) ? $_GET['page'] : 'awal';

function find_table($koneksi, $names) {
    foreach ($names as $t) {
        $t_esc = mysqli_real_escape_string($koneksi, $t);
        $res = mysqli_query($koneksi, "SHOW TABLES LIKE '$t_esc'");
        if ($res && mysqli_num_rows($res) > 0) return $t;
    }
    return false;
}

$countBuku = 0; $countPeminjam = 0; $countPending = 0; $countReturnToday = 0;

$tableBuku = find_table($koneksi, array('buku','tbl_buku','tb_buku'));
if ($tableBuku) {
    $res = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM `".mysqli_real_escape_string($koneksi,$tableBuku)."`");
    if ($res) { $r = mysqli_fetch_assoc($res); $countBuku = $r['cnt']; }
}

$tablePeminjam = find_table($koneksi, array('peminjam','tbl_peminjam','tb_peminjam'));
if ($tablePeminjam) {
    $res = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM `".mysqli_real_escape_string($koneksi,$tablePeminjam)."`");
    if ($res) { $r = mysqli_fetch_assoc($res); $countPeminjam = $r['cnt']; }

    $res = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM `".mysqli_real_escape_string($koneksi,$tablePeminjam)."` WHERE status='pending'");
    if ($res) { $r = mysqli_fetch_assoc($res); $countPending = $r['cnt']; }

    $res = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM `".mysqli_real_escape_string($koneksi,$tablePeminjam)."` WHERE DATE(tanggal_kembali)=CURDATE()");
    if ($res) { $r = mysqli_fetch_assoc($res); $countReturnToday = $r['cnt']; }
}

$recentPeminjaman = array();
if ($tablePeminjam) {
    $res = mysqli_query($koneksi, "SELECT nama_peminjam, judul_buku, tanggal_pinjam, status FROM `".mysqli_real_escape_string($koneksi,$tablePeminjam)."` ORDER BY tanggal_pinjam DESC LIMIT 7");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $recentPeminjaman[] = $row;
        }
    }
}

// ✅ PERBAIKAN: tambah gambar_buku di SELECT
$booksList = array();
if ($tableBuku) {
    $tbl = mysqli_real_escape_string($koneksi, $tableBuku);
    $res = mysqli_query($koneksi, "SELECT id_buku, judul_buku, jumlah_buku, tahun_terbit, gambar_buku FROM `".$tbl."` ORDER BY id_buku DESC LIMIT 8");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $booksList[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f1f3f4;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: #202124;
        }

        .navbar {
            background: rgba(29, 116, 85, 0.75) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            padding: 10px 18px;
        }
        .navbar-brand { font-weight: 600; color: #fff !important; letter-spacing: 0.3px; }
        #userDropdown {
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            padding: 6px 14px;
            backdrop-filter: blur(10px);
            transition: .25s ease;
        }
        #userDropdown:hover { background: rgba(255,255,255,0.25); }
        .dropdown-menu {
            margin-top: 12px !important;
            border-radius: 22px;
            border: none;
            padding: 8px 0;
            box-shadow: 0 18px 40px rgba(0,0,0,0.15);
            animation: iosDropdown .25s ease;
        }
        .dropdown-item { padding: 10px 18px; border-radius: 12px; }
        .dropdown-item:hover { background: rgba(0,0,0,0.05); }
        @keyframes iosDropdown {
            from { opacity: 0; transform: translateY(-8px) scale(.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .sidebar { min-height: 125vh; background: transparent; padding: 20px 14px; }
        .sidebar .p-3 {
            background: #0a740a;
            border-radius: 20px;
            height: calc(100vh - 40px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        .sidebar .profile {
            background: #549f4e;
            padding: 11px 14px;
            margin-bottom: 18px;
            border-radius: 18px;
        }
        .sidebar .avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: #e8f0fe;
            color: #1a73e8;
            font-weight: 600;
            display: flex; align-items: center; justify-content: center;
        }
        .sidebar .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px; border-radius: 12px;
            color: #ffffff; font-weight: 500;
            position: relative; transition: all .2s ease;
        }
        .sidebar .nav-link:hover { background: #f1f3f4; color: #0e9c0e; }
        .sidebar .nav-link.active { background: #e8f0fe; color: #0e9c0e; font-weight: 600; }
        .sidebar .nav-link.active::before {
            content: "";
            position: absolute; left: -10px; top: 6px; bottom: 6px;
            width: 4px; background: #00ff62; border-radius: 4px;
        }
        .sidebar .nav-link i { font-size: 1.1rem; }
        .sidebar .badge { margin-left: auto; background: #1a73e8; font-size: 0.7rem; }
        .sidebar .collapse .nav-link { font-size: 0.92rem; padding-left: 28px; }
        .sidebar .text-danger { color: #d93025 !important; }
        .offcanvas-body .nav-link { border-radius: 12px; margin-bottom: 4px; }
        .offcanvas .nav-link.active { background: #e8f0fe; color: #1a73e8; }

        .card { border: none; border-radius: 16px; background: #ffffff; box-shadow: 0 2px 6px rgba(60,64,67,.15); transition: .2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(60,64,67,.2); }

        .book-card .card { border-radius: 16px; overflow: hidden; }
        .book-card .card:hover { transform: translateY(-4px); }
        .book-cover { height: 190px; object-fit: cover; background: #f1f3f4; }

        .btn-primary { background: #1a73e8; border: none; border-radius: 10px; font-weight: 500; }
        .btn-primary:hover { background: #1765cc; }

        .chart-container { background: #ffffff; border-radius: 16px; padding: 12px; }
        .offcanvas { background: #ffffff; }
        .offcanvas .nav-link { color: #5f6368; }
        .offcanvas .nav-link.active { background: #e8f0fe; color: #1a73e8; }
        h4, h5 { font-weight: 600; }
        small { color: #ffffff; }

        /* KATALOG */
        .shop-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 22px;
            padding: 10px 0;
        }
        .shop-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .shop-card:hover { transform: translateY(-6px); box-shadow: 0 14px 28px rgba(0,0,0,0.12); }
        .shop-img { height: 210px; overflow: hidden; background: #f8f8f8; }
        .shop-img img { width: 100%; height: 100%; object-fit: contain; }
        .shop-body { padding: 14px; flex: 1; }
        .shop-title { font-size: 1rem; font-weight: 600; margin-bottom: 6px; color: #222; height: 42px; overflow: hidden; }
        .shop-info { font-size: 0.85rem; color: #666; margin-bottom: 4px; }
        .shop-stock { font-weight: bold; font-size: 0.9rem; color: #1a8f18; margin: 8px 0 12px 0; }
        .shop-buttons { display: flex; gap: 8px; }
        .btn-detail { flex: 1; background: #e9ecef; color: #333; text-align: center; padding: 8px; border-radius: 8px; text-decoration: none; font-size: 0.8rem; }
        .btn-detail:hover { background: #e6edb0; color: #000; }
        .btn-pinjam { flex: 1; background: #79a171; color: #fff; text-align: center; padding: 8px; border-radius: 8px; text-decoration: none; font-size: 0.8rem; }
        .btn-pinjam:hover { background: #8fe66a; color: #000; }

        @media (max-width: 576px) { .book-cover { height: 150px; } }
    </style>
</head>

<body>

<?php $initial = !empty($tampil['nama']) ? strtoupper(mb_substr($tampil['nama'],0,1)) : 'U'; ?>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
        <button class="btn btn-primary d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand fw-bold d-flex align-items-center" href="dashboard.php?page=awal">
            <i class="bi bi-book-half me-2"></i> PerpusKu
        </a>
        <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
            <li class="nav-item me-3 d-none d-lg-block">
                <a class="nav-link text-white" href="#"><i class="bi bi-bell"></i></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <div class="d-none d-lg-block text-start">
                        <div style="line-height:1;font-size:.95rem;">Hai, <strong><?= htmlspecialchars($tampil['nama']) ?></strong></div>
                        <small class="text-white-50" style="font-size:.75rem;"><?= htmlspecialchars($tampil['akses']) ?></small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="?page=profile"><i class="bi bi-person me-2"></i> Profil</a></li>
                    <li><a class="dropdown-item" href="?page=profile&edit=1"><i class="bi bi-gear me-2"></i> Pengaturan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- OFFCANVAS SIDEBAR (mobile) -->
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">PerpusKu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="p-3">
            <nav class="nav flex-column">
                <a class="nav-link <?= $page=='awal'?'active':'' ?>" href="?page=awal"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                <a class="nav-link <?= $page=='profile'?'active':'' ?>" href="?page=profile"><i class="bi bi-person me-2"></i> Profil Saya</a>
                <?php if ($tampil['akses'] === 'admin'): ?>
                    <a class="nav-link <?= $page=='buku'?'active':'' ?>" href="?page=buku"><i class="bi bi-book me-2"></i> Data Buku</a>
                    <a class="nav-link <?= $page=='kategori'?'active':'' ?>" href="?page=kategori"><i class="bi bi-tags me-2"></i> Kategori</a>
                    <a class="nav-link <?= $page=='penerbit'?'active':'' ?>" href="?page=penerbit"><i class="bi bi-building me-2"></i> Penerbit</a>
                <?php else: ?>
                    <a class="nav-link <?= $page=='buku'?'active':'' ?>" href="?page=buku"><i class="bi bi-book me-2"></i> Buku</a>
                <?php endif; ?>
                <a class="nav-link <?= $page=='peminjam'?'active':'' ?>" href="?page=peminjam">
                    <i class="bi bi-people me-2"></i> Data Peminjam
                    <?php if (!empty($countPending)) echo '<span class="badge ms-auto">'.intval($countPending).'</span>'; ?>
                </a>
                <a class="nav-link <?= $page=='history'?'active':'' ?>" href="?page=history"><i class="bi bi-clock-history me-2"></i> History</a>
                <a href="logout.php" class="nav-link text-danger mt-3"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
            </nav>
        </div>
    </div>
</div>

<!-- MAIN LAYOUT -->
<div class="container-fluid">
    <div class="row">

        <!-- DESKTOP SIDEBAR -->
        <aside class="col-md-2 sidebar d-none d-md-block">
            <div class="p-3">
                <div class="profile">
                    <div class="avatar"><?= $initial ?></div>
                    <div>
                        <div style="font-weight:700;color:#fff;"><?= htmlspecialchars($tampil['nama']) ?></div>
                        <small><?= htmlspecialchars($tampil['akses']) ?></small>
                    </div>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link <?= $page=='awal'?'active':'' ?>" href="?page=awal"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a class="nav-link <?= $page=='profile'?'active':'' ?>" href="?page=profile"><i class="bi bi-person"></i> Profil Saya</a>
                    <?php if ($tampil['akses'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#masterDataMenu" role="button">
                            <span><i class="bi bi-gear"></i> Data Buku</span>
                            <i class="bi bi-chevron-down"></i>
                        </a>
                        <div class="collapse" id="masterDataMenu">
                            <ul class="nav flex-column ms-3">
                                <li><a class="nav-link <?= $page=='buku'?'active':'' ?>" href="?page=buku"><i class="bi bi-book"></i> Buku</a></li>
                                <li><a class="nav-link <?= $page=='kategori'?'active':'' ?>" href="?page=kategori"><i class="bi bi-tags"></i> Kategori</a></li>
                                <li><a class="nav-link <?= $page=='penerbit'?'active':'' ?>" href="?page=penerbit"><i class="bi bi-building"></i> Penerbit</a></li>
                            </ul>
                        </div>
                    </li>
                    <?php elseif ($tampil['akses'] === 'anggota'): ?>
                        <a class="nav-link <?= $page=='buku'?'active':'' ?>" href="?page=buku"><i class="bi bi-book"></i> Buku</a>
                    <?php endif; ?>
                    <a class="nav-link d-flex align-items-center <?= $page=='peminjam'?'active':'' ?>" href="?page=peminjam">
                        <i class="bi bi-people"></i> Data Peminjam
                        <?php if (!empty($countPending)) echo '<span class="badge ms-auto">'.intval($countPending).'</span>'; ?>
                    </a>
                    <a class="nav-link <?= $page=='history'?'active':'' ?>" href="?page=history"><i class="bi bi-clock-history"></i> History</a>
                </nav>
            </div>
        </aside>

        <!-- KONTEN HALAMAN -->
        <main class="col-md-10 p-4">
            <?php if ($page == "awal") { ?>

                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-0">Dashboard</h4>
                            <small class="text-muted">Ringkasan singkat sistem perpustakaan</small>
                        </div>
                    </div>

                    <!-- STAT CARDS -->
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 bg-primary text-white rounded p-3"><i class="bi bi-book fs-4"></i></div>
                                    <div><h6 class="mb-0">Jumlah Buku</h6><h4 class="fw-bold mb-0"><?= number_format($countBuku) ?></h4></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 bg-info text-white rounded p-3"><i class="bi bi-people fs-4"></i></div>
                                    <div><h6 class="mb-0">Peminjam</h6><h4 class="fw-bold mb-0"><?= number_format($countPeminjam) ?></h4></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 bg-warning text-white rounded p-3"><i class="bi bi-clock-history fs-4"></i></div>
                                    <div><h6 class="mb-0">Menunggu Persetujuan</h6><h4 class="fw-bold mb-0"><?= number_format($countPending) ?></h4></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 bg-success text-white rounded p-3"><i class="bi bi-arrow-counterclockwise fs-4"></i></div>
                                    <div><h6 class="mb-0">Pengembalian Hari Ini</h6><h4 class="fw-bold mb-0"><?= number_format($countReturnToday) ?></h4></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CHART -->
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">Ringkasan Aktivitas</h5>
                                        <small class="text-muted">Terakhir 7 hari</small>
                                    </div>
                                    <div class="chart-container" style="min-height:220px">
                                        <canvas id="activityChart" style="width:100%;height:100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ BUKU KAMI — gambar_buku sudah diambil dari query -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h3 class="mb-3">Buku Kami</h3>
                            <div class="shop-container">
                                <?php if (!empty($booksList)) { foreach ($booksList as $b) { ?>
                                    <div class="shop-card">
                                        <div class="shop-img">
                                            <?php if (!empty($b['gambar_buku'])): ?>
                                                <img src="uploads/buku/<?= htmlspecialchars($b['gambar_buku']); ?>"
                                                     alt="<?= htmlspecialchars($b['judul_buku']); ?>">
                                            <?php else: ?>
                                                <img src="https://via.placeholder.com/300x180?text=No+Cover"
                                                     alt="Belum ada cover">
                                            <?php endif; ?>
                                        </div>
                                        <div class="shop-body">
                                            <div class="shop-title"><?= htmlspecialchars($b['judul_buku']); ?></div>
                                            <div class="shop-info"><?= htmlspecialchars($b['kategori'] ?? ''); ?></div>
                                            <div class="shop-stock">Stok: <?= htmlspecialchars($b['jumlah_buku']); ?></div>
                                            <div class="shop-buttons">
                                                <a href="?page=detail_buku&id_buku=<?= $b['id_buku']; ?>" class="btn-detail">Detail</a>
                                                <a href="?page=tambah_peminjam" class="btn-pinjam">Pinjam</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } } else { ?>
                                    <div class="text-muted p-3">Tidak ada data buku untuk ditampilkan.</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($tampil['akses'] == 'anggota') { ?>
                        <div class="d-flex justify-content-center mt-4">
                            <a href="?page=buku" class="btn btn-sm btn-success" style="width:350px;border-radius:10px;font-size:20px;">Lihat semua Buku</a>
                        </div>
                    <?php } ?>

                </div>

            <?php } elseif ($page == "profile") {
                include "profile.php";
            } elseif ($page == "buku") {
                include "buku/view_buku.php";
            } elseif ($page == "tambah_buku") {
                include "buku/tambah_buku.php";
            } elseif ($page == "simpan_buku") {
                include "buku/simpan_buku.php";
            } elseif ($page == "edit_buku") {
                include "buku/edit_buku.php";
            } elseif ($page == "hapus_buku") {
                include "buku/hapus_buku.php";
            } elseif ($page == "detail_buku") {
                include "buku/detail_buku.php";
            } elseif ($page == "kategori") {
                include "kategori/view_kategori.php";
            } elseif ($page == "tambah_kategori") {
                include "kategori/tambah_kategori.php";
            } elseif ($page == "simpan_kategori") {
                include "kategori/simpan_kategori.php";
            } elseif ($page == "edit_kategori") {
                include "kategori/edit_kategori.php";
            } elseif ($page == "hapus_kategori") {
                include "kategori/hapus_kategori.php";
            } elseif ($page == "penerbit") {
                include "penerbit/view_penerbit.php";
            } elseif ($page == "tambah_penerbit") {
                include "penerbit/tambah_penerbit.php";
            } elseif ($page == "simpan_penerbit") {
                include "penerbit/simpan_penerbit.php";
            } elseif ($page == "edit_penerbit") {
                include "penerbit/edit_penerbit.php";
            } elseif ($page == "hapus_penerbit") {
                include "penerbit/hapus_penerbit.php";
            } elseif ($page == "peminjam") {
                include "peminjam/view_peminjam.php";
            } elseif ($page == "tambah_peminjam") {
                include "peminjam/tambah_peminjam.php";
            } elseif ($page == "simpan_peminjam") {
                include "peminjam/simpan_peminjam.php";
            } elseif ($page == "setujui_peminjam") {
                include "peminjam/setujui_peminjaman.php";
            } elseif ($page == "tolak_peminjam") {
                include "peminjam/tolak_peminjaman.php";
            } elseif ($page == "history") {
                include "history/view_history.php";
            } elseif ($page == "pengembalian") {
                include "pengembalian/proses_pengembalian.php";
            } elseif ($page == "setujui_pengembalian") {
                include "pengembalian/setujui_pengembalian.php";
            } else {
                include "404.php";
            } ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('activityChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['6 hari', '5 hari', '4 hari', '3 hari', '2 hari', 'Kemarin', 'Hari Ini'],
                datasets: [{
                    label: 'Peminjaman',
                    data: [3, 7, 4, 6, 8, 5, 9],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.15)',
                    tension: 0.3, fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 2 } } } }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>