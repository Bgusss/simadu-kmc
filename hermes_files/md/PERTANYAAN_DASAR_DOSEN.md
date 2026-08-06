# PERTANYAAN DASAR DOSEN - CHEAT SHEET
**Sistem Informasi Manajemen Aduan Multi Channel KMC**

**Mahasiswa:** Achmad Bagus Aprianto (3042023024)  
**Tanggal:** 7 Juli 2026

---

## 🎯 TOP 15 PERTANYAAN DASAR (WAJIB HAFALKAN!)

### 1. **"Judul TA kamu apa?"**
**Jawaban:**
> "Pengembangan Aplikasi Aduan Multi-Channel Berbantuan AI untuk Klasifikasi, Deteksi Duplikasi, dan Prioritas Eskalasi pada Ketapang Media Center"

**Penjelasan singkat:**
- Sistem untuk KMC (Ketapang Media Center)
- Ambil aduan dari Facebook & Instagram otomatis
- Pakai AI untuk klasifikasi & deteksi spam
- Sistem tiket dengan SLA 24 jam

---

### 2. **"Metodologi penelitian apa yang kamu pakai?"**
**Jawaban:**
> "Saya pakai **metode Agile** dengan iterasi cepat untuk pengembangan sistem."

**Detail (jika ditanya lebih lanjut):**
- **Agile approach:** Planning → Design → Development → Testing → Deployment (iterasi)
- Cocok karena requirement bisa berubah saat diskusi dengan KMC
- Testing dilakukan di setiap iterasi (tidak menunggu akhir)

**PENTING:** Metodologi ini ada di **BAB III**, bukan BAB II!

---

### 3. **"Bahasa pemrograman apa yang kamu pakai?"**
**Jawaban:**
> "Saya pakai **3 bahasa:**
> 1. **PHP** (backend Laravel)
> 2. **JavaScript** (frontend Livewire + Vite, scraper Node.js)
> 3. **SQL** (query database MySQL)"

**Detail:**
- PHP 8.1+ (Laravel 11)
- JavaScript ES6+ (Node.js 20 untuk Playwright scraper)
- MySQL (database relational)

---

### 4. **"Framework apa yang kamu pakai?"**
**Jawaban:**
> "**Laravel 11** untuk backend, **Livewire** untuk frontend, dan **Tailwind CSS** untuk styling."

**Detail:**
- **Laravel 11:** Framework PHP full-stack (MVC pattern)
- **Livewire:** Framework reactivity (seperti Vue/React tapi tanpa API)
- **Tailwind CSS:** Utility-first CSS framework
- **Vite:** Build tool modern untuk compile asset

**Kenapa Laravel?**
- ✅ Dokumentasi lengkap
- ✅ Ekosistem besar (Eloquent ORM, Blade, Queue, Scheduler)
- ✅ Cocok untuk sistem CRUD-heavy seperti dashboard

---

### 5. **"Database apa yang kamu pakai?"**
**Jawaban:**
> "**MySQL 8.0** (RDBMS - Relational Database Management System)"

**Kenapa MySQL?**
- ✅ Open source & gratis
- ✅ Support Laravel Eloquent ORM dengan baik
- ✅ Stabil untuk data relational (users, complaints, tickets, OPDs)

**Alternatif yang TIDAK dipilih:**
- ❌ PostgreSQL (lebih kompleks, overkill untuk scope TA)
- ❌ MongoDB (NoSQL, tidak cocok untuk data relational)

---

### 6. **"Arsitektur sistem kamu pakai apa?"**
**Jawaban:**
> "**MVC (Model-View-Controller)**, arsitektur default Laravel."

**Penjelasan:**
- **Model:** Eloquent ORM (User, Complaint, Ticket, OPD)
- **View:** Blade template + Livewire component
- **Controller:** Logic routing & business logic

**Contoh flow:**
```
User akses dashboard → Route → Controller → Model (query DB) → View (Blade)
```

---

### 7. **"AI model apa yang kamu pakai?"**
**Jawaban:**
> "**Gemma 4 31B Instruction-Tuned (IT)** via **Google AI Studio API** (gratis)."

