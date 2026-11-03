<?php
$query = mysqli_query(
    $koneksi,
    "SELECT id, nama_barang, harga, stok FROM produk ORDER BY id ASC"
);
?>

<div class="d-flex flex-wrap justify-content-between
            align-items-center gap-2 mb-3">
    <div>
        <h2 class="h4 mb-1">Data Produk</h2>
        <p class="text-secondary mb-0"> Daftar produk yang tersimpan. </p>
    </div>

    <a href="index.php?page=tambah_produk" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>Tambah Produk
    </a>
</div>

<?php if (!$query): ?>
    <div class="alert alert-danger">
        Gagal mengambil data:
        <?= htmlspecialchars(mysqli_error($koneksi)) ?>
    </div>

<?php else: ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th style="min-width: 155px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php $no = 1; ?>

                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row["id"]) ?></td>
                        <td>
                            <?= htmlspecialchars($row["nama_barang"]) ?>
                        </td>
                        <td>
                            Rp <?= number_format(
                                (float)$row["harga"], 0, ",", "."
                            ) ?>
                        </td>
                        <td><?= htmlspecialchars($row["stok"]) ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="index.php?page=edit_produk&id=<?= (int)$row["id"] ?>"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <form action="hapus_produk.php"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')">

                                    <input type="hidden" name="id"
                                           value="<?= (int)$row["id"] ?>">

                                    <button type="submit"
                                            class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="6"
                            class="text-center text-secondary py-4">
                            Belum ada data produk.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php endif; ?>
