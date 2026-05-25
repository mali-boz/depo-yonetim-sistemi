<?php
/**
 * index.php — Ana giriş noktası / Yönlendirme
 */
require_once __DIR__ . '/classes/Auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (Auth::isLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: auth/login.php');
}
exit;