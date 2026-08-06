# Skenario Use Case Sistem Informasi Manajemen Aduan Multi Channel KMC

Berikut adalah detail skenario use case untuk setiap fungsi yang terdapat pada Use Case Diagram. Tabel disajikan mengikuti format standar.

---

| Skenario ID | SUC-01 |
| --- | --- |
| **Nama Use Case** | Login |
| **Deskripsi** | Aktor melakukan autentikasi untuk masuk ke dalam sistem dengan memasukkan email dan kata sandi agar dapat mengakses fitur sesuai hak aksesnya. |
| **Aktor** | Admin KMC, Pengguna OPD |
| **Pre-kondisi** | Aktor belum masuk ke dalam sistem dan berada di halaman login. |
| **Post-kondisi** | Aktor berhasil masuk dan diarahkan ke dashboard masing-masing. |
| **Skenario Normal** | 1. Aktor memasukkan email/username dan kata sandi<br>2. Aktor menekan tombol masuk<br>3. Sistem memvalidasi kredensial aktor<br>4. Sistem mengarahkan Admin KMC ke Dashboard Admin atau Pengguna OPD ke Dashboard OPD |
| **Skenario Alternatif** | 1. Aktor memasukkan kredensial yang tidak valid<br>2. Sistem menampilkan pesan peringatan bahwa email/username atau kata sandi salah<br>3. Sistem mengembalikan aktor ke halaman login |

<div align="center"><i>Tabel 3.x Skenario Use Case Login</i></div>

<br>

| Skenario ID | SUC-02 |
| --- | --- |
| **Nama Use Case** | Melihat Dashboard Admin |
| **Deskripsi** | Admin KMC memantau ringkasan informasi, statistik singkat, dan notifikasi prioritas tinggi secara *real-time*. |
| **Aktor** | Admin KMC |
| **Pre-kondisi** | Admin KMC telah berhasil masuk ke sistem. |
| **Post-kondisi** | Admin KMC melihat ringkasan data aduan dan tiket terkini. |
| **Skenario Normal** | 1. Admin KMC memilih menu Dashboard<br>2. Sistem menghitung dan memuat ringkasan data tiket, notifikasi terbaru, dan notifikasi prioritas tinggi<br>3. Sistem menampilkan informasi tersebut pada halaman dashboard Admin |
| **Skenario Alternatif** | - |

<div align="center"><i>Tabel 3.x Skenario Use Case Melihat Dashboard Admin</i></div>

<br>

| Skenario ID | SUC-03 |
| --- | --- |
| **Nama Use Case** | Memantau Notifikasi dan Hasil Klasifikasi |
| **Deskripsi** | Admin KMC melihat daftar pesan masuk dari media sosial yang telah disaring dan diklasifikasikan oleh layanan AI. |
| **Aktor** | Admin KMC |
| **Pre-kondisi** | Admin KMC berada pada halaman sistem. |
| **Post-kondisi** | Admin KMC mengetahui informasi aduan terbaru beserta rekomendasi kategorinya. |
| **Skenario Normal** | 1. Admin KMC memilih menu Notifikasi<br>2. Sistem menampilkan daftar notifikasi aduan beserta hasil rekomendasi kategori, subkategori, OPD tujuan, dan prioritas<br>3. Admin menekan salah satu notifikasi untuk melihat detail pesan |
| **Skenario Alternatif** | 1. Tidak ada notifikasi baru di database<br>2. Sistem menampilkan pesan "Tidak ada notifikasi aduan baru" |

<div align="center"><i>Tabel 3.x Skenario Use Case Memantau Notifikasi dan Hasil Klasifikasi</i></div>

<br>

