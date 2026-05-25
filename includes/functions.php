<?php
/**
 * functions.php — Paylaşılan yardımcı fonksiyonlar
 *
 * TODO:
 *
 * - function sanitize(string $input): string
 *   - trim() ile başındaki/sonundaki boşlukları temizle
 *   - htmlspecialchars($input, ENT_QUOTES, 'UTF-8') ile XSS önlemi uygula
 *   - Temizlenmiş string döndür
 *
 * - function flash(string $key, string $message): void
 *   - Flash mesajı $_SESSION['flash'][$key] içine kaydet
 *   - Bir sonraki sayfa yüklemesinde gösterilmek üzere sakla
 *
 * - function getFlash(string $key): ?string
 *   - $_SESSION['flash'][$key] varsa mesajı al
 *   - Mesajı session'dan sil (unset) — tek kullanımlık
 *   - Mesaj varsa döndür, yoksa null döndür
 *
 * - function generateCsrfToken(): string
 *   - bin2hex(random_bytes(32)) ile güvenli rastgele token oluştur
 *   - Token'ı $_SESSION['csrf_token'] içine kaydet
 *   - Token string'ini döndür (form hidden field'da kullanılacak)
 *
 * - function validateCsrfToken(string $token): bool
 *   - Gönderilen token ile $_SESSION['csrf_token'] değerini karşılaştır
 *   - hash_equals() kullan (timing attack önlemi)
 *   - Eşleşme durumunu bool olarak döndür
 *
 * - function redirect(string $url): void
 *   - header("Location: " . $url) ile yönlendir
 *   - exit() ile betiği sonlandır
 */
