<?php
declare(strict_types=1);

require_once 'config/db.php';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: index");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        exit('Geçersiz güvenlik isteği.');
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Geçerli bir e-posta adresi giriniz.';
    } else {

        $sql = "SELECT id, email, password FROM admin WHERE email = :email LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':email' => $email
        ]);

        $row = $stmt->fetch();

        if ($row && password_verify($password, $row['password'])) {

            session_regenerate_id(true);

            $_SESSION['admin_id'] = (int)$row['id'];
            $_SESSION['admin_mail'] = $row['email'];

            header("Location: index");
            exit;

        } else {

            $error_message = 'E-posta veya şifre hatalı.';

        }

    }

}
?>

<!DOCTYPE html>
<html lang="tr" class="loaded">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NFC Medya - Admin Panel</title>

    <meta name="robots" content="noindex, nofollow">

    <link rel="shortcut icon" href="assets/images/logo.png">

    <link rel="stylesheet" href="assets/dist/css/vendors.min.css">
    <link rel="stylesheet" href="assets/dist/css/login.css">
    <link rel="stylesheet" href="assets/dist/css/bootstrap-extended.css">
    <link rel="stylesheet" href="assets/dist/css/artikyeter.css">
    <link rel="stylesheet" href="assets/dist/css/components.css">
    <link rel="stylesheet" href="assets/dist/css/dark-layout.css">
    <link rel="stylesheet" href="assets/dist/css/bordered-layout.css">
    <link rel="stylesheet" href="assets/dist/css/semi-dark-layout.css">
    <link rel="stylesheet" href="assets/dist/css/vertical-menu.css">
    <link rel="stylesheet" href="assets/dist/css/form-validation.css">
    <link rel="stylesheet" href="assets/dist/css/authentication.css">
    <link rel="stylesheet" href="assets/dist/css/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/dist/css/ext-component-sweet-alerts.css">

</head>

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static">

<div class="app-content content">

    <div class="content-wrapper">

        <div class="content-body">

            <div class="auth-wrapper auth-basic px-2">

                <div class="auth-inner my-2">

                    <div class="card mb-0">

                        <div class="card-body">

                            <a href="https://www.nfcmedya.com.tr" class="brand-logo">

                                <img src="assets/images/logo.png" width="66" alt="NFC Medya Logo">

                            </a>

                            <p class="card-text mb-2 text-center">
                                NFC Medya - Admin Panel
                            </p>

                            <?php if (!empty($error_message)): ?>

                                <div class="alert alert-danger" role="alert">
                                    <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
                                </div>

                            <?php endif; ?>

                            <form method="POST" autocomplete="off">

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>"
                                >

                                <div class="mb-1">

                                    <label for="login-email" class="form-label">
                                        E-Posta
                                    </label>

                                    <input
                                        type="email"
                                        class="form-control"
                                        id="login-email"
                                        name="email"
                                        placeholder="E-posta adresiniz"
                                        required
                                        autocomplete="username"
                                        value="<?= isset($email) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    />

                                </div>

                                <div class="mb-1">

                                    <label class="form-label" for="login-password">
                                        Parola
                                    </label>

                                    <div class="input-group input-group-merge form-password-toggle">

                                        <input
                                            type="password"
                                            class="form-control form-control-merge"
                                            id="login-password"
                                            name="password"
                                            placeholder="••••••••"
                                            required
                                            autocomplete="current-password"
                                        />

                                    </div>

                                </div>

                                <button
                                    class="btn btn-outline-danger w-100"
                                    type="submit"
                                >
                                    Giriş Yap
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="assets/dist/js/jquery-3.7.1.js"></script>
<script src="assets/dist/js/vendors.min.js"></script>
<script src="assets/dist/js/jquery.validate.min.js"></script>
<script src="assets/dist/js/sweetalert2.all.min.js"></script>
<script src="assets/dist/js/app-menu.js"></script>
<script src="assets/dist/js/app.js"></script>
<script src="assets/dist/js/auth-login.js"></script>

</body>
</html>