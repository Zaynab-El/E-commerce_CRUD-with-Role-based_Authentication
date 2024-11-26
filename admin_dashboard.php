
<?php
require 'session.php';
if (!isAdmin()) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: linear-gradient(to bottom, #2575fc, #6a11cb);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2.5rem;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .actions a {
            display: inline-block;
            padding: 15px 30px;
            text-decoration: none;
            font-size: 1.2rem;
            color: #fff;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            text-align: center;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease-in-out;
            backdrop-filter: blur(10px);
        }

        .actions a:hover {
            background: rgba(255, 255, 255, 0.5);
            border-color: rgba(255, 255, 255, 0.7);
            transform: scale(1.1);
            color: #333;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer a {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Bienvenue, <?php echo getUsername(); ?> <br>(Administrateur)</h1>
    <div class="actions">
        <a href="add.php">Ajouter un utilisateur</a>
        <a href="delete.php">Supprimer un utilisateur</a>
        <a href="editer.php">Modifier un utilisateur</a>
        <a href="logout.php">Déconnexion</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 Tableau de bord Admin - <a href="#">Voir les détails</a></p>
    </div>
</body>
</html>
