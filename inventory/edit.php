<?php
/**
 * inventory/edit.php — Envanter kalemi düzenleme (UPDATE)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - header.php, functions.php, Inventory.php, Warehouse.php dahil et
 *
 * - $_GET['id'] ile envanter ID'sini al
 * - Inventory::getById($id) ile mevcut kaydı çek
 * - Kayıt bulunamazsa flash('error', 'Envanter kalemi bulunamadı.'), inventory/index.php'ye yönlendir
 *
 * - Warehouse::getAll() ile depo listesini çek (dropdown için)
 *
 * - POST isteği kontrolü:
 *   - CSRF token doğrula
 *   - Form alanlarını sanitize et (create.php ile aynı alanlar)
 *   - Doğrulama (create.php ile aynı kurallar)
 *   - Hata yoksa Inventory::update($id, $data) çağır:
 *     - $data içinde last_updated_by = Auth::currentUserId() ekle
 *     - updated_at otomatik güncellenecek (SQL NOW() veya ON UPDATE)
 *   - Başarılıysa flash('success', 'Envanter kalemi başarıyla güncellendi.'), inventory/index.php'ye yönlendir
 *
 * - GET isteği:
 *   - Sayfa başlığı: <h2>Envanter Kalemi Düzenle</h2>
 *   - Bootstrap card içinde form (create.php ile aynı yapı):
 *     - Tüm input'lar mevcut değerlerle önceden doldurulmuş
 *     - Depo dropdown'ında mevcut depo seçili (selected)
 *     - CSRF token, Güncelle butonu, İptal linki
 *   - footer.php dahil et
 */
