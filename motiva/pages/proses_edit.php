<?php
// modules/cagar/proses_edit.php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id          = (int)$_POST['id'];
    $destinasi   = mysqli_real_escape_string($conn, $_POST['destinasi'] ?? '');
    $jenis       = mysqli_real_escape_string($conn, $_POST['jenis'] ?? '');
    $kecamata_1  = mysqli_real_escape_string($conn, $_POST['kecamata_1'] ?? '');
    $kelurahan_  = mysqli_real_escape_string($conn, $_POST['kelurahan_'] ?? '');
    $sumber_1    = mysqli_real_escape_string($conn, $_POST['sumber_1'] ?? '');
    $lat         = !empty($_POST['lat']) ? $_POST['lat'] : NULL;
    $lng         = !empty($_POST['lng']) ? $_POST['lng'] : NULL;

    // Proses Upload Foto Baru (jika ada)
    $foto_query = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../uploads/foto/";
        $file_ext   = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $foto_baru  = time() . '_' . rand(1000,9999) . '.' . $file_ext;
        $target_file = $target_dir . $foto_baru;

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed_types)) {
            // Hapus foto lama jika ada
            $old = mysqli_query($conn, "SELECT foto FROM cagar_budaya_mempawah WHERE id = $id");
            $old_data = mysqli_fetch_assoc($old);
            if (!empty($old_data['foto']) && file_exists("../uploads/foto/" . $old_data['foto'])) {
                unlink("../uploads/foto/" . $old_data['foto']);
            }

            move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
            $foto_query = ", foto = '$foto_baru'";
        }
    }

    // Query Update
    $sql = "UPDATE cagar_budaya_mempawah SET 
                destinasi   = '$destinasi',
                jenis       = '$jenis',
                kecamata_1  = '$kecamata_1',
                kelurahan_  = '$kelurahan_',
                sumber_1    = '$sumber_1',
                lat         = '$lat',
                lng         = '$lng'
                $foto_query
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location.href = '../pages/dashboard.php?page=cagar';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui data: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: ../pages/dashboard.php?page=cagar");
    exit;
}

mysqli_close($conn);
?>
