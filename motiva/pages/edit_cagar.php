<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Database;
use Config\Auth;
use Config\Security;

$pageTitle = 'Edit Cagar Budaya';

include __DIR__ . '/../layout/header.php';

$db = new Database();
$conn = $db->getConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: cagar.php');
    exit;
}

// Get data
$stmt = $conn->prepare("SELECT * FROM cagar_budaya_mempawah WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header('Location: cagar.php?error=Data tidak ditemukan');
    exit;
}

// Get kecamatan list
$kecamatanList = [];
$result = mysqli_query($conn, "SELECT DISTINCT kecamata_1 FROM cagar_budaya_mempawah WHERE kecamata_1 IS NOT NULL ORDER BY kecamata_1");
while ($row = mysqli_fetch_assoc($result)) {
    $kecamatanList[] = $row['kecamata_1'];
}

$success = '';
$error = '';
$csrfToken = Security::generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $stmt = $conn->prepare("UPDATE cagar_budaya_mempawah 
                                   SET destinasi=?, jenis=?, kecamata_1=?, kelurahan_=?, sumber_1=?, lat=?, lng=?, deskripsi=?
                                   WHERE id=?");
            
            $stmt->bind_param('sssssddi', $nama, $jenis, $kecamatan, $kelurahan, $sumber, $lat, $lng, $deskripsi, $id);
            
            if ($stmt->execute()) {
                $success = 'Data berhasil diperbarui';
                $data = [
                    'id' => $id,
                    'destinasi' => $nama,
                    'jenis' => $jenis,
                    'kecamata_1' => $kecamatan,
                    'kelurahan_' => $kelurahan,
                    'sumber_1' => $sumber,
                    'lat' => $lat,
                    'lng' => $lng,
                    'deskripsi' => $deskripsi,
                    'foto' => $data['foto']
                ];
            } else {
                $error = 'Gagal memperbarui data: ' . $stmt->error;
            }
        }
    }
}
?>

<div class="row">
    <div class="col-md-8">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Form Edit Cagar Budaya</h3>
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
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($data['destinasi'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <input type="text" class="form-control" id="jenis" name="jenis" value="<?= htmlspecialchars($data['jenis'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="kecamatan">Kecamatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="<?= htmlspecialchars($data['kecamata_1'] ?? '') ?>" list="kecamatanList" required>
                        <datalist id="kecamatanList">
                            <?php foreach ($kecamatanList as $kec): ?>
                            <option value="<?= htmlspecialchars($kec) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label for="kelurahan">Kelurahan</label>
                        <input type="text" class="form-control" id="kelurahan" name="kelurahan" value="<?= htmlspecialchars($data['kelurahan_'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="sumber">Sumber</label>
                        <input type="text" class="form-control" id="sumber" name="sumber" value="<?= htmlspecialchars($data['sumber_1'] ?? '') ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="lat">Latitude</label>
                            <input type="number" class="form-control" id="lat" name="lat" step="0.000001" value="<?= htmlspecialchars($data['lat'] ?? '') ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lng">Longitude</label>
                            <input type="number" class="form-control" id="lng" name="lng" step="0.000001" value="<?= htmlspecialchars($data['lng'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($data['deskripsi'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                    <a href="cagar.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Informasi</h3>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> <?= (int)$data['id'] ?></p>
                <p><strong>Dibuat:</strong> <?= htmlspecialchars($data['created_at'] ?? '-') ?></p>
                <p><strong>Diupdate:</strong> <?= htmlspecialchars($data['updated_at'] ?? '-') ?></p>
                <hr>
                <a href="delete_cagar.php?id=<?= (int)$data['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                    <i class="fas fa-trash"></i> Hapus Data
                </a>
            </div>
        </div>

        <?php if (!empty($data['foto'])): ?>
        <div class="card card-primary mt-3">
            <div class="card-header">
                <h3 class="card-title">Foto</h3>
            </div>
            <div class="card-body">
                <img src="../uploads/foto/<?= htmlspecialchars($data['foto']) ?>" class="img-fluid" alt="Foto">
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>