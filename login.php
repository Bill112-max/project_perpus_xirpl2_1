<?php
include 'inc/conect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['id']) && !empty($_SESSION['akses'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if (isset($_POST['Login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);


    $query = "SELECT * FROM tbl_users WHERE username='$username' AND password='$password'";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil && mysqli_num_rows($hasil) > 0) {
        $data = mysqli_fetch_assoc($hasil);

        $_SESSION['id']       = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['akses']    = $data['akses']; 

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGN IN</title>
    <link rel="stylesheet" href="css/style-login.css" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
    <script src="https://unpkg.com/akar-icons-fonts"></script>
    <script src="js/login.js" defer></script>
</head>
<body>
  <main class="main">
    <div class="card">
      <div class="card-nav">
        <span class="active-bar"></span>
        <ul>
          <li>
            <button type="button" class="signin active" onclick="selectView('signin')">
              <i class="ai-person-check"></i>
              <span>Sign In</span>
            </button>
          </li>
        </ul>
      </div>
      <div class="card-hero">
        <div class="card-hero-inner">
          <div class="card-hero-content signin">
            <h2>Welcome Back.</h2>
            <h3>Please enter your credentials.</h3>
          </div>
        </div>
      </div>

      <div class="card-form">
        <div class="forms">
          <form method="post" id="signin" class="active">
            <p>Don't have an account? <a href="#" onclick="selectView('signup'); return false;">Sign Up</a>.</p>
            <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
            <label> Username </label>
            <div class="control">
              <input type="text" id="username" name="username" required><br><br>
              <i class="ai-envelope"></i>
            </div>
            <label> Password </label>
            <div class="control">
              <input type="password" id="password" name="password" required><br><br>
              <i class="ai-lock-on"></i>
            </div>
            <button type="submit" name="Login">SIGN IN</button>
          </form>

         