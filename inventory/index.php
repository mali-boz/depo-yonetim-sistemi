<?php
/**
 * inventory/index.php — Envanter listesi (READ)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Inventory.php';

$userId = $_SESSION['user_id'];
$inventoryModel = new Inventory();
$items = $inventoryModel->getAll($userId);

$pageTitle = 'Envanter';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Envanter Kalemleri</h2>
    <div class="d-flex gap-2 align-items-center">
        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Ara..." style="width: 200px;">
        <a href="create.php" class="btn btn-success">+ Yeni Kalem Ekle</a>
    </div>
</div>

<?php if (empty($items)): ?>
    <div class="alert alert-info">Envanterinizde henüz kalem bulunmuyor.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Kalem Adı</th>
                    <th>Miktar / Birim</th>
                    <th>Bağlı Depo</th>
                    <th>Son Güncelleme</th>
                    <th class="text-center">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $item): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($item['item_name']) ?></strong></td>
                    <td>
                        <span class="fs-5"><?= number_format($item['quantity'], 2, ',', '.') ?></span>
                        <small class="text-muted"><?= htmlspecialchars($item['unit']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($item['warehouse_name']) ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($item['updated_at'])) ?></td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-warning btn-sm">Düzenle</a>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $item['id'] ?>" data-action="delete.php">Sil</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
