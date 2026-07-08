# USE CASE SCENARIO
## Sistem Informasi Manajemen Aduan Multi Channel KMC

---

## Daftar Aktor

| No | Aktor | Deskripsi |
|----|-------|-----------|
| 1 | **Admin** | Staff Ketapang Media Center (KMC) yang mengelola seluruh aduan, notifikasi, tiket, data OPD, dan pengguna sistem |
| 2 | **OPD** | Staff Organisasi Perangkat Daerah yang menerima disposisi tiket dan memberikan tanggapan/tindak lanjut |
| 3 | **Masyarakat** | Pengguna yang mengakses halaman publik untuk melacak status tiket aduan berdasarkan nomor tracking |

---

## Daftar Use Case

| No | Use Case | Aktor |
|----|----------|-------|
| UC-01 | Login | Admin, OPD |
| UC-02 | Logout | Admin, OPD |
| UC-03 | Melihat Dashboard | Admin, OPD |
| UC-04 | Sinkronisasi Aduan Media Sosial | Admin (via Artisan Command) |
| UC-05 | Melihat Daftar Notifikasi Aduan | Admin |
| UC-06 | Melihat Detail Notifikasi Aduan | Admin |
| UC-07 | Klasifikasi Aduan dengan AI | Admin |
| UC-08 | Deteksi Duplikasi Aduan | Admin |
| UC-09 | Membuat Tiket Aduan | Admin |
| UC-10 | Melihat Daftar Tiket | Admin, OPD |
| UC-11 | Melihat Detail Tiket | Admin, OPD |
| UC-12 | Mengedit Tiket | Admin |
| UC-13 | Menghapus Tiket | Admin |
| UC-14 | Disposisi Tiket ke OPD | Admin |
| UC-15 | Menanggapi Tiket | Admin, OPD |
| UC-16 | Mengubah Status Tiket | Admin, OPD |
| UC-17 | Eskalasi Prioritas Otomatis (SLA) | Sistem (Otomatis) |
| UC-18 | Mengelola Data OPD | Admin |
| UC-19 | Mengelola Profil | Admin, OPD |
| UC-20 | Melacak Status Tiket (Publik) | Masyarakat |

---

## Use Case Scenario

---

### UC-01: Login

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Login |
| **Aktor** | Admin, OPD |
| **Deskripsi** | Aktor melakukan autentikasi untuk masuk ke dalam sistem dengan memasukkan email dan password |
| **Pre-Condition** | Aktor sudah memiliki akun yang terdaftar dalam sistem dan belum login |
| **Post-Condition** | Aktor berhasil masuk ke sistem dan diarahkan ke halaman dashboard sesuai role-nya |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Aktor mengakses halaman login | Sistem menampilkan form login (email dan password) |
| 2 | Aktor mengisi email dan password, lalu menekan tombol "Login" | Sistem memvalidasi data yang dimasukkan |
| 3 | | Sistem memverifikasi email dan password dengan data di database |
| 4 | | Sistem membuat session dan mengarahkan aktor ke halaman dashboard sesuai role (Admin → Dashboard Admin, OPD → Dashboard OPD) |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 3a | Email atau password salah | Sistem menampilkan pesan "Email atau password salah" dan aktor tetap berada di halaman login |
| 3b | Field email atau password kosong | Sistem menampilkan pesan validasi "Field ini wajib diisi" |

---

### UC-02: Logout

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Logout |
| **Aktor** | Admin, OPD |
| **Deskripsi** | Aktor keluar dari sistem dan mengakhiri sesi yang sedang berjalan |
| **Pre-Condition** | Aktor sudah login ke dalam sistem |
| **Post-Condition** | Sesi aktor berakhir dan aktor diarahkan kembali ke halaman login |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Aktor menekan tombol "Logout" | Sistem menghapus session aktor |
| 2 | | Sistem mengarahkan aktor kembali ke halaman login |

---

