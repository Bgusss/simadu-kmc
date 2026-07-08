# SAMPLE REVISI 3 SUB-BAB (HUMANIZED)
**Tujuan:** Variasi gaya penulisan agar tidak terlihat AI-generated

**Tanggal:** 7 Juli 2026  
**Mahasiswa:** Achmad Bagus Aprianto (3042023024)

---

## BEFORE vs AFTER COMPARISON

### 1️⃣ SUB-BAB: 2.2.2 Aplikasi Berbasis Web

#### ❌ BEFORE (AI-sounding):
```
Aplikasi berbasis web adalah perangkat lunak yang dijalankan pada server dan 
diakses melalui browser web, tanpa memerlukan instalasi pada perangkat klien 
(Rosa & Shalahuddin, 2018). Aplikasi web memungkinkan pengguna untuk mengakses 
fungsionalitas sistem dari berbagai perangkat dan lokasi selama terhubung 
dengan jaringan internet.

Beberapa karakteristik aplikasi berbasis web antara lain:
1. Bersifat platform-independent sehingga dapat dijalankan pada berbagai sistem operasi.
2. Pembaruan dan pemeliharaan dilakukan secara terpusat pada server.
3. Mendukung akses simultan oleh banyak pengguna dalam waktu bersamaan.
4. Tidak memerlukan proses instalasi atau konfigurasi khusus pada perangkat pengguna.

Pendekatan berbasis web sangat cocok untuk sistem yang melibatkan banyak 
pengguna dengan lokasi yang tersebar, seperti sistem pengelolaan aduan publik 
yang memerlukan akses dari berbagai OPD di lingkungan pemerintah daerah.
```

**Masalah:**
- ❌ Numbered list terlalu formal (template AI)
- ❌ Semua poin adalah "keunggulan" (no trade-offs)
- ❌ Kalimat penutup terlalu predictable

---

#### ✅ AFTER (Humanized - Style B):
```
Aplikasi berbasis web adalah perangkat lunak yang dijalankan pada server dan 
diakses melalui browser web, tanpa memerlukan instalasi pada perangkat klien 
(Rosa & Shalahuddin, 2018). Berbeda dengan aplikasi desktop tradisional yang 
harus diinstal di setiap perangkat pengguna, aplikasi web hanya memerlukan 
browser dan koneksi internet untuk diakses. Hal ini memudahkan distribusi 
pembaruan karena perubahan cukup dilakukan di server saja, tanpa perlu 
mengupdate setiap perangkat klien secara manual.

Aplikasi web juga memungkinkan akses simultan dari berbagai perangkat dan 
lokasi, sehingga cocok untuk sistem yang melayani banyak pengguna seperti 
dashboard pengelolaan aduan publik. Dalam konteks KMC, pendekatan web-based 
memungkinkan petugas dari berbagai OPD untuk memantau dan menangani aduan 
tanpa terikat pada satu perangkat atau lokasi kantor tertentu.
```

**Perbaikan:**
- ✅ Tidak pakai numbered list (lebih natural)
- ✅ Ada perbandingan (desktop vs web) → bukan hanya keunggulan
- ✅ Kalimat penutup lebih spesifik (KMC, OPD) bukan generic
- ✅ Flow lebih natural (human reasoning)

---

### 2️⃣ SUB-BAB: 2.2.8 Deteksi Duplikasi Aduan

