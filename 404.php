<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NFC Medya | 404</title>
    <meta name="title" content="NFC Medya | 404">
    <link rel="canonical" href="https://www.nfcmedya.com.tr/404">
    <meta name="description" content="NFC Medya, web tasarım, sosyal medya yönetimi ve dijital pazarlama çözümleriyle Türkiye genelinde markanızı dijitalde büyütür.">
    <link rel="icon" href="https://www.nfcmedya.com.tr/assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
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
    <script>
    // Tıklama olayını yakala
    document.addEventListener('click', function(e){
        // Sayfanın sol üstünden koordinat
        const x = e.pageX;
        const y = e.pageY;

        // AJAX ile kaydet
        fetch('save_click.php', {
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify({page:'<?php echo basename($_SERVER['PHP_SELF']); ?>', x:x, y:y})
        });
    });
</script>
</head>


<body class="bg-white text-primary min-h-screen flex items-center justify-center">
    <div class="text-center px-6 py-12">
        <!-- Logo -->
        <a href="https://www.nfcmedya.com.tr">
            <img src="https://www.nfcmedya.com.tr/assets/images/logo.png" alt="NFC Medya Logo" class="mx-auto mb-6 w-20 h-20" />
        </a>

        <!-- 404 Message -->
        <h1 class="text-6xl font-bold mb-4">404</h1>
        <h2 class="text-2xl md:text-3xl mb-6 pacifico">Sayfa Bulunamadı</h2>
        <p class="text-lg text-gray mb-8">
            Aradığınız sayfa silinmiş, taşınmış olabilir ya da hiç var olmamış olabilir.
        </p>

       <a href="https://www.nfcmedya.com.tr"
   class="inline-block border-2 border-primary text-primary bg-white font-semibold py-3 px-6 rounded-button transition-all duration-300 hover:bg-primary hover:text-white">
   Anasayfaya Dön
</a>


    </div>
</body>

</html>