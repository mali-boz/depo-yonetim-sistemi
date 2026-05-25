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

class User  {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../classes/Database.php';
        $this->db = Database::getInstance()->getConnection();
    }

    public function emailExists(string $email): bool {
        return $this->findByEmail($email) !== null;
    }


    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(string $name, string $email, string $password): bool {
        if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false; // Geçersiz giriş
        }
        if ($this->emailExists($email)) {
            return false; // Mükerrer email
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $email, $hashedPassword]);
    }
}