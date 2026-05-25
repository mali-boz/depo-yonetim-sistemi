<?php
/**
 * shipments/edit.php — Sevkiyat düzenleme (UPDATE)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Shipment.php';
require_once __DIR__ . '/../classes/Warehouse.php';
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

$errors = [];
$tracking_no = $existing['tracking_no'];
$origin      = $existing['origin'];
$destination = $existing['destination'];
$weight_kg   = $existing['weight_kg'];
$status      = $existing['status'];
$warehouse_id= $existing['warehouse_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tracking_no = trim($_POST['tracking_no'] ?? '');
    $origin      = trim($_POST['origin'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $weight_kg   = trim($_POST['weight_kg'] ?? '');
    $status      = trim($_POST['status'] ?? '');
    $warehouse_id= intval($_POST['warehouse_id'] ?? 0);

    // Doğrulama
    if (empty($tracking_no)) $errors[] = 'Takip numarası boş olamaz.';
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

    if (empty($errors)) {
        $result = $shipmentModel->update($id, [
            'tracking_no'  => $tracking_no,
            'origin'       => $origin,
            'destination'  => $destination,
            'weight_kg'    => $weight_kg,
            'status'       => $status,
            'warehouse_id' => $warehouse_id
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

                <form method="POST">
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ağırlık (kg)</label>
                            <input type="number" name="weight_kg" class="form-control" step="0.01" min="0.01" value="<?= htmlspecialchars($weight_kg) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Durum</label>
                            <select name="status" class="form-select" required>
                                <?php foreach (Shipment::VALID_STATUSES as $st): ?>
                                    <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>>
                                        <?= ucfirst(htmlspecialchars($st)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                        <a href="index.php" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
