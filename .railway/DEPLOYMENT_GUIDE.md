# 🚀 Deploy SIMADU-KMC ke Railway

Panduan lengkap deploy Laravel ke Railway dengan auto-deploy dari GitHub.

## 📋 Prerequisites

- [x] Akun GitHub (sudah ada ✅)
- [ ] Akun Railway (gratis, daftar via GitHub)
- [x] Project sudah di GitHub (sudah ada ✅)

## 🎯 Step 1: Daftar Railway

1. Buka https://railway.app
2. Klik **"Login"** → pilih **"Login with GitHub"**
3. Authorize Railway akses ke GitHub
4. Selesai! Dapat $5 credit gratis/bulan

## 🚂 Step 2: Create New Project

1. Di Railway dashboard, klik **"New Project"**
2. Pilih **"Deploy from GitHub repo"**
3. Cari dan pilih **`Bgusss/simadu-kmc`**
4. Railway akan auto-detect Laravel dan mulai build

## 🗄️ Step 3: Add MySQL Database

1. Di project Railway, klik **"New"** → **"Database"** → **"Add MySQL"**
2. Railway akan create database dan generate credentials otomatis
3. Database sudah connected ke app ✅

## ⚙️ Step 4: Set Environment Variables

Klik tab **"Variables"** di Railway, tambahkan:

```env
# App Configuration
APP_NAME=SIMADU-KMC
APP_ENV=production
APP_DEBUG=false
APP_URL=https://simadu-kmc-production.up.railway.app
APP_KEY=base64:GENERATE_THIS_LATER

# Database (auto-filled by Railway MySQL)
# DB_CONNECTION=mysql
# DB_HOST=${{MYSQL.MYSQLHOST}}
# DB_PORT=${{MYSQL.MYSQLPORT}}
# DB_DATABASE=${{MYSQL.MYSQLDATABASE}}
# DB_USERNAME=${{MYSQL.MYSQLUSER}}
# DB_PASSWORD=${{MYSQL.MYSQLPASSWORD}}

# Locale
APP_LOCALE=id
APP_FALLBACK_LOCALE=en

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Gemini AI
GEMINI_API_KEY=your_gemini_api_key_here
GEMINI_MODEL=gemma-4-31b-it

# Mail (optional, untuk production)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=noreply@simadu-kmc.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 🔑 Generate APP_KEY

Setelah deploy pertama:
1. Buka Railway **"Terminal"**
2. Jalankan:
   ```bash
   php artisan key:generate --show
   ```
3. Copy hasilnya (contoh: `base64:xxxxxxxxxxxxx`)
4. Paste ke variable `APP_KEY`
5. Restart deployment

## 🌐 Step 5: Setup Domain (Optional)

### Opsi A: Railway Domain (Gratis)
Railway auto-generate domain:
- Format: `simadu-kmc-production.up.railway.app`
- Langsung bisa diakses ✅

### Opsi B: Custom Domain (Kalau punya)
1. Di Railway Settings → **"Domains"**
2. Klik **"Custom Domain"**
3. Masukkan domain kamu (contoh: `simadu-kmc.com`)
4. Update DNS records sesuai instruksi Railway

## 🚀 Step 6: First Deploy

Railway akan auto-deploy setelah setup. Progress:

```
1. Building... (2-3 menit)
   ├─ Install Composer dependencies
   ├─ Install NPM dependencies
   └─ Build assets (Vite)

2. Deploying... (1-2 menit)
   ├─ Run migrations
   ├─ Cache config/routes/views
   └─ Start PHP server

3. ✅ Live!
   └─ https://simadu-kmc-production.up.railway.app
```

## 🔄 Auto-Deploy Workflow

Setelah setup, **setiap push ke GitHub = auto-deploy:**

```bash
# Di local (laptop kamu)
git add .
git commit -m "feat: add new feature"
git push origin main

