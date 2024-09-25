require_once 'config/database.php';
<?php
try {
    // Sample products data
    $products = [
        [
            'name' => 'Smartphone',
            'description' => 'fleshy, round fruits that come in many varieties and colors.',
            'price' => 100,
            'image' => 'images/Smartphone.jpg'
        ],
        [
            'name' => 'Laptop pro',
            'description' => 'High-performance laptop for professionals and gamers.',
            'price' => 30,000,
            'image' => 'images/laptop.jpg'
        ],
        [
            'name' => 'wireless earburds',
            'description' => ' a drink made from fruit or vegetables, or to the liquid that drips from cooked meat or other food.',
            'price' => 60,
            'image' => 'images/wireless earburds.jpg'
        ],
        [
            'name' => 'Watch',
            'description' => 'Fitness tracker and smartwatch with heart rate monitoring and GPS.',
            'price' => 600,
            'image' => 'images/watch.jpg'
        ],
        [
            'name' => 'Dairy milk',
            'description' => 'combines the finest ingredients and flavours that bring the delicious taste of generosity to every slab..',
            'price' => 50,
            'image' => 'images/dairymilk.jpg'
        ]
    ];

    // Insert products
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    
    foreach ($products as $product) {
        $stmt->execute([
            $product['name'],
            $product['description'],
            $product['price'],
            $product['image']
        ]);
        echo "Inserted product: " . $product['name'] . "\n";
    }

    // Sample user data
    $users = [
        [
            'username' => 'john_doe',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'email' => 'john@example.com'
        ],
        [
            'username' => 'jane_smith',
            'password' => password_hash('securepass456', PASSWORD_DEFAULT),
            'email' => 'jane@example.com'
        ]
    ];

    // Insert users
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    
    foreach ($users as $user) {
        $stmt->execute([
            $user['username'],
            $user['password'],
            $user['email']
        ]);
        echo "Inserted user: " . $user['username'] . "\n";
    }

    echo "Sample data inserted successfully.\n";

} catch (PDOException $e) {
    die("Error inserting sample data: " . $e->getMessage());
}
?>