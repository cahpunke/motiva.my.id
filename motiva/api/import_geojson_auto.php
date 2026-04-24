<?php
include '../config/database.php';

$layer = $_POST['layer']; // contoh: cagar_budaya
$file = $_FILES['geojson']['tmp_name'];

$data = json_decode(file_get_contents($file), true);

// ambil 1 sample feature untuk deteksi kolom
$sample = $data['features'][0]['properties'];

// 🔥 CEK KOLOM YANG SUDAH ADA
$columns = [];
$res = mysqli_query($conn, "SHOW COLUMNS FROM $layer");
while ($row = mysqli_fetch_assoc($res)) {
    $columns[] = $row['Field'];
}

// 🔥 TAMBAHKAN KOLOM BARU OTOMATIS
foreach ($sample as $key => $val) {

    $field = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $key));

    if (!in_array($field, $columns)) {

        mysqli_query($conn, "
            ALTER TABLE $layer 
            ADD COLUMN `$field` TEXT NULL
        ");
    }
}

// 🔥 INSERT DATA
foreach ($data['features'] as $f) {

    $geomArr = $f['geometry'];

    // hapus Z jika ada
    if ($geomArr['type'] == 'Point') {
        $coords = $geomArr['coordinates'];
        $geomArr['coordinates'] = [$coords[0], $coords[1]];
    }

    $geom = mysqli_real_escape_string($conn, json_encode($geomArr));

    $props = $f['properties'];

    $fields = [];
    $values = [];

    foreach ($props as $key => $val) {

        $field = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $key));

        $fields[] = "`$field`";
        $values[] = "'" . mysqli_real_escape_string($conn, $val) . "'";
    }

    // tambah geom
    $fields[] = "geom";
    $values[] = "'$geom'";

    $sql = "
        INSERT INTO $layer (" . implode(",", $fields) . ")
        VALUES (" . implode(",", $values) . ")
    ";

    mysqli_query($conn, $sql);
}

echo "🔥 Import sukses + auto mapping kolom";
?>
