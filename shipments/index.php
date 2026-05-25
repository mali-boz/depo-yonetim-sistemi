<?php
/**
 * shipments/index.php — Sevkiyat listesi (READ)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - header.php dahil et
 * - Shipment.php dahil et
 *
 * - Shipment::getAll() ile tüm sevkiyatları çek (depo adı dahil)
 *
 * - Sayfa başlığı: <h2>Sevkiyatlar</h2>
 * - "Yeni Sevkiyat Ekle" butonu (btn btn-success) → shipments/create.php linki
 *
 * - Flash mesajları göster
 *
 * - Bootstrap tablo (table table-striped table-hover):
 *   - Sütunlar: #, Takip No, Çıkış Yeri, Varış Yeri, Ağırlık (kg), Durum, Depo, İşlemler
 *   - Durum sütunu Bootstrap badge ile renklendirilecek:
 *     - 'beklemede' → badge bg-warning text-dark
 *     - 'yolda' → badge bg-info
 *     - 'teslim edildi' → badge bg-success
 *   - İşlemler: Düzenle (btn-warning) ve Sil (btn-danger) butonları
 *   - Sil butonu form POST ile, CSRF token + JS onay kutusu
 *
 * - Kayıt yoksa "Henüz sevkiyat eklenmemiş." bilgi mesajı
 * - footer.php dahil et
 */
