<?php
require 'session.php';
require 'db.php';

// Vérifier que l'utilisateur est connecté
redirectIfNotLoggedIn();

// Vérifier si le panier est vide
if (empty($_SESSION['cart'])) {
    header("Location: cart.php"); // Rediriger vers la page du panier
    exit;
}

$cart = $_SESSION['cart'];
$cartDetails = [];
$details = "";

// Récupérer les informations sur les produits dans le panier
foreach ($cart as $product_id) {
    // Préparer et exécuter la requête pour obtenir le produit
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Ajouter les informations du produit dans les détails de la commande
        $details .= $product['title'] . " - " . $product['price'] . "€\n";
    } else {
        // Si un produit n'existe pas dans la base de données, rediriger l'utilisateur
        header("Location: cart.php?msg=Un+produit+n'existe+plus.");
        exit;
    }
}

// Insérer la commande dans la base de données
$stmt = $pdo->prepare("INSERT INTO commandes (user_id, details) VALUES (?, ?)");
$stmt->execute([$_SESSION['user']['id'], $details]);

// Vider le panier après la commande
unset($_SESSION['cart']);

// Rediriger vers la page d'accueil après la commande
header("Location: index.php?msg=Commande+passée+avec+succès.");
exit;
?>
