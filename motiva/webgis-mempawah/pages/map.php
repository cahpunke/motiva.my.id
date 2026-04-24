<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css"/>

<div id="map" style="height: 500px;"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

<script>
const map = L.map('map').setView([-0.4, 109.2], 11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
.addTo(map);

// DRAW
const drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

const drawControl = new L.Control.Draw({
    edit: { featureGroup: drawnItems }
});
map.addControl(drawControl);

// SAVE HASIL DIGITASI
map.on(L.Draw.Event.CREATED, function (e) {
    const layer = e.layer;
    drawnItems.addLayer(layer);

    const geojson = layer.toGeoJSON();

    fetch('../api/save_geojson.php', {
        method: 'POST',
        body: JSON.stringify(geojson)
    });
});

// LOAD DATA
fetch('../api/cagar.php')
.then(res => res.json())
.then(data => {
    L.geoJSON(data).addTo(map);
});
</script>