# Railway otomatis:
# 1. Detect push ke GitHub
# 2. Pull latest code
# 3. Build & deploy (2-5 menit)
# 4. ✅ Live dengan fitur baru
```

## 📊 Monitoring

### Check Status
- Railway Dashboard → lihat **"Deployments"**
- Hijau ✅ = Running
- Merah ❌ = Error (cek logs)

### View Logs
1. Klik **"View Logs"** di Railway
2. Lihat real-time logs
3. Debug kalau ada error

### Resource Usage
- Dashboard → **"Metrics"**
- Monitor CPU, RAM, Network
- Track $5 credit usage

## 🛠️ Troubleshooting

### Build Failed

**Error: Composer dependencies**
```bash
# Pastikan composer.lock ter-commit
git add composer.lock
git commit -m "chore: add composer.lock"
git push
```

**Error: NPM build failed**
```bash
# Test build di local dulu
npm run build

# Kalau error, fix lalu push
git add .
git commit -m "fix: resolve build error"
git push
```

### Database Migration Failed

1. Buka Railway Terminal
2. Run manual:
   ```bash
   php artisan migrate:fresh --seed --force
   ```

### APP_KEY Error

1. Generate di Terminal:
   ```bash
   php artisan key:generate --show
   ```
2. Copy ke Environment Variables
3. Restart deployment

### Out of Memory

Railway free tier: 512MB RAM. Kalau kurang:
- Optimize dependencies
- Disable unnecessary services
- Atau upgrade plan

## 🔒 Security Checklist

- [ ] `APP_DEBUG=false` di production
- [ ] `APP_ENV=production`
- [ ] APP_KEY sudah di-set
- [ ] Database credentials secure (auto by Railway)
- [ ] GEMINI_API_KEY di environment variables (jangan hardcode)
- [ ] .env tidak ter-commit (sudah di .gitignore ✅)

## 📝 Post-Deployment

### Seed Database (First Time)

```bash
# Di Railway Terminal
php artisan db:seed
```

Ini akan create:
- Default admin user
- OPD data
- Categories & sub-categories

### Default Login

**Admin:**
- URL: `https://your-app.railway.app/login`
- Email: `admin@kmc.go.id`
- Password: `password`

⚠️ **GANTI PASSWORD** setelah login pertama!

## 🔄 Development Workflow

### Branch Strategy

```bash
# Production branch (auto-deploy ke Railway)
main

# Development branch (testing di local)
develop

# Feature branches (eksperimen)
feature/nama-fitur
```

### Daily Workflow

```bash
# 1. Coding di branch develop
git checkout develop
# ... coding ...

# 2. Test di local
php artisan serve
# Browse: http://localhost:8000

# 3. Kalau OK, merge ke main
git checkout main
git merge develop
git push origin main

# 4. Railway auto-deploy (2-5 menit)
# 5. Check: https://your-app.railway.app
```

### Rollback Kalau Deploy Rusak

```bash
# Revert ke commit sebelumnya
git revert HEAD
git push origin main

# Atau rollback ke commit spesifik
git reset --hard abc1234
git push --force origin main
```

Railway akan auto-deploy versi lama.

## 💰 Cost Estimation (Free Tier)

**$5/bulan credit = ±500 jam runtime**

Breakdown:
- Always-on app: ~720 jam/bulan ❌ (melebihi)
- Sleep setelah 30 menit idle: ~100 jam/bulan ✅ (cukup)

**Tips hemat credit:**
- Railway free tier: app sleep otomatis setelah idle
- First request: wake up (±10 detik)
- Cocok untuk demo TA ✅

## 🎓 Untuk Demo TA

**Setup sebelum sidang:**
1. Deploy ke Railway ✅
2. Test semua fitur work ✅
3. Seed data dummy untuk demo ✅
4. Catat URL & login credentials ✅
5. Screenshot dashboard untuk laporan ✅

**Saat sidang:**
- Buka URL Railway (bukan localhost)
- Tunjukkan sistem online 24/7
- Dosen/penguji bisa akses kapanpun
- Professional impression 🚀

## 📞 Need Help?

- Railway Docs: https://docs.railway.app
- Railway Discord: https://discord.gg/railway
- Atau tanya saya lagi 😊

## 📚 Resources

- [Railway Laravel Template](https://railway.app/template/laravel)
- [Railway Environment Variables](https://docs.railway.app/deploy/variables)
- [Railway Custom Domains](https://docs.railway.app/deploy/exposing-your-app)

---

**Next:** Setelah deploy, update README.md dengan link production! 🎉
