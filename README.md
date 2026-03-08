# Eterna - Full-Stack Blog Yönetim Sistemi

Bu proje, Eterna iş başvurusu değerlendirme süreci için Laravel 12 ve Vue 3 (Composition API) kullanılarak geliştirilmiş kapsamlı, tek sayfalık (SPA) bir Blog Yönetim Sistemidir.

## 🚀 Kullanılan Teknolojiler ve Paketler

**Backend:**
- **Framework:** Laravel 12
- **Kimlik Doğrulama:** Laravel Sanctum (E-mail veya Telefon ile Auth)
- **Canlı Yayın (WebSockets):** Laravel Reverb
- **Görsel Yönetimi:** Spatie MediaLibrary v11
- **Loglama:** Spatie Activitylog v4 (Sadece değişen veriler loglanır)
- **Veritabanı:** MySQL 

**Frontend:**
- **Framework:** Vue 3 (Composition API)
- **CSS Framework:** Tailwind CSS
- **Yönlendirme (Routing):** Vue Router
- **Form Doğrulama:** Vee-Validate & Yup
- **Gelişmiş Seçim:** Vue Select (Arama yapılabilen kategoriler için)
- **Girdi Maskeleme:** Vue The Mask (Telefon numarası formatı için)

## 🛠️ Kurulum Adımları

Projeyi kendi ortamınızda çalıştırmak için aşağıdaki adımları sırasıyla izleyiniz:

**1. Projeyi Klonlayın ve Bağımlılıkları Yükleyin**
\`\`\`bash
git clone https://github.com/alpayyyy/ETERNA-TASK-2.git
cd eterna-api
composer install
npm install
\`\`\`

**2. Ortam Değişkenlerini Ayarlayın**
\`\`\`bash
cp .env.example .env
\`\`\`
`.env` dosyanızı açın ve yerel sunucunuza göre veritabanı ayarlarınızı (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) doldurun. 

Canlı yayın (Reverb) altyapısı için gereken ayarlar kopyaladığınız dosyada hazır olarak gelecektir. Reverb güvenlik anahtarlarını oluşturmak için şu komutu çalıştırın:
\`\`\`bash
php artisan reverb:install
\`\`\`

**3. Uygulama Anahtarını Oluşturun**
\`\`\`bash
php artisan key:generate
\`\`\`

**4. Veritabanını Oluşturun ve Test Verilerini Ekleyin (Seeder)**
\`\`\`bash
php artisan migrate:fresh --seed
\`\`\`
*Not: Bu komut tabloları oluşturacak ve sisteme test için yetkili hesaplar (örn: admin@eterna.net.tr / password), örnek kategoriler ve yazılar ekleyecektir.*

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

**Terminal 2: Vue & Vite Frontend Sunucusu**
\`\`\`bash
npm run dev
\`\`\`

**Terminal 3: Laravel Reverb (Canlı Yorum ve Bildirimler İçin)**
\`\`\`bash
php artisan reverb:start
\`\`\`

*(Opsiyonel) Zamanlanmış Görevler (1 Hafta Yorum Almayan Yazıları Silmek İçin)*
\`\`\`bash
php artisan schedule:work
\`\`\`

## 🧪 Postman Collection
Projedeki tüm endpoint'leri test edebilmeniz için hazırlanan Postman koleksiyonu projenin ana dizininde `Eterna_API.postman_collection.json` adıyla bulunmaktadır. Dosyayı Postman'e import ederek tüm istekleri hazır şekilde kullanabilirsiniz.

---
*Simplicity is the soul of efficiency. - Austin Freeman*