
<?php
session_start();
include '../config/database.php';

// Validasi method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak valid");
}

// Ambil input dengan aman
$u = trim($_POST['username'] ?? '');
$p = trim($_POST['password'] ?? '');

// Validasi kosong
if ($u === '' || $p === '') {
    die("Username dan password wajib diisi");
}

// Gunakan prepared statement (ANTI SQL INJECTION)
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $u);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$d = mysqli_fetch_assoc($result);

// Verifikasi password
if ($d && password_verify($p, $d['password'])) {
    $_SESSION['login'] = true;
    $_SESSION['username'] = $d['username'];

    header("Location: ../pages/dashboard.php");
    exit;
} else {
    echo "Login gagal";
}
?>