### UC-03: Melihat Dashboard

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Melihat Dashboard |
| **Aktor** | Admin, OPD |
| **Deskripsi** | Aktor melihat halaman utama sistem yang menampilkan ringkasan data aduan, tiket, dan notifikasi |
| **Pre-Condition** | Aktor sudah login ke dalam sistem |
| **Post-Condition** | Sistem menampilkan halaman dashboard dengan data ringkasan sesuai role aktor |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Aktor mengakses menu Dashboard | Sistem mengambil data ringkasan dari database |
| 2 | | **Dashboard Admin:** Sistem menampilkan jumlah notifikasi baru, notifikasi prioritas tinggi, total tiket (berdasarkan status: baru, proses, selesai), daftar notifikasi terbaru, dan daftar tiket overdue SLA |
| 3 | | **Dashboard OPD:** Sistem menampilkan jumlah tiket yang didisposisi ke OPD tersebut, tiket yang perlu ditanggapi, dan status tiket |

---

### UC-04: Sinkronisasi Aduan Media Sosial

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Sinkronisasi Aduan Media Sosial |
| **Aktor** | Admin (melalui Artisan Command) |
| **Deskripsi** | Sistem mengambil aduan terbaru dari media sosial (Facebook Post Mentions, Facebook Comment Mentions, Instagram DM) menggunakan scraper Playwright dan menyimpannya sebagai notifikasi baru |
| **Pre-Condition** | Koneksi internet tersedia, akun media sosial KMC sudah dikonfigurasi, dan Node.js serta Playwright sudah terpasang |
| **Post-Condition** | Aduan baru dari media sosial tersimpan di database sebagai notifikasi dan ditampilkan pada dashboard admin |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin menjalankan perintah sinkronisasi melalui terminal (artisan command) | Sistem menjalankan scraper Playwright sesuai kanal yang dipilih |
| 2 | | Sistem membuka browser otomatis dan mengakses halaman media sosial KMC |
| 3 | | Sistem mengekstrak data aduan (pengirim, isi pesan, waktu, platform) |
| 4 | | Sistem mengirimkan isi aduan ke AI (Gemma 4 31B IT via Google AI Studio) untuk klasifikasi otomatis |
| 5 | | Sistem menerima hasil klasifikasi AI (kategori, sub-kategori, prioritas, OPD, confidence score, deteksi duplikasi) |
| 6 | | Sistem menyimpan data aduan beserta hasil klasifikasi sebagai notifikasi baru di database |
| 7 | | Sistem menampilkan notifikasi baru di dashboard admin |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 2a | Koneksi ke media sosial gagal | Sistem menampilkan pesan error di terminal dan proses dihentikan |
| 4a | API Google AI Studio tidak tersedia | Sistem menyimpan aduan tanpa hasil klasifikasi AI dan menandai status klasifikasi sebagai "pending" |
| 5a | AI mendeteksi pesan sebagai bukan aduan (spam/tidak relevan) | Sistem menandai notifikasi dengan label "bukan aduan" dan tetap menyimpan ke database |

---

### UC-05: Melihat Daftar Notifikasi Aduan

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Melihat Daftar Notifikasi Aduan |
| **Aktor** | Admin |
| **Deskripsi** | Admin melihat seluruh daftar aduan yang masuk dari media sosial beserta hasil klasifikasi AI |
| **Pre-Condition** | Admin sudah login ke dalam sistem |
| **Post-Condition** | Sistem menampilkan daftar notifikasi aduan dengan informasi platform, pengirim, isi pesan, hasil klasifikasi, dan status |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin mengakses menu Notifikasi | Sistem mengambil data notifikasi dari database |
| 2 | | Sistem menampilkan daftar notifikasi berupa tabel dengan kolom: platform (Facebook/Instagram), pengirim, ringkasan isi, kategori AI, prioritas AI, waktu masuk, dan status (baru/sudah dibuat tiket) |
| 3 | Admin dapat menekan tombol refresh untuk mengecek notifikasi terbaru | Sistem melakukan polling data terbaru dan memperbarui tampilan |

---

