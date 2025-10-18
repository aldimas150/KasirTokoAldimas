<?php
session_start();
require '../../config/database.php';

// Pastikan request adalah POST dan ada data keranjang
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['cart_data'])) {

    // Ambil data dari form
    $id_user = $_SESSION['user_id'];
    $total_bayar = $_POST['total_belanja_hidden'];
    $cart_items = json_decode($_POST['cart_data'], true); // Decode JSON menjadi array PHP

    // --- Memulai Database Transaction ---
    mysqli_begin_transaction($conn);

    try {
        // 1. SIMPAN DATA KE TABEL `sales` (TRANSAKSI UTAMA)
        $kode_transaksi = "TRX" . date("YmdHis");
        $sql_sale = "INSERT INTO sales (kode_transaksi, tanggal_transaksi, total_bayar, id_user) 
                     VALUES ('$kode_transaksi', NOW(), '$total_bayar', '$id_user')";
        
        mysqli_query($conn, $sql_sale);
        
        // Ambil ID dari transaksi yang baru saja disimpan
        $id_sale = mysqli_insert_id($conn);

        // 2. LOOPING & SIMPAN SETIAP ITEM KERANJANG KE `sale_details` DAN UPDATE STOK
        foreach ($cart_items as $item) {
            $id_product = $item['id'];
            $jumlah = $item['quantity'];
            $harga = $item['price'];
            $subtotal = $harga * $jumlah;

            // a. Simpan ke tabel sale_details
            $sql_detail = "INSERT INTO sale_details (id_sale, id_product, jumlah, subtotal) 
                           VALUES ('$id_sale', '$id_product', '$jumlah', '$subtotal')";
            mysqli_query($conn, $sql_detail);

            // b. Kurangi stok di tabel products
            $sql_update_stok = "UPDATE products SET stok = stok - $jumlah WHERE id = $id_product";
            mysqli_query($conn, $sql_update_stok);
        }

        // Jika semua query berhasil, simpan perubahan secara permanen
        mysqli_commit($conn);

// Redirect ke halaman cetak struk dengan mengirim ID transaksi
header("Location: cetak_struk.php?id=" . $id_sale);
exit();

    } catch (mysqli_sql_exception $exception) {
        // Jika ada satu saja query yang gagal, batalkan semua perubahan
        mysqli_rollback($conn);
        
        echo "<h1>Transaksi Gagal!</h1>";
        echo "<p>Terjadi kesalahan saat memproses transaksi. Silakan coba lagi.</p>";
        echo "Error: " . $exception->getMessage();
    }

} else {
    echo "Keranjang kosong atau terjadi kesalahan.";
    header("Location: index.php");
    exit();
}
?>