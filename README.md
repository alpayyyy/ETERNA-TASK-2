# Eterna - Blog Yönetim Sistemi API

Bu proje, Eterna iş başvurusu değerlendirme süreci için Laravel 12 kullanılarak geliştirilmiş kapsamlı bir Blog Yönetim Sistemi REST API'sidir.

## 🚀 Kullanılan Teknolojiler ve Paketler
- **Framework:** Laravel 12
- **Kimlik Doğrulama:** Laravel Sanctum (API Token Auth)
- **Canlı Yayın (WebSockets):** Laravel Reverb
- **Görsel Yönetimi:** Spatie MediaLibrary v11
- **Loglama:** Spatie Activitylog v4
- **Veritabanı:** MySQL

## 🛠️ Kurulum Adımları

Projeyi kendi ortamınızda çalıştırmak için aşağıdaki adımları sırasıyla izleyiniz:

**1. Projeyi Klonlayın ve Bağımlılıkları Yükleyin**
\`\`\`bash
git clone <sizin-github-repo-linkiniz>
cd eterna-api
composer install
\`\`\`

**2. Ortam Değişkenlerini Ayarlayın**
\`\`\`bash
cp .env.example .env
\`\`\`
`.env` dosyanızı açın ve veritabanı ayarlarınızı (DB_DATABASE, DB_USERNAME, DB_PASSWORD) kendi yerel sunucunuza göre düzenleyin.

**3. Uygulama Anahtarını Oluşturun**
\`\`\`bash
php artisan key:generate
\`\`\`

**4. Veritabanını Oluşturun ve Test Verilerini Ekleyin (Seeder)**
\`\`\`bash
php artisan migrate --seed
\`\`\`
*Not: Bu komut tabloları oluşturacak ve sisteme test için 1 Admin, 1 Yazar, 1 Kullanıcı, örnek kategoriler ve yazılar ekleyecektir.*

**5. Medya Dosyaları İçin Sembolik Bağlantı Oluşturun**
Kapak görsellerinin dışarıdan erişilebilir olması için gereklidir:
\`\`\`bash
php artisan storage:link
\`\`\`

## 🏃‍♂️ Uygulamayı Çalıştırma

Projeyi tam kapasiteyle test edebilmek için 3 ayrı terminal penceresinde aşağıdaki komutları çalıştırmanız gerekmektedir:

**Terminal 1: Geliştirme Sunucusu**
\`\`\`bash
php artisan serve
\`\`\`

**Terminal 2: Laravel Reverb (Canlı Yorum ve Bildirimler İçin)**
\`\`\`bash
php artisan reverb:start
\`\`\`

**Terminal 3: Zamanlanmış Görevler (1 Hafta Yorum Almayan Yazıları Silmek İçin)**
\`\`\`bash
php artisan schedule:work
\`\`\`

## 🧪 Postman Collection
Projedeki tüm endpoint'leri test edebilmeniz için hazırlanan Postman koleksiyonu projenin ana dizininde `Eterna_API.postman_collection.json` adıyla bulunmaktadır. Dosyayı Postman'e import ederek tüm istekleri hazır şekilde kullanabilirsiniz.

---
*Simplicity is the soul of efficiency. - Austin Freeman*