<?php
// yayd/logout.php
session_start();

// Hapus semua variabel session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Arahkan kembali ke halaman utama
header("Location: index.php");
exit();
?>