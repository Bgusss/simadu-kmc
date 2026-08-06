# USE CASE SCENARIO
## Sistem Informasi Manajemen Aduan Multi Channel KMC

Daftar Aktor

## Daftar Aktor

| No. | Aktor | Deskripsi |
| --- | --- | --- |
| 1 | Admin KMC | Pengguna yang mengelola notifikasi, tiket, data dan akun OPD, statistik aduan, serta profil admin. |
| 2 | Pengguna OPD | Pengguna dari OPD yang melihat, menanggapi, dan memperbarui status tiket yang ditugaskan kepada OPD-nya. |
| 3 | Masyarakat | Pengguna portal publik yang melacak tiket dan melihat informasi pemantauan aduan publik. |

## Skenario Use Case

### UC-01 Login

| Nama Use Case | Login |
| --- | --- |
| Aktor | Admin KMC, Pengguna OPD |
| Tujuan | Memverifikasi identitas pengguna dan memberikan akses sesuai peran. |
| Kondisi Awal | Pengguna berada pada halaman login dan memiliki akun terdaftar. |
| Kondisi Akhir | Pengguna masuk ke dashboard sesuai peran. |
| Alur Utama | 1. Pengguna mengisi username atau email dan kata sandi.<br>2. Pengguna menekan tombol Masuk.<br>3. Sistem memvalidasi data masuk.<br>4. Sistem mengarahkan pengguna ke Dashboard Admin atau Dashboard OPD. |
| Alur Alternatif | Jika data masuk tidak sesuai, sistem menampilkan pesan kesalahan dan pengguna tetap berada pada halaman login. |

### UC-02 Melihat Dashboard Admin

| Nama Use Case | Melihat Dashboard Admin |
| --- | --- |
| Aktor | Admin KMC |
| Tujuan | Memantau ringkasan aduan, tiket, dan notifikasi. |
| Kondisi Awal | Admin KMC telah masuk ke sistem. |
| Kondisi Akhir | Ringkasan informasi pengelolaan aduan ditampilkan. |
| Alur Utama | 1. Admin membuka Dashboard Admin.<br>2. Sistem mengambil ringkasan notifikasi dan tiket.<br>3. Sistem menampilkan statistik tiket, notifikasi terbaru, notifikasi prioritas tinggi, tren aduan, dan distribusi sumber aduan. |
| Alur Alternatif | Jika belum terdapat data, sistem menampilkan ringkasan dengan nilai kosong. |

### UC-03 Memantau Notifikasi dan Hasil Klasifikasi

| Nama Use Case | Memantau Notifikasi dan Hasil Klasifikasi |
| --- | --- |
| Aktor | Admin KMC |
| Tujuan | Meninjau notifikasi aduan serta rekomendasi hasil pengolahan sistem. |
| Kondisi Awal | Admin KMC telah masuk ke sistem. |
| Kondisi Akhir | Admin mengetahui informasi aduan dan rekomendasi penanganannya. |
| Alur Utama | 1. Admin membuka menu Notifikasi.<br>2. Sistem menampilkan daftar notifikasi.<br>3. Admin dapat mencari atau menyaring notifikasi.<br>4. Admin membuka notifikasi untuk melihat isi aduan serta rekomendasi kategori, subkategori, OPD tujuan, dan prioritas. |
| Alur Alternatif | Jika tidak ada notifikasi yang sesuai, sistem menampilkan informasi bahwa data tidak ditemukan. |

### UC-04 Memverifikasi Kemungkinan Duplikasi

| Nama Use Case | Memverifikasi Kemungkinan Duplikasi |
| --- | --- |
| Aktor | Admin KMC |
| Tujuan | Menentukan apakah notifikasi yang ditandai sebagai kemungkinan duplikasi perlu diarsipkan atau dibuatkan tiket. |
| Kondisi Awal | Terdapat notifikasi dengan status menunggu verifikasi kemungkinan duplikasi. |
| Kondisi Akhir | Notifikasi dikonfirmasi sebagai duplikat atau dibuatkan tiket sebagai aduan baru. |
| Alur Utama | 1. Admin membuka notifikasi yang ditandai sebagai kemungkinan duplikasi.<br>2. Sistem menampilkan informasi notifikasi dan referensi aduan terkait.<br>3. Admin memilih tindakan Bukan Duplikat.<br>4. Sistem membuat tiket dari notifikasi tersebut. |
| Alur Alternatif | Jika Admin memilih Konfirmasi Duplikat, sistem mengarsipkan notifikasi dan tidak membuat tiket baru. |

### UC-05 Mengelola Tiket

