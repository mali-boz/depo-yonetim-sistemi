<?php
/**
 * Inventory.php — Envanter modeli (CRUD)
 */

require_once __DIR__ . '/Database.php';

class Inventory {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT i.*, w.name AS warehouse_name
             FROM inventory i
             JOIN warehouses w ON i.warehouse_id = w.id
             WHERE w.created_by = ?
             ORDER BY i.updated_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id, int $userId): ?array {
        $stmt = $this->db->prepare(
            "SELECT i.* FROM inventory i
             JOIN warehouses w ON i.warehouse_id = w.id
             WHERE i.id = ? AND w.created_by = ?"
        );
        $stmt->execute([$id, $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO inventory (item_name, quantity, unit, warehouse_id, last_updated_by)
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['item_name'],
            $data['quantity'],
            $data['unit'],
            $data['warehouse_id'],
            $data['last_updated_by']
        ]);
    }

    public function update(int $id, array $data, int $userId): bool {
        $stmt = $this->db->prepare(
            "UPDATE inventory SET item_name=?, quantity=?, unit=?, warehouse_id=?, last_updated_by=?, updated_at=NOW()
             WHERE id=? AND warehouse_id IN (SELECT id FROM warehouses WHERE created_by = ?)"
        );
        return $stmt->execute([
            $data['item_name'],
            $data['quantity'],
            $data['unit'],
            $data['warehouse_id'],
            $userId,
            $id,
            $userId
        ]);
    }

    public function delete(int $id, int $userId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM inventory WHERE id = ? AND warehouse_id IN (SELECT id FROM warehouses WHERE created_by = ?)"
        );
        $stmt->execute([$id, $userId]);
        return $stmt->rowCount() > 0;
    }
}
