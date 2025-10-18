<?php
require '../../config/database.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Query untuk menghapus data
$sql = "DELETE FROM products WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: index.php?status=hapus_sukses");
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}
?>