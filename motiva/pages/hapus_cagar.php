<?php
// modules/cagar/hapus_cagar.php
include '../config/database.php';

// Cek apakah ada ID yang dikirim
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    $id = (int)$_GET['id'];

    // Ambil data foto lama sebelum dihapus (untuk menghapus file fisik)
    $sql = "SELECT foto FROM cagar_budaya_mempawah WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        // Hapus file foto jika ada
        if (!empty($data['foto'])) {
            $file_path = "../uploads/foto/" . $data['foto'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Hapus data dari database
        $delete_sql = "DELETE FROM cagar_budaya_mempawah WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $id);

        if (mysqli_stmt_execute($delete_stmt)) {
            echo "<script>
                    alert('Data berhasil dihapus!');
                    window.location.href = '../../pages/dashboard.php?page=cagar';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus data!');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location.href = '../../pages/dashboard.php?page=cagar';
              </script>";
    }
} else {
    echo "<script>
            alert('ID tidak valid!');
            window.location.href = '../../pages/dashboard.php?page=cagar';
          </script>";
}

mysqli_close($conn);
?>
