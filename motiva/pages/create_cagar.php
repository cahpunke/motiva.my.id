<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Database;
use Config\Auth;
use Config\Security;

$pageTitle = 'Tambah Cagar Budaya';

include __DIR__ . '/../layout/header.php';

$db = new Database();
$conn = $db->getConnection();

// Get kecamatan list for dropdown
$kecamatanList = [];
$result = mysqli_query($conn, "SELECT DISTINCT kecamata_1 FROM cagar_budaya_mempawah WHERE kecamata_1 IS NOT NULL ORDER BY kecamata_1");
while ($row = mysqli_fetch_assoc($result)) {
    $kecamatanList[] = $row['kecamata_1'];
}

$success = '';
$error = '';
$csrfToken = Security::generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF
    if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token tidak valid';
    } else {
        $nama = $_POST['nama'] ?? '';
        $jenis = $_POST['jenis'] ?? '';
        $kecamatan = $_POST['kecamatan'] ?? '';
        $kelurahan = $_POST['kelurahan'] ?? '';
        $sumber = $_POST['sumber'] ?? '';
        $lat = $_POST['lat'] ?? '';
        $lng = $_POST['lng'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';

        if (empty($nama) || empty($kecamatan)) {
            $error = 'Nama dan Kecamatan wajib diisi';
        } else {
            $stmt = $conn->prepare("INSERT INTO cagar_budaya_mempawah (destinasi, jenis, kecamata_1, kelurahan_, sumber_1, lat, lng, deskripsi) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->bind_param('sssssdds', $nama, $jenis, $kecamatan, $kelurahan, $sumber, $lat, $lng, $deskripsi);
            
            if ($stmt->execute()) {
                $success = 'Data berhasil ditambahkan';
                // Reset form
                $_POST = [];
            } else {
                $error = 'Gagal menambahkan data: ' . $stmt->error;
            }
        }
    }
}
?>

<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Cagar Budaya</h3>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Sukses!</strong> <?= htmlspecialchars($success) ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Error!</strong> <?= htmlspecialchars($error) ?>
                    </div>
                    <?php endif; ?>

                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                    <div class="form-group">
                        <label for="nama">Nama Cagar Budaya <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <input type="text" class="form-control" id="jenis" name="jenis" value="<?= htmlspecialchars($_POST['jenis'] ?? '') ?>" placeholder="Candi, Makam, dll">
                    </div>

                    <div class="form-group">
                        <label for="kecamatan">Kecamatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="<?= htmlspecialchars($_POST['kecamatan'] ?? '') ?>" list="kecamatanList" required>
                        <datalist id="kecamatanList">
                            <?php foreach ($kecamatanList as $kec): ?>
                            <option value="<?= htmlspecialchars($kec) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label for="kelurahan">Kelurahan</label>
                        <input type="text" class="form-control" id="kelurahan" name="kelurahan" value="<?= htmlspecialchars($_POST['kelurahan'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="sumber">Sumber</label>
                        <input type="text" class="form-control" id="sumber" name="sumber" value="<?= htmlspecialchars($_POST['sumber'] ?? '') ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="lat">Latitude</label>
                            <input type="number" class="form-control" id="lat" name="lat" step="0.000001" value="<?= htmlspecialchars($_POST['lat'] ?? '') ?>" placeholder="-7.123456">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lng">Longitude</label>
                            <input type="number" class="form-control" id="lng" name="lng" step="0.000001" value="<?= htmlspecialchars($_POST['lng'] ?? '') ?>" placeholder="109.123456">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="cagar.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Petunjuk</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>Nama</strong> - Nama cagar budaya (wajib)</li>
                    <li><strong>Jenis</strong> - Tipe cagar budaya</li>
                    <li><strong>Kecamatan</strong> - Lokasi kecamatan (wajib)</li>
                    <li><strong>Kelurahan</strong> - Lokasi kelurahan</li>
                    <li><strong>Latitude/Longitude</strong> - Koordinat GPS</li>
                    <li><strong>Deskripsi</strong> - Penjelasan detail</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>