### UC-06: Melihat Detail Notifikasi Aduan

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Melihat Detail Notifikasi Aduan |
| **Aktor** | Admin |
| **Deskripsi** | Admin melihat detail lengkap dari satu notifikasi aduan termasuk hasil klasifikasi AI |
| **Pre-Condition** | Admin sudah login dan terdapat notifikasi aduan di sistem |
| **Post-Condition** | Sistem menampilkan detail notifikasi aduan secara lengkap |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin menekan salah satu notifikasi dari daftar | Sistem mengambil data detail notifikasi dari database |
| 2 | | Sistem menampilkan informasi lengkap: isi pesan asli, platform asal, pengirim, waktu, hasil klasifikasi AI (kategori, sub-kategori, OPD rekomendasi, prioritas, confidence score), dan status duplikasi |

---

### UC-07: Klasifikasi Aduan dengan AI

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Klasifikasi Aduan dengan AI |
| **Aktor** | Admin |
| **Deskripsi** | Sistem memberikan rekomendasi klasifikasi aduan menggunakan model AI Gemma 4 31B IT melalui Google AI Studio, meliputi kategori, sub-kategori, OPD tujuan, dan tingkat prioritas |
| **Pre-Condition** | Admin sudah login, terdapat notifikasi aduan yang belum diklasifikasi, dan koneksi ke Google AI Studio tersedia |
| **Post-Condition** | Notifikasi aduan memiliki hasil klasifikasi AI yang dapat dijadikan rekomendasi oleh admin |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Proses klasifikasi berjalan otomatis saat sinkronisasi aduan | Sistem mengirimkan isi pesan aduan ke Gemma 4 31B IT via Gemini API |
| 2 | | Model AI menganalisis isi pesan menggunakan NLP dan memberikan output berupa JSON terstruktur |
| 3 | | Sistem menerima hasil klasifikasi: kategori, sub-kategori, OPD rekomendasi, tingkat prioritas (rendah/sedang/tinggi), dan confidence score |
| 4 | | Sistem menyimpan hasil klasifikasi bersama data notifikasi di database |
| 5 | Admin melihat hasil klasifikasi AI pada halaman detail notifikasi | Sistem menampilkan rekomendasi AI yang dapat diterima, diubah, atau ditolak oleh admin |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 1a | API Google AI Studio tidak tersedia (rate limit/error) | Sistem menyimpan aduan tanpa klasifikasi dan menandai status sebagai "pending classification" |
| 5a | Admin tidak setuju dengan hasil klasifikasi AI | Admin dapat mengubah kategori, sub-kategori, prioritas, dan OPD secara manual saat membuat tiket |

---

### UC-08: Deteksi Duplikasi Aduan

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Deteksi Duplikasi Aduan |
| **Aktor** | Admin |
| **Deskripsi** | Sistem mendeteksi apakah aduan yang baru masuk merupakan duplikasi dari aduan sebelumnya dengan membandingkan kemiripan semantik menggunakan AI |
| **Pre-Condition** | Admin sudah login dan terdapat notifikasi aduan baru di sistem |
| **Post-Condition** | Aduan yang terdeteksi sebagai duplikat ditandai dan admin dapat mengkonfirmasi atau menolak status duplikasi |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Proses deteksi duplikasi berjalan otomatis saat sinkronisasi aduan | Sistem mengirimkan isi aduan ke model AI untuk dibandingkan dengan aduan-aduan sebelumnya |
| 2 | | Model AI membandingkan kemiripan semantik dan menentukan apakah aduan tersebut merupakan duplikat |
| 3 | | Jika terdeteksi sebagai duplikat, sistem menandai notifikasi dengan label "kemungkinan duplikat" beserta referensi aduan asli |
| 4 | Admin melihat label duplikasi pada halaman detail notifikasi | Sistem menampilkan informasi duplikasi dan tombol konfirmasi |
| 5 | Admin menekan tombol "Konfirmasi Duplikat" atau "Bukan Duplikat" | Sistem memperbarui status duplikasi di database |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 2a | AI tidak mendeteksi kemiripan dengan aduan sebelumnya | Sistem tidak menandai notifikasi sebagai duplikat dan proses berlanjut normal |
| 5a | Admin mengkonfirmasi sebagai duplikat | Sistem menandai aduan sebagai duplikat; admin dapat memilih untuk tidak membuat tiket baru |
| 5b | Admin menolak status duplikat | Sistem menghapus label duplikasi dan aduan diperlakukan sebagai aduan baru |

