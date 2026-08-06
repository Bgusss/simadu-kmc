# Prompt Generation untuk Visualisasi Activity Diagram (Mermaid) — Revisi v2 (Swimlane)

**Catatan revisi v2:** Berdasarkan referensi Tabel 2.2 (Simbol Activity Diagram) yang menyertakan elemen *Swimlane*, dan contoh diagram "Edit Wilayah" (Admin | Sistem), keenam prompt di bawah ini direvisi agar diagram dibuat **dengan swimlane per aktor**.

Karena Mermaid `flowchart` **tidak memiliki notasi swimlane native** seperti PlantUML (`|Aktor|`), teknik yang digunakan adalah:
- Root diagram menggunakan `flowchart LR` (kiri-ke-kanan), agar setiap aktor tersusun sebagai kolom berdampingan (mendekati tampilan swimlane).
- Setiap aktor dibuat sebagai `subgraph "<Nama Aktor>"` tersendiri, diberi `direction TB` di dalamnya agar node-node milik aktor tersebut tetap tersusun vertikal ke bawah.
- Setiap node (proses maupun decision) ditempatkan pada subgraph aktor yang **bertanggung jawab** melakukan atau menentukan langkah tersebut.
- Panah antar-aktor (lintas subgraph) tetap digambar mengikuti urutan alur asli.

Catatan: karena Mermaid tetap mengatur layout secara otomatis, kolom aktor tidak dijamin sejajar presisi seperti swimlane UML klasik — namun pengelompokan aktornya tetap valid dan jelas secara notasi.

---

## 1. Activity Diagram Pengolahan Aduan dari Media Sosial
**Aktor:** Sistem, Admin KMC

**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Pengolahan Aduan dari Media Sosial" dengan spesifikasi berikut:
- Format: `flowchart LR` sebagai root, dengan swimlane per aktor menggunakan `subgraph` dan `direction TB` di dalam masing-masing subgraph.
- Aktor yang terlibat: "Sistem" dan "Admin KMC".
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Gunakan node bentuk stadium/rounded untuk Mulai dan Selesai, bentuk persegi panjang untuk proses, dan bentuk diamond (rhombus) untuk decision.
- Setiap node harus ditempatkan pada subgraph aktor yang sesuai (ditandai di alur berikut).
- Alur:
  1. Mulai. (Sistem)
  2. "Sinkronisasi Aduan Facebook dan Instagram". (Sistem)
  3. "Simpan Data Mention". (Sistem)
  4. Decision: "Pesan spam?". (Sistem)
     - Jika "Ya": "Pesan Tidak Diproses (Notifikasi dan tiket tidak dibuat)" -> Selesai. (Sistem)
     - Jika "Tidak": "Simpan Notifikasi". (Sistem)
  5. Dari Simpan Notifikasi lanjut ke "Klasifikasi AI (Kategori, OPD, Prioritas)". (Sistem)
  6. Decision: "Kemungkinan duplikasi?". (Sistem)
     - Jika "Tidak": "Buat Tiket (Nomor lacak, OPD, SLA)" -> Selesai. (Sistem)
     - Jika "Ya": "Tandai Kandidat Duplikasi (Menunggu verifikasi Admin KMC)". (Sistem)
  7. Dari verifikasi masuk ke Decision: "Dikonfirmasi duplikat?". (Admin KMC)
     - Jika "Ya": "Konfirmasi Duplikat (Tiket tidak dibuat)" -> Selesai. (Admin KMC)
     - Jika "Tidak": Kembali ke proses "Buat Tiket (Nomor lacak, OPD, SLA)". (Sistem)
