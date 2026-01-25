<?php
session_start();
include "inc/koneksi.php";
//jika sudah login
if(isset($_SESSION['username']))
{
    header("Location:dashboard-bootstrap.php");
    exit;
}
//proses login
if(isset($_POST['login']))
{
    $username=$_POST['username'];
    $password=$_POST['password'];
    //memanggil query dari database
    $query="select * from users where username='$username'and password='$password'";
    //echo"$query";
    //hasil query
    $hasil=mysqli_query($koneksi,$query);
    //cek apakah ada data 
    $cek=mysqli_num_rows($hasil);
    //echo $cek;
    //jika data yang diambil lebih dari nol maka
    if($cek>0)
    {
        $_SESSION['username']=$username;
        header("Location:dashboard-bootstrap.php");
        exit;
    }
    else{
        echo"MAAF LOGIN GAGAL<br>";
        //echo"SELAMAT DATANG $username"; 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0d6efd, #6f42c1);
            min-height: 100vh;
        }
        .login-card {
            border-radius: 15px;
            overflow: hidden;
        }
        .login-card .card-body {
            padding: 2.5rem;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-login {
            border-radius: 30px;
            padding: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow login-card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">Login Admin</h4>
                    <p class="text-muted small">Silakan masuk untuk melanjutkan</p>
                </div>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label small" for="remember">
                                Ingat saya
                            </label>
                        </div>
                        <a href="#" class="small text-decoration-none">Lupa password?</a>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary w-100 btn-login">
                        Login
                    </button>
                </form>

                <div class="text-center mt-4 small text-muted">
                    © 2026 Aplikasi Sekolah
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>