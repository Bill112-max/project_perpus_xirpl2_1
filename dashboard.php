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

// Fungsi bantu: cari tabel dari beberapa varian nama umum
function find_table($koneksi, $names) {
    foreach ($names as $t) {
        $t_esc = mysqli_real_escape_string($koneksi, $t);
        $res = mysqli_query($koneksi, "SHOW TABLES LIKE '$t_esc'");
        if ($res && mysqli_num_rows($res) > 0) return $t;
    }
    return false;
}

// Ambil statistik singkat (aman, tanpa input user)
$countBuku = 0; $countPeminjam = 0; $countPending = 0; $countReturnToday = 0;

// coba cari nama tabel buku yang mungkin ada
$tableBuku = find_table($koneksi, array('buku','tbl_buku','tb_buku'));
if ($tableBuku) {
    $res = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM `".mysqli_real_escape_string($koneksi,$tableBuku)."`");
    if ($res) { $r = mysqli_fetch_assoc($res); $countBuku = $r['cnt']; }
}

// coba cari nama tabel peminjam
$tablePeminjam = find_table($koneksi, array('peminjam','tbl_peminjam','tb_peminjam'));
if ($tablePeminjam) {
    $res = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM `".mysqli_real_escape_string($koneksi,$tablePeminjam)."`");
    if ($res) { $r = mysqli_fetch_assoc($res); $countPeminjam = $r['cnt']; }

    $res = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM `".mysqli_real_escape_string($koneksi,$tablePeminjam)."` WHERE status='pending'");
    if ($res) { $r = mysqli_fetch_assoc($res); $countPending = $r['cnt']; }

    $res = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM `".mysqli_real_escape_string($koneksi,$tablePeminjam)."` WHERE DATE(tanggal_kembali)=CURDATE()");
    if ($res) { $r = mysqli_fetch_assoc($res); $countReturnToday = $r['cnt']; }
}

// Ambil peminjaman terbaru (untuk tabel)
$recentPeminjaman = array();
if ($tablePeminjam) {
    $res = mysqli_query($koneksi, "SELECT nama_peminjam, judul_buku, tanggal_pinjam, status FROM `".mysqli_real_escape_string($koneksi,$tablePeminjam)."` ORDER BY tanggal_pinjam DESC LIMIT 7");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $recentPeminjaman[] = $row;
        }
    }
}

