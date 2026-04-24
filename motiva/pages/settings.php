<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Auth;
use Config\Security;

$pageTitle = 'Settings';

include __DIR__ . '/../layout/header.php';

$success = '';
$error = '';
$csrfToken = Security::generateCSRFToken();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'CSRF token tidak valid';
    } else {
        // Here you can save settings
        $success = 'Pengaturan berhasil disimpan';
    }
}

?>

<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Pengaturan Sistem</h3>
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
                        <label for="appName">Nama Aplikasi</label>
                        <input type="text" class="form-control" id="appName" name="app_name" value="MOTIVA WebGIS">
                    </div>

                    <div class="form-group">
                        <label for="appEmail">Email Aplikasi</label>
                        <input type="email" class="form-control" id="appEmail" name="app_email" value="admin@motiva.local">
                    </div>

                    <div class="form-group">
                        <label for="sessionTimeout">Session Timeout (detik)</label>
                        <input type="number" class="form-control" id="sessionTimeout" name="session_timeout" value="3600">
                    </div>

                    <hr>

                    <h5>Database</h5>
                    <div class="form-group">
                        <label>Host</label>
                        <input type="text" class="form-control" value="localhost" disabled>
                    </div>

                    <div class="form-group">
                        <label>Database</label>
                        <input type="text" class="form-control" value="webgis_mempawah" disabled>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>