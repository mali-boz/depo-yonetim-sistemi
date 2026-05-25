<?php
/**
 * header.php — Ortak sayfa başlığı ve navigasyon
 *
 * TODO:
 * - session_start() çağır (oturum zaten aktif değilse)
 * - config.php dosyasını dahil et (require_once)
 * - functions.php dosyasını dahil et (require_once)
 *
 * - <!DOCTYPE html> ve <html lang="tr"> çıktısı ver
 * - <head> bölümü:
 *   - <meta charset="UTF-8">
 *   - <meta name="viewport" content="width=device-width, initial-scale=1.0">
 *   - <title> etiketi: sayfa başlığı veya APP_NAME sabiti
 *   - Bootstrap 5 CSS CDN bağlantısı (link tag)
 *   - assets/css/style.css bağlantısı (link tag)
 *
 * - <body> aç
 * - Bootstrap navbar oluştur (navbar-expand-lg, navbar-dark, bg-dark):
 *   - Marka adı: APP_NAME sabiti, ana sayfaya link
 *   - Mobil hamburger menü butonu (navbar-toggler)
 *   - Navigasyon linkleri:
 *     - Anasayfa (dashboard.php)
 *     - Depolar (warehouses/index.php)
 *     - Sevkiyatlar (shipments/index.php)
 *     - Envanter (inventory/index.php)
 *   - Sağ taraf (navbar-nav ms-auto):
 *     - Oturum açıksa: kullanıcı adı göster + Çıkış linki (auth/logout.php)
 *     - Oturum kapalıysa: Giriş (auth/login.php) + Kayıt (auth/register.php)
 *
 * - <main class="container mt-4"> aç (footer.php'de kapatılacak)
 * - Flash mesajları varsa Bootstrap alert ile göster
 */
