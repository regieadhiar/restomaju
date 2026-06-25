<?php

function handleCashierRequest(PDO $conn): array {
    if (isset($_GET['action']) && $_GET['action'] === 'get_bill' && isset($_GET['table_id'])) {
        header('Content-Type: application/json');
        $tid = intval($_GET['table_id']);

        $stmtOrder = $conn->prepare("SELECT * FROM orders WHERE table_id = ? AND status = 'ready' ORDER BY id DESC LIMIT 1");
        $stmtOrder->execute([$tid]);
        $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $tableQuery = $conn->prepare("SELECT table_number FROM restaurant_tables WHERE id = ?");
            $tableQuery->execute([$tid]);
            $tableInfo = $tableQuery->fetch(PDO::FETCH_ASSOC);

            $stmtItems = $conn->prepare("SELECT oi.*, m.name FROM order_items oi JOIN menu_items m ON oi.menu_id = m.id WHERE oi.order_id = ?");
            $stmtItems->execute([$order['id']]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'table_number' => $tableInfo['table_number'],
                'customer_name'=> $order['customer_name'],
                'order_time'   => date('H:i:s', strtotime($order['created_at'])),
                'total_amount' => $order['total_amount'],
                'items'        => $items
            ]);
        } else {
            echo json_encode(['error' => 'Tidak ada tagihan aktif']);
        }
        exit;
    }

    // Validate discount code
    if (isset($_GET['action']) && $_GET['action'] === 'validate_discount') {
        header('Content-Type: application/json');
        $code = strtoupper(trim($_GET['code'] ?? ''));
        
        if (empty($code)) {
            echo json_encode(['valid' => false, 'message' => 'Kode diskon tidak boleh kosong']);
            exit;
        }

        $stmt = $conn->prepare("SELECT discount_percent FROM discount_codes WHERE code = ? AND is_active = 1");
        $stmt->execute([$code]);
        $discount = $stmt->fetchColumn();

        if ($discount !== false) {
            echo json_encode(['valid' => true, 'discount_percent' => $discount]);
        } else {
            echo json_encode(['valid' => false, 'message' => 'Kode diskon tidak valid atau tidak aktif']);
        }
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'pay') {
        $table_id = intval($_POST['table_id']);
        $tip = max(0, floatval($_POST['tip'] ?? 0));
        $discount_percent = max(0, min(100, floatval($_POST['discount_percent'] ?? 0)));
        
        try {
            $conn->beginTransaction();

            $updOrder = $conn->prepare("UPDATE orders SET status = 'paid', tip_amount = ?, discount_percent = ? WHERE table_id = ? AND status = 'ready'");
            $updOrder->execute([$tip, $discount_percent, $table_id]);

            $updTable = $conn->prepare("UPDATE restaurant_tables SET status = 'empty' WHERE id = ?");
            $updTable->execute([$table_id]);

            $conn->commit();
            header("Location: cashier.php?msg=Pembayaran Sukses!");
        } catch (Exception $e) {
            $conn->rollBack();
            die($e->getMessage());
        }
        exit;
    }

    return [
        'tables' => $conn->query("SELECT * FROM restaurant_tables")->fetchAll(PDO::FETCH_ASSOC)
    ];
}
