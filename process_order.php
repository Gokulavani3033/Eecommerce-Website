<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

// Validate and sanitize input
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
$state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
$zip = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_STRING);
$card_number = filter_input(INPUT_POST, 'card_number', FILTER_SANITIZE_STRING);
$expiry_date = filter_input(INPUT_POST, 'expiry_date', FILTER_SANITIZE_STRING);
$cvv = filter_input(INPUT_POST, 'cvv', FILTER_SANITIZE_STRING);
$total_amount = filter_input(INPUT_POST, 'total_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

// Validate input (add more thorough validation as needed)
if (!$name || !$email || !$address || !$city || !$state || !$zip || !$card_number || !$expiry_date || !$cvv || !$total_amount) {
    die('Invalid input. Please fill out all fields.');
}

try {
    $pdo->beginTransaction();

    // Insert order into the database
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
    $stmt->execute([1, $total_amount]); // Replace 1 with actual user_id if you have user authentication
    $order_id = $pdo->lastInsertId();

    // Insert order items
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $product_stmt->execute([$product_id]);
        $product = $product_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            $stmt->execute([$order_id, $product_id, $quantity, $product['price']]);
        }
    }

    $pdo->commit();

    // Clear the cart
    unset($_SESSION['cart']);

    // Redirect to a thank you page or order confirmation page
    header('Location: order_confirmation.php?order_id=' . $order_id);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die('An error occurred while processing your order. Please try again later.');
}
?>