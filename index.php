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

function cleanText(?string $value, int $limit = 180): string
{
    $text = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = trim(strip_tags($text));

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        return mb_strlen($text, 'UTF-8') > $limit
            ? mb_substr($text, 0, $limit, 'UTF-8') . '...'
            : $text;
    }

    return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
}

function cleanImageFile(?string $file): string
{
    $file = basename((string) $file);
    return preg_match('/^[a-zA-Z0-9_\-.çÇğĞıİöÖşŞüÜ]+$/u', $file) ? $file : 'default.jpg';
}

try {
    $stmt = $pdo->prepare('SELECT id, baslik, aciklama, resim FROM proje WHERE vitrin = :vitrin ORDER BY id DESC LIMIT 6');
    $stmt->execute(['vitrin' => 1]);
    $projeler = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Vitrin proje sorgu hatası: ' . $e->getMessage());
    $projeler = [];
}

$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');

$references = [
    ['file' => 'çetin-grup.png', 'alt' => 'Çetin Group - NFC Medya referansı'],
    ['file' => 'yünsa.png', 'alt' => 'Yünsa - NFC Medya referansı'],
    ['file' => 'seyidoglu-merkez.png', 'alt' => 'Seyidoğlu Merkez - NFC Medya referansı'],
    ['file' => 'atak.png', 'alt' => 'Atak Kozmetik - NFC Medya referansı'],
    ['file' => 'zoomlion.png', 'alt' => 'Zoomlion - NFC Medya referansı'],
    ['file' => 'kapaklı.png', 'alt' => 'Kapaklı Site Spor - NFC Medya referansı'],
    ['file' => 'blc.png', 'alt' => 'BLC Turizm - NFC Medya referansı'],
    ['file' => 'nokta.png', 'alt' => 'Nokta Akademi - NFC Medya referansı'],
    ['file' => 'seyidoglu.png', 'alt' => 'Seyidoğlu - NFC Medya referansı'],
    ['file' => 'eren-raf.png', 'alt' => 'Eren Raf - NFC Medya referansı'],
    ['file' => 'mucizeler.png', 'alt' => 'Mucizeler Diyarı - NFC Medya referansı'],
    ['file' => 'selanik_balık_logo.png', 'alt' => 'Selanik Balık - NFC Medya referansı'],
    ['file' => 'pusula.png', 'alt' => 'Pusula - NFC Medya referansı'],
    ['file' => 'NARCAR.png', 'alt' => 'NARCAR - NFC Medya referansı'],
    ['file' => 'benzinkapaklı.png', 'alt' => 'Benzin Kapaklı - NFC Medya referansı'],
    ['file' => 'tunç-steak.png', 'alt' => 'Tunç Steak - NFC Medya referansı'],
    ['file' => 'bahçelievleranaokululogo.png', 'alt' => 'Bahçelievler Anaokulu - NFC Medya referansı'],
];

