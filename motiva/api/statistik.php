<?php
include '../config/database.php';
$q=mysqli_query($conn,"SELECT kategori, COUNT(*) as jumlah FROM cagar_budaya GROUP BY kategori");
$d=[]; while($r=mysqli_fetch_assoc($q)){$d[]=$r;}
echo json_encode($d);
?>