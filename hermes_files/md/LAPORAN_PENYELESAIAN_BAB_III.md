# LAPORAN PENYELESAIAN BAB III
**Tanggal:** 2026-07-13  
**Status:** ✓ SELESAI

---

## RINGKASAN PENGERJAAN

### Dokumen Utama
- **File:** `.hermes/md/BAB_III_METODOLOGI_DAN_PERANCANGAN.md`
- **Ukuran:** 32.990 karakter, 303 baris
- **Format:** Markdown dengan tabel dan referensi gambar

### Struktur BAB III (Sesuai Panduan TA §4.2.3)

#### 3.1 Metodologi Penelitian
- ✓ 3.1.1 Metode Penelitian (R&D, observasi + studi literatur)
- ✓ 3.1.2 Model Pengembangan Sistem (Agile Development)
- ✓ 3.1.3 Alat dan Bahan (7 tabel: Hardware, Software Utama, Library Frontend, Library Backend, Alat Pengembangan, Alat Uji Coba, Bahan)
- ✓ 3.1.4 Prosedur Penelitian (4 tahap Agile: Requirement, Design, Development, Testing)
- ✓ 3.1.5 Objek Penelitian (Ketapang Media Center, Diskominfo Ketapang)
- ✓ 3.1.6 Prosedur Pengumpulan Data (Observasi, studi literatur, pengujian sistem)

#### 3.2 Perancangan Sistem
- ✓ 3.2.1 Arsitektur Sistem (Laravel MVC, AI eksternal, scraper lokal, Railway deployment)
- ✓ 3.2.2 Perancangan Arus Data (2 activity diagram: pengolahan aduan, tindak lanjut SLA)
- ✓ 3.2.3 Perancangan Basis Data (ERD 9 entitas, deskripsi tabel)
- ✓ 3.2.4 Perancangan Antar Muka (Deskripsi 5 halaman utama)
- ✓ 3.2.5 Perancangan Pengujian Sistem (Black box testing, 20 fungsi)
- ✓ 3.2.6 Perancangan Pengujian Penerimaan Pengguna (UAT, Likert 5 skala, 10 pernyataan)
- ✓ 3.2.7 Representasi Kasus (Contoh aduan duplikasi, tiket eskalasi, laporan prioritas tinggi)

---

## DIAGRAM VISUAL (6 File SVG)

| No. | File | Deskripsi | Status |
|-----|------|-----------|--------|
| 1 | `GAMBAR_3_1_PROSEDUR_PENELITIAN.svg` | Flowchart Agile horizontal 4 tahap | ✓ Valid XML |
| 2 | `GAMBAR_3_2_ARSITEKTUR_SISTEM.svg` | Arsitektur 3-tier: User → Laravel → MySQL + AI | ✓ Valid XML |
| 3 | `GAMBAR_3_3_USE_CASE_DIAGRAM.svg` | Use case 3 aktor (Admin, OPD, Masyarakat) | ✓ Valid XML |
| 4 | `GAMBAR_3_4_ACTIVITY_PENGOLAHAN_ADUAN.svg` | Activity diagram: scraping → AI → tiket | ✓ Valid XML |
| 5 | `GAMBAR_3_5_ACTIVITY_TINDAK_LANJUT_SLA.svg` | Activity diagram: SLA 24 jam + eskalasi | ✓ Valid XML |
| 6 | `GAMBAR_3_6_ERD.svg` | ERD 9 entitas dengan kardinalitas lengkap | ✓ Valid XML |

**Format:** Semua diagram hitam-putih, formal, siap cetak akademik.

---

## KONSISTENSI IMPLEMENTASI

✓ **LLM Gemma 4 31B IT** via Google AI Studio (bukan NLP tradisional)  
✓ **Playwright** scraper lokal (bukan Railway background job)  
✓ **Laravel 13** + Blade (bukan SPA React/Vue)  
✓ **MySQL** Railway hayabusa.proxy.rlwy.net:23796  
✓ **SLA 1×24 jam** tahap 1 → proses_disposisi, 2×24 jam → eskalasi  
✓ **Railway** deployment web + database  
✓ **Manual artisan** sync Facebook/Instagram (bukan cron otomatis)

