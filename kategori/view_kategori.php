<?php 
include __DIR__ . '/../inc/conect.php';
$data = mysqli_query($koneksi, "SELECT * FROM tbl_kategori"); 
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan data kategori</title>
    <link rel="stylesheet" href="../css/Tambah.css">
</head>
<body>
       <h2>Daftar Data kategori</h2> 
    <h3><a href="tambah_kategori.php">Tambah kategori</a></h3> 
    <table border="1" cellpadding="5"> 
        <tr> 
            <th>ID kategori</th> 
            <th>Kategori</th> 
            <th>AKSI</th>
        </tr> 
        <?php while($row = mysqli_fetch_array($data)) { ?> 
        <tr> 
            <td><?= $row['id_kategori']; ?></td> 
            <td><?= $row['kategori']; ?></td> 
            <td>
                <a href="edit_kategori.php?id_kategori=<?= $row['id_kategori']; ?>">EDIT</a> | 
                <a href="hapus_kategori.php?id_kategori=<?= $row['id_kategori']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">HAPUS</a>
            </td>
        </tr> 
        <?php } ?> 
    </table> 
</body>
</html>