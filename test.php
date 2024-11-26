<?php
// Paramètres de connexion à la base de données
$servername = "localhost:3306"; // ou localhost:3307 si c'est le port séparé
$username = "root";
$password = "123456"; // Assurez-vous que le mot de passe est correct
$dbname = "produits_db";
$port = 3307; // Le port que vous avez configuré

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
echo "Connexion à la base de données réussie !<br>";

?>
