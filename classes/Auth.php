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
