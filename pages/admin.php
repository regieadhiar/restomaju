<?php
include __DIR__ . '/../config/db.php';
include __DIR__ . '/../actions/admin_action.php';
checkRole('admin');

$pageTitle = 'Dashboard Admin - RestoMaju';
$data = handleAdminRequest($conn);
$tab = $_GET['tab'] ?? 'dashboard';
$incomeToday = $data['incomeToday'];
$totalOrders = $data['totalOrders'];
$activeMenu = $data['activeMenu'];
$occupiedTbl = $data['occupiedTbl'];

$menus = $data['menus'];
$tables = $data['tables'];

// Derived statistics for dashboard
$categoryCounts = [];
foreach ($menus as $m) {
    $cat = isset($m['category']) ? $m['category'] : 'lainnya';
    $categoryCounts[$cat] = ($categoryCounts[$cat] ?? 0) + 1;
}
$uniqueCategoryCount = count($categoryCounts);

$availableCount = 0;
$unavailableCount = 0;
foreach ($menus as $m) {
    if (isset($m['status']) && $m['status'] === 'available') $availableCount++; else $unavailableCount++;
}

// Recent menus (sorted by id desc)
$recentMenus = $menus;
usort($recentMenus, function($a, $b){ return ($b['id'] ?? 0) <=> ($a['id'] ?? 0); });
$recentMenus = array_slice($recentMenus, 0, 5);

