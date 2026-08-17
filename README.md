# Sistem Informasi Monitoring Aduan Multi Channel KMC

<p align="center">
  <img src="public/images/kmc-logo-full.png" alt="KMC Logo" width="300">
</p>

<p align="center">
  <a href="https://github.com/Bgusss/simadu-kmc/actions"><img src="https://github.com/Bgusss/simadu-kmc/workflows/Laravel%20Tests/badge.svg" alt="Build Status"></a>
  <a href="https://github.com/Bgusss/simadu-kmc/blob/main/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel" alt="Laravel 13"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.3-777BB4?logo=php" alt="PHP 8.3"></a>
</p>

Sistem informasi berbasis web untuk mengelola aduan masyarakat dari berbagai channel (Facebook, Instagram, WhatsApp) dengan klasifikasi otomatis menggunakan AI dan sistem ticketing terintegrasi.

## 🎯 Fitur Utama

- **Multi-Channel Integration**: Sinkronisasi otomatis aduan dari Facebook mentions, Instagram mentions, dan form web
- **AI Classification**: Klasifikasi aduan otomatis menggunakan Gemini AI (gemma-4-31b-it) dengan akurasi 97.5%
- **Smart Ticketing System**: Sistem tiket dengan status tracking, eskalasi otomatis, dan disposisi ke OPD
- **Real-time Dashboard**: Monitoring aduan real-time dengan statistik dan visualisasi data
- **Duplicate Detection**: Deteksi otomatis aduan duplikat menggunakan content hash
- **Priority Management**: Sistem prioritas aduan (Normal, High, Urgent) dengan eskalasi otomatis
- **Multi-Role Access**: Role-based access untuk Admin dan OPD dengan permission granular

## 🛠️ Tech Stack

- **Framework**: Laravel 13 (PHP 8.3)
- **Frontend**: Vite, TailwindCSS 4, Alpine.js
- **Database**: MySQL
- **AI/ML**: Google Gemini API (gemma-4-31b-it)
- **Automation**: Playwright (Facebook/Instagram scraping)
- **Testing**: PHPUnit, Pest

## 📋 Requirements

- PHP >= 8.3
- Composer
- Node.js >= 18
- MySQL >= 8.0
- Git

## 🚀 Installation

### 1. Clone Repository

```bash
git clone https://github.com/Bgusss/simadu-kmc.git
cd simadu-kmc
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan sesuaikan konfigurasi:

```env
APP_NAME="SIMADU-KMC"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simadu_kmc
DB_USERNAME=root
DB_PASSWORD=

# Gemini AI Configuration
GEMINI_API_KEY=your_gemini_api_key
GEMINI_MODEL=gemma-4-31b-it

# Facebook/Instagram Credentials (for scraping)
FACEBOOK_EMAIL=your_fb_email
FACEBOOK_PASSWORD=your_fb_password
INSTAGRAM_USERNAME=your_ig_username
INSTAGRAM_PASSWORD=your_ig_password
```

### 4. Database Migration & Seeding

```bash
php artisan migrate --seed
```

### 5. Build Assets

```bash
npm run build
```

### 6. Run Development Server

```bash
# Opsi 1: Jalankan semua service sekaligus (recommended)
composer dev

# Opsi 2: Manual
php artisan serve
npm run dev
php artisan queue:listen
```

## 👥 Default Users

### Admin
- Email: `admin@kmc.go.id`
- Password: `password`

### OPD User (Example)
- Email: `dinas.pendidikan@kmc.go.id`
- Password: `password`

## 📱 Channel Sync Commands

### Facebook Mentions
```bash
# Sync Facebook post mentions
php artisan sync:facebook-post-mentions

# Sync Facebook comment mentions
php artisan sync:facebook-comment-mentions
```

### Instagram Mentions
```bash
php artisan sync:instagram-mentions
```

### Check Escalation (Auto Priority Update)
```bash
php artisan check:escalation
```

## 🧪 Testing

```bash
# Run all tests
composer test

