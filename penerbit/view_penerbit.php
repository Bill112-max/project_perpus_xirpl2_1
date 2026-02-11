<?php 
include __DIR__ . '/../inc/conect.php';
$data = mysqli_query($koneksi, "SELECT * FROM tbl_penerbit"); 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan penerbit</title>
    <link rel="stylesheet" href="../css/Tambah.css">
</head>
<body>
       <h2>Daftar Data penerbit</h2> 
    <h3><a href="tambah_penerbit.php">Tambah Penerbit</a></h3> 
    <table border="1" cellpadding="5"> 
        <tr> 
            <th>ID penerbit</th> 
            <th>Nama penerbit</th> 
            <th>nomor telephone penerbit</th> 
            <th>nama sales</th> 
            <th>nomor telephone sales</th> 
            <th>AKSI</th>
        </tr> 
        <?php while($row = mysqli_fetch_array($data)) { ?> 
        <tr> 
            <td><?= $row['id_penerbit']; ?></td>
            <td><?= $row['nama_penerbit']; ?></td>
            <td><?= $row['no_tlp_penerbit']; ?></td>
            <td><?= $row['nama_sales']; ?></td>
            <td><?= $row['no_tlp_sales']; ?></td>
         
       
            <td>
                <a href="edit_penerbit.php?id_penerbit=<?= $row['id_penerbit']; ?>">EDIT</a> | 
                <a href="hapus_penerbit.php?id_penerbit=<?= $row['id_penerbit']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">HAPUS</a>
            </td>
        </tr> 
        <?php } ?> 
    </table> 
</body>
</html>