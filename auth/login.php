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
