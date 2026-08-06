# Analisis Kesesuaian BAB III §3.2 pada Dokumen Word

**Dokumen diperiksa:** `BAB_III_METODOLOGI_DAN_PERANCANGAN.docx`  
**Dasar pembanding:** route, controller, model, command scheduler, serta diagram sumber SIMADU-KMC.  
**Status:** Belum sepenuhnya siap. Isi utama sudah sesuai arah sistem, tetapi terdapat beberapa ketidaksesuaian antar-narasi, diagram, dan fitur aktual.

## Ringkasan Penilaian

| Bagian | Kesesuaian | Catatan |
|---|---:|---|
| 3.2.1 Arsitektur Sistem | 80% | Narasi alur utama sesuai, tetapi gambar belum tersisip dan gambar final harus menampilkan scraper lokal. |
| 3.2.2 Perancangan Arus Data | 70% | Aktor pada narasi tidak konsisten dengan use case; activity diagram pengolahan aduan perlu diperbaiki. |
| 3.2.3 Perancangan Basis Data | 80% | Narasi relasi inti sesuai, tetapi ERD memiliki dua kesalahan struktur. |
| 3.2.4 Perancangan Antar Muka | 85% | Halaman inti sesuai, namun beberapa halaman aktual belum tercantum. |
| 3.2.5 Perancangan Pengujian Sistem | 90% | Skenario inti sesuai, tetapi belum mencakup beberapa fungsi admin dan profil. |
| 3.2.6 UAT | 95% | Sesuai tujuan dan pengguna sistem. |

## Temuan Prioritas

### 1. Dokumen Word belum memuat gambar diagram
Dokumen masih berisi penanda `[Sisipkan Gambar ...]`. Paket `.docx` tidak memiliki berkas pada `word/media/`. Karena itu Gambar 3.2 sampai 3.6 belum benar-benar masuk ke Word.

### 2. Gambar 3.2 harus konsisten dengan narasi
Narasi menjelaskan alur Facebook/Instagram → scraper lokal → internet → web server → basis data. Gunakan gambar arsitektur final yang memuat **Komputer Lokal / Scraper**. Jangan gunakan SVG lama yang menghubungkan Facebook dan Instagram langsung ke Internet tanpa komponen scraper.

### 3. Perbaiki pembukaan 3.2.2 dan Tabel 3.2
Use case yang tersedia hanya menampilkan aktor Admin KMC, Pengguna OPD, Masyarakat, dan Sistem Scraper. Namun paragraf Word menyebut Facebook, Instagram, Google AI Studio, dan Laravel Scheduler sebagai aktor. Pilih salah satu secara konsisten; untuk use case sederhana, gunakan empat aktor yang memang tampil pada Gambar 3.3.

Tambahkan fungsi **mengelola profil** untuk Admin KMC. Tambahkan juga Dashboard OPD dan fungsi monitoring publik bila seluruh halaman aktual ingin dicakup.

### 4. Gambar 3.4 tidak sesuai urutan proses aktual
Diagram saat ini menempatkan proses **Simpan Mention dan Notifikasi** sebelum keputusan spam. Pada kode aktual, mention mentah disimpan lebih dahulu, kemudian spam diperiksa; notifikasi hanya dibuat jika pesan bukan spam.

Urutan yang benar:
`Sinkronisasi aduan → Simpan mention → Pesan spam? → [Tidak] Simpan notifikasi → Klasifikasi AI → Periksa duplikasi → Buat tiket / verifikasi admin`.

### 5. Gambar 3.6 perlu dua koreksi
- Hapus atribut `platform` dari entitas `notifications`; kolom tersebut tidak ada pada basis data aktif.
- Ubah kardinalitas `opds` ke `users` dari `1 : banyak` menjadi `1 : 0..1`, karena satu OPD memiliki satu akun pengguna OPD pada model aktif.

### 6. Tabel 3.4 perlu dilengkapi dan disederhanakan
Tambahkan baris:
- Profil Admin
- Profil Pengguna OPD

Perjelas halaman tiket admin mencakup pembuatan, perubahan, dan penghapusan tiket. Portal publik juga saat ini menampilkan monitoring ringkas dan daftar tiket, selain pencarian nomor pelacakan.

Ubah frasa `Data dashboard diperbarui melalui AJAX polling` menjadi `Data dashboard dirancang untuk diperbarui secara berkala tanpa memuat ulang halaman`, agar tetap bernada perancangan, bukan implementasi.

### 7. Tabel 3.5 perlu tambahan pengujian
Tambahkan skenario untuk:
- Manajemen OPD dan akun OPD oleh admin
- Perubahan profil admin dan pengguna OPD
- Pembuatan tiket manual oleh admin

Skenario SLA tahap pertama sebaiknya berbunyi: `Tiket yang telah disampaikan kepada OPD dan belum menerima respons melewati batas waktu penanganan.` Ini lebih tepat daripada menyatakan semua tiket yang belum direspons langsung masuk disposisi.

## Kesimpulan
Isi §3.2 sudah menggambarkan alur utama SIMADU-KMC: pengambilan aduan media sosial, penyaringan, klasifikasi AI, deteksi duplikasi, tiket, penugasan OPD, SLA, dan pelacakan publik. Namun dokumen Word belum dapat disebut final sebelum gambar dimasukkan dan tujuh poin di atas disesuaikan.

Bagian yang sudah sesuai tanpa perubahan substansial: proses klasifikasi/duplikasi, penugasan tiket, tanggapan OPD, eskalasi SLA, relasi utama data, dan rancangan UAT.
