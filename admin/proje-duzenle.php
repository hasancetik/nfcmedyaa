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

function temizle(?string $veri): string {
    return htmlspecialchars((string)$veri, ENT_QUOTES, 'UTF-8');
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    exit('Geçersiz proje ID.');
}

try {
    $stmt = $pdo->prepare("SELECT * FROM proje WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $proje = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$proje) {
        exit('Proje bulunamadı.');
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('Sistemsel hata.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        exit('Geçersiz güvenlik isteği.');
    }

    $baslik = trim($_POST['baslik'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');

    if ($baslik === '') {
        $errors['baslik'] = 'Başlık boş bırakılamaz.';
    }

    if ($aciklama === '') {
        $errors['aciklama'] = 'Açıklama boş bırakılamaz.';
    }

    $resim_listesi = $proje['resim'];

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
        $resimler = [];

        foreach ($_FILES['resim']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['resim']['error'][$key] !== UPLOAD_ERR_OK) {
                continue;
            }

            $mime = mime_content_type($tmp_name);

            if (!isset($allowedMime[$mime])) {
                continue;
            }

            $extension = $allowedMime[$mime];
            $yeniAd = uniqid('proje_', true) . '.' . $extension;
            $hedef = $upload_dir . $yeniAd;

            if (move_uploaded_file($tmp_name, $hedef)) {
                $resimler[] = $yeniAd;
            }
        }

        if (!empty($resimler)) {
            $resim_listesi = implode(',', $resimler);
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE proje 
                SET baslik = :baslik, aciklama = :aciklama, resim = :resim
                WHERE id = :id
            ");

            $stmt->execute([
                ':baslik' => $baslik,
                ':aciklama' => $aciklama,
                ':resim' => $resim_listesi,
                ':id' => $id
            ]);

            header("Location: proje");
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errors['db'] = 'Veritabanı güncelleme hatası.';
        }
    }
}

include 'header.php';
?>

<style>
.proje-edit-hero {
    background: linear-gradient(135deg, #1f2d55 0%, #31447a 100%);
    color: #fff;
    border-radius: 18px;
    padding: 30px;
    margin-bottom: 24px;
    box-shadow: 0 14px 35px rgba(31,45,85,.16);
}

.proje-edit-card {
    border: 0;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(31,45,85,.08);
}

.proje-preview {
    width: 100%;
    max-height: 280px;
    object-fit: cover;
    border-radius: 16px;
    border: 1px solid #eee;
    margin-bottom: 14px;
}

.proje-mini-preview {
    width: 78px;
    height: 78px;
    object-fit: cover;
    border-radius: 12px;
    border: 1px solid #eee;
    margin-right: 8px;
    margin-bottom: 8px;
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
</style>

<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">

        <div class="content-header row mb-2">
            <div class="content-header-left col-md-9 col-12">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Proje Düzenle</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index">Anasayfa</a></li>
                                <li class="breadcrumb-item"><a href="proje">Projeler</a></li>
                                <li class="breadcrumb-item active">Düzenle</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">

            <div class="proje-edit-hero">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="text-white mb-1">
                            <?php echo temizle($proje['baslik'] ?? 'Proje Düzenle'); ?>
                        </h2>
                        <p class="mb-0 text-white-50">
                            Proje bilgilerini, açıklamasını ve görsellerini bu ekrandan güncelleyebilirsin.
                        </p>
                    </div>

                    <div class="col-lg-4 text-lg-end mt-2 mt-lg-0">
                        <a href="../proje-detay.php?id=<?php echo (int)$proje['id']; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-light">
                            Sitede Görüntüle
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
                        <div class="card proje-edit-card">
                            <div class="card-header border-bottom">
                                <div>
                                    <h4 class="card-title mb-25">Proje İçeriği</h4>
                                    <small class="text-muted">Başlık ve açıklama alanlarını güncelle.</small>
                                </div>
                            </div>

                            <div class="card-body pt-2">

                                <div class="mb-2">
                                    <label class="form-label">Başlık</label>
                                    <input 
                                        type="text" 
                                        name="baslik" 
                                        class="form-control <?php echo isset($errors['baslik']) ? 'is-invalid' : ''; ?>" 
                                        value="<?php echo temizle($proje['baslik']); ?>"
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
                                    ><?php echo temizle($proje['aciklama']); ?></textarea>

                                    <?php if (isset($errors['aciklama'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo temizle($errors['aciklama']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-end mt-2">
                                    <a href="proje" class="btn btn-outline-secondary me-1">Vazgeç</a>
                                    <button type="submit" name="submit" class="btn btn-outline-danger">Güncelle</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-5">
                        <div class="card proje-edit-card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title mb-0">Görsel Yönetimi</h4>
                            </div>

                            <div class="card-body pt-2">

                                <label class="form-label">Mevcut Görsel</label>

                                <?php
                                $ilkResim = '';
                                if (!empty($proje['resim'])) {
                                    $resimler = explode(',', $proje['resim']);
                                    $ilkResim = basename(trim($resimler[0]));
                                }
                                ?>

                                <?php if ($ilkResim !== ''): ?>
                                    <img src="../uploads/proje/<?php echo temizle($ilkResim); ?>" class="proje-preview" alt="Proje görseli">
                                <?php else: ?>
                                    <div class="alert alert-warning">Bu projeye ait görsel bulunmuyor.</div>
                                <?php endif; ?>

                                <?php if (!empty($proje['resim'])): ?>
                                    <div class="d-flex flex-wrap mb-2">
                                        <?php foreach (explode(',', $proje['resim']) as $resim): ?>
                                            <?php $resim = basename(trim($resim)); ?>
                                            <?php if ($resim !== ''): ?>
                                                <img src="../uploads/proje/<?php echo temizle($resim); ?>" class="proje-mini-preview" alt="">
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="upload-box">
                                    <label class="form-label">Yeni Görsel Yükle</label>
                                    <input 
                                        type="file" 
                                        name="resim[]" 
                                        class="form-control" 
                                        accept=".jpg,.jpeg,.png,.webp" 
                                        multiple
                                    >

                                    <small class="text-muted d-block mt-1">
                                        Yeni görsel yüklersen mevcut görsel bilgisi yeni görsellerle güncellenir.
                                    </small>
                                </div>

                            </div>
                        </div>

                        <div class="card proje-edit-card">
                            <div class="card-body">
                                <h5 class="mb-1">Proje Bilgisi</h5>
                                <p class="mb-50">
                                    <strong>ID:</strong> <?php echo (int)$proje['id']; ?>
                                </p>
                                <p class="mb-0">
                                    <strong>Tarih:</strong> 
                                    <?php echo !empty($proje['tarih']) ? temizle(date('d.m.Y', strtotime((string)$proje['tarih']))) : '-'; ?>
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