$services = [
    ['title' => 'Web Tasarım', 'url' => 'https://www.nfcmedya.com.tr/hizmetler/web-tasarim', 'icon' => 'ri-layout-4-line', 'desc' => 'Hızlı, mobil uyumlu, SEO temeli güçlü ve satışa yönlendiren kurumsal web siteleri.'],
    ['title' => 'Sosyal Medya Yönetimi', 'url' => 'https://www.nfcmedya.com.tr/hizmetler/sosyal-medya', 'icon' => 'ri-instagram-line', 'desc' => 'İçerik planı, tasarım, reels, reklam ve düzenli hesap yönetimiyle görünürlük artışı.'],
    ['title' => 'SEO & Google Görünürlüğü', 'url' => 'https://www.nfcmedya.com.tr/hizmetler/seo', 'icon' => 'ri-search-eye-line', 'desc' => 'Yerel SEO, teknik optimizasyon ve içerik çalışmalarıyla aramalarda daha güçlü konum.'],
    ['title' => 'Grafik Tasarım', 'url' => 'https://www.nfcmedya.com.tr/hizmetler/grafik-tasarim', 'icon' => 'ri-palette-line', 'desc' => 'Logo, kurumsal kimlik, katalog, afiş ve sosyal medya görselleri için profesyonel tasarım.'],
    ['title' => 'Video Prodüksiyon', 'url' => 'https://www.nfcmedya.com.tr/hizmetler/video-produksiyon', 'icon' => 'ri-movie-2-line', 'desc' => 'Tanıtım filmi, reklam videosu, reels, drone çekimi ve post-prodüksiyon çözümleri.'],
    ['title' => 'Açık Hava Reklamcılığı', 'url' => 'https://www.nfcmedya.com.tr/hizmetler/acik-hava-reklam', 'icon' => 'ri-megaphone-line', 'desc' => 'Tabela, araç kaplama, dijital baskı ve dış mekan reklam uygulamaları.'],
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Çerkezköy Reklam Ajansı | Sosyal Medya, Web Tasarım ve SEO | NFC Medya</title>
    <meta name="description" content="Çerkezköy reklam ajansı NFC Medya; sosyal medya yönetimi, web tasarım, SEO, grafik tasarım, video prodüksiyon ve açık hava reklam çözümleri sunar.">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <meta name="author" content="NFC Medya">
    <link rel="canonical" href="https://www.nfcmedya.com.tr/">
    <link rel="icon" href="https://www.nfcmedya.com.tr/assets/images/logo.png">
    <meta name="google-site-verification" content="-PYvImFRavOTdp3csyg5g91A_WvKFNyDs5fEcYn8Ddg">

    <meta property="og:site_name" content="NFC Medya">
    <meta property="og:title" content="Çerkezköy Reklam Ajansı | NFC Medya">
    <meta property="og:description" content="Çerkezköy'de sosyal medya yönetimi, web tasarım, SEO, grafik tasarım, video prodüksiyon ve açık hava reklam hizmetleri.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.nfcmedya.com.tr/">
    <meta property="og:image" content="https://www.nfcmedya.com.tr/assets/images/logo.png">
    <meta property="og:image:alt" content="NFC Medya Çerkezköy Reklam Ajansı Logo">
    <meta property="og:locale" content="tr_TR">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Çerkezköy Reklam Ajansı | NFC Medya">
    <meta name="twitter:description" content="Çerkezköy reklam ajansı NFC Medya ile sosyal medya, web tasarım, SEO ve reklam çözümleri.">
    <meta name="twitter:image" content="https://www.nfcmedya.com.tr/assets/images/logo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EF9R992QJX"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-EF9R992QJX');</script>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.nfcmedya.com.tr/assets/css/style.css">

    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter','system-ui','sans-serif'] }, colors: { ink:'#07111f', night:'#0f172a', primary:'#1f2d55', electric:'#2563eb', cyan:'#38bdf8', soft:'#f8fafc' }, boxShadow: { glow:'0 24px 80px rgba(37,99,235,.24)' }, borderRadius: { button:'999px' } } } };
    </script>

    <style>
        html{scroll-behavior:smooth} body{font-family:Inter,system-ui,sans-serif;background:#fff;color:#0f172a}.glass{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);backdrop-filter:blur(18px)}.grid-bg{background-image:linear-gradient(rgba(255,255,255,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.06) 1px,transparent 1px);background-size:44px 44px}.marquee{animation:marquee 26s linear infinite}@keyframes marquee{from{transform:translateX(0)}to{transform:translateX(-50%)}}.text-gradient{background:linear-gradient(135deg,#fff 0%,#93c5fd 48%,#38bdf8 100%);-webkit-background-clip:text;background-clip:text;color:transparent}.shine{position:relative;overflow:hidden}.shine:after{content:"";position:absolute;inset:-120% auto auto -60%;width:80%;height:300%;transform:rotate(25deg);background:linear-gradient(90deg,transparent,rgba(255,255,255,.26),transparent);transition:.7s}.shine:hover:after{left:130%}.service-card:hover .service-icon{transform:translateY(-8px) rotate(-4deg) scale(1.05)}
    </style>

    <script type="application/ld+json">{"@context":"https://schema.org","@type":"LocalBusiness","name":"NFC Medya","alternateName":"NFC Medya Çerkezköy Reklam Ajansı","url":"https://www.nfcmedya.com.tr/","logo":"https://www.nfcmedya.com.tr/assets/images/logo.png","image":"https://www.nfcmedya.com.tr/assets/images/logo.png","description":"NFC Medya, Çerkezköy merkezli reklam ajansı olarak sosyal medya yönetimi, web tasarım, SEO, grafik tasarım, video prodüksiyon ve açık hava reklamcılığı hizmetleri sunar.","telephone":"+90-530-183-9574","priceRange":"₺₺","address":{"@type":"PostalAddress","streetAddress":"Gazi Mustafa Kemalpaşa, İnan Sk. No:4 Kat:5 Daire:9","addressLocality":"Çerkezköy","addressRegion":"Tekirdağ","postalCode":"59500","addressCountry":"TR"},"areaServed":[{"@type":"City","name":"Çerkezköy"},{"@type":"City","name":"Kapaklı"},{"@type":"City","name":"Tekirdağ"}],"sameAs":["https://www.instagram.com/nfc_medya"],"makesOffer":[{"@type":"Offer","itemOffered":{"@type":"Service","name":"Çerkezköy Sosyal Medya Yönetimi"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"Çerkezköy Web Tasarım"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"Çerkezköy SEO Hizmeti"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"Grafik Tasarım"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"Video Prodüksiyon"}},{"@type":"Offer","itemOffered":{"@type":"Service","name":"Açık Hava Reklamcılığı"}}]}</script>
    <script type="application/ld+json">{"@context":"https://schema.org","@type":"FAQPage","mainEntity":[{"@type":"Question","name":"NFC Medya hangi hizmetleri verir?","acceptedAnswer":{"@type":"Answer","text":"NFC Medya; sosyal medya yönetimi, web tasarım, SEO, grafik tasarım, video prodüksiyon ve açık hava reklamcılığı hizmetleri sunar."}},{"@type":"Question","name":"NFC Medya nerede hizmet veriyor?","acceptedAnswer":{"@type":"Answer","text":"NFC Medya, Çerkezköy merkezli çalışır; Çerkezköy, Kapaklı ve Tekirdağ bölgesindeki işletmelere reklam ve dijital pazarlama çözümleri sunar."}}]}</script>
</head>
<body class="bg-white antialiased">
<?php include 'header.php'; ?>

<main>
    <section id="home" class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-[#1f2d55] to-[#2a3d73]">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[#1f2d55]/80"></div>
            <canvas id="particleCanvas" class="absolute inset-0"></canvas>
            <div class="absolute inset-0" style="background-image: url('assets/images/banner2.png'); background-size: cover; background-position: center; mix-blend-mode: overlay;"></div>
        </div>

        <div class="container max-w-screen-xl mx-auto px-4 relative z-10">
            <div class="flex justify-center items-center">
                <div class="text-white max-w-5xl text-center" data-aos="fade-up">
                    <p class="text-sm sm:text-base uppercase tracking-[0.25em] text-white/80 mb-5">Çerkezköy Reklam Ajansı</p>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mt-10">
                        NFC Medya ile Markanızı Çerkezköy’de ve Dijitalde Öne Çıkarın
                    </h1>

                    <p class="text-lg sm:text-xl text-gray-300 mt-8 mb-10 leading-relaxed mx-auto max-w-4xl" id="heroText">
                        Çerkezköy merkezli reklam ajansı NFC Medya; sosyal medya yönetimi, web tasarım, SEO, grafik tasarım, video prodüksiyon ve açık hava reklamcılığı hizmetleriyle işletmenizin görünürlüğünü artırır.
                    </p>

                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="#services" class="group inline-flex items-center px-8 py-4 bg-white text-[#1f2d55] font-semibold rounded-button hover:bg-primary transition-all transform relative overflow-hidden hover:text-white">
                            <span class="relative z-10">Hizmetlerimizi Keşfedin</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-[#1f2d55] to-[#2a3d73] transform translate-x-full group-hover:translate-x-0 transition-transform duration-500"></div>
                            <i class="ri-arrow-right-line ml-2 mt-0.5 group-hover:translate-x-2 transition-transform relative z-10"></i>
                        </a>

                        <a href="https://www.nfcmedya.com.tr/iletisim" class="group inline-flex items-center px-8 py-4 border-2 border-white text-white font-semibold rounded-button hover:bg-white hover:text-[#1f2d55] transition-colors transform">
                            Teklif Alın
                            <i class="ri-arrow-right-line ml-2 mt-0.5 group-hover:translate-x-2 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="py-24 lg:py-32 bg-soft">
        <div class="container max-w-screen-xl mx-auto px-4">
            <div class="max-w-3xl" data-aos="fade-up">
                <span class="text-sm font-black tracking-[.24em] uppercase text-electric">Çözümlerimiz</span>
                <h2 class="mt-5 text-4xl md:text-6xl font-black tracking-[-0.04em] text-night">Reklam, tasarım ve dijital büyüme tek merkezde.</h2>
                <p class="mt-6 text-lg text-slate-600 leading-relaxed">Her hizmeti ayrı iş değil, markanız için tek bir büyüme sistemi olarak planlıyoruz.</p>
            </div>
            <div class="mt-14 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($services as $index => $service): ?>
                    <a href="<?= e($service['url']) ?>" class="service-card group rounded-[2rem] bg-white p-8 border border-slate-100 hover:border-electric/30 hover:shadow-2xl transition-all duration-500" data-aos="fade-up" data-aos-delay="<?= 80 * ($index + 1) ?>">
                        <div class="service-icon h-16 w-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center transition-all duration-500 group-hover:bg-primary group-hover:text-white">
                            <i class="<?= e($service['icon']) ?> text-3xl"></i>
                        </div>
                        <h3 class="mt-8 text-2xl font-black text-night"><?= e($service['title']) ?></h3>
                        <p class="mt-4 text-slate-600 leading-relaxed"><?= e($service['desc']) ?></p>
                        <span class="mt-8 inline-flex items-center font-bold text-primary">Detaylı Bilgi <i class="ri-arrow-right-line ml-2 group-hover:translate-x-2 transition-transform"></i></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white overflow-hidden">
        <div class="container max-w-screen-xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <span class="text-sm font-black tracking-[.24em] uppercase text-electric">Neden NFC Medya?</span>
                <h2 class="mt-5 text-4xl md:text-6xl font-black tracking-[-0.04em] text-night">Yerel gücü dijital stratejiyle birleştiriyoruz.</h2>
                <p class="mt-6 text-lg text-slate-600 leading-relaxed">Çerkezköy, Kapaklı ve Tekirdağ bölgesindeki işletmeler için sadece güzel tasarım değil; güven veren, satışa yönlendiren ve ölçülebilir reklam süreçleri kurguluyoruz.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <span class="rounded-full bg-slate-100 px-5 py-3 font-bold text-slate-700">Strateji</span>
                    <span class="rounded-full bg-slate-100 px-5 py-3 font-bold text-slate-700">Tasarım</span>
                    <span class="rounded-full bg-slate-100 px-5 py-3 font-bold text-slate-700">Performans</span>
                    <span class="rounded-full bg-slate-100 px-5 py-3 font-bold text-slate-700">Yerel SEO</span>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-5" data-aos="fade-left">
                <?php foreach ([['Yerel Görünürlük','Çerkezköy ve çevre aramalarında daha güçlü marka algısı.'],['Düzenli İçerik','Profesyonel tasarım, reels ve paylaşım planı.'],['SEO Altyapısı','Google için anlaşılır, hızlı ve kullanıcı dostu sayfalar.'],['Tek Elden Hizmet','Web, sosyal medya, video ve baskı süreçleri aynı ekipte.']] as $item): ?>
                    <div class="rounded-[1.75rem] bg-slate-50 border border-slate-100 p-7 hover:bg-night hover:text-white transition-all duration-500">
                        <h3 class="text-xl font-black mb-3"><?= e($item[0]) ?></h3>
                        <p class="text-slate-600 group-hover:text-white/70"><?= e($item[1]) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="proje" class="py-32 relative overflow-hidden">
        <div class="container max-w-screen-xl mx-auto px-4 relative">
            <div class="flex flex-col items-center mb-20" data-aos="fade-up" data-aos-duration="1000">
                <div class="relative inline-block mb-6">
                    <span class="absolute -inset-4 bg-[#1f2d55] opacity-10 rounded-full blur-lg"></span>
                    <span class="relative text-sm font-bold text-[#1f2d55] tracking-[0.2em] uppercase">Portfolyo</span>
                </div>
                <h2 class="text-4xl md:text-6xl font-bold text-[#1f2d55] mb-8 text-center">Çerkezköy ve Bölgeden Seçkin Projelerimiz</h2>
                <p class="text-gray-600 max-w-2xl text-center text-lg leading-relaxed">Yaratıcı çözümlerimiz ve başarı hikayeleriyle markaları dijitalde ve sahada daha görünür hale getiriyoruz.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                <div class="absolute inset-0 bg-gradient-to-r from-[#1f2d55]/5 via-transparent to-[#1f2d55]/5 blur-3xl -z-10"></div>

                <?php if (!empty($projeler)): ?>
                    <?php foreach ($projeler as $proje): ?>
                        <?php
                            $projeId = (int) ($proje['id'] ?? 0);
                            $projeBaslik = e($proje['baslik'] ?? 'NFC Medya Projesi');
                            $projeAciklama = e(cleanText($proje['aciklama'] ?? '', 170));
                            $projeResim = e(cleanImageFile($proje['resim'] ?? 'default.jpg'));
                        ?>
                        <a href="https://www.nfcmedya.com.tr/proje-detay.php?id=<?= $projeId ?>" class="group relative rounded-xl overflow-hidden bg-white shadow-lg block" aria-label="<?= $projeBaslik ?> projesini incele">
                            <div class="relative h-[500px] overflow-hidden">
                                <img src="https://www.nfcmedya.com.tr/uploads/proje/<?= $projeResim ?>" loading="lazy" alt="<?= $projeBaslik ?> - NFC Medya projesi" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#1f2d55] to-transparent opacity-90 sm:opacity-0 sm:group-hover:opacity-90 transition-all duration-500 flex items-end p-8">
                                    <div class="transform translate-y-0 sm:translate-y-8 sm:group-hover:translate-y-0 transition-transform duration-500">
                                        <h3 class="text-white text-2xl font-bold mt-2 line-clamp-1"><?= $projeBaslik ?></h3>
                                        <p class="text-white/80 mt-4 mb-6 line-clamp-3"><?= $projeAciklama ?></p>
                                        <span class="inline-flex items-center text-white hover:text-white/80 transition-colors">Projeyi İncele <i class="ri-arrow-right-line ml-2 transition-transform group-hover:translate-x-2"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center text-gray-600 bg-gray-50 rounded-xl p-10">Vitrin projeleri yakında burada yayınlanacaktır.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

        <section id="references" class="py-20 bg-white overflow-hidden">
        <div class="container max-w-screen-xl mx-auto px-4" data-aos="fade-up">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-sm font-black tracking-[.24em] uppercase text-electric">Referanslarımız</span>
                <h2 class="mt-4 text-4xl md:text-5xl font-black tracking-[-0.04em] text-night">Bölgenin güvenen markaları.</h2>
            </div>
        </div>
        <div class="relative whitespace-nowrap overflow-hidden border-y border-slate-100 py-6">
            <div class="inline-flex gap-8 marquee">
                <?php for ($i = 0; $i < 2; $i++): foreach ($references as $reference): ?>
                    <div class="h-28 w-44 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center px-6">
                        <img src="https://www.nfcmedya.com.tr/assets/images/referans/<?= e(cleanImageFile($reference['file'])) ?>" loading="lazy" alt="<?= e($reference['alt']) ?>" class="max-h-16 max-w-28 object-contain grayscale hover:grayscale-0 transition-all">
                    </div>
                <?php endforeach; endfor; ?>
            </div>
        </div>
    </section>

    <section class="py-24 bg-soft">
        <div class="container max-w-screen-xl mx-auto px-4 grid lg:grid-cols-2 gap-12">
            <div data-aos="fade-right">
                <span class="text-sm font-black tracking-[.24em] uppercase text-electric">SSS</span>
                <h2 class="mt-5 text-4xl md:text-5xl font-black tracking-[-0.04em] text-night">Sık sorulan sorular.</h2>
                <p class="mt-5 text-slate-600 text-lg">Başlamadan önce en çok merak edilen konular.</p>
            </div>
            <div class="space-y-4" data-aos="fade-left">
                <?php foreach ([['NFC Medya hangi işleri yapıyor?','Sosyal medya yönetimi, web tasarım, SEO, grafik tasarım, video prodüksiyon ve açık hava reklamcılığı alanlarında hizmet veriyoruz.'],['Sadece Çerkezköy’e mi hizmet veriyorsunuz?','Merkezimiz Çerkezköy’dedir. Kapaklı, Tekirdağ ve çevre bölgelerdeki işletmelere de hizmet sunuyoruz.'],['Web sitesi ve sosyal medya birlikte yapılabilir mi?','Evet. Web sitesi, SEO ve sosyal medya içeriklerini birlikte planlayarak bütünlüklü dijital görünürlük oluşturabiliriz.']] as $faq): ?>
                    <details class="group rounded-[1.5rem] bg-white border border-slate-100 p-6 open:shadow-xl transition-all">
                        <summary class="cursor-pointer list-none flex items-center justify-between gap-4 font-black text-lg text-night"><?= e($faq[0]) ?><i class="ri-add-line group-open:rotate-45 transition-transform text-2xl"></i></summary>
                        <p class="mt-4 text-slate-600 leading-relaxed"><?= e($faq[1]) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="contact" class="relative py-24 bg-night text-white overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(37,99,235,.35),transparent_45%)]"></div>
        <div class="container max-w-screen-xl mx-auto px-4 relative z-10 text-center" data-aos="zoom-in">
            <span class="inline-flex rounded-full glass px-5 py-2 text-sm font-bold text-white/80">Markanız için yeni dönem</span>
            <h2 class="mt-6 text-4xl md:text-6xl font-black tracking-[-0.04em] max-w-4xl mx-auto">Dijitalde daha güçlü görünmek için ilk adımı atalım.</h2>
            <p class="mt-6 text-lg text-slate-300 max-w-2xl mx-auto">Web, sosyal medya, SEO, video ve reklam ihtiyaçlarınızı birlikte planlayalım.</p>
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="https://www.nfcmedya.com.tr/iletisim" class="shine inline-flex items-center justify-center px-8 py-4 rounded-button bg-white text-night font-black">Teklif Alın <i class="ri-arrow-right-up-line ml-2"></i></a>
                <a href="tel:+905301839574" class="inline-flex items-center justify-center px-8 py-4 rounded-button glass font-black">Hemen Arayın <i class="ri-phone-line ml-2"></i></a>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>

<script src="https://www.nfcmedya.com.tr/assets/js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
    function setupDropdown(toggleId,dropdownId,arrowId){const toggleBtn=document.getElementById(toggleId);const dropdown=document.getElementById(dropdownId);const arrow=document.getElementById(arrowId);if(toggleBtn&&dropdown&&arrow){toggleBtn.addEventListener('click',()=>{dropdown.classList.toggle('max-h-0');dropdown.classList.toggle('max-h-[1000px]');arrow.classList.toggle('rotate-180');});}}
    setupDropdown('toggleKurumsal','kurumsalDropdown','kurumsalArrow');setupDropdown('toggleHizmetler','hizmetlerDropdown','hizmetlerArrow');
});
</script>
<script>
document.addEventListener('click',function(e){const target=e.target;if(target&&target.closest&&target.closest('a, button')){fetch('https://www.nfcmedya.com.tr/save_click.php',{method:'POST',headers:{'Content-Type':'application/json'},credentials:'same-origin',body:JSON.stringify({page:<?= json_encode($currentPage, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,x:Math.round(e.pageX),y:Math.round(e.pageY)})}).catch(function(){});}});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init({duration:800,once:true,offset:80});</script>
</body>
</html>

