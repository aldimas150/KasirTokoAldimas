<?php
require '../../config/database.php';
include '../../includes/header.php';

// Ambil semua data produk untuk ditampilkan di dropdown
$products_query = "SELECT * FROM products WHERE stok > 0 ORDER BY nama_produk ASC";
$products_result = mysqli_query($conn, $products_query);
?>

<div class="container-fluid">
    <h3 class="mb-4">Halaman Transaksi Kasir</h3>

    <div class="row">
    <div class="col-md-5">
    <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-box me-2"></i>Pilih Produk</h5>
                </div>
                <div class="card-body">
                    <form id="add-to-cart-form">
                        <div class="mb-3">
                            <label for="product" class="form-label">Produk</label>
                            <select id="product" name="product" class="form-select" required>
                                <option value="">-- Cari atau Pilih Produk --</option>
                                <?php while ($product = mysqli_fetch_assoc($products_result)) : ?>
                                    <option 
                                        value="<?php echo $product['id']; ?>" 
                                        data-harga="<?php echo $product['harga']; ?>"
                                        data-stok="<?php echo $product['stok']; ?>">
                                        <?php echo $product['nama_produk'] . " (Stok: " . $product['stok'] . ")"; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
    <div class="card shadow-sm h-100">
                 <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja</h5>
                </div>
                <div class="card-body">
                    <form action="process.php" method="POST">
                        <table class="table table-hover" id="cart-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                        
                        <div class="mt-4 p-3 bg-light rounded text-end">
                            <h3 class="fw-bold">Total: Rp <span id="total-belanja">0</span></h3>
                        </div>

                        <div class="row mt-4 align-items-end">
                            <div class="col-md-6">
                                <label for="bayar" class="form-label">Jumlah Bayar (Rp)</label>
                                <input type="number" id="bayar" name="bayar" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kembalian (Rp)</label>
                                <h3 class="fw-bold text-success">Rp <span id="kembalian">0</span></h3>
                            </div>
                        </div>

                        <input type="hidden" name="cart_data" id="cart_data">
                        <input type="hidden" name="total_belanja_hidden" id="total_belanja_hidden">

                        <div class="d-grid mt-4">
                            <button type="submit" id="proses-transaksi-btn" class="btn btn-success btn-lg" disabled><i class="fas fa-check-circle me-2"></i>Proses Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartForm = document.getElementById('add-to-cart-form');
    const cartTableBody = document.querySelector('#cart-table tbody');
    const totalBelanjaSpan = document.getElementById('total-belanja');
    const bayarInput = document.getElementById('bayar');
    const kembalianSpan = document.getElementById('kembalian');
    const cartDataInput = document.getElementById('cart_data');
    const totalBelanjaHiddenInput = document.getElementById('total_belanja_hidden');
    const prosesBtn = document.getElementById('proses-transaksi-btn');

    let cart = [];

    addToCartForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const productSelect = document.getElementById('product');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const quantityInput = document.getElementById('quantity');
        const productId = selectedOption.value;
        if (!productId) {
            alert('Silakan pilih produk terlebih dahulu.');
            return;
        }
        const productName = selectedOption.text.split(' (Stok:')[0];
        const productPrice = parseFloat(selectedOption.dataset.harga);
        const productStock = parseInt(selectedOption.dataset.stok);
        let quantity = parseInt(quantityInput.value);
        const existingCartItem = cart.find(item => item.id === productId);
        let totalQtyInCart = existingCartItem ? existingCartItem.quantity : 0;
        if (quantity + totalQtyInCart > productStock) {
            alert(`Stok tidak mencukupi! Sisa stok: ${productStock}.`);
            return;
        }
        if (existingCartItem) {
            existingCartItem.quantity += quantity;
        } else {
            cart.push({ id: productId, name: productName, price: productPrice, quantity: quantity });
        }
        updateCartDisplay();
        quantityInput.value = 1;
        productSelect.value = "";
    });

    function updateCartDisplay() {
        cartTableBody.innerHTML = '';
        let total = 0;
        cart.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            const row = `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td class="text-end">${subtotal.toLocaleString('id-ID')}</td>
                    <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item" data-index="${index}"><i class="fas fa-trash-alt"></i></button></td>
                </tr>
            `;
            cartTableBody.innerHTML += row;
        });
        totalBelanjaSpan.textContent = total.toLocaleString('id-ID');
        totalBelanjaHiddenInput.value = total;
        updatePayment();
        prosesBtn.disabled = cart.length === 0;
        cartDataInput.value = JSON.stringify(cart);
    }
    
    bayarInput.addEventListener('input', updatePayment);
    function updatePayment() {
        const total = parseFloat(totalBelanjaHiddenInput.value) || 0;
        const bayar = parseFloat(bayarInput.value) || 0;
        const kembalian = bayar - total;
        if (kembalian >= 0) {
            kembalianSpan.textContent = kembalian.toLocaleString('id-ID');
        } else {
            kembalianSpan.textContent = '0';
        }
    }

    cartTableBody.addEventListener('click', function(e) {
        // Find the button that was clicked
        const removeButton = e.target.closest('.remove-item');
        if (removeButton) {
            const itemIndex = parseInt(removeButton.dataset.index);
            cart.splice(itemIndex, 1);
            updateCartDisplay();
        }
    });
});
</script>

<?php include '../../includes/footer.php'; ?>