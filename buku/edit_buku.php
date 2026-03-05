<?php
include __DIR__ . '/../inc/conect.php';

$id_buku = $_GET['id_buku'];
$result  = mysqli_query($koneksi, "SELECT * FROM tbl_buku WHERE id_buku='$id_buku'");
$buku    = mysqli_fetch_array($result);

if (!$buku) {
    die("Data tidak ditemukan");
}

if (isset($_POST['update'])) {

    $judul_buku     = $_POST['judul_buku'];
    $sinopsis       = $_POST['sinopsis'];
    $jumlah_halaman = $_POST['jumlah_halaman'];
    $jumlah_buku    = $_POST['jumlah_buku'];
    $id_kategori    = $_POST['id_kategori'];
    $id_penerbit    = $_POST['id_penerbit'];
    $tahun_terbit   = $_POST['tahun_terbit'];
    $gambar_lama    = $_POST['gambar_lama'];

    $gambar = $gambar_lama;

    if (isset($_FILES['gambar_buku']) && $_FILES['gambar_buku']['error'] == 0) {

        $file_tmp = $_FILES['gambar_buku']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['gambar_buku']['name'], PATHINFO_EXTENSION));

        $gambar = 'cover_' . time() . '.' . $file_ext;

        $folder = __DIR__ . '/../uploads/buku/';
        if (!is_dir($folder)) mkdir($folder, 0755, true);

        if (!empty($gambar_lama) && file_exists($folder . $gambar_lama)) {
            unlink($folder . $gambar_lama);
        }

        move_uploaded_file($file_tmp, $folder . $gambar);
    }

    mysqli_query($koneksi, "UPDATE tbl_buku SET 
        judul_buku      = '$judul_buku',
        sinopsis        = '$sinopsis',
        jumlah_halaman  = '$jumlah_halaman',
        jumlah_buku     = '$jumlah_buku',
        id_kategori     = '$id_kategori',
        id_penerbit     = '$id_penerbit',
        tahun_terbit    = '$tahun_terbit',
        gambar_buku     = '$gambar'
        WHERE id_buku   = '$id_buku'
    ");

    echo "<script>
        alert('Data berhasil diupdate');
        window.location='dashboard.php?page=buku';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Buku</title>
<style>
body { font-family: Segoe UI; background: #f5f8ff; }

.wrapper {
    max-width: 700px;
    margin: 40px auto;
    padding: 25px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

h2 { text-align: center; margin-bottom: 20px; color: #0d6efd; }

.form-row   { display: grid; grid-template-columns: 1fr 2fr; gap: 10px; margin-bottom: 5px; }
.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 5px; }
.form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 5px; }

/* Label grid — ikut kolom yang sama dengan inputnya */
.label-row   { display: grid; grid-template-columns: 1fr 2fr; gap: 10px; margin-bottom: 4px; }
.label-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 4px; }
.label-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 4px; }

.mb { margin-bottom: 15px; }

input, textarea, select {
    width: 100%; padding: 8px;
    border: 1px solid #ccc; border-radius: 6px;
    box-sizing: border-box;
}

textarea { resize: none; }

label { display: block; font-size: 13px; color: #555; font-weight: 600; }

/* PREVIEW GAMBAR */
.preview-wrap { margin: 10px 0 15px 0; }
.preview-wrap img {
    width: 120px;
    height: 160px;
    object-fit: contain;
    border-radius: 8px;
    border: 2px solid #dee2e6;
    display: block;
    margin-top: 8px;
    background: #f8f9fa;
}
.preview-wrap small { color: #888; font-size: 12px; }

button {
    width: 100%; padding: 10px;
    background: #0d6efd; color: white;
    border: none; border-radius: 6px;
    cursor: pointer; font-size: 15px;
}
button:hover { background: #0b5ed7; }

@media(max-width: 768px) {
    .form-row, .form-row-2, .form-row-3,
    .label-row, .label-row-2, .label-row-3 {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>
<div class="wrapper">
<h2>Edit Buku</h2>

<form method="POST" enctype="multipart/form-data">

    <input type="hidden" name="gambar_lama" value="<?= $buku['gambar_buku'] ?>">

    <!-- LABEL: ID & JUDUL -->
    <div class="label-row">
        <label>ID Buku</label>
        <label>Judul Buku</label>
    </div>
    <!-- INPUT: ID & JUDUL -->
    <div class="form-row mb">
        <input type="text" value="<?= $buku['id_buku'] ?>" readonly>
        <input type="text" name="judul_buku" value="<?= $buku['judul_buku'] ?>" required>
    </div>

    <!-- SINOPSIS -->
    <label>Sinopsis</label>
    <textarea name="sinopsis" rows="3" style="margin-bottom:15px"><?= $buku['sinopsis'] ?></textarea>

    <!-- LABEL: JUMLAH -->
    <div class="label-row-2">
        <label>Jumlah Buku</label>
        <label>Jumlah Halaman</label>
    </div>
    <!-- INPUT: JUMLAH -->
    <div class="form-row-2 mb">
        <input type="text" name="jumlah_buku"    value="<?= $buku['jumlah_buku'] ?>">
        <input type="text" name="jumlah_halaman" value="<?= $buku['jumlah_halaman'] ?>">
    </div>

    <!-- LABEL: KATEGORI | PENERBIT | TAHUN -->
    <div class="label-row-3">
        <label>Kategori</label>
        <label>Penerbit</label>
        <label>Tahun Terbit</label>
    </div>
    <!-- INPUT: KATEGORI | PENERBIT | TAHUN -->
    <div class="form-row-3 mb">
        <select name="id_kategori">
            <?php
            $kategori = mysqli_query($koneksi, "SELECT * FROM tbl_kategori");
            while ($k = mysqli_fetch_assoc($kategori)) {
                $sel = ($k['id_kategori'] == $buku['id_kategori']) ? "selected" : "";
                echo "<option value='{$k['id_kategori']}' $sel>{$k['kategori']}</option>";
            }
            ?>
        </select>

        <select name="id_penerbit">
            <?php
            $penerbit = mysqli_query($koneksi, "SELECT * FROM tbl_penerbit");
            while ($p = mysqli_fetch_assoc($penerbit)) {
                $sel = ($p['id_penerbit'] == $buku['id_penerbit']) ? "selected" : "";
                echo "<option value='{$p['id_penerbit']}' $sel>{$p['nama_penerbit']}</option>";
            }
            ?>
        </select>

        <input type="text" name="tahun_terbit" value="<?= $buku['tahun_terbit'] ?>">
    </div>

    <!-- COVER -->
    <label>Ganti Cover <small style="font-weight:normal;color:#aaa;">(kosongkan jika tidak ingin ganti)</small></label>
    <input type="file" name="gambar_buku" accept="image/*" style="margin-top:6px">

    <!-- Preview gambar saat ini -->
    <div class="preview-wrap">
        <?php if (!empty($buku['gambar_buku'])): ?>
            <small>Cover saat ini:</small>
            <img src="uploads/buku/<?= $buku['gambar_buku'] ?>" alt="Cover Buku">
        <?php else: ?>
            <small style="color:#aaa;">Belum ada cover.</small>
        <?php endif; ?>
    </div>

    <button type="submit" name="update">Simpan Perubahan</button>

</form>
</div>
</body>
</html>