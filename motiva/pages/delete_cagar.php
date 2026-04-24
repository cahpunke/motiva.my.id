<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Database;
use Config\Auth;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: cagar.php?error=Invalid ID');
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Get foto filename
$stmt = $conn->prepare("SELECT foto FROM cagar_budaya_mempawah WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header('Location: cagar.php?error=Data tidak ditemukan');
    exit;
}

// Delete from database
$stmt = $conn->prepare("DELETE FROM cagar_budaya_mempawah WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    // Delete foto if exists
    if (!empty($data['foto'])) {
        $fotoPath = __DIR__ . '/../uploads/foto/' . $data['foto'];
        if (file_exists($fotoPath)) {
            unlink($fotoPath);
        }
    }
    
    header('Location: cagar.php?success=Data berhasil dihapus');
} else {
    header('Location: cagar.php?error=Gagal menghapus data');
}
exit;
?>