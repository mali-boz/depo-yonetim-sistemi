<?php
/**
 * dashboard.php — Kullanıcı kontrol paneli
 *
 * TODO:
 * - auth_guard.php dahil et (oturum kontrolü)
 * - header.php dahil et
 * - Database bağlantısı al
 *
 * - Toplam depo sayısını sorgula ve Bootstrap card ile göster
 * - Toplam sevkiyat sayısını sorgula ve Bootstrap card ile göster
 * - Toplam envanter kalemi sayısını sorgula ve Bootstrap card ile göster
 *
 * - Kullanıcı adıyla hoş geldiniz mesajı göster
 * - Her varlık için "Yeni Ekle" hızlı eylem butonları ekle
 *   (depolar, sevkiyatlar, envanter)
 *
 * - Bootstrap grid (row > col) ile kartları düzenle
 * - Flash mesajları varsa göster
 * - footer.php dahil et
 */

require_once __DIR__ . '/includes/auth_guard.php';
require_once __DIR__ . '/classes/Database.php';

$db = Database::getInstance()->getConnection();
$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

// Özet sayıları çek
$stmtWarehouses = $db->prepare("SELECT COUNT(*) FROM warehouses WHERE created_by = ?");
$stmtWarehouses->execute([$userId]);
$totalWarehouses = $stmtWarehouses->fetchColumn();

$stmtShipments = $db->prepare("SELECT COUNT(*) FROM shipments WHERE created_by = ?");
$stmtShipments->execute([$userId]);
$totalShipments = $stmtShipments->fetchColumn();

$stmtInventory = $db->prepare("SELECT COUNT(*) FROM inventory WHERE last_updated_by = ?");
$stmtInventory->execute([$userId]);
$totalInventory = $stmtInventory->fetchColumn();

$pageTitle = 'Dashboard — Depo Yönetim Sistemi';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row mb-4 align-items-center mt-3">
    <div class="col">
        <h2 class="mb-0">Hoş geldiniz, <?= htmlspecialchars($userName) ?>!</h2>
    </div>
</div>

<!-- Özet Kartları -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-primary h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <h5 class="card-title text-primary mb-3">Kayıtlı Depolar</h5>
                <p class="display-5 fw-bold text-dark mb-3"><?= $totalWarehouses ?></p>
                <a href="warehouses/create.php" class="btn btn-outline-primary btn-sm mt-auto mx-auto">+ Yeni Depo Ekle</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <h5 class="card-title text-success mb-3">Sevkiyatlar</h5>
                <p class="display-5 fw-bold text-dark mb-3"><?= $totalShipments ?></p>
                <a href="shipments/create.php" class="btn btn-outline-success btn-sm mt-auto mx-auto">+ Yeni Sevkiyat Oluştur</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info h-100">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <h5 class="card-title text-info mb-3">Envanter Kalemleri</h5>
                <p class="display-5 fw-bold text-dark mb-3"><?= $totalInventory ?></p>
                <a href="inventory/create.php" class="btn btn-outline-info btn-sm mt-auto mx-auto">+ Yeni Kalem Ekle</a>
            </div>
        </div>
    </div>
</div>

<!-- Hızlı Erişim -->
<div class="card shadow-sm border-0 bg-white">
    <div class="card-body p-4">
        <h5 class="card-title mb-3">Hızlı Erişim</h5>
        <div class="d-flex gap-2 flex-wrap">
            <a href="warehouses/index.php" class="btn btn-primary">Depoları Görüntüle</a>
            <a href="shipments/index.php" class="btn btn-success">Sevkiyatları Görüntüle</a>
            <a href="inventory/index.php" class="btn btn-info text-white">Envanteri Görüntüle</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
