<?php
session_start();
require 'session.php';
require 'db.php';

// Vérifier si l'utilisateur est administrateur
if (!isAdmin()) {
    header("Location: login.php");
    exit("Accès interdit.");
}

// Supprimer l'utilisateur si un ID est fourni via un formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = (int) $_POST['user_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $message = "Utilisateur supprimé avec succès.";
    } catch (PDOException $e) {
        $message = "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            padding: 20px;
        }
        h1 {
            color: #2575fc;
        }
        .message {
            color: green;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #2575fc;
            color: white;
        }
        form {
            display: inline;
        }
        button {
            padding: 5px 10px;
            color: white;
            background: red;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: darkred;
        }
        .back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            background-color: #2575fc;
            border-radius: 5px;
        }
        .back:hover {
            background-color: #6a11cb;
        }
    </style>
</head>
<body>
    <h1>Supprimer un utilisateur</h1>

    <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Récupérer tous les utilisateurs
            $stmt = $pdo->query("SELECT id, name, email, role FROM users");
            while ($user = $stmt->fetch()) {
                echo "<tr>";
                echo "<td>{$user['id']}</td>";
                echo "<td>{$user['name']}</td>";
                echo "<td>{$user['email']}</td>";
                echo "<td>{$user['role']}</td>";
                echo "<td>
                        <form method='POST' action='delete.php'>
                            <input type='hidden' name='user_id' value='{$user['id']}'>
                            <button type='submit'>Supprimer</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <a class="back" href="admin_dashboard.php">Retour au tableau de bord</a>
</body>
</html>
