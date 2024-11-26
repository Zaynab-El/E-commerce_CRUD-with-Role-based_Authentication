<?php
session_start();
require 'session.php';
require 'db.php';

if (!isAdmin()) {
    header("Location: login.php");
    exit("Accès interdit.");
}

// Ajout d'un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // 'admin' ou 'user'

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $role]);

        header("Location: admin_dashboard.php");
        exit("Utilisateur ajouté avec succès.");
    } catch (PDOException $e) {
        die("Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #2575fc;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #6a11cb;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Ajouter un utilisateur</h1>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <select name="role" required>
            <option value="user">Utilisateur</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
