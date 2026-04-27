# BondoWisata 🏖️
**Sistem Rekomendasi Wisata Kabupaten Bondowoso**

Aplikasi web full-stack berbasis Laravel 11 untuk rekomendasi wisata resmi Kabupaten Bondowoso (Jawa Timur), mencakup data Restoran, Hotel, dan Ekonomi Kreatif dari Dinas Pariwisata (Dispar).

---

## 🚀 Fitur Utama

- **Landing Page** dengan statistik dan top rekomendasi SAW
- **Explore** dengan filter kategori, kecamatan, harga, dan sorting
- **Peta Interaktif** Google Maps dengan marker berwarna per kategori
- **Detail Tempat** dengan galeri foto, ulasan, sentimen badge, dan embed peta
- **Autentikasi** Email/Password + Google OAuth (Socialite)
- **Dashboard User** — ulasan, favorit, profil
- **Dashboard Admin** — CRUD tempat, import CSV, sentimen, SAW ranking
- **Analisis Sentimen** — Naive Bayes classifier Bahasa Indonesia
- **SAW Decision Support** — bobot kriteria yang dapat dikonfigurasi admin
- **REST API** — siap untuk Flutter mobile app (JWT Sanctum)

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 11 (PHP 8.3+) |
| Database | MySQL 8.0 |
| Frontend | Blade + Livewire 3 + Alpine.js |
| Styling | Tailwind CSS 3 |
| Auth | Laravel Sanctum + Spatie Permission |
| OAuth | Laravel Socialite (Google) |
| Queue | Database Queue |
| Debug | Laravel Telescope (dev) |

---

## 📋 Prasyarat

- PHP >= 8.3 dengan ekstensi: `openssl`, `pdo_mysql`, `mbstring`, `gd`, `zip`, `fileinfo`, `curl`, `intl`
- MySQL 8.0+
- Composer
- Node.js 18+ & NPM
- (Opsional) Google Maps API Key, Google OAuth credentials

---

## ⚙️ Instalasi

### 1. Clone & Install Dependensi

```bash
git clone https://github.com/your-repo/bondowisata.git
cd bondowisata
composer install
npm install
```

### 2. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_NAME=BondoWisata
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bondowisata
DB_USERNAME=root
DB_PASSWORD=your_password

GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

GOOGLE_MAPS_API_KEY=your_maps_api_key
```

### 3. Setup Database

```bash
# Buat database MySQL terlebih dahulu
mysql -u root -p -e "CREATE DATABASE bondowisata CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Jalankan migrasi dan seeder
php artisan migrate --seed
```

### 4. Build Frontend

```bash
npm run build
# Atau untuk development:
npm run dev
```

### 5. Storage Link

```bash
php artisan storage:link
```

### 6. Jalankan Aplikasi

```bash
php artisan serve
# atau tentukan port:
php artisan serve --port=8000
```

Buka: **http://localhost:8000**

---

## 👤 Akun Default (Seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@bondowisata.id | password123 |
| User | budi@example.com | password |

---

## 📡 API Endpoints

### Autentikasi
```
POST /api/auth/login
POST /api/auth/register
POST /api/auth/logout       [Bearer Token]
```

### Tempat (Public)
```
GET /api/tempat             ?kategori=restoran&kecamatan_id=&harga_min=&harga_max=&search=&sort_by=&per_page=15
GET /api/tempat/{id}
GET /api/rekomendasi        ?kategori=&per_page=15
GET /api/kecamatan
GET /api/kategori
```

### User (Authenticated)
```
GET    /api/user/profile
PUT    /api/user/profile
POST   /api/ulasan
PUT    /api/ulasan/{id}
DELETE /api/ulasan/{id}
GET    /api/favorit
POST   /api/favorit/{tempat_id}
DELETE /api/favorit/{tempat_id}
```

### Admin (Role: admin)
```
GET  /api/admin/sentimen/summary
GET  /api/admin/sentimen/keywords
POST /api/admin/saw/recalculate
POST /api/admin/tempat/import-csv
```

---

## 🧠 Algoritma SAW (Simple Additive Weighting)

Bobot kriteria default (dapat diubah admin):

| Kriteria | Bobot | Tipe |
|---|---|---|
| Rating | 40% | Benefit |
| Sentimen | 25% | Benefit |
| Harga | 15% | Cost |
| Popularitas | 10% | Benefit |
| Kebaruan | 10% | Benefit |

Formula: **Vᵢ = Σ(Wⱼ × Rᵢⱼ)**

---

## 🤖 Analisis Sentimen (Naive Bayes)

- Preprocessing: lowercase, hapus karakter khusus
- Tokenisasi kata
- Penghapusan stopwords Bahasa Indonesia (250+ kata)
- Stemming sederhana (prefix/suffix removal)
- Deteksi negasi (tidak, bukan, belum, dll)
- Klasifikasi: **Positif / Netral / Negatif**
- Auto-analisis saat ulasan dibuat (via Observer + Queue Job)

---

## 📁 Struktur Direktori Penting

```
app/
├── Http/Controllers/
│   ├── Auth/               # Login, Register, Google OAuth
│   ├── Admin/              # Dashboard Admin
│   ├── Api/                # REST API endpoints
│   ├── DashboardController.php
│   ├── ExploreController.php
│   └── HomeController.php
├── Models/                 # Eloquent Models (UUID)
├── Services/
│   ├── SentimentAnalysisService.php  # Naive Bayes
│   └── SawRecommendationService.php  # SAW DSS
├── Jobs/
│   └── AnalyzeSentimentJob.php       # Queue Job
└── Observers/
    └── UlasanObserver.php            # Auto-trigger sentiment

database/
├── migrations/             # 8 tabel utama
├── seeders/                # Data Bondowoso realistis
└── factories/              # Untuk testing

resources/views/
├── layouts/                # public.blade.php, dashboard.blade.php, auth.blade.php
├── public/                 # home, explore, detail, map
├── dashboard/              # index, profile, ulasan, favorit
└── admin/                  # dashboard, tempat, sentimen, saw

config/
└── saw.php                 # Konfigurasi bobot SAW

routes/
├── web.php                 # Public + Auth + Dashboard + Admin
├── api.php                 # REST API endpoints
└── console.php             # Scheduled SAW recalculation (daily)
```

---

## 🧪 Testing

```bash
php artisan test
# atau hanya unit tests:
php artisan test --testsuite=Unit
```

Test Coverage:
- `SentimentAnalysisServiceTest` — 10 test cases
- `SawRecommendationServiceTest` — 6 test cases

---

## 📥 Import CSV Dispar

Format kolom CSV (header wajib):

```
kode_dispar,nama_usaha,jenis_kategori,kecamatan,alamat,latitude,longitude,no_telepon,harga_min,harga_max,deskripsi,tgl_daftar
REST-001,Warung Bu Ani,restoran,Bondowoso Kota,"Jl. Diponegoro No. 1",-7.9092,113.8224,08123456789,10000,50000,"Warung makan enak",2023-01-15
```

---

## 🔄 Queue Worker

```bash
php artisan queue:work --queue=default
```

---

## 📅 Scheduler

Tambahkan ke crontab server:
```cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔍 Laravel Telescope (Dev)

Akses: **http://localhost:8000/telescope**

---

## 📄 Lisensi

MIT License — Data resmi dari Dinas Pariwisata Kabupaten Bondowoso.
