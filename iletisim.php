<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$status = $_GET['status'] ?? '';
$allowedStatuses = ['success', 'error'];

if (!in_array($status, $allowedStatuses, true)) {
    $status = '';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>İletişim | Çerkezköy Reklam Ajansı NFC Medya</title>
<meta name="description" content="Çerkezköy reklam ajansı NFC Medya ile iletişime geçin. Sosyal medya yönetimi, web tasarım, SEO, grafik tasarım ve reklam hizmetleri için teklif alın.">
<meta name="robots" content="index, follow, max-image-preview:large">
<link rel="canonical" href="https://www.nfcmedya.com.tr/iletisim">
<link rel="icon" href="https://www.nfcmedya.com.tr/assets/images/logo.png">

<meta property="og:site_name" content="NFC Medya">
<meta property="og:title" content="İletişim | Çerkezköy Reklam Ajansı NFC Medya">
<meta property="og:description" content="Çerkezköy'de sosyal medya yönetimi, web tasarım, SEO, grafik tasarım ve reklam hizmetleri için NFC Medya ile iletişime geçin.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.nfcmedya.com.tr/iletisim">
<meta property="og:image" content="https://www.nfcmedya.com.tr/assets/images/logo.png">
<meta property="og:image:alt" content="NFC Medya Çerkezköy Reklam Ajansı Logo">
<meta property="og:locale" content="tr_TR">

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
                accent: '#9d181a'
            },
            borderRadius: {
                button: '10px'
            }
        }
    }
};
</script>

<style>
.contact-hero {
    background:
        radial-gradient(circle at 12% 20%, rgba(157,24,26,.35), transparent 28%),
        radial-gradient(circle at 90% 18%, rgba(31,45,85,.45), transparent 34%),
        linear-gradient(135deg, #07101f 0%, #14203d 45%, #1f2d55 100%);
}
.hero-grid {
    background-image:
        linear-gradient(rgba(255,255,255,.07) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.07) 1px, transparent 1px);
    background-size: 44px 44px;
}
.glass-box {
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.15);
    backdrop-filter: blur(16px);
}
.info-card {
    transition: all .3s ease;
}
.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 45px rgba(31,45,85,.12);
}
.form-input {
    border: 1px solid #dbe1ea;
    background: #f8fafc;
    transition: all .25s ease;
}
.form-input:focus {
    background: #fff;
    border-color: #1f2d55;
    box-shadow: 0 0 0 4px rgba(31,45,85,.10);
}
</style>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "NFC Medya",
  "alternateName": "NFC Medya Çerkezköy Reklam Ajansı",
  "url": "https://www.nfcmedya.com.tr/iletisim",
  "logo": "https://www.nfcmedya.com.tr/assets/images/logo.png",
  "telephone": "+90-530-183-9574",
  "email": "info@nfcmedya.com.tr",
  "priceRange": "₺₺",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Gazi Mustafa Kemalpaşa, İnan Sk. No:4 Kat:5 Daire:9",
    "addressLocality": "Çerkezköy",
    "addressRegion": "Tekirdağ",
    "postalCode": "59500",
    "addressCountry": "TR"
  },
  "areaServed": [
    {"@type": "City", "name": "Çerkezköy"},
    {"@type": "City", "name": "Kapaklı"},
    {"@type": "City", "name": "Tekirdağ"}
  ],
  "sameAs": [
    "https://www.instagram.com/nfc_medya"
  ]
}
</script>
</head>

<body class="bg-[#f4f7fb] text-gray-900">

<?php include 'header.php'; ?>

<main>

