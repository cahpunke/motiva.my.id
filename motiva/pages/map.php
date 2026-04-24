<!DOCTYPE html><html>
<head>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
</head>
<body>
<h3>WebGIS FINAL PRO</h3>
<div id="map" style="height:500px;"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const map=L.map('map').setView([-0.4,109.2],11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

function load(url,style,popup){
return fetch(url).then(r=>r.json()).then(d=>{
return L.geoJSON(d,{
style:style,
onEachFeature:popup
});
});
}

function popupCagar(f,l){
let p=f.properties;
let foto=p.foto?'<img src="../uploads/foto/'+p.foto+'" width="100%">':'';
l.bindPopup('<b>'+p.nama+'</b><br>'+foto);
}

Promise.all([
load('../api/cagar.php',{color:'red'},popupCagar),
load('../api/kecamatan.php',{color:'blue'}),
load('../api/sungai.php',{color:'cyan'}),
load('../api/kabupaten.php',{color:'green'})
]).then(([cagar,kecamatan,sungai,kabupaten])=>{
let maps={"Cagar":cagar,"Kecamatan":kecamatan,"Sungai":sungai,"Kabupaten":kabupaten};
L.control.layers(null,maps).addTo(map);
kabupaten.addTo(map);
});
</script>
</body></html>