| Skenario ID | SUC-04 |
| --- | --- |
| **Nama Use Case** | Memverifikasi Kemungkinan Duplikasi |
| **Deskripsi** | Mengonfirmasi apakah notifikasi baru merupakan duplikat dari aduan yang sudah ada tiketnya. |
| **Aktor** | Admin KMC |
| **Pre-kondisi** | Terdapat notifikasi yang ditandai sistem sebagai "Kemungkinan Duplikasi". |
| **Post-kondisi** | Notifikasi diarsipkan (jika duplikat) atau dibuatkan tiket (jika bukan duplikat). |
| **Skenario Normal** | 1. Admin KMC membuka notifikasi yang terdeteksi sebagai kemungkinan duplikasi<br>2. Sistem menampilkan aduan baru bersandingan dengan aduan lama yang mirip<br>3. Admin menekan tombol "Konfirmasi Duplikat"<br>4. Sistem mengubah status notifikasi menjadi diarsipkan tanpa membuat tiket |
| **Skenario Alternatif** | 1. Admin menilai aduan tersebut bukan duplikat<br>2. Admin menekan tombol "Bukan Duplikat"<br>3. Sistem membuat tiket baru berdasarkan hasil analisis notifikasi<br>4. Sistem meneruskannya ke OPD terkait |

<div align="center"><i>Tabel 3.x Skenario Use Case Memverifikasi Kemungkinan Duplikasi</i></div>

<br>

| Skenario ID | SUC-05 |
| --- | --- |
| **Nama Use Case** | Mengelola Tiket |
| **Deskripsi** | Admin KMC dapat melihat detail tiket, membuat tiket secara manual, atau mengubah atribut tiket seperti kategori, OPD tujuan, dan prioritas. |
| **Aktor** | Admin KMC |
| **Pre-kondisi** | Admin KMC berada di halaman Manajemen Tiket. |
| **Post-kondisi** | Data tiket berhasil disimpan atau diperbarui di dalam basis data sistem. |
| **Skenario Normal** | 1. Admin membuka daftar tiket<br>2. Admin memilih tiket yang ingin diubah<br>3. Admin mengubah kategori, subkategori, atau OPD tujuan<br>4. Admin menyimpan perubahan<br>5. Sistem menyimpan pembaruan dan mencatat riwayat perubahan |
| **Skenario Alternatif** | 1. Admin membuat tiket manual dengan menekan tombol "Buat Tiket"<br>2. Admin mengisi formulir data aduan, pelapor, dan memilih OPD tujuan<br>3. Admin menyimpan data<br>4. Sistem menghasilkan nomor tiket dan menyimpannya |

<div align="center"><i>Tabel 3.x Skenario Use Case Mengelola Tiket</i></div>

<br>

| Skenario ID | SUC-06 |
| --- | --- |
| **Nama Use Case** | Mengelola Data dan Akun OPD |
| **Deskripsi** | Admin KMC menambah, mengubah, atau menghapus data instansi OPD beserta kredensial akun penggunanya. |
| **Aktor** | Admin KMC |
| **Pre-kondisi** | Admin KMC berada di halaman Manajemen OPD. |
| **Post-kondisi** | Data dan akun OPD berhasil diperbarui di basis data. |
| **Skenario Normal** | 1. Admin memilih menu Manajemen OPD<br>2. Sistem menampilkan daftar OPD<br>3. Admin memilih fungsi tambah atau edit data<br>4. Admin memasukkan nama OPD, profil, dan data kredensial akses<br>5. Admin menekan tombol simpan<br>6. Sistem memvalidasi dan menyimpan data |
| **Skenario Alternatif** | 1. Admin memasukkan data yang tidak lengkap atau format email salah<br>2. Sistem menampilkan pesan error validasi<br>3. Admin memperbaiki isian formulir dan menyimpan ulang |

<div align="center"><i>Tabel 3.x Skenario Use Case Mengelola Data dan Akun OPD</i></div>

<br>

| Skenario ID | SUC-07 |
| --- | --- |
| **Nama Use Case** | Melihat Statistik Aduan |
| **Deskripsi** | Admin KMC melihat laporan atau grafik perkembangan pengelolaan aduan. |
| **Aktor** | Admin KMC |
| **Pre-kondisi** | Admin KMC telah berhasil masuk ke sistem. |
| **Post-kondisi** | Admin KMC mendapatkan informasi tren pengelolaan tiket. |
| **Skenario Normal** | 1. Admin memilih menu Statistik/Laporan<br>2. Sistem mengambil data rekapitulasi tiket berdasarkan rentang waktu, status, dan platform sumber<br>3. Sistem menampilkan visualisasi data dalam bentuk grafik atau tabel |
| **Skenario Alternatif** | - |

