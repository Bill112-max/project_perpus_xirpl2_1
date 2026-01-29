<?php
include "inc/conect.php";

// cek login
if (!isset($_SESSION['username'])) {
    header("location:../login.php");
    exit;
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center">
                    <i class="bi bi-person-circle fs-2"></i>
                    <h5 class="mt-2 mb-0">Profil Pengguna</h5>
                </div>

                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Nama</span>
                            <span><?= $tampil['nama'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Email</span>
                            <span><?= $tampil['email'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">No. Telepon</span>
                            <span><?= $tampil['no_tlp'] ?></span>
                        </li>
                    </ul>
                </div>

                <div class="card-footer text-center bg-light">
                    <span class="text-muted small">
                        <i class="bi bi-shield-lock"></i> Data akun tersimpan aman
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>
