# Yapay Zeka (AI) İle Proje Geliştirme Süreci

Bu dosya, projenin tasarlanma, canlıya alınma ve geliştirme aşamalarında Antigravity (AI) ajanıyla yapılan örnek komut ve cevap diyaloglarını içermektedir.

---

### KOMUT 1: Proje İskeletinin Çıkartılması

**Kullanıcı:**
bu benim web tabanlı proje ödevim. Esnek bir yapı kurmalıyım. githuba da yükleyeceğim için standartlara uygun olmalı.
bunun için antigravity agentıma prompt verir misin? bize kod vermesin sadece iskelet ve yapılacaklar listesi çıkartsın, klasör sistemini ayarlasın yeterli.
Benim proje konum "Lojistik depo yönetim sistemi".

**AI Cevabı:**
Aşağıdaki prompt'u antigravity agent'ına ver:

```text
You are a senior full-stack developer acting as a project scaffolding agent.

Your task: Generate a complete project skeleton and TODO list for a PHP + MySQL web application.
Do NOT write any implementation code. Only produce:
1. Folder/file structure (tree format)
2. Per-file TODO comments (what each file must implement)
3. A database schema outline (table names, columns, types — no SQL, just a structured list)
4. A ordered task checklist the developer will follow

---

PROJECT BRIEF:
- Topic: Lojistik Depo Yönetim Sistemi (Logistics Warehouse Management System)
- Stack: Plain PHP (no frameworks, no external PHP libraries), MySQL/MariaDB, Bootstrap 5, vanilla JS
- Hosting: Shared hosting (cPanel-style), no .htaccess allowed
- Version control: GitHub (sensitive credentials must be excluded via config pattern)

MANDATORY FEATURES (graded equally):
1. User registration (with password_hash)
2. Login / logout (PHP sessions, no plain cookies)
3. CREATE — logged-in user adds a record to a domain table (not the users table)
4. READ — list all records belonging to/visible to the user
5. UPDATE — edit an existing record
6. DELETE — remove a record
7. All UI elements must be styled by Bootstrap 5 (no unstyled elements)
8. README.md with 2+ screenshots and a YouTube/Drive video link
9. Application must run on live hosting without errors

DOMAIN ENTITIES (design around these):
- Users (auth)
- Warehouses (depo): name, location, capacity_m3, created_by, created_at
- Shipments (sevkiyat): tracking_no, origin, destination, weight_kg, status (enum: beklemede/yolda/teslim edildi), warehouse_id, created_by, created_at
- Inventory items (envanter): item_name, quantity, unit, warehouse_id, last_updated_by, updated_at
	
CONSTRAINTS:
- Backend: 100% plain PHP, developer may define their own PHP classes
- No .htaccess
- Passwords: password_hash() / password_verify() only
- Sessions: session_start() based auth guard on every protected page
- Config file must be gitignored (provide config.example.php instead)
- Bootstrap 5 CDN is acceptable

OUTPUT FORMAT:
Section 1 — Folder & File Tree (use tree notation, annotate each file with one-line purpose)
Section 2 — Per-file TODO list (bullet points, implementation hints, no actual code)
Section 3 — Database Schema Outline (table → columns → type → constraints, plain text)
Section 4 — Ordered Developer Checklist (numbered, grouped by phase: Setup → Auth → Domain CRUD → UI Polish → Deployment → GitHub)

Be precise. Be exhaustive. Do not write PHP, HTML, SQL, or JS code.
```

---

### KOMUT 2: Projeyi Canlıya Alma Süreci

**Kullanıcı:**
Bu projeyi nasıl canlıya alırım yardımcı ol.

**AI Cevabı:**
Şimdi Yapmanız Gereken Adımlar

**Adım 1: Lokaldeki Veritabanını Dışa Aktar**
- Tarayıcıdan `http://localhost/phpmyadmin` adresine gidin
- Sol menüden `depo_yonetim` veritabanını seçin
- Üst menüden "Dışa Aktar" (Export) sekmesine tıklayın
- "Hızlı" (Quick) seçeneği yeterli, format SQL olsun
- "Git" (Go) butonuna basın → `.sql` dosyası bilgisayarınıza inecek

**Adım 2: Hosting'deki Veritabanına İçe Aktar**
- Tarayıcıdan `http://95.130.171.20/phpmyadmin` adresine gidin
- Giriş bilgileri:
  - Kullanıcı: *************
  - Şifre: ************
- Sol menüden `dbstorage*******` veritabanını seçin
- Üst menüden "İçe Aktar" (Import) sekmesine tıklayın
- Az önce indirdiğiniz `.sql` dosyasını seçin
- "Git" (Go) butonuna basın
- Tabloların (users, warehouses, shipments, inventory) oluştuğunu doğrulayın

**Adım 3: config.php Dosyasını Güncelle**
Hosting'e yüklemeden önce `config/config.php`'yi şu bilgilerle güncelleyin:

| Ayar | Lokaldeki | Hosting'deki |
| --- | --- | --- |
| DB_HOST | localhost | localhost (aynı kalır) |
| DB_NAME | depo_yonetim | dbstorage********* |
| DB_USER | root | ************* |
| DB_PASS | (boş) | ************* |

**Adım 4: FileZilla ile Dosyaları Yükle**
- FileZilla'yı indirin (https://filezilla-project.org) ve kurun
- Üst kısımdaki bağlantı alanlarına yazın:
  - Sunucu: `95.130.171.20`
  - Kullanıcı adı: `********`
  - Şifre: `*********`
  - Port: `21`
