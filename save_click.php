<?php
declare(strict_types=1);

require_once 'db.php';

date_default_timezone_set('Europe/Istanbul');

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error']);
    exit;
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') === false) {
    http_response_code(415);
    echo json_encode(['status' => 'error']);
    exit;
}

$rawInput = file_get_contents('php://input');

if ($rawInput === false || strlen($rawInput) > 1000) {
    http_response_code(400);
    echo json_encode(['status' => 'error']);
    exit;
}

$data = json_decode($rawInput, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['status' => 'error']);
    exit;
}

$page = trim((string)($data['page'] ?? ''));
$x = filter_var($data['x'] ?? null, FILTER_VALIDATE_INT);
$y = filter_var($data['y'] ?? null, FILTER_VALIDATE_INT);

if ($page === '' || mb_strlen($page, 'UTF-8') > 150 || $x === false || $y === false) {
    http_response_code(400);
    echo json_encode(['status' => 'error']);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_\-\/\.]+$/', $page)) {
    http_response_code(400);
    echo json_encode(['status' => 'error']);
    exit;
}

if ($x < 0 || $x > 10000 || $y < 0 || $y > 10000) {
    http_response_code(400);
    echo json_encode(['status' => 'error']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO clicks (page_url, click_x, click_y, created_at, ip_address, user_agent)
        VALUES (:page, :x, :y, :created_at, :ip_address, :user_agent)
    ");

    $stmt->execute([
        ':page' => $page,
        ':x' => $x,
        ':y' => $y,
        ':created_at' => date('Y-m-d H:i:s'),
        ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ':user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255)
    ]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    error_log('Click kayıt hatası: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error']);
}