- Berikan label pada setiap percabangan panah (Ya/Tidak).
```

---

## 2. Activity Diagram Tindak Lanjut Tiket dan Eskalasi SLA
**Aktor:** OPD, Sistem

**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Tindak Lanjut Tiket dan Eskalasi SLA" dengan spesifikasi berikut:
- Format: `flowchart LR` sebagai root, dengan swimlane per aktor menggunakan `subgraph` dan `direction TB` di dalam masing-masing subgraph.
- Aktor yang terlibat: "OPD" dan "Sistem".
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Gunakan node bentuk stadium/rounded untuk Mulai dan Selesai, bentuk persegi panjang untuk proses, dan bentuk diamond (rhombus) untuk decision.
- Setiap node harus ditempatkan pada subgraph aktor yang sesuai (ditandai di alur berikut).
- Alur:
  1. Mulai. (Sistem)
  2. "Tiket Dibuat (Status: diterima, OPD ditetapkan)". (Sistem)
  3. "Tiket Diteruskan atau Dibaca oleh OPD (Batas waktu penanganan ditetapkan)". (OPD)
  4. Decision: "Ada respons OPD sebelum SLA?". (Sistem)
     - Jika "Ya": "Simpan Tanggapan dan Perbarui Status (Riwayat penanganan dicatat)". (Sistem)
     - Jika "Tidak": "Proses Disposisi (Tiket masuk proses disposisi, Batas waktu diperbarui)". (Sistem)
  5. Dari Proses Disposisi masuk ke Decision: "Ada respons OPD pada SLA baru?". (Sistem)
     - Jika "Ya": Kembali ke proses "Simpan Tanggapan dan Perbarui Status". (Sistem)
     - Jika "Tidak": "Eskalasi Tiket (Prioritas dinaikkan, SLA diatur kembali)" -> loop kembali ke Decision "Ada respons OPD pada SLA baru?". (Sistem)
  6. Dari "Simpan Tanggapan..." masuk ke Decision: "Tiket selesai?". (OPD)
     - Jika "Tidak": "Lanjut Tindak Lanjut oleh OPD" -> loop kembali ke awal titik "Tiket Diteruskan atau Dibaca oleh OPD". (OPD)
     - Jika "Ya": "Perbarui Status Selesai" -> Selesai. (Sistem)
- Pastikan panah loop balik (terutama pada Eskalasi Tiket dan Lanjut Tindak Lanjut oleh OPD) digambarkan jelas arah kembalinya agar tidak membingungkan pembaca.
- Berikan label pada setiap percabangan panah (Ya/Tidak).
```

---

## 3. Activity Diagram Login dan Logout
**Aktor:** Pengguna (Admin/OPD), Sistem

**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Login dan Logout" dengan spesifikasi berikut:
- Format: `flowchart LR` sebagai root, dengan swimlane per aktor menggunakan `subgraph` dan `direction TB` di dalam masing-masing subgraph.
- Aktor yang terlibat: "Pengguna (Admin/OPD)" dan "Sistem".
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Gunakan node bentuk stadium/rounded untuk Mulai dan Selesai, bentuk persegi panjang untuk proses, dan bentuk diamond (rhombus) untuk decision.
- Setiap node harus ditempatkan pada subgraph aktor yang sesuai (ditandai di alur berikut).
- Alur:
  1. Mulai. (Pengguna)
  2. "Mengakses Halaman Login". (Pengguna)
  3. "Memasukkan Email dan Kata Sandi". (Pengguna)
  4. Decision: "Kredensial valid?". (Sistem)
     - Jika "Tidak": Loop kembali ke "Mengakses Halaman Login". (Pengguna)
     - Jika "Ya": Lanjut ke Decision: "Peran (Role)?". (Sistem)
  5. Dari Decision "Peran (Role)?": (Sistem)
     - Jika "Admin": "Menampilkan Dashboard Admin KMC". (Sistem)
     - Jika "OPD": "Menampilkan Dashboard OPD". (Sistem)
  6. Dari kedua proses Dashboard tersebut, panah bersatu (join) menuju proses "Menekan Tombol Logout". (Pengguna)
  7. "Mengakhiri Sesi" -> Selesai. (Sistem)
- Berikan label pada setiap percabangan panah (Ya/Tidak/Admin/OPD).
```

---

## 4. Activity Diagram Pembuatan Tiket Manual
**Aktor:** Admin, Sistem

**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Pembuatan Tiket Manual" dengan spesifikasi berikut:
- Format: `flowchart LR` sebagai root, dengan swimlane per aktor menggunakan `subgraph` dan `direction TB` di dalam masing-masing subgraph.
- Aktor yang terlibat: "Admin" dan "Sistem".
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Gunakan node bentuk stadium/rounded untuk Mulai dan Selesai, bentuk persegi panjang untuk proses, dan bentuk diamond (rhombus) untuk decision.
- Setiap node harus ditempatkan pada subgraph aktor yang sesuai (ditandai di alur berikut).
- Alur:
  1. Mulai. (Admin)
  2. "Mengakses Formulir Buat Tiket". (Admin)
  3. "Mengisi Data Pelapor, Isi Aduan, Kategori, Prioritas, dan OPD". (Admin)
  4. "Menyimpan Tiket". (Admin)
  5. Decision: "Data valid dan lengkap?". (Sistem)
     - Jika "Tidak": "Menampilkan Pesan Kesalahan" -> Loop kembali ke "Mengisi Data Pelapor...". (Sistem -> Admin)
     - Jika "Ya": "Menghasilkan Nomor Pelacakan dan Menetapkan SLA". (Sistem)
  6. "Menyimpan Data Tiket ke Basis Data". (Sistem)
  7. "Meneruskan Tiket ke Dashboard OPD Tujuan". (Sistem)
  8. Selesai.
- Berikan label pada setiap percabangan panah (Ya/Tidak).
```

