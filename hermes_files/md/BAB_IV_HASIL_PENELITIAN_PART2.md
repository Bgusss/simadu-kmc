## 4.2 Hasil Pengujian Sistem

Pengujian sistem dilakukan menggunakan metode *black box testing* untuk memastikan bahwa seluruh fitur yang dikembangkan berfungsi sesuai dengan kebutuhan fungsional. Pengujian dilakukan berdasarkan 20 skenario pengujian yang telah dirancang pada sub-bagian 3.2.5. Setiap skenario pengujian mencakup fitur yang diuji, skenario pengujian, hasil yang diharapkan, hasil pengujian aktual, serta keterangan berupa referensi gambar bukti pengujian.

Pengujian dilakukan pada lingkungan pengembangan lokal menggunakan Laragon dengan PHP 8.3.2, MySQL 8.0, dan Google Chrome sebagai peramban. Data pengujian menggunakan data aduan nyata yang diambil dari Facebook dan Instagram KMC serta data pengujian yang dibuat khusus untuk menguji kondisi khusus seperti validasi masukan, pembatasan hak akses, dan penanganan kesalahan.

Hasil pengujian sistem disajikan dalam bentuk tabel yang mencakup nomor pengujian, fitur yang diuji, skenario pengujian, hasil yang diharapkan, hasil pengujian aktual, dan keterangan. Seluruh pengujian dilakukan secara manual dengan mencatat hasil pengujian pada setiap langkah.

### 4.2.1 Pengujian Autentikasi

Pengujian autentikasi dilakukan untuk memastikan bahwa sistem dapat memverifikasi kredensial pengguna dengan benar dan mengarahkan pengguna sesuai dengan perannya. Pengujian mencakup login dengan kredensial yang benar, kredensial yang salah, serta validasi masukan kosong. Hasil pengujian autentikasi ditunjukkan pada Tabel 4.1.

**Tabel 4.1 Hasil Pengujian Autentikasi**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 1 | Login Admin | Admin memasukkan kredensial yang benar (email: admin@kmc.go.id, password: 301220) | Sistem mengarahkan admin ke dashboard admin | Berhasil. Sistem mengarahkan ke dashboard admin setelah kredensial diverifikasi | Gambar 4.1, 4.2 |
| 2 | Login OPD | Pengguna OPD memasukkan kredensial yang benar | Sistem mengarahkan pengguna ke dashboard OPD | Berhasil. Sistem mengarahkan ke dashboard OPD sesuai dengan OPD pengguna | Gambar 4.10, 4.11 |
| 3 | Login Gagal | Pengguna memasukkan kredensial yang salah atau kosong | Sistem menolak login dan menampilkan pesan validasi | Berhasil. Sistem menampilkan pesan "Email atau password salah" tanpa mengungkapkan informasi spesifik | Gambar 4.16 |

Hasil pengujian autentikasi menunjukkan bahwa sistem berhasil memverifikasi kredensial pengguna dengan benar. Sistem menolak login apabila kredensial salah atau kosong dan menampilkan pesan validasi tanpa mengungkapkan informasi spesifik seperti "email tidak ditemukan" atau "password salah" untuk menjaga keamanan.

### 4.2.2 Pengujian Otorisasi

Pengujian otorisasi dilakukan untuk memastikan bahwa sistem membatasi akses pengguna sesuai dengan peran masing-masing. Pengguna OPD tidak boleh mengakses halaman khusus admin, dan pengguna OPD hanya boleh melihat tiket yang ditugaskan kepada OPD-nya. Hasil pengujian otorisasi ditunjukkan pada Tabel 4.2.

**Tabel 4.2 Hasil Pengujian Otorisasi**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 4 | Pembatasan Akses Admin | Pengguna OPD mencoba mengakses halaman manajemen OPD (/admin/opd) | Sistem menolak akses dan menampilkan halaman 403 Forbidden | Berhasil. Sistem menolak akses dan menampilkan pesan "Anda tidak memiliki akses ke halaman ini" | Gambar 4.17 |
| 5 | Pembatasan Tiket OPD | Pengguna OPD mencoba mengakses tiket yang ditugaskan ke OPD lain | Sistem menolak akses dan menampilkan halaman 403 atau mengarahkan ke daftar tiket OPD sendiri | Berhasil. Sistem menolak akses dan menampilkan pesan bahwa tiket tidak ditemukan atau bukan milik OPD pengguna | Gambar 4.18 |

