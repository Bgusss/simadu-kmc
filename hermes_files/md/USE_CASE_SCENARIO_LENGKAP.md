# Skenario Use Case Sistem Informasi Manajemen Aduan Multi Channel KMC

Berikut adalah detail skenario use case untuk setiap fungsi yang terdapat pada Use Case Diagram.

---

### 1. Skenario Use Case Login
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Login |
| **Aktor** | Admin KMC, Pengguna OPD |
| **Tujuan** | Memverifikasi identitas aktor agar dapat mengakses fitur sesuai hak aksesnya. |
| **Kondisi Awal** | Aktor belum masuk ke dalam sistem dan berada di halaman login. |
| **Kondisi Akhir** | Aktor berhasil masuk dan diarahkan ke dashboard masing-masing. |
| **Alur Utama (Normal)** | 1. Aktor memasukkan email/username dan kata sandi.<br>2. Aktor menekan tombol masuk.<br>3. Sistem memvalidasi kredensial aktor.<br>4. Sistem mengarahkan Admin KMC ke Dashboard Admin atau Pengguna OPD ke Dashboard OPD. |
| **Alur Alternatif** | Jika kredensial tidak valid:<br>3a. Sistem menampilkan pesan peringatan bahwa email/username atau kata sandi salah.<br>4a. Sistem mengembalikan aktor ke halaman login. |

---

### 2. Skenario Use Case Melihat Dashboard Admin
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Melihat Dashboard Admin |
| **Aktor** | Admin KMC |
| **Tujuan** | Memantau ringkasan informasi, statistik singkat, dan notifikasi prioritas tinggi. |
| **Kondisi Awal** | Admin KMC telah berhasil login. |
| **Kondisi Akhir** | Admin KMC melihat ringkasan data aduan dan tiket secara *real-time*. |
| **Alur Utama (Normal)** | 1. Admin memilih menu Dashboard.<br>2. Sistem menghitung dan memuat ringkasan data tiket, notifikasi terbaru, dan notifikasi prioritas tinggi.<br>3. Sistem menampilkan informasi tersebut pada halaman dashboard Admin. |
| **Alur Alternatif** | Tidak ada. |

---

### 3. Skenario Use Case Memantau Notifikasi dan Hasil Klasifikasi
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Memantau Notifikasi dan Hasil Klasifikasi |
| **Aktor** | Admin KMC |
| **Tujuan** | Melihat daftar pesan masuk dari media sosial yang telah disaring dan diklasifikasikan oleh layanan AI. |
| **Kondisi Awal** | Admin KMC berada pada halaman sistem. |
| **Kondisi Akhir** | Admin KMC mengetahui informasi aduan terbaru beserta rekomendasi kategorinya. |
| **Alur Utama (Normal)** | 1. Admin memilih menu Notifikasi.<br>2. Sistem menampilkan daftar notifikasi aduan beserta hasil rekomendasi kategori, subkategori, OPD tujuan, dan prioritas.<br>3. Admin menekan salah satu notifikasi untuk melihat detail pesan. |
| **Alur Alternatif** | Jika tidak ada notifikasi baru:<br>2a. Sistem menampilkan pesan "Tidak ada notifikasi aduan baru". |

---

### 4. Skenario Use Case Memverifikasi Kemungkinan Duplikasi
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Memverifikasi Kemungkinan Duplikasi |
| **Aktor** | Admin KMC |
| **Tujuan** | Mengonfirmasi apakah notifikasi baru merupakan duplikat dari aduan yang sudah ada tiketnya. |
| **Kondisi Awal** | Terdapat notifikasi yang ditandai sistem sebagai "Kemungkinan Duplikasi". |
| **Kondisi Akhir** | Notifikasi diarsipkan (jika duplikat) atau dibuatkan tiket (jika bukan duplikat). |
| **Alur Utama (Normal)** | 1. Admin membuka notifikasi yang terdeteksi sebagai kemungkinan duplikasi.<br>2. Sistem menampilkan aduan baru bersandingan dengan aduan lama yang mirip.<br>3. Admin menekan tombol "Konfirmasi Duplikat".<br>4. Sistem mengubah status notifikasi menjadi diarsipkan tanpa membuat tiket. |
| **Alur Alternatif** | Jika admin menilai aduan tersebut bukan duplikat:<br>3a. Admin menekan tombol "Bukan Duplikat".<br>4a. Sistem membuat tiket baru berdasarkan hasil analisis notifikasi tersebut dan meneruskannya ke OPD terkait. |

---

