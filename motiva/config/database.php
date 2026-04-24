<?php
// Load environment variables from .env file
function loadEnv() {
    $envFile = __DIR__ . '/../.env';
    if (!file_exists($envFile)) {
        $envFile = __DIR__ . '/../.env.example';
    }
    
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') === false || $line[0] === '#') continue;
            
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            $value = trim($value, '"\'');
            
            if (!empty($key)) {
                $_ENV[$key] = $value;
            }
        }
    }
}

// Load environment variables
loadEnv();

// Get database credentials from environment variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$name = $_ENV['DB_NAME'] ?? 'motiva_db';

// Create database connection
$conn = mysqli_connect($host, $user, $pass, $name);

// Check connection
if (!$conn) {
    die("Koneksi DB gagal: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");
?>