Hasil pengujian otorisasi menunjukkan bahwa sistem berhasil membatasi akses pengguna sesuai dengan peran masing-masing. Pengguna OPD tidak dapat mengakses halaman admin dan tidak dapat melihat tiket milik OPD lain.

### 4.2.3 Pengujian Sinkronisasi Data Media Sosial

Pengujian sinkronisasi data media sosial dilakukan untuk memastikan bahwa sistem dapat mengambil data aduan dari Facebook dan Instagram dengan benar, menyimpan data mention dan notifikasi, serta mencegah pembuatan data ganda. Pengujian mencakup sinkronisasi data baru, sinkronisasi data yang sudah pernah diambil, serta validasi format data JSON dari Playwright. Hasil pengujian sinkronisasi data media sosial ditunjukkan pada Tabel 4.3.

**Tabel 4.3 Hasil Pengujian Sinkronisasi Data Media Sosial**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 6 | Sinkronisasi Facebook | Data mention Facebook baru berhasil diperoleh scraper dan dikirim ke perintah Artisan | Data mention dan notifikasi tersimpan satu kali tanpa duplikasi | Berhasil. Data tersimpan dengan `source_type='facebook'` dan `permalink` unik | Gambar 4.19 |
| 7 | Sinkronisasi Instagram | Data pesan Instagram baru berhasil diperoleh scraper dan dikirim ke perintah Artisan | Data mention dan notifikasi tersimpan satu kali tanpa duplikasi | Berhasil. Data tersimpan dengan `source_type='instagram'` dan `instagram_message_id` unik | Gambar 4.20 |
| 8 | Pencegahan Data Ganda | Scraper memproses tautan atau pesan yang telah tersimpan pada sinkronisasi sebelumnya | Sistem tidak membuat data mention atau notifikasi ganda | Berhasil. Sistem memeriksa `permalink` (Facebook) atau `instagram_message_id` (Instagram) sebelum menyimpan | Gambar 4.21 |

Hasil pengujian sinkronisasi data media sosial menunjukkan bahwa sistem berhasil mengambil data dari Facebook dan Instagram dengan benar. Sistem tidak membuat data ganda apabila data yang sama diproses lebih dari satu kali.

### 4.2.4 Pengujian Filter Spam

Pengujian filter spam dilakukan untuk memastikan bahwa sistem dapat menyaring pesan yang tidak layak diproses seperti pesan kosong, reaksi singkat, emoji tanpa teks, atau promosi. Pengujian mencakup filter spam aturan lokal dan filter spam AI. Hasil pengujian filter spam ditunjukkan pada Tabel 4.4.

**Tabel 4.4 Hasil Pengujian Filter Spam**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 9 | Filter Spam Lokal | Pesan hanya berisi emoji, reaksi singkat seperti "👍", "Ok", atau promosi produk | Pesan ditandai `is_spam=true` dan `spam_reason` diisi dengan alasan, tidak dibuatkan tiket | Berhasil. Sistem menandai pesan spam dan tidak melanjutkan ke proses klasifikasi | Gambar 4.22 |
| 10 | Filter Spam AI | Pesan yang tidak terdeteksi oleh aturan lokal diperiksa oleh AI | AI menilai apakah pesan layak diproses. Jika tidak layak, `is_spam=true` dan tidak dibuatkan tiket | Berhasil. AI memberikan penilaian spam dengan alasan yang jelas | Gambar 4.23 |

Hasil pengujian filter spam menunjukkan bahwa sistem berhasil menyaring pesan yang tidak layak diproses. Kombinasi filter lokal dan filter AI memastikan bahwa hanya aduan yang relevan yang diproses lebih lanjut.

### 4.2.5 Pengujian Klasifikasi AI

Pengujian klasifikasi AI dilakukan untuk memastikan bahwa sistem dapat mengirimkan prompt dan teks aduan ke Google AI Studio, menerima hasil klasifikasi, menyimpan hasil klasifikasi, serta menggunakan cadangan berbasis kata kunci apabila layanan AI gagal atau hasil respons tidak valid. Hasil pengujian klasifikasi AI ditunjukkan pada Tabel 4.5.

