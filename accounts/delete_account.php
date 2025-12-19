<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

// Get account ID from URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list_accounts.php");
    exit;
}

// Optional: check if account has transactions
$stmt = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE account_id = ?");
$stmt->execute([$id]);
$transactionsCount = $stmt->fetchColumn();

if ($transactionsCount > 0) {
    // Prevent deletion if transactions exist
    header("Location: list_accounts.php?error=Impossible+de+supprimer+un+compte+avec+transactions");
    exit;
}

// Delete account
$stmt = $pdo->prepare("DELETE FROM comptes WHERE id = ?");
$stmt->execute([$id]);

header("Location: list_accounts.php?success=Compte+supprimé+avec+succès");
exit;
