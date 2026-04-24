<?php
include '../config/database.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$kategori = $_POST['kategori'];
$deskripsi = $_POST['deskripsi'];

// 🔥 upload foto jika ada
$foto = $_FILES['foto']['name'];

if ($foto) {
    $tmp = $_FILES['foto']['tmp_name'];
    move_uploaded_file($tmp, "../uploads/foto/".$foto);

    mysqli_query($conn, "
        UPDATE cagar_budaya 
        SET 
            nama='$nama',
            kategori='$kategori',
            deskripsi='$deskripsi',
            foto='$foto'
        WHERE id='$id'
    ");
} else {
    mysqli_query($conn, "
        UPDATE cagar_budaya 
        SET 
            nama='$nama',
            kategori='$kategori',
            deskripsi='$deskripsi'
        WHERE id='$id'
    ");
}

header("Location: ../pages/cagar.php");
?>