**Tabel 4.5 Hasil Pengujian Klasifikasi AI**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 11 | Klasifikasi AI Normal | Aduan valid "Jalan berlubang di Jl. Sudirman depan kantor pos" dikirimkan ke layanan AI | Sistem menyimpan kategori (Infrastruktur), subkategori (Jalan Rusak), OPD (Dinas PUPR), prioritas (Sedang), kepercayaan, dan alasan | Berhasil. Hasil klasifikasi tersimpan di tabel `ai_classifications` dengan `confidence=0.85` | Gambar 4.24 |
| 12 | Cadangan Klasifikasi | Layanan AI gagal dijangkau atau hasil respons tidak valid (format JSON salah) | Sistem menggunakan aturan cadangan berbasis kata kunci dan proses tidak berhenti | Berhasil. Sistem mendeteksi kegagalan, menggunakan cadangan, dan mencatat `fallback_used=true` | Gambar 4.25 |

Hasil pengujian klasifikasi AI menunjukkan bahwa sistem berhasil mengintegrasikan layanan AI Google AI Studio dengan model Gemma 4 31B IT. Sistem juga berhasil menggunakan cadangan berbasis kata kunci apabila layanan AI gagal, sehingga proses pengelolaan aduan tetap dapat berjalan.

### 4.2.6 Pengujian Deteksi Duplikasi

Pengujian deteksi duplikasi dilakukan untuk memastikan bahwa sistem dapat membandingkan aduan baru dengan notifikasi yang telah memiliki tiket dalam 30 hari terakhir, mendeteksi kemungkinan duplikasi berdasarkan masalah, lokasi, dan objek yang sama, serta menunggu konfirmasi admin sebelum membuat tiket. Hasil pengujian deteksi duplikasi ditunjukkan pada Tabel 4.6.

**Tabel 4.6 Hasil Pengujian Deteksi Duplikasi**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 13 | Deteksi Duplikasi Positif | Aduan baru "Jalan berlubang Jl. Sudirman" dikirimkan, sedangkan 2 hari lalu ada tiket dengan isi serupa | Notifikasi ditandai sebagai kemungkinan duplikasi (`duplicate_status='terdeteksi'`) dan menunggu konfirmasi admin | Berhasil. Sistem menandai duplikasi dengan `duplicate_similarity=0.92` dan menampilkan notifikasi pembanding | Gambar 4.26 |
| 14 | Konfirmasi Duplikat | Admin menyatakan notifikasi sebagai duplikat melalui tombol "Konfirmasi Duplikat" | Notifikasi diarsipkan (`duplicate_status='diarsip'`) dan sistem tidak membuat tiket baru | Berhasil. Status duplikasi berubah menjadi `diarsip` tanpa membuat tiket | Gambar 4.27 |
| 15 | Konfirmasi Bukan Duplikat | Admin menyatakan notifikasi bukan duplikat melalui tombol "Bukan Duplikat" | Sistem membuat tiket otomatis dari hasil klasifikasi yang tersimpan (`duplicate_status='bukan duplikat'`) | Berhasil. Tiket dibuat dengan nomor pelacakan, SLA 24 jam, dan status awal `diterima` | Gambar 4.28 |

Hasil pengujian deteksi duplikasi menunjukkan bahwa sistem berhasil mendeteksi kemungkinan duplikasi berdasarkan kesamaan masalah, lokasi, dan objek. Sistem menunggu konfirmasi admin sebelum membuat tiket baru atau mengarsipkan notifikasi duplikat.

### 4.2.7 Pengujian Pembuatan Tiket

Pengujian pembuatan tiket dilakukan untuk memastikan bahwa sistem dapat membuat tiket dari notifikasi yang tidak terdeteksi sebagai duplikasi, menghasilkan nomor pelacakan unik, menetapkan batas SLA 24 jam, mencatat status awal, serta menetapkan OPD tujuan berdasarkan hasil klasifikasi. Hasil pengujian pembuatan tiket ditunjukkan pada Tabel 4.7.