// Ambil daftar buku terbaru (maks 8) untuk ditampilkan di dashboard
$booksList = array();
if ($tableBuku) {
    $tbl = mysqli_real_escape_string($koneksi, $tableBuku);
    $res = mysqli_query($koneksi, "SELECT id_buku, judul_buku, jumlah_buku, tahun_terbit FROM `".$tbl."` ORDER BY id_buku DESC LIMIT 8");
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
            background-color: #f4f6f9;
        }

        /* Sidebar utama (sedikit lebih cerah) */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #0b0b0b 0%, #121212 100%); /* hitam yang sedikit lebih cerah */
            color: #fff;
            padding-top: 1rem;
        }

        .sidebar .profile {
            display: flex; align-items: center; gap: .75rem;
            padding: .6rem; border-radius: 8px; margin-bottom: 1rem;
            background: rgba(255,255,255,0.02);
        }

        .sidebar .avatar {
            width:44px; height:44px; border-radius:50%; background:rgba(255,255,255,0.06); color:#fff; font-weight:700; display:flex; align-items:center; justify-content:center; font-size:1.05rem;
            box-shadow:0 2px 6px rgba(0,0,0,0.18); border:2px solid rgba(255,255,255,0.9); transition: all .15s ease;
        }
        .sidebar .avatar:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.25); }
        .sidebar .avatar.avatar-active { background: #0d6efd; border-color: #0d6efd; color: #fff; box-shadow: 0 8px 26px rgba(13,110,253,0.28); }
        .sidebar .role { color: rgba(255,255,255,0.78); font-size:0.82rem; }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.92); border-radius:6px; padding: .5rem .75rem; display:flex; align-items:center; gap:.6rem;
        }
        .sidebar .nav-link i { width:22px; text-align:center; }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.08); color: #fff;
            text-decoration: none;
        }
        .sidebar .badge {
            background: rgba(255,255,255,0.12); color:#fff; font-weight:600; font-size:0.8rem;
        }
        .book-card .card { border-radius: 8px; overflow: hidden; }
        .book-cover {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
            background-color: #f1f3f5;
        }
        .book-card .card-body { padding: .75rem; }
        .book-card .card-title { font-size: 0.98rem; height: 48px; overflow: hidden; }
        @media (max-width: 576px) {
            .book-cover {
                height: 140px; 
            }
            .sidebar { padding: .6rem; }
            .sidebar .profile { padding: .4rem; }
        }
        .offcanvas-body .nav { padding-left:0; padding-right:0; }
        .offcanvas .nav-link { color:#fff; }
        .offcanvas .nav-link.active { background-color: rgba(255,255,255,0.08); color:#fff; }
        .chart-container { width:100%; height:240px; }
    </style> 
</head>

<body>

    <nav class="navbar navbar-dark bg-primary navbar-expand-lg">
        <div class="container-fluid">
            <button class="btn btn-primary d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                <i class="bi bi-list"></i>
            </button>

            <a class="navbar-brand fw-bold d-flex align-items-center" href="dashboard.php">
                <i class="bi bi-book-half me-2"></i> <span>PerpusKu</span>
            </a>

            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#topbar" aria-controls="topbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

                <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item me-3 d-none d-lg-block">
                        <a class="nav-link text-white" href="#" title="Notifikasi"><i class="bi bi-bell"></i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 me-2"></i>
                            <div class="d-none d-lg-block text-start">
                                <div style="line-height:1; font-size:0.95rem;">Hai, <strong><?= htmlspecialchars($tampil['nama']) ?></strong></div>
                                <small class="text-white-50" style="font-size:0.75rem;"> <?= htmlspecialchars($tampil['akses']) ?></small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="?page=profile"><i class="bi bi-person me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="?page=profile&edit=1"><i class="bi bi-gear me-2"></i> Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">PerpusKu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="p-3">
                <?php $initial = !empty($tampil['nama']) ? strtoupper(mb_substr($tampil['nama'],0,1)) : 'U'; ?>
                <div class="profile mb-3">
                    <div class="avatar"><?= $initial ?></div>
                    <div>
                        <div style="font-weight:700;color:#fff;"><?= htmlspecialchars($tampil['nama']) ?></div>
                        <small class="role"><?= htmlspecialchars($tampil['akses']) ?></small>
                    </div>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link <?php if ($page == 'awal') echo 'active'; ?>" href="?page=awal"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a class="nav-link <?php if ($page == 'profile') echo 'active'; ?>" href="?page=profile"><i class="bi bi-person me-2"></i> Profil Saya</a>

                    <?php if ($tampil['akses'] === 'admin') { ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php if (in_array($page, ['buku','kategori','penerbit'])) echo 'active'; ?>" 
           href="#" id="masterDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-gear me-2"></i> Master Data
        </a>
        <ul class="dropdown-menu" aria-labelledby="masterDropdown">
            <li><a class="dropdown-item <?php if ($page == 'buku') echo 'active'; ?>" href="?page=buku"><i class="bi bi-book me-2"></i> Data Buku</a></li>
            <li><a class="dropdown-item <?php if ($page == 'kategori') echo 'active'; ?>" href="?page=kategori"><i class="bi bi-tags me-2"></i> Kategori</a></li>
            <li><a class="dropdown-item <?php if ($page == 'penerbit') echo 'active'; ?>" href="?page=penerbit"><i class="bi bi-building me-2"></i> Penerbit</a></li>
        </ul>
    </li>
<?php } ?>

                    <a class="nav-link d-flex align-items-center <?php if ($page == 'peminjam') echo 'active'; ?>" href="?page=peminjam"><i class="bi bi-people me-2"></i> Data Peminjam <?php if (!empty($countPending)) { echo '<span class="badge ms-auto">'.intval($countPending).'</span>'; } ?></a>
                    <a class="nav-link <?php if ($page == 'history') echo 'active'; ?>" href="?page=history"><i class="bi bi-clock-history me-2"></i> History</a>

                    <div class="mt-3">
                        <a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
                    </div>
                </nav>
                <hr class="border-secondary">
                <div class="d-grid gap-2">
                    <a href="?page=tambah_buku" class="btn btn-sm btn-primary d-none d-md-inline"> <i class="bi bi-plus-circle me-1"></i> Tambah Buku</a>
                    <a href="?page=tambah_peminjam" class="btn btn-sm btn-outline-primary d-none d-md-inline"> <i class="bi bi-person-plus me-1"></i> Tambah Peminjam</a>


                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">

            <aside class="col-md-2 sidebar d-none d-md-block">
                <div class="p-3">
                    <?php $initial = !empty($tampil['nama']) ? strtoupper(mb_substr($tampil['nama'],0,1)) : 'U'; ?>
                    <div class="profile">
                        <div class="avatar"><?= $initial ?></div>
                        <div>
                            <div style="font-weight:700;color:#fff;"><?= htmlspecialchars($tampil['nama']) ?></div>
                            <small class="role"><?= htmlspecialchars($tampil['akses']) ?></small>
                        </div>
                    </div>

                    <nav class="nav flex-column">
                        <a class="nav-link <?php if ($page == 'awal') echo 'active'; ?>" href="?page=awal"><i class="bi bi-speedometer2"></i> Dashboard</a>
                        <a class="nav-link <?php if ($page == 'profile') echo 'active'; ?>" href="?page=profile"><i class="bi bi-person"></i> Profil Saya</a>

                         <li class="nav-item">
                    <a class="nav-link <?php if ($page == 'view_tampil_buku') echo 'active'; ?>" href="?page=view_tampil_buku">
                        <i class="bi bi-book"></i> Buku
                    </a>
                </li>
        <?php if ($tampil['akses'] === 'admin') { ?>
    <li class="nav-item">
        <a class="nav-link d-flex justify-content-between align-items-center" 
           data-bs-toggle="collapse" href="#masterDataMenu" role="button" aria-expanded="false" aria-controls="masterDataMenu">
            <span><i class="bi bi-gear"></i> Data Buku</span>
            <i class="bi bi-chevron-down"></i>
        </a>
        
        <div class="collapse" id="masterDataMenu">
            <ul class="nav flex-column ms-3">
                <li class="nav-item">
                    <a class="nav-link <?php if ($page == 'buku') echo 'active'; ?>" href="?page=buku">
                        <i class="bi bi-book"></i> Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($page == 'kategori') echo 'active'; ?>" href="?page=kategori">
                        <i class="bi bi-tags"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($page == 'penerbit') echo 'active'; ?>" href="?page=penerbit">
                        <i class="bi bi-building"></i> Penerbit
                    </a>
                </li>
            </ul>
        </div>
    </li>
<?php } ?>

                        <a class="nav-link d-flex align-items-center <?php if ($page == 'peminjam') echo 'active'; ?>" href="?page=peminjam"><i class="bi bi-people"></i> Data Peminjam <?php if (!empty($countPending)) { echo '<span class="badge ms-auto">'.intval($countPending).'</span>'; } ?></a>
                        <a class="nav-link <?php if ($page == 'history') echo 'active'; ?>" href="?page=history"><i class="bi bi-clock-history"></i> History</a>

                    </nav>
                </div>
            </aside>

            <main class="col-md-10 p-4">
                <?php
                if ($page == "awal") {
                    ?>
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h4 class="mb-0">Dashboard</h4>
                                <small class="text-muted">Ringkasan singkat sistem perpustakaan</small>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="me-3 bg-primary text-white rounded p-3">
                                            <i class="bi bi-book fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Jumlah Buku</h6>
                                            <h4 class="fw-bold mb-0"><?= number_format($countBuku) ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <div class="card shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="me-3 bg-info text-white rounded p-3">
                                            <i class="bi bi-people fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Peminjam</h6>
                                            <h4 class="fw-bold mb-0"><?= number_format($countPeminjam) ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <div class="card shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="me-3 bg-warning text-white rounded p-3">
                                            <i class="bi bi-clock-history fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Menunggu Persetujuan</h6>
                                            <h4 class="fw-bold mb-0"><?= number_format($countPending) ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-lg-3">
                                <div class="card shadow-sm">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="me-3 bg-success text-white rounded p-3">
                                            <i class="bi bi-arrow-counterclockwise fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Pengembalian Hari Ini</h6>
                                            <h4 class="fw-bold mb-0"><?= number_format($countReturnToday) ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-8">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="card-title mb-0">Ringkasan Aktivitas</h5>
                                            <small class="text-muted">Terakhir 7 hari</small>
                                        </div>
                                        <div class="chart-container" style="min-height:220px"><canvas id="activityChart" style="width:100%; height:100%;"></canvas></div>
                                    </div>
                                </div>
                            </div>

                    
                               

                        <!-- Buku kami -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h3 class="mb-3">Buku Kami</h3>
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                                    <?php if (!empty($booksList)) { foreach ($booksList as $b) { ?>
                                        <div class="col book-card">
                                            <div class="card h-100 shadow-sm">
                                                <?php $cover = (isset($b['gambar_buku']) && !empty($b['gambar_buku'])) ? htmlspecialchars($b['gambar_buku']) : 'https://via.placeholder.com/300x180?text=No+Cover'; ?>
                                                <img src="<?= $cover ?>" alt="cover" class="book-cover">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-1"><?= htmlspecialchars($b['judul_buku']) ?></h6>
                                                    <div class="text-muted small">Jumlah: <?= htmlspecialchars($b['jumlah_buku'] ?? '—') ?> • <?= htmlspecialchars($b['tahun_terbit'] ?? '') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } else { ?>
                                        <div class="col-12"><div class="text-muted">Tidak ada data buku untuk ditampilkan.</div></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php
                } elseif ($page == "profile") {
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
                } elseif ($page == "view_tampil_buku") {
                    include "buku/view_Tampil_buku.php";

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
                }
                ?>
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
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 2 } } } }
            });
        }
    </script>
    <script>
        document.querySelectorAll('.avatar').forEach(function(el){
            el.addEventListener('click', function(){
                this.classList.toggle('avatar-active');
                var pressed = this.getAttribute('aria-pressed') === 'true';
                this.setAttribute('aria-pressed', (!pressed).toString());
            });
            el.addEventListener('keydown', function(e){
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>