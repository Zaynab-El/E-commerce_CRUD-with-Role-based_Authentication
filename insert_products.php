<?php 
// Path to JSON file
$jsonFile = "products.json";

// Load JSON file
if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    $products = json_decode($jsonData, true);

    if ($products === null) {
        die("Error: JSON file could not be decoded.");
    }

    echo "JSON file loaded successfully!<br>";
} else {
    die("Error: JSON file not found.");
}

// Database connection details
$servername = "localhost:3306"; 
$username = "root";
$password = "123456";
$dbname = "produits_db";
$port = 3307; 

// Connect to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Database connection successful!<br>";

// Préparer la requête UPDATE pour mettre à jour les produits existants
$stmt = $conn->prepare("UPDATE produits SET title = ?, description = ?, category = ?, price = ?, discountPercentage = ?, rating = ?, stock = ?, brand = ?, sku = ?, weight = ?, width = ?, height = ?, depth = ?, warrantyInformation = ?, shippingInformation = ?, availabilityStatus = ?, returnPolicy = ?, minimumOrderQuantity = ?, createdAt = ?, updatedAt = ?, barcode = ?, qrCode = ?, thumbnail = ?, tags = ?, dimensions = ? WHERE id = ?");
$stmt->bind_param("isssdddissddddssssisssssss", 
    $title, $description, $category, $price, $discountPercentage, $rating, $stock, $brand, $sku, $weight, $width, $height, $depth, $warrantyInformation, $shippingInformation, $availabilityStatus, $returnPolicy, $minimumOrderQuantity, $createdAt, $updatedAt, $barcode, $qrCode, $thumbnail, $tags, $dimensions, $id
);

// Affichage des produits
foreach ($products as $product) {
    // Récupérer les données produit
    $id = $product['id'];
    $title = $product['title'];
    $description = $product['description'];
    $category = $product['category'];
    $price = $product['price'];
    $discountPercentage = $product['discountPercentage'];
    $rating = $product['rating'];
    $stock = $product['stock'];
    $tags = json_encode($product['tags']);  // Convertir les tags en format JSON
    $brand = $product['brand'];
    $sku = $product['sku'];
    $weight = $product['weight'];
    $width = isset($product['dimensions']['width']) ? $product['dimensions']['width'] : null;
    $height = isset($product['dimensions']['height']) ? $product['dimensions']['height'] : null;
    $depth = isset($product['dimensions']['depth']) ? $product['dimensions']['depth'] : null;
    $dimensions = isset($product['dimensions']) ? json_encode($product['dimensions']) : null;
    $warrantyInformation = $product['warrantyInformation'];
    $shippingInformation = $product['shippingInformation'];
    $availabilityStatus = $product['availabilityStatus'];
    $returnPolicy = $product['returnPolicy'];
    $minimumOrderQuantity = $product['minimumOrderQuantity'];
    $createdAt = isset($product['createdAt']) ? $product['createdAt'] : null;
    $updatedAt = isset($product['updatedAt']) ? $product['updatedAt'] : null;

    $barcode = isset($product['barcode']) ? $product['barcode'] : null;
    $qrCode = isset($product['qrCode']) ? $product['qrCode'] : null;
    $thumbnail = $product['thumbnail'];

    // Affichage du produit sur la page
    echo "<h2>" . $title . "</h2>";
    echo "<p>" . $description . "</p>";
    echo "<p><strong>Category:</strong> " . $category . "</p>";
    echo "<p><strong>Price:</strong> $" . $price . "</p>";
    echo "<p><strong>Discount:</strong> " . $discountPercentage . "% off</p>";
    echo "<p><strong>Rating:</strong> " . $rating . " stars</p>";
    echo "<p><strong>Stock:</strong> " . $stock . " items</p>";
    echo "<p><strong>Brand:</strong> " . $brand . "</p>";
    echo "<p><strong>SKU:</strong> " . $sku . "</p>";
    echo "<p><strong>Dimensions:</strong> " . $width . " x " . $height . " x " . $depth . " cm</p>";
    echo "<img src='" . $product['images'][0] . "' alt='" . $title . "'>";
    echo "<p><strong>Thumbnail:</strong> <img src='" . $thumbnail . "' alt='Thumbnail'></p>";

    // Insertion ou mise à jour du produit dans la base de données
    // Vérifier si le produit existe déjà avec le même ID, si oui, mettre à jour
    $checkQuery = $conn->prepare("SELECT id FROM produits WHERE id = ?");
    $checkQuery->bind_param("i", $id);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        if ($stmt->execute()) {
            echo "Product updated: $title<br>";
        } else {
            echo "Error updating product: $title<br>";
        }
    } else {
        echo "Duplicate product skipped: ID $id<br>";
    }

    $checkQuery->close();
}

// Close connection
$stmt->close();
$conn->close();

echo "All products have been successfully added!";
?>
