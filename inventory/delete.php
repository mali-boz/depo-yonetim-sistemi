<?php
/**
 * inventory/delete.php — Envanter kalemi silme (DELETE)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - functions.php, Inventory.php dahil et
 *
 * - Yalnızca POST isteklerini kabul et
 *   - GET isteği gelirse inventory/index.php'ye yönlendir
 *
 * - CSRF token doğrula
 * - $_POST['id'] ile envanter ID'sini al ve intval() ile integer'a çevir
 *
 * - Inventory::delete($id) çağır
 * - Başarılıysa flash('success', 'Envanter kalemi başarıyla silindi.')
 * - Başarısızsa flash('error', 'Envanter kalemi silinirken bir hata oluştu.')
 * - inventory/index.php'ye yönlendir
 */
