<?php
/**
 * inventory/create.php — Yeni envanter kalemi ekleme (CREATE)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Inventory.php';
require_once __DIR__ . '/../classes/Warehouse.php';
require_once __DIR__ . '/../includes/functions.php';

$userId = $_SESSION['user_id'];
$warehouseModel = new Warehouse();
$userWarehouses = $warehouseModel->getAll($userId);

if (empty($userWarehouses)) {
    setFlash('error', 'Envanter ekleyebilmek için önce bir depo oluşturmalısınız.');
    header('Location: ../warehouses/create.php');
    exit;
}

$errors = [];
$item_name = '';
$quantity = '';
$unit = 'adet';
$warehouse_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name   = trim($_POST['item_name'] ?? '');
    $quantity    = trim($_POST['quantity'] ?? '');
    $unit        = trim($_POST['unit'] ?? '');
    $warehouse_id= intval($_POST['warehouse_id'] ?? 0);

    // Doğrulama
    if (empty($item_name)) $errors[] = 'Kalem adı boş olamaz.';
    if (!is_numeric($quantity) || $quantity < 0) $errors[] = 'Miktar sıfır veya daha büyük bir sayı olmalıdır.';
    if (empty($unit)) $errors[] = 'Birim boş olamaz.';
    
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
        $inventory = new Inventory();
        $result = $inventory->create([
            'item_name'       => $item_name,
            'quantity'        => $quantity,
            'unit'            => $unit,
            'warehouse_id'    => $warehouse_id,
            'last_updated_by' => $userId
        ]);

        if ($result) {
            setFlash('success', 'Envanter kalemi başarıyla eklendi.');
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Envanter eklenirken bir hata oluştu.';
        }
    }
}

$pageTitle = 'Yeni Envanter Ekle';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="card-title mb-4">Yeni Envanter Kalemi Ekle</h4>

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
                    <div class="mb-3">
                        <label class="form-label">Kalem Adı</label>
                        <input type="text" name="item_name" class="form-control" value="<?= htmlspecialchars($item_name) ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Miktar</label>
                            <input type="number" name="quantity" class="form-control" step="0.01" min="0" value="<?= htmlspecialchars($quantity) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Birim (Örn: adet, kg, ton)</label>
                            <input type="text" name="unit" class="form-control" value="<?= htmlspecialchars($unit) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bağlı Depo</label>
                        <select name="warehouse_id" class="form-select" required>
                            <option value="">Depo Seçin...</option>
                            <?php foreach ($userWarehouses as $w): ?>
                                <option value="<?= $w['id'] ?>" <?= $warehouse_id == $w['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($w['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <a href="index.php" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