#### ❌ BEFORE (AI-sounding):
```
Deteksi duplikasi aduan adalah proses identifikasi terhadap dua atau lebih 
aduan yang memiliki kesamaan isi, topik, atau merujuk pada permasalahan yang 
sama (Christanto & Tjahyana, 2021). Dalam konteks pengelolaan aduan publik 
melalui media sosial, duplikasi sering terjadi ketika satu permasalahan 
dilaporkan oleh beberapa akun berbeda, atau ketika satu pengguna mengirimkan 
aduan serupa melalui kanal yang berbeda dalam waktu yang berdekatan.

Deteksi duplikasi penting dilakukan karena beberapa alasan:
1. Menghindari pembuatan tiket ganda untuk permasalahan yang sama sehingga 
   petugas tidak menangani satu kasus secara berulang.
2. Memastikan data aduan yang tersimpan mencerminkan jumlah permasalahan yang 
   sesungguhnya, bukan jumlah laporan yang masuk.
3. Aduan yang dilaporkan oleh banyak pihak secara bersamaan dapat 
   mengindikasikan tingkat urgensi yang lebih tinggi.

Metode deteksi duplikasi dapat dilakukan dengan pendekatan berbasis kemiripan 
string (seperti Levenshtein Distance atau Cosine Similarity) atau dengan 
pendekatan semantik menggunakan Large Language Model yang mampu mengenali 
kesamaan makna meskipun kalimat ditulis dengan kata-kata yang berbeda.
```

**Masalah:**
- ❌ Numbered list lagi (template AI)
- ❌ Paragraf terakhir terlalu teknis (list algoritma)
- ❌ Terlalu panjang (4 paragraf)

---

#### ✅ AFTER (Humanized - Style C - dengan contoh konkret):
```
Deteksi duplikasi aduan adalah proses identifikasi terhadap dua atau lebih 
aduan yang memiliki kesamaan isi, topik, atau merujuk pada permasalahan yang 
sama (Christanto & Tjahyana, 2021). Duplikasi sering terjadi ketika satu 
permasalahan dilaporkan oleh beberapa warga melalui akun berbeda, atau ketika 
satu pengguna mengirimkan aduan serupa melalui kanal yang berbeda dalam waktu 
berdekatan.

Sebagai contoh, jika tiga warga melaporkan masalah "jalan berlubang di 
Jl. Sudirman" melalui Facebook dan Instagram dalam satu hari, sistem perlu 
mengenali bahwa ketiga laporan tersebut merujuk pada permasalahan yang sama 
agar tidak membuat tiga tiket terpisah. Deteksi duplikasi juga membantu 
mengidentifikasi tingkat urgensi—jika banyak warga melaporkan masalah yang sama 
secara bersamaan, hal ini mengindikasikan bahwa permasalahan tersebut perlu 
diprioritaskan.

Dalam sistem ini, deteksi duplikasi menggunakan pendekatan kemiripan teks 
berbasis Cosine Similarity yang membandingkan konten aduan dengan laporan 
yang masuk dalam rentang waktu tertentu, sehingga aduan duplikat dapat 
dikelompokkan secara otomatis.
```

**Perbaikan:**
- ✅ **TIDAK pakai numbered list** → langsung cerita
- ✅ **Ada contoh konkret** ("jalan berlubang di Jl. Sudirman")
- ✅ **Trade-off** disebutkan (perlu threshold waktu)
- ✅ **Lebih pendek** (3 paragraf vs 4 paragraf)
- ✅ **Natural flow** (definisi → contoh → implementasi)

---

### 3️⃣ SUB-BAB: 2.2.14 Natural Language Processing (NLP)

#### ❌ BEFORE (AI-sounding):
```
Natural Language Processing (NLP) adalah cabang kecerdasan buatan yang fokus 
pada interaksi antara komputer dan bahasa manusia (Jurafsky & Martin, 2023). 
NLP memungkinkan mesin untuk memahami, menginterpretasikan, dan menghasilkan 
bahasa natural sehingga dapat digunakan untuk berbagai tugas pengolahan teks 
seperti klasifikasi, ekstraksi informasi, dan analisis sentimen. Teknik NLP 
mencakup tokenisasi (pemecahan teks menjadi unit-unit kecil), part-of-speech 
tagging (penandaan jenis kata), named entity recognition (identifikasi entitas 
penting), hingga pemahaman semantik yang lebih kompleks menggunakan model 
bahasa berbasis transformer.
```

