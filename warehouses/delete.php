<?php
/**
 * warehouses/delete.php — Depo silme (DELETE)
 *
 * TODO:
 * - auth_guard.php dahil et
 * - functions.php, Warehouse.php dahil et
 *
 * - Yalnızca POST isteklerini kabul et
 *   - GET isteği gelirse warehouses/index.php'ye yönlendir
 *
 * - CSRF token doğrula (validateCsrfToken)
 *
 * - $_POST['id'] ile depo ID'sini al ve integer'a çevir (intval)
 *
 * - Silmeden önce bağımlılık kontrolü yap:
 *   - Bu depoya bağlı sevkiyat (shipments) var mı?
 *   - Bu depoya bağlı envanter (inventory) var mı?
 *   - Bağlı kayıt varsa:
 *     - flash('error', 'Bu depoya bağlı kayıtlar var. Önce onları silmelisiniz.')
 *     - warehouses/index.php'ye yönlendir
 *
 * - Bağımlılık yoksa Warehouse::delete($id) çağır
 * - Başarılıysa flash('success', 'Depo başarıyla silindi.')
 * - Başarısızsa flash('error', 'Depo silinirken bir hata oluştu.')
 * - warehouses/index.php'ye yönlendir
 */
