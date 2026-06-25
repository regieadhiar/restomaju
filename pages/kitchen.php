<?php
include __DIR__ . '/../config/db.php';
include __DIR__ . '/../actions/kitchen_action.php';
checkRole('kitchen');

$pageTitle = 'Kitchen Display System - RestoMaju';
$data = handleKitchenRequest($conn);
$orders = $data['orders'];
$menus = $data['menus'];
$activeTab = $_GET['kitchen_tab'] ?? 'orders';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../components/head.php'; ?>
<body class="bg-slate-900 text-slate-100 min-h-screen">
    <header class="bg-slate-950 border-b border-slate-800 p-4 sticky top-0 z-10 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-white">Kitchen Display System</h1>
            <p class="text-xs text-resto-gray">Monitor Antrean Masakan Aktif</p>
        </div>
        <div class="flex items-center space-x-6">
            <span class="text-sm text-slate-400"><i class="fas fa-clock mr-1"></i> <span id="clock">00:00:00</span></span>
            <a href="logout.php" class="text-red-400 hover:text-red-300"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </header>

    <main class="container mx-auto p-6">
        <!-- Tab Navigation -->
        <div class="flex space-x-2 mb-6">
            <a href="?kitchen_tab=orders" class="px-5 py-2.5 rounded-lg font-bold text-sm transition <?= $activeTab === 'orders' ? 'bg-amber-500 text-white' : 'bg-slate-800 text-slate-400 hover:text-white' ?>"><i class="fas fa-fire mr-2"></i>Antrean Pesanan</a>
            <a href="?kitchen_tab=menu" class="px-5 py-2.5 rounded-lg font-bold text-sm transition <?= $activeTab === 'menu' ? 'bg-amber-500 text-white' : 'bg-slate-800 text-slate-400 hover:text-white' ?>"><i class="fas fa-utensils mr-2"></i>Ketersediaan Menu</a>
        </div>

        <?php if ($activeTab === 'orders'): ?>
            <?php if(empty($orders)): ?>
                <div class="text-center py-20 text-slate-500">
                    <i class="fas fa-concierge-bell text-6xl mb-4 text-slate-600"></i>
                    <h3 class="text-xl font-semibold">Tidak ada antrean pesanan</h3>
                    <p class="text-sm text-slate-400 mt-1">Seluruh pesanan pelanggan telah diproses selesai.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach($orders as $o): ?>
                        <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden shadow-lg">
                            <div class="bg-amber-500/10 border-b border-amber-500/20 p-4 flex justify-between items-center">
                                <h3 class="text-lg font-bold text-amber-400"><?= $o['table_number'] ?> - <?= htmlspecialchars($o['customer_name']) ?></h3>
                            </div>
                            <div class="p-4">
                                <?php if (!empty($o['notes'])): ?>
                                    <div class="mb-3 bg-amber-500/10 border border-amber-500/30 rounded-lg px-3 py-2">
                                        <p class="text-xs font-bold text-amber-400 mb-1"><i class="fas fa-sticky-note mr-1"></i>Catatan:</p>
                                        <p class="text-sm text-amber-200"><?= htmlspecialchars($o['notes']) ?></p>
                                    </div>
                                <?php endif; ?>
                                <ul class="space-y-3">
                                    <?php
                                    $stmtItems = $conn->prepare("SELECT oi.quantity, m.name, m.category FROM order_items oi JOIN menu_items m ON oi.menu_id = m.id WHERE oi.order_id = ?");
                                    $stmtItems->execute([$o['id']]);
                                    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($items as $i):
                                        $is_drink = in_array($i['category'], ['drink']);
                                    ?>
                                        <li class="flex items-start text-sm font-medium">
                                            <span class="bg-slate-700 px-2 py-0.5 rounded text-xs mr-2 text-white font-bold"><?= $i['quantity'] ?>x</span>
                                            <span class="<?= $is_drink ? 'text-blue-300' : 'text-slate-100' ?>"><?= htmlspecialchars($i['name']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="p-4 bg-slate-850 border-t border-slate-700">
                                <a href="?action=complete_order&id=<?= $o['id'] ?>" class="block text-center w-full bg-green-600 text-white py-2.5 rounded-lg font-semibold hover:bg-green-700 transition">
                                    <i class="fas fa-check-circle mr-2"></i>Siap Saji
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($activeTab === 'menu'): ?>
            <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
                <div class="p-4 border-b border-slate-700">
                    <h2 class="text-lg font-bold text-white"><i class="fas fa-utensils mr-2 text-amber-400"></i>Ketersediaan Menu</h2>
                    <p class="text-xs text-slate-400 mt-1">Toggle status ketersediaan menu untuk pelayan.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-700 text-slate-400 text-xs font-medium uppercase">
                                <th class="px-4 py-3">Nama Menu</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Harga</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php
                            $byCategory = [];
                            foreach ($menus as $m) {
                                $cat = $m['category'] ?? 'lainnya';
                                if (!isset($byCategory[$cat])) $byCategory[$cat] = [];
                                $byCategory[$cat][] = $m;
                            }
                            ?>
                            <?php foreach ($byCategory as $catName => $items): ?>
                                <tr class="border-b border-slate-700/50">
                                    <td colspan="5" class="px-4 py-2 bg-slate-750 text-xs font-bold text-amber-400 uppercase tracking-wider"><?= ucfirst($catName) ?></td>
                                </tr>
                                <?php foreach ($items as $m): ?>
                                    <tr class="border-b border-slate-700/30 hover:bg-slate-750 transition">
                                        <td class="px-4 py-3 font-medium text-white"><?= htmlspecialchars($m['name']) ?></td>
                                        <td class="px-4 py-3 text-slate-400 text-xs uppercase"><?= $m['category'] ?></td>
                                        <td class="px-4 py-3 text-slate-300">Rp <?= number_format($m['price'], 0, ',', '.') ?></td>
                                        <td class="px-4 py-3 text-center">
                                            <?php if ($m['status'] === 'available'): ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400"><i class="fas fa-check-circle mr-1"></i>Tersedia</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400"><i class="fas fa-times-circle mr-1"></i>Habis</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="?action=toggle_menu&id=<?= $m['id'] ?>" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold transition <?= $m['status'] === 'available' ? 'bg-red-500/20 text-red-400 hover:bg-red-500/30' : 'bg-green-500/20 text-green-400 hover:bg-green-500/30' ?>">
                                                <i class="fas <?= $m['status'] === 'available' ? 'fa-toggle-off' : 'fa-toggle-on' ?> mr-1"></i>
                                                <?= $m['status'] === 'available' ? 'Tandai Habis' : 'Tandai Tersedia' ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <script>
        setInterval(() => {
            document.getElementById('clock').textContent = new Date().toLocaleTimeString('id-ID');
        }, 1000);
    </script>
</body>
</html>