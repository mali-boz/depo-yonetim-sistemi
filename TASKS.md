# TASKS.md — Lojistik Depo Yönetim Sistemi

## PHASE 0 — Ortam & Yapılandırma

- [x] **A1** `config/config.php` oluştur (DB host, name, user, pass sabitleri). Bu dosyayı `.gitignore`'a ekle.
- [x] **A2** `config/config.example.php`'yi doldur (gerçek değer yok, sadece anahtar isimler ve dummy değerler).
- [x] **A3** `.gitignore`'u kontrol et: `config/config.php`, `.env`, geçici dosyalar kapsanıyor mu?
- [x] **A4** `classes/Database.php` yaz: PDO bağlantısı, singleton ya da basit factory. `config.php`'yi `require` et.
- [x] **A5** Veritabanını oluştur: `users`, `warehouses`, `shipments`, `inventory` tablolarını çalıştır.
- [x] **A6** Lokal ortamı doğrula: XAMPP/Laragon/Docker ile `index.php` açılıyor mu?

---

## PHASE 1 — Kimlik Doğrulama (Auth)

- [x] **B1** `classes/User.php` → `findByEmail()`, `create()` metodları.
- [x] **B2** `classes/Auth.php` → `login()`, `logout()`, `isLoggedIn()`, `currentUser()` metodları. `session_start()` sadece burada çağrılsın.
- [x] **B3** `auth/register.php` → Form göster + POST işle. `password_hash()` ile şifrele. E-posta benzersizliği kontrol et. Başarıda login'e yönlendir.
- [x] **B4** `auth/login.php` → Form göster + POST işle. `password_verify()` kullan. Session'a `user_id` yaz. Başarıda `dashboard.php`'ye yönlendir.
- [x] **B5** `auth/logout.php` → Session'ı yok et, `index.php`'ye yönlendir.
- [x] **B6** `includes/auth_guard.php` → Oturum yoksa `login.php`'ye yönlendir. Her korumalı sayfanın tepesine `require` edilecek.
- [x] **B7** `dashboard.php` → `auth_guard.php` require et. Temel bir karşılama mesajı ve navigasyon göster.
- [x] **B8** Auth akışını uçtan uca test et: kayıt → giriş → çıkış → korumalı sayfaya erişim engeli.

---

## PHASE 2 — Header / Footer / Genel Layout

- [x] **C1** `includes/header.php` → Bootstrap 5 CDN, navbar (marka adı, depo/sevkiyat/envanter linkleri, kullanıcı adı + çıkış). `$pageTitle` değişkenini `<title>` için kullan.
- [x] **C2** `includes/footer.php` → Bootstrap JS bundle CDN. İsteğe bağlı kısa footer.
- [x] **C3** `includes/functions.php` → Yardımcı fonksiyonlar: `sanitize()`, `redirect()`, `setFlash()`, `getFlash()` (session tabanlı flash mesaj).
- [x] **C4** `assets/css/style.css` → Sadece Bootstrap üzerine küçük override'lar (Bootstrap'ın tamamen değil, tamamlayacak şekilde).
- [x] **C5** `index.php` → Oturum varsa dashboard'a, yoksa login sayfasına yönlendir.

---

## PHASE 3 — Warehouse CRUD

- [x] **D1** `classes/Warehouse.php` → `getAll($userId)`, `getById($id)`, `create($data)`, `update($id, $data)`, `delete($id)` metodları. Her sorguda `created_by` kontrolü yap (başka kullanıcının deposunu göremez).
- [x] **D2** `warehouses/index.php` → Kullanıcının depolarını tablo olarak listele. "Yeni Depo" butonu. Her satırda Düzenle / Sil aksiyonları.
- [x] **D3** `warehouses/create.php` → Form (ad, konum, kapasite m³). POST'ta doğrula, kaydet, listeye yönlendir + flash mesaj.
- [x] **D4** `warehouses/edit.php` → `?id=` parametresiyle mevcut veriyi doldur. POST'ta güncelle. Yetki kontrolü: sadece kendi deposu.
- [x] **D5** `warehouses/delete.php` → POST tabanlı silme (GET ile silme yapma). Yetki kontrolü. Listeye yönlendir + flash mesaj.
- [ ] **D6** Warehouse CRUD'u uçtan uca test et.

---

## PHASE 4 — Shipment CRUD

- [ ] **E1** `classes/Shipment.php` → `getAll($userId)`, `getById($id)`, `create($data)`, `update($id, $data)`, `delete($id)` metodları.
- [ ] **E2** `shipments/index.php` → Sevkiyatları tablo olarak listele. Durum badge'i (beklemede / yolda / teslim edildi).
- [ ] **E3** `shipments/create.php` → Form (takip no, kaynak, hedef, ağırlık kg, durum, depo seçimi). Depo dropdown'u sadece kullanıcının kendi depolarını göstersin.
- [ ] **E4** `shipments/edit.php` → Mevcut veriyi doldur, güncelle. Yetki kontrolü.
- [ ] **E5** `shipments/delete.php` → POST tabanlı silme. Yetki kontrolü.
- [ ] **E6** Shipment CRUD'u uçtan uca test et.

