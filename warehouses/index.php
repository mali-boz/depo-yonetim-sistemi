<?php
/**
 * warehouses/index.php — Depo listesi (READ)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - header.php dahil et
 * - Warehouse.php dahil et
 *
 * - Warehouse::getAll() ile tüm depoları çek
 *
 * - Sayfa başlığı: <h2>Depolar</h2>
 * - "Yeni Depo Ekle" butonu (btn btn-success) → warehouses/create.php linki
 *
 * - Flash mesajları göster (başarı/hata)
 *
 * - Bootstrap tablo oluştur (table table-striped table-hover):
 *   - Sütunlar: #, Ad, Konum, Kapasite (m³), Oluşturulma Tarihi, İşlemler
 *   - Her satırda:
 *     - Depo bilgileri
 *     - İşlemler sütununda:
 *       - Düzenle butonu (btn btn-sm btn-warning) → edit.php?id=X
 *       - Sil butonu (btn btn-sm btn-danger) → form ile POST delete.php
 *         - Sil butonunda CSRF token hidden input
 *         - JavaScript onsubmit ile "Emin misiniz?" onay kutusu
 *
 * - Kayıt yoksa "Henüz depo eklenmemiş." bilgi mesajı (alert-info)
 *
 * - footer.php dahil et
 */
