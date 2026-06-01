<?php
/**
 * shipments/edit.php — Sevkiyat düzenleme (UPDATE)
 * Gelen/Giden yön, envanter bağlama ve teslim durumunda stok güncelleme destekli
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Shipment.php';
require_once __DIR__ . '/../classes/Warehouse.php';
require_once __DIR__ . '/../classes/Inventory.php';
require_once __DIR__ . '/../includes/functions.php';

$userId = $_SESSION['user_id'];
$shipmentModel = new Shipment();

// ID kontrolü
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$existing = $shipmentModel->getById($id, $userId);

if (!$existing) {
    setFlash('error', 'Sevkiyat bulunamadı veya erişim yetkiniz yok.');
    header('Location: index.php');
    exit;
}

$warehouseModel = new Warehouse();
$userWarehouses = $warehouseModel->getAll($userId);

$inventoryModel = new Inventory();
$allInventory = $inventoryModel->getAll($userId);

$errors = [];
$warnings = [];
$tracking_no  = $existing['tracking_no'];
$direction    = $existing['direction'] ?? 'giden';
$origin       = $existing['origin'];
$destination  = $existing['destination'];
$weight_kg    = $existing['weight_kg'];
$status       = $existing['status'];
$warehouse_id = $existing['warehouse_id'];
$inventory_id = $existing['inventory_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tracking_no  = trim($_POST['tracking_no'] ?? '');
    $direction    = trim($_POST['direction'] ?? '');
    $origin       = trim($_POST['origin'] ?? '');
    $destination  = trim($_POST['destination'] ?? '');
    $weight_kg    = trim($_POST['weight_kg'] ?? '');
    $status       = trim($_POST['status'] ?? '');
    $warehouse_id = intval($_POST['warehouse_id'] ?? 0);
    $inventory_id = intval($_POST['inventory_id'] ?? 0);

    // Doğrulama
    if (empty($tracking_no)) $errors[] = 'Takip numarası boş olamaz.';
    if (!in_array($direction, Shipment::VALID_DIRECTIONS)) $errors[] = 'Geçersiz yön seçimi.';
    if (empty($origin))      $errors[] = 'Çıkış yeri boş olamaz.';
    if (empty($destination)) $errors[] = 'Varış yeri boş olamaz.';
    if (!is_numeric($weight_kg) || $weight_kg <= 0) $errors[] = 'Ağırlık sıfırdan büyük olmalıdır.';
    if (!in_array($status, Shipment::VALID_STATUSES)) $errors[] = 'Geçersiz durum.';
    
    // Depo kullanıcının mı kontrolü
    $validWarehouse = false;
    foreach ($userWarehouses as $w) {
        if ($w['id'] === $warehouse_id) {
            $validWarehouse = true;
            break;
        }
    }
    if (!$validWarehouse) $errors[] = 'Geçersiz depo seçimi.';

    // Giden sevkiyat + teslim edildi durumuna geçişte stok kontrolü
    if (empty($errors) && $direction === 'giden' && $status === 'teslim edildi' && $existing['status'] !== 'teslim edildi' && $inventory_id > 0) {
        $currentStock = $shipmentModel->getInventoryStock($inventory_id);
        if ($currentStock < floatval($weight_kg)) {
            $warnings[] = "Uyarı: Seçilen envanter kaleminin mevcut stoğu ({$currentStock}) yetersiz! İstenen miktar: {$weight_kg}";
        }
    }

    $forceUpdate = isset($_POST['force_update']) && $_POST['force_update'] === '1';

    if (empty($errors) && (empty($warnings) || $forceUpdate)) {
        $result = $shipmentModel->update($id, [
            'tracking_no'  => $tracking_no,
            'direction'    => $direction,
            'origin'       => $origin,
            'destination'  => $destination,
            'weight_kg'    => $weight_kg,
            'status'       => $status,
            'warehouse_id' => $warehouse_id,
            'inventory_id' => $inventory_id ?: null
        ], $userId);

        if ($result) {
            setFlash('success', 'Sevkiyat başarıyla güncellendi.');
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Sevkiyat güncellenirken bir hata oluştu.';
        }
    }
}

$pageTitle = 'Sevkiyat Düzenle';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="card-title mb-4">Sevkiyat Düzenle</h4>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($warnings) && empty($errors)): ?>
                    <div class="alert alert-warning">
                        <strong>⚠️ Stok Uyarısı:</strong>
                        <ul class="mb-2">
                            <?php foreach ($warnings as $w): ?>
                                <li><?= htmlspecialchars($w) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="mb-0">Yine de güncellemek istiyor musunuz?</p>
                    </div>
                <?php endif; ?>

                <?php if ($existing['status'] === 'teslim edildi'): ?>
                    <div class="alert alert-info">
                        <strong>ℹ️ Bilgi:</strong> Bu sevkiyat teslim edilmiş. Envanter stoğu bu sevkiyata göre güncellenmiştir.
                    </div>
                <?php endif; ?>

                <form method="POST" class="needs-validation" novalidate>
                    <?php if (!empty($warnings) && empty($errors)): ?>
                        <input type="hidden" name="force_update" value="1">
                    <?php endif; ?>

                    <!-- Yön Seçimi -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sevkiyat Yönü</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="direction" id="dirGiden" value="giden" <?= $direction === 'giden' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="dirGiden">📤 Giden Sevkiyat</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="direction" id="dirGelen" value="gelen" <?= $direction === 'gelen' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="dirGelen">📥 Gelen Sevkiyat</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Takip Numarası</label>
                            <input type="text" name="tracking_no" class="form-control" value="<?= htmlspecialchars($tracking_no) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bağlı Depo</label>
                            <select name="warehouse_id" class="form-select" required>
                                <?php foreach ($userWarehouses as $w): ?>
                                    <option value="<?= $w['id'] ?>" <?= $warehouse_id == $w['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($w['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Çıkış Yeri</label>
                            <input type="text" name="origin" class="form-control" value="<?= htmlspecialchars($origin) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Varış Yeri</label>
                            <input type="text" name="destination" class="form-control" value="<?= htmlspecialchars($destination) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ağırlık (kg)</label>
                            <input type="number" name="weight_kg" class="form-control" step="0.01" min="0.01" value="<?= htmlspecialchars($weight_kg) ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Durum</label>
                            <select name="status" class="form-select" required>
                                <?php foreach (Shipment::VALID_STATUSES as $st): ?>
                                    <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>>
                                        <?= ucfirst(htmlspecialchars($st)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Envanter Kalemi (Opsiyonel)</label>
                            <select name="inventory_id" class="form-select">
                                <option value="">Kalem Seçin...</option>
                                <?php foreach ($allInventory as $inv): ?>
                                    <option value="<?= $inv['id'] ?>"
                                            data-warehouse="<?= $inv['warehouse_id'] ?>"
                                            data-stock="<?= $inv['quantity'] ?>"
                                            <?= $inventory_id == $inv['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($inv['item_name']) ?> (<?= number_format($inv['quantity'], 2, ',', '.') ?> <?= htmlspecialchars($inv['unit']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <?php if (!empty($warnings) && empty($errors)): ?>
                            <button type="submit" class="btn btn-warning">⚠️ Yine de Güncelle</button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-primary">Güncelle</button>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
