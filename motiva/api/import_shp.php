<?php
$dir="../uploads/shp/";
$name=$_FILES['shp']['name'];
$tmp=$_FILES['shp']['tmp_name'];
move_uploaded_file($tmp,$dir.$name);

$input=$dir.$name;
$output=$dir."converted.geojson";

// membutuhkan GDAL (ogr2ogr)
exec("ogr2ogr -f GeoJSON $output $input");

// kirim ke importer
$_FILES['geojson']['tmp_name']=$output;
$_POST['layer']=$_POST['layer'] ?? 'cagar_budaya';
include 'import_geojson.php';
?>