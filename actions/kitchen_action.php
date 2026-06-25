<?php

function handleKitchenRequest(PDO $conn): array {
    if (isset($_GET['action']) && $_GET['action'] === 'complete_order' && isset($_GET['id'])) {
        $order_id = intval($_GET['id']);
        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("SELECT table_id FROM orders WHERE id = ?");
            $stmt->execute([$order_id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                $updOrder = $conn->prepare("UPDATE orders SET status = 'ready' WHERE id = ?");
                $updOrder->execute([$order_id]);

                $updTable = $conn->prepare("UPDATE restaurant_tables SET status = 'ready' WHERE id = ?");
                $updTable->execute([$order['table_id']]);
            }

            $conn->commit();
            header("Location: kitchen.php?msg=Pesanan diselesaikan!");
        } catch (Exception $e) {
            $conn->rollBack();
            die($e->getMessage());
        }
        exit;
    }

    // Toggle menu availability
    if (isset($_GET['action']) && $_GET['action'] === 'toggle_menu' && isset($_GET['id'])) {
        $menu_id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT status FROM menu_items WHERE id = ?");
        $stmt->execute([$menu_id]);
        $current = $stmt->fetchColumn();
        $newStatus = ($current === 'available') ? 'unavailable' : 'available';
        $upd = $conn->prepare("UPDATE menu_items SET status = ? WHERE id = ?");
        $upd->execute([$newStatus, $menu_id]);
        header("Location: kitchen.php?msg=Status menu diperbarui!");
        exit;
    }

    return [
        'orders' => $conn->query("SELECT o.*, t.table_number FROM orders o JOIN restaurant_tables t ON o.table_id = t.id WHERE o.status = 'pending' ORDER BY o.id ASC")->fetchAll(PDO::FETCH_ASSOC),
        'menus' => $conn->query("SELECT id, name, category, price, status FROM menu_items ORDER BY category, name")->fetchAll(PDO::FETCH_ASSOC)
    ];
}
