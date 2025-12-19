<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$error = "";
$success = "";

// Get account ID from URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list_accounts.php");
    exit;
}

// Fetch account data
$stmt = $pdo->prepare("SELECT * FROM comptes WHERE id = ?");
$stmt->execute([$id]);
$account = $stmt->fetch();

if (!$account) {
    header("Location: list_accounts.php");
    exit;
}

// Fetch all clients for selection
$clientsStmt = $pdo->query("SELECT id, full_name FROM clients ORDER BY full_name ASC");
$clients = $clientsStmt->fetchAll();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $client_id = $_POST['client_id'] ?? null;
    $account_type = $_POST['account_type'] ?? "";
    $solde = $_POST['solde'] ?? 0;
    $account_statue = $_POST['account_statue'] ?? "Actif";

    if (!$client_id || !$account_type || !is_numeric($solde)) {
        $error = "Tous les champs sont obligatoires et le solde doit être un nombre.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE comptes SET client_id=?, account_type=?, solde=?, account_statue=? WHERE id=?");
            $stmt->execute([$client_id, $account_type, $solde, $account_statue, $id]);
            $success = "Compte mis à jour avec succès !";

            // Refresh account data
            $stmt = $pdo->prepare("SELECT * FROM comptes WHERE id = ?");
            $stmt->execute([$id]);
            $account = $stmt->fetch();

        } catch (PDOException $e) {
            $error = "Erreur: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier compte | Bankly V2</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
    <strong>Bankly V2 - Modifier Compte</strong>
    <a href="list_accounts.php">Retour à la liste</a> |
    <a href="../auth/logout.php">Déconnexion</a>
</header>

<div class="container">
    <h2>Modifier compte</h2>

    <?php if ($error) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success) : ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Client</label><br>
        <select name="client_id" required>
            <?php foreach($clients as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id'] == $account['client_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['full_name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Type de compte</label><br>
        <select name="account_type" required>
            <option value="Courant" <?= $account['account_type'] === 'Courant' ? 'selected' : '' ?>>Courant</option>
            <option value="Epargne" <?= $account['account_type'] === 'Epargne' ? 'selected' : '' ?>>Épargne</option>
            <option value="Professionnel" <?= $account['account_type'] === 'Professionnel' ? 'selected' : '' ?>>Professionnel</option>
            <option value="Jeune" <?= $account['account_type'] === 'Jeune' ? 'selected' : '' ?>>Jeune</option>
        </select><br>

        <label>Solde (MAD)</label><br>
        <input type="number" step="0.01" name="solde" value="<?= $account['solde'] ?>" required><br>

        <label>Statut</label><br>
        <select name="account_statue" required>
            <option value="Actif" <?= $account['account_statue'] === 'Actif' ? 'selected' : '' ?>>Actif</option>
            <option value="Inactif" <?= $account['account_statue'] === 'Inactif' ? 'selected' : '' ?>>Inactif</option>
            <option value="Blocked" <?= $account['account_statue'] === 'Blocked' ? 'selected' : '' ?>>Blocked</option>
        </select><br><br>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
</body>
</html>