<section class="contact-hero relative pt-36 pb-24 overflow-hidden">
    <div class="absolute inset-0 hero-grid opacity-70"></div>
    <div class="absolute -left-20 top-32 w-72 h-72 bg-[#9d181a]/30 rounded-full blur-3xl"></div>
    <div class="absolute -right-20 bottom-10 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

            <div class="lg:col-span-7" data-aos="fade-right">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-box text-white text-sm tracking-[0.18em] uppercase mb-7">
                    <span class="w-2 h-2 rounded-full bg-[#9d181a]"></span>
                    NFC Medya İletişim
                </span>

                <h1 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">
                    Reklam, dijital ve tasarım ihtiyaçlarınız için bize ulaşın.
                </h1>

                <p class="text-white/75 text-lg leading-relaxed max-w-2xl mb-9">
                    Çerkezköy merkezli reklam ajansı NFC Medya ile sosyal medya, web tasarım, SEO, grafik tasarım, video prodüksiyon ve açık hava reklam süreçlerinizi birlikte planlayalım.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="https://wa.me/905301839574" target="_blank" rel="noopener" class="inline-flex items-center px-7 py-4 bg-[#9d181a] text-white font-bold rounded-button hover:bg-[#c91d20] transition">
                        <i class="ri-whatsapp-line mr-2 text-xl"></i>
                        WhatsApp’tan Yaz
                    </a>

                    <a href="tel:+905301839574" class="inline-flex items-center px-7 py-4 border border-white/30 text-white font-bold rounded-button hover:bg-white hover:text-[#1f2d55] transition">
                        <i class="ri-phone-line mr-2 text-xl"></i>
                        Hemen Ara
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5" data-aos="fade-left">
                <div class="glass-box rounded-[34px] p-7 text-white shadow-2xl">
                    <div class="flex items-center justify-between mb-7">
                        <div>
                            <p class="text-white/55 text-sm">NFC Medya</p>
                            <h2 class="text-2xl font-black">Hızlı İletişim</h2>
                        </div>

                        <img src="https://www.nfcmedya.com.tr/assets/images/logo.png" alt="NFC Medya Logo" class="w-16 h-16 bg-white rounded-2xl p-2 object-contain">
                    </div>

                    <div class="space-y-4">
                        <a href="https://wa.me/905301839574" target="_blank" rel="noopener" class="flex items-center gap-4 bg-white/10 border border-white/10 rounded-2xl p-5 hover:bg-white/15 transition">
                            <div class="w-12 h-12 rounded-2xl bg-[#9d181a] flex items-center justify-center">
                                <i class="ri-whatsapp-line text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold">WhatsApp</h3>
                                <p class="text-white/65">+90 530 183 95 74</p>
                            </div>
                        </a>

                        <a href="mailto:info@nfcmedya.com.tr" class="flex items-center gap-4 bg-white/10 border border-white/10 rounded-2xl p-5 hover:bg-white/15 transition">
                            <div class="w-12 h-12 rounded-2xl bg-[#9d181a] flex items-center justify-center">
                                <i class="ri-mail-line text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold">E-Posta</h3>
                                <p class="text-white/65">info@nfcmedya.com.tr</p>
                            </div>
                        </a>

                        <div class="flex items-start gap-4 bg-white/10 border border-white/10 rounded-2xl p-5">
                            <div class="w-12 h-12 rounded-2xl bg-[#9d181a] flex items-center justify-center shrink-0">
                                <i class="ri-map-pin-2-line text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold">Adres</h3>
                                <p class="text-white/65">Gazi Mustafa Kemalpaşa, İnan Sk. No:4 Kat:5 Daire:9, Çerkezköy/Tekirdağ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-6 -mt-10 relative z-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <a href="https://wa.me/905301839574" target="_blank" rel="noopener" class="info-card bg-white rounded-[26px] p-7 shadow-xl border border-gray-100">
            <div class="w-14 h-14 rounded-2xl bg-[#1f2d55] text-white flex items-center justify-center mb-5">
                <i class="ri-whatsapp-line text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-[#1f2d55] mb-2">WhatsApp</h3>
            <p class="text-gray-600">Hızlı teklif ve bilgi almak için bize WhatsApp’tan yazabilirsiniz.</p>
        </a>

        <a href="tel:+905301839574" class="info-card bg-white rounded-[26px] p-7 shadow-xl border border-gray-100">
            <div class="w-14 h-14 rounded-2xl bg-[#9d181a] text-white flex items-center justify-center mb-5">
                <i class="ri-phone-line text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-[#1f2d55] mb-2">Telefon</h3>
            <p class="text-gray-600">Projeleriniz için doğrudan arayarak bizimle iletişime geçebilirsiniz.</p>
        </a>

        <a href="mailto:info@nfcmedya.com.tr" class="info-card bg-white rounded-[26px] p-7 shadow-xl border border-gray-100">
            <div class="w-14 h-14 rounded-2xl bg-[#1f2d55] text-white flex items-center justify-center mb-5">
                <i class="ri-mail-line text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-[#1f2d55] mb-2">E-Posta</h3>
            <p class="text-gray-600">Detaylı brief ve dosyalarınızı e-posta üzerinden gönderebilirsiniz.</p>
        </a>

    </div>
