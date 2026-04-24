<?php
include '../config/database.php';

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="cagar.geojson"');

$query = mysqli_query($conn, "SELECT * FROM cagar_budaya");

$features = [];

while ($row = mysqli_fetch_assoc($query)) {
    $features[] = [
        "type" => "Feature",
        "geometry" => json_decode($row['geom']),
        "properties" => [
            "nama" => $row['nama']
        ]
    ];
}

echo json_encode([
    "type" => "FeatureCollection",
    "features" => $features
]);