---

### UC-09: Membuat Tiket Aduan

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Membuat Tiket Aduan |
| **Aktor** | Admin |
| **Deskripsi** | Admin membuat tiket aduan berdasarkan notifikasi yang masuk, dengan menggunakan atau mengubah rekomendasi klasifikasi AI |
| **Pre-Condition** | Admin sudah login dan terdapat notifikasi aduan yang belum dibuatkan tiket |
| **Post-Condition** | Tiket aduan berhasil dibuat dengan nomor tracking unik dan tersimpan di database |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin menekan tombol "Buat Tiket" pada halaman notifikasi | Sistem menampilkan form pembuatan tiket dengan data rekomendasi AI yang sudah terisi otomatis (kategori, sub-kategori, OPD, prioritas) |
| 2 | Admin memeriksa dan dapat mengubah data rekomendasi AI sesuai kebutuhan | Sistem menampilkan dropdown untuk kategori, sub-kategori, OPD, dan prioritas |
| 3 | Admin menekan tombol "Simpan Tiket" | Sistem memvalidasi data tiket |
| 4 | | Sistem menyimpan tiket ke database dengan nomor tracking unik, mencatat waktu pembuatan sebagai awal SLA 24 jam |
| 5 | | Sistem memperbarui status notifikasi menjadi "sudah dibuat tiket" |
| 6 | | Sistem menampilkan pesan "Tiket berhasil dibuat" dan mengarahkan admin ke halaman detail tiket |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 3a | Data tiket tidak lengkap (field wajib kosong) | Sistem menampilkan pesan validasi dan admin tetap di halaman form |

---

### UC-10: Melihat Daftar Tiket

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Melihat Daftar Tiket |
| **Aktor** | Admin, OPD |
| **Deskripsi** | Aktor melihat daftar seluruh tiket aduan yang ada di sistem |
| **Pre-Condition** | Aktor sudah login ke dalam sistem |
| **Post-Condition** | Sistem menampilkan daftar tiket sesuai hak akses aktor |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Aktor mengakses menu Tiket | Sistem mengambil data tiket dari database |
| 2 | | **Admin:** Sistem menampilkan seluruh tiket dengan kolom nomor tracking, judul, kategori, prioritas, status, OPD tujuan, dan waktu pembuatan |
| 3 | | **OPD:** Sistem hanya menampilkan tiket yang didisposisikan ke OPD tersebut |

---

### UC-11: Melihat Detail Tiket

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Melihat Detail Tiket |
| **Aktor** | Admin, OPD |
| **Deskripsi** | Aktor melihat informasi lengkap dari satu tiket aduan termasuk histori tanggapan |
| **Pre-Condition** | Aktor sudah login dan terdapat tiket di sistem |
| **Post-Condition** | Sistem menampilkan detail tiket secara lengkap |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Aktor menekan salah satu tiket dari daftar | Sistem mengambil data detail tiket dari database |
| 2 | | Sistem menampilkan informasi lengkap: nomor tracking, judul, isi aduan asli, kategori, sub-kategori, OPD tujuan, prioritas, status, waktu pembuatan, batas SLA, dan histori tanggapan |

---

### UC-12: Mengedit Tiket

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Mengedit Tiket |
| **Aktor** | Admin |
| **Deskripsi** | Admin mengubah data tiket aduan yang sudah dibuat, seperti kategori, prioritas, atau OPD tujuan |
| **Pre-Condition** | Admin sudah login dan terdapat tiket yang akan diedit |
| **Post-Condition** | Data tiket berhasil diperbarui di database |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin menekan tombol "Edit" pada halaman detail tiket | Sistem menampilkan form edit dengan data tiket yang sudah terisi |
| 2 | Admin mengubah data yang diperlukan (kategori, sub-kategori, prioritas, OPD) | Sistem menampilkan dropdown dengan pilihan yang tersedia |
| 3 | Admin menekan tombol "Simpan Perubahan" | Sistem memvalidasi dan menyimpan perubahan ke database |
| 4 | | Sistem menampilkan pesan "Tiket berhasil diperbarui" |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 3a | Data tidak valid atau field wajib kosong | Sistem menampilkan pesan validasi dan admin tetap di halaman form edit |

