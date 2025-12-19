<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$error = "";
$success = "";

// Get client ID from URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: list_clients.php");
    exit;
}

// Fetch client data
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();

if (!$client) {
    header("Location: list_clients.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone_number = trim($_POST["phone_number"] ?? "");
    $adresse = trim($_POST["adresse"] ?? "");
    $cin = trim($_POST["cin"] ?? "");
    $gendre = $_POST["gendre"] ?? "";
    $birthday = $_POST["birthday"] ?? "";

    if (!$full_name || !$email || !$phone_number || !$adresse || !$cin || !$gendre || !$birthday) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE clients SET full_name=?, email=?, phone_number=?, adresse=?, cin=?, gendre=?, birthday=? WHERE id=?");
            $stmt->execute([$full_name, $email, $phone_number, $adresse, $cin, $gendre, $birthday, $id]);
            $success = "Client mis à jour avec succès !";

            // Refresh client data
            $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
            $stmt->execute([$id]);
            $client = $stmt->fetch();

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
    <title>Modifier client | Bankly V2</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
    <strong>Bankly V2 - Modifier Client</strong>
    <a href="list_clients.php">Retour à la liste</a> |
    <a href="../auth/logout.php">Déconnexion</a>
</header>

<div class="container">
    <h2>Modifier client</h2>

    <?php if ($error) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success) : ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="full_name" value="<?= htmlspecialchars($client['full_name']) ?>" placeholder="Nom complet" required><br>
        <input type="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" placeholder="Email" required><br>
        <input type="text" name="phone_number" value="<?= htmlspecialchars($client['phone_number']) ?>" placeholder="Téléphone" required><br>
        <input type="text" name="adresse" value="<?= htmlspecialchars($client['adresse']) ?>" placeholder="Adresse" required><br>
        <input type="text" name="cin" value="<?= htmlspecialchars($client['cin']) ?>" placeholder="CIN" required><br>
        <select name="gendre" required>
            <option value="">Genre</option>
            <option value="Homme" <?= $client['gendre'] === 'Homme' ? 'selected' : '' ?>>Homme</option>
            <option value="Femme" <?= $client['gendre'] === 'Femme' ? 'selected' : '' ?>>Femme</option>
        </select><br>
        <input type="date" name="birthday" value="<?= $client['birthday'] ?>" required><br>
        <button type="submit">Mettre à jour</button>
    </form>
</div>
</body>
</html>
