<?php
namespace Config;

class Auth {
    private $db;
    private $sessionTimeout = 3600; // 1 hour

    public function __construct() {
        $this->db = new Database();
        session_start();
        $this->checkSessionTimeout();
    }

    /**
     * Login User
     */
    public function login($username, $password) {
        $username = trim($username);
        $password = trim($password);

        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Username dan password wajib diisi'];
        }

        $stmt = $this->db->prepare("SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1");
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error'];
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Log failed attempt
            error_log("Failed login attempt for username: {$username}");
            return ['success' => false, 'message' => 'Username atau password salah'];
        }

        $user = $result->fetch_assoc();

        if (!Security::verifyPassword($password, $user['password'])) {
            error_log("Failed login attempt for username: {$username}");
            return ['success' => false, 'message' => 'Username atau password salah'];
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();

        error_log("Successful login for username: {$username}");
        return ['success' => true, 'message' => 'Login berhasil'];
    }

    /**
     * Logout User
     */
    public function logout() {
        session_destroy();
        return true;
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Get current user
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role']
        ];
    }

    /**
     * Check session timeout
     */
    private function checkSessionTimeout() {
        if ($this->isLoggedIn()) {
            if (time() - $_SESSION['last_activity'] > $this->sessionTimeout) {
                $this->logout();
                header('Location: login.php?expired=1');
                exit;
            }
            $_SESSION['last_activity'] = time();
        }
    }

    /**
     * Check permission
     */
    public function hasPermission($role) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        return $_SESSION['role'] === $role || $_SESSION['role'] === 'admin';
    }
}
?>