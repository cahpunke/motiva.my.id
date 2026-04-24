<?php
session_start();
include '../config/database.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['login'] = true;
    $_SESSION['role'] = $user['role'];
    header("Location: ../pages/dashboard.php");
} else {
    echo "Login gagal";
}
?>