**Detail:**
- 31 miliar parameter
- Support bahasa Indonesia + dialek lokal
- **GRATIS** dengan quota: RPM=15, RPD=1,500, TPM=unlimited
- Akurasi sistem: **97.5%**

**Kenapa Gemma, bukan GPT-4 atau Gemini Flash?**
- ✅ **GRATIS** (cocok demo TA)
- ✅ **TPM unlimited** (Gemini Flash cuma 1M TPM)
- ✅ Akurasi sudah cukup tinggi (97.5%)

---

### 8. **"Teknik AI apa yang kamu pakai untuk klasifikasi?"**
**Jawaban:**
> "**NLP (Natural Language Processing)** dengan **LLM (Large Language Model)** untuk text classification."

**Proses klasifikasi:**
1. Input: teks aduan (contoh: "Jalan berlubang di Benua Kayong")
2. AI proses: extract kategori, sub-kategori, OPD
3. Output: JSON { kategori: "Infrastruktur", opd: "Dinas PUPR", confidence: 0.92 }

**Teknik tambahan:**
- **Named Entity Recognition (NER):** extract lokasi, nama, tanggal
- **Cosine Similarity:** deteksi duplikasi (aduan yang sama dikirim 2x)

---

### 9. **"Web scraping pakai apa?"**
**Jawaban:**
> "**Playwright** (framework automation browser) via **Node.js**."

**Kenapa Playwright, bukan API resmi?**
- ✅ **GRATIS** (API resmi FB/IG berbayar + review app lama)
- ✅ Cocok untuk **proof of concept** TA
- ✅ Bisa scrape FB post, FB comment, IG DM

**Trade-off:**
- ⚠️ Lebih lambat (10-15 detik per scrape)
- ⚠️ Brittle (bergantung struktur HTML Facebook/Instagram)
- ✅ **Solusi jangka panjang:** upgrade ke API resmi jika implementasi nyata Pemda

---

### 10. **"Sistem kamu real-time atau batch processing?"**
**Jawaban:**
> "**Batch processing** via Laravel Scheduler (cron job)."

**Detail:**
- Scraping dijalankan **setiap 5 menit** (otomatis via cron)
- Klasifikasi AI dijalankan setelah scraping selesai
- Dashboard update otomatis via Livewire (polling setiap 10 detik)

**Kenapa bukan real-time?**
- ⚠️ Real-time butuh webhook (API berbayar)
- ✅ Batch 5 menit sudah cukup untuk konteks KMC (aduan tidak urgent sekali)

---

### 11. **"Sistem kamu web-based atau mobile?"**
**Jawaban:**
> "**Web-based** (aplikasi berbasis web, diakses via browser)."

**Kenapa web?**
- ✅ **Platform-independent** (bisa diakses dari Windows, Mac, Android, iOS)
- ✅ Tidak perlu install aplikasi
- ✅ **Mobile responsive** (pakai Tailwind CSS, bisa dibuka di HP)

**Future work:** Bisa dikembangkan jadi mobile app (React Native)

---

### 12. **"Server deployment pakai apa?"**
**Jawaban (untuk demo TA):**
> "**Laragon** di laptop lokal (development environment)."

**Detail:**
- Laragon: portable web server (Apache + MySQL + PHP + Node.js dalam 1 paket)
- **Demo:** localhost (tidak deploy ke internet untuk TA)

**Jika ditanya production:**
> "Untuk production, bisa deploy ke **VPS** dengan stack **LEMP** (Linux + Nginx + MySQL + PHP)."

---

### 13. **"Sistem kamu butuh internet atau bisa offline?"**
**Jawaban:**
> "**Harus online** (butuh koneksi internet)."

**Alasan:**
- ✅ Scraping FB/IG butuh internet
- ✅ API Gemini (Google AI Studio) butuh internet
- ✅ Dashboard bisa diakses dari mana saja (cloud-based)

**Offline capability:** Database lokal masih bisa diakses (tapi scraping & AI tidak jalan)

