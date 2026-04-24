<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Database;
use Config\Auth;

$pageTitle = 'Data Cagar Budaya';

include __DIR__ . '/../layout/header.php';

$db = new Database();
$conn = $db->getConnection();

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total records
$totalResult = mysqli_query($conn, "SELECT COUNT(*) as total FROM cagar_budaya_mempawah");
$totalRecords = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalRecords / $limit);

// Get data
$sql = "SELECT id, destinasi AS nama, kecamata_1 AS kecamatan, kelurahan_, sumber_1 AS sumber, foto
        FROM cagar_budaya_mempawah
        ORDER BY id DESC
        LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

?>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Daftar Cagar Budaya</h3>
                <div class="card-tools">
                    <a href="create_cagar.php" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 50px">ID</th>
                                <th>Nama</th>
                                <th>Kecamatan</th>
                                <th>Kelurahan</th>
                                <th>Sumber</th>
                                <th style="width: 100px">Foto</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="text-center"><?= (int)$row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['kecamatan'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['kelurahan_'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['sumber'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($row['foto'])): ?>
                                        <img src="../uploads/foto/<?= htmlspecialchars($row['foto']) ?>" width="50" class="img-thumbnail">
                                        <?php else: ?>
                                        <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit_cagar.php?id=<?= (int)$row['id'] ?>" class="btn btn-xs btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete_cagar.php?id=<?= (int)$row['id'] ?>" class="btn btn-xs btn-danger" onclick="return confirm('Yakin?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
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