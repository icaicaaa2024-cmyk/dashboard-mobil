<?php
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    echo '<div class="alert alert-warning">
        ID produk tidak valid.
    </div>';
    return;
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT nama_barang, harga, stok FROM produk WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    echo '<div class="alert alert-warning">
        Produk tidak ditemukan.
    </div>';
    return;
}

$nama = $data["nama_barang"];
$harga = $data["harga"];
$stok = $data["stok"];
$pesan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST["nama_barang"] ?? "");
    $harga = $_POST["harga"] ?? "";
    $stok = $_POST["stok"] ?? "";

    if ($nama === "" || $harga === "" || $stok === "") {
        $pesan = "Semua kolom harus diisi.";
    } elseif (!is_numeric($harga) || $harga < 0) {
        $pesan = "Harga harus berupa angka yang valid.";
    } elseif (
        filter_var($stok, FILTER_VALIDATE_INT) === false
        || $stok < 0
    ) {
        $pesan = "Stok harus berupa bilangan bulat minimal 0.";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE produk
             SET nama_barang = ?, harga = ?, stok = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sdii",
            $nama,
            $harga,
            $stok,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php?page=produk");
            exit;
        } else {
            $pesan = "Gagal memperbarui produk.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<h2 class="h4 mb-3">Edit Produk</h2>
<hr>
<div class="card border-0 shadow-sm">
    <div class="card-body">

        <?php if ($pesan !== ""): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars((string)$nama) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" min="0" step="any" value="<?= htmlspecialchars((string)$harga) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" step="1" value="<?= htmlspecialchars((string)$stok) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Update
            </button>
            <a href="index.php?page=produk" class="btn btn-secondary"> Kembali </a>
        </form>

    </div>
</div>
