<?php
session_start();

// Vérifier que les données nécessaires sont présentes
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Initialiser le panier si nécessaire
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Ajouter ou mettre à jour le produit dans le panier
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity; // Ajouter la quantité
    } else {
        $_SESSION['cart'][$product_id] = $quantity; // Ajouter le produit
    }

    // Redirection vers la page d'accueil après ajout
    header("Location: index.php");
    exit;
} else {
    echo "Invalid request!";
}
?>
