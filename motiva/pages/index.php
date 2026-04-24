<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Database;
use Config\Auth;

$pageTitle = 'Dashboard';

include __DIR__ . '/../layout/header.php';

$db = new Database();
$conn = $db->getConnection();

// Get statistics
$totalCagar = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM cagar_budaya_mempawah")
)['total'];

$totalKecamatan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(DISTINCT kecamata_1) as total FROM cagar_budaya_mempawah")
)['total'];

// Get data by kecamatan
$kecamatanData = [];
$result = mysqli_query($conn, "SELECT kecamata_1, COUNT(*) as jumlah FROM cagar_budaya_mempawah GROUP BY kecamata_1 ORDER BY jumlah DESC LIMIT 5");
while ($row = mysqli_fetch_assoc($result)) {
    $kecamatanData[] = $row;
}
?>

<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $totalCagar ?></h3>
                <p>Total Cagar Budaya</p>
            </div>
            <div class="icon">
                <i class="fas fa-landmark"></i>
            </div>
            <a href="cagar.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $totalKecamatan ?></h3>
                <p>Total Kecamatan</p>
            </div>
            <div class="icon">
                <i class="fas fa-map"></i>
            </div>
            <a href="map.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="dataWithFoto">0</h3>
                <p>Cagar dengan Foto</p>
            </div>
            <div class="icon">
                <i class="fas fa-images"></i>
            </div>
            <a href="upload.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="dataWithoutFoto">0</h3>
                <p>Cagar tanpa Foto</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="cagar.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Top 5 Kecamatan</h3>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <p class="d-flex flex-column">
                        <span class="text-bold text-lg">Distribusi Cagar Budaya</span>
                    </p>
                </div>
                <div style="position: relative; height: 300px;">
                    <canvas id="chartKecamatan"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Quick Info</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Cagar</span>
                    <span class="badge badge-primary"><?= $totalCagar ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Kecamatan</span>
                    <span class="badge badge-success"><?= $totalKecamatan ?></span>
                </div>
                <hr>
                <a href="cagar.php" class="btn btn-sm btn-primary">Lihat Data</a>
                <a href="map.php" class="btn btn-sm btn-success">Lihat Peta</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
$(document).ready(function() {
    // Load data dengan fetch
    fetch('../api/cagar.php')
        .then(res => res.json())
        .then(data => {
            let withFoto = 0;
            let withoutFoto = 0;
            let kecData = {};
            
            data.features.forEach(f => {
                if (f.properties.foto) {
                    withFoto++;
                } else {
                    withoutFoto++;
                }
                
                let kec = f.properties.kecamatan || 'Unknown';
                kecData[kec] = (kecData[kec] || 0) + 1;
            });
            
            $('#dataWithFoto').text(withFoto);
            $('#dataWithoutFoto').text(withoutFoto);
            
            // Chart
            const labels = Object.keys(kecData).sort((a, b) => kecData[b] - kecData[a]).slice(0, 5);
            const values = labels.map(k => kecData[k]);
            
            const ctx = document.getElementById('chartKecamatan').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Cagar',
                        data: values,
                        backgroundColor: '#0d6efd',
                        borderColor: '#0d6efd',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        })
        .catch(err => console.error('Error loading data:', err));
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>