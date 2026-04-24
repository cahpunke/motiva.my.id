<?php
include '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

$geom = json_encode($data['geometry']);
$geom = mysqli_real_escape_string($conn, $geom);

mysqli_query($conn, "
INSERT INTO cagar_budaya (nama, geom)
VALUES ('Digitasi Map', '$geom')
");
?>
