<?php
/**
 * Shipment.php — Sevkiyat modeli (CRUD)
 *
 * TODO:
 * - Database.php dahil et (require_once)
 * - Shipment sınıfı oluştur
 *
 * - Durum enum değerleri sabiti tanımla:
 *   VALID_STATUSES = ['beklemede', 'yolda', 'teslim edildi']
 *
 * - public static getAll(): array
 *   - SELECT s.*, w.name AS warehouse_name
 *     FROM shipments s
 *     JOIN warehouses w ON s.warehouse_id = w.id
 *     ORDER BY s.created_at DESC
 *   - Depo adını da içeren sevkiyat listesini döndür
 *
 * - public static getById(int $id): ?array
 *   - SELECT * FROM shipments WHERE id = ? prepared statement
 *   - Bulunursa satırı döndür, bulunamazsa null döndür
 *
 * - public static getByWarehouse(int $warehouseId): array
 *   - SELECT * FROM shipments WHERE warehouse_id = ?
 *   - Belirli depoya ait sevkiyatları döndür
 *
 * - public static create(array $data): bool
 *   - INSERT INTO shipments (tracking_no, origin, destination, weight_kg, status, warehouse_id, created_by)
 *   - tracking_no: kullanıcıdan al veya otomatik oluştur (ör: "SVK-" . strtoupper(uniqid()))
 *   - status değerini VALID_STATUSES ile doğrula
 *   - warehouse_id'nin geçerli bir depo olduğunu kontrol et
 *   - Prepared statement kullan
 *
 * - public static update(int $id, array $data): bool
 *   - UPDATE shipments SET tracking_no=?, origin=?, destination=?, weight_kg=?, status=?, warehouse_id=? WHERE id=?
 *   - status değerini VALID_STATUSES ile doğrula
 *   - Prepared statement kullan
 *
 * - public static delete(int $id): bool
 *   - DELETE FROM shipments WHERE id = ?
 *   - Başarı durumunu bool olarak döndür
 */
