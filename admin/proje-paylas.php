<?php
declare(strict_types=1);

require_once 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Europe/Istanbul');

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../404");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$baslik = '';
$aciklama = '';

function temizle(?string $veri): string {
    return htmlspecialchars((string)$veri, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        exit('Geçersiz güvenlik isteği.');
    }

    $baslik = trim($_POST['baslik'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');
    $tarih = date("Y-m-d H:i:s");

    if ($baslik === '') {
        $errors['baslik'] = "Başlık boş bırakılamaz.";
    }

    if ($aciklama === '') {
        $errors['aciklama'] = "Açıklama boş bırakılamaz.";
    }

    $resimler = [];
    $allowedMime = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $upload_dir = dirname(__DIR__) . '/uploads/proje/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (!empty($_FILES['resim']['name'][0])) {
        foreach ($_FILES['resim']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['resim']['error'][$key] !== UPLOAD_ERR_OK) {
                continue;
            }

            $mime = mime_content_type($tmp_name);

            if (!isset($allowedMime[$mime])) {
                continue;
            }

            if ((int)$_FILES['resim']['size'][$key] > 5 * 1024 * 1024) {
                continue;
            }

            $extension = $allowedMime[$mime];
            $yeniAd = uniqid('proje_', true) . '.' . $extension;
            $hedef = $upload_dir . $yeniAd;

            if (move_uploaded_file($tmp_name, $hedef)) {
                $resimler[] = $yeniAd;
            }
        }
    }

    $resim_listesi = implode(',', $resimler);

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO proje 
                (baslik, aciklama, tarih, resim)
                VALUES 
                (:baslik, :aciklama, :tarih, :resim)
            ");

            $stmt->execute([
                ':baslik' => $baslik,
                ':aciklama' => $aciklama,
                ':tarih' => $tarih,
                ':resim' => $resim_listesi
            ]);

            header("Location: proje");
            exit;
        } catch (PDOException $e) {
            error_log('Proje ekleme hatası: ' . $e->getMessage());
            $errors['db'] = 'Proje eklenirken sistemsel bir hata oluştu.';
        }
    }
}

include 'header.php';
?>

<style>
.proje-add-hero {
    background: linear-gradient(135deg, #1f2d55 0%, #31447a 100%);
    color: #fff;
    border-radius: 18px;
    padding: 30px;
    margin-bottom: 24px;
    box-shadow: 0 14px 35px rgba(31,45,85,.16);
}

.proje-add-card {
    border: 0;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(31,45,85,.08);
}

.upload-box {
    border: 2px dashed #d8d8d8;
    border-radius: 16px;
    padding: 22px;
    background: #fafafa;
}

.form-control,
.form-select {
    border-radius: 10px;
}

textarea.form-control {
    min-height: 260px;
}

.proje-info-list {
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
}

.proje-info-list li {
    margin-bottom: 12px;
    color: #6e6b7b;
}
</style>

<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">

        <div class="content-header row mb-2">
            <div class="content-header-left col-md-9 col-12">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Proje Paylaş</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index">Anasayfa</a></li>
                                <li class="breadcrumb-item"><a href="proje">Projeler</a></li>
                                <li class="breadcrumb-item active">Proje Paylaş</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">

            <div class="proje-add-hero">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="text-white mb-1">Yeni Proje Ekle</h2>
                        <p class="mb-0 text-white-50">
                            NFC Medya portföyüne yeni proje ekleyebilir, açıklama ve görsellerini buradan yönetebilirsin.
                        </p>
                    </div>

                    <div class="col-lg-4 text-lg-end mt-2 mt-lg-0">
                        <a href="proje" class="btn btn-light">
                            Projelere Dön
                        </a>
                    </div>
                </div>
            </div>

            <?php if (!empty($errors['db'])): ?>
                <div class="alert alert-danger">
                    <?php echo temizle($errors['db']); ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">

                <input type="hidden" name="csrf_token" value="<?php echo temizle($_SESSION['csrf_token']); ?>">

                <div class="row">

                    <div class="col-xl-8 col-lg-7">
                        <div class="card proje-add-card">
                            <div class="card-header border-bottom">
                                <div>
                                    <h4 class="card-title mb-25">Proje İçeriği</h4>
                                    <small class="text-muted">Başlık ve açıklama alanlarını doldur.</small>
                                </div>
                            </div>

                            <div class="card-body pt-2">

                                <div class="mb-2">
                                    <label class="form-label">Başlık</label>
                                    <input 
                                        type="text" 
                                        name="baslik" 
                                        class="form-control <?php echo isset($errors['baslik']) ? 'is-invalid' : ''; ?>" 
                                        value="<?php echo temizle($baslik); ?>"
                                        placeholder="Örn: Yünsa Dijital Pazarlama Projesi"
                                    >

                                    <?php if (isset($errors['baslik'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo temizle($errors['baslik']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Açıklama</label>
                                    <textarea 
                                        name="aciklama" 
                                        class="form-control <?php echo isset($errors['aciklama']) ? 'is-invalid' : ''; ?>"
                                        placeholder="Proje hakkında detaylı açıklama yaz..."
                                    ><?php echo temizle($aciklama); ?></textarea>

                                    <?php if (isset($errors['aciklama'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo temizle($errors['aciklama']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-end mt-2">
                                    <a href="proje" class="btn btn-outline-secondary me-1">Vazgeç</a>
                                    <button type="submit" name="submit" class="btn btn-outline-danger">Projeyi Yayınla</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-5">
                        <div class="card proje-add-card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title mb-0">Görsel Yükleme</h4>
                            </div>

                            <div class="card-body pt-2">

                                <div class="upload-box">
                                    <label class="form-label">Proje Görselleri</label>
                                    <input 
                                        type="file" 
                                        name="resim[]" 
                                        class="form-control" 
                                        accept=".jpg,.jpeg,.png,.webp" 
                                        multiple
                                    >

                                    <small class="text-muted d-block mt-1">
                                        JPG, PNG veya WEBP formatında görsel yükleyebilirsin. Maksimum önerilen boyut: 5 MB.
                                    </small>
                                </div>

                            </div>
                        </div>

                        <div class="card proje-add-card">
                            <div class="card-body">
                                <h5 class="mb-1">Proje Ekleme Notları</h5>

                                <ul class="proje-info-list">
                                    <li>Başlık kısa, net ve marka odaklı olmalı.</li>
                                    <li>Açıklama kısmında yapılan hizmetleri detaylandır.</li>
                                    <li>Kaliteli ve yatay/dikey uyumlu görseller kullan.</li>
                                    <li>SEO için işletme adı ve hizmet türünü metne ekle.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card proje-add-card">
                            <div class="card-body">
                                <h5 class="mb-1">Örnek Başlık</h5>
                                <p class="mb-0 text-muted">
                                    “Yünsa Sosyal Medya Yönetimi ve Dijital Strateji Projesi”
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>