<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

// Fetch counts
$clientsCount = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$accountsCount = $pdo->query("SELECT COUNT(*) FROM comptes")->fetchColumn();
$todayTransactions = $pdo->query("SELECT COUNT(*) FROM transactions WHERE DATE(transaction_date) = CURDATE()")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Bankly V2</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body >

<header>
    <strong>Bankly V2 - Dashboard</strong>
    <a href="../auth/logout.php">Déconnexion</a>
</header>

<div class="container">
    <h2>Résumé global</h2>
    
    <div class="stats">
        
        <div class="card">
            <h3>Clients</h3>
            <p><?= $clientsCount ?></p>
            <a href="../clients/list_clients.php">Show Clients</a>
        </div>
           
        <div class="card">
            <h3>Comptes</h3>
            <p><?= $accountsCount ?></p>
            <a href="../accounts/list_accounts.php">Show Accounts</a>
        </div>
        
        <div class="card">
            <h3>Transactions aujourd’hui</h3>
            <p><?= $todayTransactions ?></p>
            <a href="../transactions/list_transactions.php">Show Transactions</a>
        </div>
        
    </div>
</div>

</body>
</html>
