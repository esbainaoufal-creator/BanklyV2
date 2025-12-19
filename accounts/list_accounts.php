<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

// Success/error messages
$success = $_GET['success'] ?? "";
$error = $_GET['error'] ?? "";

// Fetch all accounts with client info
$stmt = $pdo->query("
    SELECT comptes.*, clients.full_name AS client_name
    FROM comptes
    JOIN clients ON comptes.client_id = clients.id
    ORDER BY comptes.id DESC
");
$accounts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des comptes | Bankly V2</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
    <strong>Bankly V2 - Comptes</strong>
    <a href="../dashboard/dashboard.php">Dashboard</a> |
    <a href="../auth/logout.php">Déconnexion</a>
</header>

<div class="container">
    <h2>Liste des comptes</h2>
    <a href="add_account.php">Ajouter un compte</a>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Numéro de compte</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Solde</th>
                <th>Client</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($accounts as $acc): ?>
            <tr>
                <td><?= $acc['id'] ?></td>
                <td><?= $acc['account_number'] ?></td>
                <td><?= $acc['account_type'] ?></td>
                <td><?= $acc['account_statue'] ?></td>
                <td><?= number_format($acc['solde'],2) ?> MAD</td>
                <td><?= htmlspecialchars($acc['client_name']) ?></td>
                <td><?= $acc['creation_date'] ?></td>
                <td>
                    <a href="edit_account.php?id=<?= $acc['id'] ?>">Modifier</a> |
                    <a href="delete_account.php?id=<?= $acc['id'] ?>" onclick="return confirm('Supprimer ce compte ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
