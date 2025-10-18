<?php
require '../../config/database.php';
include '../../includes/header.php';
?>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="fas fa-box-open me-2"></i>Data Produk
            </h4>
        </div>
        <div class="card-body">
            <a href="create.php" class="btn btn-primary mb-3">
                <i class="fas fa-plus-circle me-2"></i>Tambah Produk
            </a>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM products ORDER BY id DESC";
                        $result = mysqli_query($conn, $sql);
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row['kode_produk'] . "</td>";
                            echo "<td>" . $row['nama_produk'] . "</td>";
                            echo "<td class='text-end'>Rp " . number_format($row['harga']) . "</td>";
                            echo "<td class='text-center'>" . $row['stok'] . "</td>";
                            echo "<td class='text-center'>
                                    <a href='edit.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm' title='Edit'>
                                        <i class='fas fa-edit'></i>
                                    </a>
                                    <a href='delete.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' title='Hapus' onclick=\"return confirm('Apakah Anda yakin ingin menghapus produk ini?');\">
                                        <i class='fas fa-trash-alt'></i>
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>