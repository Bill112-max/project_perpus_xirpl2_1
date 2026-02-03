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

        .sidebar {
            min-height: 100vh;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark bg-primary navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">PerpusKu</a>
            <div class="d-flex text-white align-items-center">
                <i class="bi bi-person-circle me-2"></i> Hai, <?= $tampil['nama'] ?>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <aside class="col-md-2 bg-dark text-white sidebar p-3">
                <ul class="nav nav-pills flex-column gap-2">
                    <li class="nav-item">
                        <a href="?page=awal" class="nav-link text-white <?php if ($page == 'awal') echo 'active bg-primary'; ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=profile" class="nav-link text-white <?php if ($page == 'profile') echo 'active bg-primary'; ?>">
                            <i class="bi bi-person"></i> Profil Saya
                        </a>
                    </li>
                    <?php if ($tampil['akses'] === 'admin') { ?>
                        <li class="nav-item">
                            <a href="?page=buku" class="nav-link text-white <?php if ($page == 'buku') echo 'active bg-primary'; ?>">
                                <i class="bi bi-book"></i> Data buku
                            </a>
                        </li>
                    <?php } ?>

                    <li class="nav-item">
                        <a href="?page=peminjam" class="nav-link text-white <?php if ($page == 'peminjam') echo 'active bg-primary'; ?>">
                            <i class="bi bi-people"></i> Data Peminjam
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=history" class="nav-link text-white <?php if ($page == 'history') echo 'active bg-primary'; ?>">
                            <i class="bi bi-file-earmark-text card-icon"></i> History
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a href="logout.php" class="nav-link text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </aside>

            <main class="col-md-10 p-4">
                <?php
                if ($page == "awal") {
                    include "awal.php";
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>