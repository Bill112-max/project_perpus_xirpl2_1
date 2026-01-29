<?php
include "inc/conect.php"; 

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM tbl_buku");

?>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h6 class="mb-0">Macam-Macam Buku</h6>
    </div>
    <div class="card-body">
        <?php 
        $no = 1;
        while($buku = mysqli_fetch_assoc($query)): 
        ?>
        <div class="row mb-4">
            <div class="col-md-4">
                <!-- Slot Gambar -->
                <div style="border:1px dashed #aaa; height:200px; text-align:center; line-height:200px;">
                    <?php 
           
                    if(!empty($buku['gambar'])) {
                        echo "<img src='uploads/".$buku['gambar']."' alt='".$buku['judul']."' style='max-height:200px;'>";
                    } else {
                        echo "[Slot Gambar Buku]";
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-8">
        
                <h5><?= $no++ . ". " . $buku['judul_buku']; ?></h5>
                <p><?= $buku['sinopsis']; ?></p>
                <ul>
                    <li>Jumlah Halaman: <?= $buku['jumlah_halaman']; ?></li>
                    <li>Jumlah Buku: <?= $buku['jumlah_buku']; ?></li>
                    <li>Bahasa: indonesia </li>
                </ul>
                <span class="badge bg-info">Best Seller</span>
            </div>
        </div>
        <hr>
        <?php endwhile; ?>
    </div>
</div>