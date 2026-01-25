<?php
include '../inc/conect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Form</title>
    <link rel="stylesheet" href="../css/tambah.css">
</head>
<body>
<div class="wrapper">
    <h1> Input data kategori buku </h1>
    <form action="simpan_kategori.php" method="post">
        <label>id kategori:</label><br> 
        <input type="text" name="id_kategori" required><br><br> 
        <label>kategori:</label><br> 
        <input type="text" name="kategori" required><br><br> 
        
        <button type="submit">Simpan</button> 
    </form> 
</div>
</body>
</html>