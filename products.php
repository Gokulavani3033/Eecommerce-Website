<?php
require_once 'config/database.php';
include 'includes/header.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        ?>
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <img src="<?php echo !empty($product['image']) ? htmlspecialchars($product['image']) : 'images/default-placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid mb-3">
        <?php echo "<!-- Debug: Image path is '" . htmlspecialchars($product['image']) . "' -->"; ?>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
        <form action="cart.php" method="post">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="number" name="quantity" value="1" min="1" class="form-control mb-2" style="width: 100px;">
            <button type="submit" class="btn btn-primary">Add to Cart</button>
        </form>
        <?php
    } else {
        echo "<p>Product not found.</p>";
    }
} 
else {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($products)) {
        echo "<!-- Debug: First product data: ";
        print_r($products[0]);
        echo " -->";
    }
    ?>
    <h1>Our Products</h1>   
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
                        <a href="products.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

include 'includes/footer.php';
?>
