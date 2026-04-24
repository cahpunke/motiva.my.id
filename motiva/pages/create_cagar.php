<!DOCTYPE html>
<html>
<head>
<title>Tambah Cagar Budaya</title>

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
}

/* Map */
#map {
    height: 320px;
    border-radius: 10px;
}

/* Preview */
.preview-img {
    max-width: 180px;
    border-radius: 10px;
    margin-top: 10px;
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
    <a href="../pages/dashboard.php?page=map">🗺️ Lihat Map</a>
    <a href="../pages/dashboard.php?page=cagar">📍 Data Cagar</a>
    <a href="../pages/dashboard.php?page=import">📥 Import Data</a>
</div>

<!-- CONTENT -->
<div class="content">

<div class="card card-form p-4">
<h4 class="mb-4">➕ Tambah Data Cagar Budaya</h4>

<form method="POST" action="proses_tambah.php" enctype="multipart/form-data">

<div class="row">

<!-- FORM -->
<div class="col-md-7">

<div class="mb-3">
<label>Destinasi</label>
<input type="text" name="destinasi" class="form-control" required>
</div>

<div class="mb-3">
<label>Jenis</label>
<input type="text" name="jenis" class="form-control">
</div>

<div class="mb-3">
<label>Kelurahan</label>
<input type="text" name="kelurahan_" class="form-control">
</div>

<div class="mb-3">
<label>Kecamatan</label>
<input type="text" name="kecamata_1" class="form-control">
</div>

<div class="mb-3">
<label>Kabupaten</label>
<input type="text" name="kab_kota" class="form-control">
</div>

<div class="mb-3">
<label>Provinsi</label>
<input type="text" name="provinsi_1" class="form-control">
</div>

<div class="mb-3">
<label>Sumber</label>
<input type="text" name="sumber_1" class="form-control">
</div>

<div class="row">
<div class="col-md-6">
<label>Latitude</label>
<input type="text" id="lat" name="lat" class="form-control" placeholder="-0.123456">
</div>

<div class="col-md-6">
<label>Longitude</label>
<input type="text" id="lng" name="lng" class="form-control" placeholder="109.123456">
</div>
</div>

<!-- FOTO -->
<div class="mt-3">
<label>Foto</label>
<input type="file" name="foto" class="form-control" accept="image/*" onchange="previewImage(event)">
<small class="text-muted">Format JPG/PNG, max 5MB</small>

<img id="preview" class="preview-img d-none">
</div>

<div class="mt-4 d-flex justify-content-between">
<a href="../pages/dashboard.php?page=cagar" class="btn btn-secondary">← Batal</a>
<button type="submit" class="btn btn-success">✅ Simpan Data</button>
</div>

</div>

<!-- MAP -->
<div class="col-md-5">
<label>Pilih Lokasi</label>
<div id="map"></div>
<small>Klik map untuk mengisi koordinat</small>
</div>

</div>

</form>
</div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// INIT MAP
const map = L.map('map').setView([0.35, 109.0], 10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker;

// klik map
map.on('click', function(e){
    document.getElementById('lat').value = e.latlng.lat;
    document.getElementById('lng').value = e.latlng.lng;

    if(marker){
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng).addTo(map);
    }
});

// preview gambar
function previewImage(event){
    const file = event.target.files[0];
    const preview = document.getElementById('preview');

    if(file){
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    }
}
</script>

</body>
</html>
