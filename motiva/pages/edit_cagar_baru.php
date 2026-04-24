<?php
// modules/cagar/edit_cagar.php
include '../config/database.php';
//include '../templates/header.php';
//include '../templates/sidebar.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<div class='alert alert-danger'>ID tidak valid.</div>";
    exit;
}

$sql = "SELECT * FROM cagar_budaya_mempawah WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Edit Cagar Budaya</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<style>
body {
    background: #f4f6f9;
    font-family: 'Segoe UI', sans-serif;
}

/* Sidebar */
.sidebar {
    width: 230px;
    height: 100vh;
    position: fixed;
    background: #1e293b;
    color: white;
    padding-top: 20px;
}

.sidebar a {
    color: #cbd5e1;
    display: block;
    padding: 12px 20px;
    text-decoration: none;
}

.sidebar a:hover {
    background: #0d6efd;
    color: white;
}

/* Content */
.content {
    margin-left: 230px;
    padding: 30px;
}

/* Navbar */
.navbar {
    margin-left: 230px;
}

/* Card */
.card-form {
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    border: none;
}

/* Map */
#map {
    height: 300px;
    border-radius: 10px;
}

/* Image */
.preview-img {
    max-width: 180px;
    border-radius: 10px;
    margin-bottom: 10px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">🌐 Dashboard MOTIVA</span>
    <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
</nav>

<!-- SIDEBAR -->
<div class="sidebar">
    <h5 class="text-center mb-4">MENU</h5>
    <a href="dashboard.php?page=map">🗺️ Lihat Map</a>
    <a href="dashboard.php?page=cagar">📍 Data Cagar</a>
    <a href="dashboard.php?page=import">📥 Import Data</a>
</div>

<!-- CONTENT -->
<div class="content">

<div class="card card-form p-4">
<h4 class="mb-4">✏️ Edit Cagar Budaya</h4>

<form method="post" enctype="multipart/form-data">

<div class="row">

<!-- ================= FORM ================= -->
<div class="col-md-7">

<div class="mb-3">
<label>Destinasi</label>
<input type="text" name="destinasi" class="form-control"
value="<?= $data['destinasi'] ?? '' ?>" required>
</div>

<div class="mb-3">
<label>Jenis</label>
<input type="text" name="jenis" class="form-control"
value="<?= $data['jenis'] ?? '' ?>">
</div>

<div class="mb-3">
<label>Kelurahan</label>
<input type="text" name="kelurahan_" class="form-control"
value="<?= $data['kelurahan_'] ?? '' ?>">
</div>

<div class="mb-3">
<label>Kecamatan</label>
<input type="text" name="kecamata_1" class="form-control"
value="<?= $data['kecamata_1'] ?? '' ?>">
</div>

<div class="mb-3">
<label>Kabupaten</label>
<input type="text" name="kab_kota" class="form-control"
value="<?= $data['kab_kota'] ?? '' ?>">
</div>

<div class="mb-3">
<label>Provinsi</label>
<input type="text" name="provinsi_1" class="form-control"
value="<?= $data['provinsi_1'] ?? '' ?>">
</div>

<div class="mb-3">
<label>Sumber</label>
<input type="text" name="sumber_1" class="form-control"
value="<?= $data['sumber_1'] ?? '' ?>">
</div>

<div class="row">
<div class="col-md-6">
<label>Latitude</label>
<input type="text" id="lat" name="lat" class="form-control"
value="<?= $data['lat'] ?? '' ?>">
</div>

<div class="col-md-6">
<label>Longitude</label>
<input type="text" id="lng" name="lng" class="form-control"
value="<?= $data['lng'] ?? '' ?>">
</div>
</div>

<!-- FOTO -->
<div class="mt-3">
<label>Foto</label><br>

<?php if(!empty($data['foto'])): ?>
<img src="../uploads/foto/<?= $data['foto'] ?>" class="preview-img">
<?php endif; ?>

<img id="previewNew" class="preview-img d-none">

<input type="file" name="foto" class="form-control mt-2" onchange="previewImage(event)">
</div>

<div class="mt-4 d-flex justify-content-between">
<a href="dashboard.php?page=cagar" class="btn btn-secondary">← Kembali</a>
<button type="submit" name="update" class="btn btn-primary">💾 Simpan</button>
</div>

</div>

<!-- ================= MAP ================= -->
<div class="col-md-5">
<label>Pilih Lokasi di Map</label>
<div id="map"></div>
<small>Klik map untuk isi koordinat</small>
</div>

</div>

</form>
</div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// INIT MAP
const lat = <?= $data['lat'] ?? 0.3 ?>;
const lng = <?= $data['lng'] ?? 109 ?>;

const map = L.map('map').setView([lat, lng], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker = L.marker([lat, lng]).addTo(map);

// klik map
map.on('click', function(e){
    document.getElementById('lat').value = e.latlng.lat;
    document.getElementById('lng').value = e.latlng.lng;
    marker.setLatLng(e.latlng);
});

// preview gambar
function previewImage(event){
    const file = event.target.files[0];
    const preview = document.getElementById('previewNew');

    if(file){
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    }
}
</script>

</body>
</html>
