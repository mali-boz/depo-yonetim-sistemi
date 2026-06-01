<?php
/**
 * warehouses/index.php — Depo listesi (READ)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Warehouse.php';

$userId = $_SESSION['user_id'];
$warehouse = new Warehouse();
$warehouses = $warehouse->getAll($userId);

$pageTitle = 'Depolar';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Depolar</h2>
    <div class="d-flex gap-2 align-items-center">
        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Ara..." style="width: 200px;">
        <a href="create.php" class="btn btn-success">+ Yeni Depo Ekle</a>
    </div>
</div>

<?php if (empty($warehouses)): ?>
    <div class="alert alert-info">Henüz depo eklenmemiş. Yukarıdaki butona tıklayarak ilk deponuzu ekleyin.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Depo Adı</th>
                    <th>Konum</th>
                    <th>Kapasite (m³)</th>
                    <th>Oluşturulma</th>
                    <th class="text-center">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($warehouses as $i => $w): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($w['name']) ?></td>
                    <td><?= htmlspecialchars($w['location']) ?></td>
                    <td><?= number_format($w['capacity_m3'], 2, ',', '.') ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($w['created_at'])) ?></td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $w['id'] ?>" class="btn btn-warning btn-sm">Düzenle</a>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $w['id'] ?>" data-action="delete.php">Sil</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
