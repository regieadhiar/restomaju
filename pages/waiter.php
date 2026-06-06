<?php
include __DIR__ . '/../config/db.php';
include __DIR__ . '/../actions/waiter_action.php';
checkRole('waiter');

$pageTitle = 'Halaman Pelayan - RestoMaju';
$data = handleWaiterRequest($conn);
$tablesQuery = $data['tablesQuery'];
$menuQuery = $data['menuQuery'];
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../components/head.php'; ?>
<body class="bg-slate-50 min-h-screen font-sans">
    <header class="bg-white shadow-sm sticky top-0 z-10 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold text-slate-800"><i class="fas fa-concierge-bell mr-2 text-orange-500"></i>Halaman Pelayan</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-slate-500"><i class="fas fa-user mr-1"></i><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="text-red-500 hover:text-red-700 text-sm font-semibold"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nomor Meja</label>
                    <select id="table-select" class="w-full px-4 py-3 border border-slate-200 rounded-lg outline-none bg-white">
                        <option value="">-- Pilih Meja --</option>
                        <?php foreach($tablesQuery as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= $t['status']!=='empty'?'disabled':'' ?>><?= $t['table_number'] ?> <?= $t['status']!=='empty'?'(Terisi)':'' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nama Pelanggan</label>
                    <input type="text" id="customer-name" class="w-full px-4 py-3 border border-slate-200 rounded-lg outline-none" placeholder="Masukkan nama pelanggan">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="flex space-x-2 mb-6 overflow-x-auto pb-2">
                    <button onclick="filterMenu('all')" data-category="all" class="cat-btn px-4 py-2 bg-orange-500 text-white rounded-lg"><i class="fas fa-list mr-2"></i>Semua</button>
                    <button onclick="filterMenu('food')" data-category="food" class="cat-btn px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg"><i class="fas fa-utensils mr-2"></i>Makanan</button>
                    <button onclick="filterMenu('drink')" data-category="drink" class="cat-btn px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg"><i class="fas fa-glass-whiskey mr-2"></i>Minuman</button>
                    <button onclick="filterMenu('snack')" data-category="snack" class="cat-btn px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg"><i class="fas fa-cookie mr-2"></i>Cemilan</button>
                </div>

                <div id="menu-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach($menuQuery as $m): ?>
                        <div class="menu-item bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden" data-category="<?= $m['category'] ?>">
                            <img src="<?= $m['image'] ?>" class="w-full h-44 object-cover">
                            <div class="p-4">
                                <h3 class="font-semibold text-slate-800"><?= htmlspecialchars($m['name']) ?></h3>
                                <p class="text-orange-500 font-bold mb-3">Rp <?= number_format($m['price'], 0, ',', '.') ?></p>
                                <button onclick="addToCart(<?= $m['id'] ?>, '<?= addslashes($m['name']) ?>', <?= $m['price'] ?>)" class="w-full bg-green-500 text-white py-2 px-4 rounded-lg font-medium hover:bg-green-600 transition" <?= $m['status'] === 'unavailable' ? 'disabled bg-slate-300' : '' ?>>
                                    <?= $m['status'] === 'available' ? '<i class="fas fa-plus mr-2"></i>Tambah' : 'Habis' ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-4 text-slate-800">Keranjang Pesanan</h2>
                    <div id="cart-items" class="space-y-4 max-h-80 overflow-y-auto mb-6 text-slate-500">
                        <p class="text-center py-4">Keranjang kosong</p>
                    </div>
                    <div class="border-t pt-4">
                        <div class="flex justify-between text-lg font-semibold mb-4">
                            <span>Total:</span><span id="cart-total" class="text-orange-500 font-bold">Rp 0</span>
                        </div>
                        <button id="send-order" onclick="submitOrder()" class="w-full bg-orange-500 text-white py-3 rounded-lg font-bold disabled:opacity-50" disabled>
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Ke Dapur
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php renderToast(); ?>

    <script>
        let cart = [];
        
        function filterMenu(cat) {
            document.querySelectorAll('.menu-item').forEach(el => {
                if(cat === 'all' || el.dataset.category === cat) el.classList.remove('hidden');
                else el.classList.add('hidden');
            });
            setActiveCategory(cat);
        }

        function setActiveCategory(cat) {
            document.querySelectorAll('.cat-btn').forEach(btn => {
                if(btn.dataset.category === cat) {
                    btn.classList.remove('bg-white', 'border-slate-200', 'text-slate-700');
                    btn.classList.add('bg-orange-500', 'text-white', 'border-orange-500');
                } else {
                    btn.classList.remove('bg-orange-500', 'text-white', 'border-orange-500');
                    btn.classList.add('bg-white', 'border-slate-200', 'text-slate-700');
                }
            });
        }

        function addToCart(id, name, price) {
            let exist = cart.find(i => i.id === id);
            if(exist) { exist.qty++; } 
            else { cart.push({id, name, price, qty: 1}); }
            renderCart();
            showNotification(name + " ditambahkan.");
        }

        function updateQty(id, delta) {
            let item = cart.find(i => i.id === id);
            if(item) {
                item.qty += delta;
                if(item.qty <= 0) cart = cart.filter(i => i.id !== id);
            }
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            if(cart.length === 0) {
                container.innerHTML = '<p class="text-center py-4">Keranjang kosong</p>';
                document.getElementById('cart-total').textContent = 'Rp 0';
                document.getElementById('send-order').disabled = true;
                return;
            }
            let total = 0;
            container.innerHTML = cart.map(i => {
                total += i.price * i.qty;
                return `
                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-lg">
                    <div><h4 class="font-medium text-slate-800">${i.name}</h4><p class="text-xs text-orange-500">Rp ${i.price.toLocaleString('id-ID')}</p></div>
                    <div class="flex items-center space-x-2">
                        <button onclick="updateQty(${i.id}, -1)" class="px-2 bg-gray-200 rounded font-bold">-</button>
                        <span class="font-bold text-sm">${i.qty}</span>
                        <button onclick="updateQty(${i.id}, 1)" class="px-2 bg-orange-500 text-white rounded font-bold">+</button>
                    </div>
                </div>`;
            }).join('');
            document.getElementById('cart-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
            validateForm();
        }

        function validateForm() {
            const table = document.getElementById('table-select').value;
            const cust = document.getElementById('customer-name').value.trim();
            document.getElementById('send-order').disabled = !(table && cust && cart.length > 0);
        }

        document.getElementById('table-select').addEventListener('change', validateForm);
        document.getElementById('customer-name').addEventListener('input', validateForm);

        function submitOrder() {
            const payload = {
                table_id: document.getElementById('table-select').value,
                customer_name: document.getElementById('customer-name').value,
                cart: cart
            };
            fetch('?action=place_order', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    showNotification(res.message);
                    setTimeout(() => location.reload(), 1500);
                } else { showNotification(res.message, 'error'); }
            });
        }
    </script>
</body>
</html>