| Nama Use Case | Mengelola Tiket |
| --- | --- |
| Aktor | Admin KMC |
| Tujuan | Membuat, melihat, memperbarui, atau menghapus data tiket. |
| Kondisi Awal | Admin KMC telah masuk ke sistem. |
| Kondisi Akhir | Data tiket tersimpan, diperbarui, atau dihapus sesuai tindakan Admin. |
| Alur Utama | 1. Admin membuka menu Tiket.<br>2. Sistem menampilkan daftar tiket.<br>3. Admin memilih membuat tiket baru atau membuka tiket yang sudah ada.<br>4. Admin mengisi atau memperbarui informasi tiket.<br>5. Admin menyimpan perubahan.<br>6. Sistem memvalidasi dan menyimpan data tiket. |
| Alur Alternatif | Jika data wajib belum lengkap atau tidak valid, sistem menampilkan pesan validasi dan meminta Admin memperbaiki data. |

### UC-06 Mengelola Data dan Akun OPD

| Nama Use Case | Mengelola Data dan Akun OPD |
| --- | --- |
| Aktor | Admin KMC |
| Tujuan | Mengelola data OPD beserta akun Pengguna OPD. |
| Kondisi Awal | Admin KMC telah masuk ke sistem. |
| Kondisi Akhir | Data OPD dan akun pengguna berhasil ditambahkan, diperbarui, atau dihapus. |
| Alur Utama | 1. Admin membuka menu Manajemen OPD.<br>2. Sistem menampilkan daftar OPD dan akun terkait.<br>3. Admin memilih tambah, ubah, atau hapus data.<br>4. Admin mengisi atau memperbarui data OPD dan akun.<br>5. Sistem memvalidasi dan menyimpan perubahan. |
| Alur Alternatif | Jika username atau email telah digunakan, sistem menampilkan pesan validasi dan data tidak disimpan. |

### UC-07 Melihat Statistik Aduan

| Nama Use Case | Melihat Statistik Aduan |
| --- | --- |
| Aktor | Admin KMC |
| Tujuan | Melihat informasi statistik dan perkembangan pengelolaan aduan. |
| Kondisi Awal | Admin KMC telah masuk ke sistem. |
| Kondisi Akhir | Statistik aduan ditampilkan kepada Admin. |
| Alur Utama | 1. Admin membuka halaman statistik pada Dashboard Admin.<br>2. Sistem mengolah ringkasan data aduan dan tiket.<br>3. Sistem menampilkan tren, distribusi sumber aduan, dan ringkasan status tiket. |
| Alur Alternatif | Jika belum tersedia data, sistem menampilkan grafik atau ringkasan kosong. |

### UC-08 Mengelola Profil Admin

| Nama Use Case | Mengelola Profil Admin |
| --- | --- |
| Aktor | Admin KMC |
| Tujuan | Memperbarui data akun Admin KMC. |
| Kondisi Awal | Admin KMC telah masuk ke sistem. |
| Kondisi Akhir | Data profil Admin berhasil diperbarui. |
| Alur Utama | 1. Admin membuka menu Profil.<br>2. Sistem menampilkan data profil.<br>3. Admin memperbarui username, email, kata sandi, atau foto profil.<br>4. Admin menyimpan perubahan.<br>5. Sistem memvalidasi dan menyimpan data. |
| Alur Alternatif | Jika username atau email sudah digunakan, sistem menampilkan pesan validasi. |

### UC-09 Melihat Dashboard OPD

| Nama Use Case | Melihat Dashboard OPD |
| --- | --- |
| Aktor | Pengguna OPD |
| Tujuan | Memantau ringkasan tiket yang ditugaskan kepada OPD. |
| Kondisi Awal | Pengguna OPD telah masuk ke sistem dan terhubung dengan OPD. |
| Kondisi Akhir | Ringkasan tiket OPD ditampilkan. |
| Alur Utama | 1. Pengguna OPD membuka Dashboard OPD.<br>2. Sistem mengambil tiket yang ditugaskan kepada OPD pengguna.<br>3. Sistem menampilkan jumlah tiket berdasarkan perkembangan penanganan dan daftar tiket terbaru. |
| Alur Alternatif | Jika akun tidak terhubung dengan OPD, sistem menolak akses. |

### UC-10 Melihat Tiket OPD

| Nama Use Case | Melihat Tiket OPD |
| --- | --- |
| Aktor | Pengguna OPD |
| Tujuan | Melihat daftar dan detail tiket yang menjadi tanggung jawab OPD. |
| Kondisi Awal | Pengguna OPD telah masuk ke sistem. |
| Kondisi Akhir | Pengguna OPD melihat tiket yang ditugaskan kepada OPD-nya. |
| Alur Utama | 1. Pengguna OPD membuka menu Tiket.<br>2. Sistem menampilkan tiket yang ditugaskan kepada OPD pengguna.<br>3. Pengguna dapat mencari atau menyaring tiket.<br>4. Pengguna membuka salah satu tiket.<br>5. Sistem menampilkan detail tiket dan riwayat penanganan. |
| Alur Alternatif | Jika Pengguna OPD mencoba membuka tiket milik OPD lain, sistem menolak akses. |

### UC-11 Memberikan Tanggapan Tiket

