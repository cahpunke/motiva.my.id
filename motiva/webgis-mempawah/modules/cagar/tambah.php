<?php
include '../../config/database.php';

$nama = $_POST['nama'];

$foto = $_FILES['foto']['name'];
$tmp = $_FILES['foto']['tmp_name'];

move_uploaded_file($tmp, "../../uploads/foto/".$foto);

mysqli_query($conn, "
INSERT INTO cagar_budaya (nama, foto)
VALUES ('$nama', '$foto')
");
?>