**Tabel 4.7 Hasil Pengujian Pembuatan Tiket**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 16 | Pembuatan Tiket Otomatis | Aduan valid tidak terdeteksi sebagai duplikasi setelah proses klasifikasi dan deteksi duplikasi | Sistem membuat nomor pelacakan (format TKT-YYYYMMDD-XXXX), tiket, SLA 24 jam dari waktu pembuatan, dan riwayat status awal `diterima` | Berhasil. Tiket dibuat dengan `tracking_number='TKT-20260714-0001'`, `sla_deadline=2026-07-15 10:30:00`, `status='diterima'`, dan `assigned_opd_id` sesuai hasil klasifikasi | Gambar 4.29 |

Hasil pengujian pembuatan tiket menunjukkan bahwa sistem berhasil membuat tiket secara otomatis dengan nomor pelacakan unik, batas SLA 24 jam, status awal, dan OPD tujuan yang sesuai.

### 4.2.8 Pengujian Pengelolaan Tiket

Pengujian pengelolaan tiket dilakukan untuk memastikan bahwa admin dapat mengubah kategori, subkategori, OPD tujuan, atau prioritas tiket apabila hasil klasifikasi AI tidak sesuai. Pengujian juga mencakup validasi masukan dan penyimpanan perubahan data tiket. Hasil pengujian pengelolaan tiket ditunjukkan pada Tabel 4.8.

**Tabel 4.8 Hasil Pengujian Pengelolaan Tiket**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 17 | Edit Data Tiket | Admin mengubah kategori dari "Infrastruktur" menjadi "Kebersihan" dan OPD dari "Dinas PUPR" menjadi "Dinas Lingkungan Hidup" | Sistem menyimpan perubahan data tiket dan OPD tujuan diperbarui | Berhasil. Tiket diperbarui dengan kategori dan OPD baru, riwayat mencatat perubahan | Gambar 4.30 |

Hasil pengujian pengelolaan tiket menunjukkan bahwa admin dapat mengubah data tiket dengan benar. Sistem menyimpan perubahan dan mencatat riwayat perubahan untuk audit.

### 4.2.9 Pengujian Tanggapan OPD

Pengujian tanggapan OPD dilakukan untuk memastikan bahwa pengguna OPD dapat mengirimkan tanggapan terhadap tiket yang ditugaskan, mengunggah lampiran, serta memperbarui status tiket. Pengujian juga mencakup validasi masukan dan pembatasan akses tiket milik OPD lain. Hasil pengujian tanggapan OPD ditunjukkan pada Tabel 4.9.

**Tabel 4.9 Hasil Pengujian Tanggapan OPD**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 18 | Kirim Tanggapan OPD | Pengguna OPD mengirimkan tanggapan yang valid dengan teks "Sudah kami tindaklanjuti, perbaikan akan dilakukan besok" dan lampiran foto | Sistem menyimpan tanggapan, lampiran, dan memperbarui status menjadi `dijawab` apabila tiket belum ditutup | Berhasil. Tanggapan tersimpan di tabel `ticket_responses`, lampiran tersimpan di storage, status diperbarui | Gambar 4.31 |

Hasil pengujian tanggapan OPD menunjukkan bahwa pengguna OPD dapat memberikan tanggapan dengan benar. Sistem menyimpan tanggapan, lampiran, dan memperbarui status tiket sesuai dengan tindakan OPD.

### 4.2.10 Pengujian SLA dan Eskalasi

Pengujian SLA dan eskalasi dilakukan untuk memastikan bahwa sistem dapat mendeteksi tiket yang melewati batas SLA 24 jam, mengubah status menjadi Out SLA, serta melakukan eskalasi prioritas apabila tiket kembali melewati batas waktu. Pengujian dilakukan dengan mengubah waktu pembuatan tiket secara manual untuk simulasi. Hasil pengujian SLA dan eskalasi ditunjukkan pada Tabel 4.10.

**Tabel 4.10 Hasil Pengujian SLA dan Eskalasi**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 19 | Out SLA Tahap Pertama | Tiket dengan status `diteruskan` atau `dibaca` dibuat 25 jam yang lalu tanpa respons | Sistem mengubah status menjadi `proses_disposisi` dan menetapkan SLA baru 24 jam | Berhasil. Perintah `php artisan check:escalation` mendeteksi tiket dan mengubah status | Gambar 4.32 |
| 20 | Eskalasi Prioritas | Tiket dengan status `proses_disposisi` kembali melewati 24 jam tanpa penyelesaian | Sistem mencatat eskalasi (`escalation_count` bertambah 1), menaikkan prioritas dari `rendah` menjadi `sedang` atau dari `sedang` menjadi `tinggi`, dan menetapkan SLA baru 24 jam | Berhasil. Prioritas dinaikkan, `escalation_count=1`, status kembali ke `proses_disposisi` dengan SLA baru | Gambar 4.33 |

