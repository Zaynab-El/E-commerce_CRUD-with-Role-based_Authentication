<?php
session_start();

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit("Accès refusé.");
}

// Charger les produits depuis le fichier JSON
$products = json_decode(file_get_contents("./products.json"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container pt-4">
        <h1>Products</h1>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) : ?>
                    <tr id="<?= 'product_' . $product->id ?>">
                        <td><?= $product->id ?></td>
                        <td><?= htmlspecialchars($product->title) ?></td>
                        <td><input type="number" class="form-control text-end" value="1" min="1" id="qty_<?= $product->id ?>"></td>
                        <td><?= $product->price ?>€</td>
                        <td><button class="btn btn-primary" onclick="addToCart(<?= $product->id ?>)">Add to Cart <i class="bi bi-basket"></i></button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function addToCart(productId) {
            const qty = document.querySelector('#qty_' + productId).value; 
            fetch('/addToCart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${productId}&quantity=${qty}`
            })
            .then(response => response.text())
            .then(data => {
                alert('Product added to cart');
            })
            .catch(error => console.error('Error adding product to cart:', error));
        }
    </script>
</body>
</html>