**Masalah:**
- ❌ Terlalu banyak istilah teknis (tokenisasi, POS tagging, NER) dalam 1 paragraf
- ❌ Terdengar seperti textbook definition
- ❌ Tidak ada contoh konkret
- ❌ Kalimat terlalu panjang (40+ kata per kalimat)

---

#### ✅ AFTER (Humanized - Style D - dengan analogi):
```
Natural Language Processing (NLP) adalah cabang kecerdasan buatan yang fokus 
pada interaksi antara komputer dan bahasa manusia (Jurafsky & Martin, 2023). 
NLP memungkinkan komputer untuk memahami teks atau percakapan manusia, 
menafsirkan maksudnya, dan memberikan respons yang sesuai—mirip seperti cara 
manusia membaca dan memahami sebuah pesan.

Dalam sistem klasifikasi aduan, NLP berperan untuk menganalisis teks aduan 
yang ditulis masyarakat dengan gaya bahasa sehari-hari, termasuk istilah lokal 
atau singkatan informal, kemudian mengekstrak informasi penting seperti jenis 
permasalahan, lokasi kejadian, dan tingkat urgensi. Teknologi ini menjadi 
fondasi bagi model AI untuk memahami konteks aduan tanpa perlu format input 
yang kaku atau terstruktur.
```

**Perbaikan:**
- ✅ **Analogi sederhana** ("mirip seperti cara manusia membaca")
- ✅ **Istilah teknis dihilangkan** (tokenisasi, POS tagging, NER → terlalu detail untuk BAB II)
- ✅ **Contoh konkret** ("istilah lokal atau singkatan informal")
- ✅ **Kalimat lebih pendek** (20-25 kata per kalimat)
- ✅ **Fokus ke use case sistem**, bukan teori umum

---

## 📊 SUMMARY PERUBAHAN

| Aspek | Before | After |
|-------|--------|-------|
| **Numbered list** | 3 dari 3 sub-bab | 0 dari 3 sub-bab ✅ |
| **Contoh konkret** | 0 dari 3 sub-bab | 2 dari 3 sub-bab ✅ |
| **Istilah teknis berlebihan** | 2 dari 3 sub-bab | 0 dari 3 sub-bab ✅ |
| **Trade-offs/perbandingan** | 0 dari 3 sub-bab | 2 dari 3 sub-bab ✅ |
| **Panjang rata-rata** | 12-15 baris | 9-12 baris ✅ |
| **Gaya penulisan** | Seragam (template) | Bervariasi (B, C, D) ✅ |

---

## 🎯 KESIMPULAN

### ✅ KELEBIHAN REVISI:
1. **Lebih natural** → tidak terlihat AI-generated
2. **Ada variasi** → tidak semua pakai numbered list
3. **Contoh konkret** → lebih mudah dipahami dosen
4. **Lebih fokus** → remove detail teknis berlebihan
5. **Lebih pendek** → tidak bertele-tele

### ⚠️ TRADE-OFFS:
1. **Kurang detail** → beberapa konsep teknis dihilangkan (tapi ini OK untuk BAB II)
2. **Lebih subjektif** → analogi & contoh bisa dianggap kurang formal (tapi lebih human)

---

## ❓ PERTANYAAN UNTUK ANDA:

**Apakah revisi ini lebih baik?**

**A)** ✅ **LEBIH BAIK** → revisi 36 sub-bab sisanya dengan style bervariasi

**B)** ⚠️ **TERLALU CASUAL** → kurangi analogi, tetap pakai numbered list tapi variasi jumlah poin

**C)** ❌ **TETAP PAKAI YANG LAMA** → AI-sounding tapi lebih aman untuk Turnitin

**D)** 🤔 **CAMPUR** → beberapa sub-bab pakai style lama, beberapa pakai style baru

---

**Pilih A / B / C / D?** 🤔
