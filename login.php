<?php
include 'inc/conect.php';
session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
if (isset($_POST['Login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT * FROM tbl_users WHERE username = '$username' AND password = '$password'";
$hasil = mysqli_query($koneksi, $query);
$cek = mysqli_num_rows($hasil);
$tampil = mysqli_fetch_array($hasil);
        if ($cek > 0) {


            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid username or password.";
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
  <main class="main">
    <div class="card">
      <div class="card-nav">
        <span class="active-bar"></span>
        <ul>
          <li>
            <button
              type="button"
              class="signin active"
              onclick="selectView('signin')"
            >
              <i class="ai-person-check"></i>
              <span>Sign In</span>
            </button>
          </li>
          <li>
            <button type="button" class="signup" onclick="selectView('signup')">
              <i class="ai-person-add"></i>
              <span>Sign Up</span>
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
          <div class="card-hero-content signup">
            <h2>Sign Up Now.</h2>
            <h3>Join the crowd and get started.</h3>
          </div>
        </div>
      </div>

<div class="card-form">
                <div class="forms">
                    <form method="post" id="signin" class="active">
                        <p>Don't have an account? <a href="#" onclick="selectView('signup'); return false;">Sign Up</a>.</p>
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
                    <form id="signup">
                        <p>Already have an account? <a href="#" onclick="selectView('signin'); return false;">Sign In</a>.</p>
                        <label> Username </label>
                        <div class="control">
                            <input type="text" placeholder="NOAW" />
                            <i class="ai-person"></i>
                        </div>
                        <label> Email </label>
                        <div class="control">
                            <input type="email" placeholder="NOAW@gmail.com" />
                            <i class="ai-envelope"></i>
                        </div>
                        <label> Password </label>
                        <div class="control">
                            <input type="password" placeholder="●●●●●●●●●●●●●" />
                            <i class="ai-lock-on"></i>
                        </div>
                        <button>SIGN UP</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>