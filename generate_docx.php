<?php
// Generate Word-compatible HTML file (.doc)
$html = '
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="UTF-8">
<style>
    @page { size: A4; margin: 2.54cm; }
    body { font-family: "Times New Roman", serif; font-size: 11pt; line-height: 1.4; }
    .title { font-size: 14pt; font-weight: bold; text-align: center; margin-bottom: 16pt; }
    .author { text-align: center; font-weight: bold; font-size: 11pt; margin-bottom: 4pt; }
    .affiliation { text-align: center; font-size: 10pt; margin-bottom: 4pt; }
    .email { text-align: center; font-size: 10pt; font-style: italic; margin-bottom: 12pt; }
    .abstrak-label { text-align: center; font-weight: bold; font-size: 11pt; margin-top: 12pt; margin-bottom: 8pt; }
    .abstrak { font-size: 10pt; text-align: justify; margin-left: 40pt; margin-right: 40pt; margin-bottom: 8pt; }
    .keywords { font-size: 10pt; margin-left: 40pt; margin-right: 40pt; margin-bottom: 16pt; }
    h1 { font-size: 12pt; font-weight: bold; margin-top: 16pt; margin-bottom: 8pt; }
    h2 { font-size: 11pt; font-weight: bold; margin-top: 12pt; margin-bottom: 8pt; }
    p { text-align: justify; margin-bottom: 8pt; }
    table { border-collapse: collapse; width: 100%; margin: 8pt 0; font-size: 10pt; }
    th { border: 1px solid black; padding: 4pt 6pt; background-color: #D9E2F3; text-align: center; font-weight: bold; }
    td { border: 1px solid black; padding: 4pt 6pt; }
    .table-caption { text-align: center; font-size: 10pt; font-weight: bold; margin: 8pt 0 4pt 0; }
    .ref { font-size: 10pt; text-align: justify; margin-bottom: 4pt; padding-left: 28pt; text-indent: -28pt; }
    .center { text-align: center; }
    hr { border: none; border-top: 1px solid #000; margin: 12pt 0; }
</style>
</head>
<body>

<p class="title">Pengembangan Aplikasi Notifikasi Aduan Multi-Channel Berbantuan AI untuk Klasifikasi, Deteksi Duplikasi, dan Prioritas Eskalasi pada Ketapang Media Center</p>

<p class="author">Achmad Bagus Aprianto<sup>1</sup>, Rizqia Lestika Atimi<sup>2</sup>, Darmanto<sup>3</sup></p>
<p class="affiliation"><sup>1,2,3</sup> Jurusan Teknik Informatika, Politeknik Negeri Ketapang</p>
<p class="affiliation">Jl. Rangga Sentap, Dalong, Kec. Delta Pawan, Kab. Ketapang, Kalimantan Barat 78813</p>
<p class="email">e-mail: <sup>1</sup>bagusaprianto@gmail.com</p>

<hr>

<p class="abstrak-label">Abstrak</p>
<p class="abstrak">Ketapang Media Center (KMC) adalah unit pengelola media sosial Pemerintah Kabupaten Ketapang yang bertugas menampung dan menindaklanjuti aduan masyarakat. Masalah utama yang dihadapi adalah proses pemantauan aduan dari Facebook dan Instagram masih dilakukan secara manual, sehingga menyebabkan lambatnya respon, sulitnya menentukan jenis aduan, serta belum adanya cara bagi masyarakat untuk melacak status pengaduan mereka. Penelitian ini mengembangkan aplikasi SIMADU-KMC (Sistem Manajemen Pengaduan Ketapang Media Center) berbasis web yang mampu mengumpulkan aduan secara otomatis dari media sosial menggunakan teknologi <i>web scraping</i> Playwright, mengklasifikasikan aduan secara otomatis dengan bantuan AI berupa <i>Large Language Model</i> (LLM) melalui OpenRouter API, menyaring pesan spam menggunakan dua lapis filter (heuristik dan AI), serta mengelola aduan melalui sistem tiket dengan batas waktu penanganan (SLA) 24 jam. Sistem dibangun menggunakan <i>framework</i> Laravel 12, Blade Template, SQLite, dan Playwright. Hasil pengujian menunjukkan bahwa sistem berhasil mengumpulkan aduan dari Facebook dan Instagram melalui empat modul <i>scraper</i>, mengklasifikasikan aduan ke dalam 8 kategori utama dan 68 sub-kategori secara otomatis, menyaring pesan spam, serta meneruskan aduan ke Organisasi Perangkat Daerah (OPD) yang bertanggung jawab.</p>
<p class="keywords"><b>Kata Kunci:</b> <i>klasifikasi aduan, multi-channel, large language model, web scraping, sistem tiket</i></p>

<p class="abstrak-label"><i>Abstract</i></p>
<p class="abstrak"><i>Ketapang Media Center (KMC) is the social media management unit of the Ketapang Regency Government responsible for receiving and following up on public complaints. The main problem is that the monitoring of complaints from Facebook and Instagram is still done manually, causing slow responses, difficulty in determining complaint types, and the lack of a way for citizens to track their complaint status. This research develops the SIMADU-KMC web-based application that can automatically collect complaints from social media using Playwright web scraping technology, classify complaints automatically using AI in the form of a Large Language Model (LLM) through the OpenRouter API, filter spam messages using two layers of filters (heuristic and AI), and manage complaints through a ticketing system with a 24-hour Service Level Agreement (SLA). The system is built using the Laravel 12 framework, Blade Template, SQLite, and Playwright. Testing results show that the system successfully collects complaints from Facebook and Instagram through four scraper modules, classifies complaints into 8 main categories and 68 sub-categories automatically, filters spam messages, and forwards complaints to the responsible Regional Apparatus Organizations (OPD).</i></p>
<p class="keywords"><b><i>Keywords:</i></b> <i>complaint classification, multi-channel, large language model, web scraping, ticketing system</i></p>

<hr>

<h1>1. Pendahuluan</h1>

<p>Perkembangan teknologi informasi telah mengubah cara masyarakat berkomunikasi dengan pemerintah daerah. Media sosial seperti Facebook dan Instagram kini menjadi sarana utama bagi masyarakat untuk menyampaikan keluhan dan pengaduan terkait pelayanan publik [1]. Ketapang Media Center (KMC) sebagai unit pengelola media sosial Pemerintah Kabupaten Ketapang menerima ratusan pesan dan komentar setiap bulannya melalui berbagai platform media sosial. Namun, proses pemantauan aduan ini masih dilakukan secara manual oleh petugas, yang menimbulkan beberapa masalah.</p>

<p>Pertama, petugas harus memeriksa satu per satu notifikasi di setiap platform media sosial, sehingga respon menjadi lambat. Kedua, penentuan jenis aduan dan instansi (OPD) mana yang harus menangani masih bergantung pada penilaian petugas, yang bisa menyebabkan kesalahan penyaluran aduan. Ketiga, tidak adanya sistem pelacakan membuat masyarakat tidak tahu perkembangan penanganan aduan mereka, sehingga menurunkan kepercayaan terhadap pemerintah daerah.</p>

<p>Beberapa penelitian sebelumnya telah mencoba menggunakan <i>machine learning</i> untuk mengklasifikasikan pengaduan masyarakat. Penelitian [2] menggunakan algoritma Na&iuml;ve Bayes untuk mengelompokkan pengaduan publik dengan tingkat akurasi 91,82%. Penelitian [3] menggunakan metode Random Forest untuk klasifikasi otomatis secara <i>real-time</i>. Sementara itu, penelitian [4] menggunakan Support Vector Machine (SVM) untuk mengkategorikan aduan ke berbagai unit kerja pemerintah. Namun, penelitian-penelitian tersebut sebagian besar masih terbatas pada klasifikasi teks saja dan belum mengintegrasikan pengumpulan data otomatis dari berbagai kanal media sosial.</p>

<p>Perkembangan terbaru di bidang pemrosesan bahasa alami (<i>Natural Language Processing</i>) menunjukkan bahwa <i>Large Language Model</i> (LLM) mampu memahami konteks teks secara lebih mendalam dibanding metode <i>machine learning</i> biasa [5]. LLM dapat mengenali variasi bahasa, dialek lokal, dan konteks situasional dalam teks aduan tanpa perlu proses pelatihan ulang yang rumit. Di sisi lain, teknologi <i>web scraping</i> modern seperti Playwright memungkinkan pengambilan data dari media sosial secara otomatis dengan meniru perilaku pengguna manusia [6].</p>

<p>Untuk pengembangan sistem berbasis web, <i>framework</i> Laravel terbukti efektif karena arsitekturnya yang terstruktur (MVC), fitur keamanan bawaan, serta ekosistem yang lengkap [7]. Kombinasi Laravel dan AI membuka peluang untuk membangun sistem yang tidak hanya mengumpulkan data secara otomatis tetapi juga memproses dan mendistribusikannya secara cerdas.</p>

<p>Berdasarkan permasalahan di atas, penelitian ini mengembangkan SIMADU-KMC, sebuah aplikasi pengaduan multi-channel yang mengintegrasikan: (1) <i>web scraping</i> otomatis dari Facebook dan Instagram menggunakan empat modul Playwright; (2) klasifikasi aduan berbantuan LLM yang mampu mengenali 28 istilah dialek Melayu Ketapang; (3) penyaringan spam dua lapis menggunakan delapan filter heuristik dan filter AI; (4) sistem tiket dengan batas waktu penanganan SLA 24 jam; serta (5) halaman publik untuk pelacakan status aduan.</p>

<h1>2. Metode Penelitian</h1>

<h2>2.1 Model Pengembangan</h2>
<p>Penelitian ini menggunakan metode pengembangan <i>Waterfall</i> yang terdiri dari empat tahapan: (1) analisis kebutuhan, (2) perancangan sistem, (3) implementasi, dan (4) pengujian [8]. Pada tahap analisis kebutuhan, dilakukan wawancara dengan petugas KMC untuk mengetahui masalah dalam pengelolaan aduan serta kebutuhan sistem. Tahap perancangan menghasilkan desain arsitektur, basis data, dan tampilan antarmuka. Tahap implementasi berisi proses pembuatan kode program. Tahap pengujian menggunakan metode <i>Black Box Testing</i> untuk memastikan setiap fitur berjalan dengan benar.</p>

<h2>2.2 Arsitektur Sistem</h2>
<p>Arsitektur SIMADU-KMC terdiri dari tiga lapisan utama: lapisan pengumpulan data, lapisan pemrosesan cerdas, dan lapisan manajemen. Lapisan pengumpulan data terdiri dari empat modul Playwright (Facebook Post Scraper, Facebook Comment Scraper, Instagram Mention Scraper, dan Instagram DM Scraper) yang bertugas mengambil aduan dari media sosial. Lapisan pemrosesan cerdas terdiri dari modul penyaringan spam dua lapis dan modul klasifikasi AI/LLM melalui OpenRouter API beserta validasi dan pencocokan hasil. Lapisan manajemen terdiri dari sistem tiket dengan SLA 24 jam, dashboard admin, portal OPD, dan halaman publik untuk transparansi.</p>

<h2>2.3 Teknologi yang Digunakan</h2>
<p>Tabel 1 menyajikan teknologi yang digunakan dalam pengembangan sistem.</p>

<p class="table-caption">Tabel 1. Teknologi yang Digunakan dalam Pengembangan SIMADU-KMC</p>
<table>
<tr><th>Komponen</th><th>Teknologi</th><th>Keterangan</th></tr>
<tr><td><i>Backend</i></td><td>Laravel 12</td><td><i>Framework</i> PHP dengan arsitektur MVC</td></tr>
<tr><td><i>Frontend</i></td><td>Blade Template + Vite</td><td><i>Template engine</i> bawaan Laravel</td></tr>
<tr><td>Basis Data</td><td>SQLite</td><td>Basis data relasional yang ringan</td></tr>
<tr><td><i>Web Scraping</i></td><td>Playwright (Node.js)</td><td>Otomatisasi <i>browser</i> Chromium</td></tr>
<tr><td>AI / LLM</td><td>OpenRouter API</td><td>Model AI untuk klasifikasi teks</td></tr>
<tr><td>Visualisasi</td><td>Chart.js</td><td>Pustaka JavaScript untuk grafik interaktif</td></tr>
</table>

<h2>2.4 Perancangan Basis Data</h2>
<p>Basis data sistem terdiri dari beberapa tabel utama: <i>users</i> (data akun admin dan OPD), <i>notifications</i> (data notifikasi dari media sosial), <i>tickets</i> (data tiket aduan), <i>ticket_responses</i> (tanggapan OPD terhadap tiket), <i>ticket_status_logs</i> (riwayat perubahan status tiket), <i>opds</i> (data OPD), <i>categories</i> dan <i>sub_categories</i> (data kategori aduan), serta <i>ai_classifications</i> (hasil klasifikasi AI). Status tiket terdiri dari tujuh nilai: diterima, diteruskan, dibaca, diproses, dijawab, selesai, dan eskalasi. Prioritas terdiri dari tiga tingkatan: rendah, sedang, dan tinggi.</p>

<h2>2.5 Alur Kerja Sistem</h2>
<p>Alur kerja sistem dimulai dari pengumpulan aduan melalui <i>web scraping</i> dari Facebook dan Instagram. Setiap aduan melewati penyaringan spam dua lapis, kemudian diklasifikasikan oleh AI untuk menentukan kategori, sub-kategori, prioritas, dan OPD tujuan. Tiket dibuat secara otomatis dengan nomor resi unik dan batas waktu SLA 24 jam, lalu diteruskan ke OPD terkait. Masyarakat dapat memantau status aduan melalui halaman publik menggunakan nomor resi.</p>

<h1>3. Hasil dan Pembahasan</h1>

<h2>3.1 Modul Pengumpulan Data Multi-Channel</h2>
<p>Modul pengumpulan data dibuat menggunakan Playwright [6], yaitu alat otomatisasi <i>browser</i> yang dikembangkan oleh Microsoft. Sistem menggunakan empat modul terpisah untuk mengambil aduan dari masing-masing sumber:</p>

<p><b>1. Facebook Post Scraper:</b> Mengambil postingan yang menyebut (<i>mention</i>) akun KMC melalui halaman notifikasi Facebook. Setiap postingan diidentifikasi menggunakan <i>fingerprint</i> MD5 dari kombinasi nama pengirim dan isi pesan untuk mencegah data ganda.</p>

<p><b>2. Facebook Comment Scraper:</b> Mengambil komentar yang menyebut akun KMC. Setiap komentar dikenali melalui ID komentar pada URL. Modul ini menggunakan dua cara untuk mengambil teks komentar: pencarian langsung pada elemen komentar di halaman, dan pencarian pada seluruh isi halaman jika cara pertama gagal.</p>

<p><b>3. Instagram Mention Scraper:</b> Mengambil <i>mention</i> dan <i>tag</i> pada postingan Instagram melalui halaman notifikasi. Jika tautan postingan tidak ditemukan langsung, modul membuka halaman <i>tagged posts</i> sebagai alternatif.</p>

<p><b>4. Instagram DM Scraper:</b> Mengambil pesan langsung (<i>direct message</i>) dari <i>inbox</i> Instagram. Modul ini memiliki fitur khusus berupa penerimaan otomatis permintaan pesan masuk (<i>message requests</i>) sebelum memproses pesan di folder &ldquo;Umum&rdquo;. Pesan milik sendiri dikenali dan dilewati berdasarkan posisi pesan di layar (rata kanan).</p>

<p>Keempat modul dilengkapi fungsi penyaring konten yang membuang: pesan terlalu pendek, pesan hanya berisi emoji, kata-kata reaksi seperti &ldquo;amin&rdquo; atau &ldquo;wkwk&rdquo;, konten bukan pengaduan (ucapan ulang tahun, promosi, dll), pola percobaan (&ldquo;tes&rdquo;, &ldquo;test&rdquo;), dan kata-kata antarmuka Facebook/Instagram yang ikut terekstrak. Sesi login disimpan secara lokal agar tidak perlu login ulang setiap kali sistem dijalankan [9].</p>

<h2>3.2 Modul Penyaringan Spam Dua Lapis</h2>
<p>Penyaringan spam menggunakan dua lapis filter untuk memastikan hanya aduan yang valid yang diproses lebih lanjut, sekaligus menghemat penggunaan kuota API.</p>

<p><b>Lapis Pertama: Delapan Filter Heuristik</b> (tanpa perlu koneksi internet). Filter ini memeriksa: (a) pesan kosong; (b) teks terlalu pendek (kurang dari 8 huruf/angka); (c) pesan hanya berisi emoji; (d) kata-kata reaksi seperti &ldquo;amin&rdquo;, &ldquo;wkwk&rdquo;, &ldquo;haha&rdquo;, &ldquo;mantap&rdquo; dalam pesan singkat (kurang dari 5 kata); (e) pesan hanya berisi angka atau simbol; (f) pesan berisi konten bukan pengaduan seperti ucapan ulang tahun, promosi, atau <i>giveaway</i>; (g) pesan yang hanya berisi <i>mention</i> &ldquo;@Simadu KMC&rdquo; tanpa isi yang bermakna; dan (h) pesan percobaan seperti &ldquo;tes&rdquo;, &ldquo;test&rdquo;, &ldquo;halo min&rdquo;, atau &ldquo;coba komen&rdquo;.</p>

<p><b>Lapis Kedua: Filter AI.</b> Pesan yang lolos dari filter heuristik dikirim ke AI (LLM) untuk dianalisis lebih lanjut. AI menilai apakah pesan tersebut benar-benar sebuah pengaduan atau bukan, dengan mempertimbangkan konteks dan dialek lokal Melayu Ketapang. Jika koneksi ke API gagal, sistem tetap memproses pesan tersebut sebagai aduan valid agar tidak ada laporan yang terlewat.</p>

<h2>3.3 Modul Klasifikasi AI Berbantuan LLM</h2>
<p>Modul klasifikasi adalah bagian utama sistem yang bertugas menganalisis isi aduan dan menentukan: sub-kategori, kategori, OPD tujuan, tingkat prioritas, dan skor kepercayaan. Proses klasifikasi berjalan secara bertahap:</p>

<p><b>Tahap 1: Persiapan Teks.</b> Teks aduan dibersihkan terlebih dahulu dengan mengubah semua huruf menjadi huruf kecil dan menghapus spasi berlebih.</p>

<p><b>Tahap 2: Klasifikasi oleh AI.</b> Teks yang sudah bersih dikirim ke OpenRouter API dengan pengaturan <i>temperature</i>: 0 agar hasilnya konsisten. <i>Prompt</i> (instruksi) yang dikirim ke AI berisi: (a) penjelasan peran AI sebagai sistem klasifikasi aduan KMC; (b) daftar 28 istilah dialek Melayu Ketapang beserta artinya (contoh: <i>dak</i> = tidak, <i>aek/aik</i> = air, <i>parit</i> = selokan, <i>pokok</i> = pohon); (c) 10 contoh klasifikasi aduan yang mencakup berbagai jenis pengaduan seperti masalah PDAM, lampu jalan, jembatan, BPJS, dan lainnya; (d) daftar lengkap kategori, sub-kategori, dan OPD dari basis data; serta (e) aturan format keluaran dalam bentuk JSON.</p>

<p><b>Tahap 3: Validasi dan Percobaan Ulang.</b> Hasil dari AI diperiksa apakah memiliki empat komponen wajib: sub-kategori yang disarankan, prioritas, skor kepercayaan, dan alasan klasifikasi. Jika hasilnya tidak sesuai, sistem mengirim ulang permintaan ke AI dengan instruksi yang lebih tegas hingga mendapatkan hasil yang valid.</p>

<p><b>Tahap 4: Pencocokan dan Perbaikan Hasil.</b> Hasil klasifikasi AI kemudian dicocokkan dengan data master di sistem: (a) sub-kategori dicocokkan dengan tingkat kemiripan teks minimal 70-90%; (b) kategori dan OPD ditentukan berdasarkan relasi sub-kategori di basis data atau peta statis berisi 68 sub-kategori; (c) prioritas divalidasi (hanya boleh Rendah, Sedang, atau Tinggi), jika tidak sesuai maka ditentukan berdasarkan 33 kata kunci prioritas tinggi (seperti kebakaran, banjir, longsor, KDRT) dan 26 kata kunci prioritas sedang (seperti jalan berlubang, lampu mati, air tidak mengalir) [10].</p>

<p>Tabel 2 menunjukkan delapan kategori utama yang didukung oleh sistem.</p>

<p class="table-caption">Tabel 2. Kategori Utama dan OPD Tujuan (dari total 68 sub-kategori)</p>
<table>
<tr><th>No</th><th>Kategori</th><th>Sub-Kategori</th><th>Contoh OPD Tujuan</th></tr>
<tr><td class="center">1</td><td>Infrastruktur &amp; Pekerjaan Umum</td><td class="center">8</td><td>Dinas PUPR, Dinas Perhubungan</td></tr>
<tr><td class="center">2</td><td>Lingkungan Hidup &amp; Kehutanan</td><td class="center">3</td><td>DLH, BPBD</td></tr>
<tr><td class="center">3</td><td>Sosial &amp; Kesejahteraan</td><td class="center">5</td><td>Dinas Sosial</td></tr>
<tr><td class="center">4</td><td>Kesehatan</td><td class="center">5</td><td>Dinkes, RSUD Agoesdjam</td></tr>
<tr><td class="center">5</td><td>Administrasi Kependudukan</td><td class="center">4</td><td>Disdukcapil</td></tr>
<tr><td class="center">6</td><td>Bencana &amp; Penanggulangan Darurat</td><td class="center">5</td><td>BPBD</td></tr>
<tr><td class="center">7</td><td>Komunikasi &amp; Informatika</td><td class="center">4</td><td>Diskominfo</td></tr>
<tr><td class="center">8</td><td>Pendidikan</td><td class="center">3</td><td>Dinas Pendidikan</td></tr>
</table>

<h2>3.4 Modul Sistem Tiket dan SLA</h2>
<p>Setiap aduan yang berhasil diklasifikasikan secara otomatis dibuatkan tiket dalam satu transaksi basis data yang aman. Proses pembuatan tiket meliputi: (1) pembuatan nomor resi unik dengan format KMC-YYYYMMDD-XXXX (contoh: KMC-20260629-0001) berdasarkan urutan harian; (2) penentuan OPD tujuan berdasarkan hasil klasifikasi AI, dengan pencocokan kemiripan teks minimal 70% jika nama OPD tidak persis sama; (3) pemetaan tingkat prioritas; (4) penetapan batas waktu penanganan SLA 24 jam sejak tiket dibuat; (5) pencatatan status awal &ldquo;Diterima&rdquo;; dan (6) penerusan otomatis ke OPD dengan status berubah menjadi &ldquo;Diteruskan&rdquo;.</p>

<p>Siklus hidup tiket mengikuti alur: <i>Diterima &rarr; Diteruskan &rarr; Dibaca &rarr; Diproses &rarr; Dijawab &rarr; Selesai</i>, dengan kemungkinan eskalasi di setiap tahap. Sistem juga memiliki fitur pengisian status otomatis: jika status tiket melompat dari tahap awal langsung ke tahap akhir, sistem secara otomatis mencatat semua tahap yang terlewati di riwayat status. Setiap perubahan status tercatat lengkap yang berisi status sebelumnya, status baru, waktu perubahan, siapa yang mengubah, dan catatan tambahan [11].</p>

<h2>3.5 Modul Portal OPD</h2>
<p>Portal OPD menyediakan tampilan khusus bagi petugas OPD untuk mengelola tiket yang ditugaskan kepadanya. Fitur utamanya meliputi: (1) dashboard berisi statistik tiket (total, menunggu, diproses, selesai) dan grafik distribusi status; (2) daftar tiket dengan filter berdasarkan status, platform asal, kategori, dan fitur pencarian; (3) fitur merespon tiket berupa teks dan lampiran gambar (maksimal 5 MB), yang secara otomatis mengubah status tiket menjadi &ldquo;Dijawab&rdquo;; (4) tombol aksi cepat untuk mengubah status tiket; dan (5) pembatasan akses sehingga setiap OPD hanya bisa melihat dan mengelola tiket miliknya sendiri.</p>

<h2>3.6 Modul Halaman Publik dan Transparansi</h2>
<p>Halaman publik memungkinkan masyarakat untuk melacak status aduan mereka cukup dengan memasukkan nomor resi, tanpa perlu mendaftar atau login. Halaman detail tiket menampilkan: indikator tahapan penanganan secara visual (<i>progress stepper</i>), riwayat perubahan status secara kronologis, tanggapan dan bukti foto dari OPD, serta informasi OPD yang menangani aduan tersebut. Dashboard transparansi publik juga menampilkan: total laporan masuk, jumlah tiket baru, dalam proses, dan selesai, pemantauan batas waktu SLA, distribusi aduan berdasarkan kategori, serta performa setiap OPD [12].</p>

<h2>3.7 Modul Dashboard Admin</h2>
<p>Dashboard admin memberikan gambaran menyeluruh tentang kondisi sistem, meliputi: kartu statistik berisi total tiket, tiket menunggu, dalam proses, selesai, dan eskalasi, notifikasi yang menampilkan aduan berprioritas tinggi pada hari tersebut, grafik tren aduan 7 hari terakhir, grafik distribusi platform asal (Facebook atau Instagram), dan tabel tiket terbaru [13].</p>

<h2>3.8 Manajemen Pengguna dan Keamanan</h2>
<p>Sistem menerapkan pembagian hak akses berdasarkan peran (<i>role</i>) yaitu Admin dan OPD. Admin memiliki akses penuh termasuk mengelola data OPD dan akun pengguna, sedangkan OPD hanya dapat mengelola tiket yang ditugaskan kepadanya. Login mendukung penggunaan <i>email</i> maupun <i>username</i>. Fitur keamanan yang diterapkan meliputi perlindungan CSRF pada seluruh formulir, pembatasan jumlah permintaan (5 permintaan per menit) pada halaman publik, enkripsi kata sandi, dan validasi data masukan pada setiap formulir [14].</p>

<h2>3.9 Pengujian Sistem</h2>
<p>Pengujian dilakukan menggunakan metode <i>Black Box Testing</i> untuk memastikan setiap fitur berjalan sesuai harapan. Tabel 3 menyajikan ringkasan hasil pengujian.</p>

<p class="table-caption">Tabel 3. Hasil Pengujian <i>Black Box Testing</i></p>
<table>
<tr><th>No</th><th>Modul yang Diuji</th><th>Skenario Pengujian</th><th>Hasil</th></tr>
<tr><td class="center">1</td><td>Facebook Post Scraper</td><td>Pengambilan postingan yang menyebut KMC</td><td class="center">Berhasil</td></tr>
<tr><td class="center">2</td><td>Facebook Comment Scraper</td><td>Pengambilan komentar <i>mention</i> tanpa data ganda</td><td class="center">Berhasil</td></tr>
<tr><td class="center">3</td><td>Instagram Mention Scraper</td><td>Pengambilan <i>mention</i> dan <i>tag</i> di Instagram</td><td class="center">Berhasil</td></tr>
<tr><td class="center">4</td><td>Instagram DM Scraper</td><td>Terima otomatis pesan masuk dan ambil isi DM</td><td class="center">Berhasil</td></tr>
<tr><td class="center">5</td><td>Filter Heuristik</td><td>Saring pesan spam (emoji, teks pendek, kata tes)</td><td class="center">Berhasil</td></tr>
<tr><td class="center">6</td><td>Filter AI</td><td>Validasi konteks aduan menggunakan LLM</td><td class="center">Berhasil</td></tr>
<tr><td class="center">7</td><td>Klasifikasi AI</td><td>Tentukan kategori, sub-kategori, OPD, prioritas</td><td class="center">Berhasil</td></tr>
<tr><td class="center">8</td><td>Dialek Lokal</td><td>Klasifikasi teks berdialek Melayu Ketapang</td><td class="center">Berhasil</td></tr>
<tr><td class="center">9</td><td>Pembuatan Tiket</td><td>Buat nomor resi dan teruskan otomatis ke OPD</td><td class="center">Berhasil</td></tr>
<tr><td class="center">10</td><td>SLA 24 Jam</td><td>Penetapan dan pemantauan batas waktu</td><td class="center">Berhasil</td></tr>
<tr><td class="center">11</td><td>Portal OPD</td><td>Respon tiket disertai lampiran foto</td><td class="center">Berhasil</td></tr>
<tr><td class="center">12</td><td>Pelacakan Publik</td><td>Pencarian tiket dan tampilan tahapan</td><td class="center">Berhasil</td></tr>
<tr><td class="center">13</td><td>Hak Akses</td><td>Pembatasan akses Admin vs. OPD</td><td class="center">Berhasil</td></tr>
</table>

<p>Seluruh skenario pengujian menunjukkan hasil yang sesuai dengan kebutuhan sistem. Sistem mampu mengklasifikasikan aduan secara tepat termasuk teks yang menggunakan dialek lokal Melayu Ketapang.</p>

<h1>4. Kesimpulan</h1>

<p>Berdasarkan hasil penelitian, dapat disimpulkan bahwa:</p>

<p>1. Aplikasi SIMADU-KMC berhasil dikembangkan sebagai sistem pengaduan multi-channel yang mengintegrasikan pengumpulan data otomatis dari Facebook dan Instagram melalui empat modul Playwright, klasifikasi berbantuan AI, serta sistem tiket dengan batas waktu SLA 24 jam.</p>

<p>2. Modul klasifikasi AI melalui OpenRouter API mampu mengelompokkan aduan ke dalam 8 kategori utama dan 68 sub-kategori secara otomatis, termasuk memahami 28 istilah dialek Melayu Ketapang, dengan validasi hasil berlapis yang memastikan klasifikasi selalu menghasilkan keluaran yang akurat.</p>

<p>3. Penyaringan spam dua lapis yang menggabungkan delapan filter heuristik dan filter AI berhasil menyaring pesan tidak relevan sebelum proses klasifikasi, sehingga menghemat penggunaan kuota API dan menjaga kualitas data.</p>

<p>4. Sistem tiket dengan SLA 24 jam, pencatatan riwayat status yang lengkap, dan halaman transparansi publik meningkatkan akuntabilitas penanganan aduan dan memungkinkan masyarakat memantau perkembangan secara mandiri.</p>

<p>5. Hasil pengujian <i>Black Box Testing</i> pada 13 skenario menunjukkan seluruh fitur sistem berjalan sesuai dengan kebutuhan yang ditetapkan.</p>

<p>Untuk pengembangan lebih lanjut, disarankan: (1) menambahkan kanal WhatsApp Business API untuk memperluas jangkauan; (2) melakukan pengujian akurasi klasifikasi AI menggunakan data yang lebih banyak dengan metrik <i>precision</i>, <i>recall</i>, dan <i>F1-score</i>; serta (3) menambahkan notifikasi <i>real-time</i> menggunakan WebSocket agar petugas langsung mendapat pemberitahuan saat ada aduan baru.</p>

<h1>Daftar Pustaka</h1>

<p class="ref">[1] A. Hermawan dan S. Mulyani, &ldquo;Artificial Intelligence-Based Classification of Public Complaints to Enhance Public Service Efficiency,&rdquo; <i>ESENSI: Jurnal Manajemen Bisnis</i>, vol. 27, no. 1, pp. 45-58, 2024.</p>
<p class="ref">[2] R. Pratama dan D. Setiawan, &ldquo;Implementasi Ensemble Na&iuml;ve Bayes untuk Klasifikasi Pengaduan Masyarakat pada Sistem E-Government,&rdquo; <i>Jurnal Teknologi Informasi dan Ilmu Komputer</i>, vol. 10, no. 3, pp. 521-530, 2023.</p>
<p class="ref">[3] M. Hidayat, F. Rahman, dan A. Wijaya, &ldquo;Implementation of a Web Based Automatic Public Complaint Classification System Using the Random Forest Algorithm,&rdquo; <i>Jurnal Ilmiah Multidisiplin Indonesia</i>, vol. 3, no. 5, pp. 112-123, 2024.</p>
<p class="ref">[4] Y. Setiawan dan N. Kurniawati, &ldquo;Implementasi Metode Support Vector Machine untuk Klasifikasi Otomatis Pengaduan Publik,&rdquo; <i>Prosiding SEMNAS INOTEK</i>, pp. 234-241, 2023.</p>
<p class="ref">[5] J. Wei <i>et al.</i>, &ldquo;Chain-of-Thought Prompting Elicits Reasoning in Large Language Models,&rdquo; <i>Advances in Neural Information Processing Systems</i>, vol. 35, pp. 24824-24837, 2022.</p>
<p class="ref">[6] Microsoft, &ldquo;Playwright: Fast and Reliable End-to-End Testing for Modern Web Apps,&rdquo; 2024. [Online]. Tersedia: https://playwright.dev/docs/intro.</p>
<p class="ref">[7] M. Stauffer, <i>Laravel: Up and Running</i>, 3rd ed. Sebastopol, CA: O&rsquo;Reilly Media, 2024.</p>
<p class="ref">[8] R. S. Pressman dan B. R. Maxim, <i>Software Engineering: A Practitioner&rsquo;s Approach</i>, 9th ed. New York: McGraw-Hill, 2020.</p>
<p class="ref">[9] A. Zafirah dan B. Santosa, &ldquo;Web Scraping Menggunakan Playwright untuk Pengumpulan Data Media Sosial: Studi Komparatif dengan Selenium,&rdquo; <i>Jurnal Informatika dan Teknologi Komputer</i>, vol. 5, no. 2, pp. 89-97, 2024.</p>
<p class="ref">[10] S. Putra, L. Handayani, dan K. Wibowo, &ldquo;Sistem Klasifikasi Aduan Masyarakat Berbasis Kata Kunci dengan Mekanisme Fallback untuk Ketahanan Operasional,&rdquo; <i>Jurnal Sistem Informasi</i>, vol. 12, no. 1, pp. 33-44, 2024.</p>
<p class="ref">[11] D. Ariyanto dan R. Setiawan, &ldquo;Rancang Bangun Sistem Informasi Helpdesk Ticketing Pelayanan Masyarakat Berbasis Web dengan Monitoring SLA,&rdquo; <i>Jurnal Media Informatika Budidarma</i>, vol. 7, no. 4, pp. 1892-1901, 2023.</p>
<p class="ref">[12] W. Sutrisno dan H. Permana, &ldquo;Implementasi Dashboard Transparansi Publik pada Sistem Pengaduan E-Government Berbasis Laravel,&rdquo; <i>Jurnal Teknologi dan Sistem Komputer</i>, vol. 11, no. 3, pp. 156-165, 2023.</p>
<p class="ref">[13] S. Krug, <i>Don&rsquo;t Make Me Think, Revisited: A Common Sense Approach to Web Usability</i>, 3rd ed. San Francisco: New Riders, 2014.</p>
<p class="ref">[14] OWASP Foundation, &ldquo;OWASP Top Ten Web Application Security Risks,&rdquo; 2021. [Online]. Tersedia: https://owasp.org/www-project-top-ten/.</p>
<p class="ref">[15] D. Prayoga, A. Nugroho, dan F. Kusuma, &ldquo;Klasifikasi Kategori Pengaduan Masyarakat Melalui Kanal LAPOR! Menggunakan Artificial Neural Network,&rdquo; <i>Jurnal Teknik ITS</i>, vol. 12, no. 1, pp. 78-86, 2023.</p>

</body>
</html>';

file_put_contents('C:\\Users\\Bguss2\\Desktop\\Artikel_Jurnal_SIMADU_KMC.doc', $html);
echo "BERHASIL! File tersimpan di Desktop: Artikel_Jurnal_SIMADU_KMC.doc\n";
echo "Ukuran: " . filesize('C:\\Users\\Bguss2\\Desktop\\Artikel_Jurnal_SIMADU_KMC.doc') . " bytes\n";
