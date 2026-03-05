<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../inc/conect.php';

$id_buku        = $_POST['id_buku'];
$judul_buku     = $_POST['judul_buku'];
$sinopsis       = $_POST['sinopsis'];
$jumlah_halaman = $_POST['jumlah_halaman'];
$jumlah_buku    = $_POST['jumlah_buku'];
$id_kategori    = $_POST['id_kategori'];
$id_penerbit    = $_POST['id_penerbit'];
$thn_terbit     = $_POST['tahun_terbit'];

// ========================================
// PROSES UPLOAD → SIMPAN NAMA FILE
// ========================================
$gambar_buku = '';

if (isset($_FILES['cover_buku']) && $_FILES['cover_buku']['error'] == 0) {

    $file_tmp = $_FILES['cover_buku']['tmp_name'];
    $file_ext = strtolower(pathinfo($_FILES['cover_buku']['name'], PATHINFO_EXTENSION));

    // Nama file unik
    $gambar_buku   = 'cover_' . time() . '.' . $file_ext;

    // Folder tujuan — buat otomatis kalau belum ada
    $folder = __DIR__ . '/../uploads/buku/';
    if (!is_dir($folder)) mkdir($folder, 0755, true);

    // Pindahkan file ke folder uploads
    move_uploaded_file($file_tmp, $folder . $gambar_buku);
}
// ========================================

$sql = "INSERT INTO tbl_buku 
            (id_buku, judul_buku, sinopsis, jumlah_halaman, jumlah_buku, id_kategori, id_penerbit, tahun_terbit, gambar_buku)
        VALUES 
            ('$id_buku', '$judul_buku', '$sinopsis', '$jumlah_halaman', '$jumlah_buku', 
             '$id_kategori', '$id_penerbit', '$thn_terbit', '$gambar_buku')";

if (mysqli_query($koneksi, $sql)) {
    echo "✅ Data berhasil disimpan. <a href='dashboard.php?page=buku'>Lihat Data</a>";
} else {
    echo "❌ Error: " . mysqli_error($koneksi);
}