---

### 14. **"Sistem kamu sudah dipakai KMC atau masih prototype?"**
**Jawaban jujur:**
> "Masih **prototype** untuk demo TA. Belum deploy production ke KMC."

**Status:**
- ✅ Sistem **berjalan lengkap** di lokal (demo ready)
- ✅ Sudah ditest dengan data real (200 samples)
- ⚠️ Belum deploy ke server KMC (butuh persetujuan resmi Pemda)

**Future:** Jika KMC approve, bisa deploy ke server mereka dengan upgrade:
- API resmi FB/IG
- Server production
- Security hardening

---

### 15. **"Apa kontribusi/novelty sistem kamu?"**
**Jawaban:**
> "Sistem ini **3-in-1:** scraping multi-channel + klasifikasi AI tanpa training + ticketing dengan SLA otomatis, yang **belum ada di penelitian sebelumnya**."

**Detail kontribusi:**
1. ✅ **Multi-channel:** ambil aduan dari FB + IG sekaligus (bukan single channel)
2. ✅ **LLM generatif:** tidak perlu training model (beda dari ML konvensional)
3. ✅ **Dialek lokal:** support istilah lokal Ketapang (tidak ada di sistem lain)
4. ✅ **SLA + eskalasi otomatis:** tiket auto-escalate jika >24 jam (tidak ada di sistem helpdesk biasa)

---

## 💡 TIPS MENJAWAB

### ✅ DO's (LAKUKAN):
1. **Jawab singkat dulu** (1-2 kalimat), tunggu dosen tanya lebih detail
2. **Sebutkan angka** jika ada (97.5% akurasi, 200 test samples, 5 menit interval)
3. **Jujur** tentang limitasi (masih prototype, scraping bukan real-time, dll)
4. **Siapkan backup:** screenshot, video demo (jika live demo gagal)

### ❌ DON'Ts (JANGAN):
1. ❌ Jawab bertele-tele (dosen bosan)
2. ❌ Bilang "saya lupa" (hafalkan cheat sheet ini!)
3. ❌ Mengklaim 100% sempurna (pasti ada limitasi)
4. ❌ Panik jika tidak tahu → bilang "Saya belum implement, tapi idenya bisa pakai X"

---

## 📚 HAFALKAN 5 ANGKA INI

1. **97.5%** = Akurasi klasifikasi AI
2. **200** = Jumlah test samples
3. **24 jam** = SLA tiket
4. **32** = Jumlah kategori + OPD
5. **5 menit** = Interval scraping otomatis

---

## 🎓 FORMULA JAWABAN CEPAT

**Template jawaban 90% pertanyaan:**

```
Teknologi X: [nama tool/framework]
Alasan pakai X: [1 kelebihan utama]
Kenapa tidak pakai Y: [1 alasan singkat]
```

**Contoh:**
> "Database: MySQL. Alasan: stabil untuk data relational. Kenapa tidak pakai MongoDB? Karena data saya relational (user-ticket-OPD), bukan document-based."

---

## ⚡ QUICK REFERENCE

| Pertanyaan | Jawaban Singkat |
|------------|----------------|
| Metodologi? | **Agile** (iterative) |
| Bahasa? | **PHP + JavaScript + SQL** |
| Framework? | **Laravel 11 + Livewire + Tailwind** |
| Database? | **MySQL 8.0** |
| Arsitektur? | **MVC** (Model-View-Controller) |
| AI Model? | **Gemma 4 31B IT** (Google AI Studio) |
| Scraping? | **Playwright + Node.js** |
| Real-time? | **Tidak** (batch 5 menit) |
| Platform? | **Web-based** (mobile responsive) |
| Akurasi? | **97.5%** (200 samples) |
| SLA? | **24 jam** (auto-escalate) |
| Status? | **Prototype** (demo TA) |

---

**CETAK HALAMAN INI & BAWA KE SIDANG!** 📄

Baca 3x sebelum sidang → 95% pertanyaan dasar sudah terjawab! 🚀
