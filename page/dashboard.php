<?php
$query = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM produk"
);

$total = 0;

if ($query) {
    $data = mysqli_fetch_assoc($query);
    $total = $data["total"];
}
?>

<h2 class="mb-3">Dashboard</h2>
<hr>
<h4>Selamat Datang di ZaRamart</h4>
<p>Ini adalah halaman utama toko.</p>

<div class="card shadow-sm mt-4" style="max-width: 300px;">
    <div class="card-body">
        <h5>Total Produk</h5>
        <h2><?= $total ?></h2>
        <a href="index.php?page=produk" class="btn btn-primary btn-sm"> Lihat Produk.</a>
    </div>
</div>
