<?php
include __DIR__ . '/../inc/conect.php';
$id_kategori = $_GET['id_kategori']; 
$result = mysqli_query($koneksi, "SELECT * FROM tbl_kategori WHERE id_kategori='$id_kategori'"); 
$_kategori= mysqli_fetch_array($result);   

if (!$_kategori) {
    die("Data kategori dengan id kategori $id_kategori tidak ditemukan.");
}

if (isset($_POST['update'])) {
    $id_kategori    = $_POST['id_kategori'];
    $kategori       = $_POST['kategori'];


    $sql = "UPDATE tbl_kategori
            SET kategori='$kategori'
            WHERE id_kategori='$id_kategori'";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
                alert('✅ Data berhasil diperbarui!');
                window.location.href='view_kategori.php';
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
<title>Edit Data kategori</title>
<link rel="stylesheet" href="../css/Edit.css">
</head>
<body>

<div class="wrapper">
    <h2>Edit Data kategori</h2>
    <form method="POST">
        <label>id kategori:</label><br> 
        <input type="text" name="id_kategori" value="<?php echo $_kategori ['id_kategori']; ?>" readonly>
        <label>kategori </label>
        <input type="text" name="kategori" value="<?php echo $_kategori['kategori']; ?>" required>

        <button type="submit" name="update">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
