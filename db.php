<?php
declare(strict_types=1);

$host = 'localhost';
$dbname = 'u738369617_nfc';
$username = 'u738369617_nfc';
$password = 'pqS+a:Dh0';

$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    error_log('Veritabanı bağlantı hatası: ' . $e->getMessage());
    http_response_code(500);
    exit('Sistemsel bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
}