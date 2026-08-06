# Analisis Komprehensif Laporan Tugas Akhir

**File yang Dianalisis:** `LAPORAN TA AchmadBagusA_TI6A_3042023024-4.docx`
**Acuan:** Panduan TA Politeknik Negeri Ketapang

---

## 1. Temuan Kritis (Critical Issues)

### A. Hilangnya Sitasi dalam Teks (Broken Citations)
Seluruh sitasi dalam laporan, khususnya pada **BAB II Tinjauan Pustaka**, terputus (broken).
- **Contoh di dokumen:** _"Menurut , dalam konteks pengerjaan mandiri, metode Agile merupakan..."_
- **Seharusnya:** _"Menurut [Nama Penulis, Tahun], dalam konteks..."_
- **Penyebab:** Anda menggunakan *Mendeley* untuk menyisipkan sitasi. Ketika file ini disalin atau dikerjakan antar versi/komputer, *field code* Mendeley menjadi *corrupt*.
- **Dampak:** **DAFTAR PUSTAKA MENJADI KOSONG**. Halaman Daftar Pustaka ada, namun tidak berisi daftar referensi apa pun karena Mendeley tidak dapat membaca sitasi yang rusak.
- **Tindakan yang Diperlukan:** Anda harus melakukan *Update Citations and Bibliography* melalui tab *References* > *Mendeley Cite* di Microsoft Word Anda. Jika tidak berhasil, Anda harus menyisipkan ulang sitasi satu per satu di BAB II dan di Daftar Pustaka secara manual.

### B. Kesalahan Format Caption Gambar (Gambar 4.51)
Ada kesalahan fatal pada penamaan/caption Gambar 4.51 yang menggunakan bentuk kalimat penjelasan, bukan sekadar judul gambar.
- **Temuan di dokumen:** `Gambar 4.51 adalah potongan kode dari file AIClassificationService.php yang menunjukkan proses pengi`
- **Seharusnya:** `Gambar 4.51 Potongan Kode Pemanggilan API` (Ternyata Anda memiliki dua caption Gambar 4.51, yang satu kalimat deskripsi dan yang satu lagi caption benar).
- **Tindakan yang Diperlukan:** Hapus kalimat penjelas yang salah tempat tersebut dari format *style* caption gambar. Kalimat penjelas harus menggunakan format *Normal*, bukan format *Caption*.

---

## 2. Temuan Minor & Typo

### A. Typo pada Kata "Prompt"
Terdapat kesalahan pengetikan (*typo*) "Promt" yang seharusnya "Prompt" pada beberapa caption gambar di BAB IV:
- `Gambar 4.45 User Promt Konteks Bahasa Lokal` ❌ 
- `Gambar 4.47 Potongan Kode User Promt Contoh Klasifikasi` ❌
- `Gambar 4.50 Promt Deteksi Duplikat` ❌
- **Tindakan:** Ubah kata **Promt** menjadi **Prompt**.

### B. Kesalahan Ketik pada Daftar Tabel
Pada caption tabel 4.10 terdapat kata berulang/spasi berlebih:
- `Tabel 4.10 Tabel  Keterbatasan Token dan Request API` ❌
- **Tindakan:** Ubah menjadi `Tabel 4.10 Keterbatasan Token dan Request API` (hapus kata "Tabel" yang kedua kali).

---

## 3. Kesesuaian dengan Panduan TA Politeknik Ketapang

Secara umum, struktur laporan sudah **sangat baik** dan **sesuai** dengan aturan hierarki Politeknik Negeri Ketapang (Lampiran O Panduan TA).

### ✅ Struktur Penomoran (LULUS)
Struktur penomoran di BAB II, BAB III, dan BAB IV telah mengikuti format hirarki resmi:
1. **1., 2., 3.** (Tingkat 1 - BAB)
2. **1.1, 1.2** (Tingkat 2 - Sub-bab)
3. **1.1.1, 1.1.2** (Tingkat 3 - Sub-sub-bab)
4. **A., B., C.** (Tingkat 4 - Sub-bagian kapital, misalnya `A. Tampilan Halaman Login`)
5. **1., 2., 3.** (Tingkat 5 - Sub-bagian angka, misal `1. Tabel Database Users`)

Tidak ditemukan lompatan (jumping) hierarki (misalnya dari 4.1.1 tiba-tiba ke angka 1 tanpa huruf A).

### ✅ Struktur Tabel & Gambar (LULUS)
- Jumlah Gambar: **173 Gambar**
- Jumlah Tabel: **102 Tabel**
- Seluruh nomor gambar dan tabel menggunakan format `[Nomor Bab].[Nomor Urut]`, contoh `Gambar 4.50` yang berarti Gambar ke-50 di BAB IV. Ini sudah sesuai panduan.
- Penamaan panjang tabel yang diputus ke halaman berikutnya menggunakan tambahan kata **"Lanjutan"** (contoh: `Tabel 4.4 Hasil Pengujian Sinkronisasi dan Klasifikasi Aduan Lanjutan`). Ini adalah praktik yang **sangat disarankan** dan sudah benar.

### ✅ Penggunaan Kata Asing (Italic)
Istilah asing seperti *Agile*, *Use Case*, *Activity Diagram*, *Black Box Testing*, dan nama-nama file/tabel bahasa Inggris telah menggunakan cetak miring (*italic*) pada mayoritas teks.

---

## 4. Kesimpulan dan Tindakan Selanjutnya

Laporan Tugas Akhir Anda sudah 95% selesai dengan format yang sangat rapi dan komprehensif. Anda HANYA PERLU memperbaiki 3 hal berikut sebelum mencetak/menjilid laporan:

1. **[SANGAT PENTING] Perbaiki sitasi Mendeley.** Buka Word, klik tab *Mendeley*, dan perbaiki referensi yang hilang seperti "Menurut ," agar nama penulis dan tahunnya kembali muncul. Pastikan Daftar Pustaka di akhir dokumen terisi kembali.
2. Hapus *style caption* pada kalimat deskriptif `Gambar 4.51 adalah potongan kode dari...` dan ubah menjadi teks normal biasa.
3. Koreksi *typo* "Promt" menjadi "Prompt" di caption Gambar 4.45, 4.47, dan 4.50, serta hapus spasi ganda di caption Tabel 4.10.