---

## PHASE 5 — Inventory CRUD

- [ ] **F1** `classes/Inventory.php` → `getAll($userId)`, `getById($id)`, `create($data)`, `update($id, $data)`, `delete($id)` metodları.
- [ ] **F2** `inventory/index.php` → Envanter kalemlerini tablo olarak listele (kalem adı, miktar, birim, bağlı depo).
- [ ] **F3** `inventory/create.php` → Form (kalem adı, miktar, birim, depo seçimi).
- [ ] **F4** `inventory/edit.php` → Güncelleme formu. `last_updated_by` ve `updated_at` alanlarını set et.
- [ ] **F5** `inventory/delete.php` → POST tabanlı silme.
- [ ] **F6** Inventory CRUD'u uçtan uca test et.

---

## PHASE 6 — UI Polish & Doğrulama

- [ ] **G1** Tüm formlara Bootstrap doğrulama sınıfları ekle (`is-invalid`, `invalid-feedback`).
- [ ] **G2** Flash mesajları Bootstrap `alert` componenti ile göster (success / danger).
- [ ] **G3** Silme aksiyonlarına onay modalı ekle (Bootstrap Modal + küçük JS snippet).
- [ ] **G4** Dashboard'a özet kartlar ekle: toplam depo sayısı, aktif sevkiyat sayısı, toplam envanter kalemi.
- [ ] **G5** Tüm sayfalarda stilsiz (Bootstrap tarafından kapsanmayan) HTML elementi olmadığını kontrol et.
- [ ] **G6** Mobil uyumluluğu test et: navbar collapse çalışıyor mu? Tablolar küçük ekranda okunabilir mi?

---

## PHASE 7 — Güvenlik & Kod Kalitesi

- [ ] **H1** Tüm `$_GET` / `$_POST` değerleri için PDO prepared statements kullanıldığını doğrula (SQL injection yok).
- [ ] **H2** Yetki bypass testi: başka kullanıcının depo/sevkiyat/envanter ID'siyle URL manipülasyonu çalışıyor mu? Çalışıyorsa düzelt.
- [ ] **H3** Korumalı her sayfanın tepesinde `auth_guard.php` olduğunu kontrol et.
- [ ] **H4** `config/config.php`'nin Git geçmişinde olmadığını doğrula (`git log --all -- config/config.php`).
- [ ] **H5** Error reporting'i canlı ortam için kapat: `display_errors = 0`, hataları logla.

---

## PHASE 8 — Canlıya Alma (Hosting)

- [ ] **I1** Hosting cPanel'inde veritabanı ve kullanıcı oluştur.
- [ ] **I2** `config/config.php`'yi hosting bilgileriyle güncelle (lokal config'i commit etme).
- [ ] **I3** Dosyaları FTP/SFTP ile yükle (ya da Git pull + hosting terminali).
- [ ] **I4** SQL tablolarını hosting phpMyAdmin üzerinden oluştur.
- [ ] **I5** Canlıda kayıt → giriş → CRUD döngüsünü test et.
- [ ] **I6** `.htaccess` dosyası bulunmadığını doğrula (ödev kuralı).

---

## PHASE 9 — GitHub & Dokümantasyon

- [ ] **J1** `README.md` yaz: proje adı, kısa açıklama, özellik listesi, kurulum adımları (`config.example.php`'den `config.php` oluşturma dahil), canlı demo linki.
- [ ] **J2** Uygulamadan en az 2 ekran görüntüsü al (ör. dashboard + bir CRUD sayfası). `screenshots/` klasörüne ekle. README'de referans ver.
- [ ] **J3** 1–3 dakikalık demo videosu çek (Loom / OBS). YouTube'a yükle (listesiz olabilir) ya da Google Drive'da herkese açık yap. README'e bağlantıyı ekle.
- [ ] **J4** `AI.md` dosyasını oluştur. Yapay zeka araçlarıyla olan sohbetleri markdown formatında ekle (ödev kuralı).
- [ ] **J5** Son commit öncesi repo'yu tara: şifre, API anahtarı, hosting bilgisi kalmış mı?
- [ ] **J6** Repo'yu public yap. GitHub bağlantısını Ekampüs'e gönder.

---

## Hızlı Referans — Veritabanı Tabloları

| Tablo | Kritik Sütunlar |
|---|---|
| `users` | id, name, email, password_hash, created_at |
| `warehouses` | id, name, location, capacity_m3, created_by (FK→users), created_at |
| `shipments` | id, tracking_no, origin, destination, weight_kg, status ENUM('beklemede','yolda','teslim edildi'), warehouse_id (FK), created_by (FK), created_at |
| `inventory` | id, item_name, quantity, unit, warehouse_id (FK), last_updated_by (FK→users), updated_at |