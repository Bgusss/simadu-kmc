# Prompt Generation untuk Visualisasi Activity Diagram (Mermaid)

Berikut adalah *prompt* lengkap dan terstruktur yang dapat Anda gunakan di AI generator mana pun (ChatGPT, Claude, dsb.) untuk menghasilkan kode Mermaid yang akurat, sesuai dengan standar Laporan Tugas Akhir Politeknik Negeri dan alur sistem SIMADU-KMC.

---

## 1. Activity Diagram Pengolahan Aduan dari Media Sosial
**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Pengolahan Aduan dari Media Sosial" dengan spesifikasi berikut:
- Format: `stateDiagram-v2`
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Jangan gunakan swimlane (tanpa aktor), fokus pada alur sistem.
- Alur:
  1. Mulai.
  2. "Sinkronisasi Aduan Facebook dan Instagram".
  3. "Simpan Data Mention".
  4. Decision: "Pesan spam?".
     - Jika "Ya": "Pesan Tidak Diproses (Notifikasi dan tiket tidak dibuat)" -> Selesai.
     - Jika "Tidak": "Simpan Notifikasi".
  5. Dari Simpan Notifikasi lanjut ke "Klasifikasi AI (Kategori, OPD, Prioritas)".
  6. Decision: "Kemungkinan duplikasi?".
     - Jika "Tidak": "Buat Tiket (Nomor lacak, OPD, SLA)" -> Selesai.
     - Jika "Ya": "Tandai Kandidat Duplikasi (Menunggu verifikasi Admin KMC)".
  7. Dari verifikasi masuk ke Decision: "Dikonfirmasi duplikat?".
     - Jika "Ya": "Konfirmasi Duplikat (Tiket tidak dibuat)" -> Selesai.
     - Jika "Tidak": Kembali ke proses "Buat Tiket (Nomor lacak, OPD, SLA)".
- Berikan label pada setiap percabangan panah (Ya/Tidak).
```

---

## 2. Activity Diagram Tindak Lanjut Tiket dan Eskalasi SLA
**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Tindak Lanjut Tiket dan Eskalasi SLA" dengan spesifikasi berikut:
- Format: `stateDiagram-v2`
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Jangan gunakan swimlane.
- Alur:
  1. Mulai.
  2. "Tiket Dibuat (Status: diterima, OPD ditetapkan)".
  3. "Tiket Diteruskan atau Dibaca oleh OPD (Batas waktu penanganan ditetapkan)".
  4. Decision: "Ada respons OPD sebelum SLA?".
     - Jika "Ya": "Simpan Tanggapan dan Perbarui Status (Riwayat penanganan dicatat)".
     - Jika "Tidak": "Proses Disposisi (Tiket masuk proses disposisi, Batas waktu diperbarui)".
  5. Dari Proses Disposisi masuk ke Decision: "Ada respons OPD pada SLA baru?".
     - Jika "Ya": Kembali ke proses "Simpan Tanggapan dan Perbarui Status".
     - Jika "Tidak": "Eskalasi Tiket (Prioritas dinaikkan, SLA diatur kembali)" -> loop kembali ke Decision "Ada respons OPD pada SLA baru?".
  6. Dari "Simpan Tanggapan..." masuk ke Decision: "Tiket selesai?".
     - Jika "Tidak": "Lanjut Tindak Lanjut oleh OPD" -> loop kembali ke awal titik "Tiket Diteruskan atau Dibaca oleh OPD".
     - Jika "Ya": "Perbarui Status selesai" -> Selesai.
- Berikan label pada setiap percabangan panah (Ya/Tidak).
```

---

