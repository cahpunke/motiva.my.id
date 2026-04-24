<?php 
#elseif($page == 'cagar'):
?>

<h2 class="mb-4 fw-bold">📋 Daftar Cagar Budaya</h2>

<?php
// 🔥 QUERY SUDAH DISESUAIKAN DENGAN DATABASE + ALIAS
include '../config/database.php';
$sql = "SELECT 
            id,
            destinasi AS nama,
            kecamata_1 AS kecamatan,
            sumber_1 AS sumber,
            kelurahan_ AS kelurahan
        FROM cagar_budaya
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

// 🔥 CEK ERROR QUERY
if (!$result) {
    echo '<div class="alert alert-danger">
            <strong>Query Error:</strong> ' . mysqli_error($conn) . '<br>
            <small>SQL: ' . htmlspecialchars($sql) . '</small>
          </div>';
} else {
?>

<div class="table-responsive">
<table class="table table-bordered table-hover table-striped align-middle">

    <thead class="table-dark text-center">
        <tr>
            <th width="80px">ID</th>
            <th>Nama</th>
            <th>Kecamatan</th>
            <th>Sumber</th>
            <th>Kelurahan</th>
            <th width="180px">Aksi</th>
        </tr>
    </thead>

    <tbody>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($d = mysqli_fetch_assoc($result)) {
    ?>

        <tr>
            <td class="text-center fw-bold"><?= $d['id'] ?></td>

            <td><?= htmlspecialchars($d['nama']) ?></td>

            <td><?= htmlspecialchars($d['kecamatan']) ?></td>

            <td><?= htmlspecialchars($d['sumber']) ?></td>

            <td><?= htmlspecialchars($d['kelurahan']) ?></td>

            <td class="text-center">

                <a href="pages/edit_cagar.php?id=<?= $d['id'] ?>" 
                   class="btn btn-sm btn-warning">
                   ✏️ Edit
                </a>

                <a href="modules/cagar/hapus.php?id=<?= $d['id'] ?>" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                   🗑️ Hapus
                </a>

            </td>
        </tr>

    <?php
        }
    } else {
        echo "<tr>
                <td colspan='6' class='text-center py-5 text-muted'>
                    Belum ada data cagar budaya.
                </td>
              </tr>";
    }
    ?>

    </tbody>
</table>
</div>

<?php } ?>
