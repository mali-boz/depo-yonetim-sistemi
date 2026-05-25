<?php
/**
 * auth_guard.php — Oturum koruma dosyası
 *
 * TODO:
 * - Oturum zaten aktif değilse session_start() çağır
 *   (session_status() === PHP_SESSION_NONE kontrolü yap)
 *
 * - functions.php dahil et (require_once) — flash() ve redirect() için
 *
 * - $_SESSION['user_id'] isset kontrolü yap
 * - Eğer user_id yoksa:
 *   - flash('error', 'Bu sayfayı görüntülemek için giriş yapmalısınız.') çağır
 *   - auth/login.php sayfasına yönlendir (redirect)
 *   - exit() ile betiği sonlandır
 *
 * NOT: Bu dosya her korumalı sayfanın en üstünde require edilmelidir.
 * Örnek kullanım: require_once __DIR__ . '/../includes/auth_guard.php';
 */
