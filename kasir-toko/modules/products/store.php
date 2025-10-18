<?php
require '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $kode_produk = $_POST['kode_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Buat query untuk menyimpan data
    $sql = "INSERT INTO products (kode_produk, nama_produk, harga, stok) VALUES ('$kode_produk', '$nama_produk', '$harga', '$stok')";

    // Eksekusi query
    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, redirect ke halaman daftar produk
        header("Location: index.php?status=sukses");
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Tutup koneksi
    mysqli_close($conn);
}
?>