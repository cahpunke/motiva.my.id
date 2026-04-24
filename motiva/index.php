<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>MOTIVA</title>

    <!-- 🔥 WAJIB MOBILE -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- LEAFLET -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

    <style>
        #map { height: 400px; }

        .hero {
            background: linear-gradient(to right, #0d6efd, #00c6ff);
            color: white;
            padding: 60px;
            text-align: center;
        }
    </style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">

    <a class="navbar-brand" href="#" 
       style="font-family: 'Montserrat', sans-serif; 
              font-size: 2.2rem; 
              font-weight: 800; 
              letter-spacing: 8px;">
        M O T I V A
    </a>

    <!-- 🔥 FIX TOGGLER -->
    <button class="navbar-toggler" type="button"
        data-bs-toggle="collapse"
        data-bs-target="#menu"
        aria-controls="menu"
        aria-expanded="false"
        aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- MENU -->
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item">
          <a class="nav-link active" href="index.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="pages/sebaran.php">Sebaran Cagar Budaya</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="pages/motif.php">Kumpulan Motif</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="pages/ekraf.php">Ekraf</a>
        </li>

        <li class="nav-item">
          <a href="auth/login.php" class="btn btn-warning ms-2 mt-2 mt-lg-0">Login</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- ================= HERO ================= -->
<div class="hero">
    <h1>Penciptaan Karya Kreatif Inovatif</h1>
    <p>“Dari Peta ke Cerita, dari Budaya ke Pengalaman”</p>
</div>

<!-- ================= TENTANG ================= -->
<div class="container mt-5">
    <h2 class="text-center fw-bold mb-4">Tentang MOTIVA</h2>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <p class="lead">
                MOTIVA merupakan platform berbasis Sistem Informasi Geografis (SIG) 
                yang menyajikan informasi spasial cagar budaya di Kabupaten Mempawah.
            </p>

            <p>
                Platform ini mendukung pelestarian, dokumentasi, dan promosi 
                warisan budaya melalui WebGIS.
            </p>

            <p>
                Dilengkapi teknologi <strong>Augmented Reality (AR)</strong> 
                untuk pengalaman visual yang lebih interaktif.
            </p>
        </div>
    </div>
</div>

<!-- ================= MAP ================= -->
<div class="container mt-4">
    <h3 class="text-center">Peta Cagar Budaya</h3>
    <div id="map"></div>
</div>

<!-- ================= FITUR ================= -->
<div class="container mt-4">
    <div class="row text-center">

        <div class="col-md-4">
            <h3>📍 Lokasi</h3>
            <p>Menampilkan titik cagar budaya</p>
        </div>

        <div class="col-md-4">
            <h3>🗂️ Data</h3>
            <p>Berbasis GeoJSON & SHP</p>
        </div>

        <div class="col-md-4">
            <h3>📊 Statistik</h3>
            <p>Analisis wilayah</p>
        </div>

    </div>
</div>

<!-- ================= FOOTER ================= -->
<footer class="text-center mt-5 p-3 bg-dark text-white">
    © 2026 WebGIS Mempawah
</footer>

<!-- ================= SCRIPT ================= -->

<!-- LEAFLET -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// INIT MAP
const map = L.map('map').setView([0.3, 109.1], 10);

// BASEMAP
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// LOAD DATA
// LOAD DATA
fetch('../api/cagar.php')
.then(res => res.json())
.then(data => {

    let layer = L.geoJSON(data, {
        pointToLayer: function (feature, latlng) {
            return L.circleMarker(latlng, {
                radius: 6,
                fillColor: "red",
                color: "#000",
                weight: 1,
                fillOpacity: 0.8
            });
        },

        onEachFeature: function (feature, layer) {

            let p = feature.properties;

            let foto = p.foto 
                ? `<img src="uploads/foto/${p.foto}" 
                        style="width:100%;margin-top:8px;border-radius:8px;">`
                : `<small style="color:gray">Tidak ada foto</small>`;

            layer.bindPopup(`
                <div style="min-width:200px">
                    <b style="font-size:14px">${p.nama || p.destinasi || '-'}</b><br>

                    <small>
                        Kecamatan: ${p.kecamatan || '-'}<br>
                        Jenis: ${p.jenis || '-'}
                    </small>

                    ${foto}
                </div>
            `);
        }

    }).addTo(map);

    map.fitBounds(layer.getBounds());
});
</script>

<!-- 🔥 INI YANG MEMPERBAIKI MENU MOBILE -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
