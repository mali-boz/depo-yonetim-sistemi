<?php
/**
 * shipments/create.php — Yeni sevkiyat ekleme (CREATE)
 * Gelen/Giden yön seçimi, envanter kalemi bağlama ve stok kontrolü destekli
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Shipment.php';
require_once __DIR__ . '/../classes/Warehouse.php';
require_once __DIR__ . '/../classes/Inventory.php';
require_once __DIR__ . '/../includes/functions.php';

$userId = $_SESSION['user_id'];
$warehouseModel = new Warehouse();
$userWarehouses = $warehouseModel->getAll($userId);

if (empty($userWarehouses)) {
    setFlash('error', 'Sevkiyat ekleyebilmek için önce bir depo oluşturmalısınız.');
    header('Location: ../warehouses/create.php');
    exit;
}

// Tüm envanter kalemlerini getir (depo bilgisiyle)
$inventoryModel = new Inventory();
$allInventory = $inventoryModel->getAll($userId);

$errors = [];
$warnings = [];
$tracking_no = '';
$direction = 'giden';
$origin = '';
$destination = '';
$weight_kg = '';
$status = 'beklemede';
$warehouse_id = '';
$inventory_id = '';

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

    // Giden sevkiyat için stok kontrolü
    if (empty($errors) && $direction === 'giden' && $inventory_id > 0) {
        $shipmentModel = new Shipment();
        $currentStock = $shipmentModel->getInventoryStock($inventory_id);
        if ($currentStock < floatval($weight_kg)) {
            $warnings[] = "Uyarı: Seçilen envanter kaleminin mevcut stoğu ({$currentStock}) yetersiz! İstenen miktar: {$weight_kg}";
        }
    }

    // Uyarı varsa ama kullanıcı onayladıysa devam et
    $forceCreate = isset($_POST['force_create']) && $_POST['force_create'] === '1';

    if (empty($errors) && (empty($warnings) || $forceCreate)) {
        $shipment = new Shipment();
        $result = $shipment->create([
            'tracking_no'  => $tracking_no,
            'direction'    => $direction,
            'origin'       => $origin,
            'destination'  => $destination,
            'weight_kg'    => $weight_kg,
            'status'       => $status,
            'warehouse_id' => $warehouse_id,
            'inventory_id' => $inventory_id ?: null,
            'created_by'   => $userId
        ]);

        if ($result) {
            setFlash('success', 'Sevkiyat başarıyla eklendi.');
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Sevkiyat eklenirken bir hata oluştu.';
        }
    }
}

$pageTitle = 'Yeni Sevkiyat Ekle';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="card-title mb-4">Yeni Sevkiyat Ekle</h4>

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
                        <p class="mb-0">Yine de oluşturmak istiyor musunuz?</p>
                    </div>
                <?php endif; ?>

                <form method="POST" class="needs-validation" novalidate>
                    <?php if (!empty($warnings) && empty($errors)): ?>
                        <input type="hidden" name="force_create" value="1">
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
                            <select name="warehouse_id" id="warehouseSelect" class="form-select" required>
                                <option value="">Depo Seçin...</option>
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
                                            data-unit="<?= htmlspecialchars($inv['unit']) ?>"
                                            <?= $inventory_id == $inv['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($inv['item_name']) ?> (<?= number_format($inv['quantity'], 2, ',', '.') ?> <?= htmlspecialchars($inv['unit']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <?php if (!empty($warnings) && empty($errors)): ?>
                            <button type="submit" class="btn btn-warning">⚠️ Yine de Oluştur</button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
