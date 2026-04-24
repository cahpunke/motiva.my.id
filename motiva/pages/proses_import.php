<?php
// ================= LOAD KONEKSI =================
include "config/database.php";

// ================= VALIDASI =================
if (!isset($_FILES['geojson'])) {
    die("❌ File tidak ditemukan!");
}

// amankan nama tabel
$table = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['table']);

$tmp = $_FILES['geojson']['tmp_name'];

$json = file_get_contents($tmp);
$data = json_decode($json, true);

if (!isset($data['features'])) {
    die("❌ Format GeoJSON tidak valid!");
}

// ================= AMBIL STRUKTUR =================
$sample = $data['features'][0]['properties'];

$columns = [];

foreach ($sample as $key => $val) {
    $columns[] = "`$key` TEXT";
}

// tambahan field
$columns[] = "lat DOUBLE";
$columns[] = "lng DOUBLE";
$columns[] = "geometry JSON";

// ================= CREATE TABLE =================
$sqlCreate = "CREATE TABLE IF NOT EXISTS `$table` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    " . implode(",", $columns) . "
)";

if (!$conn->query($sqlCreate)) {
    die("❌ Gagal create table: " . $conn->error);
}

// ================= INSERT DATA =================
$total = 0;

foreach ($data['features'] as $f) {

    $p = $f['properties'];
    $g = $f['geometry'];

    $fields = [];
    $values = [];

    foreach ($p as $key => $val) {
        $fields[] = "`$key`";
        $values[] = "'" . $conn->real_escape_string($val) . "'";
    }

    // koordinat
    $lat = "NULL";
    $lng = "NULL";

    if ($g['type'] === "Point") {
        $lng = $g['coordinates'][0];
        $lat = $g['coordinates'][1];
    }

    // geometry
    $fields[] = "lat";
    $fields[] = "lng";
    $fields[] = "geometry";

    $values[] = $lat;
    $values[] = $lng;
    $values[] = "'" . $conn->real_escape_string(json_encode($g)) . "'";

    $sql = "INSERT INTO `$table` (" . implode(",", $fields) . ")
            VALUES (" . implode(",", $values) . ")";

    if ($conn->query($sql)) {
        $total++;
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}

// ================= OUTPUT =================
echo "<h3>✅ Import berhasil</h3>";
echo "<p>Total data: <b>$total</b></p>";
echo "<p>Tabel: <b>$table</b></p>";
echo "<a href='upload_geojson.php'>Upload lagi</a>";
?>
