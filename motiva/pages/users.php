<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Database;
use Config\Auth;
use Config\Security;

$pageTitle = 'Manajemen User';

include __DIR__ . '/../layout/header.php';

$db = new Database();
$conn = $db->getConnection();

$success = '';
$error = '';
$csrfToken = Security::generateCSRFToken();

// Handle delete
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    if (Security::verifyCSRFToken($_GET['csrf'] ?? '')) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND id != ?");
        $currentUser = $auth->getCurrentUser();
        $stmt->bind_param('ii', $deleteId, $currentUser['id']);
        
        if ($stmt->execute()) {
            $success = 'User berhasil dihapus';
        } else {
            $error = 'Gagal menghapus user';
        }
    }
}

// Get all users
$result = mysqli_query($conn, "SELECT id, username, role, email, created_at FROM users ORDER BY created_at DESC");
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar User</h3>
                <div class="card-tools">
                    <a href="create_user.php" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Tambah User
                    </a>
                </div>
            </div>
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

                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50px">ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th style="width: 100px">Role</th>
                            <th>Dibuat</th>
                            <th style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= (int)$user['id'] ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email'] ?? '-') ?></td>
                            <td>
                                <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td><?= substr($user['created_at'], 0, 10) ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= (int)$user['id'] ?>" class="btn btn-xs btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?= (int)$user['id'] ?>&csrf=<?= urlencode($csrfToken) ?>" class="btn btn-xs btn-danger" onclick="return confirm('Yakin?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>