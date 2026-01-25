<?php
//cek session
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}
$data = mysqli_query($koneksi, "SELECT * FROM tbl_jurusan");

$status = $_GET['status'] ?? '';

if (isset($_POST['add_jur'])) {
    $kode_juru = $_POST['kode_jurusan'];
    $nama_jur = $_POST['nama_jurusan'];
    $singkatan_jur = $_POST['singkatan'];


    $sql = "INSERT INTO tbl_jurusan (kode_jurusan, nama_jurusan, singkatan)
        VALUES ('$kode_juru', '$nama_jur', '$singkatan_jur')";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: ?page=jurusan&status=success_simpan");
    } else {
        header("Location: ?page=jruusan&status=error_simpan");
    }
}

?>
<!--form tambah jurusan-->
<h3 class="mb-4">Tambah Data Jurusan</h3>
<div class="container mt-5 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Tambah Data Jurusan</h5>
        </div>

        <div class="card-body">
            <form method="post">

                <!-- Text -->
                <div class="mb-3">
                    <label class="form-label">Kode Jurusan</label>
                    <input type="text" class="form-control" placeholder="Masukkan Kode Jurusan" required name="kode_jurusan">
                </div>
                <!-- Nama Jurusan -->
                <div class="mb-3">
                    <label class="form-label">Nama Jurusan</label>
                    <input type="text" class="form-control" placeholder="Masukkan nama lengkap" name="nama_jurusan">
                </div>
                <!-- Singkatan -->
                <div class="mb-3">
                    <label class="form-label">Singkatan Jurusan</label>
                    <input type="text" class="form-control" placeholder="Masukkan Singkatan Jurusan"  name="singkatan">
                </div>

                <!-- Tombol submit -->
                <div class="text-end">
                    <button type="submit" name="add_jur" class="btn btn-primary">Simpan</button>

                    <button type="reset" class="btn btn-secondary">Reset</button>
                    
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Tabel -->
<h3 class="mb-4">Data Jurusan</h3>
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h6 class="mb-0">Data Jurusan</h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Kode Jurusan</th>
                    <th>Nama Jurusan</th>
                    <th>Singkatan Jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($data)) { ?>
                    <tr>
                        <td><?= $row['kode_jurusan']; ?></td>
                        <td><?= $row['nama_jurusan']; ?></td>
                        <td><?= $row['singkatan']; ?></td>
                        <td> <a href="?page=edit_jur&id_jurusan=<?php echo $row['kode_jurusan'] ?> " class="btn btn-sm btn-warning">Edit</a> <a href="?page=hapus_jur&id_jurusan=<?php echo $row['kode_jurusan'] ?>" class="btn btn-danger btn-sm btn-hapus"> Hapus</a></td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>