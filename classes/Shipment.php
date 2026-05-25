<?php
/**
 * Shipment.php — Sevkiyat modeli (CRUD)
 */

require_once __DIR__ . '/Database.php';

class Shipment {
    private $db;

    public const VALID_STATUSES = ['beklemede', 'yolda', 'teslim edildi'];

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT s.*, w.name AS warehouse_name
             FROM shipments s
             JOIN warehouses w ON s.warehouse_id = w.id
             WHERE s.created_by = ?
             ORDER BY s.created_at DESC"
        );
        $stmt->execute([$userId]);
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
            "INSERT INTO shipments (tracking_no, origin, destination, weight_kg, status, warehouse_id, created_by)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['tracking_no'],
            $data['origin'],
            $data['destination'],
            $data['weight_kg'],
            $data['status'],
            $data['warehouse_id'],
            $data['created_by']
        ]);
    }

    public function update(int $id, array $data, int $userId): bool {
        $stmt = $this->db->prepare(
            "UPDATE shipments SET tracking_no=?, origin=?, destination=?, weight_kg=?, status=?, warehouse_id=?
             WHERE id=? AND created_by=?"
        );
        return $stmt->execute([
            $data['tracking_no'],
            $data['origin'],
            $data['destination'],
            $data['weight_kg'],
            $data['status'],
            $data['warehouse_id'],
            $id,
            $userId
        ]);
    }

    public function delete(int $id, int $userId): bool {
        $stmt = $this->db->prepare("DELETE FROM shipments WHERE id = ? AND created_by = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->rowCount() > 0;
    }
}
