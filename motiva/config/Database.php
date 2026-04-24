<?php
namespace Config;

class Database {
    private $host;
    private $user;
    private $pass;
    private $name;
    private $conn;

    public function __construct() {
        // Load from .env file
        $this->loadEnv();
        $this->connect();
    }

    private function loadEnv() {
        $envFile = __DIR__ . '/../.env';
        if (!file_exists($envFile)) {
            $envFile = __DIR__ . '/../.env.example';
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') === false || $line[0] === '#') continue;
            
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            if ($key === 'DB_HOST') $this->host = $value;
            if ($key === 'DB_USER') $this->user = $value;
            if ($key === 'DB_PASS') $this->pass = $value;
            if ($key === 'DB_NAME') $this->name = $value;
        }
    }

    private function connect() {
        $this->conn = new \mysqli($this->host, $this->user, $this->pass, $this->name);
        
        if ($this->conn->connect_error) {
            error_log("Database Connection Failed: " . $this->conn->connect_error);
            die("Database connection failed. Please contact administrator.");
        }
        
        $this->conn->set_charset("utf8mb4");
    }

    public function getConnection() {
        return $this->conn;
    }

    public function prepare($query) {
        return $this->conn->prepare($query);
    }

    public function query($query) {
        return $this->conn->query($query);
    }

    public function close() {
        $this->conn->close();
    }
}
?>