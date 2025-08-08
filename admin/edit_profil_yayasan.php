<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) { 
    header("Location: ../login.php"); 
    exit(); 
}
require_once '../views/admin/edit_profil_yayasan.php';