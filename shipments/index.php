<?php
/**
 * shipments/index.php — Sevkiyat listesi (READ)
 * Gelen/Giden filtreli, arama destekli
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Shipment.php';

$userId = $_SESSION['user_id'];
$shipment = new Shipment();

// Yön filtresi (GET parametresi)
$directionFilter = isset($_GET['direction']) && in_array($_GET['direction'], Shipment::VALID_DIRECTIONS)
    ? $_GET['direction']
    : null;

$shipments = $shipment->getAll($userId, $directionFilter);

// Durum badge renkleri
function statusBadge(string $status): string {
    return match ($status) {
        'beklemede'      => '<span class="badge bg-warning text-dark">Beklemede</span>',
        'yolda'          => '<span class="badge bg-info text-white">Yolda</span>',
        'teslim edildi'  => '<span class="badge bg-success">Teslim Edildi</span>',
        default          => '<span class="badge bg-secondary">' . htmlspecialchars($status) . '</span>',
    };
}

// Yön badge renkleri
function directionBadge(string $direction): string {
    return match ($direction) {
        'gelen' => '<span class="badge bg-primary">📥 Gelen</span>',
        'giden' => '<span class="badge bg-danger">📤 Giden</span>',
        default => '<span class="badge bg-secondary">' . htmlspecialchars($direction) . '</span>',
    };
}

$pageTitle = 'Sevkiyatlar';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Sevkiyatlar</h2>
    <div class="d-flex gap-2 align-items-center">
        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Ara..." style="width: 200px;">
        <a href="create.php" class="btn btn-success">+ Yeni Sevkiyat</a>
    </div>
</div>

<!-- Yön Filtre Butonları -->
<div class="mb-3">
    <div class="btn-group btn-group-sm" role="group">
        <a href="index.php" class="btn <?= !$directionFilter ? 'btn-dark' : 'btn-outline-dark' ?>">Tümü</a>
        <a href="index.php?direction=gelen" class="btn <?= $directionFilter === 'gelen' ? 'btn-primary' : 'btn-outline-primary' ?>">📥 Gelen</a>
        <a href="index.php?direction=giden" class="btn <?= $directionFilter === 'giden' ? 'btn-danger' : 'btn-outline-danger' ?>">📤 Giden</a>
    </div>
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
                    <th>Yön</th>
                    <th>Çıkış</th>
                    <th>Varış</th>
                    <th>Ağırlık (kg)</th>
                    <th>Durum</th>
                    <th>Depo</th>
                    <th>Envanter Kalemi</th>
                    <th class="text-center">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shipments as $i => $s): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($s['tracking_no']) ?></strong></td>
                    <td><?= directionBadge($s['direction']) ?></td>
                    <td><?= htmlspecialchars($s['origin']) ?></td>
                    <td><?= htmlspecialchars($s['destination']) ?></td>
                    <td><?= number_format($s['weight_kg'], 2, ',', '.') ?></td>
                    <td><?= statusBadge($s['status']) ?></td>
                    <td><?= htmlspecialchars($s['warehouse_name']) ?></td>
                    <td><?= $s['inventory_item_name'] ? htmlspecialchars($s['inventory_item_name']) : '<span class="text-muted">—</span>' ?></td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-warning btn-sm">Düzenle</a>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $s['id'] ?>" data-action="delete.php">Sil</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
