# ANALISIS SUB-BAB YANG KURANG DI BAB II
**Tanggal:** 8 Juli 2026, 14:33 WIB  
**Mahasiswa:** Achmad Bagus Aprianto (3042023024)

---

## ✅ SUB-BAB YANG SUDAH ADA (39 SUB-BAB)

### **Sudah lengkap:**
- ✅ 2.2.24 Laragon (sudah ada!)
- ✅ 2.2.1-2.2.39 (39 sub-bab)

---

## ⚠️ SUB-BAB YANG MUNGKIN KURANG

Berdasarkan tech stack & metodologi proyek SIMADU-KMC:

### **KATEGORI 1: METODOLOGI PENGEMBANGAN**

#### **1. Agile / Scrum** ⭐ (PRIORITAS TINGGI)
**Alasan:**
- Proyek ini dikembangkan pakai metodologi apa?
- Waterfall? Agile? Scrum?
- Biasanya TA harus jelaskan metodologi pengembangan

**Posisi:** Tambahkan setelah 2.2.1 Sistem Informasi
**Nomor baru:** 2.2.2 Metodologi Agile / Scrum

**Isi minimum:**
- Definisi Agile/Scrum
- Sprint, backlog, user story
- Kenapa cocok untuk proyek ini

---

#### **2. Software Development Life Cycle (SDLC)** ⭐
**Alasan:**
- Siklus hidup pengembangan software
- Tahapan: planning, analysis, design, implementation, testing, deployment
- Standar TA harus ada

**Posisi:** Setelah metodologi Agile
**Nomor baru:** 2.2.3 SDLC

---

### **KATEGORI 2: TEKNOLOGI WEB & DEPLOYMENT**

#### **3. Web Server (Apache / Nginx)** 🔧
**Alasan:**
- Laragon pakai Apache atau Nginx?
- Server web apa yang dipilih?

**Posisi:** Setelah 2.2.24 Laragon
**Nomor baru:** 2.2.25 Web Server

**Catatan:** Jika pakai bawaan Laragon (Apache), bisa skip atau gabung ke 2.2.24

---

#### **4. Git / Version Control** 🔧
**Alasan:**
- Proyek pakai Git untuk version control?
- Repository ada di GitHub/GitLab?
- Standar development modern harus ada

**Posisi:** Setelah Visual Studio Code
**Nomor baru:** 2.2.26 Git Version Control

---

#### **5. Composer** ⚠️ (SUDAH ADA IMPLISIT?)
**Alasan:**
- Dependency manager PHP untuk Laravel
- Biasanya wajib disebutkan

**Status:** Cek apakah sudah disebutkan di 2.2.16 Laravel atau 2.2.17 PHP?
**Posisi jika tambah:** Setelah 2.2.17 PHP
**Nomor baru:** 2.2.18 Composer

---

#### **6. npm / Package Manager** ⚠️
**Alasan:**
- Vite pakai npm untuk install dependencies
- Frontend dependencies (Tailwind, Livewire) pakai npm

**Status:** Cek apakah sudah disebutkan di 2.2.31 Vite?
**Posisi jika tambah:** Setelah 2.2.30 Node.js
**Nomor baru:** 2.2.31 npm (Node Package Manager)

---

### **KATEGORI 3: TESTING & QUALITY ASSURANCE**

#### **7. Unit Testing / Feature Testing** 🔧
**Alasan:**
- Laravel pakai PHPUnit untuk testing
- Selain Black Box, ada White Box testing?

**Posisi:** Setelah 2.2.33 Black Box Testing
**Nomor baru:** 2.2.34 Unit Testing

**Catatan:** Jika fokus Black Box saja, bisa skip

---

### **KATEGORI 4: API & INTEGRASI**

#### **8. RESTful API / API Architecture** ⚠️
**Alasan:**
- Sistem multi-channel (Facebook, Instagram) pakai API?
- Backend-frontend komunikasi pakai REST API?

**Posisi:** Setelah 2.2.4 Multi-Channel
**Nomor baru:** 2.2.5 RESTful API

**Catatan:** Jika sistem pure monolith (no API), bisa skip

---

#### **9. JSON (JavaScript Object Notation)** 🔧
**Alasan:**
- Format data untuk API
- Livewire, Playwright, Google AI Studio pakai JSON

**Posisi:** Setelah RESTful API (jika ada)
**Nomor baru:** 2.2.6 JSON

---

### **KATEGORI 5: SECURITY & AUTHENTICATION**

#### **10. Authentication & Authorization** ⚠️
**Alasan:**
- Sistem login pakai apa?
- Laravel Breeze? Jetstream? Manual?
- RBAC sudah ada (2.2.32), tapi Authentication belum

**Posisi:** Sebelum 2.2.32 RBAC
**Nomor baru:** 2.2.31 Authentication & Authorization

---

#### **11. Middleware (Laravel)** ⚠️
**Alasan:**
- Laravel middleware untuk auth, CORS, throttling
- Sudah disebutkan di 2.2.16 Laravel?

**Status:** Cek apakah sudah dijelaskan detail
**Posisi jika tambah:** Setelah 2.2.16 Laravel
**Nomor baru:** 2.2.17 Middleware

---

### **KATEGORI 6: DATABASE & ORM**

#### **12. Eloquent ORM** ⚠️ (SUDAH ADA IMPLISIT?)
**Alasan:**
- Laravel pakai Eloquent untuk database
- Sudah disebutkan di 2.2.16 Laravel?

