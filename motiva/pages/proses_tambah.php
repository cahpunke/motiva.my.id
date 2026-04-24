<?php
// modules/cagar/proses_tambah.php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $destinasi   = mysqli_real_escape_string($conn, $_POST['destinasi'] ?? '');
    $jenis       = mysqli_real_escape_string($conn, $_POST['jenis'] ?? '');
    $kecamata_1  = mysqli_real_escape_string($conn, $_POST['kecamata_1'] ?? '');
    $kelurahan_  = mysqli_real_escape_string($conn, $_POST['kelurahan_'] ?? '');
    $sumber_1    = mysqli_real_escape_string($conn, $_POST['sumber_1'] ?? '');
    $lat         = !empty($_POST['lat']) ? $_POST['lat'] : NULL;
    $lng         = !empty($_POST['lng']) ? $_POST['lng'] : NULL;

    // Proses Upload Foto
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../uploads/foto/";
        $file_ext   = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $foto       = time() . '_' . rand(1000,9999) . '.' . $file_ext;
        $target_file = $target_dir . $foto;

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed_types)) {
            move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
        } else {
            $foto = ''; // reset jika tipe file tidak diizinkan
        }
    }

    // Query Insert
    $sql = "INSERT INTO cagar_budaya_mempawah 
            (destinasi, jenis, kecamata_1, kelurahan_, sumber_1, lat, lng, foto) 
            VALUES 
            ('$destinasi', '$jenis', '$kecamata_1', '$kelurahan_', '$sumber_1', '$lat', '$lng', '$foto')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location.href = '../pages/dashboard.php?page=cagar';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan data: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: ../pages/dashboard.php?page=cagar");
    exit;
}

mysqli_close($conn);
?>
