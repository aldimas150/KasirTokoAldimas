<?php
session_start();
// PERIKSA HAK AKSES
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Jika bukan admin, tendang ke dasbor
    header("Location: /kasir-toko/dashboard.php");
    exit();
}

require '../../config/database.php';
include '../../includes/header.php';
?>
require '../../config/database.php';
include '../../includes/header.php';
?>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="fas fa-users-cog me-2"></i>Manajemen Pengguna
            </h4>
        </div>
        <div class="card-body">
            <a href="create.php" class="btn btn-primary mb-3">
                <i class="fas fa-plus-circle me-2"></i>Tambah Pengguna
            </a>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ambil semua data pengguna kecuali password
                        $sql = "SELECT id, username, nama_lengkap, role FROM users ORDER BY id DESC";
                        $result = mysqli_query($conn, $sql);
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . $row['username'] . "</td>";
                            echo "<td>" . $row['nama_lengkap'] . "</td>";
                            echo "<td><span class='badge bg-info'>" . ucfirst($row['role']) . "</span></td>";
                            echo "<td class='text-center'>
                                    <a href='edit.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm' title='Edit'>
                                        <i class='fas fa-edit'></i>
                                    </a> ";
                            
                            // Tambahkan pengaman: Admin tidak bisa menghapus akunnya sendiri
                            if ($row['id'] != $_SESSION['user_id']) {
                                echo "<a href='delete.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' title='Hapus' onclick=\"return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');\">
                                          <i class='fas fa-trash-alt'></i>
                                      </a>";
                            }

                            echo "</td>";
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