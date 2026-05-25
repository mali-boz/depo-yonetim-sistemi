<?php
/**
 * logout.php — Oturum kapatma
 */

require_once '../classes/Auth.php';
require_once '../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Oturum açık değilse zaten login'e yönlendir
if (!Auth::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

Auth::logout();

// Logout session'ı yok ettiği için flash mesaj için yeni session başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
setFlash('success', 'Başarıyla çıkış yapıldı.');

header('Location: login.php');
exit;
