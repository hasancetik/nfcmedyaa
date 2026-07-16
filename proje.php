<?php
declare(strict_types=1);

require_once 'db.php';

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function cleanText(?string $value, int $limit = 170): string
{
    $text = html_entity_decode((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = trim(strip_tags($text));
    $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        return mb_strlen($text, 'UTF-8') > $limit
            ? mb_substr($text, 0, $limit, 'UTF-8') . '...'
            : $text;
    }

    return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
}

function cleanImageFile(?string $file): string
{
    $file = basename((string)$file);
    return preg_match('/^[a-zA-Z0-9_\-.çÇğĞıİöÖşŞüÜ]+$/u', $file) ? $file : 'default.jpg';
}

$limit = 15;

$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, [
    'options' => [
        'default' => 1,
        'min_range' => 1
    ]
]);

try {
    $totalStmt = $pdo->query("SELECT COUNT(*) FROM proje");
    $totalProjects = (int)$totalStmt->fetchColumn();
    $totalPages = max(1, (int)ceil($totalProjects / $limit));

    if ($page > $totalPages) {
        $page = $totalPages;
    }

    $offset = ($page - 1) * $limit;

    $stmt = $pdo->prepare("
        SELECT id, baslik, aciklama, resim 
        FROM proje 
        ORDER BY id DESC 
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $projeler = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log('proje.php sorgu hatası: ' . $e->getMessage());
    $totalProjects = 0;
    $totalPages = 1;
    $projeler = [];
}

$itemListElements = [];
$position = 1 + (($page - 1) * $limit);

foreach ($projeler as $proje) {
    $projectId = (int)($proje['id'] ?? 0);

    $itemListElements[] = [
        '@type' => 'ListItem',
        'position' => $position++,
        'url' => 'https://www.nfcmedya.com.tr/proje-detay.php?id=' . $projectId,
        'name' => cleanText($proje['baslik'] ?? 'NFC Medya Projesi', 80)
    ];
}

$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'NFC Medya Projeler',
    'description' => 'NFC Medya tarafından Çerkezköy ve Tekirdağ bölgesinde hayata geçirilen reklam, web tasarım, sosyal medya ve dijital pazarlama projeleri.',
    'url' => 'https://www.nfcmedya.com.tr/proje',
    'itemListElement' => $itemListElements
];

$canonical = $page > 1
    ? 'https://www.nfcmedya.com.tr/proje?page=' . $page
    : 'https://www.nfcmedya.com.tr/proje';
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projelerimiz | Çerkezköy Reklam Ajansı NFC Medya</title>
    <meta name="description" content="NFC Medya projelerini inceleyin. Çerkezköy reklam ajansı olarak web tasarım, sosyal medya yönetimi, SEO, grafik tasarım ve video projeleri üretiyoruz.">
    <meta name="robots" content="index, follow, max-image-preview:large">

    <link rel="canonical" href="<?= e($canonical) ?>">
    <link rel="icon" href="https://www.nfcmedya.com.tr/assets/images/logo.png">

    <meta property="og:site_name" content="NFC Medya">
    <meta property="og:title" content="Projelerimiz | Çerkezköy Reklam Ajansı NFC Medya">
    <meta property="og:description" content="NFC Medya tarafından hazırlanan web tasarım, sosyal medya, SEO, grafik tasarım ve reklam projelerini keşfedin.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="https://www.nfcmedya.com.tr/assets/images/logo.png">
    <meta property="og:image:alt" content="NFC Medya Çerkezköy Reklam Ajansı Logo">
    <meta property="og:locale" content="tr_TR">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Projelerimiz | Çerkezköy Reklam Ajansı NFC Medya">
    <meta name="twitter:description" content="Çerkezköy reklam ajansı NFC Medya tarafından hazırlanan projeleri inceleyin.">
    <meta name="twitter:image" content="https://www.nfcmedya.com.tr/assets/images/logo.png">

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
                        primary: "#1f2d55",
                        secondary: "#c5c5c5",
                    },
                    borderRadius: {
                        none: "0px",
                        sm: "4px",
                        DEFAULT: "8px",
                        md: "12px",
                        lg: "16px",
                        xl: "20px",
                        "2xl": "24px",
                        "3xl": "32px",
                        full: "9999px",
                        button: "8px",
                    },
                },
            },
        };
    </script>

    <script type="application/ld+json">
    <?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
    </script>