Hasil pengujian SLA dan eskalasi menunjukkan bahwa sistem berhasil memantau batas waktu penanganan tiket dan melakukan eskalasi secara otomatis. Eskalasi memastikan bahwa tiket yang tidak ditangani dalam waktu yang ditentukan mendapat perhatian lebih.

### 4.2.11 Pengujian Pelacakan Tiket Publik

Pengujian pelacakan tiket publik dilakukan untuk memastikan bahwa masyarakat dapat melacak perkembangan tiket menggunakan nomor pelacakan tanpa perlu masuk ke sistem. Pengujian mencakup pencarian nomor pelacakan yang tersedia dan tidak tersedia. Hasil pengujian pelacakan tiket publik ditunjukkan pada Tabel 4.11.

**Tabel 4.11 Hasil Pengujian Pelacakan Tiket Publik**

| No | Fitur | Skenario Pengujian | Hasil yang Diharapkan | Hasil Pengujian | Keterangan |
|---:|---|---|---|---|---|
| 21 | Pelacakan Berhasil | Masyarakat memasukkan nomor pelacakan yang valid (TKT-20260714-0001) | Sistem menampilkan detail tiket yang sesuai, termasuk status, kategori, OPD yang menangani, dan riwayat penanganan | Berhasil. Halaman menampilkan informasi tiket yang dapat diakses publik tanpa informasi sensitif | Gambar 4.15 |
| 22 | Pelacakan Gagal | Masyarakat memasukkan nomor pelacakan yang tidak tersedia atau format salah | Sistem menampilkan pesan "Tiket tidak ditemukan. Pastikan nomor pelacakan benar." | Berhasil. Sistem menampilkan pesan yang sesuai tanpa mengungkapkan alasan spesifik | Gambar 4.34 |

Hasil pengujian pelacakan tiket publik menunjukkan bahwa masyarakat dapat melacak perkembangan tiket dengan mudah menggunakan nomor pelacakan. Sistem hanya menampilkan informasi yang bersifat publik dan tidak mengungkapkan informasi sensitif.

### 4.2.12 Ringkasan Hasil Pengujian Sistem

Berdasarkan hasil pengujian sistem yang telah dilakukan terhadap 22 skenario pengujian, seluruh fitur yang dikembangkan berfungsi sesuai dengan kebutuhan fungsional. Tabel 4.12 menyajikan ringkasan hasil pengujian sistem.

**Tabel 4.12 Ringkasan Hasil Pengujian Sistem**

| No | Kategori Pengujian | Jumlah Skenario | Berhasil | Gagal | Persentase Keberhasilan |
|---:|---|---:|---:|---:|---:|
| 1 | Autentikasi | 3 | 3 | 0 | 100% |
| 2 | Otorisasi | 2 | 2 | 0 | 100% |
| 3 | Sinkronisasi Data Media Sosial | 3 | 3 | 0 | 100% |
| 4 | Filter Spam | 2 | 2 | 0 | 100% |
| 5 | Klasifikasi AI | 2 | 2 | 0 | 100% |
| 6 | Deteksi Duplikasi | 3 | 3 | 0 | 100% |
| 7 | Pembuatan Tiket | 1 | 1 | 0 | 100% |
| 8 | Pengelolaan Tiket | 1 | 1 | 0 | 100% |
| 9 | Tanggapan OPD | 1 | 1 | 0 | 100% |
| 10 | SLA dan Eskalasi | 2 | 2 | 0 | 100% |
| 11 | Pelacakan Tiket Publik | 2 | 2 | 0 | 100% |
| **TOTAL** | | **22** | **22** | **0** | **100%** |

Dengan hasil pengujian sistem ini maka dapat dinyatakan bahwa Sistem Informasi Manajemen Aduan Multi Channel KMC berfungsi dengan baik dan memenuhi kebutuhan fungsional yang telah ditetapkan.
