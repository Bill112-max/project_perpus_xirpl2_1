<?php
// include "inc/conect.php"; 

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1 style="text-align: center;">404</h1>
    <h1 style="text-align: center;">PAGE NOT FOUND</h1>
</body>
</html>