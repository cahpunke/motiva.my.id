<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../auth/login.php");
    exit;
}
include '../config/database.php';

$page = $_GET['page'] ?? 'map';
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard MOTIVA</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<style>
body { overflow-x: hidden; }

.sidebar {
    width: 220px;
    height: 100vh;
    position: fixed;
    background: #212529;
    color: white;
    padding-top: 20px;
}

.sidebar a {
    color: white;
    display: block;
    padding: 10px 20px;
    text-decoration: none;
}

.sidebar a:hover {
    background: #0d6efd;
}

.content {
    margin-left: 220px;
    padding: 20px;
}

.navbar {
    margin-left: 220px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Dashboard MOTIVA</span>
        <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>

<!-- SIDEBAR -->
<div class="sidebar">
    <h5 class="text-center">MENU</h5>

    <a href="?page=map">🗺️ Lihat Map</a>
    <a href="?page=cagar">📍 Data Cagar</a>
    <a href="?page=upload">📥 Import Data</a>
</div>

<!-- CONTENT -->
<div class="content">

<?php if($page == 'map'): ?>

    <h3>Peta Cagar Budaya</h3>
    <div id="map" style="height:500px;"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!--
    <script>
    const map = L.map('map').setView([0.3,109.1],10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    fetch('../api/cagar.php')
    .then(res => res.json())
    .then(data => {
  	let layer = L.geoJSON(data, { pointToLayer: function (feature, latlng) { return L.circleMarker(latlng, { radius: 6, fillColor: "red", color: "#000", weight: 1, fillOpacity: 0.8 }); }, onEachFeature: function (f, l) { l.bindPopup(f.properties.nama ?? 'Tanpa Nama'); } }).addTo(map); if(layer.getBounds().isValid()){ map.fitBounds(layer.getBounds()); } });
    </script>
-->
<script>
const map = L.map('map').setView([0.3,109.1],10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

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

        // 🔥 POPUP LENGKAP
        onEachFeature: function (f, l) {

            let p = f.properties;

            let foto = '';
            if (p.foto && p.foto !== '') {
                foto = `<img src="../uploads/foto/${p.foto}" 
                             style="width:150px; margin-top:5px; border-radius:6px;">`;
            } else {
                foto = `<small style="color:gray">Tidak ada foto</small>`;
            }

            l.bindPopup(`
                <div style="min-width:220px">
                    <b>${p.nama ?? 'Tanpa Nama'}</b><br>

                    <table style="font-size:13px">
                        <tr>
                            <td><b>Kecamatan</b></td>
                            <td>: ${p.kecamatan ?? '-'}</td>
                        </tr>
                        <tr>
                            <td><b>Kelurahan</b></td>
                            <td>: ${p.kelurahan ?? '-'}</td>
                        </tr>
                        <tr>
                            <td><b>Sumber</b></td>
                            <td>: ${p.sumber ?? '-'}</td>
                        </tr>
                    </table>

                    ${foto}
                </div>
            `);
        }

    }).addTo(map);

    if(layer.getBounds().isValid()){
        map.fitBounds(layer.getBounds());
    }

});
</script>



<?php elseif($page == 'cagar'): ?>

    <h3 class="mb-4">📋 Daftar Cagar Budaya</h3>

    <?php
    // 🔥 QUERY FIX + ALIAS (AMAN)
    $sql = "SELECT 
                id,
                destinasi AS nama,
                kecamata_1 AS kecamatan,
                sumber_1 AS sumber,
		kelurahan_ AS kelurahan,
		foto AS Foto
            FROM cagar_budaya_mempawah
            ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "<div class='alert alert-danger'>
                Query Error: " . mysqli_error($conn) . "
              </div>";
    } else {
    ?>

    <table class="table table-bordered table-hover table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th width="80px">ID</th>
                <th>Nama</th>
                <th>Kecamatan</th>
                <th>Sumber</th>
		<th>Kelurahan</th>
		<th>Foto</th>
                <th width="150px">Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($d = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td class="text-center fw-bold"><?= $d['id'] ?></td>
                <td><?= htmlspecialchars($d['nama'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['kecamatan'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['sumber'] ?? '-') ?></td>
                <td><?= htmlspecialchars($d['kelurahan'] ?? '-') ?></td>
		<td class="text-center">
		<?php if(!empty($d['foto'])): ?>
		<img src="../uploads/foto/<?= $d['foto'] ?>" width="80">
		<?php else: ?>
		    <span class="text-muted">-</span>
		<?php endif; ?>
		</td>
                <td class="text-center">
                    <a href="edit_cagar.php?id=<?= $d['id'] ?>" 
                       class="btn btn-sm btn-warning">✏️ Edit</a>
			 <a href="create_cagar.php?id=>"
                       class="btn btn-sm btn-warning">✏️ Tambah</a>
                    <a href="../pages/hapus_cagar.php?id=<?= $d['id'] ?>" 
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
                    <td colspan='6' class='text-center text-muted'>
                        Tidak ada data
                    </td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>

    <?php } ?>


<?php elseif($page == 'upload'): ?>
<!--
    <h3>📥 Import GeoJSON</h3>

    <form method="post" action="../api/proses_import.php" enctype="multipart/form-data">
        <select name="layer" class="form-control mb-2">
            <option value="cagar_budaya">Cagar Budaya</option>
        </select>

        <input type="file" name="geojson" class="form-control mb-2">

        <button class="btn btn-success">Import</button>
    </form>
-->

<?php
// upload_geojson.php
?>

<div class="container">

    <h3 class="mb-3">📥 Import GeoJSON</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="post" action="../api/proses_import.php" enctype="multipart/form-data">
<!--
                <label class="form-label">Pilih Layer</label>
                <select name="table" class="form-control mb-3" required>
                    <option value="cagar_budaya">Cagar Budaya</option>
                    <option value="sungai">Sungai</option>
                    <option value="kecamatan">Kecamatan</option>
                    <option value="kabupaten">Kabupaten</option>
               </select>
-->
                <label class="form-label">Upload File GeoJSON</label>
                <input type="file" name="geojson" class="form-control mb-3" accept=".json,.geojson" required>

                <button class="btn btn-success w-100">🚀 Import Sekarang</button>

            </form>

        </div>
    </div>

</div>
<?php endif; ?>

</div>

</body>
</html>
