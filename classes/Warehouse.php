<?php
/**
 * Warehouse.php — Depo modeli (CRUD)
 *
 * TODO:
 * - Database.php dahil et (require_once)
 * - Warehouse sınıfı oluştur
 *
 * - public static getAll(): array
 *   - SELECT * FROM warehouses ORDER BY created_at DESC
 *   - Tüm depoları dizi olarak döndür
 *   - İsteğe bağlı: created_by ile filtreleme parametresi ekle
 *
 * - public static getById(int $id): ?array
 *   - SELECT * FROM warehouses WHERE id = ? prepared statement
 *   - Bulunursa satırı döndür, bulunamazsa null döndür
 *
 * - public static create(array $data): bool
 *   - INSERT INTO warehouses (name, location, capacity_m3, created_by)
 *   - $data anahtarları: 'name', 'location', 'capacity_m3', 'created_by'
 *   - Prepared statement kullan
 *   - Başarı durumunu bool olarak döndür
 *
 * - public static update(int $id, array $data): bool
 *   - UPDATE warehouses SET name=?, location=?, capacity_m3=? WHERE id=?
 *   - Prepared statement kullan
 *   - Başarı durumunu bool olarak döndür
 *
 * - public static delete(int $id): bool
 *   - Silmeden önce bağlı sevkiyat ve envanter kayıtlarını kontrol et
 *   - Bağlı kayıt varsa silmeyi engelle veya uyarı döndür
 *   - DELETE FROM warehouses WHERE id = ?
 *   - Başarı durumunu bool olarak döndür
 */