</head>

<body class="main-layout">

<?php include "header.php"; ?>

<main class="pt-32 max-w-7xl mx-auto px-4">

    <section class="text-center mb-16" data-aos="fade-down" data-aos-duration="800">
        <p class="text-sm uppercase tracking-[0.25em] text-primary/70 mb-4">
            NFC Medya Portfolyo
        </p>

        <h1 class="text-4xl md:text-5xl font-bold text-primary mb-5">
            Çerkezköy Reklam Ajansı Projelerimiz
        </h1>

        <p class="max-w-3xl mx-auto text-gray-600 text-lg leading-relaxed">
            Web tasarım, sosyal medya yönetimi, SEO, grafik tasarım, video prodüksiyon
            ve açık hava reklamcılığı alanlarında hayata geçirdiğimiz çalışmaları inceleyin.
        </p>
    </section>

    <?php if (!empty($projeler)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $delay = 0;
            foreach ($projeler as $proje):
                $projectId = (int)($proje['id'] ?? 0);
                $projectTitle = cleanText($proje['baslik'] ?? 'NFC Medya Projesi', 90);
                $projectDesc = cleanText($proje['aciklama'] ?? '', 160);
                $projectImage = cleanImageFile($proje['resim'] ?? 'default.jpg');
                $detailUrl = 'https://www.nfcmedya.com.tr/proje-detay.php?id=' . $projectId;
            ?>
                <article class="bg-white rounded-xl shadow hover:shadow-xl transition overflow-hidden h-auto flex flex-col"
                         data-aos="fade-up"
                         data-aos-delay="<?= (int)$delay ?>">

                    <a href="<?= e($detailUrl) ?>" aria-label="<?= e($projectTitle) ?> projesini incele">
                        <img
                            src="https://www.nfcmedya.com.tr/uploads/proje/<?= e($projectImage) ?>"
                            alt="<?= e($projectTitle) ?> - NFC Medya projesi"
                            loading="lazy"
                            class="w-full aspect-[3/4] object-cover">
                    </a>

                    <div class="p-5 flex flex-col flex-grow">
                        <h2 class="text-xl font-bold text-primary mb-3 line-clamp-1">
                            <a href="<?= e($detailUrl) ?>" class="hover:text-[#9d181a] transition">
                                <?= e($projectTitle) ?>
                            </a>
                        </h2>

                        <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                            <?= e($projectDesc) ?>
                        </p>

                        <div class="mt-auto">
                            <a href="<?= e($detailUrl) ?>"
                               class="inline-block bg-primary text-white px-4 py-2 rounded hover:bg-[#9d181a] text-sm transition">
                                Detay
                            </a>
                        </div>
                    </div>
                </article>
            <?php
                $delay += 100;
            endforeach;
            ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow p-10 text-center text-gray-600">
            Henüz yayınlanmış proje bulunmamaktadır.
        </div>
    <?php endif; ?>

    <?php if ($totalPages > 1): ?>
        <div class="flex justify-center mt-12 mb-16">
            <nav class="flex flex-wrap gap-2 text-sm" aria-label="Proje sayfaları">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= $i === 1 ? '/proje' : '/proje?page=' . $i ?>"
                       class="px-3 py-1 rounded <?= $i === $page ? 'bg-primary text-white' : 'border border-gray-300 hover:bg-gray-200' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </nav>
        </div>
    <?php endif; ?>

</main>

<?php include "footer.php"; ?>

<script src="https://www.nfcmedya.com.tr/assets/js/script.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function setupDropdown(toggleId, dropdownId, arrowId) {
            const toggleBtn = document.getElementById(toggleId);
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById(arrowId);

            if (toggleBtn && dropdown && arrow) {
                toggleBtn.addEventListener("click", () => {
                    dropdown.classList.toggle("max-h-0");
                    dropdown.classList.toggle("max-h-[1000px]");
                    arrow.classList.toggle("rotate-180");
                });
            }
        }

        setupDropdown("toggleKurumsal", "kurumsalDropdown", "kurumsalArrow");
        setupDropdown("toggleHizmetler", "hizmetlerDropdown", "hizmetlerArrow");
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