---

### UC-13: Menghapus Tiket

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Menghapus Tiket |
| **Aktor** | Admin |
| **Deskripsi** | Admin menghapus tiket aduan dari sistem |
| **Pre-Condition** | Admin sudah login dan terdapat tiket yang akan dihapus |
| **Post-Condition** | Tiket berhasil dihapus dari database |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin menekan tombol "Hapus" pada halaman detail tiket | Sistem menampilkan dialog konfirmasi "Apakah Anda yakin ingin menghapus tiket ini?" |
| 2 | Admin menekan tombol "Ya, Hapus" | Sistem menghapus tiket dari database |
| 3 | | Sistem menampilkan pesan "Tiket berhasil dihapus" dan mengarahkan admin ke halaman daftar tiket |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 2a | Admin menekan tombol "Batal" | Sistem menutup dialog konfirmasi dan admin tetap di halaman detail tiket |

---

### UC-14: Disposisi Tiket ke OPD

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Disposisi Tiket ke OPD |
| **Aktor** | Admin |
| **Deskripsi** | Admin mendisposisikan tiket aduan ke OPD terkait sesuai bidang permasalahan agar ditindaklanjuti |
| **Pre-Condition** | Admin sudah login, tiket sudah dibuat, dan OPD tujuan sudah ditentukan |
| **Post-Condition** | Tiket berhasil didisposisikan ke OPD dan terlihat di dashboard OPD yang bersangkutan |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin memilih OPD tujuan saat membuat atau mengedit tiket | Sistem menampilkan daftar OPD yang terdaftar dalam sistem |
| 2 | Admin memilih OPD yang sesuai dan menyimpan | Sistem menyimpan disposisi ke database dan mengubah status tiket menjadi "Disposisi" |
| 3 | | Sistem mengirimkan notifikasi ke dashboard OPD yang bersangkutan |
| 4 | | Sistem mulai menghitung waktu SLA 24 jam untuk penanganan oleh OPD |

---

### UC-15: Menanggapi Tiket

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Menanggapi Tiket |
| **Aktor** | Admin, OPD |
| **Deskripsi** | Aktor memberikan tanggapan atau tindak lanjut terhadap tiket aduan |
| **Pre-Condition** | Aktor sudah login dan terdapat tiket yang perlu ditanggapi |
| **Post-Condition** | Tanggapan tersimpan di database dan terlihat pada histori tiket |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Aktor membuka halaman detail tiket | Sistem menampilkan detail tiket dan form tanggapan |
| 2 | Aktor mengisi form tanggapan (isi tanggapan) | |
| 3 | Aktor menekan tombol "Kirim Tanggapan" | Sistem memvalidasi dan menyimpan tanggapan ke database |
| 4 | | Sistem menampilkan tanggapan pada histori tiket dengan timestamp dan nama pengirim |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 3a | Field tanggapan kosong | Sistem menampilkan pesan validasi "Tanggapan wajib diisi" |

---

### UC-16: Mengubah Status Tiket

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Mengubah Status Tiket |
| **Aktor** | Admin, OPD |
| **Deskripsi** | Aktor mengubah status tiket aduan sesuai perkembangan penanganan |
| **Pre-Condition** | Aktor sudah login dan terdapat tiket yang statusnya akan diubah |
| **Post-Condition** | Status tiket berhasil diperbarui di database |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Aktor membuka halaman detail tiket | Sistem menampilkan detail tiket beserta dropdown status |
| 2 | Aktor memilih status baru (Baru → Diproses → Selesai) | Sistem menampilkan pilihan status yang tersedia |
| 3 | Aktor menekan tombol "Update Status" | Sistem memvalidasi dan menyimpan perubahan status ke database |
| 4 | | Sistem menampilkan pesan "Status tiket berhasil diperbarui" |

