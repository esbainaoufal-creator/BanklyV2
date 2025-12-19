<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$error = "";
$success = "";

// Fetch all accounts for selection
$accountsStmt = $pdo->query("
    SELECT comptes.id, comptes.account_number, clients.full_name AS client_name
    FROM comptes
    JOIN clients ON comptes.client_id = clients.id
    ORDER BY comptes.id ASC
");
$accounts = $accountsStmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $account_id = $_POST['account_id'] ?? null;
    $transaction_type = $_POST['transaction_type'] ?? "";
    $amount = $_POST['amount'] ?? 0;
    $description = trim($_POST['description'] ?? "");

    if (!$account_id || !$transaction_type || !is_numeric($amount) || $amount <= 0) {
        $error = "Tous les champs sont obligatoires et le montant doit être positif.";
    } else {
        // Fetch current balance
        $stmt = $pdo->prepare("SELECT solde FROM comptes WHERE id = ?");
        $stmt->execute([$account_id]);
        $account = $stmt->fetch();

        if (!$account) {
            $error = "Compte introuvable.";
        } else {
            $newBalance = $account['solde'];
            if ($transaction_type === "Depot") {
                $newBalance += $amount;
            } elseif ($transaction_type === "Retrait") {
                if ($amount > $account['solde']) {
                    $error = "Solde insuffisant pour le retrait.";
                } else {
                    $newBalance -= $amount;
                }
            }

            if (!$error) {
                try {
                    // Insert transaction
                    $stmt = $pdo->prepare("INSERT INTO transactions (transaction_type, amout, description, account_id) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$transaction_type, $amount, $description, $account_id]);

                    // Update account balance
                    $stmt = $pdo->prepare("UPDATE comptes SET solde=? WHERE id=?");
                    $stmt->execute([$newBalance, $account_id]);

                    $success = "Transaction effectuée avec succès !";

                } catch (PDOException $e) {
                    $error = "Erreur: " . $e->getMessage();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Effectuer transaction | Bankly V2</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
    <strong>Bankly V2 - Transactions</strong>
    <a href="list_transactions.php">Historique des transactions</a> |
    <a href="../dashboard/dashboard.php">Dashboard</a> |
    <a href="../auth/logout.php">Déconnexion</a>
</header>

<div class="container">
    <h2>Effectuer une transaction</h2>

    <?php if ($error) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success) : ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Compte</label><br>
        <select name="account_id" required>
            <option value="">-- Sélectionner un compte --</option>
            <?php foreach($accounts as $acc): ?>
                <option value="<?= $acc['id'] ?>"><?= $acc['account_number'] ?> - <?= htmlspecialchars($acc['client_name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Type de transaction</label><br>
        <select name="transaction_type" required>
            <option value="">-- Sélectionner --</option>
            <option value="Depot">Dépôt</option>
            <option value="Retrait">Retrait</option>
        </select><br>

        <label>Montant (MAD)</label><br>
        <input type="number" step="0.01" name="amount" required><br>

        <label>Description</label><br>
        <input type="text" name="description"><br><br>

        <button type="submit">Effectuer</button>
    </form>
</div>
</body>
</html>
