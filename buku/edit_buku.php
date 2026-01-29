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
    /* Reset dasar */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Arial, sans-serif;
    }

    /* Wrapper utama */
    .wrapper {
        max-width: 700px;
        margin: 50px auto;
        background: #fdfdfd;
        padding: 40px;
        border-radius: 14px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    /* Judul */
    .wrapper h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 1.8rem;
        font-weight: 700;
        color: #0d6efd;
        letter-spacing: 0.5px;
    }

    /* Label */
    .wrapper label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #444;
    }

    /* Input & Textarea */
    .wrapper input[type="text"],
    .wrapper textarea,
    .wrapper select {
        width: 100%;
        padding: 12px 14px;
        margin-bottom: 20px;
        border: 1px solid #d0d0d0;
        border-radius: 10px;
        font-size: 1rem;
        background: #fff;
        transition: all 0.3s ease;
    }

    .wrapper input[type="text"]:focus,
    .wrapper textarea:focus,
    .wrapper select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 8px rgba(13, 110, 253, 0.25);
        outline: none;
    }

    /* Tombol */
    .wrapper button {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: #fff;
        font-size: 1.05rem;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: transform 0.2s ease, background 0.3s ease;
    }

    .wrapper button:hover {
        background: linear-gradient(135deg, #0b5ed7, #094db5);
        transform: translateY(-2px);
    }

    /* Responsif */
    @media (max-width: 768px) {
        .wrapper {
            margin: 20px;
            padding: 25px;
        }

        .wrapper h2 {
            font-size: 1.5rem;
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
            <label>Kategori (id):</label><br>
            <select name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php
                $kategori = mysqli_query($koneksi, "SELECT * FROM tbl_kategori ORDER BY kategori ASC");
                while ($row = mysqli_fetch_assoc($kategori)) {
                    // cek apakah id_kategori dari buku sama dengan id_kategori dari tabel kategori
                    $selected = ($row['id_kategori'] == $buku['id_kategori']) ? "selected" : "";
                    echo "<option value='{$row['id_kategori']}' $selected>{$row['id_kategori']} - {$row['kategori']}</option>";
                }
                ?>
            </select><br><br>

            <label>Penerbit (id):</label><br>
            <select name="id_penerbit" required>
                <option value="">-- Pilih Penerbit --</option>
                <?php
                $penerbit = mysqli_query($koneksi, "SELECT * FROM tbl_penerbit ORDER BY nama_penerbit ASC");
                while ($row = mysqli_fetch_assoc($penerbit)) {
                    $selected = ($row['id_penerbit'] == $buku['id_penerbit']) ? "selected" : "";
                    echo "<option value='{$row['id_penerbit']}' $selected>{$row['id_penerbit']} - {$row['nama_penerbit']}</option>";
                }
                ?>
            </select><br><br>

            <label>tahun terbit</label>
            <input type="text" name="tahun_terbit" value="<?php echo $buku['tahun_terbit']; ?>" readonly>
            <button type="submit" name="update">Simpan Perubahan</button>
        </form>
    </div>

</body>

</html>