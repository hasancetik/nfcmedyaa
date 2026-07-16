<?php
declare(strict_types=1);

require_once 'db.php';

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function decodeCleanText(?string $value, int $limit = 160): string
{
    $text = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = trim(strip_tags($text));
    $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        return mb_strlen($text, 'UTF-8') > $limit ? mb_substr($text, 0, $limit, 'UTF-8') . '...' : $text;
    }

    return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
}

function decodeFullText(?string $value): string
{
    $text = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = strip_tags($text);
    return trim($text);
}

function cleanImageFile(?string $file): string
{
    $file = basename((string) $file);
    return preg_match('/^[a-zA-Z0-9_\-.çÇğĞıİöÖşŞüÜ]+$/u', $file) ? $file : 'default.jpg';
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => [
        'min_range' => 1,
    ],
]);

if (!$id) {
    http_response_code(404);
    exit('Geçersiz proje.');
}

try {
    $stmt = $pdo->prepare('SELECT id, baslik, aciklama, resim FROM proje WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $proje = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Proje detay sorgu hatası: ' . $e->getMessage());
    http_response_code(500);
    exit('Sistemsel bir hata oluştu. Lütfen daha sonra tekrar deneyin.');
}

if (!$proje) {
    http_response_code(404);
    exit('Proje bulunamadı.');
}

$projeId = (int) ($proje['id'] ?? 0);
$projeBaslik = decodeCleanText($proje['baslik'] ?? 'NFC Medya Projesi', 80);
$projeAciklamaMeta = decodeCleanText($proje['aciklama'] ?? '', 155);
$projeAciklamaTam = decodeFullText($proje['aciklama'] ?? '');
$projeResim = cleanImageFile($proje['resim'] ?? 'default.jpg');
$canonicalUrl = 'https://www.nfcmedya.com.tr/proje-detay.php?id=' . $projeId;
$imageUrl = 'https://www.nfcmedya.com.tr/uploads/proje/' . rawurlencode($projeResim);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($projeBaslik) ?> | NFC Medya Çerkezköy Reklam Ajansı</title>
    <meta name="description" content="<?= e($projeAciklamaMeta) ?>">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <link rel="icon" href="https://www.nfcmedya.com.tr/assets/images/logo.png">

    <meta property="og:site_name" content="NFC Medya">
    <meta property="og:title" content="<?= e($projeBaslik) ?> | NFC Medya">
    <meta property="og:description" content="<?= e($projeAciklamaMeta) ?>">
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:image" content="<?= e($imageUrl) ?>">
    <meta property="og:image:alt" content="<?= e($projeBaslik) ?> - NFC Medya Projesi">
    <meta property="og:locale" content="tr_TR">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($projeBaslik) ?> | NFC Medya">
    <meta name="twitter:description" content="<?= e($projeAciklamaMeta) ?>">
    <meta name="twitter:image" content="<?= e($imageUrl) ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.nfcmedya.com.tr/assets/css/style.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1f2d55',
                        secondary: '#c5c5c5'
                    },
                    borderRadius: {
                        none: '0px', sm: '4px', DEFAULT: '8px', md: '12px',
                        lg: '16px', xl: '20px', '2xl': '24px', '3xl': '32px',
                        full: '9999px', button: '8px'
                    }
                }
            }
        };
    </script>

    <script type="application/ld+json">
    <?= json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'name' => $projeBaslik,
        'description' => $projeAciklamaMeta,
        'image' => $imageUrl,
        'url' => $canonicalUrl,
        'author' => [
            '@type' => 'Organization',
            'name' => 'NFC Medya',
            'url' => 'https://www.nfcmedya.com.tr/'
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'NFC Medya',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => 'https://www.nfcmedya.com.tr/assets/images/logo.png'
            ]
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $canonicalUrl
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
    </script>
</head>

<body class="min-h-screen flex flex-col bg-gray-50">

<?php include 'header.php'; ?>

<main class="w-full flex-1">
    <section class="pt-32 pb-16 bg-gradient-to-br from-[#1f2d55] to-[#2a3d73]">
        <div class="max-w-7xl mx-auto px-4 text-center text-white">
            <p class="text-sm uppercase tracking-[0.25em] text-white/70 mb-4">NFC Medya Proje Detayı</p>
            <h1 class="text-3xl md:text-5xl font-bold leading-tight"><?= e($projeBaslik) ?></h1>
            <p class="max-w-3xl mx-auto mt-6 text-white/85 text-lg leading-relaxed"><?= e($projeAciklamaMeta) ?></p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="flex flex-col lg:flex-row items-start gap-12 bg-white p-6 md:p-8 rounded-2xl shadow-md" data-aos="fade-up">
            <button type="button" class="w-full max-w-sm flex-shrink-0 text-left" onclick="openImageModal()" aria-label="Proje görselini büyüt">
                <img src="<?= e($imageUrl) ?>" alt="<?= e($projeBaslik) ?> - NFC Medya projesi" class="w-full h-auto object-contain rounded-xl shadow-lg transition-transform duration-500 hover:scale-105">
            </button>

            <article class="w-full self-start">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-6"><?= e($projeBaslik) ?></h2>
                <div class="text-lg leading-relaxed text-gray-700 whitespace-pre-line">
                    <?= nl2br(e($projeAciklamaTam)) ?>
                </div>
            </article>
        </div>

        <div class="mt-10 flex flex-wrap gap-4 justify-center">
            <a href="https://www.nfcmedya.com.tr/proje" class="inline-flex items-center px-6 py-3 bg-white text-[#1f2d55] border border-gray-200 rounded-button font-semibold hover:bg-gray-100 transition">
                <i class="ri-arrow-left-line mr-2"></i> Tüm Projelere Dön
            </a>
            <a href="https://www.nfcmedya.com.tr/iletisim" class="inline-flex items-center px-6 py-3 bg-[#1f2d55] text-white rounded-button font-semibold hover:bg-[#9d181a] transition">
                Benzer Bir Proje İçin Teklif Al
                <i class="ri-arrow-right-line ml-2"></i>
            </a>
        </div>
    </section>

    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden" onclick="closeImageModal()">
        <button type="button" onclick="closeImageModal()" aria-label="Görseli kapat" class="absolute top-6 right-6 text-white text-3xl hover:text-red-400 transition z-50">
            <i class="ri-close-line"></i>
        </button>

        <div class="relative p-4 sm:p-8 w-full max-w-4xl" onclick="event.stopPropagation()">
            <img src="<?= e($imageUrl) ?>" alt="<?= e($projeBaslik) ?> tam ekran görsel" class="w-full h-auto max-h-[90vh] object-contain rounded-xl shadow-2xl">
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://www.nfcmedya.com.tr/assets/js/script.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function setupDropdown(toggleId, dropdownId, arrowId) {
            const toggleBtn = document.getElementById(toggleId);
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById(arrowId);

            if (toggleBtn && dropdown && arrow) {
                toggleBtn.addEventListener('click', () => {
                    dropdown.classList.toggle('max-h-0');
                    dropdown.classList.toggle('max-h-[1000px]');
                    arrow.classList.toggle('rotate-180');
                });
            }
        }

        setupDropdown('toggleKurumsal', 'kurumsalDropdown', 'kurumsalArrow');
        setupDropdown('toggleHizmetler', 'hizmetlerDropdown', 'hizmetlerArrow');
    });

    function openImageModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
</body>
</html>
