## BAB I PENDAHULUAN
**File asli:** LAPORAN TA AchmadBagusA_TI6A_3042023024-2.docx
**Tanggal humanize:** 11 Juli 2026

---

### 1.1 Latar Belakang

Ketapang Media Center (KMC) adalah unit di bawah Dinas Komunikasi dan Informatika Kabupaten Ketapang yang bertugas menerima dan mengelola aduan masyarakat. Setiap harinya, pesan dan komentar berisi laporan warga masuk dari berbagai platform seperti WhatsApp, Facebook, Instagram, hingga media sosial resmi lainnya. Namun selama ini, petugas harus membuka satu per satu platform tersebut secara terpisah dan manual untuk memantau aduan yang masuk. Ketika jumlah laporan meningkat sekaligus dari banyak saluran, proses ini menjadi sangat melelahkan dan rawan terlewat.

Media sosial memang sudah menjadi cara utama warga Ketapang menyampaikan keluhan. Aksesnya mudah, cepat, dan langsung bisa menjangkau banyak pihak sekaligus. Tapi justru di sinilah masalah muncul: tidak semua aduan yang masuk memiliki urgensi yang sama, dan pengelolaan yang sepenuhnya manual membuat petugas sulit membedakan mana yang harus segera ditangani.

Permasalahannya tidak berhenti di situ. Aduan yang masuk dari berbagai akun dan platform berpotensi membahas permasalahan yang sama. Misalnya, satu kejadian kerusakan jalan bisa dilaporkan oleh puluhan warga dalam waktu bersamaan melalui Facebook, Instagram, bahkan komentar di postingan yang berbeda-beda. Tanpa sistem yang bisa mengenali kemiripan isi laporan, petugas bisa saja membuat tiket terpisah untuk setiap laporan tersebut, padahal semuanya merujuk pada satu masalah yang sama. Akibatnya, data menjadi duplikat, pekerjaan ganda, dan laporan tidak efisien.

Di sisi lain, tidak semua petugas memiliki waktu atau kapasitas untuk membaca dan menilai isi setiap aduan secara mendalam sebelum meneruskannya ke OPD yang berwenang. Proses ini sangat bergantung pada penilaian subjektif, dan tanpa panduan otomatis, risiko salah kategorisasi atau keterlambatan penanganan cukup besar, khususnya untuk aduan yang sifatnya mendesak.

Atas dasar kondisi tersebut, penelitian ini mengembangkan Sistem Informasi Manajemen Aduan Multi Channel KMC, sebuah aplikasi berbasis web yang membantu petugas memantau dan mengelola aduan secara terpusat. Pada tahap awal ini, sistem difokuskan pada Facebook dan Instagram, dengan tiga mekanisme pengambilan data otomatis: pemantauan mention di akun resmi Facebook KMC, pemantauan mention di komentar postingan masyarakat, dan pemantauan pesan langsung (direct message) Instagram.

Yang membedakan sistem ini dari solusi serupa adalah pemanfaatan kecerdasan buatan (AI) untuk mengelompokkan aduan yang lolos penyaringan secara otomatis ke dalam kategori, subkategori, OPD yang berwenang, dan tingkat prioritas yang sesuai. Untuk aduan yang tidak terdeteksi sebagai duplikat, hasil klasifikasi digunakan sistem untuk membuat tiket secara otomatis dan meneruskannya kepada OPD terkait. Aduan yang terdeteksi sebagai kemungkinan duplikat ditahan sebagai notifikasi sampai admin memastikan status duplikasinya. Admin juga dapat mengubah kategori, subkategori, OPD, atau prioritas apabila ditemukan ketidaksesuaian.

Sistem juga menerapkan Service Level Agreement (SLA) selama 24 jam. Apabila OPD belum memberikan tanggapan hingga batas waktu tersebut, tiket ditandai sebagai Out SLA dan statusnya otomatis berubah menjadi Proses Disposisi. Perubahan status ini menjadi penanda bahwa tiket masih memerlukan tindak lanjut dari instansi terkait.

---

### 1.2 Rumusan Masalah

Berdasarkan latar belakang yang telah diuraikan, penelitian ini berfokus pada lima permasalahan berikut:

1. Bagaimana mengembangkan Sistem Informasi Manajemen Aduan Multi Channel KMC berbasis web yang mampu mengelola aduan masyarakat secara terpusat dari Facebook dan Instagram?
2. Bagaimana menerapkan AI untuk mengklasifikasikan aduan secara otomatis berdasarkan isi pesan yang diterima?
3. Bagaimana menampilkan notifikasi aduan secara cepat dan terstruktur pada dashboard admin agar pemantauan lebih efektif?
4. Bagaimana menentukan kategori, subkategori, tingkat prioritas, dan OPD terkait secara otomatis, serta membuat tiket untuk aduan yang tidak terdeteksi sebagai duplikat?
5. Bagaimana menyimpan data aduan, hasil klasifikasi AI, hasil deteksi duplikasi, serta riwayat tiket ke dalam basis data agar dapat digunakan kembali dalam proses pengelolaan berikutnya?

---

### 1.3 Batasan Masalah

Agar pembahasan lebih terarah, penelitian ini dibatasi pada hal-hal berikut:

