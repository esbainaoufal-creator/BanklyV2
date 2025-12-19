<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

// Success/error messages from add/edit/delete
$success = $_GET['success'] ?? "";
$error = $_GET['error'] ?? "";

// Fetch all clients
$stmt = $pdo->query("SELECT * FROM clients ORDER BY id DESC");
$clients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des clients | Bankly V2</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <header>
        <strong>Bankly V2 - Clients</strong>
        <a href="../dashboard/dashboard.php">Dashboard</a> |
        <a href="../auth/logout.php">Déconnexion</a>
    </header>

    <div class="container">
        <h2>Liste des clients</h2>
        <button class="button2">
            <a href="add_client.php">ajouter un client</a>
        </button>

        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <table class="table" border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>CIN</th>
                    <th>Genre</th>
                    <th>Date de naissance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= $client['id'] ?></td>
                        <td><?= htmlspecialchars($client['full_name']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['phone_number']) ?></td>
                        <td><?= htmlspecialchars($client['adresse']) ?></td>
                        <td><?= htmlspecialchars($client['cin']) ?></td>
                        <td><?= $client['gendre'] ?></td>
                        <td><?= $client['birthday'] ?></td>
                        <td>
                            <a href="edit_client.php?id=<?= $client['id'] ?>">Modifier</a> |
                            <a href="delete_client.php?id=<?= $client['id'] ?>" onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>