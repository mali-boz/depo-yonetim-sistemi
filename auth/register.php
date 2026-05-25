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