<div align="center"><i>Tabel 3.x Skenario Use Case Melihat Statistik Aduan</i></div>

<br>

| Skenario ID | SUC-08 |
| --- | --- |
| **Nama Use Case** | Mengelola Profil |
| **Deskripsi** | Pengguna memperbarui data profil pribadi seperti nama, email, foto, dan kata sandi. |
| **Aktor** | Admin KMC, Pengguna OPD |
| **Pre-kondisi** | Aktor sedang berada di dalam sistem. |
| **Post-kondisi** | Data profil aktor berhasil diperbarui. |
| **Skenario Normal** | 1. Aktor memilih menu Profil<br>2. Sistem menampilkan data profil aktor saat ini<br>3. Aktor mengubah informasi yang diinginkan atau memasukkan kata sandi baru<br>4. Aktor menyimpan perubahan<br>5. Sistem memvalidasi dan menyimpan pembaruan profil ke database |
| **Skenario Alternatif** | 1. Aktor memasukkan konfirmasi kata sandi baru yang tidak cocok<br>2. Sistem menampilkan peringatan kata sandi tidak cocok<br>3. Aktor mengulang pengisian kata sandi dan menyimpan |

<div align="center"><i>Tabel 3.x Skenario Use Case Mengelola Profil</i></div>

<br>

| Skenario ID | SUC-09 |
| --- | --- |
| **Nama Use Case** | Melihat Dashboard OPD |
| **Deskripsi** | Pengguna OPD memantau ringkasan tiket aduan yang menjadi tanggung jawab instansinya. |
| **Aktor** | Pengguna OPD |
| **Pre-kondisi** | Pengguna OPD telah berhasil masuk ke sistem. |
| **Post-kondisi** | Pengguna OPD melihat ringkasan tiket khusus untuk instansinya. |
| **Skenario Normal** | 1. Pengguna OPD mengakses halaman utama<br>2. Sistem menyaring data tiket hanya untuk OPD yang bersangkutan<br>3. Sistem menampilkan ringkasan jumlah tiket masuk, diproses, dan selesai |
| **Skenario Alternatif** | - |

<div align="center"><i>Tabel 3.x Skenario Use Case Melihat Dashboard OPD</i></div>

<br>

| Skenario ID | SUC-10 |
| --- | --- |
| **Nama Use Case** | Melihat Tiket OPD |
| **Deskripsi** | Pengguna OPD membaca daftar dan melihat detail aduan yang ditugaskan kepada OPD terkait. |
| **Aktor** | Pengguna OPD |
| **Pre-kondisi** | Pengguna OPD berada di Dashboard OPD. |
| **Post-kondisi** | Pengguna OPD melihat rincian keluhan, pelapor, dan riwayat penanganan tiket. |
| **Skenario Normal** | 1. Pengguna OPD memilih menu Daftar Tiket<br>2. Sistem menampilkan seluruh tiket yang ditugaskan ke OPD tersebut<br>3. Pengguna OPD memilih salah satu tiket<br>4. Sistem menampilkan halaman detail tiket berisi deskripsi aduan dan lampiran |
| **Skenario Alternatif** | 1. Pengguna OPD mencoba mengakses tautan URL tiket milik OPD lain<br>2. Sistem menolak akses<br>3. Sistem menampilkan pesan error otoritas bahwa hak akses ditolak |

<div align="center"><i>Tabel 3.x Skenario Use Case Melihat Tiket OPD</i></div>

<br>

