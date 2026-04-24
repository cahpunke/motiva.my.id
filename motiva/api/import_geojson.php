<?php
include '../config/database.php';
$layer=$_POST['layer'];
$file=$_FILES['geojson']['tmp_name'];
$data=json_decode(file_get_contents($file),true);
foreach($data['features'] as $f){
  $geom=mysqli_real_escape_string($conn,json_encode($f['geometry']));
  $nama=$f['properties']['nama']??$f['properties']['NAME']??'Tanpa Nama';
  mysqli_query($conn,"INSERT INTO $layer(nama,geom)VALUES('$nama','$geom')");
}
echo "Import sukses";
?>