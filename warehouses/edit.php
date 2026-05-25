<?php
/**
 * warehouses/edit.php — Depo düzenleme (UPDATE)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Warehouse.php';
require_once __DIR__ . '/../includes/functions.php';

$userId = $_SESSION['user_id'];
$warehouseModel = new Warehouse();

// ID kontrolü
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$existing = $warehouseModel->getById($id, $userId);

if (!$existing) {
    setFlash('error', 'Depo bulunamadı veya erişim yetkiniz yok.');
    header('Location: index.php');
    exit;
}

$errors = [];
$name     = $existing['name'];
$location = $existing['location'];
$capacity = $existing['capacity_m3'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $capacity = trim($_POST['capacity_m3'] ?? '');

    // Doğrulama
    if (empty($name))     $errors[] = 'Depo adı boş olamaz.';
    if (empty($location)) $errors[] = 'Konum boş olamaz.';
    if (!is_numeric($capacity) || $capacity <= 0) $errors[] = 'Kapasite sıfırdan büyük bir sayı olmalıdır.';

    if (empty($errors)) {
        $result = $warehouseModel->update($id, [
            'name'       => $name,
            'location'   => $location,
            'capacity_m3'=> $capacity
        ], $userId);

        if ($result) {
            setFlash('success', 'Depo başarıyla güncellendi.');
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Depo güncellenirken bir hata oluştu.';
        }
    }
}

$pageTitle = 'Depo Düzenle';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="card-title mb-4">Depo Düzenle</h4>

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
                        <label class="form-label">Depo Adı</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konum</label>
                        <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($location) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapasite (m³)</label>
                        <input type="number" name="capacity_m3" class="form-control" step="0.01" min="0.01" value="<?= htmlspecialchars($capacity) ?>" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                        <a href="index.php" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