| Skenario ID | SUC-11 |
| --- | --- |
| **Nama Use Case** | Memberikan Tanggapan Tiket |
| **Deskripsi** | Pengguna OPD mengirimkan balasan, penjelasan, atau solusi terkait aduan ke dalam sistem. |
| **Aktor** | Pengguna OPD |
| **Pre-kondisi** | Pengguna OPD sedang membuka halaman detail suatu tiket. |
| **Post-kondisi** | Tanggapan tersimpan dalam riwayat tiket dan status diperbarui. |
| **Skenario Normal** | 1. Pengguna OPD menuliskan pesan tanggapan pada kolom yang tersedia<br>2. Pengguna OPD menambahkan lampiran foto bukti penanganan (jika ada)<br>3. Pengguna OPD menekan tombol Kirim Tanggapan<br>4. Sistem menyimpan pesan dan lampiran ke dalam riwayat tiket |
| **Skenario Alternatif** | 1. Pengguna OPD mengunggah file lampiran yang melebihi batas sistem<br>2. Sistem menolak proses dan menampilkan pesan batas maksimal ukuran file<br>3. Pengguna OPD mengganti file dengan ukuran yang sesuai dan mengirim ulang |

<div align="center"><i>Tabel 3.x Skenario Use Case Memberikan Tanggapan Tiket</i></div>

<br>

| Skenario ID | SUC-12 |
| --- | --- |
| **Nama Use Case** | Memperbarui Status Tiket |
| **Deskripsi** | Pengguna OPD mengubah tahapan progres penanganan tiket (misal: Diproses, Selesai). |
| **Aktor** | Pengguna OPD |
| **Pre-kondisi** | Pengguna OPD sedang membuka halaman detail tiket yang masih aktif. |
| **Post-kondisi** | Status tiket berubah dan dicatat ke dalam log riwayat penanganan. |
| **Skenario Normal** | 1. Pengguna OPD memilih opsi status terbaru pada menu pembaruan status<br>2. Pengguna OPD menambahkan catatan pembaruan status<br>3. Pengguna OPD menyimpan pembaruan<br>4. Sistem mengubah status tiket dan menyimpan log riwayat perubahan status |
| **Skenario Alternatif** | - |

<div align="center"><i>Tabel 3.x Skenario Use Case Memperbarui Status Tiket</i></div>

<br>

| Skenario ID | SUC-13 |
| --- | --- |
| **Nama Use Case** | Melacak Tiket |
| **Deskripsi** | Masyarakat melihat perkembangan penanganan aduan mereka melalui portal publik. |
| **Aktor** | Masyarakat |
| **Pre-kondisi** | Masyarakat berada di portal pelacakan publik sistem. |
| **Post-kondisi** | Sistem menampilkan progres tiket yang dicari. |
| **Skenario Normal** | 1. Masyarakat memasukkan Nomor Pelacakan (Resi) tiket ke dalam kolom pencarian<br>2. Masyarakat menekan tombol Lacak<br>3. Sistem mencari data tiket berdasarkan nomor pelacakan tersebut di basis data<br>4. Sistem menampilkan status saat ini, OPD yang menangani, dan riwayat singkat tiket kepada masyarakat |
| **Skenario Alternatif** | 1. Masyarakat memasukkan nomor pelacakan yang salah atau tidak terdaftar<br>2. Sistem gagal menemukan data yang cocok<br>3. Sistem menampilkan pesan peringatan bahwa tiket tidak ditemukan |

<div align="center"><i>Tabel 3.x Skenario Use Case Melacak Tiket</i></div>

<br>

| Skenario ID | SUC-14 |
| --- | --- |
| **Nama Use Case** | Melihat Informasi Pemantauan Aduan Publik |
| **Deskripsi** | Masyarakat melihat ringkasan data aduan publik yang ditangani secara transparan. |
| **Aktor** | Masyarakat |
| **Pre-kondisi** | Masyarakat berada di portal publik sistem. |
| **Post-kondisi** | Sistem menampilkan ringkasan statistik pengelolaan aduan secara terbuka. |
| **Skenario Normal** | 1. Masyarakat mengakses halaman utama pemantauan publik<br>2. Sistem mengambil data ringkasan agregat tiket (jumlah aduan masuk, aduan selesai, dll)<br>3. Sistem menampilkan statistik sederhana beserta daftar aduan publik terkini yang disamarkan identitasnya |
| **Skenario Alternatif** | - |

<div align="center"><i>Tabel 3.x Skenario Use Case Melihat Informasi Pemantauan Aduan Publik</i></div>
