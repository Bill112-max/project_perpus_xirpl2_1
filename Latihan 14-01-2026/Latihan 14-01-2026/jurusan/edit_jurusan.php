<?php
$kode = $_GET['id_jurusan'];
//echo"$nis";
//query untuk mengambil data siswa berdasarkan nis
$query = "select * from tbl_jurusan where kode_jurusan='$kode'";
//untuk menjalankan query
$result = mysqli_query($koneksi, $query);
//Perintah untuk mengambil data
$row = mysqli_fetch_array($result);

//jika tombil edit di tekan
if (isset($_POST['edit_jur'])) {
    $kode = $_POST['kode_jurusan'];
    $nama = $_POST['nama_jurusan'];
    $singkatan = $_POST['singkatan_jurusan'];

    //perintah query untuk mengupdate data
    $query = "update tbl_jurusan set nama_jurusan='$nama',singkatan='$singkatan' where kode_jurusan='$kode'";
    //menjalankan query
    $result = mysqli_query($koneksi, $query);
    if ($result) {
        header("Location: ?page=jurusan&status=success");
    } else {
        header("Location: ?page=jruusan&status=error");
    }
}
?>

<h3 class="mb-4">Edit Jurusan</h3>
<div class="container mt-5 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Edit Data Jurusan</h5>
        </div>

        <div class="card-body">
            <form method="post">

                <!-- Text -->
                <div class="mb-3">
                    <label class="form-label">Kode Jurusan</label>
                    <input type="text" class="form-control" placeholder="Masukkan nama lengkap" required value="<?php echo $row['kode_jurusan'] ?> " readonly name="kode_jurusan">
                </div>
                <!-- Nama Jurusan -->
                <div class="mb-3">
                    <label class="form-label">Nama Jurusan</label>
                    <input type="text" class="form-control" placeholder="Masukkan nama lengkap" required value="<?php echo $row['nama_jurusan']?>" name="nama_jurusan">
                </div>
                <!-- Singkatan -->
                <div class="mb-3">
                    <label class="form-label">Singkatan Jurusan</label>
                    <input type="text" class="form-control" placeholder="Masukkan nama lengkap" required value="<?php echo $row['singkatan']?>" name="singkatan_jurusan">
                </div>

                <!-- Tombol submit -->
                <div class="text-end">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" name="edit_jur" class="btn btn-primary">Edit</button>
                </div>

            </form>
        </div>
    </div>
</div>