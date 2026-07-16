<?php
declare(strict_types=1);

session_start();

function clean_input(string $data, int $max = 255): string {
    $data = trim($data);
    $data = strip_tags($data);
    $data = str_replace(["\r", "\n"], '', $data);
    return mb_substr($data, 0, $max, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: iletisim.php?status=error');
    exit;
}

if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    header('Location: iletisim.php?status=error');
    exit;
}

if (!empty($_POST['website'])) {
    header('Location: iletisim.php?status=success');
    exit;
}

$name    = clean_input($_POST['name'] ?? '', 100);
$phone   = clean_input($_POST['phone'] ?? '', 25);
$email   = clean_input($_POST['email'] ?? '', 150);
$subject = clean_input($_POST['subject'] ?? '', 150);
$message = trim(strip_tags($_POST['message'] ?? ''));
$message = mb_substr($message, 0, 2000, 'UTF-8');

if (
    $name === '' ||
    $phone === '' ||
    $email === '' ||
    $subject === '' ||
    $message === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    header('Location: iletisim.php?status=error');
    exit;
}

if (!preg_match('/^[0-9+\s().-]{7,25}$/', $phone)) {
    header('Location: iletisim.php?status=error');
    exit;
}

$to = 'info@nfcmedya.com.tr';

$mailSubject = '=?UTF-8?B?' . base64_encode('NFC Medya İletişim Formu: ' . $subject) . '?=';

$mailBody  = "Yeni bir iletişim formu mesajı geldi.\n\n";
$mailBody .= "İsim Soyisim: {$name}\n";
$mailBody .= "Telefon: {$phone}\n";
$mailBody .= "E-Posta: {$email}\n\n";
$mailBody .= "Konu: {$subject}\n\n";
$mailBody .= "Mesaj:\n{$message}\n";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .= "From: NFC Medya <no-reply@nfcmedya.com.tr>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

unset($_SESSION['csrf_token']);

if (mail($to, $mailSubject, $mailBody, $headers)) {
    header('Location: iletisim.php?status=success');
    exit;
}

header('Location: iletisim.php?status=error');
exit;