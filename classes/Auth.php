<?php
/**
 * Auth.php — Oturum yönetimi sınıfı
 *
 * TODO:
 * - User.php ve Database.php dahil et (require_once)
 * - Auth sınıfı oluştur, tüm metodlar static olacak
 *
 * - public static login(string $email, string $password): bool
 *   - User::findByEmail() ile kullanıcıyı bul
 *   - Kullanıcı bulunamazsa false döndür
 *   - password_verify($password, $user['password']) ile doğrula
 *   - Doğrulama başarılıysa:
 *     - session_regenerate_id(true) çağır (session fixation önlemi)
 *     - $_SESSION['user_id'] = $user['id']
 *     - $_SESSION['user_name'] = $user['name']
 *     - $_SESSION['user_email'] = $user['email']
 *     - true döndür
 *   - Doğrulama başarısızsa false döndür
 *
 * - public static logout(): void
 *   - $_SESSION içini temizle (session_unset)
 *   - session_destroy() çağır
 *   - session_regenerate_id(true) ile yeni oturum başlat
 *
 * - public static isLoggedIn(): bool
 *   - $_SESSION['user_id'] isset kontrolü yap
 *
 * - public static currentUserId(): ?int
 *   - Oturum açıksa $_SESSION['user_id'] döndür
 *   - Değilse null döndür
 */
require_once __DIR__ . '/User.php';
class Auth {
    public static function login(string $email, string $password): bool {
        
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        if (!$user) {
            return false; // Kullanıcı bulunamadı
        }
        if (password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true); // Session fixation önlemi
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            return true; // Giriş başarılı
        }
        return false; // Şifre doğrulama başarısız
    }

    public static function logout(): void {
        session_unset(); // Tüm session değişkenlerini temizle
        session_destroy(); // Oturumu sonlandır
        session_regenerate_id(true); // Yeni oturum başlat
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function currentUserId(): ?int {
        return self::isLoggedIn() ? $_SESSION['user_id'] : null;
    }
}