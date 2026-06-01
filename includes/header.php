<?php
/**
 * header.php — Ortak sayfa başlığı ve navigasyon
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/functions.php';

// BASE_URL belirleme (Klasör derinliğini URL üzerinden hesaplayalım)
// Projedeki alt klasörlerimiz bellidir:
$subFolders = ['auth', 'inventory', 'shipments', 'warehouses'];
$currentDir = basename(dirname($_SERVER['SCRIPT_NAME']));

if (in_array($currentDir, $subFolders)) {
    // Eğer bu alt klasörlerden birindeysek, bir üst dizine çıkmamız gerek
    $baseUrl = '../';
} else {
    // Kök dizindeysek (dashboard.php, index.php vb)
    $baseUrl = './';
}

$pageTitle = $pageTitle ?? 'Depo Yönetim Sistemi';
$isLoggedIn = Auth::isLoggedIn();
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Özel Stiller -->
    <link href="<?= $baseUrl ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Ortak Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= $baseUrl ?>dashboard.php">Depo Yönetim</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if ($isLoggedIn): ?>
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>dashboard.php">Anasayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>warehouses/index.php">Depolar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>shipments/index.php">Sevkiyatlar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>inventory/index.php">Envanter</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link text-light"><?= htmlspecialchars($userName) ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="<?= $baseUrl ?>auth/logout.php">Çıkış</a>
                </li>
            </ul>
            <?php else: ?>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>auth/login.php">Giriş Yap</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>auth/register.php">Kayıt Ol</a>
                </li>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Ana İçerik Konteyneri -->
<main class="container">
    <!-- Ortak Flash Mesaj Alanı -->
    <?php if ($successMsg = getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <?= htmlspecialchars($successMsg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg = getFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <?= htmlspecialchars($errorMsg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
        </div>
    <?php endif; ?>
