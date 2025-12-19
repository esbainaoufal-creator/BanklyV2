<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$error = "";
$success = "";

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
            $stmt = $pdo->prepare("INSERT INTO clients (full_name, email, phone_number, adresse, cin, gendre, birthday, utilisateur_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$full_name, $email, $phone_number, $adresse, $cin, $gendre, $birthday, $_SESSION['user_id']]);
            $success = "Client ajouté avec succès !";
        } catch (PDOException $e) {
            // Duplicate entry or other DB error
            $error = "Erreur: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter client | Bankly V2</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
    <strong>Bankly V2 - Ajouter Client</strong>
    <a href="list_clients.php">Retour à la liste</a> |
    <a href="../auth/logout.php">Déconnexion</a>
</header>

<div class="container">
    <h2>Ajouter un client</h2>

    <?php if ($error) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success) : ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
        <p><a href="list_clients.php">Voir la liste des clients</a></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="full_name" placeholder="Nom complet" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="phone_number" placeholder="Téléphone" required><br>
        <input type="text" name="adresse" placeholder="Adresse" required><br>
        <input type="text" name="cin" placeholder="CIN" required><br>
        <select name="gendre" required>
            <option value="">Genre</option>
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
        </select><br>
        <input type="date" name="birthday" required><br>
        <button type="submit">Ajouter</button>
    </form>
</div>
</body>
</html>
