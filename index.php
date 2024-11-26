<?php
session_start();

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost:3306;dbname=produits_db", 'root', '123456');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les produits
try {
    $sql = "SELECT * FROM produits";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erreur lors de la récupération des produits : " . $e->getMessage());
}

// Si aucun produit n'est trouvé, initialisez un tableau vide
if (!$products) {
    $products = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Shop</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/lux/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #f8f9fa;
            color: #333;
        }

        h1 {
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-weight: bold;
            color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-cart {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            font-size: 18px;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-cart:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="container pt-4">
        <h1 class="text-center mb-4">Welcome to Our Shop</h1>
        <div class="row">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="<?= !empty($product['thumbnail']) ? $product['thumbnail'] : 'default-image.jpg' ?>" 
                                 alt="<?= $product['title'] ?>" class="card-img-top" style="max-height: 300px; object-fit: contain;">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= $product['title'] ?></h5>
                                <p class="card-text">Price: <?= number_format($product['price'], 2) ?>€</p>
                                <form action="addToCart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No products available in the shop.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bouton flottant pour accéder au panier -->
    <a href="cart.php" class="btn btn-cart">
        <i class="bi bi-cart"></i>
    </a>
</body>

</html>
