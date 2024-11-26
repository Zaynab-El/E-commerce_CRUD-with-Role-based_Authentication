<?php
session_start();
session_unset(); // Détruit toutes les variables de session
session_destroy(); // Détruit la session
header("Location: login.php"); // Redirige l'utilisateur vers la page de connexion
exit();
?>
