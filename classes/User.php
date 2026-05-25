<?php
/**
 * User.php — Kullanıcı modeli
 *
 * TODO:
 * - Database.php dahil et (require_once)
 * - User sınıfı oluştur
 *
 * - public static register(string $name, string $email, string $password): bool
 *   - Girdi doğrulama: isim boş olmamalı, email geçerli formatta olmalı
 *   - emailExists() ile mükerrer kontrol yap
 *   - Şifreyi password_hash($password, PASSWORD_DEFAULT) ile hashle
 *   - INSERT INTO users (name, email, password) prepared statement ile çalıştır
 *   - Başarılıysa true, hata varsa false döndür
 *
 * - public static findByEmail(string $email): ?array
 *   - SELECT * FROM users WHERE email = ? prepared statement
 *   - Kullanıcı bulunursa satırı (array) döndür
 *   - Bulunamazsa null döndür
 *
 * - public static emailExists(string $email): bool
 *   - findByEmail() sonucunun null olup olmadığını kontrol et
 *   - Kayıt sırasında mükerrer email önlemek için kullan
 */
