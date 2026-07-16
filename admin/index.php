<?php
declare(strict_types=1);

require_once 'config/db.php';

$admin_toplam = 0;
$proje_toplam = 0;
$vitrin_toplam = 0;
$click_toplam = 0;
$son_projeler = [];

try {
    $stmt = $pdo->query("
        SELECT
            (SELECT COUNT(*) FROM admin) AS admin_toplam,
            (SELECT COUNT(*) FROM proje) AS proje_toplam,
            (SELECT COUNT(*) FROM proje WHERE vitrin = 1) AS vitrin_toplam,
            (SELECT COUNT(*) FROM clicks) AS click_toplam
    ");

    $counts = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($counts) {
        $admin_toplam = (int)($counts['admin_toplam'] ?? 0);
        $proje_toplam = (int)($counts['proje_toplam'] ?? 0);
        $vitrin_toplam = (int)($counts['vitrin_toplam'] ?? 0);
        $click_toplam = (int)($counts['click_toplam'] ?? 0);
    }

    $stmt = $pdo->query("
        SELECT id, baslik, vitrin
        FROM proje
        ORDER BY id DESC
        LIMIT 6
    ");

    $son_projeler = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log('Admin index sorgu hatası: ' . $e->getMessage());
}

include 'header.php';
?>

<style>
.dashboard-hero {
    background: linear-gradient(135deg, #1f2d55 0%, #31447a 100%);
    border-radius: 18px;
    padding: 34px;
    color: #fff;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}

.dashboard-hero::after {
    content: "";
    position: absolute;
    right: -80px;
    top: -80px;
    width: 220px;
    height: 220px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}

.dashboard-card {
    border: 0;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(31,45,85,0.08);
    transition: all .25s ease;
}

.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 40px rgba(31,45,85,0.13);
}

.stat-icon {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.quick-btn {
    border-radius: 14px;
    padding: 14px 16px;
    font-weight: 600;
}

.table td,
.table th {
    vertical-align: middle;
}
</style>

<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">

        <div class="content-header row mb-2">
            <div class="content-header-left col-12">
                <h2 class="content-header-title float-start mb-0">Anasayfa</h2>
            </div>
        </div>

        <div class="content-body">

            <div class="dashboard-hero">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="text-white mb-1">NFC Medya Yönetim Paneli</h2>
                        <p class="mb-0 text-white-50">
                            Projelerini, vitrin içeriklerini ve site etkileşimlerini buradan kolayca takip edebilirsin.
                        </p>
                    </div>

                    <div class="col-lg-4 text-lg-end mt-2 mt-lg-0">
                        <a href="proje-paylas" class="btn btn-light">
                            Yeni Proje Ekle
                        </a>
                    </div>
                </div>
            </div>

            <section>
                <div class="row">

                    <div class="col-xl-3 col-md-6 mb-2">
                        <div class="card dashboard-card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted">Yetkili Sayısı</span>
                                    <h2 class="fw-bolder mb-0 mt-50">
                                        <?= htmlspecialchars((string)$admin_toplam, ENT_QUOTES, 'UTF-8'); ?>
                                    </h2>
                                </div>

                                <div class="stat-icon bg-light-danger text-danger">
                                    <i class="fi fi-rr-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-2">
                        <div class="card dashboard-card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted">Toplam Proje</span>
                                    <h2 class="fw-bolder mb-0 mt-50">
                                        <?= htmlspecialchars((string)$proje_toplam, ENT_QUOTES, 'UTF-8'); ?>
                                    </h2>
                                </div>

                                <div class="stat-icon bg-light-primary text-primary">
                                    <i class="fi fi-rr-edit"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-2">
                        <div class="card dashboard-card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted">Vitrin Projesi</span>
                                    <h2 class="fw-bolder mb-0 mt-50">
                                        <?= htmlspecialchars((string)$vitrin_toplam, ENT_QUOTES, 'UTF-8'); ?>
                                    </h2>
                                </div>

                                <div class="stat-icon bg-light-success text-success">
                                    <i class="fi fi-rr-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-2">
                        <div class="card dashboard-card">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted">Toplam Tıklama</span>
                                    <h2 class="fw-bolder mb-0 mt-50">
                                        <?= htmlspecialchars((string)$click_toplam, ENT_QUOTES, 'UTF-8'); ?>
                                    </h2>
                                </div>

                                <div class="stat-icon bg-light-warning text-warning">
                                    <i class="fi fi-rr-chart-histogram"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <div class="row">

                <div class="col-xl-8 col-lg-7 mb-2">
                    <div class="card dashboard-card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-25">Son Eklenen Projeler</h4>
                                <small class="text-muted">En son eklenen proje kayıtları</small>
                            </div>

                            <a href="proje" class="btn btn-sm btn-outline-danger">
                                Tümünü Gör
                            </a>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Proje Başlığı</th>
                                            <th>Durum</th>
                                            <th class="text-end">İşlem</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if (!empty($son_projeler)): ?>
                                            <?php foreach ($son_projeler as $proje): ?>
                                                <tr>
                                                    <td>
                                                        <strong>
                                                            <?= htmlspecialchars($proje['baslik'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                                                        </strong>
                                                    </td>

                                                    <td>
                                                        <?php if ((int)($proje['vitrin'] ?? 0) === 1): ?>
                                                            <span class="badge bg-light-success">Vitrinde</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-light-secondary">Normal</span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td class="text-end">
                                                        <a href="proje-duzenle?id=<?= (int)$proje['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                            Düzenle
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center p-3 text-muted">
                                                    Henüz proje bulunmuyor.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5 mb-2">
                    <div class="card dashboard-card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title mb-0">Hızlı İşlemler</h4>
                        </div>

                        <div class="card-body">
                            <a href="proje-paylas" class="btn btn-outline-danger w-100 quick-btn mb-1">
                                Yeni Proje Ekle
                            </a>

                            <a href="proje" class="btn btn-outline-primary w-100 quick-btn mb-1">
                                Projeleri Yönet
                            </a>

                            <a href="../heatmap_pages" class="btn btn-outline-warning w-100 quick-btn mb-1">
                                Isı Haritasını Aç
                            </a>

                            <a href="profile" class="btn btn-outline-secondary w-100 quick-btn">
                                Profilimi Düzenle
                            </a>
                        </div>
                    </div>

                    <div class="card dashboard-card">
                        <div class="card-body">
                            <h5 class="mb-1">SEO Hatırlatma</h5>
                            <p class="text-muted mb-0">
                                Yeni proje eklerken başlık, açıklama ve kaliteli görsel kullanman Google görünürlüğüne katkı sağlar.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<?php include 'footer.php'; ?>