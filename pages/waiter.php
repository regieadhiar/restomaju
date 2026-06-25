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
            <h1 class="text-xl font-bold text-resto-dark"><i class="fas fa-concierge-bell mr-2 text-resto-primary"></i>Halaman Pelayan</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-resto-gray"><i class="fas fa-user mr-1"></i><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="text-resto-danger hover:text-red-700 text-sm font-semibold"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-resto-dark mb-2">Nomor Meja</label>
                    <select id="table-select" class="w-full px-4 py-3 border border-resto-gray/30 rounded-lg outline-none bg-white">
                        <option value="">-- Pilih Meja --</option>
                        <?php foreach($tablesQuery as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= $t['status']!=='empty'?'disabled':'' ?>><?= $t['table_number'] ?> <?= $t['status']!=='empty'?'(Terisi)':'' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-resto-dark mb-2">Nama Pelanggan</label>
                    <input type="text" id="customer-name" class="w-full px-4 py-3 border border-resto-gray/30 rounded-lg outline-none" placeholder="Masukkan nama pelanggan">
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
            
            <div class="lg:col-span-1 relative">
                <div id="mobile-cart-sidebar" class="fixed inset-y-0 right-0 z-40 w-full max-w-sm p-4 transform translate-x-full transition-transform duration-300 md:static md:translate-x-0 md:w-auto md:p-0">
                    <div class="bg-white rounded-xl shadow-lg sticky top-24">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-resto-dark">Keranjang Pesanan</h2>
                                <button id="close-cart-mobile" class="md:hidden text-resto-gray hover:text-resto-dark">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <div id="cart-items" class="space-y-4 max-h-96 overflow-y-auto mb-6 text-resto-gray">
                                <div class="text-center py-8 text-resto-gray">
                                    <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                                    <p>Keranjang kosong</p>
                                    <p class="text-sm">Tambahkan menu dari katalog</p>
                                </div>
                            </div>

                            <div class="border-t pt-6">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-resto-dark mb-2"><i class="fas fa-sticky-note mr-1 text-orange-400"></i>Catatan Pesanan</label>
                                    <textarea id="order-notes" rows="2" class="w-full px-3 py-2 border border-resto-gray/30 rounded-lg outline-none text-sm resize-none" placeholder="Contoh: Pedas, Tanpa bawang, dll."></textarea>
                                </div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-lg font-semibold text-resto-dark">Total :</span>
                                    <span id="cart-total" class="text-2xl font-bold text-resto-primary">Rp 0</span>
                                </div>
                                <button id="send-order" onclick="submitOrder()" class="w-full bg-resto-primary text-white py-4 px-4 rounded-lg font-semibold hover:bg-orange-600 transition duration-300 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <i class="fas fa-paper-plane mr-2"></i>Kirim Pesanan ke Dapur
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button id="open-cart-mobile" class="md:hidden fixed bottom-6 right-6 bg-resto-primary text-white p-4 rounded-full shadow-lg hover:bg-orange-600 transition duration-300 z-20">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span id="cart-count-badge" class="absolute -top-2 -right-2 bg-resto-danger text-white text-xs rounded-full w-6 h-6 flex items-center justify-center">0</span>
                </button>
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
                container.innerHTML = '<div class="text-center py-8 text-resto-gray"><i class="fas fa-shopping-cart text-4xl mb-3"></i><p>Keranjang kosong</p><p class="text-sm">Tambahkan menu dari katalog</p></div>';
                document.getElementById('cart-total').textContent = 'Rp 0';
                document.getElementById('send-order').disabled = true;
                updateCartCount();
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
            updateCartCount();
            validateForm();
        }

        function updateCartCount() {
            const badge = document.getElementById('cart-count-badge');
            if (!badge) return;
            const count = cart.reduce((sum, item) => sum + item.qty, 0);
            badge.textContent = count;
        }

        function validateForm() {
            const table = document.getElementById('table-select').value;
            const cust = document.getElementById('customer-name').value.trim();
            document.getElementById('send-order').disabled = !(table && cust && cart.length > 0);
        }

        document.getElementById('table-select').addEventListener('change', validateForm);
        document.getElementById('customer-name').addEventListener('input', validateForm);
        document.getElementById('open-cart-mobile').addEventListener('click', openCartMobile);
        document.getElementById('close-cart-mobile').addEventListener('click', closeCartMobile);

        function openCartMobile() {
            document.getElementById('mobile-cart-sidebar').classList.remove('translate-x-full');
        }

        function closeCartMobile() {
            document.getElementById('mobile-cart-sidebar').classList.add('translate-x-full');
        }

        function submitOrder() {
            const payload = {
                table_id: document.getElementById('table-select').value,
                customer_name: document.getElementById('customer-name').value,
                notes: document.getElementById('order-notes').value.trim(),
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