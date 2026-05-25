<?php
/**
 * Database.php — PDO veritabanı bağlantı sınıfı
 *
 * TODO:
 * - config.php dosyasını dahil et (require_once)
 * - Database sınıfı oluştur
 *
 * - private static $instance özelliği tanımla (singleton deseni)
 *
 * - public static getConnection(): PDO metodu:
 *   - Eğer $instance null ise yeni PDO nesnesi oluştur
 *   - DSN: "mysql:host=DB_HOST;dbname=DB_NAME;charset=DB_CHARSET"
 *   - PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ayarla
 *   - PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ayarla
 *   - PDO::ATTR_EMULATE_PREPARES => false ayarla
 *   - $instance'ı döndür
 *
 * - Constructor'ı private yap (dışarıdan new ile oluşturma engeli)
 */

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct(){
        require_once __DIR__ . '/../config/config.php';
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}