
<?php
include "inc/conect.php";
//memulai session

//cek login
if (!isset($_SESSION['username'])) {
    header("location:../login.php");
    exit;
}
?>

<!-- CONTENT -->
<div class="content">
    <h2>Profile</h2>
    <div class="card-container">
        <div class="card-2">
            <h3>NAMA: <?= $tampil['nama'] ?></h3>
            <h3>EMAIL: <?= $tampil['email'] ?></h3>
            <h3>NO TLP: <?= $tampil['no_tlp'] ?></h3>   
        </div>
    </div>
</div>

</body>
</html>