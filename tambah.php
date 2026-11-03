<?php
$pesan = "";
$nama = "";
$harga = "";
$stok = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST["nama_barang"] ?? "");
    $harga = trim($_POST["harga"] ?? "");
    $stok = trim($_POST["stok"] ?? "");

    if ($nama === "" || $harga === "" || $stok === "") {
        $pesan = "Semua kolom harus diisi.";
    } elseif (!is_numeric($harga) || (float)$harga < 0) {
        $pesan = "Harga harus berupa angka yang valid.";
    } elseif (
        filter_var($stok, FILTER_VALIDATE_INT) === false
        || (int)$stok < 0
    ) {
        $pesan = "Stok harus berupa bilangan bulat minimal 0.";
    } else {
        $harga = (float)$harga;
        $stok = (int)$stok;

        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO produk (nama_barang, harga, stok)
             VALUES (?, ?, ?)"
        );

        if (!$stmt) {
            $pesan = "Query gagal: " . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                "sdi",
                $nama,
                $harga,
                $stok
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                header("Location: index.php?page=produk");
                exit;
            } else {
                $pesan = "Data gagal disimpan: "
                    . mysqli_stmt_error($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>

<h2 class="h4 mb-3">Tambah Produk</h2>
<hr>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <?php if ($pesan !== ""): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <form method="POST"
              action="index.php?page=tambah_produk">

            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input
                    type="text"
                    name="nama_barang"
                    class="form-control"
                    value="<?= htmlspecialchars($nama) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input
                    type="number"
                    name="harga"
                    class="form-control"
                    min="0"
                    step="any"
                    value="<?= htmlspecialchars($harga) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" step="1" value="<?= htmlspecialchars($stok) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan
            </button>

            <a href="index.php?page=produk" class="btn btn-secondary"> Kembali</a>
        </form>

    </div>
</div>
