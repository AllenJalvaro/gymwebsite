<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];

        // Check if the product exists
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product && $quantity > 0) {
            // Update the quantity in the session
            $_SESSION['cart'][$product_id] = $quantity;

            // Calculate new total and subtotal
            $total = (float)$product['price'] * $quantity;
            $subtotal = calculateSubtotal($_SESSION['cart']);

            echo json_encode(['total' => number_format($total, 2), 'subtotal' => number_format($subtotal, 2)]);
        }
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
