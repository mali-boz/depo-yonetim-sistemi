<?php
/**
 * shipments/create.php — Yeni sevkiyat ekleme (CREATE)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - header.php, functions.php, Shipment.php, Warehouse.php dahil et
 *
 * - Warehouse::getAll() ile depo listesini çek (dropdown için)
 *
 * - POST isteği kontrolü:
 *   - CSRF token doğrula
 *   - Form alanlarını sanitize et: tracking_no, origin, destination, weight_kg, status, warehouse_id
 *   - Doğrulama:
 *     - Takip numarası boş olmamalı
 *     - Çıkış yeri boş olmamalı
 *     - Varış yeri boş olmamalı
 *     - Ağırlık sayısal ve 0'dan büyük olmalı
 *     - Durum geçerli enum değeri olmalı: ['beklemede', 'yolda', 'teslim edildi']
 *     - Depo ID'si geçerli bir depoya ait olmalı
 *   - Hata yoksa Shipment::create() çağır (created_by = Auth::currentUserId())
 *   - Başarılıysa flash + yönlendir
 *
 * - GET isteği:
 *   - Sayfa başlığı: <h2>Yeni Sevkiyat Ekle</h2>
 *   - Bootstrap card içinde form:
 *     - Takip No (text input — veya otomatik oluşturulacaksa readonly)
 *     - Çıkış Yeri (text input)
 *     - Varış Yeri (text input)
 *     - Ağırlık kg (number input, step="0.01", min="0.01")
 *     - Durum (select dropdown): beklemede, yolda, teslim edildi seçenekleri
 *     - Depo (select dropdown): Warehouse::getAll() ile doldur
 *     - CSRF token (hidden)
 *     - Kaydet (btn-primary) + İptal (btn-secondary)
 *   - footer.php dahil et
 */
