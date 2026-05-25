<?php
/**
 * Warehouse.php — Depo modeli (CRUD)
 */

require_once __DIR__ . '/Database.php';

class Warehouse {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Kullanıcıya ait tüm depoları getir
     */
    public function getAll(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM warehouses WHERE created_by = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Tek bir depoyu ID ile getir (yetki kontrolü dahil)
     */
    public function getById(int $id, int $userId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM warehouses WHERE id = ? AND created_by = ?");
        $stmt->execute([$id, $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Yeni depo oluştur
     */
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO warehouses (name, location, capacity_m3, created_by) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['name'],
            $data['location'],
            $data['capacity_m3'],
            $data['created_by']
        ]);
    }

    /**
     * Depo güncelle (yetki kontrolü dahil)
     */
    public function update(int $id, array $data, int $userId): bool {
        $stmt = $this->db->prepare(
            "UPDATE warehouses SET name = ?, location = ?, capacity_m3 = ? WHERE id = ? AND created_by = ?"
        );
        return $stmt->execute([
            $data['name'],
            $data['location'],
            $data['capacity_m3'],
            $id,
            $userId
        ]);
    }

    /**
     * Depo sil (yetki kontrolü dahil)
     */
    public function delete(int $id, int $userId): bool {
        // Bağlı sevkiyat kontrolü
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM shipments WHERE warehouse_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Bağlı sevkiyat var, silinemez
        }

        // Bağlı envanter kontrolü
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM inventory WHERE warehouse_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Bağlı envanter var, silinemez
        }

        $stmt = $this->db->prepare("DELETE FROM warehouses WHERE id = ? AND created_by = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->rowCount() > 0;
    }
}
