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
    <a href="create.php" class="btn btn-success">+ Yeni Kalem Ekle</a>
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
                        <form method="POST" action="delete.php" class="d-inline" onsubmit="return confirm('Bu envanter kalemini silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
