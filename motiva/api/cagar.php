<?php
// api/cagar.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include '../config/database.php';

if (!$conn) {
    echo json_encode(["type" => "FeatureCollection", "features" => []]);
    exit;
}

$sql = "SELECT 
            id,
            destinasi,
            jenis,
            kecamata_1,
            kelurahan_,
            sumber_1,
            lat,
            lng,
            geometry,
            foto
        FROM cagar_budaya_mempawah 
        WHERE lat IS NOT NULL 
          AND lng IS NOT NULL";

$result = mysqli_query($conn, $sql);

$features = [];

while ($r = mysqli_fetch_assoc($result)) {

    // decode geometry dari DB
    $geom = json_decode($r['geometry'], true);

    // fallback jika tidak ada geometry
    if (empty($geom) && !empty($r['lat']) && !empty($r['lng'])) {
        $geom = [
            "type" => "Point",
            "coordinates" => [
                (float)$r['lng'],
                (float)$r['lat']
            ]
        ];
    }

    $features[] = [
        "type" => "Feature",
        "geometry" => $geom, // ✅ FIX DI SINI
        "properties" => [
            "id"        => (int)$r['id'],
            "nama"      => $r['destinasi'] ?? 'Tanpa Nama',
            "jenis"     => $r['jenis'] ?? '-',
            "kecamatan" => $r['kecamata_1'] ?? '-',
            "kelurahan" => $r['kelurahan_'] ?? '-',
            "sumber"    => $r['sumber_1'] ?? '-',
            "foto"      => $r['foto'] ?? ''
        ]
    ];
}

echo json_encode([
    "type" => "FeatureCollection",
    "features" => $features
], JSON_UNESCAPED_UNICODE);

mysqli_close($conn);
?>
