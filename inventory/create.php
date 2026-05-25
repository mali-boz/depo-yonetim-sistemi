<?php
/**
 * inventory/create.php — Yeni envanter kalemi ekleme (CREATE)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - header.php, functions.php, Inventory.php, Warehouse.php dahil et
 *
 * - Warehouse::getAll() ile depo listesini çek (dropdown için)
 *
 * - POST isteği kontrolü:
 *   - CSRF token doğrula
 *   - Form alanlarını sanitize et: item_name, quantity, unit, warehouse_id
 *   - Doğrulama:
 *     - Ürün adı boş olmamalı
 *     - Miktar sayısal ve 0 veya üzeri olmalı (is_numeric, >= 0)
 *     - Birim boş olmamalı (ör: "adet", "kg", "litre", "kutu")
 *     - Depo ID'si geçerli bir depoya ait olmalı
 *   - Hata yoksa Inventory::create() çağır:
 *     - $data = ['item_name' => ..., 'quantity' => ..., 'unit' => ..., 'warehouse_id' => ..., 'last_updated_by' => Auth::currentUserId()]
 *   - Başarılıysa flash + yönlendir
 *
 * - GET isteği:
 *   - Sayfa başlığı: <h2>Yeni Envanter Kalemi Ekle</h2>
 *   - Bootstrap card içinde form:
 *     - Ürün Adı (text input)
 *     - Miktar (number input, min="0")
 *     - Birim (text input — veya select: adet, kg, litre, kutu, paket)
 *     - Depo (select dropdown): Warehouse::getAll() ile doldur
 *     - CSRF token (hidden)
 *     - Kaydet (btn-primary) + İptal (btn-secondary → inventory/index.php)
 *   - footer.php dahil et
 */
