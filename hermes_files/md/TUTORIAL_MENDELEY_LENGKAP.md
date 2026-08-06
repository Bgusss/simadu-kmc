# TUTORIAL LENGKAP: Setup Mendeley untuk Laporan TA
**Untuk:** Achmad Bagus Aprianto - Politeknik Negeri Ketapang  
**Tanggal:** 11 Juli 2026  
**Waktu:** ~30 menit (install + input 42 referensi)

---

## 🎯 APA ITU MENDELEY?

Mendeley adalah software **GRATIS** untuk:
- ✅ Menyimpan semua referensi (42 sumber) dalam 1 tempat
- ✅ Membuat sitasi otomatis di Word: `(Kadir, 2019)`
- ✅ Membuat Daftar Pustaka otomatis (urut alfabetis, format APA 7th)
- ✅ Update otomatis kalau ada perubahan

**Analogi:** Mendeley = "Google Drive untuk referensi ilmiah"

---

## 📥 STEP 1: Download & Install Mendeley (5 menit)

### 1.1 Download Mendeley Desktop
1. Buka browser → https://www.mendeley.com/download-desktop-new/
2. Klik tombol **"Download Mendeley Desktop"** (Windows)
3. File download: `Mendeley-Desktop-x.xx.x-win64.exe` (~120 MB)

### 1.2 Install Mendeley
1. **Jalankan installer** (double-click `.exe`)
2. Klik **"Next"** → **"I Agree"** → **"Install"**
3. Centang **"Install MS Word Plugin"** ← PENTING!
4. Klik **"Finish"**

### 1.3 Buat Akun (Gratis)
1. Buka Mendeley Desktop (icon di desktop)
2. Klik **"Create Account"**
3. Isi:
   - Email: (email kampus atau Gmail)
   - Password: (buat password kuat)
   - Name: Achmad Bagus Aprianto
4. Klik **"Register"**
5. Cek email → klik link verifikasi

✅ **Selesai!** Mendeley sudah siap digunakan.

---

## 📚 STEP 2: Import 42 Referensi ke Mendeley (20 menit)

### 2.1 Persiapan: Download File Referensi
Saya sudah buatkan file `.bib` (BibTeX format) yang bisa langsung di-import ke Mendeley.

