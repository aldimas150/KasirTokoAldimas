<?php
require '../../config/database.php';
include '../../includes/header.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Query untuk mengambil data produk berdasarkan ID
$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);
?>

<h3>Edit Produk</h3>
<a href="index.php">Kembali</a>

<form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    
    <table>
        <tr>
            <td>Kode Produk</td>
            <td><input type="text" name="kode_produk" value="<?php echo $product['kode_produk']; ?>" required></td>
        </tr>
        <tr>
            <td>Nama Produk</td>
            <td><input type="text" name="nama_produk" value="<?php echo $product['nama_produk']; ?>" required></td>
        </tr>
        <tr>
            <td>Harga</td>
            <td><input type="number" name="harga" value="<?php echo $product['harga']; ?>" required></td>
        </tr>
        <tr>
            <td>Stok</td>
            <td><input type="number" name="stok" value="<?php echo $product['stok']; ?>" required></td>
        </tr>
        <tr>
            <td></td>
            <td><button type="submit">Update Produk</button></td>
        </tr>
    </table>
</form>

<?php
include '../../includes/footer.php';
?>