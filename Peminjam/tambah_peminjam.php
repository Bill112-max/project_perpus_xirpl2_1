<?php
include "inc/conect.php";

$buku = mysqli_query($koneksi,"
    SELECT 
        b.id_buku,
        b.judul_buku,
        k.kategori,
        p.nama_penerbit
    FROM tbl_buku b
    LEFT JOIN tbl_kategori k ON b.id_kategori = k.id_kategori
    LEFT JOIN tbl_penerbit p ON b.id_penerbit = p.id_penerbit
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Form Pengajuan Peminjaman</title>

<style>
body{
    font-family:Arial;
    background:#f2f2f2;
}
.box{
    width:600px;
    margin:40px auto;
    background:#fff;
    padding:25px;
    border-radius:10px;
}
label{font-weight:bold}
input,select,button{
    width:100%;
    padding:8px;
    margin:8px 0;
}
button{
    background:#0d6efd;
    color:#fff;
    border:none;
    cursor:pointer;
}
button:hover{background:#0b5ed7}

.list{
    border:1px solid #ddd;
    max-height:200px;
    overflow:auto;
}
.item{
    padding:8px;
    cursor:pointer;
    border-bottom:1px solid #eee;
}
.item:hover{
    background:#f1f1f1;
}
.small{font-size:12px;color:#555}
</style>
</head>

<body>

<div class="box">
<h2>Form Pengajuan Peminjaman</h2>

<!-- MODE SEARCH -->
<label>Cari Berdasarkan</label>
<select id="mode">
    <option value="judul">Judul</option>
    <option value="kategori">Kategori</option>
    <option value="penerbit">Penerbit</option>
</select>

<input type="text" id="keyword" placeholder="Ketik pencarian...">

<div class="list" id="listBuku">
<?php while($b=mysqli_fetch_assoc($buku)){ ?>
    <div class="item"
        data-id="<?= $b['id_buku'] ?>"
        data-judul="<?= strtolower($b['judul_buku']) ?>"
        data-kategori="<?= strtolower($b['kategori']) ?>"
        data-penerbit="<?= strtolower($b['nama_penerbit']) ?>"
        onclick="pilihBuku(this)">
        <b><?= $b['judul_buku'] ?></b><br>
        <span class="small">
            <?= $b['kategori'] ?> | <?= $b['nama_penerbit'] ?>
        </span>
    </div>
<?php } ?>
</div>

<hr>

<form method="post" action="dashboard.php?page=simpan_peminjam">
<input type="hidden" name="id_buku" id="id_buku">

<label>Buku Terpilih</label>
<input type="text" id="judul_buku" readonly required>

<label>Jumlah Pinjam</label>
<input type="number" name="jumlah" required>

<label>Tanggal Pinjam</label>
<input type="date" name="tgl_pinjam" required>

<label>Tanggal Kembali</label>
<input type="date" name="tgl_kembali" required>

<button>Simpan</button>
</form>

</div>

<script>
const keyword = document.getElementById("keyword");
const mode = document.getElementById("mode");
const items = document.querySelectorAll(".item");

keyword.addEventListener("keyup", filter);
mode.addEventListener("change", filter);

function filter(){
    let key = keyword.value.toLowerCase();
    let by = mode.value;

    items.forEach(i=>{
        let val = i.dataset[by];
        i.style.display = val.includes(key) ? "block" : "none";
    });
}

function pilihBuku(el){
    document.getElementById("id_buku").value = el.dataset.id;
    document.getElementById("judul_buku").value =
        el.querySelector("b").innerText;
}
</script>

</body>
</html>
