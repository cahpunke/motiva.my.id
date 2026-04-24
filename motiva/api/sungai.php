<?php
include '../config/database.php';
$q=mysqli_query($conn,"SELECT * FROM sungai");
$f=[];
while($r=mysqli_fetch_assoc($q)){
  $f[]=["type"=>"Feature","geometry"=>json_decode($r['geometry']),
  "properties"=>["nama"=>$r['nama']]];
}
echo json_encode(["type"=>"FeatureCollection","features"=>$f]);
?>
