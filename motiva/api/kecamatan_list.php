<?php
header('Content-Type: application/json');

include '../config/database.php';

// ambil kecamatan dari tabel cagar (lebih akurat untuk filter)
$sql = "SELECT DISTINCT kecamata_1 AS kecamatan 
        FROM cagar_budaya_mempawah 
        WHERE kecamata_1 IS NOT NULL 
        ORDER BY kecamata_1 ASC";

$result = mysqli_query($conn, $sql);

$data = [];

while($row = mysqli_fetch_assoc($result)){
    $data[] = $row['kecamatan'];
}

echo json_encode($data);

mysqli_close($conn);
