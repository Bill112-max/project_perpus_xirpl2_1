<?php  
include __DIR__ . '/../inc/conect.php';
$data = mysqli_query($koneksi, "SELECT * FROM tbl_penerbit");  
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Data Penerbit</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma;
            background: #f4f6f9;
            margin: 0;
        }

        .wrapper {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #48804f;
            font-weight: 600;
        }

        .top-bar {
            display: flex;
            justify-content: flex-start; /* tombol di kiri */
            margin-bottom: 20px;
        }

        .top-bar a {
            background: #4ba03e;
            color: #fff;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s;
        }

        .top-bar a:hover {
            background: #2c5134;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            font-size: 14px;
        }

        th {
            background: #48804f;
            color: #fff;
            padding: 12px;
            text-align: left;
            font-weight: 500;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f8f9fa;
        }

        /* ACTION BUTTONS */
        .aksi a {
            text-decoration: none;
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 6px;
            margin-right: 6px;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .aksi a:hover {
            opacity: 0.85;
        }

        .edit {
            background: #ffc107;
            color: #000;
        }

        .hapus {
            background: #dc3545;
            color: #fff;
        }

        /* MOBILE */
        @media(max-width:768px){
            .wrapper {
                margin: 20px;
                padding: 20px;
            }

            table {
                font-size: 13px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Daftar Data Penerbit</h2>  

        <div class="top-bar">
            <a href="tambah_penerbit.php">+ Tambah Penerbit</a>
        </div>

        <table>
            <tr>
                <th>ID Penerbit</th>
                <th>Nama Penerbit</th>
                <th>Nomor Telepon Penerbit</th>
                <th>Nama Sales</th>
                <th>Nomor Telepon Sales</th>
                <th>Aksi</th>
            </tr>
            <?php while($row = mysqli_fetch_array($data)) { ?>  
            <tr>
                <td><?= $row['id_penerbit']; ?></td>
                <td><?= $row['nama_penerbit']; ?></td>
                <td><?= $row['no_tlp_penerbit']; ?></td>
                <td><?= $row['nama_sales']; ?></td>
                <td><?= $row['no_tlp_sales']; ?></td>
                <td class="aksi">
                    <a class="edit" href="?page=edit_penerbit&id_penerbit=<?= $row['id_penerbit']; ?>">Edit</a>
                    <a class="hapus" href="?page=hapus_penerbit&id_penerbit=<?= $row['id_penerbit']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>  
        </table>
    </div>
</body>
</html>