**File:** `DAFTAR_PUSTAKA_42_SUMBER.bib` (di folder `.hermes\md\`)

### 2.2 Import File BibTeX ke Mendeley
1. **Buka Mendeley Desktop**
2. Klik menu **"File"** → **"Import"** → **"BibTeX (*.bib)"**
3. Pilih file: `C:\laragon\www\SIMADU-KMC\.hermes\md\DAFTAR_PUSTAKA_42_SUMBER.bib`
4. Klik **"Open"**
5. Tunggu 10-30 detik → **42 referensi muncul** di library Mendeley

### 2.3 Verifikasi Import Berhasil
1. Di Mendeley, lihat sidebar kiri → **"All Documents"**
2. Jumlah dokumen harus: **42**
3. Cek beberapa referensi:
   - Scroll ke **"Kadir"** → klik → lihat detail:
     - Author: `Kadir, A.`
     - Year: `2019`
     - Title: `Pengenalan Sistem Informasi Edisi Revisi`
     - Publisher: `Andi Offset`
   - Scroll ke **"Russell"** → klik → lihat detail:
     - Author: `Russell, S.; Norvig, P.`
     - Year: `2020`
     - Title: `Artificial Intelligence: A Modern Approach`
     - Edition: `4`
     - Publisher: `Pearson Education`

✅ **Jika semua benar,** import berhasil!

---

## 📝 STEP 3: Pakai Mendeley di Microsoft Word (5 menit)

### 3.1 Cek Plugin Word Sudah Aktif
1. **Buka Microsoft Word**
2. Lihat menu atas → harus ada tab **"References"**
3. Klik tab **"References"** → harus ada grup **"Mendeley Cite-O-Matic"**:
   - Insert Citation
   - Insert Bibliography
   - Refresh
   - More

**Kalau tidak ada?**
- Tutup Word
- Buka Mendeley Desktop → menu **"Tools"** → **"Install MS Word Plugin"**
- Buka Word lagi

### 3.2 Insert Sitasi Pertama Kali
1. **Buka laporan TA Anda** (`.docx`)
2. Letakkan kursor di tempat mau sitasi, contoh:
   > Sistem informasi merupakan kombinasi dari komponen-komponen... `[KURSOR DI SINI]`
3. Klik tab **"References"** → klik **"Insert Citation"**
4. **Jendela Mendeley muncul:**
   - Ketik nama penulis: `Kadir`
   - Pilih: **Kadir, A. (2019) - Pengenalan Sistem Informasi...**
   - Klik **"OK"**
5. **Sitasi otomatis muncul:** `(Kadir, 2019)`

### 3.3 Buat Daftar Pustaka Otomatis
1. **Scroll ke bagian "DAFTAR PUSTAKA"** di Word
2. Hapus daftar pustaka manual lama (jika ada)
3. Letakkan kursor di bawah judul "DAFTAR PUSTAKA"
4. Klik tab **"References"** → klik **"Insert Bibliography"**
5. **BOOM!** Daftar Pustaka 42 sumber muncul otomatis:
   - Urut alfabetis ✅
   - Format APA 7th Edition ✅
   - Hanya sumber yang dikutip ✅

### 3.4 Set Citation Style ke APA 7th
1. Di Word, klik tab **"References"**
2. Klik dropdown **"Style"** (biasanya di grup Mendeley)
3. Pilih: **"American Psychological Association 7th edition"**
4. Klik **"Refresh"** → semua sitasi & daftar pustaka update ke format APA 7th

✅ **Selesai!** Sitasi & Daftar Pustaka otomatis siap pakai.

---

## 🎨 STEP 4: Tips & Trik Mendeley

### 4.1 Insert Sitasi Cepat (Keyboard Shortcut)
- Windows: **Alt + 3** (atau **Alt + M**)
- Jendela Mendeley langsung muncul

### 4.2 Edit Referensi (Jika Ada yang Salah)
1. Buka **Mendeley Desktop**
2. Cari referensi yang mau diedit
3. Klik kanan → **"Edit Details"**
4. Edit field: Author, Year, Title, Publisher, dll.
5. Klik **"Save"**
6. Di Word, klik **"Refresh"** → semua sitasi + daftar pustaka update otomatis

### 4.3 Hapus Referensi yang Tidak Dipakai
1. Di Mendeley Desktop, cari referensi
2. Klik kanan → **"Move to Trash"**
3. Di Word, klik **"Refresh"** → referensi hilang dari Daftar Pustaka

### 4.4 Tambah Referensi Baru
**Cara 1: Manual**
1. Di Mendeley Desktop, klik **"File"** → **"Add Entry Manually"**
2. Pilih tipe: **"Book"**, **"Journal Article"**, **"Conference Paper"**, dll.
3. Isi detail: Author, Year, Title, Publisher, dll.
4. Klik **"Save"**

**Cara 2: Import dari DOI**
1. Klik **"File"** → **"Add Entry Manually"** → pilih tipe
2. Isi field **"DOI"** saja, contoh: `10.32493/informatika.v6i1.8323`
3. Klik tombol **"Search by DOI"** → detail otomatis terisi
4. Klik **"Save"**

**Cara 3: Drag-Drop PDF**
1. Punya file PDF artikel?
2. Drag-drop PDF ke jendela Mendeley Desktop
3. Mendeley otomatis extract metadata (Author, Title, Year, dll.)

### 4.5 Backup Library Mendeley
1. Klik menu **"Tools"** → **"Options"** → tab **"General"**
2. Lihat lokasi folder: `C:\Users\Bguss2\Documents\Mendeley Desktop\`
3. **Backup folder ini** ke Google Drive / USB (cadangan jika laptop rusak)

---

## 🚨 TROUBLESHOOTING: Masalah Umum

### ❌ Plugin Mendeley tidak muncul di Word
**Solusi:**
1. Tutup Word
2. Buka Mendeley Desktop → **"Tools"** → **"Install MS Word Plugin"**
3. Restart Word

### ❌ Sitasi tidak muncul (hanya placeholder `{Mendeley...}`)
**Solusi:**
1. Klik tab **"References"** → klik **"Refresh"**
2. Jika masih tidak muncul, coba **restart Word**

### ❌ Format sitasi salah (bukan APA 7th)
**Solusi:**
1. Klik tab **"References"**
2. Dropdown **"Style"** → pilih **"American Psychological Association 7th edition"**
3. Klik **"Refresh"**

### ❌ Daftar Pustaka tidak urut alfabetis
**Solusi:**
1. Hapus bibliography lama (pilih semua → Delete)
2. Klik **"Insert Bibliography"** lagi → otomatis urut alfabetis

### ❌ Referensi tidak ditemukan saat insert citation
**Solusi:**
1. Buka Mendeley Desktop → cek referensi sudah ada?
2. Jika ada, coba **sync**: klik icon **"Sync"** (lingkaran panah) di toolbar
3. Kembali ke Word → coba insert citation lagi

---

## 📖 CONTOH PENGGUNAAN: Sitasi di BAB II

### Sebelum (Manual):
```
Sistem informasi merupakan kombinasi dari komponen-komponen... (Kadir, 2019).
Laravel merupakan framework PHP open-source... (Harianto dkk., 2019).
```
**Masalah:**
- Ketik manual `(Kadir, 2019)` → typo risiko tinggi
- Daftar Pustaka manual → lupa cantumkan Kadir/Harianto
- Ganti tahun → edit semua tempat manual

### Sesudah (Mendeley):
1. **Ketik kalimat:**
   > Sistem informasi merupakan kombinasi dari komponen-komponen...
2. **Klik "Insert Citation"** → pilih **Kadir (2019)** → **otomatis jadi:**
   > Sistem informasi merupakan kombinasi dari komponen-komponen... (Kadir, 2019).
3. **Daftar Pustaka otomatis update:**
   > Kadir, A. (2019). *Pengenalan Sistem Informasi Edisi Revisi*. Andi Offset.

**Keuntungan:**
- ✅ Sitasi konsisten (tidak typo)
- ✅ Daftar Pustaka otomatis urut & lengkap
- ✅ Ganti tahun → 1x edit di Mendeley → semua tempat update

---

## 📊 WORKFLOW LENGKAP: Dari 0 sampai Selesai

```
HARI 1: Setup (30 menit)
├─ Download & Install Mendeley
├─ Import 42 referensi dari file .bib
└─ Cek plugin Word aktif

HARI 2-3: Tulis BAB I-II (pakai sitasi Mendeley)
├─ Tulis kalimat → Insert Citation → pilih sumber
├─ Lanjut tulis → Insert Citation lagi
└─ Daftar Pustaka otomatis update (tidak perlu manual)

HARI 4-5: Tulis BAB III-V (lanjut pakai sitasi)
├─ Tambah referensi baru jika perlu
└─ Refresh bibliography setiap selesai 1 bab

HARI SIDANG: Revisi Mudah
├─ Dosen minta tambah referensi? → 1 menit di Mendeley → Refresh
├─ Dosen minta hapus referensi? → 1 menit di Mendeley → Refresh
└─ Dosen minta ganti format? → Ganti style → Refresh
```

---

## ✅ CHECKLIST: Sudah Siap?

Sebelum mulai tulis laporan, pastikan:
- [x] Mendeley Desktop sudah terinstall
- [x] Akun Mendeley sudah dibuat & verifikasi email
- [x] Plugin Word sudah aktif (ada tab "References" dengan grup Mendeley)
- [x] 42 referensi sudah di-import ke library Mendeley
- [x] Citation style sudah di-set ke **APA 7th Edition**
- [x] Sudah coba insert 1 sitasi test di Word (berhasil muncul)

**Jika semua ✅, Anda siap pakai Mendeley untuk laporan TA!**

---

## 🎓 TIPS PRO untuk Sidang TA

### 1. Backup Library Sebelum Sidang
- Export semua referensi: **"File"** → **"Export"** → format **"BibTeX"**
- Simpan file `.bib` di Google Drive (jaga-jaga laptop rusak)

### 2. Siapkan PDF Artikel (Jika Dosen Minta Bukti)
- Di Mendeley, attach PDF artikel ke setiap referensi
- Klik kanan referensi → **"Add File"** → pilih PDF
- Saat sidang, dosen tanya "Ada buktinya?" → buka Mendeley → klik referensi → PDF langsung muncul

### 3. Print Daftar Pustaka Terpisah (untuk Sidang)
- Copy Daftar Pustaka dari Word → paste ke file baru
- Print → bawa saat sidang (baca lebih mudah daripada scroll di laptop)

### 4. Hindari Edit Manual di Word
- **JANGAN** edit sitasi manual di Word: `(Kadir, 2019)` → `(Kadir, 2020)` ❌
- Edit di Mendeley Desktop → Refresh di Word ✅
- Alasan: Edit manual akan hilang saat Refresh

---

## 📞 BANTUAN LEBIH LANJUT

**Video Tutorial (Bahasa Indonesia):**
- YouTube: "Cara Menggunakan Mendeley untuk Skripsi/TA" (cari di YouTube)
- Durasi: 10-15 menit
- Lihat cara insert citation, edit referensi, troubleshooting

**Dokumentasi Resmi:**
- https://www.mendeley.com/guides
- https://www.mendeley.com/reference-management/mendeley-cite

**Forum Mendeley:**
- https://community.mendeley.com/
- Tanya jawab jika ada masalah teknis

---

## 🎉 SELAMAT!

Anda sekarang sudah tahu:
✅ Apa itu Mendeley & kegunaannya  
✅ Cara install & setup akun  
✅ Cara import 42 referensi  
✅ Cara insert sitasi di Word  
✅ Cara buat Daftar Pustaka otomatis  
✅ Tips & troubleshooting  

**Next step:** Mulai tulis laporan TA dengan sitasi Mendeley! 🚀

---

**File ini dibuat oleh:** Hermes Agent (Kiro AI)  
**Untuk:** Achmad Bagus Aprianto (3042023024)  
**Tanggal:** 11 Juli 2026  
**Durasi setup:** ~30 menit (termasuk import 42 referensi)  

**Good luck untuk sidang TA! 💪**
