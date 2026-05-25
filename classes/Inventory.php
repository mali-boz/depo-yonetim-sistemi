<?php
/**
 * Inventory.php — Envanter modeli (CRUD)
 *
 * TODO:
 * - Database.php dahil et (require_once)
 * - Inventory sınıfı oluştur
 *
 * - public static getAll(): array
 *   - SELECT i.*, w.name AS warehouse_name
 *     FROM inventory i
 *     JOIN warehouses w ON i.warehouse_id = w.id
 *     ORDER BY i.updated_at DESC
 *   - Depo adını da içeren envanter listesini döndür
 *
 * - public static getById(int $id): ?array
 *   - SELECT * FROM inventory WHERE id = ? prepared statement
 *   - Bulunursa satırı döndür, bulunamazsa null döndür
 *
 * - public static getByWarehouse(int $warehouseId): array
 *   - SELECT * FROM inventory WHERE warehouse_id = ?
 *   - Belirli depoya ait envanter kalemlerini döndür
 *
 * - public static create(array $data): bool
 *   - INSERT INTO inventory (item_name, quantity, unit, warehouse_id, last_updated_by)
 *   - warehouse_id'nin geçerli bir depo olduğunu kontrol et
 *   - Prepared statement kullan
 *   - Başarı durumunu bool olarak döndür
 *
 * - public static update(int $id, array $data): bool
 *   - UPDATE inventory SET item_name=?, quantity=?, unit=?, warehouse_id=?, last_updated_by=?, updated_at=NOW() WHERE id=?
 *   - last_updated_by alanını mevcut oturum kullanıcısı ile doldur
 *   - updated_at alanını güncelleme anında ayarla
 *   - Prepared statement kullan
 *
 * - public static delete(int $id): bool
 *   - DELETE FROM inventory WHERE id = ?
 *   - Başarı durumunu bool olarak döndür
 */
