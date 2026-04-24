<?php
include '../config/database.php';

$id = $_POST['id'];
$file = $_FILES['foto']['name'];
$tmp  = $_FILES['foto']['tmp_name'];

move_uploaded_file($tmp, "../uploads/foto/".$file);

mysqli_query($conn, "
    UPDATE cagar_budaya 
    SET foto='$file' 
    WHERE id='$id'
");

echo "Upload berhasil";
?>
