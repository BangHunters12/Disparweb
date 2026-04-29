# BondoWisata

Sistem rekomendasi wisata Kabupaten Bondowoso berbasis Laravel. Aplikasi ini berisi halaman publik, dashboard pengguna, dashboard admin, REST API untuk mobile app, rekomendasi SAW, dan analisis sentimen ulasan.

## Tech Stack

- PHP 8.3+
- Laravel 13
- SQLite untuk development lokal, MySQL dapat dipakai untuk deployment
- Blade, Alpine.js, Vite, Tailwind CSS 4
- Laravel Sanctum, Socialite, Spatie Permission
- Laravel Telescope dan Pail untuk debugging lokal

## Quick Start

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
npm run dev:all
```

Buka `http://127.0.0.1:8000`.

## Developer Commands

```bash
npm run dev:all       # server, queue, logs, and Vite
npm run serve         # Laravel server only
npm run dev           # Vite only
npm run queue         # queue worker
npm run logs          # Laravel Pail logs
npm run clear         # clear framework caches
npm run routes        # route list
npm run fresh         # migrate:fresh --seed
npm run test          # PHP tests
npm run format:check  # Pint check
npm run format        # Pint fix
```

More detail is in `docs/DEVELOPMENT.md`.

## Main Areas

- `resources/views/landing.blade.php`: main `/` landing page, written as normal Blade.
- `resources/css/landing.css`: landing page visual styling.
- `resources/js/landing.js`: landing page GSAP, ScrollTrigger, and Lenis animations.
- `public/images/landing`: static assets required by the landing page.
- `resources/views/public`: public Laravel pages.
- `resources/views/dashboard`: authenticated user pages.
- `resources/views/admin`: admin dashboard pages.
- `routes/web.php`: web routes.
- `routes/api.php`: Sanctum API routes.
- `app/Services`: SAW recommendation and sentiment analysis services.

## Seeded Accounts

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@bondowisata.id` | `password123` |
| User | `budi@example.com` | `password` |

## API Overview

Public:

```text
POST /api/auth/login
POST /api/auth/register
GET  /api/tempat
GET  /api/tempat/{id}
GET  /api/rekomendasi
GET  /api/kecamatan
GET  /api/kategori
```

Authenticated:

```text
POST   /api/auth/logout
GET    /api/user/profile
PUT    /api/user/profile
POST   /api/ulasan
PUT    /api/ulasan/{id}
DELETE /api/ulasan/{id}
GET    /api/favorit
POST   /api/favorit/{tempatId}
DELETE /api/favorit/{tempatId}
```

Admin:

```text
GET  /api/admin/sentimen/summary
GET  /api/admin/sentimen/keywords
POST /api/admin/saw/recalculate
POST /api/admin/tempat/import-csv
```