# Run specific test
php artisan test --filter=TicketEditTest

# Run with coverage
composer test:coverage
```

## 📊 Project Structure

```
simadu-kmc/
├── app/
│   ├── Console/Commands/     # Artisan commands untuk sync channel
│   ├── Http/Controllers/     # Controllers (Admin, OPD, Public)
│   ├── Models/               # Eloquent models
│   └── Services/             # Business logic services
│       ├── AIClassificationService.php
│       ├── InstagramService.php
│       ├── TicketingService.php
│       └── WhatsAppService.php
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Seeders (OPD, Categories, Users)
├── playwright/               # Playwright scripts untuk scraping
├── resources/
│   ├── css/
│   ├── js/
│   └── views/                # Blade templates
└── routes/
    └── web.php               # Web routes
```

## 🤖 AI Classification

Sistem menggunakan Google Gemini AI untuk klasifikasi otomatis:

- **Model**: gemma-4-31b-it (FREE tier via Google AI Studio)
- **Akurasi**: 97.5%
- **False Positive**: <3%
- **Confidence Avg**: 92%
- **Rate Limits**: 15 RPM, Unlimited TPM, 1,500 RPD

### Kategori & Sub-kategori

Sistem dapat mengklasifikasi aduan ke dalam berbagai kategori OPD:

- Dinas Pendidikan
- Dinas Kesehatan
- Dinas Pekerjaan Umum
- Dinas Perhubungan
- Dinas Lingkungan Hidup
- Dan 10+ kategori lainnya

## 🔐 Security

- CSRF Protection (Laravel default)
- XSS Protection via Blade escaping
- SQL Injection protection via Eloquent ORM
- Role-based access control (RBAC)
- Rate limiting pada API endpoints
- Session encryption

## 🎓 Academic Context

Proyek ini merupakan Tugas Akhir (TA) untuk program D3 Teknologi Informasi, Politeknik Negeri Ketapang.

**Penulis**: Achmad Bagus Aprianto (3042023024)  
**Metodologi**: Agile Development  
**Tahun**: 2026

## 🤝 Contributing

Kami menerima kontribusi dari siapa saja! Silakan baca [CONTRIBUTING.md](CONTRIBUTING.md) untuk panduan lengkap.

### Quick Start untuk Kontributor

1. Fork repository ini
2. Buat branch baru (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'feat: add amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buat Pull Request

## 📄 License

Proyek ini dilisensikan di bawah [MIT License](LICENSE) - lihat file LICENSE untuk detail.

## 📋 Changelog

Lihat [CHANGELOG.md](CHANGELOG.md) untuk riwayat perubahan lengkap.

## 🔒 Security

Jika menemukan security vulnerability, silakan baca [SECURITY.md](SECURITY.md) untuk panduan pelaporan yang aman.

## 🙏 Acknowledgments

- Laravel Framework
- Google Gemini AI
- Playwright Browser Automation
- Politeknik Negeri Ketapang
- Ketapang Media Center (KMC)

## 📞 Contact

Untuk pertanyaan atau feedback, silakan hubungi:
- Email: bgzaprian@gmail.com
- GitHub: [@Bgusss](https://github.com/Bgusss)

## 🚀 Deployment

### Railway Hosting (Free)

Project ini bisa di-deploy gratis ke Railway dengan auto-deploy dari GitHub.

**Quick Deploy:**
1. Daftar di [Railway.app](https://railway.app) (login via GitHub)
2. New Project → Deploy from GitHub → pilih repo ini
3. Add MySQL database
4. Set environment variables
5. Deploy! 🎉

**Panduan Lengkap:** Lihat [Railway Deployment Guide](.railway/DEPLOYMENT_GUIDE.md)

**Branch Strategy:** Lihat [Branch Strategy Guide](.railway/BRANCH_STRATEGY.md)

---

<p align="center">Made with ❤️ for Ketapang Media Center</p>
