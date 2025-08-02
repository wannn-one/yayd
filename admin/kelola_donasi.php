<?php
// admin/kelola_donasi.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) { 
    header("Location: ../login.php"); 
    exit(); 
}
require_once '../views/admin/kelola_donasi.php';