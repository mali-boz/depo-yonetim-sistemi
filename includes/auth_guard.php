<?php
/**
 * auth_guard.php — Oturum koruma dosyası
 *
 * NOT: Bu dosya her korumalı sayfanın en üstünde require edilmelidir.
 * Örnek kullanım: require_once __DIR__ . '/../includes/auth_guard.php';
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';

if (!isset($_SESSION['user_id'])) {
    setFlash('error', 'Bu sayfayı görüntülemek için giriş yapmalısınız.');

    // Projenin kök dizinine göre login yolunu hesapla
    $loginUrl = dirname($_SERVER['SCRIPT_NAME']) . '/../auth/login.php';
    $loginUrl = preg_replace('#/+#', '/', $loginUrl);

    header('Location: ' . $loginUrl);
    exit;
}
