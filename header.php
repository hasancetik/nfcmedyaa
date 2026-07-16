<?php
$currentPath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');

function activeMenu(string $path, string $currentPath): string {
    return $currentPath === trim($path, '/') ? 'text-[#9d181a] font-semibold' : 'text-[#1f2d55]';
}

function activeGroup(array $paths, string $currentPath): string {
    foreach ($paths as $path) {
        if (str_starts_with($currentPath, trim($path, '/'))) {
            return 'text-[#9d181a] font-semibold';
        }
    }

    return 'text-[#1f2d55]';
}
?>

<header class="fixed w-full bg-white/95 backdrop-blur-md z-50 shadow-sm border-b border-gray-100">
    <div class="container max-w-screen-xl mx-auto px-4 py-3 flex justify-between items-center">

        <a href="https://www.nfcmedya.com.tr" aria-label="NFC Medya Ana Sayfa" class="flex items-center gap-3">
            <img 
                src="https://www.nfcmedya.com.tr/assets/images/logo.png" 
                alt="NFC Medya - Çerkezköy Reklam Ajansı" 
                width="48" 
                height="48"
                loading="eager">

            <div class="hidden sm:block leading-tight">
                <span class="block text-[#1f2d55] font-bold text-lg">NFC Medya</span>
                <span class="block text-xs text-gray-500">Çerkezköy Reklam Ajansı</span>
            </div>
        </a>

        <nav class="hidden lg:flex items-center space-x-8 relative" aria-label="Ana Menü">

            <a href="https://www.nfcmedya.com.tr"
               class="nav-link <?= activeMenu('', $currentPath); ?> hover:text-[#9d181a] transition">
                Ana Sayfa
            </a>

            <div class="relative group">
                <button type="button"
                        class="nav-link <?= activeGroup(['kurumsal'], $currentPath); ?> flex items-center gap-1 hover:text-[#9d181a] transition">
                    Kurumsal
                    <i class="ri-arrow-down-s-line text-base"></i>
                </button>

                <div class="absolute top-full left-0 mt-4 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-2 group-hover:translate-y-0 transition-all duration-300 z-50 overflow-hidden">
                    <a href="https://www.nfcmedya.com.tr/kurumsal/hakkimizda"
                       class="flex items-center gap-3 px-5 py-4 text-sm text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 transition">
                        <i class="ri-building-line text-xl"></i>
                        <span>NFC Medya Hakkında</span>
                    </a>

                    <a href="https://www.nfcmedya.com.tr/kurumsal/vizyon-misyon"
                       class="flex items-center gap-3 px-5 py-4 text-sm text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 transition">
                        <i class="ri-focus-3-line text-xl"></i>
                        <span>Vizyon / Misyon</span>
                    </a>
                </div>
            </div>

            <div class="relative group">
                <button type="button"
                        class="nav-link <?= activeGroup(['hizmetler'], $currentPath); ?> flex items-center gap-1 hover:text-[#9d181a] transition">
                    Hizmetler
                    <i class="ri-arrow-down-s-line text-base"></i>
                </button>

                <div class="absolute top-full left-1/2 -translate-x-1/2 mt-4 w-[620px] bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible translate-y-2 group-hover:translate-y-0 transition-all duration-300 z-50 p-4">
                    <div class="grid grid-cols-2 gap-2">

                        <a href="https://www.nfcmedya.com.tr/hizmetler/web-tasarim"
                           class="flex items-start gap-3 p-4 rounded-xl text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 transition">
                            <i class="ri-code-s-slash-line text-2xl mt-1"></i>
                            <span>
                                <strong class="block text-sm">Çerkezköy Web Tasarım</strong>
                                <small class="text-gray-500">SEO uyumlu kurumsal siteler</small>
                            </span>
                        </a>

                        <a href="https://www.nfcmedya.com.tr/hizmetler/grafik-tasarim"
                           class="flex items-start gap-3 p-4 rounded-xl text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 transition">
                            <i class="ri-palette-line text-2xl mt-1"></i>
                            <span>
                                <strong class="block text-sm">Grafik Tasarım</strong>
                                <small class="text-gray-500">Logo, katalog ve kurumsal kimlik</small>
                            </span>
                        </a>

                        <a href="https://www.nfcmedya.com.tr/hizmetler/video-produksiyon"
                           class="flex items-start gap-3 p-4 rounded-xl text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 transition">
                            <i class="ri-movie-line text-2xl mt-1"></i>
                            <span>
                                <strong class="block text-sm">Video Prodüksiyon</strong>
                                <small class="text-gray-500">Reels, tanıtım ve reklam filmi</small>
                            </span>
                        </a>

                        <a href="https://www.nfcmedya.com.tr/hizmetler/sosyal-medya"
                           class="flex items-start gap-3 p-4 rounded-xl text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 transition">
                            <i class="ri-instagram-line text-2xl mt-1"></i>
                            <span>
                                <strong class="block text-sm">Sosyal Medya Yönetimi</strong>
                                <small class="text-gray-500">İçerik, reklam ve hesap yönetimi</small>
                            </span>
                        </a>

                        <a href="https://www.nfcmedya.com.tr/hizmetler/seo"
                           class="flex items-start gap-3 p-4 rounded-xl text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 transition">
                            <i class="ri-search-eye-line text-2xl mt-1"></i>
                            <span>
                                <strong class="block text-sm">Çerkezköy SEO Hizmeti</strong>
                                <small class="text-gray-500">Google görünürlüğü ve yerel SEO</small>
                            </span>
                        </a>

                        <a href="https://www.nfcmedya.com.tr/hizmetler/acik-hava-reklam"
                           class="flex items-start gap-3 p-4 rounded-xl text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 transition">
                            <i class="ri-signpost-line text-2xl mt-1"></i>
                            <span>
                                <strong class="block text-sm">Açık Hava Reklamcılığı</strong>
                                <small class="text-gray-500">Tabela, baskı ve uygulama</small>
                            </span>
                        </a>

                    </div>
                </div>
            </div>

            <a href="https://www.nfcmedya.com.tr/proje"
               class="nav-link <?= activeMenu('proje', $currentPath); ?> hover:text-[#9d181a] transition">
                Projeler
            </a>

            <a href="https://www.nfcmedya.com.tr/iletisim"
               class="nav-link <?= activeMenu('iletisim', $currentPath); ?> hover:text-[#9d181a] transition">
                İletişim
            </a>
        </nav>

        <div class="hidden lg:flex items-center gap-3">
            <a href="https://wa.me/905301839574"
               target="_blank"
               rel="noopener noreferrer"
               class="w-10 h-10 flex items-center justify-center rounded-full border border-[#1f2d55]/20 text-[#1f2d55] hover:bg-[#1f2d55] hover:text-white transition"
               aria-label="WhatsApp">
                <i class="ri-whatsapp-line text-xl"></i>
            </a>

            <a href="https://www.nfcmedya.com.tr/iletisim"
               class="inline-flex items-center px-5 py-2.5 bg-[#1f2d55] text-white text-sm font-semibold rounded-button hover:bg-[#9d181a] transition shadow-md">
                Teklif Al
            </a>
        </div>

        <button 
            id="mobileMenuBtn" 
            type="button"
            aria-label="Mobil menüyü aç"
            class="lg:hidden w-10 h-10 flex items-center justify-center text-[#1f2d55]">
            <i class="ri-menu-line text-2xl"></i>
        </button>
    </div>
