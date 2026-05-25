<?php
/**
 * index.php — Ana giriş noktası / Yönlendirme
 *
 * TODO:
 * - session_start() çağır
 * - Kullanıcı oturum açmışsa dashboard.php'ye yönlendir
 * - Oturum açmamışsa auth/login.php'ye yönlendir
 */
require_once 'classes/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    echo "Veritabanı bağlantısı başarılı.";
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}