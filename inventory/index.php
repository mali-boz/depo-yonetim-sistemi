<?php
/**
 * inventory/index.php — Envanter listesi (READ)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - header.php dahil et
 * - Inventory.php dahil et
 *
 * - Inventory::getAll() ile tüm envanter kalemlerini çek (depo adı dahil)
 *
 * - Sayfa başlığı: <h2>Envanter</h2>
 * - "Yeni Kalem Ekle" butonu (btn btn-success) → inventory/create.php linki
 *
 * - Flash mesajları göster
 *
 * - Bootstrap tablo (table table-striped table-hover):
 *   - Sütunlar: #, Ürün Adı, Miktar, Birim, Depo, Son Güncelleme, İşlemler
 *   - Her satırda:
 *     - Envanter bilgileri
 *     - Son Güncelleme: updated_at tarihini formatla (ör: d.m.Y H:i)
 *     - İşlemler: Düzenle (btn-warning) ve Sil (btn-danger) butonları
 *     - Sil butonu form POST ile, CSRF token + JS onay kutusu
 *
 * - Kayıt yoksa "Henüz envanter kalemi eklenmemiş." bilgi mesajı
 * - footer.php dahil et
 */
