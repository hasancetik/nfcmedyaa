<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_id'])) {
    http_response_code(404);
    header('Location: ../404');
    exit;
}

$adminId = filter_var($_SESSION['admin_id'], FILTER_VALIDATE_INT);

if (!$adminId) {
    session_destroy();
    http_response_code(404);
    header('Location: ../404');
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

try {
    $stmt = $pdo->prepare("
        SELECT id, name, email, positions 
        FROM admin 
        WHERE id = :admin_id 
        LIMIT 1
    ");

    $stmt->execute([
        ':admin_id' => $adminId
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        session_destroy();
        http_response_code(404);
        header('Location: ../404');
        exit;
    }

} catch (PDOException $e) {
    error_log('Admin header sorgu hatası: ' . $e->getMessage());
    http_response_code(500);
    exit('Sistemsel bir hata oluştu.');
}

$currentPageUrl = basename($_SERVER['PHP_SELF'] ?? '');

function isActive(array $pages, string $currentPage): string
{
    return in_array($currentPage, $pages, true) ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="tr" class="loaded">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NFC Medya - Admin Panel</title>

    <meta name="robots" content="noindex, nofollow">

    <link rel="shortcut icon" href="assets/images/logo.png">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static"
      data-open="click"
      data-menu="vertical-menu-modern"
      data-col="">

<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">

    <div class="navbar-container d-flex content">

        <div class="bookmark-wrapper d-flex align-items-center">

            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item">
                    <a class="nav-link menu-toggle" href="#" aria-label="Menüyü aç">
                        <i class="fi fi-rr-bars-staggered"></i>
                    </a>
                </li>
            </ul>

            <ul class="nav navbar-nav">
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link nav-link-style" href="#" aria-label="Tema değiştir">
                        <i class="ficon" data-feather="moon"></i>
                    </a>
                </li>
            </ul>

        </div>

        <ul class="nav navbar-nav align-items-center ms-auto">

            <li class="nav-item dropdown dropdown-user">

                <a class="nav-link dropdown-toggle dropdown-user-link"
                   id="dropdown-user"
                   href="#"
                   data-bs-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="false">

                    <div class="user-nav d-sm-flex d-none">

                        <span class="user-name fw-bolder">
                            <?= e($admin['name'] ?? 'Admin'); ?>
                        </span>

                        <span class="user-status">
                            <?= e($admin['positions'] ?? 'Yönetici'); ?>
                        </span>

                    </div>

                    <span class="avatar">
                        <img class="round"
                             src="assets/images/2.png"
                             alt="Admin avatar"
                             height="40"
                             width="40">

                        <span class="avatar-status-online"></span>
                    </span>

                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">

                    <a class="dropdown-item" href="profile">
                        <i class="me-50" data-feather="user"></i>
                        Profil
                    </a>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="logout">
                        <i class="me-50" data-feather="power"></i>
                        Çıkış Yap
                    </a>

                </div>

            </li>

        </ul>

    </div>

</nav>

<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">

    <div class="navbar-header">

        <ul class="nav navbar-nav flex-row">

            <li class="nav-item me-auto">

                <a class="navbar-brand" href="index" aria-label="NFC Medya Admin Ana Sayfa">

                    <span class="brand-logo">
                        <img src="assets/images/logo.png"
                             alt="NFC Medya Logo"
                             height="24"
                             width="32">
                    </span>

                </a>

            </li>

            <li class="nav-item nav-toggle">

                <a class="nav-link modern-nav-toggle pe-0"
                   data-bs-toggle="collapse"
                   href="#"
                   aria-label="Menüyü daralt">

                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>

                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary"
                       data-feather="disc"
                       data-ticon="disc"></i>

                </a>

            </li>

        </ul>

    </div>

    <div class="shadow-bottom"></div>

    <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="navigation-header">

                <span>Genel</span>

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="14"
                     height="14"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     class="feather feather-more-horizontal">

                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="19" cy="12" r="1"></circle>
                    <circle cx="5" cy="12" r="1"></circle>

                </svg>

            </li>

            <li class="<?= isActive(['index.php'], $currentPageUrl); ?> nav-item">

                <a class="d-flex align-items-center" href="index">

                    <i class="d-flex align-items-center fi fi-rr-home"></i>

                    <span class="menu-title text-truncate">
                        Anasayfa
                    </span>

                </a>

            </li>

            <li class="navigation-header">

                <span>Proje</span>

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="14"
                     height="14"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     class="feather feather-more-horizontal">

                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="19" cy="12" r="1"></circle>
                    <circle cx="5" cy="12" r="1"></circle>

                </svg>

            </li>

            <li class="<?= isActive(['proje.php', 'proje-duzenle.php'], $currentPageUrl); ?> nav-item">

                <a class="d-flex align-items-center" href="proje">

                    <i class="d-flex align-items-center fi fi-rr-edit"></i>

                    <span class="menu-title text-truncate">
                        Proje
                    </span>

                </a>

            </li>

            <li class="<?= isActive(['proje-paylas.php'], $currentPageUrl); ?> nav-item">

                <a class="d-flex align-items-center" href="proje-paylas">

                    <i class="d-flex align-items-center fi fi-rr-share"></i>

                    <span class="menu-title text-truncate">
                        Proje Paylaş
                    </span>

                </a>

            </li>

            <li class="<?= isActive(['heatmap_pages.php'], $currentPageUrl); ?> nav-item">

                <a class="d-flex align-items-center" href="../heatmap_pages">

                    <i class="d-flex align-items-center fi fi-rr-map"></i>

                    <span class="menu-title text-truncate">
                        Isı Haritası
                    </span>

                </a>

            </li>

        </ul>

    </div>

</div>