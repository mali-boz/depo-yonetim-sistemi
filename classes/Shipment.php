<?php
/**
 * Shipment.php — Sevkiyat modeli (CRUD)
 * Gelen/Giden yön ayrımı ve envanter entegrasyonu destekli
 */

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Inventory.php';

class Shipment {
    private $db;

    public const VALID_STATUSES   = ['beklemede', 'yolda', 'teslim edildi'];
    public const VALID_DIRECTIONS = ['gelen', 'giden'];

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(int $userId, ?string $directionFilter = null): array {
        $sql = "SELECT s.*, w.name AS warehouse_name, i.item_name AS inventory_item_name
                FROM shipments s
                JOIN warehouses w ON s.warehouse_id = w.id
                LEFT JOIN inventory i ON s.inventory_id = i.id
                WHERE s.created_by = ?";
        $params = [$userId];

        if ($directionFilter && in_array($directionFilter, self::VALID_DIRECTIONS)) {
            $sql .= " AND s.direction = ?";
            $params[] = $directionFilter;
        }

        $sql .= " ORDER BY s.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id, int $userId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM shipments WHERE id = ? AND created_by = ?");
        $stmt->execute([$id, $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO shipments (tracking_no, direction, origin, destination, weight_kg, status, warehouse_id, inventory_id, created_by)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $result = $stmt->execute([
            $data['tracking_no'],
            $data['direction'],
            $data['origin'],
            $data['destination'],
            $data['weight_kg'],
            $data['status'],
            $data['warehouse_id'],
            $data['inventory_id'] ?: null,
            $data['created_by']
        ]);

        // Teslim edildi durumunda stok güncelle
        if ($result && $data['status'] === 'teslim edildi' && !empty($data['inventory_id'])) {
            $this->updateInventoryStock($data['inventory_id'], $data['direction'], floatval($data['weight_kg']));
        }

        return $result;
    }

    public function update(int $id, array $data, int $userId): bool {
        // Mevcut kayıt bilgilerini al (durum değişikliği kontrolü için)
        $existing = $this->getById($id, $userId);
        if (!$existing) return false;

        $stmt = $this->db->prepare(
            "UPDATE shipments SET tracking_no=?, direction=?, origin=?, destination=?, weight_kg=?, status=?, warehouse_id=?, inventory_id=?
             WHERE id=? AND created_by=?"
        );
        $result = $stmt->execute([
            $data['tracking_no'],
            $data['direction'],
            $data['origin'],
            $data['destination'],
            $data['weight_kg'],
            $data['status'],
            $data['warehouse_id'],
            $data['inventory_id'] ?: null,
            $id,
            $userId
        ]);

        // Durum "teslim edildi"ye değiştiyse ve daha önce değilse stok güncelle
        if ($result && $data['status'] === 'teslim edildi' && $existing['status'] !== 'teslim edildi' && !empty($data['inventory_id'])) {
            $this->updateInventoryStock($data['inventory_id'], $data['direction'], floatval($data['weight_kg']));
        }

        return $result;
    }

    public function delete(int $id, int $userId): bool {
        // Eğer teslim edilmiş bir sevkiyat siliniyorsa stoku geri al
        $existing = $this->getById($id, $userId);
        if ($existing && $existing['status'] === 'teslim edildi' && !empty($existing['inventory_id'])) {
            // Ters yönde stok güncelle (giden ise geri ekle, gelen ise geri çıkar)
            $reverseDirection = $existing['direction'] === 'giden' ? 'gelen' : 'giden';
            $this->updateInventoryStock($existing['inventory_id'], $reverseDirection, floatval($existing['weight_kg']));
        }

        $stmt = $this->db->prepare("DELETE FROM shipments WHERE id = ? AND created_by = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Envanter stok miktarını güncelle
     * gelen → stok artar, giden → stok azalır
     */
    private function updateInventoryStock(int $inventoryId, string $direction, float $quantity): void {
        if ($direction === 'giden') {
            $sql = "UPDATE inventory SET quantity = quantity - ?, updated_at = NOW() WHERE id = ?";
        } else {
            $sql = "UPDATE inventory SET quantity = quantity + ?, updated_at = NOW() WHERE id = ?";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$quantity, $inventoryId]);
    }

    /**
     * Belirli bir envanter kaleminin mevcut stok miktarını döndür
     */
    public function getInventoryStock(int $inventoryId): float {
        $stmt = $this->db->prepare("SELECT quantity FROM inventory WHERE id = ?");
        $stmt->execute([$inventoryId]);
        $row = $stmt->fetch();
        return $row ? floatval($row['quantity']) : 0.0;
    }
}
