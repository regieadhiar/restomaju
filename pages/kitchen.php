<?php
include __DIR__ . '/../config/db.php';
include __DIR__ . '/../actions/kitchen_action.php';
checkRole('kitchen');

$pageTitle = 'Kitchen Display System - RestoMaju';
$data = handleKitchenRequest($conn);
$orders = $data['orders'];
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
    </main>

    <script>
        setInterval(() => {
            document.getElementById('clock').textContent = new Date().toLocaleTimeString('id-ID');
        }, 1000);
    </script>
</body>
</html>