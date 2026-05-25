<?php
/**
 * warehouses/create.php — Yeni depo ekleme (CREATE)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - header.php, functions.php, Warehouse.php dahil et
 *
 * - POST isteği kontrolü:
 *   - CSRF token doğrula
 *   - Form alanlarını sanitize et: name, location, capacity_m3
 *   - Doğrulama:
 *     - Ad boş olmamalı
 *     - Konum boş olmamalı
 *     - Kapasite sayısal ve 0'dan büyük olmalı
 *   - Hata yoksa Warehouse::create() çağır:
 *     - $data = ['name' => ..., 'location' => ..., 'capacity_m3' => ..., 'created_by' => Auth::currentUserId()]
 *   - Başarılıysa flash('success', 'Depo başarıyla eklendi.'), warehouses/index.php'ye yönlendir
 *   - Hata varsa hataları topla, formda göster
 *
 * - GET isteği (veya POST hataları sonrası):
 *   - Sayfa başlığı: <h2>Yeni Depo Ekle</h2>
 *   - Bootstrap card içinde form:
 *     - Depo Adı (text input, form-control, required)
 *     - Konum (text input, form-control, required)
 *     - Kapasite m³ (number input, form-control, step="0.01", min="0.01", required)
 *     - CSRF token (hidden input)
 *     - Kaydet butonu (btn btn-primary)
 *     - İptal linki → warehouses/index.php (btn btn-secondary)
 *   - Hata mesajlarını alert-danger ile göster
 *   - footer.php dahil et
 */
