<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Config\Auth;

$pageTitle = 'Peta Cagar Budaya';

include __DIR__ . '/../layout/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Pemetaan Cagar Budaya</h3>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 500px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kontrol Layer</h3>
            </div>
            <div class="card-body">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="layerCagar" checked>
                    <label class="custom-control-label" for="layerCagar">Cagar Budaya</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="layerKecamatan" checked>
                    <label class="custom-control-label" for="layerKecamatan">Kecamatan</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="layerSungai" checked>
                    <label class="custom-control-label" for="layerSungai">Sungai</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter</h3>
            </div>
            <div class="card-body">
                <label>Pilih Kecamatan</label>
                <select id="searchKecamatan" class="form-control">
                    <option value="">-- Semua Kecamatan --</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
let map, layerCagar, layerKecamatan, layerSungai;
let cagarData = [];

$(document).ready(function() {
    initMap();
});

function initMap() {
    map = L.map('map').setView([0.3, 109.1], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
    loadCagar();
    loadKecamatan();
    loadSungai();
}

function loadCagar() {
    fetch('../api/cagar.php')
        .then(res => res.json())
        .then(data => {
            cagarData = data.features;
            renderCagar(cagarData);
            loadDropdownKecamatan();
        });
}

function renderCagar(data) {
    if (layerCagar) map.removeLayer(layerCagar);
    
    layerCagar = L.geoJSON({
        type: "FeatureCollection",
        features: data
    }, {
        pointToLayer: (f, latlng) => L.circleMarker(latlng, {
            radius: 6,
            fillColor: "#e11d48",
            color: "#fff",
            fillOpacity: 0.9
        }),
        onEachFeature: (f, l) => {
            let p = f.properties;
            let foto = p.foto ? `<img src="../uploads/foto/${p.foto}" style="width:100%;border-radius:8px;margin-top:8px;">` : '<small style="color:gray">Tidak ada foto</small>';
            
            l.bindPopup(`
                <div style="min-width:250px">
                    <h6>${p.nama || '-'}</h6>
                    <table style="font-size:13px">
                        <tr><td><b>Jenis</b></td><td>: ${p.jenis || '-'}</td></tr>
                        <tr><td><b>Kecamatan</b></td><td>: ${p.kecamatan || '-'}</td></tr>
                        <tr><td><b>Kelurahan</b></td><td>: ${p.kelurahan || '-'}</td></tr>
                    </table>
                    ${foto}
                </div>
            `);
        }
    }).addTo(map);
}

function loadKecamatan() {
    fetch('../api/kecamatan.php')
        .then(res => res.json())
        .then(data => {
            if (layerKecamatan) map.removeLayer(layerKecamatan);
            
            layerKecamatan = L.geoJSON(data, {
                style: f => {
                    return { fillColor: '#0d6efd', weight: 1.5, color: '#333', fillOpacity: 0.3 };
                },
                onEachFeature: (f, l) => {
                    let nama = f.properties.kecamatan || '-';
                    l.bindPopup(`<b>${nama}</b>`);
                }
            }).addTo(map);
        });
}

function loadSungai() {
    fetch('../api/sungai.php')
        .then(res => res.json())
        .then(data => {
            if (layerSungai) map.removeLayer(layerSungai);
            layerSungai = L.geoJSON(data, {style: {color: "blue", weight: 2}}).addTo(map);
        });
}

function loadDropdownKecamatan() {
    let select = document.getElementById("searchKecamatan");
    let list = [];
    
    cagarData.forEach(f => {
        let nama = f.properties.kecamatan;
        if (nama && !list.includes(nama)) list.push(nama);
    });
    
    list.sort().forEach(nama => {
        let opt = document.createElement("option");
        opt.value = nama;
        opt.textContent = nama;
        select.appendChild(opt);
    });
}

document.getElementById('layerCagar').onchange = e => {
    e.target.checked ? loadCagar() : map.removeLayer(layerCagar);
};

document.getElementById('layerKecamatan').onchange = e => {
    e.target.checked ? loadKecamatan() : map.removeLayer(layerKecamatan);
};

document.getElementById('layerSungai').onchange = e => {
    e.target.checked ? loadSungai() : map.removeLayer(layerSungai);
};

document.getElementById('searchKecamatan').onchange = function() {
    let kec = this.value.toLowerCase();
    
    if (!kec) {
        renderCagar(cagarData);
        return;
    }
    
    let filtered = cagarData.filter(f => (f.properties.kecamatan || '').toLowerCase().includes(kec));
    renderCagar(filtered);
};
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>