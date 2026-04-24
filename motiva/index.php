<?php
/**
 * MOTIVA WebGIS - Main Public Index
 * This is the public homepage of the application
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOTIVA - WebGIS Cagar Budaya Mempawah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, #00c6ff 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,.1); }
        #map { height: 400px; border-radius: 8px; }
        .card { border: none; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: 30px; }
        .card-title { color: var(--primary); font-weight: 600; }
        .btn-dashboard {
            background: linear-gradient(135deg, var(--primary) 0%, #0a58ca 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }
        .btn-dashboard:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
        footer {
            background: #2c3e50;
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-map-location-dot" style="color: var(--primary);"></i>
                <strong>MOTIVA</strong> WebGIS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="pages/map.php">Peta</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                    <li class="nav-item">
                        <a href="pages/login.php" class="btn btn-primary btn-sm ms-2">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">MOTIVA WebGIS</h1>
            <p class="lead mb-4">Sistem Informasi Geografis Cagar Budaya Kabupaten Mempawah</p>
            <p class="mb-4">"Dari Peta ke Cerita, dari Budaya ke Pengalaman"</p>
            <a href="pages/map.php" class="btn btn-dashboard btn-lg me-2">Lihat Peta</a>
            <a href="pages/login.php" class="btn btn-light btn-lg">Kelola Data</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <!-- About Section -->
        <section id="tentang" class="mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="display-5 fw-bold mb-4">Tentang MOTIVA</h2>
                    <p class="lead text-muted">
                        MOTIVA merupakan platform berbasis Sistem Informasi Geografis (SIG) yang menyajikan informasi 
                        spasial cagar budaya di Kabupaten Mempawah secara komprehensif dan interaktif.
                    </p>
                    <p class="text-muted">
                        Platform ini mendukung pelestarian, dokumentasi, dan promosi warisan budaya melalui teknologi 
                        WebGIS yang modern. Dilengkapi dengan Augmented Reality (AR) untuk pengalaman visual yang lebih 
                        interaktif dan engaging.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div id="mapPreview" style="height: 350px; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,.2);"></div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="fitur" class="my-5 py-5">
            <h2 class="text-center display-5 fw-bold mb-5">Fitur Utama</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-map"></i>
                            </div>
                            <h5 class="card-title">Peta Interaktif</h5>
                            <p class="text-muted">Visualisasi sebaran cagar budaya dengan peta interaktif berbasis Leaflet</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-landmark"></i>
                            </div>
                            <h5 class="card-title">Data Komprehensif</h5>
                            <p class="text-muted">Database lengkap dengan foto, deskripsi, dan informasi geografis setiap lokasi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h5 class="card-title">Analisis Statistik</h5>
                            <p class="text-muted">Visualisasi data dengan chart dan analisis distribusi per kecamatan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-filter"></i>
                            </div>
                            <h5 class="card-title">Filter & Pencarian</h5>
                            <p class="text-muted">Cari dan filter data berdasarkan lokasi, jenis, dan kriteria lainnya</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <h5 class="card-title">Dokumentasi Foto</h5>
                            <p class="text-muted">Upload dan kelola dokumentasi visual setiap cagar budaya</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h5 class="card-title">Responsif Mobile</h5>
                            <p class="text-muted">Akses dari berbagai perangkat dengan antarmuka yang responsif</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="my-5 py-5">
            <h2 class="text-center display-5 fw-bold mb-5">Peta Cagar Budaya</h2>
            <div id="map"></div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">MOTIVA WebGIS</h5>
                    <p class="text-white-50">Sistem Informasi Geografis Cagar Budaya Kabupaten Mempawah</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold">Links</h6>
                    <ul class="list-unstyled text-white-50">
                        <li><a href="pages/map.php" class="text-white-50 text-decoration-none">Lihat Peta</a></li>
                        <li><a href="pages/login.php" class="text-white-50 text-decoration-none">Login</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="fw-bold">Contact</h6>
                    <p class="text-white-50">Email: admin@motiva.local<br>Kabupaten Mempawah</p>
                </div>
            </div>
            <hr class="text-white-50">
            <div class="text-center text-white-50">
                <p>&copy; 2026 MOTIVA WebGIS. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Initialize map preview
        const mapPreview = L.map('mapPreview').setView([0.3, 109.1], 9);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapPreview);
        
        // Load data
        fetch('pages/api/cagar.php')
            .then(res => res.json())
            .then(data => {
                L.geoJSON(data, {
                    pointToLayer: (f, latlng) => L.circleMarker(latlng, {
                        radius: 6,
                        fillColor: '#0d6efd',
                        color: '#fff',
                        fillOpacity: 0.8
                    })
                }).addTo(mapPreview);
            })
            .catch(err => console.error('Error loading map:', err));
    </script>
</body>
</html>