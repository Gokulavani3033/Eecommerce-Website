<?php
session_start();
require_once 'config/database.php';
include 'includes/header.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. Please add some products before checking out.</p>";
    include 'includes/footer.php';
    exit;
}

$product_ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));

$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($product_ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($products as $product) {
    $total += $product['price'] * $_SESSION['cart'][$product['id']];
}
?>

<h1>Checkout</h1>
<form action="process_order.php" method="post">
    <h2>Order Summary</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo $_SESSION['cart'][$product['id']]; ?></td>
                    <td>$<?php echo number_format($product['price'] * $_SESSION['cart'][$product['id']], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
        </tfoot>
    </table>

    <h2>Shipping Information</h2>
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" required>
    </div>
    <div class="mb-3">
    <label for="city" class="form-label">City</label>
        <input type="text" class="form-control" id="city" name="city" required>
    </div>
    <div class="mb-3">
        <label for="state" class="form-label">State</label>
        <input type="text" class="form-control" id="state" name="state" required>
    </div>
    <div class="mb-3">
        <label for="zip" class="form-label">ZIP Code</label>
        <input type="text" class="form-control" id="zip" name="zip" required>
    </div>

    <h2>Payment Information</h2>
    <div class="mb-3">
        <label for="card_number" class="form-label">Card Number</label>
        <input type="text" class="form-control" id="card_number" name="card_number" required>
    </div>
    <div class="mb-3">
        <label for="expiry_date" class="form-label">Expiry Date</label>
        <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
    </div>
    <div class="mb-3">
        <label for="cvv" class="form-label">CVV</label>
        <input type="text" class="form-control" id="cvv" name="cvv" required>
    </div>

    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
    <button type="submit" class="btn btn-primary">Place Order</button>
</form>

<?php include 'includes/footer.php'; ?>