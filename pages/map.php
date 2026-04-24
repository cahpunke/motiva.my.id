<!DOCTYPE html>
<html>
<head>
<title>WebGIS Peta</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body { margin:0; font-family:Segoe UI; }
#map { height: 100vh; }

/* PANEL KANAN */
.panel {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 999;
    background: rgba(255,255,255,0.95);
    padding: 15px;
    width: 300px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

/* PANEL STAT */
.panel-stat {
    position: absolute;
    bottom: 10px;
    left: 10px;
    z-index: 999;
    background: rgba(255,255,255,0.95);
    padding: 15px;
    width: 320px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

/* LEGEND */
.legend {
    background: white;
    padding: 10px;
    border-radius: 8px;
    line-height: 18px;
}
.legend i {
    width: 18px;
    height: 18px;
    float: left;
    margin-right: 8px;
    opacity: 0.7;
}

/* STAT */
.stat-box {
    background: linear-gradient(135deg,#0d6efd,#2563eb);
    color:white;
    padding:10px;
    border-radius:10px;
    margin-bottom:10px;
}

#listData {
    max-height:150px;
    overflow:auto;
}
#listData div:hover{
    background:#f1f5f9;
    cursor:pointer;
}
</style>
</head>

<body>

<div id="map"></div>

<!-- PANEL -->
<div class="panel">

<h6>🗺️ Layer</h6>

<div class="form-check">
<input type="checkbox" id="layerKecamatan" checked> Kecamatan
</div>

<div class="form-check mb-3">
<input type="checkbox" id="layerSungai" checked> Sungai
</div>

<hr>

<h6>🔍 Filter Kecamatan</h6>
<select id="searchKecamatan" class="form-select">
<option value="">-- Semua Kecamatan --</option>
</select>

</div>

<!-- PANEL STAT -->
<div class="panel-stat">

<div class="stat-box">
<b>Total Data:</b> <span id="totalData">0</span>
</div>

<canvas id="chartKecamatan"></canvas>

<hr>

<div id="listData">
<b>Klik kecamatan di peta</b>
</div>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// INIT MAP
const map = L.map('map').setView([0.3,109.1],10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// GLOBAL
let layerKecamatan, layerSungai;
let kecamatanData = [];
let chart;

// ================= COLOR =================
function getColor(d){
    return d > 20 ? '#800026' :
           d > 10 ? '#BD0026' :
           d > 5  ? '#E31A1C' :
           d > 2  ? '#FC4E2A' :
           d > 0  ? '#FD8D3C' :
                    '#FFEDA0';
}

// ================= LOAD KECAMATAN =================
function loadKecamatan(){

fetch('../api/kecamatan.php')
.then(res=>res.json())
.then(data=>{

    if(layerKecamatan) map.removeLayer(layerKecamatan);

    kecamatanData = data.features || [];

    layerKecamatan = L.geoJSON(data,{

        style:f=>{
            let nama = f.properties.kecamatan || '';
            return { fillColor:'#4da6ff', weight:1.5, color:'#333', fillOpacity:0.6 };
        },

        onEachFeature:(f,l)=>{

            let nama = f.properties.kecamatan || '-';

            l.bindPopup(`<b>${nama}</b>`);

            l.on('click',()=>{
                showList(nama);
            });
        }

    }).addTo(map);

    updateDashboard();
    loadDropdownKecamatan();
});
}

// ================= LOAD SUNGAI =================
function loadSungai(){
fetch('../api/sungai.php')
.then(res=>res.json())
.then(data=>{
    if(layerSungai) map.removeLayer(layerSungai);
    layerSungai = L.geoJSON(data,{style:{color:"blue",weight:2}}).addTo(map);
});
}

// ================= LIST =================
function showList(nama){

    let html = `<b>${nama}</b><hr>`;

    document.getElementById("listData").innerHTML = html;
}

// ================= DROPDOWN =================
function loadDropdownKecamatan(){

    let select = document.getElementById("searchKecamatan");
    select.innerHTML = '<option value="">-- Semua Kecamatan --</option>';

    let list = [];

    kecamatanData.forEach(f=>{
        let nama = f.properties.kecamatan;
        if(nama && !list.includes(nama)) list.push(nama);
    });

    list.sort();

    list.forEach(nama=>{
        let opt = document.createElement("option");
        opt.value = nama;
        opt.textContent = nama;
        select.appendChild(opt);
    });
}

// ================= FILTER =================
document.getElementById("searchKecamatan").onchange = function(){

    let kec = this.value.toLowerCase();

    if(!kec){
        updateDashboard();
        return;
    }

    let filtered = kecamatanData.filter(f =>
        (f.properties.kecamatan||'').toLowerCase().includes(kec)
    );

    showList(this.value);
};

// ================= DASHBOARD =================
function updateDashboard(){

    document.getElementById("totalData").innerText = kecamatanData.length;

    let kec = {};
    kecamatanData.forEach(f=>{
        let k = f.properties.kecamatan || '-';
        kec[k] = (kec[k]||0)+1;
    });

    renderChart(kec);
}

// ================= CHART =================
function renderChart(data){

    const ctx = document.getElementById('chartKecamatan');

    if(chart) chart.destroy();

    chart = new Chart(ctx,{
        type:'bar',
        data:{
            labels:Object.keys(data),
            datasets:[{label:'Jumlah',data:Object.values(data)}]
        }
    });
}

// ================= LEGEND =================
let legend = L.control({position:'bottomright'});

legend.onAdd = function(){
    let div = L.DomUtil.create('div','legend');
    let grades=[0,1,3,6,11,21];

    for(let i=0;i<grades.length;i++){
        div.innerHTML +=
            '<i style="background:'+getColor(grades[i])+'"></i> '+
            grades[i]+(grades[i+1]?'–'+grades[i+1]+'<br>':'+');
    }
    return div;
};

legend.addTo(map);

// ================= EVENT =================
document.getElementById('layerKecamatan').onchange = e=>{
    e.target.checked ? loadKecamatan() : map.removeLayer(layerKecamatan);
};
document.getElementById('layerSungai').onchange = e=>{
    e.target.checked ? loadSungai() : map.removeLayer(layerSungai);
};

// ================= AUTO LOAD =================
window.onload = function(){
    loadKecamatan();
    loadSungai();
};
</script>

</body>
</html>