### 5. Skenario Use Case Mengelola Tiket
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Mengelola Tiket |
| **Aktor** | Admin KMC |
| **Tujuan** | Melihat detail tiket, membuat tiket secara manual, atau mengubah atribut tiket (kategori, OPD tujuan, prioritas). |
| **Kondisi Awal** | Admin KMC berada di halaman Manajemen Tiket. |
| **Kondisi Akhir** | Data tiket berhasil disimpan atau diperbarui di dalam sistem. |
| **Alur Utama (Normal)** | 1. Admin membuka daftar tiket.<br>2. Admin memilih tiket yang ingin diubah.<br>3. Admin mengubah kategori, subkategori, atau OPD tujuan.<br>4. Admin menyimpan perubahan.<br>5. Sistem menyimpan pembaruan dan mencatat riwayat perubahan. |
| **Alur Alternatif** | Jika Admin membuat tiket manual:<br>1a. Admin menekan tombol "Buat Tiket".<br>2a. Admin mengisi formulir data aduan, pelapor, dan memilih OPD tujuan.<br>3a. Admin menyimpan data.<br>4a. Sistem menghasilkan nomor tiket dan menyimpannya. |

---

### 6. Skenario Use Case Mengelola Data dan Akun OPD
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Mengelola Data dan Akun OPD |
| **Aktor** | Admin KMC |
| **Tujuan** | Menambah, mengubah, atau menghapus data instansi OPD beserta akun penggunanya. |
| **Kondisi Awal** | Admin KMC berada di halaman Manajemen OPD. |
| **Kondisi Akhir** | Data dan akun OPD berhasil diperbarui. |
| **Alur Utama (Normal)** | 1. Admin memilih menu Manajemen OPD.<br>2. Sistem menampilkan daftar OPD.<br>3. Admin memilih fungsi tambah/edit data.<br>4. Admin memasukkan nama OPD, profil, dan data kredensial akses (email/password).<br>5. Admin menekan tombol simpan.<br>6. Sistem memvalidasi dan menyimpan data. |
| **Alur Alternatif** | Jika data yang diinput tidak lengkap atau format email salah:<br>6a. Sistem menampilkan pesan error validasi.<br>7a. Admin memperbaiki isian formulir. |

---

### 7. Skenario Use Case Melihat Statistik Aduan
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Melihat Statistik Aduan |
| **Aktor** | Admin KMC |
| **Tujuan** | Melihat laporan atau grafik perkembangan aduan. |
| **Kondisi Awal** | Admin KMC telah berhasil login. |
| **Kondisi Akhir** | Admin KMC mendapatkan informasi tren pengelolaan tiket. |
| **Alur Utama (Normal)** | 1. Admin memilih menu Statistik/Laporan.<br>2. Sistem mengambil data rekapitulasi tiket berdasarkan rentang waktu, status, dan platform sumber.<br>3. Sistem menampilkan visualisasi data dalam bentuk grafik atau tabel. |
| **Alur Alternatif** | Tidak ada. |

---

### 8. Skenario Use Case Mengelola Profil
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Mengelola Profil |
| **Aktor** | Admin KMC, Pengguna OPD |
| **Tujuan** | Memperbarui data profil pribadi seperti nama, email, foto, dan kata sandi. |
| **Kondisi Awal** | Aktor sedang berada di dalam sistem. |
| **Kondisi Akhir** | Data profil aktor diperbarui. |
| **Alur Utama (Normal)** | 1. Aktor memilih menu Profil.<br>2. Sistem menampilkan data profil aktor saat ini.<br>3. Aktor mengubah informasi yang diinginkan atau memasukkan kata sandi baru.<br>4. Aktor menyimpan perubahan.<br>5. Sistem menyimpan pembaruan profil ke database. |
| **Alur Alternatif** | Jika konfirmasi kata sandi baru tidak cocok:<br>5a. Sistem menampilkan peringatan kata sandi tidak cocok.<br>6a. Aktor mengulang pengisian kata sandi. |

---

### 9. Skenario Use Case Melihat Dashboard OPD
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Melihat Dashboard OPD |
| **Aktor** | Pengguna OPD |
| **Tujuan** | Memantau ringkasan tiket aduan yang menjadi tanggung jawab instansinya. |
| **Kondisi Awal** | Pengguna OPD telah berhasil login. |
| **Kondisi Akhir** | Pengguna OPD melihat ringkasan tiket khusus untuk instansinya. |
| **Alur Utama (Normal)** | 1. Pengguna OPD mengakses halaman utama.<br>2. Sistem menyaring data tiket hanya untuk OPD yang bersangkutan.<br>3. Sistem menampilkan ringkasan jumlah tiket masuk, diproses, dan selesai. |
| **Alur Alternatif** | Tidak ada. |

---

