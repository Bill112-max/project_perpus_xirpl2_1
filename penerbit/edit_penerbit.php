<?php
include __DIR__ . '/../inc/conect.php';
$id_penerbit = $_GET['id_penerbit']; 
$result = mysqli_query($koneksi, "SELECT * FROM tbl_penerbit WHERE id_penerbit='$id_penerbit'"); 
$penerbit= mysqli_fetch_array($result);   

if (!$penerbit) {
    die("Data penerbit dengan id penerbit $id_penerbit tidak ditemukan.");
}

if (isset($_POST['update'])) {
    $id_penerbit        = $_POST['id_penerbit'];
    $nama_penerbit      = $_POST['nama_penerbit'];
    $no_tlp_penerbit    = $_POST['no_tlp_penerbit'];
    $nama_sales         = $_POST['nama_sales'];
    $no_tlp_sales       = $_POST['no_tlp_sales'];


    $sql = "UPDATE tbl_penerbit 
            SET nama_penerbit='$nama_penerbit',
                no_tlp_penerbit='$no_tlp_penerbit',
                nama_sales='$nama_sales',
                no_tlp_sales='$no_tlp_sales'
            WHERE id_penerbit='$id_penerbit'";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
                alert('✅ Data berhasil diperbarui!');
                window.location.href='view_penerbit.php';
              </script>";
    } else {
        echo "❌ Gagal mengupdate data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Data penerbit</title>
<link rel="stylesheet" href="../css/Edit.css">
</head>
<body>

<div class="wrapper">
    <h2>Edit Data penerbit</h2>
    <form method="POST">
        <label>id penerbit</label>
        <input type="text" name="id_penerbit" value="<?php echo $penerbit['id_penerbit']; ?>" readonly>
        <label>nama penerbit </label>       
        <input type="text" name="nama_penerbit" value="<?php echo $penerbit['nama_penerbit']; ?>" required>
        <label>nomor telephone penerbit</label>
        <input type="text" name="no_tlp_penerbit" value="<?php echo $penerbit['no_tlp_penerbit']; ?>" required>
        <label>nama sales</label>
        <input type="text" name="nama_sales" value="<?php echo $penerbit['nama_sales']; ?>" required>
        <label>nomor telephone sales</label>
        <input type="text" name="no_tlp_sales" value="<?php echo $penerbit['no_tlp_sales']; ?>" required>
        <button type="submit" name="update">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
