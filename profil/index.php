<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php?error=login_required");
    exit();
}

require_once '../views/profil/index.php';
?>