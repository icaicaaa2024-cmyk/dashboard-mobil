
<?php
require_once "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php?page=produk");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: index.php?page=produk");
    exit;
}

$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM produk WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: index.php?page=produk");
exit;
?>