- "Hızlı Bağlan" butonuna basın
- Sağ tarafta (uzak sunucu) `public_html` klasörüne girin
- Sol tarafta (bilgisayarınız) proje klasörünüze gidin: `C:\Users\malib\Desktop\Projects\Depo_Yönetim`
- Şu dosya/klasörleri seçip sağ tarafa sürükleyin:
  - 📁 auth/
  - 📁 classes/
  - 📁 config/ (güncellenmiş config.php ile)
  - 📁 includes/
  - 📁 inventory/
  - 📁 warehouses/
  - 📁 shipments/
  - 📁 assets/
  - 📄 dashboard.php
  - 📄 index.php

*Yüklemeyin:*
❌ `.git/`
❌ `README.md`, `TASKS.md`
❌ `screenshots/`

**Adım 5: Test Et**
Tarayıcıdan gidin: `http://95.130.171.20/~st24360859078/`

Kontrol edin:
✅ Ana sayfa açılıyor mu?
✅ Kayıt olabiliyorum mu?
✅ Giriş yapabiliyorum mu?
✅ Depo/Sevkiyat/Envanter ekleyebiliyorum mu?
✅ Düzenleme ve silme çalışıyor mu?
✅ Çıkış yapabiliyorum mu?

Sorun yaşarsan bana söyle, adım adım çözeriz! 🚀

---

### KOMUT 3: GitHub Üzerinden Özellik Bazlı Ayrı Commit Atmak

**Kullanıcı:**
kral githubla alakalı bir şey soracağım bazen birden çok dosyayı değiştiriyoruz veya farklı konularda değişiklik yapıyorum fakat aralarda git push atmıyorum en son yapıyorum bazen.böyle olunca da commitler tüm hepsinde aynı oluyor.ben ayrı ayrı commit atmak istiyorum bunu nasıl yaparım

**AI Cevabı:**
Harika bir soru! Birçok geliştiricinin başta yaşadığı yaygın bir durumdur. Bunun çözümü **`git add .`** (nokta) alışkanlığından vazgeçip, sadece o an commit etmek istediğin dosyaları seçmekten geçer. 

Şu an senin projende de tam olarak bu durumu yaşıyoruz. Örneğin sen şu an hem "Navigasyon hatasını" çözdün, hem "Arama çubuğu" ekledin, hem de "Sevkiyat-Stok entegrasyonu" yaptın. Hepsini tek bir commit yaparsan hepsi birbirine girer.

İşte bunları ayrı ayrı commit etmenin yolu:

**1. Adım: Nelerin değiştiğine bak**
Önce terminale şu komutu yazarak hangi dosyaların değiştiğini gör:
```bash
git status
```
Sana kırmızı renkte değişen dosyaları listeleyecek.

**2. Adım: İlk özelliği (örneğin sadece Navigasyon Fix) sahneye al (Add)**
Sadece `header.php` dosyasını değiştirdiğimiz kısım navigasyondu. Sadece onu seçiyoruz:
```bash
git add includes/header.php
```
*Not: Eğer `git status` yazarsan, bu sefer sadece header.php'nin yeşil olduğunu göreceksin.*

**3. Adım: Seçilen dosyalara Commit at**
```bash
git commit -m "fix: navbar giriş/kayıt linkleri hosting ortamına uygun düzeltildi"
```
*(Bu sayede ilk commitin atıldı ve **sadece** header.php'yi içeriyor!)*

**4. Adım: İkinci özelliği (Arama Çubuğu) seç ve Commit at**
Şimdi arama çubuğu ile ilgili olan dosyaları seçelim:
```bash
git add includes/footer.php
git add warehouses/index.php
git add inventory/index.php
# (Sonra commitini at)
git commit -m "feat: tablo listeleri için anlık javascript arama çubuğu eklendi"
```

**5. Adım: Kalan özelliği (Sevkiyat/Stok Entegrasyonu) seç ve Commit at**
```bash
git add database.sql classes/Shipment.php shipments/
git commit -m "feat: sevkiyatlara yön eklendi ve envanter stok entegrasyonu yapıldı"
```

**6. Adım: En son tek seferde Push at!**
Commitleri bilgisayarında ayrı ayrı paketledin. Artık hepsini Github'a tek seferde gönderebilirsin:
```bash
git push
```
Github'a girdiğinde bu 3 commitin de **ayrı ayrı mesajlarla** ve **sadece kendi dosyalarıyla** alt alta listelendiğini göreceksin! 🚀

---

**💡 Ekstra İpucu (İleri Seviye):**  
Diyelim ki tek bir dosyanın (`Shipment.php` gibi) hem yukarısında hem aşağısında farklı konularla ilgili kod değiştirdin ve bunları da ayırmak istiyorsun. O zaman:
```bash
git add -p Shipment.php
```
yazarsın. Git sana değişen kodları parça parça gösterip *"Bunu bu commite alayım mı? (y/n)"* diye sorar. `y` basarak sadece istediğin satırları commit'e dahil edebilirsin.

Bu yöntemle kendi projeni de şu an terminalden (veya VSCode'un sol menüsündeki Git sekmesinden dosyaların yanındaki `+` tuşuna basarak) ayrı ayrı commit'leyebilirsin.