---

### UC-17: Eskalasi Prioritas Otomatis (SLA)

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Eskalasi Prioritas Otomatis (SLA) |
| **Aktor** | Sistem (Otomatis) |
| **Deskripsi** | Sistem secara otomatis memeriksa tiket yang melewati batas waktu SLA dan meningkatkan prioritas serta mengirimkan notifikasi |
| **Pre-Condition** | Terdapat tiket yang belum selesai dan command `ticket:check-escalation` dijalankan |
| **Post-Condition** | Tiket yang melewati SLA mengalami eskalasi prioritas dan notifikasi dikirimkan |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin menjalankan command `ticket:check-escalation` | Sistem memeriksa seluruh tiket yang belum berstatus "Selesai" |
| 2 | | Sistem menghitung selisih waktu antara waktu pembuatan tiket dengan waktu saat ini |
| 3 | | Jika tiket melewati SLA 1×24 jam dan belum didisposisi: sistem otomatis memproses disposisi |
| 4 | | Jika tiket melewati SLA 2×24 jam: sistem meningkatkan prioritas (rendah → sedang, sedang → tinggi) |
| 5 | | Sistem mengirimkan notifikasi eskalasi ke dashboard admin dan OPD terkait |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 2a | Tidak ada tiket yang melewati batas SLA | Proses selesai tanpa perubahan |
| 4a | Tiket sudah memiliki prioritas "Tinggi" | Sistem tidak meningkatkan prioritas lebih lanjut, tetapi tetap mengirimkan notifikasi overdue |

---

### UC-18: Mengelola Data OPD

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Mengelola Data OPD |
| **Aktor** | Admin |
| **Deskripsi** | Admin mengelola data master OPD dalam sistem, meliputi menambah, melihat, mengedit, dan menghapus data OPD |
| **Pre-Condition** | Admin sudah login ke dalam sistem |
| **Post-Condition** | Data OPD berhasil ditambahkan, diubah, atau dihapus dari database |

**Main Flow — Tambah Data OPD:**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin mengakses menu Kelola OPD | Sistem menampilkan daftar OPD yang terdaftar |
| 2 | Admin menekan tombol "Tambah OPD" | Sistem menampilkan form tambah OPD (nama OPD, singkatan, email, dll.) |
| 3 | Admin mengisi data OPD dan menekan tombol "Simpan" | Sistem memvalidasi data |
| 4 | | Sistem menyimpan data OPD baru ke database dan menampilkan pesan "Data OPD berhasil ditambahkan" |

**Main Flow — Edit Data OPD:**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin menekan tombol "Edit" pada data OPD | Sistem menampilkan form edit dengan data OPD yang sudah terisi |
| 2 | Admin mengubah data dan menekan "Simpan Perubahan" | Sistem memvalidasi dan menyimpan perubahan ke database |

**Main Flow — Hapus Data OPD:**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Admin menekan tombol "Hapus" pada data OPD | Sistem menampilkan dialog konfirmasi |
| 2 | Admin menekan "Ya, Hapus" | Sistem menghapus data OPD dari database |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 3a | Data tidak lengkap saat tambah/edit | Sistem menampilkan pesan validasi |
| 2a (Hapus) | Admin menekan "Batal" | Sistem menutup dialog konfirmasi |

---

### UC-19: Mengelola Profil

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Mengelola Profil |
| **Aktor** | Admin, OPD |
| **Deskripsi** | Aktor melihat dan memperbarui data profil akunnya, seperti nama, email, dan password |
| **Pre-Condition** | Aktor sudah login ke dalam sistem |
| **Post-Condition** | Data profil aktor berhasil diperbarui di database |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Aktor mengakses menu Profil | Sistem menampilkan halaman profil dengan data akun saat ini |
| 2 | Aktor mengubah data yang diinginkan (nama, email, password) | Sistem menampilkan form edit profil |
| 3 | Aktor menekan tombol "Simpan Perubahan" | Sistem memvalidasi data (email unik, password minimal 8 karakter) |
| 4 | | Sistem menyimpan perubahan ke database dan menampilkan pesan "Profil berhasil diperbarui" |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 3a | Email sudah digunakan oleh akun lain | Sistem menampilkan pesan "Email sudah terdaftar" |
| 3b | Password tidak memenuhi syarat | Sistem menampilkan pesan validasi |

