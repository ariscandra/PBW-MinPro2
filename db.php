<?php
declare(strict_types=1);

$dbHost = "127.0.0.1";
$dbUser = "root";
$dbPass = "";
$dbName = "minpro_portfolio";
$dbPort = 3306;

$koneksi = @new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);

if ($koneksi->connect_errno) {
    http_response_code(500);
    echo "<h1>Koneksi database gagal.</h1>";
    echo "<p>Liat lagi <code>db.php</code></p>";
    exit;
}

$koneksi->set_charset("utf8mb4");
