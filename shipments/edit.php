<?php
/**
 * shipments/edit.php — Sevkiyat düzenleme (UPDATE)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - header.php, functions.php, Shipment.php, Warehouse.php dahil et
 *
 * - $_GET['id'] ile sevkiyat ID'sini al
 * - Shipment::getById($id) ile mevcut kaydı çek
 * - Kayıt bulunamazsa flash('error', 'Sevkiyat bulunamadı.'), shipments/index.php'ye yönlendir
 *
 * - Warehouse::getAll() ile depo listesini çek (dropdown için)
 *
 * - POST isteği kontrolü:
 *   - CSRF token doğrula
 *   - Form alanlarını sanitize et (create.php ile aynı alanlar)
 *   - Doğrulama (create.php ile aynı kurallar)
 *   - Hata yoksa Shipment::update($id, $data) çağır
 *   - Başarılıysa flash('success', 'Sevkiyat başarıyla güncellendi.'), shipments/index.php'ye yönlendir
 *
 * - GET isteği:
 *   - Sayfa başlığı: <h2>Sevkiyat Düzenle</h2>
 *   - Bootstrap card içinde form (create.php ile aynı yapı):
 *     - Tüm input'lar mevcut değerlerle önceden doldurulmuş
 *     - Durum dropdown'ında mevcut durum seçili (selected)
 *     - Depo dropdown'ında mevcut depo seçili (selected)
 *     - CSRF token, Güncelle butonu, İptal linki
 *   - footer.php dahil et
 */
