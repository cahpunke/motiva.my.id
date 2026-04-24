<?php
include '../config/database.php';

// ================= VALIDASI FILE =================
if (!isset($_FILES['geojson']) || $_FILES['geojson']['error'] != 0) {
    die("❌ File GeoJSON tidak ditemukan atau gagal upload!");
}

$tmp = $_FILES['geojson']['tmp_name'];
$json = file_get_contents($tmp);
$data = json_decode($json, true);

if (!$data || !isset($data['features'])) {
    die("❌ Format GeoJSON tidak valid!");
}

// ================= AUTO NAMA TABLE =================
$table = $data['name'] ?? 'geojson_data';
$table = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $table));

if ($table == '') {
    $table = 'geojson_' . time();
}

echo "<h4>📦 Nama Table: <b>$table</b></h4>";

// ================= CEK / CREATE TABLE =================
$cekTable = $conn->query("SHOW TABLES LIKE '$table'");

if ($cekTable->num_rows == 0) {

    $sample = $data['features'][0]['properties'];
    $columns = [];

    foreach ($sample as $key => $val) {
        $columns[] = "`$key` TEXT";
    }

    $columns[] = "lat DOUBLE";
    $columns[] = "lng DOUBLE";
    $columns[] = "geometry JSON";
    $columns[] = "foto TEXT";

    $sqlCreate = "CREATE TABLE `$table` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        " . implode(",", $columns) . "
    )";

    if (!$conn->query($sqlCreate)) {
        die("❌ Gagal create table: " . $conn->error);
    }

    echo "✅ Table berhasil dibuat<br>";
}

// ================= CEK KOLOM =================
$existingColumns = [];
$result = $conn->query("SHOW COLUMNS FROM `$table`");

while ($row = $result->fetch_assoc()) {
    $existingColumns[] = $row['Field'];
}

// ================= TAMBAH KOLOM JIKA BELUM ADA =================
$sample = $data['features'][0]['properties'];

foreach ($sample as $key => $val) {
    if (!in_array($key, $existingColumns)) {

        $alter = "ALTER TABLE `$table` ADD `$key` TEXT";

        if ($conn->query($alter)) {
            echo "➕ Kolom ditambahkan: $key<br>";
        }
    }
}

// ================= TAMBAH KOLOM TAMBAHAN =================
if (!in_array('lat', $existingColumns)) {
    $conn->query("ALTER TABLE `$table` ADD lat DOUBLE");
}
if (!in_array('lng', $existingColumns)) {
    $conn->query("ALTER TABLE `$table` ADD lng DOUBLE");
}
if (!in_array('geometry', $existingColumns)) {
    $conn->query("ALTER TABLE `$table` ADD geometry JSON");
}
if (!in_array('foto', $existingColumns)) {
    $conn->query("ALTER TABLE `$table` ADD foto TEXT");
}

// ================= INSERT / UPDATE =================
$totalInsert = 0;
$totalUpdate = 0;

foreach ($data['features'] as $f) {

    $p = $f['properties'];
    $g = $f['geometry'];

    // gunakan Number sebagai unique
    $uniqueKey = $p['Number'] ?? null;

    // ================= KOORDINAT =================
    $lat = "NULL";
    $lng = "NULL";

    if ($g['type'] === "Point") {
        $lng = $g['coordinates'][0];
        $lat = $g['coordinates'][1];
    }

    // ================= FOTO =================
    $foto = "NULL";
    if (isset($p['foto']) && $p['foto'] != '') {
        $foto = "'../upload/foto/" . $conn->real_escape_string($p['foto']) . "'";
    }

    // ================= CEK DATA EXIST =================
    if ($uniqueKey) {

        $cek = $conn->query("SELECT id FROM `$table` WHERE `Number`='$uniqueKey'");

        if ($cek && $cek->num_rows > 0) {

            // UPDATE
            $updateFields = [];

            foreach ($p as $key => $val) {
                $val = $conn->real_escape_string($val);
                $updateFields[] = "`$key`='$val'";
            }

            $updateFields[] = "lat=$lat";
            $updateFields[] = "lng=$lng";
            $updateFields[] = "geometry='" . $conn->real_escape_string(json_encode($g)) . "'";

            if ($foto !== "NULL") {
                $updateFields[] = "foto=$foto";
            }

            $sqlUpdate = "UPDATE `$table` SET 
                          " . implode(",", $updateFields) . " 
                          WHERE `Number`='$uniqueKey'";

            $conn->query($sqlUpdate);
            $totalUpdate++;
            continue;
        }
    }

    // ================= INSERT =================
    $fields = [];
    $values = [];

    foreach ($p as $key => $val) {
        $fields[] = "`$key`";
        $values[] = "'" . $conn->real_escape_string($val) . "'";
    }

    $fields[] = "lat";
    $fields[] = "lng";
    $fields[] = "geometry";
    $fields[] = "foto";

    $values[] = $lat;
    $values[] = $lng;
    $values[] = "'" . $conn->real_escape_string(json_encode($g)) . "'";
    $values[] = $foto;

    $sqlInsert = "INSERT INTO `$table` (" . implode(",", $fields) . ")
                  VALUES (" . implode(",", $values) . ")";

    if ($conn->query($sqlInsert)) {
        $totalInsert++;
    }
}

// ================= OUTPUT =================
echo "<hr>";
echo "<h3>✅ Import Selesai</h3>";
echo "<p>📥 Insert: <b>$totalInsert</b></p>";
echo "<p>🔄 Update: <b>$totalUpdate</b></p>";

echo "<a href='../pages/dashboard.php?page=upload' class='btn btn-primary'>⬅ Kembali</a>";
?>
