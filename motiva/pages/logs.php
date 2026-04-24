<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Database;
use Config\Auth;

$pageTitle = 'Activity Log';

include __DIR__ . '/../layout/header.php';

$db = new Database();
$conn = $db->getConnection();

// Pagination
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total
$totalResult = mysqli_query($conn, "SELECT COUNT(*) as total FROM activity_logs");
$totalRecords = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalRecords / $limit);

// Get logs
$result = mysqli_query($conn, "SELECT al.*, u.username FROM activity_logs al
                               LEFT JOIN users u ON al.user_id = u.id
                               ORDER BY al.created_at DESC
                               LIMIT $limit OFFSET $offset");

?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Activity Log</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 100px">Tanggal</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Deskripsi</th>
                            <th style="width: 150px">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
                            <td><span class="badge badge-primary"><?= htmlspecialchars($row['action']) ?></span></td>
                            <td><?= htmlspecialchars($row['description'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['ip_address'] ?? '-') ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav>
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>