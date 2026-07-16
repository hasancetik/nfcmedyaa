<?php
declare(strict_types=1);

require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$page = trim($_GET['page'] ?? 'index.php');

if (
    $page === '' ||
    mb_strlen($page, 'UTF-8') > 150 ||
    !preg_match('/^[a-zA-Z0-9_\-\/\.]+$/', $page)
) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Geçersiz sayfa'
    ]);
    exit;
}

try {

    $stmt = $pdo->prepare("
        SELECT 
            click_x AS x,
            click_y AS y,
            1 AS value
        FROM clicks
        WHERE page_url = :page
        ORDER BY id DESC
        LIMIT 5000
    ");

    $stmt->execute([
        ':page' => $page
    ]);

    $clicks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(
        $clicks,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

} catch (PDOException $e) {

    error_log('get_clicks.php hatası: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Sistemsel hata'
    ]);
}