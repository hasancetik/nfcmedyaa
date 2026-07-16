<?php
declare(strict_types=1);

require_once 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$msg = '';
$error = '';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../404");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function temizle(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function guvenliResim(?string $file): string {
    $file = basename((string)$file);
    return preg_match('/^[a-zA-Z0-9_\-.çÇğĞıİöÖşŞüÜ]+$/u', $file) ? $file : '';
}

if (isset($_POST['ilan_delete'])) {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Geçersiz güvenlik isteği.';
    } else {
        $ilan_id = filter_input(INPUT_POST, 'ilan_id', FILTER_VALIDATE_INT);

        if (!$ilan_id) {
            $error = 'Geçersiz proje ID.';
        } else {
            try {
                $stmt_select = $pdo->prepare("SELECT resim FROM proje WHERE id = ? LIMIT 1");
                $stmt_select->execute([$ilan_id]);
                $resim = $stmt_select->fetchColumn();

                if ($resim !== false) {
                    $stmt_delete = $pdo->prepare("DELETE FROM proje WHERE id = ? LIMIT 1");
                    $stmt_delete->execute([$ilan_id]);

                    if ($stmt_delete->rowCount() > 0) {
                        $resimDosya = guvenliResim((string)$resim);

                        if ($resimDosya !== '') {
                            $resim_sil_path = dirname(__DIR__) . '/uploads/proje/' . $resimDosya;

                            if (is_file($resim_sil_path)) {
                                @unlink($resim_sil_path);
                            }
                        }

                        header("Location: proje?deleted=1");
                        exit;
                    } else {
                        $error = 'Proje silinemedi. Lütfen tekrar deneyin.';
                    }
                } else {
                    $error = 'Proje bulunamadı.';
                }
            } catch (PDOException $e) {
                error_log('Proje silme hatası: ' . $e->getMessage());
                $error = 'Proje silinirken sistemsel bir hata oluştu.';
            }
        }
    }
}

if (isset($_GET['deleted'])) {
    $msg = 'Proje başarıyla silindi.';
}

$search = isset($_GET['search']) ? trim((string)$_GET['search']) : '';

try {
    if ($search !== '') {
        $query = "SELECT * FROM proje 
            WHERE id LIKE ? 
            OR baslik LIKE ? 
            OR aciklama LIKE ? 
            OR tarih LIKE ? 
            ORDER BY id DESC";

        $stmt = $pdo->prepare($query);
        $searchTerm = "%{$search}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM proje ORDER BY id DESC");
        $stmt->execute();
    }

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Proje listeleme hatası: ' . $e->getMessage());
    $result = [];
    $error = 'Projeler listelenirken hata oluştu.';
}

$toplamProje = count($result);
$vitrinProje = 0;

foreach ($result as $item) {
    if ((int)($item['vitrin'] ?? 0) === 1) {
        $vitrinProje++;
    }
}

$normalProje = max(0, $toplamProje - $vitrinProje);
?>

<?php include 'header.php'; ?> 

<style>
.proje-stat-card {
    border: 0;
    border-radius: 16px;
    box-shadow: 0 10px 28px rgba(31,45,85,0.08);
    transition: all .25s ease;
}
.proje-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 36px rgba(31,45,85,0.13);
}
.proje-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.proje-thumb {
    width: 58px;
    height: 58px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #eee;
}
.proje-page-card {
    border-radius: 18px;
    overflow: hidden;
}
.proje-search-input {
    min-width: 280px;
}
.option-list li a,
.option-list li button {
    border-radius: 10px;
}
</style>