---

### UC-20: Melacak Status Tiket (Publik)

| Komponen | Deskripsi |
|----------|-----------|
| **Nama Use Case** | Melacak Status Tiket (Publik) |
| **Aktor** | Masyarakat |
| **Deskripsi** | Masyarakat mengakses halaman publik untuk melacak status tiket aduan berdasarkan nomor tracking |
| **Pre-Condition** | Masyarakat memiliki nomor tracking tiket yang diperoleh dari admin KMC |
| **Post-Condition** | Sistem menampilkan status terkini dari tiket aduan yang dicari |

**Main Flow (Alur Utama):**

| No | Aktor | Sistem |
|----|-------|--------|
| 1 | Masyarakat mengakses halaman pelacakan tiket publik | Sistem menampilkan form pencarian dengan field nomor tracking |
| 2 | Masyarakat memasukkan nomor tracking dan menekan tombol "Lacak" | Sistem mencari tiket berdasarkan nomor tracking di database |
| 3 | | Sistem menampilkan informasi tiket: nomor tracking, kategori aduan, status penanganan, tanggal masuk, dan tanggapan terbaru dari OPD |

**Alternative Flow (Alur Alternatif):**

| No | Kondisi | Aksi |
|----|---------|------|
| 2a | Nomor tracking tidak ditemukan | Sistem menampilkan pesan "Tiket dengan nomor tracking tersebut tidak ditemukan" |
| 2b | Field nomor tracking kosong | Sistem menampilkan pesan validasi "Nomor tracking wajib diisi" |

---

## Ringkasan Hubungan Aktor dan Use Case

| Use Case | Admin | OPD | Masyarakat | Sistem |
|----------|-------|-----|------------|--------|
| UC-01 Login | ✅ | ✅ | | |
| UC-02 Logout | ✅ | ✅ | | |
| UC-03 Melihat Dashboard | ✅ | ✅ | | |
| UC-04 Sinkronisasi Aduan | ✅ | | | |
| UC-05 Melihat Daftar Notifikasi | ✅ | | | |
| UC-06 Melihat Detail Notifikasi | ✅ | | | |
| UC-07 Klasifikasi Aduan dengan AI | ✅ | | | |
| UC-08 Deteksi Duplikasi Aduan | ✅ | | | |
| UC-09 Membuat Tiket Aduan | ✅ | | | |
| UC-10 Melihat Daftar Tiket | ✅ | ✅ | | |
| UC-11 Melihat Detail Tiket | ✅ | ✅ | | |
| UC-12 Mengedit Tiket | ✅ | | | |
| UC-13 Menghapus Tiket | ✅ | | | |
| UC-14 Disposisi Tiket ke OPD | ✅ | | | |
| UC-15 Menanggapi Tiket | ✅ | ✅ | | |
| UC-16 Mengubah Status Tiket | ✅ | ✅ | | |
| UC-17 Eskalasi Prioritas Otomatis | | | | ✅ |
| UC-18 Mengelola Data OPD | ✅ | | | |
| UC-19 Mengelola Profil | ✅ | ✅ | | |
| UC-20 Melacak Status Tiket | | | ✅ | |

---

**Catatan:**
- Use case scenario ini disusun berdasarkan fitur aktual Sistem Informasi Manajemen Aduan Multi Channel KMC
- Setiap use case scenario menjelaskan alur utama (main flow) dan alur alternatif (alternative flow)
- Format mengikuti standar Cockburn (2001) untuk penulisan use case scenario
- Dokumen ini menjadi acuan untuk perancangan activity diagram dan sequence diagram pada BAB III

---

**END OF USE CASE SCENARIO**