**Status:** Cek apakah perlu sub-bab terpisah
**Posisi jika tambah:** Setelah 2.2.18 MySQL
**Nomor baru:** 2.2.19 Eloquent ORM

---

#### **13. Migration & Seeder** 🔧
**Alasan:**
- Laravel migration untuk struktur database
- Seeder untuk data dummy testing

**Posisi:** Setelah Eloquent ORM (jika ada)
**Nomor baru:** 2.2.20 Migration & Seeder

---

### **KATEGORI 7: FRONTEND & UI/UX**

#### **14. Responsive Web Design** 🔧
**Alasan:**
- Sistem multi-channel harus responsive
- Tailwind CSS untuk responsive design

**Posisi:** Setelah 2.2.23 Tailwind CSS
**Nomor baru:** 2.2.24 Responsive Web Design

---

### **KATEGORI 8: AI & NLP (TAMBAHAN)**

#### **15. Prompt Engineering** ⚠️
**Alasan:**
- LLM pakai prompt untuk klasifikasi aduan
- Teknik prompt engineering untuk akurasi tinggi

**Posisi:** Setelah 2.2.15 LLM
**Nomor baru:** 2.2.16 Prompt Engineering

---

#### **16. Few-Shot Learning** 🔧
**Alasan:**
- LLM pakai few-shot examples untuk klasifikasi
- Alternatif untuk fine-tuning

**Posisi:** Setelah Prompt Engineering (jika ada)
**Nomor baru:** 2.2.17 Few-Shot Learning

---

## 🎯 REKOMENDASI PRIORITAS

### **WAJIB TAMBAHKAN (PRIORITAS TINGGI):** ⭐⭐⭐

1. **Agile / Scrum / SDLC** (Metodologi pengembangan)
   - **Alasan:** WAJIB untuk TA, dosen pasti tanya
   - **Posisi:** 2.2.2 (setelah Sistem Informasi)

2. **Composer** (jika belum ada)
   - **Alasan:** Dependency manager Laravel, sering ditanya dosen
   - **Posisi:** Setelah 2.2.17 PHP

3. **Authentication & Authorization** (jika belum detail)
   - **Alasan:** Sistem punya login, harus dijelaskan
   - **Posisi:** Sebelum 2.2.32 RBAC

---

### **SANGAT DIREKOMENDASIKAN (PRIORITAS MEDIUM):** ⭐⭐

4. **Git / Version Control**
   - **Alasan:** Standar development modern
   - **Posisi:** Setelah Visual Studio Code

5. **RESTful API** (jika sistem pakai API)
   - **Alasan:** Multi-channel integration pakai API
   - **Posisi:** Setelah Multi-Channel

6. **Prompt Engineering** (jika LLM pakai prompt detail)
   - **Alasan:** Teknik penting untuk akurasi AI
   - **Posisi:** Setelah LLM

---

### **OPSIONAL (PRIORITAS LOW):** ⭐

7. npm / Package Manager
8. JSON
9. Eloquent ORM (jika belum detail)
10. Migration & Seeder
11. Responsive Web Design
12. Unit Testing
13. Few-Shot Learning
14. Web Server

---

## 📊 RINGKASAN

### **SUB-BAB SAAT INI:** 39 sub-bab

### **REKOMENDASI TAMBAHAN:**
- **WAJIB:** 3 sub-bab (Agile, Composer, Auth)
- **SANGAT DIREKOMENDASIKAN:** 3 sub-bab (Git, REST API, Prompt Eng)
- **OPSIONAL:** 8 sub-bab

### **TOTAL JIKA SEMUA DITAMBAH:** 39 + 14 = **53 sub-bab** ❌ (TERLALU BANYAK!)

### **REKOMENDASI REALISTIS:**
**39 + 3 (wajib) + 2 (sangat direkomendasikan) = 44 sub-bab** ✅

---

## ❓ PERTANYAAN UNTUK ANDA

Sebelum saya tambahkan sub-bab, tolong jawab:

1. **Metodologi pengembangan apa yang Anda pakai?**
   - [ ] Agile
   - [ ] Scrum
   - [ ] Waterfall
   - [ ] Prototype
   - [ ] Lainnya: ______

2. **Sistem pakai API atau monolith?**
   - [ ] REST API (backend-frontend terpisah)
   - [ ] Monolith (Blade full-stack)
   - [ ] Hybrid (Livewire)

3. **Authentication pakai apa?**
   - [ ] Laravel Breeze
   - [ ] Laravel Jetstream
   - [ ] Manual (Auth controller sendiri)
   - [ ] Belum ada auth (sistem tanpa login)

4. **Proyek pakai Git?**
   - [ ] Ya (GitHub/GitLab/Bitbucket)
   - [ ] Tidak (folder lokal saja)

5. **LLM pakai prompt engineering detail?**
   - [ ] Ya (few-shot examples, system prompt detail)
   - [ ] Tidak (prompt sederhana)

---

## 🚀 NEXT STEPS

**Setelah Anda jawab 5 pertanyaan di atas, saya akan:**

1. ✅ Tambahkan 3-5 sub-bab yang WAJIB
2. ✅ Renumber semua sub-bab (2.2.1 - 2.2.X)
3. ✅ Tulis isi sub-bab baru (definisi + sitasi + fitur)
4. ✅ Update DRAFT_BAB_II_BAHASA_SEDERHANA.md
5. ✅ Siap copy ke Word!

---

**JAWAB 5 PERTANYAAN DI ATAS DULU, BARU SAYA TAMBAHKAN SUB-BAB!** 📋

---

**END OF ANALYSIS**
