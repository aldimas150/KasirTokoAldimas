<?php
include '../../includes/header.php';
include '../../includes/navigation.php';
?>

<div class="container">
    <h3>Tambah Produk Baru</h3>
    <hr>

    <form action="store.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="kode_produk" class="form-label">Kode Produk</label>
            <input type="text" class="form-control" id="kode_produk" name="kode_produk" placeholder="Contoh: BRG001" required>
        </div>

        <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Contoh: Buku Tulis Sinar Dunia" required>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 5000" required>
        </div>

        <div class="mb-3">
            <label for="stok" class="form-label">Stok Awal</label>
            <input type="number" class="form-control" id="stok" name="stok" placeholder="Contoh: 100" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan Produk</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php
include '../../includes/footer.php';
?>