<!DOCTYPE html>
<html>
<head>
    <title>Upload GeoJSON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Upload GeoJSON → Auto Import ke MySQL</h3>

    <form action="../pages/proses_import.php" method="POST" enctype="multipart/form-data">
        
        <div class="mb-3">
            <label class="form-label">Pilih File GeoJSON</label>
            <input type="file" name="geojson" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Tabel</label>
            <input type="text" name="table" class="form-control" placeholder="contoh: cagar_budaya" required>
        </div>

        <button type="submit" class="btn btn-primary">Import</button>

    </form>
</div>

</body>
</html>
