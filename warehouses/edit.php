<?php
/**
 * warehouses/edit.php — Depo düzenleme (UPDATE)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - header.php, functions.php, Warehouse.php dahil et
 *
 * - $_GET['id'] ile depo ID'sini al
 * - Warehouse::getById($id) ile mevcut kaydı çek
 * - Kayıt bulunamazsa flash('error', 'Depo bulunamadı.'), warehouses/index.php'ye yönlendir
 *
 * - POST isteği kontrolü:
 *   - CSRF token doğrula
 *   - Form alanlarını sanitize et: name, location, capacity_m3
 *   - Doğrulama (create.php ile aynı kurallar)
 *   - Hata yoksa Warehouse::update($id, $data) çağır
 *   - Başarılıysa flash('success', 'Depo başarıyla güncellendi.'), warehouses/index.php'ye yönlendir
 *   - Hata varsa hataları topla, formda göster
 *
 * - GET isteği (veya POST hataları sonrası):
 *   - Sayfa başlığı: <h2>Depo Düzenle</h2>
 *   - Bootstrap card içinde form (create.php ile aynı yapı):
 *     - Tüm input'lar mevcut değerlerle önceden doldurulmuş (value="...")
 *     - CSRF token (hidden input)
 *     - Güncelle butonu (btn btn-primary)
 *     - İptal linki → warehouses/index.php (btn btn-secondary)
 *   - footer.php dahil et
 */