</section>

<section class="max-w-7xl mx-auto px-6 py-20">
    <?php if ($status !== ''): ?>
        <div class="mb-8">
            <?php if ($status === 'success'): ?>
                <div class="bg-green-100 border border-green-300 text-green-800 px-5 py-4 rounded-2xl shadow-sm" role="alert">
                    <strong>Gönderildi!</strong> Mesajınız başarıyla gönderildi.
                </div>
            <?php elseif ($status === 'error'): ?>
                <div class="bg-red-100 border border-red-300 text-red-800 px-5 py-4 rounded-2xl shadow-sm" role="alert">
                    <strong>Hata!</strong> Mesajınız gönderilemedi. Lütfen tekrar deneyin.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

        <div class="lg:col-span-4 space-y-6">
            <div class="bg-[#1f2d55] text-white rounded-[30px] p-8 shadow-xl">
                <span class="text-sm font-black text-white/60 tracking-[0.18em] uppercase">Adres</span>
                <h2 class="text-3xl font-black mt-3 mb-5">Ofis Bilgileri</h2>

                <div class="space-y-5">
                    <div>
                        <h3 class="font-bold mb-1">Adres</h3>
                        <p class="text-white/70">Gazi Mustafa Kemalpaşa, İnan Sk. No:4 Kat:5 Daire:9, 59500 Çerkezköy/Tekirdağ</p>
                    </div>

                    <div>
                        <h3 class="font-bold mb-1">Telefon</h3>
                        <a href="https://wa.me/905301839574" target="_blank" rel="noopener" class="block text-white/70 hover:text-white">+90 (530) 183 95 74</a>
                        <a href="https://wa.me/905078949574" target="_blank" rel="noopener" class="block text-white/70 hover:text-white">+90 (507) 894 95 74</a>
                    </div>

                    <div>
                        <h3 class="font-bold mb-1">E-Posta</h3>
                        <a href="mailto:info@nfcmedya.com.tr" class="text-white/70 hover:text-white">info@nfcmedya.com.tr</a>
                    </div>
                </div>

                <div class="flex gap-3 pt-7">
                    <a href="https://www.instagram.com/nfc_medya" target="_blank" rel="noopener" class="w-11 h-11 rounded-full bg-white text-[#1f2d55] flex items-center justify-center hover:bg-[#9d181a] hover:text-white transition">
                        <i class="ri-instagram-line text-xl"></i>
                    </a>
                    <a href="https://www.instagram.com/nfc_medya" target="_blank" rel="noopener" class="w-11 h-11 rounded-full bg-white text-[#1f2d55] flex items-center justify-center hover:bg-[#9d181a] hover:text-white transition">
                        <i class="ri-facebook-circle-fill text-xl"></i>
                    </a>
                    <a href="https://www.instagram.com/nfc_medya" target="_blank" rel="noopener" class="w-11 h-11 rounded-full bg-white text-[#1f2d55] flex items-center justify-center hover:bg-[#9d181a] hover:text-white transition">
                        <i class="ri-linkedin-fill text-xl"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-[30px] p-8 shadow-xl border border-gray-100">
                <h3 class="text-2xl font-black text-[#1f2d55] mb-4">Hizmetlerimiz</h3>

                <div class="grid grid-cols-1 gap-3 text-sm font-semibold">
                    <span class="bg-[#f4f7fb] rounded-xl px-4 py-3 text-[#1f2d55]">Web Tasarım</span>
                    <span class="bg-[#f4f7fb] rounded-xl px-4 py-3 text-[#1f2d55]">Sosyal Medya Yönetimi</span>
                    <span class="bg-[#f4f7fb] rounded-xl px-4 py-3 text-[#1f2d55]">SEO Hizmeti</span>
                    <span class="bg-[#f4f7fb] rounded-xl px-4 py-3 text-[#1f2d55]">Grafik Tasarım</span>
                    <span class="bg-[#f4f7fb] rounded-xl px-4 py-3 text-[#1f2d55]">Video Prodüksiyon</span>
                    <span class="bg-[#f4f7fb] rounded-xl px-4 py-3 text-[#1f2d55]">Açık Hava Reklam</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <form id="contactForm" method="post" class="bg-white rounded-[30px] p-8 md:p-10 shadow-xl border border-gray-100" action="send_contact.php" novalidate>
                <span class="text-sm font-black text-[#9d181a] tracking-[0.2em] uppercase">Teklif Formu</span>
                <h2 class="text-3xl md:text-5xl font-black text-[#1f2d55] mt-3 mb-3">Projenizi bize anlatın</h2>
                <p class="text-gray-600 mb-8">Formu doldurun, reklam ve dijital pazarlama ihtiyaçlarınız için size dönüş yapalım.</p>

                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-bold mb-2">Ad Soyad</label>
                        <input type="text" name="name" id="name" maxlength="100" autocomplete="name" class="form-input w-full px-4 py-4 rounded-xl outline-none" required placeholder="Ad Soyad">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-bold mb-2">Telefon</label>
                        <input type="tel" name="phone" id="phone" maxlength="25" autocomplete="tel" class="form-input w-full px-4 py-4 rounded-xl outline-none" required placeholder="Telefon Numaranız">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold mb-2">E-Posta</label>
                        <input type="email" name="email" id="email" maxlength="150" autocomplete="email" class="form-input w-full px-4 py-4 rounded-xl outline-none" required placeholder="E-Posta Adresiniz">
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-bold mb-2">Konu</label>
                        <input type="text" name="subject" id="subject" maxlength="150" class="form-input w-full px-4 py-4 rounded-xl outline-none" required placeholder="Hangi hizmetle ilgileniyorsunuz?">
                    </div>
                </div>

                <div class="mt-5">
                    <label for="message" class="block text-sm font-bold mb-2">Mesajınız</label>
                    <textarea rows="6" name="message" id="message" maxlength="2000" class="form-input w-full px-4 py-4 rounded-xl outline-none" required placeholder="Projenizi kısaca anlatın..."></textarea>
                </div>

                <button type="submit" class="mt-6 w-full bg-[#9d181a] text-white py-4 rounded-button font-black hover:bg-[#1f2d55] transition">
                    Mesajı Gönder
                </button>
            </form>
        </div>

    </div>
</section>

<section class="max-w-7xl mx-auto px-6 pb-20">
    <div class="rounded-[30px] overflow-hidden shadow-xl border border-gray-100 h-[420px]">
        <iframe
            title="NFC Medya Google Harita Konumu"
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d23982.378695676696!2d28.000877!3d41.291515!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14b5296277dd927d%3A0xe6af25f2d66388fa!2sNFC%20Medya!5e0!3m2!1str!2str!4v1737801969761!5m2!1str!2str"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>

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

    setTimeout(() => {
        const alert = document.querySelector('[role="alert"]');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);
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