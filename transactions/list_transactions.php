<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

// Fetch all transactions with account & client info
$stmt = $pdo->query("
    SELECT t.*, c.account_number, cl.full_name AS client_name
    FROM transactions t
    JOIN comptes c ON t.account_id = c.id
    JOIN clients cl ON c.client_id = cl.id
    ORDER BY t.transaction_date DESC
");
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des transactions | Bankly V2</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
    <strong>Bankly V2 - Historique des transactions</strong>
    <a href="make_transaction.php">Effectuer une transaction</a> |
    <a href="../dashboard/dashboard.php">Dashboard</a> |
    <a href="../auth/logout.php">Déconnexion</a>
</header>

<div class="container">
    <h2>Historique des transactions</h2>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Compte</th>
                <th>Client</th>
                <th>Type</th>
                <th>Montant</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($transactions as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><?= $t['account_number'] ?></td>
                <td><?= htmlspecialchars($t['client_name']) ?></td>
                <td><?= $t['transaction_type'] ?></td>
                <td><?= number_format($t['amout'],2) ?> MAD</td>
                <td><?= htmlspecialchars($t['description']) ?></td>
                <td><?= $t['transaction_date'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
