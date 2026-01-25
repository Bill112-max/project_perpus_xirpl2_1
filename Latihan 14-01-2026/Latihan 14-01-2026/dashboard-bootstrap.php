<?php
include "inc/koneksi.php";
//memulai session
session_start();
//cek login
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}
$user = $_SESSION['username'];
$query = "select * from users where username='$user'";
//echo"$query";
$hasil = mysqli_query($koneksi, $query);
$tampil = mysqli_fetch_array($hasil);
$page = $_GET['page'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            min-height: 100vh;
        }

        .card-icon {
            font-size: 40px;
            opacity: 0.7;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">APP SEKOLAH</a>
            <div class="dropdown ms-auto">
                <a class="text-white dropdown-toggle text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> Admin
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="?page=profile">Profil</a></li>
                    <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <aside class="col-md-2 bg-dark text-white sidebar p-3">
                <ul class="nav nav-pills flex-column gap-2">
                    <li class="nav-item">
                        <a href="?page=awal" class="nav-link text-white <?php if ($page == 'awal') {
                                                                            echo "active";
                                                                        } ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=jurusan" class="nav-link text-white <?php if ($page == 'jurusan') {
                                                                                echo " active";
                                                                            } elseif ($page == 'edit_jur') {
                                                                                echo " active";
                                                                            } ?>">
                            <i class="bi bi-people"></i> Data Jurusan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?page=kelas" class="nav-link text-white ">
                            <i class="bi bi-people"></i> Data Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">
                            <i class="bi bi-folder"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">
                            <i class="bi bi-file-earmark-text"></i> Laporan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">
                            <i class="bi bi-gear"></i> Pengaturan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link text-white">
                            <i class="bi bi-gear"></i> logout
                        </a>
                    </li>
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="col-md-10 p-4">
                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page']; {
                        if ($page == "profile") {
                            include "profile.php";
                        }
                        if ($page == "jurusan") {
                            include "jurusan/view_jurusan.php";
                        }
                        if ($page == "edit_jur") {
                            include "jurusan/edit_jurusan.php";
                        }
                        if ($page == "hapus_jur") {
                            include "jurusan/hapus_jurusan.php";
                        }
                        if ($page == "kelas") {
                            include "kelas/view_kelas.php";
                        }
                        if ($page == "awal") {
                            include "awal.php";
                        }
                    }
                } else {
                    include "awal.php";
                }
                ?>

            </main>
        </div>
    </div>

</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--success edit-->
<?php if ($status == 'success') { ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil diubah',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
    </script>
    <!--error edit-->
<?php } elseif ($status == 'error') { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Data gagal diperbarui'
        });
    </script>
<?php } ?>
<!--success simpan-->
<?php if ($status == 'success_simpan') { ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil tersimpan',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
    </script>
    <!--error simpan-->

<?php } elseif ($status == 'error_simpan') { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Data gagal tersimpan'
        });
    </script>
<?php } ?>
<!--menghapus ada pilihan ya tidak -->
<script>
    document.querySelectorAll('.btn-hapus').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // hentikan link

            const url = this.getAttribute('href');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
<!--hapus-->
<?php if (@$_GET['status'] == 'deleted') { ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data berhasil dihapus',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
<?php } ?>