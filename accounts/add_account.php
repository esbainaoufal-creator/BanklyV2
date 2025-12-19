<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$error = "";
$success = "";

// Fetch all clients for selection
$clientsStmt = $pdo->query("SELECT id, full_name FROM clients ORDER BY full_name ASC");
$clients = $clientsStmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $client_id = $_POST['client_id'] ?? null;
    $account_type = $_POST['account_type'] ?? "";
    $solde = $_POST['solde'] ?? 0;
    $account_statue = $_POST['account_statue'] ?? "Actif";

    if (!$client_id || !$account_type || !is_numeric($solde)) {
        $error = "Tous les champs sont obligatoires et le solde doit être un nombre.";
    } else {
        // Generate a unique 14-digit account number
        $account_number = str_pad(rand(0, 99999999999999), 14, "0", STR_PAD_LEFT);

        try {
            $stmt = $pdo->prepare("INSERT INTO comptes (account_number, account_type, solde, account_statue, utilisateur_id, client_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$account_number, $account_type, $solde, $account_statue, $_SESSION['user_id'], $client_id]);
            $success = "Compte ajouté avec succès !";
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
    <title>Ajouter compte | Bankly V2</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
    <strong>Bankly V2 - Ajouter Compte</strong>
    <a href="list_accounts.php">Retour à la liste</a> |
    <a href="../auth/logout.php">Déconnexion</a>
</header>

<div class="container">
    <h2>Ajouter un compte</h2>

    <?php if ($error) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success) : ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
        <p><a href="list_accounts.php">Voir la liste des comptes</a></p>
    <?php endif; ?>

    <form method="POST">
        <label>Client</label><br>
        <select name="client_id" required>
            <option value="">-- Sélectionner un client --</option>
            <?php foreach($clients as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['full_name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Type de compte</label><br>
        <select name="account_type" required>
            <option value="">-- Sélectionner le type --</option>
            <option value="Courant">Courant</option>
            <option value="Epargne">Épargne</option>
            <option value="Professionnel">Professionnel</option>
            <option value="Jeune">Jeune</option>
        </select><br>

        <label>Solde initial (MAD)</label><br>
        <input type="number" step="0.01" name="solde" required><br>

        <label>Statut</label><br>
        <select name="account_statue" required>
            <option value="Actif">Actif</option>
            <option value="Inactif">Inactif</option>
            <option value="Blocked">Blocked</option>
        </select><br><br>

        <button type="submit">Ajouter</button>
    </form>
</div>
</body>
</html>