## 3. Activity Diagram Login dan Logout
**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Login dan Logout" dengan spesifikasi berikut:
- Format: `stateDiagram-v2`
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Jangan gunakan swimlane.
- Alur:
  1. Mulai.
  2. "Mengakses Halaman Login".
  3. "Memasukkan Email dan Kata Sandi".
  4. Decision: "Kredensial valid?".
     - Jika "Tidak": Loop kembali ke "Mengakses Halaman Login".
     - Jika "Ya": Lanjut ke Decision: "Peran (Role)?".
  5. Dari Decision "Peran (Role)?":
     - Jika "Admin": "Menampilkan Dashboard Admin KMC".
     - Jika "OPD": "Menampilkan Dashboard OPD".
  6. Dari kedua proses Dashboard tersebut, panah bersatu (join) menuju proses "Menekan Tombol Logout".
  7. "Mengakhiri Sesi" -> Selesai.
- Berikan label pada setiap percabangan panah (Ya/Tidak/Admin/OPD).
```

---

## 4. Activity Diagram Pembuatan Tiket Manual
**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Pembuatan Tiket Manual" dengan spesifikasi berikut:
- Format: `stateDiagram-v2`
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Jangan gunakan swimlane.
- Alur:
  1. Mulai.
  2. "Mengakses Formulir Buat Tiket".
  3. "Mengisi Data Pelapor, Isi Aduan, Kategori, Prioritas, dan OPD".
  4. "Menyimpan Tiket".
  5. Decision: "Data valid dan lengkap?".
     - Jika "Tidak": "Menampilkan Pesan Kesalahan" -> Loop kembali ke "Mengisi Data Pelapor...".
     - Jika "Ya": "Menghasilkan Nomor Pelacakan dan Menetapkan SLA".
  6. "Menyimpan Data Tiket ke Basis Data".
  7. "Meneruskan Tiket ke Dashboard OPD Tujuan".
  8. Selesai.
- Berikan label pada setiap percabangan panah (Ya/Tidak).
```

---

## 5. Activity Diagram Manajemen Data dan Akun OPD
**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Manajemen Data dan Akun OPD" dengan spesifikasi berikut:
- Format: `stateDiagram-v2`
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Jangan gunakan swimlane.
- Alur:
  1. Mulai.
  2. "Mengakses Halaman Manajemen OPD".
  3. Decision: "Pilih Tindakan?".
     - Jalur 1 "Tambah": "Mengisi Formulir Data Instansi dan Kredensial Akun".
     - Jalur 2 "Ubah": "Mengubah Data Instansi atau Kredensial Akun".
     - Jalur 3 "Hapus": "Menekan Tombol Hapus OPD".
  4. Dari jalur "Tambah" dan "Ubah", masuk ke "Menyimpan Data".
  5. Dari "Menyimpan Data", masuk ke Decision: "Validasi Data?".
     - Jika "Tidak": Loop kembali ke pengisian form.
     - Jika "Ya": "Memperbarui Data OPD di Basis Data" -> Selesai.
  6. Dari jalur "Hapus", masuk ke Decision: "Konfirmasi Hapus?".
     - Jika "Batal": Lanjut ke Selesai.
     - Jika "Ya": "Menghapus Data OPD dari Basis Data" -> Selesai.
- Berikan label pada setiap percabangan panah secara jelas.
```

---

## 6. Activity Diagram Pelacakan Tiket oleh Masyarakat
**Prompt:**
```text
Buatkan kode Mermaid.js untuk Activity Diagram "Pelacakan Tiket oleh Masyarakat" dengan spesifikasi berikut:
- Format: `stateDiagram-v2`
- Gaya penulisan: Bahasa Indonesia formal, standar akademis Tugas Akhir.
- Jangan gunakan swimlane.
- Alur:
  1. Mulai.
  2. "Mengakses Portal Publik SIMADU-KMC".
  3. "Memasukkan Nomor Pelacakan (Resi)".
  4. "Mencari Data Tiket di Basis Data".
  5. Decision: "Nomor pelacakan ditemukan?".
     - Jika "Tidak": "Menampilkan Pesan Tiket Tidak Ditemukan" -> Selesai.
     - Jika "Ya": "Menampilkan Informasi Tiket, Status, OPD, dan Riwayat Penanganan" -> Selesai.
- Berikan label pada setiap percabangan panah (Ya/Tidak).
```