1. Sistem yang dikembangkan adalah aplikasi web Sistem Informasi Manajemen Aduan Multi Channel KMC untuk membantu pengelolaan aduan masyarakat.
2. Sumber dan pengambilan data aduan dibatasi pada Facebook dan Instagram; platform lain seperti WhatsApp atau Telegram tidak termasuk dalam penelitian ini.
3. AI digunakan untuk mengklasifikasikan aduan secara otomatis, meliputi kategori, subkategori, tingkat prioritas, OPD terkait, serta deteksi kemungkinan duplikasi.
4. Aduan yang tidak terdeteksi sebagai duplikat dibuatkan tiket secara otomatis. Aduan yang terdeteksi sebagai kemungkinan duplikat menunggu konfirmasi admin sebelum tiket dibuat atau notifikasi diarsipkan. Admin dapat mengubah kategori, subkategori, OPD, atau prioritas apabila ditemukan kesalahan.
5. Lingkup penelitian mencakup penerimaan aduan, notifikasi, klasifikasi, deteksi duplikasi, pembuatan tiket, disposisi ke OPD, serta perubahan status menjadi Proses Disposisi ketika tiket melewati SLA 24 jam. Jika tiket tersebut kembali melewati SLA 24 jam berikutnya, sistem mencatat eskalasi dan dapat menaikkan prioritasnya. Evaluasi kepuasan masyarakat terhadap hasil penanganan aduan di lapangan tidak termasuk dalam ruang lingkup penelitian.
6. Pengembangan integrasi dengan platform lain dan penambahan fitur lanjutan dapat dilakukan pada penelitian selanjutnya.

---

### 1.4 Tujuan Penelitian

Penelitian ini bertujuan untuk:

1. Mengembangkan Sistem Informasi Manajemen Aduan Multi Channel KMC berbasis web untuk membantu pengelolaan aduan masyarakat secara terpusat.
2. Mengintegrasikan aduan dari Facebook dan Instagram ke dalam satu sistem agar proses pemantauan lebih mudah dan terstruktur.
3. Menerapkan AI untuk mengklasifikasikan aduan dan mendeteksi kemungkinan duplikasi berdasarkan isi pesan yang masuk.
4. Menghasilkan kategori, subkategori, tingkat prioritas, dan OPD terkait secara otomatis sebagai dasar pembuatan tiket aduan yang tidak terdeteksi sebagai duplikat.
5. Menyimpan data aduan, hasil klasifikasi AI, hasil deteksi duplikasi, dan riwayat tiket ke dalam basis data untuk keperluan pengelolaan aduan berikutnya.

---

### 1.5 Manfaat Penelitian

**Bagi Ketapang Media Center (KMC)**

Sistem ini membantu admin KMC memantau aduan dari Facebook dan Instagram dalam satu tampilan terpusat. Klasifikasi otomatis dari AI mempercepat pengelompokan aduan, sedangkan informasi prioritas dan OPD tujuan membantu proses penerusan tiket. Sistem juga menandai kemungkinan duplikasi sehingga tiket ganda dapat dicegah. Seluruh data aduan dan riwayat tiket tersimpan secara terstruktur untuk pelaporan serta evaluasi layanan publik.

**Bagi Masyarakat**

Dengan pengelolaan aduan yang lebih cepat dan sistematis, laporan yang disampaikan melalui media sosial dapat terdata dan diteruskan kepada OPD terkait. Perkembangan tiket juga dapat dilihat melalui portal pelacakan publik menggunakan nomor pelacakan tiket.

**Bagi Penulis**

Penelitian ini menjadi kesempatan nyata untuk menerapkan ilmu yang dipelajari selama perkuliahan, khususnya di bidang pengembangan perangkat lunak berbasis web dan kecerdasan buatan. Sekaligus memenuhi persyaratan akademik untuk memperoleh gelar Ahli Madya pada Program Studi D3 Teknologi Informasi Politeknik Negeri Ketapang.

**Bagi Program Studi dan Peneliti Selanjutnya**

Laporan ini dapat menjadi referensi bagi penelitian berikutnya yang berhubungan dengan sistem manajemen aduan berbasis media sosial, penerapan AI dalam pelayanan publik, maupun pengembangan integrasi multi-platform yang belum sempat diimplementasikan dalam penelitian ini.

---

### 1.6 Sistematika Penulisan

Laporan tugas akhir ini disusun dalam lima bab dengan alur sebagai berikut:

**BAB I: Pendahuluan** menguraikan latar belakang masalah, rumusan masalah, batasan penelitian, tujuan, manfaat, dan sistematika penulisan laporan ini.

**BAB II: Tinjauan Pustaka** memuat penelitian terdahulu yang relevan, teori-teori dasar yang mendukung sistem, serta profil Ketapang Media Center sebagai tempat penelitian.

**BAB III: Metodologi Penelitian dan Perancangan Sistem** menjelaskan metode yang digunakan, alat dan bahan, prosedur penelitian, serta perancangan sistem secara menyeluruh mulai dari arsitektur, arus data, basis data, tampilan antarmuka, hingga rencana pengujian.

**BAB IV: Hasil Penelitian** membahas hasil implementasi sistem yang telah dibangun, hasil pengujian fungsionalitas, serta pembahasan terhadap hasil pengujian tersebut.

**BAB V: Penutup** berisi kesimpulan dari hasil penelitian dan saran untuk pengembangan sistem pada penelitian selanjutnya.
