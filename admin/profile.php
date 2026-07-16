<?php
declare(strict_types=1);
require_once 'config/db.php';
if(session_status()===PHP_SESSION_NONE){session_start();}
if(empty($_SESSION['admin_id'])){
    header('Location: ../404');
    exit;
}
$admin_id=(int)$_SESSION['admin_id'];
if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token']=bin2hex(random_bytes(32));
}
$error='';
$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(empty($_POST['csrf_token'])||!hash_equals($_SESSION['csrf_token'],$_POST['csrf_token'])){
        exit('Geçersiz güvenlik isteği.');
    }
    $name=trim($_POST['name']??'');
    $gender=trim($_POST['gender']??'');
    $birthday=trim($_POST['birthday']??'');
    if($name===''){
        $error='Ad soyad boş bırakılamaz.';
    }else{
        try{
            $stmt=$pdo->prepare("UPDATE admin SET name=:name, gender=:gender, birthday=:birthday WHERE id=:id LIMIT 1");
            $stmt->bindValue(':name',$name,PDO::PARAM_STR);
            $stmt->bindValue(':gender',$gender,PDO::PARAM_STR);
            $stmt->bindValue(':birthday',$birthday,PDO::PARAM_STR);
            $stmt->bindValue(':id',$admin_id,PDO::PARAM_INT);
            $stmt->execute();
            header('Location: profile?success=1');
            exit;
        }catch(PDOException $e){
            error_log('Profile update error: '.$e->getMessage());
            $error='Profil güncellenemedi: '.$e->getMessage();
        }
    }
}
try{
    $stmt=$pdo->prepare("SELECT id,name,email,positions,gender,birthday FROM admin WHERE id=:id LIMIT 1");
    $stmt->execute([':id'=>$admin_id]);
    $admin=$stmt->fetch(PDO::FETCH_ASSOC);
    if(!$admin){
        session_destroy();
        header('Location: ../404');
        exit;
    }
}catch(PDOException $e){
    error_log('Profile select error: '.$e->getMessage());
    exit('Sistemsel bir hata oluştu.');
}
if(isset($_GET['success'])){
    $success='Profil başarıyla güncellendi.';
}
include 'header.php';
?>
<div class="app-content content">
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Profil</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <?php if($success!==''): ?>
                <div class="alert alert-success" role="alert"><div class="alert-body"><?= htmlspecialchars($success,ENT_QUOTES,'UTF-8'); ?></div></div>
            <?php endif; ?>
            <?php if($error!==''): ?>
                <div class="alert alert-danger" role="alert"><div class="alert-body"><?= htmlspecialchars($error,ENT_QUOTES,'UTF-8'); ?></div></div>
            <?php endif; ?>
            <section class="app-user-view-account">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center flex-column text-center">
                                    <img class="img-fluid rounded-circle mt-2 mb-2" src="assets/images/2.png" height="120" width="120" alt="Admin Avatar">
                                    <h4 class="mb-0"><?= htmlspecialchars($admin['name']?:'Admin',ENT_QUOTES,'UTF-8'); ?></h4>
                                    <span class="badge bg-light-danger mt-1"><?= htmlspecialchars($admin['positions']?:'Yönetici',ENT_QUOTES,'UTF-8'); ?></span>
                                </div>
                                <hr>
                                <h4 class="fw-bolder mb-1">Hesap Bilgileri</h4>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-75"><span class="fw-bolder me-25">Ad Soyad:</span><span><?= htmlspecialchars($admin['name']?:'-',ENT_QUOTES,'UTF-8'); ?></span></li>
                                    <li class="mb-75"><span class="fw-bolder me-25">E-Posta:</span><span><?= htmlspecialchars($admin['email']?:'-',ENT_QUOTES,'UTF-8'); ?></span></li>
                                    <li class="mb-75"><span class="fw-bolder me-25">Yetki:</span><span><?= htmlspecialchars($admin['positions']?:'-',ENT_QUOTES,'UTF-8'); ?></span></li>
                                    <li class="mb-75"><span class="fw-bolder me-25">Cinsiyet:</span><span><?= htmlspecialchars($admin['gender']?:'-',ENT_QUOTES,'UTF-8'); ?></span></li>
                                    <li><span class="fw-bolder me-25">Doğum Tarihi:</span><span><?= htmlspecialchars($admin['birthday']?:'-',ENT_QUOTES,'UTF-8'); ?></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-7 col-md-7">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title mb-0">Profil Bilgilerini Düzenle</h4>
                            </div>
                            <div class="card-body pt-2">
                                <form method="post" autocomplete="off">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'],ENT_QUOTES,'UTF-8'); ?>">
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">Ad Soyad</label>
                                            <input type="text" name="name" class="form-control" required maxlength="100" value="<?= htmlspecialchars($admin['name']??'',ENT_QUOTES,'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">E-Posta</label>
                                            <input type="email" class="form-control" disabled value="<?= htmlspecialchars($admin['email']??'',ENT_QUOTES,'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">Yetki</label>
                                            <input type="text" class="form-control" disabled value="<?= htmlspecialchars($admin['positions']??'',ENT_QUOTES,'UTF-8'); ?>">
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">Cinsiyet</label>
                                            <select name="gender" class="form-select">
                                                <option value="">Cinsiyet Seçiniz</option>
                                                <option value="Erkek" <?= (($admin['gender']??'')==='Erkek')?'selected':''; ?>>Erkek</option>
                                                <option value="Kadın" <?= (($admin['gender']??'')==='Kadın')?'selected':''; ?>>Kadın</option>
                                                <option value="Diğer" <?= (($admin['gender']??'')==='Diğer')?'selected':''; ?>>Diğer</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label class="form-label">Doğum Tarihi</label>
                                            <input type="date" name="birthday" class="form-control" value="<?= htmlspecialchars($admin['birthday']??'',ENT_QUOTES,'UTF-8'); ?>">
                                        </div>
                                        <div class="col-12 mt-1">
                                            <button type="submit" class="btn btn-outline-danger me-1">Kaydet</button>
                                            <a href="index" class="btn btn-outline-secondary">Vazgeç</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-1">Güvenlik Notu</h5>
                                <p class="mb-0 text-muted">Profil bilgilerini güncel tutman admin panel güvenliği ve yetki takibi için önemlidir.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>