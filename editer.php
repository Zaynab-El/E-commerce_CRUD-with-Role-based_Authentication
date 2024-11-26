<?php
require 'session.php';
if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=produits_db", 'root', '123456');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des utilisateurs
$stmt = $pdo->query("SELECT id, name, email, role FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Modifier un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Validation des données
    if (!empty($name) && !empty($email) && !empty($role)) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $userId]);
        $message = "Utilisateur mis à jour avec succès.";
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/lux/bootstrap.min.css">
    <style>
        body {
            background: #fff;
            color: #000;
        }

        h1 {
            color: #007bff; /* Bleu */
            font-weight: bold; /* Gras */
            text-align: center;
            margin-top: 20px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #007bff !important; /* Bleu */
            border-color: #0056b3 !important;
        }

        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #004085 !important;
        }

        .text-success {
            color: #28a745 !important;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Modifier un utilisateur</h1>
        <p class="text-center text-success"><?= $message ?? '' ?></p>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="user_id" class="form-label">Sélectionner un utilisateur</label>
                <select name="user_id" id="user_id" class="form-select">
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>">
                            <?= $user['name'] ?> (<?= $user['email'] ?> - <?= $user['role'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="user">Utilisateur</option>
                    <option value="admin">Administrateur</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Modifier</button>
        </form>
        <a href="admin_dashboard.php" class="btn btn-secondary mt-3 w-100">Retour</a>
    </div>
</body>
</html>
