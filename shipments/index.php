<?php
/**
 * shipments/index.php — Sevkiyat listesi (READ)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Shipment.php';

$userId = $_SESSION['user_id'];
$shipment = new Shipment();
$shipments = $shipment->getAll($userId);

// Durum badge renkleri
function statusBadge(string $status): string {
    return match ($status) {
        'beklemede'      => '<span class="badge bg-warning text-dark">Beklemede</span>',
        'yolda'          => '<span class="badge bg-info text-white">Yolda</span>',
        'teslim edildi'  => '<span class="badge bg-success">Teslim Edildi</span>',
        default          => '<span class="badge bg-secondary">' . htmlspecialchars($status) . '</span>',
    };
}

$pageTitle = 'Sevkiyatlar';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Sevkiyatlar</h2>
    <a href="create.php" class="btn btn-success">+ Yeni Sevkiyat</a>
</div>

<?php if (empty($shipments)): ?>
    <div class="alert alert-info">Henüz sevkiyat eklenmemiş.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Takip No</th>
                    <th>Çıkış</th>
                    <th>Varış</th>
                    <th>Ağırlık (kg)</th>
                    <th>Durum</th>
                    <th>Depo</th>
                    <th class="text-center">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shipments as $i => $s): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($s['tracking_no']) ?></strong></td>
                    <td><?= htmlspecialchars($s['origin']) ?></td>
                    <td><?= htmlspecialchars($s['destination']) ?></td>
                    <td><?= number_format($s['weight_kg'], 2, ',', '.') ?></td>
                    <td><?= statusBadge($s['status']) ?></td>
                    <td><?= htmlspecialchars($s['warehouse_name']) ?></td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-warning btn-sm">Düzenle</a>
                        <form method="POST" action="delete.php" class="d-inline" onsubmit="return confirm('Bu sevkiyatı silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
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
