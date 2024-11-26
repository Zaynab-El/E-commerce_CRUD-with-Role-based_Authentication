<?php
session_start();

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost:3306;dbname=produits_db", 'root', '123456');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si le panier existe et contient des produits
$cartContent = "";
$total = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$product_id]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;
            $cartContent .= "
                <div class='cart-item'>
                    <span class='product-title'>{$product['title']}</span>
                    <span class='product-quantity'>x $quantity</span>
                    <span class='product-price'>" . number_format($subtotal, 2) . "€</span>
                </div>";
        } else {
            $cartContent .= "<p class='error'>Produit ID $product_id introuvable dans la base de données.</p>";
        }
    }
} else {
    $cartContent = "<p class='empty-cart'>Votre panier est vide.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Panier</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f8ff;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #2575fc;
            text-align: center;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .product-title {
            font-weight: bold;
            color: #333;
        }

        .product-quantity {
            font-size: 0.9rem;
            color: #555;
        }

        .product-price {
            color: #2575fc;
            font-weight: bold;
        }

        .empty-cart {
            text-align: center;
            color: #888;
            margin-top: 20px;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 1.2rem;
            color: #333;
        }

        .total span {
            font-weight: bold;
            color: #2575fc;
        }

        .checkout-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            text-align: center;
            color: #fff;
            background-color: #2575fc;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
        }

        .checkout-btn:hover {
            background-color: #6a11cb;
        }

        .error {
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Votre Panier</h2>
        <?php echo $cartContent; ?>
        <?php if ($total > 0): ?>
            <p class="total">Total : <span><?php echo number_format($total, 2); ?>€</span></p>
            <a href="checkout.php" class="checkout-btn">Passer à la caisse</a>
        <?php endif; ?>
    </div>
</body>
</html>
