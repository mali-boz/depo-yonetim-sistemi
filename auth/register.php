<?php
/**
 * register.php — Kullanıcı kayıt sayfası
 *
 * TODO:
 * - session_start() çağır
 * - functions.php, User.php, Auth.php dahil et
 * - Zaten oturum açıksa dashboard.php'ye yönlendir
 *
 * - POST isteği kontrolü ($_SERVER['REQUEST_METHOD'] === 'POST'):
 *   - CSRF token doğrula (validateCsrfToken)
 *   - Tüm form alanlarını sanitize() ile temizle: name, email, password, confirm_password
 *   - Doğrulama kuralları:
 *     - Ad boş olmamalı
 *     - Email geçerli formatta olmalı (filter_var FILTER_VALIDATE_EMAIL)
 *     - Şifre en az 6 karakter olmalı
 *     - Şifre ve şifre tekrarı eşleşmeli
 *     - Email daha önce kullanılmamış olmalı (User::emailExists)
 *   - Hata yoksa User::register() çağır
 *   - Başarılıysa flash('success', 'Kayıt başarılı! Giriş yapabilirsiniz.'), login.php'ye yönlendir
 *   - Hata varsa hataları dizide topla, formda göster
 *
 * - GET isteği (veya POST hataları sonrası):
 *   - header.php dahil et
 *   - Bootstrap card içinde kayıt formu göster:
 *     - Ad (text input, form-control)
 *     - E-posta (email input, form-control)
 *     - Şifre (password input, form-control)
 *     - Şifre Tekrarı (password input, form-control)
 *     - CSRF token (hidden input — generateCsrfToken())
 *     - Kayıt Ol butonu (btn btn-primary)
 *   - Hata mesajlarını Bootstrap alert-danger ile göster
 *   - "Zaten hesabınız var mı? Giriş Yap" linki
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
$success = '';

// Initialize form variables
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Tüm alanları doldurunuz.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Geçerli bir e-posta adresi giriniz.';
    } elseif (strlen($password) < 6) {
        $error = 'Şifre en az 6 karakter olmalıdır.';
    } elseif ($password !== $confirm) {
        $error = 'Şifreler eşleşmiyor.';
    } else {
        $user = new User();
        if ($user->emailExists($email)) {
            $error = 'Bu e-posta adresi zaten kayıtlı.';
        } else {
            $user->create($name, $email, $password);
            $success = 'Kayıt başarılı. Giriş yapabilirsiniz.';
        }
    }
}

$pageTitle = 'Kayıt Ol';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h4 class="card-title mb-4 text-center">Kayıt Ol</h4>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Ad Soyad</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-posta</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Şifre</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Şifre Tekrar</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-2">Kayıt Ol</button>
                </form>
                <p class="text-center mt-4 mb-0">
                    Hesabın var mı? <a href="login.php" class="text-decoration-none">Giriş yap</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>