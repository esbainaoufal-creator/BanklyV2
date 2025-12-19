<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /RBANKO/auth/login.php");
    exit;
}
