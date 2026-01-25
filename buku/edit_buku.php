<?php
include __DIR__ . '/../inc/conect.php';
$id_buku = $_GET['id_buku'];
$result = mysqli_query($koneksi, "SELECT * FROM tbl_buku WHERE id_buku='$id_buku'");
$buku = mysqli_fetch_array($result);

if (!$buku) {
    die("Data buku dengan id buku $id_buku tidak ditemukan.");
}

if (isset($_POST['update'])) {
    $id_buku    = $_POST['id_buku'];
    $judul_buku  = $_POST['judul_buku'];
    $sinopsis    = $_POST['sinopsis'];
    $jumlah_halaman = $_POST['jumlah_halaman'];
    $jumlah_buku  = $_POST['jumlah_buku'];
    $id_kategori  = $_POST['id_kategori'];
    $id_penerbit  = $_POST['id_penerbit'];
    $thn_terbit   = $_POST['tahun_terbit'];

    $sql = "UPDATE tbl_buku 
            SET judul_buku='$judul_buku',
                sinopsis='$sinopsis',
                jumlah_halaman='$jumlah_halaman',
                jumlah_buku='$jumlah_buku'
            WHERE id_buku='$id_buku'";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
                alert('✅ Data berhasil diperbarui!');
                window.location.href='dashboard.php?page=buku';
              </script>";
    } else {
        echo "❌ Gagal mengupdate data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<style>

.wrapper {
    max-width: 600px;
    margin: 40px auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.wrapper h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 1.6rem;
    font-weight: bold;
    color: #0d6efd; 
}

.wrapper label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #333;
}

.wrapper input[type="text"],
.wrapper textarea {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 18px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.wrapper input[type="text"]:focus,
.wrapper textarea:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 6px rgba(13, 110, 253, 0.3);
    outline: none;
}


.wrapper button {
    width: 100%;
    padding: 12px;
    background: #0d6efd;
    color: #fff;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
}


.wrapper button:hover {
    background: #0b5ed7;
}


@media (max-width: 768px) {
    .wrapper {
        margin: 20px;
        padding: 20px;
    }
}
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Buku</title>
    <link rel="stylesheet" href="../css/Edit.css">
</head>

<body>

    <div class="wrapper">
        <h2>Edit Data buku</h2>
        <form method="POST">
            <label>id buku</label>
            <input type="text" name="id_buku" value="<?php echo $buku['id_buku']; ?>" readonly>
            <label>judul buku </label>
            <input type="text" name="judul_buku" value="<?php echo $buku['judul_buku']; ?>" required>
            <label>sinopsis</label>
            <textarea name="sinopsis" rows="4"><?php echo $buku['sinopsis']; ?></textarea>
            <label>jumlah halaman</label>
            <input type="text" name="jumlah_halaman" value="<?php echo $buku['jumlah_halaman']; ?>" required>
            <label>jumlah buku</label>
            <input type="text" name="jumlah_buku" value="<?php echo $buku['jumlah_buku']; ?>" required>
              <label>kategori(id):</label><br>
            <select name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php
                $kategori = mysqli_query($koneksi, "SELECT * FROM tbl_kategori ORDER BY kategori ASC");
                while ($row = mysqli_fetch_assoc($kategori)) {
                    echo "<option value='{$row['id_kategori']}'>{$row['id_kategori']} - {$row['kategori']}</option>";
                }
                ?>
            </select><br><br>
            <label>penerbit(id):</label><br>
            <select name="id_penerbit" required>
                <option value="">-- Pilih penerbit --</option>
                <?php
                $penerbit = mysqli_query($koneksi, "SELECT * FROM tbl_penerbit ORDER BY nama_penerbit ASC");
                while ($row = mysqli_fetch_assoc($penerbit)) {
                    echo "<option value='{$row['id_penerbit']}'>{$row['id_penerbit']} - {$row['nama_penerbit']}</option>";
                }
                ?>
            <label>tahun terbit</label>
            <input type="text" name="tahun_terbit" value="<?php echo $buku['tahun_terbit']; ?>" readonly>
            <button type="submit" name="update">Simpan Perubahan</button>
        </form>
    </div>

</body>

</html>