</header>

<div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden"></div>

<div id="mobileMenu"
     class="fixed top-0 right-0 h-full w-80 bg-white z-50 transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl flex flex-col">

    <div class="flex justify-between items-center px-6 py-4 border-b">
        <a href="https://www.nfcmedya.com.tr" aria-label="NFC Medya Ana Sayfa" class="flex items-center gap-3">
            <img 
                src="https://www.nfcmedya.com.tr/assets/images/logo.png" 
                alt="NFC Medya - Çerkezköy Reklam Ajansı" 
                width="45" 
                height="45">

            <div class="leading-tight">
                <span class="block text-[#1f2d55] font-bold">NFC Medya</span>
                <span class="block text-xs text-gray-500">Reklam Ajansı</span>
            </div>
        </a>

        <button 
            id="closeMobileMenu" 
            type="button"
            aria-label="Mobil menüyü kapat"
            class="text-3xl text-[#1f2d55] transition hover:text-[#9d181a]">
            <i class="ri-close-line"></i>
        </button>
    </div>

    <nav class="mt-6 flex flex-col px-6 space-y-2 text-base font-medium overflow-y-auto" aria-label="Mobil Menü">

        <a href="https://www.nfcmedya.com.tr"
           class="mobil-nav-link py-3 px-4 rounded-xl text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-100 transition">
            Ana Sayfa
        </a>

        <div class="mobil-nav-link">
            <button id="toggleKurumsal"
                    type="button"
                    class="w-full flex justify-between items-center py-3 px-4 text-left text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-100 rounded-xl transition">
                Kurumsal
                <i class="ri-arrow-down-s-line ml-2 transition-transform duration-300" id="kurumsalArrow"></i>
            </button>

            <div id="kurumsalDropdown"
                 class="ml-4 mt-1 space-y-1 max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                <a href="https://www.nfcmedya.com.tr/kurumsal/hakkimizda"
                   class="block py-2 px-2 text-sm text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 rounded">
                    NFC Medya Hakkında
                </a>

                <a href="https://www.nfcmedya.com.tr/kurumsal/vizyon-misyon"
                   class="block py-2 px-2 text-sm text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 rounded">
                    Vizyon / Misyon
                </a>
            </div>
        </div>

        <div class="mobil-nav-link">
            <button id="toggleHizmetler"
                    type="button"
                    class="w-full flex justify-between items-center py-3 px-4 text-left text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-100 rounded-xl transition">
                Hizmetler
                <i class="ri-arrow-down-s-line ml-2 transition-transform duration-300" id="hizmetlerArrow"></i>
            </button>

            <div id="hizmetlerDropdown"
                 class="ml-4 mt-1 space-y-1 max-h-0 overflow-hidden transition-all duration-300 ease-in-out">

                <a href="https://www.nfcmedya.com.tr/hizmetler/web-tasarim"
                   class="block py-2 px-2 text-sm text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 rounded">
                    Çerkezköy Web Tasarım
                </a>

                <a href="https://www.nfcmedya.com.tr/hizmetler/grafik-tasarim"
                   class="block py-2 px-2 text-sm text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 rounded">
                    Grafik Tasarım
                </a>

                <a href="https://www.nfcmedya.com.tr/hizmetler/video-produksiyon"
                   class="block py-2 px-2 text-sm text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 rounded">
                    Video Prodüksiyon
                </a>

                <a href="https://www.nfcmedya.com.tr/hizmetler/sosyal-medya"
                   class="block py-2 px-2 text-sm text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 rounded">
                    Sosyal Medya Yönetimi
                </a>

                <a href="https://www.nfcmedya.com.tr/hizmetler/seo"
                   class="block py-2 px-2 text-sm text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 rounded">
                    SEO Hizmeti
                </a>

                <a href="https://www.nfcmedya.com.tr/hizmetler/acik-hava-reklam"
                   class="block py-2 px-2 text-sm text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-50 rounded">
                    Açık Hava Reklamcılığı
                </a>
            </div>
        </div>

        <a href="https://www.nfcmedya.com.tr/proje"
           class="mobil-nav-link py-3 px-4 rounded-xl text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-100 transition">
            Projeler
        </a>

        <a href="https://www.nfcmedya.com.tr/iletisim"
           class="mobil-nav-link py-3 px-4 rounded-xl text-[#1f2d55] hover:text-[#9d181a] hover:bg-gray-100 transition">
            İletişim
        </a>

        <div class="mt-5 p-4 rounded-2xl bg-gray-50 border border-gray-100">
            <p class="text-sm text-gray-500 mb-3">
                Hızlı iletişim
            </p>

            <a href="https://wa.me/905301839574"
               target="_blank"
               rel="noopener noreferrer"
               class="flex items-center gap-2 text-[#1f2d55] font-semibold mb-3">
                <i class="ri-whatsapp-line text-xl"></i>
                +90 530 183 95 74
            </a>

            <a href="https://www.nfcmedya.com.tr/iletisim"
               class="block text-center py-3 px-4 bg-[#1f2d55] text-white rounded-button font-semibold hover:bg-[#9d181a] transition">
                Teklif Al
            </a>
        </div>
    </nav>
</div>