---

## REFERENSI INTERNAL

### Gambar
- Gambar 3.1: 4 referensi
- Gambar 3.2–3.6: masing-masing 2 referensi

### Tabel
- Tabel 3.1–3.7: masing-masing 1–2 referensi

---

## ANTI-PATTERN CHECK

✓ **Tidak ada klaim tokenisasi/TF-IDF/cosine similarity** pada implementasi  
✓ **Password** hanya disebutkan di tabel basis data (kolom teknis), bukan narasi implementasi  
✓ **Nama formal** "Sistem Informasi Manajemen Aduan Multi Channel KMC" konsisten  
✓ **Nama kode** "SIMADU-KMC" tidak muncul di narasi formal

---

## SUMBER PUSTAKA YANG DIPERLUKAN (BELUM DICARI)

1. **Sugiyono.** *Metode Penelitian Kuantitatif, Kualitatif, dan R&D.* Bandung: Alfabeta, 2019.  
   - Status: Ditemukan di Internet Archive (identifier: `buku-metode-penelitian-sugiyono`)
   - Penulis: Prof. Dr. Sugiyono
   - Penerbit: CV. Alfabeta, Bandung
   - **Perlu:** Tahun cetakan yang sesuai (Cetakan ke-19, Oktober 2013 tersedia)

2. **Google AI.** *Gemini API Documentation.* https://ai.google.dev/docs  
   - Sudah ada di DAFTAR_PUSTAKA.md

3. **Pressman, R. S.** *Software Engineering: A Practitioner's Approach.* (Edisi 8 atau 9)  
   - Sudah ada di DAFTAR_PUSTAKA.md

---

## LANGKAH BERIKUTNYA

### 1. Review User
- Baca draft BAB III di `.hermes/md/BAB_III_METODOLOGI_DAN_PERANCANGAN.md`
- Buka 6 file SVG diagram untuk pratinjau visual
- Verifikasi kesesuaian dengan implementasi SIMADU-KMC aktual

### 2. Penyempurnaan (Jika Diperlukan)
- Revisi narasi berdasarkan feedback
- Perbaikan diagram jika ada elemen yang kurang jelas
- Penambahan detail teknis jika diminta dosen pembimbing

### 3. Integrasi ke Dokumen Word
- Copy markdown ke Word dengan format sesuai template TA
- Insert gambar SVG (convert ke PNG 300 DPI jika diperlukan)
- Sesuaikan nomor halaman dan daftar isi

### 4. Verifikasi Pustaka
- Tambahkan Sugiyono (2019 atau 2013) ke DAFTAR_PUSTAKA.md
- Pastikan semua sitasi di BAB III ada di daftar pustaka

---

## CATATAN PENTING

### Hal yang TIDAK Dilakukan (Sesuai Preferensi User)
- ❌ Build/test aplikasi (user request fokus pada dokumen/visual)
- ❌ Lint/format code (tidak relevan untuk pekerjaan TA)
- ❌ Commit ke Git (belum ada instruksi commit)

### File yang Dihasilkan
```
.hermes/md/
├── BAB_III_METODOLOGI_DAN_PERANCANGAN.md (33 KB)
├── GAMBAR_3_1_PROSEDUR_PENELITIAN.svg
├── GAMBAR_3_2_ARSITEKTUR_SISTEM.svg
├── GAMBAR_3_3_USE_CASE_DIAGRAM.svg
├── GAMBAR_3_4_ACTIVITY_PENGOLAHAN_ADUAN.svg
├── GAMBAR_3_5_ACTIVITY_TINDAK_LANJUT_SLA.svg
└── GAMBAR_3_6_ERD.svg
```

---

**Selesai:** 2026-07-13 06:46 UTC  
**Durasi total:** ~3 jam (analisis + penulisan + diagram + verifikasi)
