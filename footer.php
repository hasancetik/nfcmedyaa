<footer class="bg-white text-[#1f2d55] py-16 border-t border-gray-200">
    <div class="container max-w-screen-xl mx-auto px-4 sm:px-6 md:px-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 text-center sm:text-left">

            <!-- Logo ve Açıklama -->
            <div class="flex flex-col items-center sm:items-start">
                <a href="https://www.nfcmedya.com.tr" title="NFC Medya Ana Sayfa">
                    <img 
                        src="https://www.nfcmedya.com.tr/assets/images/logo.png"
                        alt="NFC Medya Çerkezköy Reklam Ajansı Logosu"
                        width="100"
                        height="100"
                        loading="lazy">
                </a>

                <p class="text-[#1f2d55] mt-5 text-sm leading-relaxed">
                    <strong>Çerkezköy reklam ajansı</strong> NFC Medya; sosyal medya yönetimi,
                    web tasarım, SEO, grafik tasarım ve video prodüksiyon hizmetleriyle
                    markanızı dijitalde öne çıkarır.
                </p>
            </div>

            <!-- Hizmetler -->
            <div>
                <h3 class="text-lg font-bold mb-5">Hizmetlerimiz</h3>

                <ul class="space-y-3 text-sm">

                    <li>
                        <a href="/hizmetler/web-tasarim"
                           class="hover:text-[#9d181a] transition-colors">
                            Çerkezköy Web Tasarım
                        </a>
                    </li>

                    <li>
                        <a href="/hizmetler/sosyal-medya"
                           class="hover:text-[#9d181a] transition-colors">
                            Sosyal Medya Yönetimi
                        </a>
                    </li>

                    <li>
                        <a href="/hizmetler/seo"
                           class="hover:text-[#9d181a] transition-colors">
                            Çerkezköy SEO Hizmeti
                        </a>
                    </li>

                    <li>
                        <a href="/hizmetler/grafik-tasarim"
                           class="hover:text-[#9d181a] transition-colors">
                            Grafik Tasarım
                        </a>
                    </li>

                    <li>
                        <a href="/hizmetler/video-produksiyon"
                           class="hover:text-[#9d181a] transition-colors">
                            Video Prodüksiyon
                        </a>
                    </li>

                    <li>
                        <a href="/proje"
                           class="hover:text-[#9d181a] transition-colors">
                            Tüm Projeler
                        </a>
                    </li>

                </ul>
            </div>

            <!-- İletişim -->
            <div>
                <h3 class="text-lg font-bold mb-5">İletişim</h3>

                <address class="not-italic space-y-4 text-sm">

                    <p class="flex items-start justify-center sm:justify-start">
                        <i class="ri-map-pin-line mr-2 mt-1"></i>
                        <span>
                            Gazi Mustafa Kemalpaşa,<br>
                            İnan Sk. No:4 Kat:5 Daire:9,<br>
                            59500 Çerkezköy / Tekirdağ
                        </span>
                    </p>

                    <p class="flex items-center justify-center sm:justify-start">
                        <i class="ri-phone-line mr-2"></i>

                        <a href="tel:+905301839574"
                           class="hover:text-[#9d181a] transition-colors">
                            +90 (530) 183 95 74
                        </a>
                    </p>

                    <p class="flex items-center justify-center sm:justify-start">
                        <i class="ri-mail-line mr-2"></i>

                        <a href="mailto:info@nfcmedya.com.tr"
                           class="hover:text-[#9d181a] transition-colors">
                            info@nfcmedya.com.tr
                        </a>
                    </p>

                </address>
            </div>

            <!-- Sosyal Medya -->
            <div>
                <h3 class="text-lg font-bold mb-5">Bizi Takip Edin</h3>

                <p class="text-sm text-gray-600 leading-relaxed mb-5">
                    NFC Medya’nın sosyal medya hesaplarını takip ederek
                    güncel projelerimizi ve çalışmalarımızı inceleyebilirsiniz.
                </p>

                <div class="flex justify-center sm:justify-start space-x-4">

                    <a href="https://www.instagram.com/nfc_medya"
                       target="_blank"
                       rel="noopener noreferrer"
                       title="NFC Medya Instagram"
                       aria-label="NFC Medya Instagram"
                       class="w-10 h-10 flex items-center justify-center rounded-full border border-[#1f2d55] text-[#1f2d55] hover:bg-[#1f2d55] hover:text-white transition-colors">

                        <i class="ri-instagram-fill"></i>
                    </a>

                </div>
            </div>

        </div>

        <!-- Alt Kısım -->
        <div class="border-t border-gray-300 mt-12 pt-8 text-center">

            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                NFC Medya, Çerkezköy ve Tekirdağ bölgesinde reklam ajansı,
                sosyal medya yönetimi, SEO ve web tasarım hizmetleri sunmaktadır.
            </p>

            <p class="text-sm">
                &copy; <?= date('Y') ?>

                <a href="https://www.nfcmedya.com.tr"
                   class="font-semibold hover:text-[#9d181a] transition-colors">
                    NFC Medya
                </a>

                . Tüm hakları saklıdır.
            </p>

        </div>

    </div>
</footer>

<!-- Sabit Butonlar -->
<div class="fixed bottom-10 right-6 sm:right-10 flex flex-col gap-3 z-50">

    <!-- Yukarı Çık -->
    <button id="scrollTopBtn"
            type="button"
            aria-label="Yukarı çık"
            class="hidden w-14 h-14 rounded-full border-2 border-[#1f2d55] bg-white text-[#1f2d55] text-3xl shadow-md hover:bg-[#1f2d55] hover:text-white transition flex items-center justify-center">

        <i class="ri-arrow-up-line"></i>
    </button>

    <!-- WhatsApp -->
    <a href="https://wa.me/905301839574"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="WhatsApp ile iletişime geç"
       class="w-14 h-14 rounded-full bg-green-500 text-white flex items-center justify-center text-3xl shadow-md hover:bg-green-600 transition">

        <i class="ri-whatsapp-line"></i>
    </a>

</div>

<script>
    window.addEventListener('scroll', () => {
        const scrollBtn = document.getElementById('scrollTopBtn');

        if (window.scrollY > 200) {
            scrollBtn.classList.remove('hidden');
        } else {
            scrollBtn.classList.add('hidden');
        }
    });

    document.getElementById('scrollTopBtn').addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>