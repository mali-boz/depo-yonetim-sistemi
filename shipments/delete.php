<?php
/**
 * shipments/delete.php — Sevkiyat silme (DELETE)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - functions.php, Shipment.php dahil et
 *
 * - Yalnızca POST isteklerini kabul et
 *   - GET isteği gelirse shipments/index.php'ye yönlendir
 *
 * - CSRF token doğrula
 * - $_POST['id'] ile sevkiyat ID'sini al ve intval() ile integer'a çevir
 *
 * - Shipment::delete($id) çağır
 * - Başarılıysa flash('success', 'Sevkiyat başarıyla silindi.')
 * - Başarısızsa flash('error', 'Sevkiyat silinirken bir hata oluştu.')
 * - shipments/index.php'ye yönlendir
 */
