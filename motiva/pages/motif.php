<!DOCTYPE html>
<html>
<head>
<title>Kumpulan Motif</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
<h2>Kumpulan Motif</h2>

<div class="row">

<?php
$files = glob("../uploads/motif/*");
foreach($files as $f):
?>

<div class="col-md-3 mb-3">
    <img src="<?= $f ?>" class="img-fluid">
</div>

<?php endforeach; ?>

</div>
</div>

</body>
</html>
