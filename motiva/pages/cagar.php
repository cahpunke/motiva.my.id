<?php
include '../config/database.php';
$q = mysqli_query($conn, "SELECT * FROM cagar_budaya");
?>

<h2>Data Cagar Budaya</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Foto</th>
        <th>Aksi</th>
    </tr>

    <?php while($d = mysqli_fetch_assoc($q)): ?>
    <tr>
        <td><?= $d['id'] ?></td>
        <td><?= $d['destinasi'] ?? $d['nama'] ?></td>
        <td><?= $d['jenis'] ?? $d['kategori'] ?></td>

        <td>
            <?php if($d['foto']): ?>
                <img src="../uploads/foto/<?= $d['foto'] ?>" width="80">
            <?php else: ?>
                Tidak ada
            <?php endif; ?>
        </td>

        <td>
            <a href="edit_cagar.php?id=<?= $d['id'] ?>">Edit</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
