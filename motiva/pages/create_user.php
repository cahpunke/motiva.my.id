<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Database;
use Config\Auth;
use Config\Security;

$pageTitle = 'Tambah User';

include __DIR__ . '/../layout/header.php';

$db = new Database();
$conn = $db->getConnection();

$success = '';
$error = '';
$csrfToken = Security::generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token tidak valid';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? 'operator';

        if (empty($username) || empty($password)) {
            $error = 'Username dan password wajib diisi';
        } elseif (strlen($username) < 4) {
            $error = 'Username minimal 4 karakter';
        } elseif (strlen($password) < 6) {
            $error = 'Password minimal 6 karakter';
        } elseif ($password !== $confirmPassword) {
            $error = 'Password tidak cocok';
        } else {
            // Check if username exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'Username sudah terdaftar';
            } else {
                $hashedPassword = Security::hashPassword($password);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param('ssss', $username, $email, $hashedPassword, $role);
                
                if ($stmt->execute()) {
                    $success = 'User berhasil ditambahkan';
                    $_POST = [];
                } else {
                    $error = 'Gagal menambahkan user';
                }
            }
        }
    }
}

?>

<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Tambah User</h3>
            </div>
            <form method="post">
                <div class="card-body">
                    <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= htmlspecialchars($success) ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= htmlspecialchars($error) ?>
                    </div>
                    <?php endif; ?>

                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                    <div class="form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                        <small class="form-text text-muted">Minimal 4 karakter, hanya huruf, angka, dan underscore</small>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="form-text text-muted">Minimal 6 karakter</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="operator">Operator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="users.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>