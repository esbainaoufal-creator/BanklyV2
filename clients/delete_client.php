<?php
require_once "../includes/auth_check.php";
require_once "../config/database.php";

// Get client ID from URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: list_clients.php");
    exit;
}

// Optional: Check if client has linked accounts
$stmt = $pdo->prepare("SELECT COUNT(*) FROM comptes WHERE client_id = ?");
$stmt->execute([$id]);
$accountsCount = $stmt->fetchColumn();

if ($accountsCount > 0) {
    // Cannot delete client with existing accounts
    header("Location: list_clients.php?error=Impossible+de+supprimer+un+client+avec+comptes");
    exit;
}

// Delete client
$stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
$stmt->execute([$id]);

header("Location: list_clients.php?success=Client+supprimé+avec+succès");
exit;