?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../components/head.php'; ?>

    <body class="bg-slate-50 min-h-screen">
        <div class="flex relative">
            <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

            <aside id="sidebar" class="w-64 bg-slate-900 text-white min-h-screen fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out p-6 flex flex-col justify-between">
                <button onclick="toggleSidebar()" class="absolute top-4 right-4 lg:hidden text-slate-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <div>
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white"><i class="fas fa-crown"></i></div>
                        <div><h2 class="font-bold text-sm">Owner Admin</h2><p class="text-xs text-slate-400">Manajer Restoran</p></div>
                    </div>
                    <nav class="space-y-2">
                        <a href="?tab=dashboard" class="block <?= $tab === 'dashboard' ? 'bg-orange-500 text-white' : 'text-slate-400 hover:text-white' ?> px-4 py-2.5 rounded-lg font-medium"><i class="fas fa-tachometer-alt mr-3"></i>Dashboard</a>
                        <a href="?tab=management" class="block <?= $tab === 'management' ? 'bg-orange-500 text-white' : 'text-slate-400 hover:text-white' ?> px-4 py-2.5 rounded-lg font-medium"><i class="fas fa-tools mr-3"></i>Manajemen</a>
                        <a href="?tab=analytics" class="block <?= $tab === 'analytics' ? 'bg-orange-500 text-white' : 'text-slate-400 hover:text-white' ?> px-4 py-2.5 rounded-lg font-medium"><i class="fas fa-chart-bar mr-3"></i>Analitik</a>
                        <a href="?tab=users" class="block <?= $tab === 'users' ? 'bg-orange-500 text-white' : 'text-slate-400 hover:text-white' ?> px-4 py-2.5 rounded-lg font-medium"><i class="fas fa-users mr-3"></i>Manajemen User</a>
                        <a href="logout.php" class="block text-slate-400 hover:text-white px-4 py-2.5 rounded-lg"><i class="fas fa-sign-out-alt mr-3"></i>Keluar</a>
                    </nav>
                </div>
            </aside>

            <div class="flex-1 lg:ml-64 min-w-0 transition-all duration-300">
                <header class="bg-white shadow-sm p-4 flex justify-between items-center px-4 lg:px-8">
                    <div class="flex items-center space-x-3">
                        <button onclick="toggleSidebar()" class="lg:hidden text-slate-800 hover:text-orange-500 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-base sm:text-lg lg:text-xl font-bold text-slate-800 line-clamp-1">Ringkasan Operasional Restoran</h1>
                    </div>

                    <span class="text-xs font-semibold bg-slate-100 px-3 py-1.5 rounded-lg text-slate-500"><i class="fas fa-calendar mr-1"></i> Hari Ini: <?= date('d M Y') ?></span>
                </header>

            <main class="p-8">
                <?php if ($tab === 'dashboard'): ?>
                    <!-- DASHBOARD TAB -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan Hari Ini</div>
                            <div class="text-2xl font-extrabold text-slate-800 mt-2">Rp <?= number_format($data['incomeToday'] ?? 0, 0, ',', '.') ?></div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pesanan Selesai</div>
                            <div class="text-2xl font-extrabold text-slate-800 mt-2"><?= $data['totalOrders'] ?? 0 ?> Transaksi</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Menu Aktif Siap Jual</div>
                            <div class="text-2xl font-extrabold text-slate-800 mt-2"><?= $data['activeMenu'] ?? 0 ?> Kuliner</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Meja Terisi Aktif</div>
                            <div class="text-2xl font-extrabold text-slate-800 mt-2"><?= $data['occupiedTbl'] ?? 0 ?> / <?= count($tables) ?> Meja</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white rounded-xl shadow p-6">
                            <h3 class="text-sm font-bold text-slate-800 mb-2">Menu per Kategori</h3>
                            <div class="text-xs text-slate-500">
                                <?php if (!empty($categoryCounts)): ?>
                                    <?php foreach($categoryCounts as $cname => $cnum): ?>
                                        <div class="flex justify-between text-sm py-1 border-b last:border-b-0"><span><?= ucfirst($cname) ?></span><span class="font-bold"><?= $cnum ?></span></div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-slate-400 text-sm">Belum ada menu terdaftar.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow p-6">
                            <h3 class="text-sm font-bold text-slate-800 mb-2">Ketersediaan Menu</h3>
                            <div class="text-xs text-slate-500">
                                <div class="flex justify-between text-sm py-1"><span>Tersedia</span><span class="font-bold text-green-600"><?= $availableCount ?></span></div>
                                <div class="flex justify-between text-sm py-1"><span>Habis</span><span class="font-bold text-red-600"><?= $unavailableCount ?></span></div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow p-6">
                            <h3 class="text-sm font-bold text-slate-800 mb-2">Menu Terbaru</h3>
                            <div class="text-xs text-slate-500">
                                <?php if (!empty($recentMenus)): ?>
                                    <?php foreach($recentMenus as $r): ?>
                                        <div class="py-2 border-b last:border-b-0 flex items-center justify-between"><div class="flex items-center space-x-2"><img src="<?= $r['image'] ?>" class="w-8 h-8 rounded" alt=""><div><div class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($r['name']) ?></div><div class="text-[11px] text-slate-400 uppercase"><?= $r['category'] ?></div></div></div><div class="text-xs text-slate-500">Rp <?= number_format($r['price'] ?? 0,0,',','.') ?></div></div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-slate-400 text-sm">Belum ada menu terbaru.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php elseif ($tab === 'management'): ?>
                    <!-- MANAGEMENT TAB: Katalog Menu (per kategori) dan Manajemen Meja -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2 bg-white rounded-xl shadow p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-lg font-bold text-slate-800">Katalog & Manajemen Menu</h2>
                                <button onclick="openAddModal()" class="bg-orange-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-orange-600 transition text-sm"><i class="fas fa-plus mr-2"></i>Tambah Menu</button>
                            </div>

                            <div class="overflow-x-auto">
                                <?php if (empty($menus)): ?>
                                    <div class="text-slate-400 text-center py-8">Belum ada menu terdaftar.</div>
                                <?php else: ?>
                                    <?php
                                        // Kelompokkan per kategori
                                        $byCategory = [];
                                        foreach ($menus as $m) {
                                            $cat = $m['category'] ?? 'lainnya';
                                            if (!isset($byCategory[$cat])) $byCategory[$cat] = [];
                                            $byCategory[$cat][] = $m;
                                        }
                                    ?>

                                    <?php foreach ($byCategory as $catName => $items): ?>
                                        <h3 class="font-bold text-slate-800 mt-4 mb-2"><?= ucfirst($catName) ?></h3>
                                        <table class="w-full text-left border-collapse mb-4">
                                            <thead>
                                                <tr class="border-b text-slate-400 text-sm font-medium">
                                                    <th class="pb-3">Foto</th>
                                                    <th class="pb-3">Nama Menu</th>
                                                    <th class="pb-3">Harga</th>
                                                    <th class="pb-3">Status</th>
                                                    <th class="pb-3 text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-100">
                                                <?php foreach($items as $m): ?>
                                                    <tr>
                                                        <td class="py-3"><img src="<?= $m['image'] ?>" class="w-12 h-12 object-cover rounded-lg"></td>
                                                        <td class="py-3">
                                                            <span class="font-semibold text-slate-800 block"><?= htmlspecialchars($m['name']) ?></span>
                                                            <span class="text-xs uppercase tracking-wider text-slate-400"><?= $m['category'] ?></span>
                                                        </td>
                                                        <td class="py-3 font-bold text-slate-900">Rp <?= number_format($m['price'] ?? 0, 0, ',', '.') ?></td>
                                                        <td class="py-3">
                                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold <?= ($m['status'] ?? '')==='available'?'bg-green-50 text-green-600':'bg-red-50 text-red-600' ?>">
                                                                <?= ($m['status'] ?? '')==='available'?'Tersedia':'Habis' ?>
                                                            </span>
                                                        </td>
                                                        <td class="py-3 text-center">
                                                            <div class="flex items-center justify-center space-x-1">
                                                                <button onclick="openDetailModal(<?= $m['id'] ?>)" class="text-blue-500 w-8 h-8 bg-blue-50 hover:bg-blue-100 rounded-lg" title="Detail Menu"><i class="fas fa-eye"></i></button>
                                                                <button onclick="openEditModal(<?= $m['id'] ?>)" class="text-amber-500 w-8 h-8 bg-amber-50 hover:bg-amber-100 rounded-lg" title="Edit Menu"><i class="fas fa-edit"></i></button>
                                                                <form action="?tab=dashboard&action=delete" method="POST" onsubmit="return confirm('Hapus menu ini secara permanen?')" class="inline">
                                                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                                                    <button type="submit" class="text-red-500 w-8 h-8 bg-red-50 hover:bg-red-100 rounded-lg" title="Hapus Menu"><i class="fas fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="xl:col-span-1 bg-white rounded-xl shadow p-6 h-fit">
                            <h2 class="text-lg font-bold text-slate-800 mb-2">Manajemen Meja</h2>
                            <p class="text-xs text-slate-400 mb-4">Tambah nomor baru atau hapus instalasi meja restoran.</p>

                            <form action="?tab=dashboard&action=add_table" method="POST" class="flex space-x-2 mb-6">
                                <input type="text" name="table_number" class="flex-1 border p-2 rounded-lg outline-none text-sm focus:ring-2 focus:ring-orange-500" placeholder="Contoh: Meja 21" required>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-bold text-sm"><i class="fas fa-plus"></i> Tambah</button>
                            </form>

                            <div class="overflow-y-auto max-h-[400px] border border-slate-100 rounded-lg divide-y divide-slate-100">
                                <?php foreach($tables as $t): ?>
                                    <div class="p-3 flex justify-between items-center bg-slate-50/50 hover:bg-slate-50 transition">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-chair text-slate-400 text-sm"></i>
                                            <div>
                                                <span class="font-bold text-slate-800 text-sm"><?= htmlspecialchars($t['table_number']) ?></span>
                                                <span class="block text-[10px] uppercase font-bold tracking-wider <?= ($t['status'] ?? '')==='empty'?'text-green-500':'text-amber-500' ?>"><?= ($t['status'] ?? '') === 'empty' ? 'Kosong' : 'Terisi' ?></span>
                                            </div>
                                        </div>
                                        <form action="?tab=dashboard&action=delete_table" method="POST" onsubmit="return confirm('Hapus pengaturan <?= $t['table_number'] ?> ini?')" class="inline">
                                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-1 text-sm transition" <?= ($t['status'] ?? '') !== 'empty' ? 'disabled class="opacity-30 cursor-not-allowed"' : '' ?> title="Hapus Meja"><i class="fas fa-minus-circle text-lg"></i></button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                <?php elseif ($tab === 'analytics'): ?>
                    <!-- ANALYTICS TAB -->
                    <div class="mb-6 flex space-x-2">
                        <button onclick="loadAnalytics('daily')" class="filter-btn bg-orange-500 text-white px-4 py-2 rounded-lg font-bold text-sm" data-period="daily">Harian</button>
                        <button onclick="loadAnalytics('weekly')" class="filter-btn bg-slate-200 text-slate-700 px-4 py-2 rounded-lg font-bold text-sm" data-period="weekly">Mingguan</button>
                        <button onclick="loadAnalytics('monthly')" class="filter-btn bg-slate-200 text-slate-700 px-4 py-2 rounded-lg font-bold text-sm" data-period="monthly">Bulanan</button>
                        <button onclick="loadAnalytics('all')" class="filter-btn bg-slate-200 text-slate-700 px-4 py-2 rounded-lg font-bold text-sm" data-period="all">Sepanjang Waktu</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</div>
                            <div class="text-2xl font-extrabold text-orange-600 mt-2">Rp <span id="totalIncome">0</span></div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pesanan</div>
                            <div class="text-2xl font-extrabold text-blue-600 mt-2"><span id="totalOrders">0</span> Pesanan</div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata Nilai Order</div>
                            <div class="text-2xl font-extrabold text-green-600 mt-2">Rp <span id="avgValue">0</span></div>
                        </div>
                    </div>

                    <!-- Charts Grid -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
                        <!-- Grafik Pendapatan & Pesanan -->
                        <div class="bg-white rounded-xl shadow p-6 col-span-1 xl:col-span-2">
                            <h2 class="text-lg font-bold text-slate-800 mb-4">Tren Pendapatan & Pesanan</h2>
                            <div id="chart_trend" style="width: 100%; height: 350px;"></div>
                        </div>

                        <!-- Pie Chart - Revenue by Category -->
                        <div class="bg-white rounded-xl shadow p-6">
                            <h2 class="text-lg font-bold text-slate-800 mb-4">Pendapatan Berdasarkan Kategori</h2>
                            <div id="chart_category" style="width: 100%; height: 350px;"></div>
                        </div>

                        <!-- Area Chart - Hourly Distribution -->
                        <div class="bg-white rounded-xl shadow p-6">
                            <h2 class="text-lg font-bold text-slate-800 mb-4">Distribusi Pesanan per Jam</h2>
                            <div id="chart_hourly" style="width: 100%; height: 350px;"></div>
                        </div>
                    </div>

                    <!-- Bar Chart - Top Selling Items -->
                    <div class="bg-white rounded-xl shadow p-6 col-span-1 xl:col-span-2">
                        <h2 class="text-lg font-bold text-slate-800 mb-4">Top 10 Menu Terlaris</h2>
                        <div id="chart_top_items" style="width: 100%; height: 400px;"></div>
                    </div>


                <?php elseif ($tab === 'users'): ?>
                    <!-- USER MANAGEMENT TAB -->
                    <div class="mb-6">
                        <button onclick="openCreateUserModal()" class="bg-green-500 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-600 transition text-sm"><i class="fas fa-user-plus mr-2"></i>Buat Akun Baru</button>
                    </div>

                    <div class="bg-white rounded-xl shadow p-6">
                        <h2 class="text-lg font-bold text-slate-800 mb-6">Daftar User Sistem</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b text-slate-400 text-sm font-medium">
                                        <th class="pb-3">ID</th>
                                        <th class="pb-3">Username</th>
                                        <th class="pb-3">Role</th>
                                        <th class="pb-3">Dibuat Pada</th>
                                        <th class="pb-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-100">
                                    <?php foreach($data['users'] as $u): ?>
                                        <tr>
                                            <td class="py-3 font-semibold text-slate-800">#<?= $u['id'] ?></td>
                                            <td class="py-3"><?= htmlspecialchars($u['username']) ?></td>
                                            <td class="py-3">
                                                <span class="px-3 py-1 rounded-full text-xs font-bold <?= match($u['role']) {
                                                    'waiter' => 'bg-blue-50 text-blue-600',
                                                    'kitchen' => 'bg-purple-50 text-purple-600',
                                                    'cashier' => 'bg-green-50 text-green-600',
                                                    'admin' => 'bg-orange-50 text-orange-600',
                                                    default => 'bg-slate-50 text-slate-600'
                                                } ?>">
                                                    <?= ucfirst($u['role']) ?>
                                                </span>
                                            </td>
                                            <td class="py-3 text-slate-500"><?= date('d M Y H:i', strtotime($u['created_at'])) ?></td>

                                            <td class="py-3 text-center">
                                                <div class="flex items-center justify-center space-x-1">
                                                    <button onclick="openEditUserModal(<?= $u['id'] ?>)" class="text-amber-500 w-8 h-8 bg-amber-50 hover:bg-amber-100 rounded-lg" title="Edit User"><i class="fas fa-edit"></i></button>
                                                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                                        <form action="?tab=users&action=delete_user" method="POST" onsubmit="return confirm('Hapus akun <?= htmlspecialchars($u['username']) ?> secara permanen?')" class="inline">
                                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                                            <button type="submit" class="text-red-500 w-8 h-8 bg-red-50 hover:bg-red-100 rounded-lg" title="Hapus User"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php endif; ?>

        </div>
    </div>

    <div id="add-menu-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl w-full max-w-xl p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-bold text-slate-800">Tambah Menu Kuliner Baru</h3>
                <button onclick="closeModal('add-menu-modal')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form action="?tab=dashboard&action=add" method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold block mb-1">Nama Menu</label><input type="text" name="name" class="w-full border p-2.5 rounded-lg outline-none" required></div>
                    <div><label class="text-xs font-bold block mb-1">Kategori</label><select name="category" class="w-full border p-2.5 rounded-lg bg-white"><option value="food">Makanan</option><option value="drink">Minuman</option><option value="snack">Cemilan</option></select></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold block mb-1">Harga (Rp)</label><input type="number" name="price" class="w-full border p-2.5 rounded-lg" required></div>
                    <div><label class="text-xs font-bold block mb-1">Status Ketersediaan</label><select name="status" class="w-full border p-2.5 rounded-lg bg-white"><option value="available">Tersedia</option><option value="unavailable">Habis</option></select></div>
                </div>
                <div><label class="text-xs font-bold block mb-1">URL Link Foto Menu</label><input type="url" name="image" class="w-full border p-2.5 rounded-lg" placeholder="https://picsum.photos/..."></div>
                <div><label class="text-xs font-bold block mb-1">Deskripsi Kuliner</label><textarea name="description" rows="2" class="w-full border p-2.5 rounded-lg"></textarea></div>
                <div class="flex justify-end space-x-2 border-t pt-3">
                    <button type="button" onclick="closeModal('add-menu-modal')" class="border px-4 py-2 rounded-lg font-bold text-sm">Batal</button>
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg font-bold text-sm">Simpan Menu</button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-menu-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl w-full max-w-xl p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-bold text-slate-800">Ubah Data Menu Kuliner</h3>
                <button onclick="closeModal('edit-menu-modal')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form action="?tab=dashboard&action=edit" method="POST" class="space-y-4">
                <input type="hidden" id="edit-id" name="id">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold block mb-1">Nama Menu</label><input type="text" id="edit-name" name="name" class="w-full border p-2.5 rounded-lg outline-none" required></div>
                    <div><label class="text-xs font-bold block mb-1">Kategori</label><select id="edit-category" name="category" class="w-full border p-2.5 rounded-lg bg-white"><option value="food">Makanan</option><option value="drink">Minuman</option><option value="snack">Cemilan</option></select></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-xs font-bold block mb-1">Harga (Rp)</label><input type="number" id="edit-price" name="price" class="w-full border p-2.5 rounded-lg" required></div>
                    <div><label class="text-xs font-bold block mb-1">Status Ketersediaan</label><select id="edit-status" name="status" class="w-full border p-2.5 rounded-lg bg-white"><option value="available">Tersedia</option><option value="unavailable">Habis</option></select></div>
                </div>
                <div><label class="text-xs font-bold block mb-1">URL Link Foto Menu</label><input type="url" id="edit-image" name="image" class="w-full border p-2.5 rounded-lg"></div>
                <div><label class="text-xs font-bold block mb-1">Deskripsi Kuliner</label><textarea id="edit-description" name="description" rows="2" class="w-full border p-2.5 rounded-lg"></textarea></div>
                <div class="flex justify-end space-x-2 border-t pt-3">
                    <button type="button" onclick="closeModal('edit-menu-modal')" class="border px-4 py-2 rounded-lg font-bold text-sm">Batal</button>
                    <button type="submit" class="bg-amber-500 text-white px-4 py-2 rounded-lg font-bold text-sm">Perbarui Menu</button>
                </div>
            </form>
        </div>
    </div>

    <div id="detail-menu-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 overflow-hidden">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-bold text-slate-800">Detail Menu Kuliner</h3>
                <button onclick="closeModal('detail-menu-modal')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="space-y-4">
                <img id="detail-img" class="w-full h-48 object-cover rounded-xl shadow-inner bg-slate-100" src="" alt="Preview">
                <div>
                    <h2 id="detail-title" class="text-xl font-extrabold text-slate-800">--</h2>
                    <span id="detail-badge" class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider">--</span>
                </div>
                <div class="border-y py-2 grid grid-cols-2 gap-2 text-sm">
                    <div><span class="text-slate-400 block text-xs">Harga Jual</span><b id="detail-price" class="text-orange-500 text-base font-bold">Rp 0</b></div>
                    <div><span class="text-slate-400 block text-xs">Ketersediaan</span><b id="detail-status">--</b></div>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs mb-1">Deskripsi Resep / Produk</span>
                    <p id="detail-desc" class="text-sm text-slate-600 bg-slate-50 p-3 rounded-lg leading-relaxed italic">Tidak ada deskripsi tambahan.</p>
                </div>
            </div>
            <div class="flex justify-end mt-5">
                <button onclick="closeModal('detail-menu-modal')" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2 rounded-lg font-bold text-sm">Tutup Dialog</button>
            </div>
        </div>
    </div>

    <div id="create-user-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-bold text-slate-800">Buat Akun Pengguna Baru</h3>
                <button onclick="closeModal('create-user-modal')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form action="?tab=users&action=create_user" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs font-bold block mb-1">Username</label>
                    <input type="text" name="username" class="w-full border p-2.5 rounded-lg outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan username" required>
                </div>
                <div>
                    <label class="text-xs font-bold block mb-1">Password</label>
                    <input type="password" id="new-password" name="password" class="w-full border p-2.5 rounded-lg outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan password" required>
                </div>
                <div>
                    <label class="text-xs font-bold block mb-1">Role / Jabatan</label>
                    <select name="role" class="w-full border p-2.5 rounded-lg bg-white focus:ring-2 focus:ring-green-500">
                        <option value="waiter">Pelayan</option>
                        <option value="kitchen">Dapur / Chef</option>
                        <option value="cashier">Kasir</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2 border-t pt-3">
                    <button type="button" onclick="closeModal('create-user-modal')" class="border px-4 py-2 rounded-lg font-bold text-sm">Batal</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg font-bold text-sm">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-user-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-bold text-slate-800">Edit Data User</h3>
                <button onclick="closeModal('edit-user-modal')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form action="?tab=users&action=edit_user" method="POST" class="space-y-4">
                <input type="hidden" id="edit-user-id" name="id">
                <div>
                    <label class="text-xs font-bold block mb-1">Username</label>
                    <input type="text" id="edit-user-username" name="username" class="w-full border p-2.5 rounded-lg outline-none focus:ring-2 focus:ring-amber-500" required>
                </div>
                <div>
                    <label class="text-xs font-bold block mb-1">Password Baru <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="password" name="password" class="w-full border p-2.5 rounded-lg outline-none focus:ring-2 focus:ring-amber-500" placeholder="Kosongkan jika tidak ingin ganti password">
                </div>
                
                <div class="flex justify-end space-x-2 border-t pt-3">
                    <button type="button" onclick="closeModal('edit-user-modal')" class="border px-4 py-2 rounded-lg font-bold text-sm">Batal</button>
                    <button type="submit" class="bg-amber-500 text-white px-4 py-2 rounded-lg font-bold text-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <?php renderToast(); ?>

    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        // Load Google Charts
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(() => {
            const tab = '<?= htmlspecialchars($tab) ?>';
            if (tab === 'analytics') {
                loadAnalytics('daily');
            }
        });

        function openAddModal() {
            const m = document.getElementById('add-menu-modal');
            m.classList.remove('hidden'); m.classList.add('flex');
        }

        function openCreateUserModal() {
            const m = document.getElementById('create-user-modal');
            m.classList.remove('hidden'); m.classList.add('flex');
        }

        function openEditUserModal(id) {
            fetch('?tab=users&action=get_user&id=' + id)
            .then(res => res.json())
            .then(data => {
                if(data.error) return alert(data.error);
                
                // Isi otomatis form edit dengan data user dari database
                document.getElementById('edit-user-id').value = data.id;
                document.getElementById('edit-user-username').value = data.username;
                
                const m = document.getElementById('edit-user-modal');
                m.classList.remove('hidden'); 
                m.classList.add('flex');
            });
        }

        function openEditModal(id) {
            fetch('?tab=dashboard&action=get_menu&id=' + id)
            .then(res => res.json())
            .then(data => {
                if(data.error) return alert(data.error);
                
                // Isi otomatis form edit modal box
                document.getElementById('edit-id').value = data.id;
                document.getElementById('edit-name').value = data.name;
                document.getElementById('edit-category').value = data.category;
                document.getElementById('edit-price').value = data.price;
                document.getElementById('edit-status').value = data.status;
                document.getElementById('edit-image').value = data.image;
                document.getElementById('edit-description').value = data.description;

                const m = document.getElementById('edit-menu-modal');
                m.classList.remove('hidden'); m.classList.add('flex');
            });
        }

        function openDetailModal(id) {
            fetch('?tab=dashboard&action=get_menu&id=' + id)
            .then(res => res.json())
            .then(data => {
                if(data.error) return alert(data.error);
                
                document.getElementById('detail-img').src = data.image;
                document.getElementById('detail-title').textContent = data.name;
                document.getElementById('detail-badge').textContent = data.category;
                document.getElementById('detail-badge').className = "inline-block mt-1 px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider bg-slate-100 text-slate-600";
                
                document.getElementById('detail-price').textContent = "Rp " + parseInt(data.price).toLocaleString('id-ID');
                
                const statusTxt = document.getElementById('detail-status');
                if (data.status === 'available') {
                    statusTxt.textContent = 'Tersedia'; statusTxt.className = 'text-green-600 text-sm font-bold';
                } else {
                    statusTxt.textContent = 'Habis'; statusTxt.className = 'text-red-600 text-sm font-bold';
                }

                document.getElementById('detail-desc').textContent = data.description ? data.description : 'Tidak ada deskripsi tambahan.';
                
                const m = document.getElementById('detail-menu-modal');
                m.classList.remove('hidden'); m.classList.add('flex');
            });
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            m.classList.add('hidden'); m.classList.remove('flex');
        }

        function loadAnalytics(period) {
            // Update filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-orange-500', 'text-white');
                btn.classList.add('bg-slate-200', 'text-slate-700');
            });
            document.querySelector(`[data-period="${period}"]`).classList.remove('bg-slate-200', 'text-slate-700');
            document.querySelector(`[data-period="${period}"]`).classList.add('bg-orange-500', 'text-white');

            // Fetch analytics data
            fetch('?tab=analytics&action=get_analytics&period=' + period)
            .then(res => res.json())
            .then(data => {
                // Update counters
                document.getElementById('totalIncome').textContent = parseInt(data.totalIncome).toLocaleString('id-ID');
                document.getElementById('totalOrders').textContent = data.totalOrders;
                document.getElementById('avgValue').textContent = parseInt(data.avgOrderValue).toLocaleString('id-ID');

                // Draw Trend Line Chart (Pendapatan & Pesanan)
                drawTrendChart(data.dailyData);

                // Draw Category Pie Chart
                drawCategoryChart(data.categoryData);

                // Draw Hourly Area Chart
                drawHourlyChart(data.hourlyData);

                // Draw Top Items Bar Chart
                drawTopItemsChart(data.topItems);
            });
        }

        function drawTrendChart(dailyData) {
            const chartData = [['Tanggal', 'Pendapatan (Rp)', { role: 'annotation' }, 'Pesanan', { role: 'annotation' }]];
            dailyData.forEach(row => {
                chartData.push([
                    new Date(row.date).toLocaleDateString('id-ID', { month: 'short', day: 'numeric' }),
                    parseInt(row.income),
                    'Rp ' + parseInt(row.income).toLocaleString('id-ID'),
                    parseInt(row.orders),
                    row.orders + ' pesanan'
                ]);
            });

            const options = {
                title: 'Tren Pendapatan & Pesanan',
                titleTextStyle: { fontSize: 14, bold: true, color: '#1e293b' },
                curveType: 'function',
                legend: { position: 'bottom', textStyle: { color: '#64748b' } },
                pointSize: 6,
                hAxis: {
                    title: 'Tanggal',
                    titleTextStyle: { color: '#64748b', italic: false, bold: true },
                    textStyle: { color: '#64748b' },
                    gridlines: { color: '#e2e8f0' }
                },
                vAxis: {
                    title: 'Nilai (Rp)',
                    titleTextStyle: { color: '#64748b', italic: false, bold: true },
                    textStyle: { color: '#64748b' },
                    gridlines: { color: '#e2e8f0' },
                    format: 'Rp #,###'
                },
                colors: ['#f97316', '#3b82f6'],
                animation: { duration: 1000, easing: 'out' }
            };

            const data_table = google.visualization.arrayToDataTable(chartData);
            const chart = new google.visualization.LineChart(document.getElementById('chart_trend'));
            chart.draw(data_table, options);
        }

        function drawCategoryChart(categoryData) {
            if (categoryData.length === 0) {
                document.getElementById('chart_category').innerHTML = '<p class="text-center text-slate-500 mt-20">Belum ada data kategori</p>';
                return;
            }

            const chartData = [['Kategori', 'Pendapatan']];
            categoryData.forEach(row => {
                chartData.push([
                    row.category.charAt(0).toUpperCase() + row.category.slice(1),
                    parseInt(row.revenue)
                ]);
            });

            const options = {
                title: 'Pendapatan Berdasarkan Kategori',
                titleTextStyle: { fontSize: 14, bold: true, color: '#1e293b' },
                pieHole: 0.4,
                legend: { position: 'bottom', textStyle: { color: '#64748b' } },
                colors: ['#f97316', '#3b82f6', '#10b981', '#8b5cf6', '#ec4899'],
                animation: { duration: 1000, easing: 'out' }
            };

            const data_table = google.visualization.arrayToDataTable(chartData);
            const chart = new google.visualization.PieChart(document.getElementById('chart_category'));
            chart.draw(data_table, options);
        }

        function drawHourlyChart(hourlyData) {
            if (hourlyData.length === 0) {
                document.getElementById('chart_hourly').innerHTML = '<p class="text-center text-slate-500 mt-20">Belum ada data per jam</p>';
                return;
            }

            const chartData = [['Jam', 'Pesanan', 'Pendapatan']];
            hourlyData.forEach(row => {
                chartData.push([
                    row.hour + ':00',
                    parseInt(row.orders),
                    parseInt(row.revenue)
                ]);
            });

            const options = {
                title: 'Distribusi Pesanan per Jam',
                titleTextStyle: { fontSize: 14, bold: true, color: '#1e293b' },
                legend: { position: 'bottom', textStyle: { color: '#64748b' } },
                hAxis: {
                    title: 'Jam',
                    titleTextStyle: { color: '#64748b', italic: false, bold: true },
                    textStyle: { color: '#64748b' }
                },
                vAxis: {
                    title: 'Jumlah Pesanan',
                    titleTextStyle: { color: '#64748b', italic: false, bold: true },
                    textStyle: { color: '#64748b' }
                },
                colors: ['#06b6d4'],
                animation: { duration: 1000, easing: 'out' }
            };

            const data_table = google.visualization.arrayToDataTable(chartData);
            const chart = new google.visualization.AreaChart(document.getElementById('chart_hourly'));
            chart.draw(data_table, options);
        }

        function toggleSidebar(forceOpen) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            // Hanya berlaku untuk mobile (desktop selalu tampil)
            if (window.innerWidth >= 1024) return;

            const isHidden = sidebar.classList.contains('-translate-x-full');

            if (typeof forceOpen === 'boolean') {
                if (forceOpen && isHidden) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                } else if (!forceOpen && !isHidden) {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
                return;
            }

            // Toggle biasa
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden', !sidebar.classList.contains('-translate-x-full'));
        }

        // Tutup sidebar dengan Escape jika sedang terbuka (mobile)
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && window.innerWidth < 1024) {
                toggleSidebar(false);
            }
        });

        function drawTopItemsChart(topItems) {
            if (topItems.length === 0) {
                document.getElementById('chart_top_items').innerHTML = '<p class="text-center text-slate-500 mt-40">Belum ada data menu terlaris</p>';
                return;
            }

            const chartData = [['Menu', 'Terjual', 'Pendapatan']];
            topItems.forEach(row => {
                chartData.push([
                    row.name,
                    parseInt(row.total_sold),
                    parseInt(row.revenue)
                ]);
            });

            const options = {
                title: 'Top 10 Menu Terlaris',
                titleTextStyle: { fontSize: 14, bold: true, color: '#1e293b' },
                legend: { position: 'bottom', textStyle: { color: '#64748b' } },
                hAxis: {
                    title: 'Menu',
                    titleTextStyle: { color: '#64748b', italic: false, bold: true },
                    textStyle: { color: '#64748b', fontSize: 10 },
                    slantedText: true,
                    slantedTextAngle: 45
                },
                vAxis: {
                    title: 'Jumlah Terjual',
                    titleTextStyle: { color: '#64748b', italic: false, bold: true },
                    textStyle: { color: '#64748b' }
                },
                colors: ['#ef4444', '#f59e0b'],
                animation: { duration: 1000, easing: 'out' },
                bar: { groupWidth: '75%' }
            };

            const data_table = google.visualization.arrayToDataTable(chartData);
            const chart = new google.visualization.ColumnChart(document.getElementById('chart_top_items'));
            chart.draw(data_table, options);
        }

        window.addEventListener('resize', () => {
            const tab = '<?= htmlspecialchars($tab) ?>';
            if (tab === 'analytics') {
                // Redraw all charts on resize
                const period = document.querySelector('.filter-btn.bg-orange-500')?.dataset?.period || 'daily';
                loadAnalytics(period);
            }
        });
    </script>

    <?php if(isset($_GET['msg'])): ?>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const type = "<?= isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'success' ?>";
                showNotification("<?= htmlspecialchars($_GET['msg']) ?>", type);
            });
        </script>
    <?php endif; ?>
</body>
</html>
