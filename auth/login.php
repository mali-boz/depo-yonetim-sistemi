<?php
/**
 * login.php — Kullanıcı giriş sayfası
 *
 * TODO:
 * - session_start() çağır
 * - functions.php, Auth.php dahil et
 * - Zaten oturum açıksa dashboard.php'ye yönlendir
 *
 * - POST isteği kontrolü:
 *   - CSRF token doğrula
 *   - email ve password alanlarını sanitize et
 *   - Doğrulama: email ve şifre boş olmamalı
 *   - Auth::login($email, $password) çağır
 *   - Başarılıysa flash('success', 'Hoş geldiniz!'), dashboard.php'ye yönlendir
 *   - Başarısızsa flash('error', 'E-posta veya şifre hatalı.'), formu tekrar göster
 *
 * - GET isteği (veya POST hataları sonrası):
 *   - header.php dahil et
 *   - Bootstrap card içinde giriş formu göster:
 *     - E-posta (email input, form-control)
 *     - Şifre (password input, form-control)
 *     - CSRF token (hidden input)
 *     - Giriş Yap butonu (btn btn-primary)
 *   - Flash mesajları göster (başarı ve hata)
 *   - "Hesabınız yok mu? Kayıt Ol" linki
 *   - footer.php dahil et
 */

require_once '../classes/Auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (Auth::isLoggedIn()) {
    header('Location: ../dashboard.php');
    exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        $error = 'E-posta ve şifre alanlarını doldurunuz.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Geçerli bir e-posta adresi giriniz.';
    } else {
        if (Auth::login($email, $password)) {
            header('Location: ../dashboard.php');
            exit;
        } else {
            $error = 'E-posta veya şifre hatalı.';
        }
    }
}

$pageTitle = 'Giriş Yap';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h4 class="card-title mb-4 text-center">Giriş Yap</h4>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">E-posta</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Şifre</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-2">Giriş Yap</button>
                </form>
                <p class="text-center mt-4 mb-0">
                    Hesabın yok mu? <a href="register.php" class="text-decoration-none">Kayıt ol</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
