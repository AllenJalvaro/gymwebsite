<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
        // Remove the product from the session
        unset($_SESSION['cart'][$_GET['remove']]);

        // Calculate new subtotal
        $subtotal = calculateSubtotal($_SESSION['cart']);

        echo json_encode(['subtotal' => number_format($subtotal, 2)]);
    }
}

function calculateSubtotal($cart) {
    $subtotal = 0.00;
    foreach ($cart as $product_id => $quantity) {
        $stmt = $GLOBALS['pdo']->prepare('SELECT price FROM products WHERE id = ?');
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $subtotal += (float)$product['price'] * (int)$quantity;
    }
    return $subtotal;
}
?>