| Nama Use Case | Memberikan Tanggapan Tiket |
| --- | --- |
| Aktor | Pengguna OPD |
| Tujuan | Mencatat tanggapan OPD terhadap tiket yang ditugaskan. |
| Kondisi Awal | Pengguna OPD membuka tiket milik OPD-nya. |
| Kondisi Akhir | Tanggapan tersimpan pada riwayat tiket. |
| Alur Utama | 1. Pengguna OPD membuka detail tiket.<br>2. Pengguna menuliskan tanggapan dan dapat menambahkan lampiran gambar.<br>3. Pengguna mengirim tanggapan.<br>4. Sistem memvalidasi dan menyimpan tanggapan.<br>5. Sistem memperbarui perkembangan tiket. |
| Alur Alternatif | Jika tanggapan kosong atau lampiran tidak memenuhi ketentuan, sistem menampilkan pesan validasi. |

### UC-12 Memperbarui Status Tiket

| Nama Use Case | Memperbarui Status Tiket |
| --- | --- |
| Aktor | Pengguna OPD |
| Tujuan | Memperbarui perkembangan penanganan tiket. |
| Kondisi Awal | Pengguna OPD membuka tiket milik OPD-nya. |
| Kondisi Akhir | Status dan riwayat tiket diperbarui. |
| Alur Utama | 1. Pengguna OPD membuka detail tiket.<br>2. Pengguna memilih status penanganan dan dapat menambahkan catatan atau lampiran.<br>3. Pengguna menyimpan pembaruan.<br>4. Sistem memvalidasi dan menyimpan perubahan status serta riwayatnya. |
| Alur Alternatif | Jika tidak ada perubahan status, catatan, atau lampiran, sistem menampilkan pesan bahwa tidak ada perubahan yang disimpan. |

### UC-13 Mengelola Profil OPD

| Nama Use Case | Mengelola Profil OPD |
| --- | --- |
| Aktor | Pengguna OPD |
| Tujuan | Memperbarui data akun Pengguna OPD. |
| Kondisi Awal | Pengguna OPD telah masuk ke sistem. |
| Kondisi Akhir | Data profil Pengguna OPD berhasil diperbarui. |
| Alur Utama | 1. Pengguna OPD membuka menu Profil.<br>2. Sistem menampilkan data profil.<br>3. Pengguna memperbarui nama, email, kata sandi, atau foto profil.<br>4. Pengguna menyimpan perubahan.<br>5. Sistem memvalidasi dan menyimpan data. |
| Alur Alternatif | Jika email sudah digunakan atau data tidak valid, sistem menampilkan pesan validasi. |

### UC-14 Melacak Tiket

| Nama Use Case | Melacak Tiket |
| --- | --- |
| Aktor | Masyarakat |
| Tujuan | Melihat perkembangan tiket menggunakan nomor pelacakan tanpa masuk ke sistem. |
| Kondisi Awal | Masyarakat berada pada portal publik. |
| Kondisi Akhir | Informasi tiket yang dicari ditampilkan. |
| Alur Utama | 1. Masyarakat memasukkan nomor pelacakan.<br>2. Masyarakat menekan tombol Lacak.<br>3. Sistem mencari tiket sesuai nomor pelacakan.<br>4. Sistem menampilkan informasi tiket, status, OPD penanganan, riwayat status, dan tanggapan yang tersedia. |
| Alur Alternatif | Jika nomor pelacakan tidak ditemukan, sistem menampilkan pesan bahwa tiket tidak ditemukan. |

### UC-15 Melihat Informasi Pemantauan Aduan Publik

| Nama Use Case | Melihat Informasi Pemantauan Aduan Publik |
| --- | --- |
| Aktor | Masyarakat |
| Tujuan | Melihat informasi umum pengelolaan aduan pada portal publik. |
| Kondisi Awal | Masyarakat mengakses portal publik. |
| Kondisi Akhir | Informasi pemantauan aduan publik ditampilkan. |
| Alur Utama | 1. Masyarakat membuka portal publik.<br>2. Sistem menampilkan ringkasan jumlah laporan, perkembangan penanganan, informasi per OPD, kategori, serta daftar tiket publik.<br>3. Masyarakat dapat melakukan pencarian atau penyaringan daftar tiket publik. |
| Alur Alternatif | Jika belum terdapat data, sistem menampilkan ringkasan kosong. |

### UC-16 Logout

| Nama Use Case | Logout |
| --- | --- |
| Aktor | Admin KMC, Pengguna OPD |
| Tujuan | Mengakhiri sesi pengguna pada sistem. |
| Kondisi Awal | Pengguna telah masuk ke sistem. |
| Kondisi Akhir | Sesi pengguna berakhir dan halaman login ditampilkan. |
| Alur Utama | 1. Pengguna memilih menu Logout.<br>2. Sistem mengakhiri sesi pengguna.<br>3. Sistem mengarahkan pengguna ke halaman login. |
| Alur Alternatif | Tidak ada. |
