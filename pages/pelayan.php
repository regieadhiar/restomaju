<?php
// Ambil database pdo yang sudah disertakan lewat index.php
// Kita filter menu berdasarkan kategori jika parameter 'cat' ada di URL
$category_filter = isset($_GET['cat']) ? $_GET['cat'] : 'all';

if ($category_filter == 'all') {
    $stmt = $pdo->query("SELECT * FROM menus WHERE status = 'available'");
} else {
    $stmt = $pdo->prepare("SELECT * FROM menus WHERE status = 'available' AND category = ?");
    $stmt->execute([$category_filter]);
}
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="min-h-screen">
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold text-dark">Halaman Pelayan</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray">
                        <i class="fas fa-user mr-1"></i>
                        <span><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                    </div>
                    <a href="?page=logout" class="text-danger hover:text-red-700">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-dark mb-2">Nomor Meja</label>
                    <select id="table-select" class="w-full px-4 py-3 border border-gray/30 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">-- Pilih Meja --</option>
                        <?php for($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>">Meja 0<?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark mb-2">Nama Pelanggan</label>
                    <input type="text" id="customer-name" class="w-full px-4 py-3 border border-gray/30 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Masukkan nama pelanggan">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="mb-6">
                    <div class="flex space-x-2 pb-2 overflow-x-auto">
                        <a href="?page=waiter&cat=all" class="px-4 py-2 rounded-full whitespace-nowrap flex items-center space-x-2 border <?php echo $category_filter == 'all' ? 'bg-primary text-white' : 'bg-white text-dark'; ?> shadow-sm">
                            <i class="fas fa-utensils"></i><span>Semua Menu</span>
                        </a>
                        <a href="?page=waiter&cat=makanan" class="px-4 py-2 rounded-full whitespace-nowrap flex items-center space-x-2 border <?php echo $category_filter == 'makanan' ? 'bg-primary text-white' : 'bg-white text-dark'; ?> shadow-sm">
                            <i class="fas fa-hamburger"></i><span>Makanan</span>
                        </a>
                        <a href="?page=waiter&cat=minuman" class="px-4 py-2 rounded-full whitespace-nowrap flex items-center space-x-2 border <?php echo $category_filter == 'minuman' ? 'bg-primary text-white' : 'bg-white text-dark'; ?> shadow-sm">
                            <i class="fas fa-glass-martini-alt"></i><span>Minuman</span>
                        </a>
                        <a href="?page=waiter&cat=snack" class="px-4 py-2 rounded-full whitespace-nowrap flex items-center space-x-2 border <?php echo $category_filter == 'snack' ? 'bg-primary text-white' : 'bg-white text-dark'; ?> shadow-sm">
                            <i class="fas fa-cookie"></i><span>Cemilan</span>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php if (count($menus) > 0): ?>
                        <?php foreach($menus as $menu): ?>
                            <div class="bg-white rounded-xl shadow p-4 border flex flex-col justify-between">
                                <img src="https://picsum.photos/300/200?random=<?php echo $menu['id']; ?>" alt="<?php echo $menu['name']; ?>" class="w-full h-40 object-cover rounded-lg mb-3">
                                <div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded bg-orange-100 text-primary capitalize"><?php echo $menu['category']; ?></span>
                                    <h3 class="font-bold text-dark text-lg mt-1"><?php echo htmlspecialchars($menu['name']); ?></h3>
                                    <p class="text-gray text-xs my-1 line-clamp-2"><?php echo htmlspecialchars($menu['description']); ?></p>
                                    <p class="text-primary font-bold text-xl mt-2">Rp <?php echo number_format($menu['price'], 0, ',', '.'); ?></p>
                                </div>
                                <button onclick="addToCart(<?php echo $menu['id']; ?>, '<?php echo addslashes($menu['name']); ?>', <?php echo $menu['price']; ?>)" class="w-full mt-4 bg-primary/10 text-primary hover:bg-primary hover:text-white py-2 rounded-lg font-medium transition duration-200">
                                    <i class="fas fa-plus mr-2"></i>Tambah
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-8 text-gray">Menu tidak tersedia.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg sticky top-24">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-dark mb-6">Keranjang Pesanan</h2>
                        <div id="cart-items" class="space-y-4 max-h-96 overflow-y-auto mb-6">
                            <div class="text-center py-8 text-gray">
                                <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                                <p>Keranjang kosong</p>
                            </div>
                        </div>
                        <div class="border-t pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-semibold text-dark">Total:</span>
                                <span id="cart-total" class="text-2xl font-bold text-primary">Rp 0</span>
                            </div>
                            <button id="send-order-btn" class="w-full bg-primary text-white py-4 px-4 rounded-lg font-semibold hover:bg-orange-600 transition disabled:opacity-50" disabled>
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>