---

## 5. Activity Diagram Manajemen Data dan Akun OPD
**Aktor:** Admin, Sistem

**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Manajemen Data dan Akun OPD" dengan spesifikasi berikut:
- Format: `flowchart LR` sebagai root, dengan swimlane per aktor menggunakan `subgraph` dan `direction TB` di dalam masing-masing subgraph.
- Aktor yang terlibat: "Admin" dan "Sistem".
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Gunakan node bentuk stadium/rounded untuk Mulai dan Selesai, bentuk persegi panjang untuk proses, dan bentuk diamond (rhombus) untuk decision.
- Setiap node harus ditempatkan pada subgraph aktor yang sesuai (ditandai di alur berikut).
- Alur:
  1. Mulai. (Admin)
  2. "Mengakses Halaman Manajemen OPD". (Admin)
  3. Decision: "Pilih Tindakan?". (Admin)
     - Jalur 1 "Tambah": "Mengisi Formulir Data Instansi dan Kredensial Akun". (Admin)
     - Jalur 2 "Ubah": "Mengubah Data Instansi atau Kredensial Akun". (Admin)
     - Jalur 3 "Hapus": "Menekan Tombol Hapus OPD". (Admin)
  4. Dari jalur "Tambah" dan "Ubah", masuk ke "Menyimpan Data". (Admin)
  5. Dari "Menyimpan Data", masuk ke Decision: "Validasi Data?". (Sistem)
     - Jika "Tidak": Loop kembali ke pengisian form (masing-masing sesuai jalur Tambah atau Ubah). (Admin)
     - Jika "Ya": "Memperbarui Data OPD di Basis Data" -> Selesai. (Sistem)
  6. Dari jalur "Hapus", masuk ke Decision: "Konfirmasi Hapus?". (Admin)
     - Jika "Batal": Lanjut ke Selesai.
     - Jika "Ya": "Menghapus Data OPD dari Basis Data" -> Selesai. (Sistem)
- Pastikan tiga jalur (Tambah/Ubah/Hapus) tergambar sebagai percabangan yang jelas dan tidak tumpang tindih secara layout.
- Berikan label pada setiap percabangan panah secara jelas.
```

---

## 6. Activity Diagram Pelacakan Tiket oleh Masyarakat
**Aktor:** Masyarakat, Sistem

**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Pelacakan Tiket oleh Masyarakat" dengan spesifikasi berikut:
- Format: `flowchart LR` sebagai root, dengan swimlane per aktor menggunakan `subgraph` dan `direction TB` di dalam masing-masing subgraph.
- Aktor yang terlibat: "Masyarakat" dan "Sistem".
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Gunakan node bentuk stadium/rounded untuk Mulai dan Selesai, bentuk persegi panjang untuk proses, dan bentuk diamond (rhombus) untuk decision.
- Setiap node harus ditempatkan pada subgraph aktor yang sesuai (ditandai di alur berikut).
- Alur:
  1. Mulai. (Masyarakat)
  2. "Mengakses Portal Publik SIMADU-KMC". (Masyarakat)
  3. "Memasukkan Nomor Pelacakan (Resi)". (Masyarakat)
  4. "Mencari Data Tiket di Basis Data". (Sistem)
  5. Decision: "Nomor pelacakan ditemukan?". (Sistem)
     - Jika "Tidak": "Menampilkan Pesan Tiket Tidak Ditemukan" -> Selesai. (Sistem)
     - Jika "Ya": "Menampilkan Informasi Tiket, Status, OPD, dan Riwayat Penanganan" -> Selesai. (Sistem)
- Berikan label pada setiap percabangan panah (Ya/Tidak).
```
