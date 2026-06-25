<?php

function handleWaiterRequest(PDO $conn): array {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'place_order') {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['table_id']) || empty($input['customer_name']) || empty($input['cart'])) {
            echo json_encode(['status' => 'error', 'message' => 'Data pesanan tidak lengkap']);
            exit;
        }

        try {
            $conn->beginTransaction();

            $total = 0;
            foreach ($input['cart'] as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $notes = !empty($input['notes']) ? $input['notes'] : null;
            $stmtOrder = $conn->prepare("INSERT INTO orders (table_id, customer_name, total_amount, status, notes) VALUES (?, ?, ?, 'pending', ?)");
            $stmtOrder->execute([$input['table_id'], $input['customer_name'], $total, $notes]);
            $order_id = $conn->lastInsertId();

            $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($input['cart'] as $item) {
                $stmtItem->execute([$order_id, $item['id'], $item['qty'], $item['price']]);
            }

            $time_now = date('H:i:s');
            $stmtTable = $conn->prepare("UPDATE restaurant_tables SET status = 'occupied', customer_name = ?, order_time = ? WHERE id = ?");
            $stmtTable->execute([$input['customer_name'], $time_now, $input['table_id']]);

            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dikirim ke dapur!']);
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

        exit;
    }

    return [
        'tablesQuery' => $conn->query("SELECT * FROM restaurant_tables")->fetchAll(PDO::FETCH_ASSOC),
        'menuQuery'   => $conn->query("SELECT * FROM menu_items")->fetchAll(PDO::FETCH_ASSOC),
    ];
}
