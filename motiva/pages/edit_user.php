<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Database;
use Config\Auth;
use Config\Security;

$pageTitle = 'Edit User';

include __DIR__ . '/../layout/header.php';

$db = new Database();
$conn = $db->getConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: users.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: users.php?error=User tidak ditemukan');
    exit;
}

$success = '';
$error = '';
$csrfToken = Security::generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token tidak valid';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'operator';

        if ($password !== '' && strlen($password) < 6) {
            $error = 'Password minimal 6 karakter';
        } else {
            // Prepare password part
            if (empty($password)) {
                $stmt = $conn->prepare("UPDATE users SET email=?, role=? WHERE id=?");
                $stmt->bind_param('ssi', $email, $role, $id);
            } else {
                $hashedPassword = Security::hashPassword($password);
                $stmt = $conn->prepare("UPDATE users SET email=?, password=?, role=? WHERE id=?");
                $stmt->bind_param('sssi', $email, $hashedPassword, $role, $id);
            }
            
            if ($stmt->execute()) {
                $success = 'User berhasil diupdate';
                $user['email'] = $email;
                $user['role'] = $role;
            } else {
                $error = 'Gagal mengupdate user';
            }
        }
    }
}

?>

<div class="row">
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Edit User</h3>
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
                        <label>Username</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                        <small class="form-text text-muted">Username tidak bisa diubah</small>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role">
                            <option value="operator" <?= $user['role'] === 'operator' ? 'selected' : '' ?>>Operator</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>

                    <p class="text-muted"><small>Dibuat: <?= htmlspecialchars($user['created_at']) ?></small></p>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                    <a href="users.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>