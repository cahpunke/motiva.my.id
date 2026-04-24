<?php 
elseif($page == 'cagar'):
    // ==================== HALAMAN DAFTAR CAGAR BUDAYA ====================
?>
    <h2 class="mb-4 fw-bold">📋 Daftar Cagar Budaya</h2>

<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include '../config/database.php';

if (!$conn) {
    echo json_encode(["type" => "FeatureCollection", "features" => []]);
    exit;
}

    $sql = "SELECT id, destinasi, jenis, kecamata_1, kelurahan_, sumber_1, lat, lng, foto 
            FROM cagar_budaya_mempawah 
            ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo '<div class="alert alert-danger">Query Error: ' . mysqli_error($conn) . '</div>';
    } else {
    ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th width="70px">ID</th>
                    <th>Destinasi</th>
                    <th>Jenis</th>
                    <th>Kecamatan</th>
                    <th>Kelurahan</th>
                    <th>Sumber</th>
                    <th width="100px">Latitude</th>
                    <th width="100px">Longitude</th>
                    <th width="180px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($d = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td class='text-center fw-bold'>" . $d['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($d['destinasi'] ?? '-') . "</td>";
                        echo "<td>" . htmlspecialchars($d['jenis'] ?? '-') . "</td>";
                        echo "<td>" . htmlspecialchars($d['kecamata_1'] ?? '-') . "</td>";
                        echo "<td>" . htmlspecialchars($d['kelurahan_'] ?? '-') . "</td>";
                        echo "<td>" . htmlspecialchars($d['sumber_1'] ?? '-') . "</td>";
                        echo "<td>" . ($d['lat'] ?? '-') . "</td>";
                        echo "<td>" . ($d['lng'] ?? '-') . "</td>";
                        echo "<td class='text-center'>
                                <a href='../api/edit_cagar.php?id=" . $d['id'] . "' 
                                   class='btn btn-sm btn-warning'>Edit</a>
                                <a href='../api/hapus.php?id=" . $d['id'] . "' 
                                   class='btn btn-sm btn-danger' 
                                   onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center py-5 text-muted'>Belum ada data cagar budaya.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php 
    } // end if result
    ?>
    
    <div class="mt-3">
        <a href="../api/create_cagar.php" class="btn btn-success">
            + Tambah Data Cagar Budaya Baru
        </a>
    </div>

<?php endif; ?>
