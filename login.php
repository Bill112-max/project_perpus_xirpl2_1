<?php
include 'inc/conect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['id']) && !empty($_SESSION['akses'])) {
    header("Location: dashboard.php");
    exit();
}

$error        = '';
$error_signup = '';
$success_signup = '';

// ── SIGN IN ──────────────────────────────────────────────
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

        header("Location: dashboard.php?page=awal");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}

// ── SIGN UP ──────────────────────────────────────────────
if (isset($_POST['Register'])) {
    $name     = mysqli_real_escape_string($koneksi, trim($_POST['name']));
    $username = mysqli_real_escape_string($koneksi, trim($_POST['reg_username']));
    $email    = mysqli_real_escape_string($koneksi, trim($_POST['email']));
    $telepon  = mysqli_real_escape_string($koneksi, trim($_POST['telepon']));
    $password = mysqli_real_escape_string($koneksi, $_POST['reg_password']);
    $akses    = 'anggota';

    // Cek username sudah ada
    $cek = mysqli_query($koneksi, "SELECT id FROM tbl_users WHERE username='$username'");
    if ($cek && mysqli_num_rows($cek) > 0) {
        $error_signup = "Username sudah digunakan, pilih username lain.";
    } elseif (empty($name) || empty($username) || empty($email) || empty($telepon) || empty($password)) {
        $error_signup = "Semua field wajib diisi.";
    } else {
        $insert = mysqli_query($koneksi,
            "INSERT INTO tbl_users (name, username, email, telepon, password, akses)
             VALUES ('$name','$username','$email','$telepon','$password','$akses')"
        );
        if ($insert) {
            $success_signup = "Akun berhasil dibuat! Silakan sign in.";
        } else {
            $error_signup = "Gagal membuat akun: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f2f3fe;
            color: #4b5679;
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-family: "Poppins", sans-serif;
        }

        :root {
            --gradient: linear-gradient(90deg, #0a740a, #0d960d);
        }

        .card {
            position: relative;
            overflow: hidden;
            width: 660px;
            height: 520px;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 12px 80px rgba(0, 0, 0, 0.12);
        }

        .card-bg {
            position: absolute;
            z-index: 2;
            top: 0;
            left: 0;
            bottom: 0;
            width: 50%;
            background: var(--gradient);
            translate: 0 0;
            transition: 0.5s ease-in-out;
        }

        .card-bg.signin {
            translate: 100% 0;
        }

        .hero,
        .form {
            position: absolute;
            width: 50%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transition: 0.5s ease-in-out;
        }

        .hero.active,
        .form.active {
            opacity: 1;
            visibility: visible;
        }

        .form.signup {
            left: 50%;
        }

        .hero.signin {
            left: 50%;
            translate: 25% 0;
        }

        .hero.signin.active {
            translate: 0;
        }

        .hero.signup {
            translate: -25% 0;
        }

        .hero.signup.active {
            translate: 0;
        }

        .hero {
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            width: 50%;
            color: #f9f9f9;
            text-align: center;
            padding: 0 24px;
        }

        h2 {
            margin: 0;
            font-weight: 500;
        }

        .form.signin {
            translate: 50% 0;
        }

        .form.signin.active {
            translate: 0;
        }

        .form.signup {
            translate: -50% 0;
        }

        .form.signup.active {
            translate: 0;
        }

        .hero p {
            margin: 0 0 6px;
            opacity: 0.75;
            line-height: 1.45;
        }

        .hero button {
            padding: 12px 40px;
            border-radius: 32px;
            letter-spacing: 1px;
            font-family: inherit;
            color: inherit;
            border: 1px solid #f9f9f9;
            background: transparent;
            transition: 0.3s;
            cursor: pointer;
        }

        .hero button:hover {
            color: #0a740a;
            background: #f9f9f9;
        }

        .form {
            background: #ffffff;
            z-index: 1;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding: 24px 32px;
        }

        .form h2 {
            font-size: 22px;
            text-align: center;
        }

        .sso {
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .form form > a {
            font-size: 14px;
            margin-top: 10px;
            opacity: 0.5;
            cursor: pointer;
        }

        .sso a {
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid #c2e0c2;
            font-size: 16px;
            color: #0a740a;
            cursor: pointer;
        }

        .form form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            width: 100%;
        }

        .form p {
            margin: 0 0 -8px;
            text-align: center;
            opacity: 0.5;
            font-size: 12px;
        }

        .form input {
            font-family: inherit;
            border-radius: 10px;
            border: 0;
            background: #eef0f7;
            padding: 14px 12px;
            color: inherit;
            width: 100%;
        }

        .form input::placeholder {
            color: #a0a2b6;
        }

        .form button[type="submit"] {
            border: 0;
            padding: 14px 0;
            border-radius: 32px;
            font-family: inherit;
            color: #f9f9f9;
            width: 160px;
            margin-top: 10px;
            background: var(--gradient);
            box-shadow: 0 10px 40px rgb(10 116 10 / 44%);
            cursor: pointer;
            letter-spacing: 1px;
            font-size: 13px;
        }

        .error-msg {
            color: #e84545;
            font-size: 12px;
            margin: 0;
            opacity: 1 !important;
            background: #fdeaea;
            padding: 8px 12px;
            border-radius: 8px;
            width: 100%;
            text-align: center;
        }

        .success-msg {
            color: #1a8a4a;
            font-size: 12px;
            margin: 0;
            opacity: 1 !important;
            background: #e6f9ee;
            padding: 8px 12px;
            border-radius: 8px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-bg"></div>

        <!-- Hero: Sign Up side (shown when on signup view) -->
        <div class="hero signup active">
            <h2>Welcome Back!</h2>
            <p>Sign in to review your latest profit from investments.</p>
            <button type="button" onclick="toggleView()">SIGN IN</button>
        </div>

        <!-- Form: Sign Up -->
        <div class="form signup active" id="signupForm">
            <h2>Create Account</h2>
            <div class="sso">
                <a class="fa-brands fa-facebook"></a>
                <a class="fa-brands fa-twitter"></a>
                <a class="fa-brands fa-linkedin"></a>
            </div>
            <p>Or use your email address</p>
            <form method="post">
                <?php if (!empty($error_signup)) { ?>
                    <p class="error-msg"><?php echo $error_signup; ?></p>
                <?php } ?>
                <?php if (!empty($success_signup)) { ?>
                    <p class="success-msg"><?php echo $success_signup; ?></p>
                <?php } ?>
                <input type="text" name="name" placeholder="Name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required />
                <input type="text" name="reg_username" placeholder="Username" value="<?php echo isset($_POST['reg_username']) ? htmlspecialchars($_POST['reg_username']) : ''; ?>" required />
                <input type="email" name="email" placeholder="Email address" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required />
                <input type="tel" name="telepon" placeholder="Phone number" value="<?php echo isset($_POST['telepon']) ? htmlspecialchars($_POST['telepon']) : ''; ?>" required />
                <input type="password" name="reg_password" placeholder="Password" required />
                <button type="submit" name="Register">SIGN UP</button>
            </form>
        </div>

        <!-- Hero: Sign In side (shown when on signin view) -->
        <div class="hero signin">
            <h2>Hey There!</h2>
            <p>Begin your journey using this software, and start earning now.</p>
            <button type="button" onclick="toggleView()">SIGN UP</button>
        </div>

        <!-- Form: Sign In (with PHP) -->
        <div class="form signin <?php echo (!empty($error)) ? 'active' : ''; ?>" id="signinForm">
            <h2>Sign In</h2>
            <div class="sso">
                <a class="fa-brands fa-facebook"></a>
                <a class="fa-brands fa-twitter"></a>
                <a class="fa-brands fa-linkedin"></a>
            </div>
            <p>Or use your email address</p>
            <form method="post">
                <?php if (!empty($error)) { ?>
                    <p class="error-msg"><?php echo $error; ?></p>
                <?php } ?>
                <input type="text" id="username" name="username" placeholder="Username" required />
                <input type="password" id="password" name="password" placeholder="Password" required />
                <a>Forgot password?</a>
                <button type="submit" name="Login">SIGN IN</button>
            </form>
        </div>
    </div>

    <script>
        const signinHero = document.querySelector(".hero.signin");
        const signinForm = document.querySelector(".form.signin");
        const signupHero = document.querySelector(".hero.signup");
        const signupForm = document.querySelector(".form.signup");
        const cardBg = document.querySelector(".card-bg");

        const toggleView = () => {
            const signinActive = signinHero.classList.contains("active");
            cardBg.classList.toggle("signin", !signinActive);
            [signinHero, signinForm].forEach((el) => el.classList.toggle("active"));
            [signupHero, signupForm].forEach((el) => el.classList.toggle("active"));
        };

        // Auto-switch to signin view if there's a login PHP error
        <?php if (!empty($error)) { ?>
            cardBg.classList.add("signin");
            signinHero.classList.add("active");
            signinForm.classList.add("active");
            signupHero.classList.remove("active");
            signupForm.classList.remove("active");
        <?php } ?>

        // Auto-switch to signup view if there's a signup error/success
        <?php if (!empty($error_signup) || !empty($success_signup)) { ?>
            cardBg.classList.remove("signin");
            signupHero.classList.add("active");
            signupForm.classList.add("active");
            signinHero.classList.remove("active");
            signinForm.classList.remove("active");
        <?php } ?>
    </script>
</body>
</html>