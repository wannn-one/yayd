<?php
require_once 'config.php';
$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$koneksi) {
    die("KONEKSI GAGAL: " . mysqli_connect_error());
}
?>