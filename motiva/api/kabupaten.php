<?php
include '../config/database.php';

$q = mysqli_query($conn, "SELECT * FROM kabupaten_batas_line");

$features = [];

while($d = mysqli_fetch_assoc($q)){

    $features[] = [
        "type" => "Feature",
        "properties" => $d,
        "geometry" => json_decode($d['geometry'])
    ];
}

echo json_encode([
    "type" => "FeatureCollection",
    "features" => $features
]);
