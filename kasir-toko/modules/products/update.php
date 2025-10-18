<?php
require '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id = $_POST['id'];
    $kode_produk = $_POST['kode_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Query untuk update data
    $sql = "UPDATE products SET 
                kode_produk = '$kode_produk', 
                nama_produk = '$nama_produk', 
                harga = '$harga', 
                stok = '$stok' 
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=update_sukses");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>