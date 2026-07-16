<?php
declare(strict_types=1);

require_once 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Yetkisiz erişim.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Geçersiz istek metodu.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Geçersiz güvenlik isteği.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$vitrin = filter_input(INPUT_POST, 'vitrin', FILTER_VALIDATE_INT);

if (!$id || !in_array($vitrin, [0, 1], true)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Geçersiz parametreler.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE proje 
        SET vitrin = :vitrin 
        WHERE id = :id 
        LIMIT 1
    ");

    $stmt->execute([
        ':vitrin' => $vitrin,
        ':id' => $id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Vitrin durumu güncellendi.'
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (PDOException $e) {
    error_log('Vitrin güncelleme hatası: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Sistemsel bir hata oluştu.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}