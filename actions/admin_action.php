<?php

function handleAdminRequest(PDO $conn): array {
    if (isset($_GET['action']) && $_GET['action'] === 'get_menu' && isset($_GET['id'])) {
        header('Content-Type: application/json');
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
        $stmt->execute([$id]);
        $menu = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($menu ? $menu : ['error' => 'Menu tidak ditemukan']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
        if ($_GET['action'] === 'add') {
            $name = trim($_POST['name']);
            $cat  = $_POST['category'];
            $prc  = intval($_POST['price']);
            $sts  = $_POST['status'];
            $dsc  = trim($_POST['description']);
            $img  = !empty($_POST['image']) ? trim($_POST['image']) : 'https://picsum.photos/300/200?random=food';

            $stmt = $conn->prepare("INSERT INTO menu_items (name, category, price, status, image, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $cat, $prc, $sts, $img, $dsc]);
            header("Location: admin.php?msg=Menu baru berhasil ditambahkan!");
            exit;
        }

        if ($_GET['action'] === 'edit') {
            $id   = intval($_POST['id']);
            $name = trim($_POST['name']);
            $cat  = $_POST['category'];
            $prc  = intval($_POST['price']);
            $sts  = $_POST['status'];
            $dsc  = trim($_POST['description']);
            $img  = !empty($_POST['image']) ? trim($_POST['image']) : 'https://picsum.photos/300/200?random=food';

            $stmt = $conn->prepare("UPDATE menu_items SET name = ?, category = ?, price = ?, status = ?, image = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $cat, $prc, $sts, $img, $dsc, $id]);
            header("Location: admin.php?msg=Menu berhasil diperbarui!");
            exit;
        }

        if ($_GET['action'] === 'delete' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: admin.php?msg=Menu berhasil dihapus!");
            exit;
        }

        if ($_GET['action'] === 'add_table') {
            $table_number = trim($_POST['table_number']);
            $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM restaurant_tables WHERE table_number = ?");
            $stmtCheck->execute([$table_number]);
            if ($stmtCheck->fetchColumn() > 0) {
                header("Location: admin.php?msg=Nomor meja sudah terdaftar!&type=error");
            } else {
                $stmt = $conn->prepare("INSERT INTO restaurant_tables (table_number, status) VALUES (?, 'empty')");
                $stmt->execute([$table_number]);
                header("Location: admin.php?msg=Meja baru berhasil ditambahkan!");
            }
            exit;
        }

        if ($_GET['action'] === 'delete_table' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $stmtCheck = $conn->prepare("SELECT status FROM restaurant_tables WHERE id = ?");
            $stmtCheck->execute([$id]);
            $status = $stmtCheck->fetchColumn();

            if ($status !== 'empty') {
                header("Location: admin.php?msg=Gagal! Meja sedang aktif digunakan pelanggan.&type=warning");
            } else {
                $stmt = $conn->prepare("DELETE FROM restaurant_tables WHERE id = ?");
                $stmt->execute([$id]);
                header("Location: admin.php?msg=Meja berhasil dihapus dari sistem!");
            }
            exit;
        }
    }

    // Handle analytics data request
    if (isset($_GET['action']) && $_GET['action'] === 'get_analytics') {
        header('Content-Type: application/json');
        $period = $_GET['period'] ?? 'daily';
        
        $dateFilter = match($period) {
            'daily' => "DATE(created_at) = CURRENT_DATE",
            'weekly' => "YEARWEEK(created_at) = YEARWEEK(NOW())",
            'monthly' => "YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW())",
            'all' => "1=1",
            default => "DATE(created_at) = CURRENT_DATE"
        };

        // Revenue by category
        $categoryData = $conn->query("
            SELECT mi.category, SUM(oi.quantity * oi.price) as revenue, COUNT(DISTINCT o.id) as orders
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN menu_items mi ON oi.menu_id = mi.id
            WHERE o.status = 'paid' AND $dateFilter
            GROUP BY mi.category
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Top selling items
        $topItems = $conn->query("
            SELECT mi.name, SUM(oi.quantity) as total_sold, SUM(oi.quantity * oi.price) as revenue
            FROM order_items oi
            JOIN menu_items mi ON oi.menu_id = mi.id
            JOIN orders o ON oi.order_id = o.id
            WHERE o.status = 'paid' AND $dateFilter
            GROUP BY oi.menu_id
            ORDER BY total_sold DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Hourly distribution
        $hourlyData = $conn->query("
            SELECT HOUR(created_at) as hour, COUNT(*) as orders, SUM(total_amount) as revenue
            FROM orders
            WHERE status = 'paid' AND $dateFilter
            GROUP BY HOUR(created_at)
            ORDER BY hour ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $stats = [
            'totalIncome' => $conn->query("SELECT SUM(total_amount) FROM orders WHERE status = 'paid' AND $dateFilter")->fetchColumn() ?? 0,
            'totalOrders' => $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'paid' AND $dateFilter")->fetchColumn() ?? 0,
            'avgOrderValue' => $conn->query("SELECT AVG(total_amount) FROM orders WHERE status = 'paid' AND $dateFilter")->fetchColumn() ?? 0,
            'dailyData' => $conn->query("SELECT DATE(created_at) as date, SUM(total_amount) as income, COUNT(*) as orders FROM orders WHERE status = 'paid' AND $dateFilter GROUP BY DATE(created_at) ORDER BY date ASC")->fetchAll(PDO::FETCH_ASSOC),
            'categoryData' => $categoryData,
            'topItems' => $topItems,
            'hourlyData' => $hourlyData,
        ];
        
        echo json_encode($stats);
        exit;
    }

    // Handle create new user
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'create_user') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = $_POST['role'] ?? 'waiter';

        if (empty($username) || empty($password)) {
            header("Location: admin.php?tab=users&msg=Username dan password tidak boleh kosong!&type=error");
            exit;
        }

        $hashedPass = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashedPass, $role]);
            header("Location: admin.php?tab=users&msg=Akun baru berhasil dibuat!&type=success");
        } catch(Exception $e) {
            header("Location: admin.php?tab=users&msg=Username sudah terdaftar!&type=error");
        }
        exit;
    }

    return [
        'incomeToday' => $conn->query("SELECT SUM(total_amount) FROM orders WHERE status = 'paid' AND DATE(created_at) = CURRENT_DATE")->fetchColumn() ?? 0,
        'totalOrders' => $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'paid' AND DATE(created_at) = CURRENT_DATE")->fetchColumn() ?? 0,
        'activeMenu'  => $conn->query("SELECT COUNT(*) FROM menu_items WHERE status = 'available'")->fetchColumn() ?? 0,
        'occupiedTbl' => $conn->query("SELECT COUNT(*) FROM restaurant_tables WHERE status != 'empty'")->fetchColumn() ?? 0,
        'menus'       => $conn->query("SELECT * FROM menu_items ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC),
        'tables'      => $conn->query("SELECT * FROM restaurant_tables ORDER BY table_number ASC")->fetchAll(PDO::FETCH_ASSOC),
        'users'       => $conn->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC),
    ];
}