<div class="app-content content ">
    <div class="content-wrapper container-xxl p-0">

        <div class="content-header row mb-2">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Proje</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index">Anasayfa</a></li>
                                <li class="breadcrumb-item active">Proje</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-header-right text-md-end col-md-3 col-12">
                <a href="proje-paylas" class="btn btn-outline-danger">
                    Yeni Proje Ekle
                </a>
            </div>
        </div>

        <div class="content-body">

            <div class="row">

                <div class="col-lg-3 col-sm-6">
                    <div class="card proje-stat-card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted">Toplam Proje</span>
                                <h3 class="fw-bolder mb-0 mt-50"><?php echo temizle((string)$toplamProje); ?></h3>
                            </div>
                            <div class="proje-stat-icon bg-light-primary text-primary">
                                <i class="fi fi-rr-edit"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card proje-stat-card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted">Vitrin Projesi</span>
                                <h3 class="fw-bolder mb-0 mt-50"><?php echo temizle((string)$vitrinProje); ?></h3>
                            </div>
                            <div class="proje-stat-icon bg-light-success text-success">
                                <i class="fi fi-rr-star"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card proje-stat-card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted">Normal Proje</span>
                                <h3 class="fw-bolder mb-0 mt-50"><?php echo temizle((string)$normalProje); ?></h3>
                            </div>
                            <div class="proje-stat-icon bg-light-secondary text-secondary">
                                <i class="fi fi-rr-layers"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card proje-stat-card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted">Arama Durumu</span>
                                <h3 class="fw-bolder mb-0 mt-50"><?php echo $search !== '' ? 'Aktif' : 'Pasif'; ?></h3>
                            </div>
                            <div class="proje-stat-icon bg-light-warning text-warning">
                                <i class="fi fi-rr-search"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <?php if ($msg): ?>
                <div class="alert alert-success"><?php echo temizle($msg); ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo temizle($error); ?></div>
            <?php endif; ?>

            <div class="row" id="basic-table">
                <div class="col-12">
                    <div class="card proje-page-card">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between border-bottom">
                            <div>
                                <h4 class="card-title mb-25">Proje Yönetimi</h4>
                                <small class="text-muted">
                                    Projeleri görüntüle, düzenle, vitrine al veya sil.
                                </small>
                            </div>

                            <form method="GET" class="d-flex align-items-center position-relative mt-1 mt-md-0">
                                <div class="position-relative me-2"> 
                                    <input type="text" id="searchInput" name="search" class="form-control proje-search-input" 
                                        placeholder="Proje ara..." 
                                        value="<?php echo temizle($search); ?>">
                                    <span id="clearSearch" class="position-absolute top-50 end-0 translate-middle-y me-1 text-muted" 
                                        style="cursor: pointer; font-size: 32px; display: <?php echo $search !== '' ? 'inline' : 'none'; ?>;">
                                        &times;
                                    </span>
                                </div>
                                <button type="submit" class="btn btn-primary">Ara</button>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Resim</th>
                                        <th>Başlık</th>
                                        <th>Açıklama</th>
                                        <th>Tarih</th>
                                        <th>Vitrin</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (!empty($result)): ?>
                                        <?php foreach ($result as $proje): ?>
                                            <?php
                                                $projeId = (int)($proje['id'] ?? 0);
                                                $resim = guvenliResim($proje['resim'] ?? '');
                                                $resimYolu = $resim !== '' ? '../uploads/proje/' . $resim : 'assets/images/logo.png';
                                                $aciklamaRaw = html_entity_decode((string)($proje['aciklama'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                                $aciklama = mb_substr(strip_tags($aciklamaRaw), 0, 80, 'UTF-8');
                                                $tarih = !empty($proje['tarih']) ? date("d-m-Y", strtotime((string)$proje['tarih'])) : '-';
                                                $vitrin = (int)($proje['vitrin'] ?? 0);
                                            ?>

                                            <tr>
                                                <td><?php echo temizle((string)$projeId); ?></td>

                                                <td>
                                                    <img src="<?php echo temizle($resimYolu); ?>" class="proje-thumb" alt="Proje görseli">
                                                </td>

                                                <td>
                                                    <strong><?php echo temizle($proje['baslik'] ?? ''); ?></strong>
                                                </td>

                                                <td>
                                                    <?php echo temizle($aciklama); ?><?php echo mb_strlen($aciklamaRaw, 'UTF-8') > 80 ? '...' : ''; ?>
                                                </td>

                                                <td><?php echo temizle($tarih); ?></td>

                                                <td>
                                                    <div class="d-flex align-items-center gap-50">
                                                        <input type="checkbox" class="vitrin-toggle" data-id="<?php echo $projeId; ?>" <?php echo $vitrin === 1 ? 'checked' : ''; ?>>

                                                        <?php if ($vitrin === 1): ?>
                                                            <span class="badge bg-light-success">Vitrinde</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-light-secondary">Normal</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="option-box">
                                                        <ul class="option-list">
                                                            <li>
                                                                <a href="../proje-detay.php?id=<?php echo $projeId; ?>" data-text="Görüntüle" target="_blank" rel="noopener noreferrer">
                                                                    <i class="fi fi-rr-eye"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="proje-duzenle.php?id=<?php echo $projeId; ?>" data-text="Düzenle">
                                                                    <i class="fi fi-rr-pencil"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <button type="button" data-text="Sil" data-bs-toggle="modal" data-bs-target="#ilanDelete" data-id="<?php echo $projeId; ?>">
                                                                    <i class="fi fi-rr-trash"></i>
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center p-3">Proje bulunamadı.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="ilanDelete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Proje Silinecek</h1>
                    <p>Proje silmek istiyor musunuz?</p>
                </div>

                <form method="post" class="row gy-1 pt-75 d-flex justify-content-center">
                    <input type="hidden" name="csrf_token" value="<?php echo temizle($_SESSION['csrf_token']); ?>">
                    <input type="hidden" id="deleteId" name="ilan_id" value="">
                    <button class="col-md-4 btn btn-lg btn-outline-danger" name="ilan_delete" type="submit">Evet</button>
                    <button class="col-md-4 ml-10 btn btn-lg btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">Hayır</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const clearSearch = document.getElementById("clearSearch");

    if (searchInput && clearSearch) {
        const searchForm = searchInput.closest("form");

        clearSearch.addEventListener("click", function() {
            searchInput.value = "";
            clearSearch.style.display = "none";
            searchForm.submit();
        });

        searchInput.addEventListener("input", function() {
            clearSearch.style.display = searchInput.value ? "inline" : "none";
        });
    }

    const deleteModal = document.getElementById("ilanDelete");

    if (deleteModal) {
        deleteModal.addEventListener("show.bs.modal", function(event) {
            const button = event.relatedTarget;
            const projeId = button ? button.getAttribute("data-id") : "";
            const deleteInput = deleteModal.querySelector("#deleteId");

            if (deleteInput) {
                deleteInput.value = projeId;
            }
        });
    }

    document.querySelectorAll(".vitrin-toggle").forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            const projeId = this.getAttribute("data-id");
            const newValue = this.checked ? 1 : 0;
            const currentCheckbox = this;

            fetch("proje_vitrin_guncelle.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body:
                    "id=" + encodeURIComponent(projeId) +
                    "&vitrin=" + encodeURIComponent(newValue) +
                    "&csrf_token=" + encodeURIComponent("<?php echo temizle($_SESSION['csrf_token']); ?>")
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    currentCheckbox.checked = !currentCheckbox.checked;
                    alert("Bir hata oluştu: " + data.message);
                }
            })
            .catch(() => {
                currentCheckbox.checked = !currentCheckbox.checked;
                alert("Sunucuya bağlanılamadı. Lütfen tekrar deneyin.");
            });
        });
    });
});
</script>