### 10. Skenario Use Case Melihat Tiket OPD
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Melihat Tiket OPD |
| **Aktor** | Pengguna OPD |
| **Tujuan** | Membaca daftar dan detail aduan yang ditugaskan kepada OPD terkait. |
| **Kondisi Awal** | Pengguna OPD berada di Dashboard OPD. |
| **Kondisi Akhir** | Pengguna OPD melihat rincian keluhan, pelapor, dan riwayat penanganan tiket. |
| **Alur Utama (Normal)** | 1. Pengguna OPD memilih menu Daftar Tiket.<br>2. Sistem menampilkan seluruh tiket yang ditugaskan ke OPD tersebut.<br>3. Pengguna OPD memilih salah satu tiket.<br>4. Sistem menampilkan halaman detail tiket berisi deskripsi aduan dan lampiran (jika ada). |
| **Alur Alternatif** | Jika pengguna mencoba mengakses URL tiket milik OPD lain:<br>4a. Sistem menolak akses dan menampilkan pesan error otoritas (Hak Akses Ditolak). |

---

### 11. Skenario Use Case Memberikan Tanggapan Tiket
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Memberikan Tanggapan Tiket |
| **Aktor** | Pengguna OPD |
| **Tujuan** | Mengirimkan balasan penjelasan atau solusi terkait aduan kepada sistem. |
| **Kondisi Awal** | Pengguna OPD sedang membuka halaman detail suatu tiket. |
| **Kondisi Akhir** | Tanggapan tersimpan dalam riwayat tiket. |
| **Alur Utama (Normal)** | 1. Pengguna OPD menuliskan pesan tanggapan pada kolom yang tersedia.<br>2. Pengguna OPD menambahkan lampiran foto bukti penanganan (opsional).<br>3. Pengguna OPD menekan tombol Kirim Tanggapan.<br>4. Sistem menyimpan pesan dan lampiran ke dalam riwayat tiket. |
| **Alur Alternatif** | Jika ukuran file lampiran melebihi batas sistem:<br>4a. Sistem menolak proses dan menampilkan pesan batas maksimal file.<br>5a. Pengguna OPD mengganti file dengan ukuran yang lebih kecil. |

---

### 12. Skenario Use Case Memperbarui Status Tiket
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Memperbarui Status Tiket |
| **Aktor** | Pengguna OPD |
| **Tujuan** | Mengubah status progres tiket (misal: Diproses, Dijawab, Selesai). |
| **Kondisi Awal** | Pengguna OPD sedang membuka halaman detail tiket yang belum ditutup. |
| **Kondisi Akhir** | Status tiket berubah dan dicatat dalam riwayat penanganan. |
| **Alur Utama (Normal)** | 1. Pengguna OPD memilih opsi status terbaru pada menu pembaruan status.<br>2. Pengguna OPD menambahkan catatan pembaruan status.<br>3. Pengguna OPD menyimpan pembaruan.<br>4. Sistem mengubah status tiket dan menyimpan log riwayat perubahan status. |
| **Alur Alternatif** | Tidak ada. |

---

### 13. Skenario Use Case Melacak Tiket
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Melacak Tiket |
| **Aktor** | Masyarakat |
| **Tujuan** | Melihat perkembangan penanganan aduan tanpa harus memiliki akun. |
| **Kondisi Awal** | Masyarakat berada di portal publik sistem. |
| **Kondisi Akhir** | Sistem menampilkan progres tiket yang dicari. |
| **Alur Utama (Normal)** | 1. Masyarakat memasukkan Nomor Pelacakan (Resi) tiket ke dalam kolom pencarian.<br>2. Masyarakat menekan tombol Lacak.<br>3. Sistem mencari data tiket berdasarkan nomor pelacakan.<br>4. Sistem menampilkan status saat ini, OPD yang menangani, dan riwayat singkat tiket kepada masyarakat. |
| **Alur Alternatif** | Jika nomor pelacakan salah atau tidak ditemukan:<br>3a. Sistem tidak menemukan data yang cocok.<br>4a. Sistem menampilkan pesan peringatan "Tiket tidak ditemukan. Pastikan nomor pelacakan benar". |

---

### 14. Skenario Use Case Melihat Informasi Pemantauan Aduan Publik
| Komponen | Deskripsi |
|---|---|
| **Nama Use Case** | Melihat Informasi Pemantauan Aduan Publik |
| **Aktor** | Masyarakat |
| **Tujuan** | Melihat ringkasan data aduan publik yang ditangani oleh KMC secara transparan. |
| **Kondisi Awal** | Masyarakat berada di portal publik sistem. |
| **Kondisi Akhir** | Sistem menampilkan ringkasan pengelolaan tiket yang bersifat terbuka. |
| **Alur Utama (Normal)** | 1. Masyarakat mengakses halaman utama pemantauan publik.<br>2. Sistem mengambil data ringkasan agregat tiket (jumlah aduan masuk, selesai, dll).<br>3. Sistem menampilkan statistik sederhana beserta daftar aduan publik terkini yang disamarkan identitasnya. |
| **Alur Alternatif** | Tidak ada. |