# BAB II — TINJAUAN PUSTAKA (REVISI FEEDBACK DOSEN)
**Sistem Informasi Manajemen Aduan Multi Channel KMC**

**Mahasiswa:** Achmad Bagus Aprianto (3042023024)
**Status:** ✅ REVISI BERDASARKAN FEEDBACK DOSEN
**Tanggal Revisi:** 7 Juli 2026
**Perubahan:**
- Hapus semua "Dalam penelitian ini..."
- Tambah 5 sub-bab: MVC, PBO, Laragon, VS Code, DBMS
- Ganti "penelitian" → "Tugas Akhir"
- Bahasa lebih natural tapi tetap akademis

---

## BAB II
## TINJAUAN PUSTAKA

### 2.1 Tinjauan Penelitian Terdahulu

Tinjauan penelitian terdahulu digunakan sebagai dasar untuk mengetahui perkembangan kajian, metode yang digunakan, serta celah yang masih dapat dikembangkan. Pada topik pengelolaan aduan, beberapa studi menunjukkan bahwa proses manual sering kali kurang efisien sehingga dibutuhkan pendekatan otomatis berbasis kecerdasan buatan, klasifikasi teks, dan mekanisme prioritas.

Ananto, dkk. (2019) mengklasifikasikan kategori pengaduan masyarakat pada kanal LAPOR! menggunakan Artificial Neural Network (ANN) yang dikombinasikan dengan SMOTE untuk mengatasi ketidakseimbangan data dan Chi-Square untuk seleksi fitur, menghasilkan precision 0,794, sensitivity 0,818, dan F1-Score 0,800 dari 428 kata kunci yang berpengaruh. Studi ini terbatas pada satu kanal resmi pemerintah dan mengandalkan model yang harus dilatih ulang dengan data berlabel. Sistem yang dikembangkan dalam Tugas Akhir ini berbeda karena mengambil aduan dari beberapa kanal media sosial sekaligus (Facebook dan Instagram) serta memanfaatkan LLM pre-trained (Gemma) sehingga tidak memerlukan pelatihan ulang, sekaligus menangani istilah dialek lokal yang tidak dibahas pada studi tersebut.

Zarly, dkk. (2026) membangun sistem klasifikasi pengaduan otomatis berbasis web menggunakan algoritma Random Forest di Dinas Kependudukan dan Pencatatan Sipil Kabupaten Pesisir Selatan, dengan integrasi Laravel dan Flask API, dievaluasi menggunakan accuracy, precision, recall, dan F1-score. Sistem tersebut spesifik untuk satu instansi dan satu algoritma machine learning konvensional yang membutuhkan pelatihan model. Sistem yang dikembangkan dalam Tugas Akhir ini mencakup klasifikasi otomatis ke 32 OPD sekaligus tanpa proses pelatihan model karena berbasis LLM generatif.

Prayogo, dkk. (2026) meneliti klasifikasi validitas pengaduan masyarakat (valid/tidak valid) menggunakan pendekatan deep learning terhadap 2.000 data aduan, dilatarbelakangi temuan bahwa 13% instansi pemerintah daerah tidak memenuhi SLA 100% pada tahun 2023. Studi ini berhenti pada klasifikasi biner validitas aduan tanpa mekanisme tindak lanjut. Sistem yang dikembangkan dalam Tugas Akhir ini melangkah lebih jauh dengan penyaringan spam dua lapis, klasifikasi multi-kelas (kategori, sub-kategori, OPD), serta mekanisme tiket dengan eskalasi otomatis berbasis SLA 24 jam.

Mazia, dkk. (2021) merancang sistem informasi helpdesk ticketing berbasis web untuk kebutuhan internal PT Mitra Tiga Berlian, di mana tiket dibuat manual oleh staf saat terjadi kendala TI. Sistem yang dikembangkan dalam Tugas Akhir ini berbeda karena tiket terbentuk otomatis dari hasil penyaringan dan klasifikasi AI atas aduan publik yang diambil langsung dari media sosial, dilengkapi SLA 24 jam dan eskalasi prioritas otomatis—mekanisme yang tidak ada pada sistem helpdesk internal tersebut.

Berdasarkan keempat studi tersebut, klasifikasi pengaduan berbasis AI, sistem ticketing, dan pengukuran validitas/SLA masing-masing telah diteliti secara terpisah. Tugas Akhir ini mengintegrasikan ketiganya sekaligus—pengumpulan otomatis multi-kanal, klasifikasi berbasis LLM generatif tanpa pelatihan ulang dengan dukungan dialek lokal, serta manajemen tiket dengan SLA dan eskalasi otomatis dalam satu sistem yang belum ditemukan pada studi-studi sebelumnya.

---

### 2.2 Tinjauan Pustaka

#### 2.2.1 Sistem Informasi

Sistem informasi merupakan kombinasi dari komponen-komponen yang terdiri atas manusia, teknologi informasi, dan prosedur kerja yang memproses, menyimpan, menganalisis, dan menyebarkan informasi untuk tujuan tertentu dalam suatu organisasi (Kadir, 2019). Sistem informasi berperan sebagai pendukung utama dalam proses pengambilan keputusan, koordinasi, dan pengendalian dalam organisasi dengan menyediakan informasi yang akurat, tepat waktu, dan relevan. Dalam konteks pelayanan publik, sistem informasi membantu instansi pemerintah untuk mengelola data masyarakat, memproses laporan atau keluhan, serta menyajikan informasi yang dibutuhkan untuk evaluasi kinerja.

---

#### 2.2.2 Aplikasi Berbasis Web

Aplikasi berbasis web adalah perangkat lunak yang dijalankan pada server dan diakses melalui browser web, tanpa memerlukan instalasi pada perangkat klien (Rosa & Shalahuddin, 2018). Berbeda dengan aplikasi desktop tradisional yang harus diinstal di setiap perangkat pengguna, aplikasi web hanya memerlukan browser dan koneksi internet untuk diakses. Hal ini memudahkan distribusi pembaruan karena perubahan cukup dilakukan di server saja, tanpa perlu mengupdate setiap perangkat klien secara manual.

Aplikasi web juga memungkinkan akses simultan dari berbagai perangkat dan lokasi, sehingga cocok untuk sistem yang melayani banyak pengguna seperti dashboard pengelolaan aduan publik. Dalam konteks KMC, pendekatan web-based memungkinkan petugas dari berbagai OPD untuk memantau dan menangani aduan tanpa terikat pada satu perangkat atau lokasi kantor tertentu.

---

#### 2.2.3 Manajemen Aduan

Manajemen aduan adalah proses sistematis untuk menerima, mencatat, mengklasifikasikan, menindaklanjuti, dan menyelesaikan keluhan atau laporan dari masyarakat (ISO 10002:2018). Sistem manajemen aduan yang efektif harus mampu menangani volume tinggi, memberikan transparansi kepada pelapor, serta memastikan akuntabilitas penanganan oleh instansi terkait. Standar ISO 10002:2018 menetapkan pedoman untuk merancang dan menerapkan proses penanganan keluhan yang efektif dan efisien, termasuk prinsip-prinsip seperti aksesibilitas, responsivitas, objektivitas, kerahasiaan, dan pendekatan berorientasi pelanggan.

---

#### 2.2.4 Multi-Channel

Multi-channel adalah pendekatan dalam penyediaan layanan yang memungkinkan pengguna untuk berinteraksi dengan organisasi melalui berbagai saluran komunikasi yang terintegrasi (Rangkuti, 2019). Dalam konteks pengelolaan aduan publik, strategi multi-channel berarti masyarakat tidak harus terbatas pada satu saluran saja—mereka dapat memilih menyampaikan laporan melalui Facebook, Instagram, website resmi, atau saluran lain sesuai preferensi masing-masing.

Pendekatan ini penting karena tidak semua segmen masyarakat menggunakan platform yang sama. Generasi muda cenderung aktif di media sosial, sementara kelompok lain mungkin lebih nyaman menggunakan email atau telepon. Dengan mengintegrasikan berbagai kanal ke dalam satu sistem terpusat, KMC dapat memastikan bahwa setiap aduan—dari mana pun asalnya—tercatat, terpantau, dan tertangani dengan standar yang sama.

---

#### 2.2.5 Notifikasi Real-Time

Notifikasi real-time adalah mekanisme penyampaian informasi secara instan kepada pengguna pada saat terjadinya suatu kejadian atau perubahan status dalam sistem (Nugroho & Purnama, 2020). Sistem notifikasi real-time memungkinkan pengguna untuk segera mengetahui informasi penting tanpa harus melakukan pemeriksaan manual secara berkala, sehingga meningkatkan responsivitas dalam pengambilan keputusan. Teknologi yang umum digunakan untuk notifikasi real-time meliputi WebSocket, Server-Sent Events (SSE), atau mekanisme polling pada interval pendek.

---

#### 2.2.6 Artificial Intelligence (AI)

Artificial Intelligence (AI) merupakan cabang ilmu komputer yang berfokus pada pengembangan sistem yang dapat meniru kemampuan berpikir manusia dalam menyelesaikan suatu permasalahan (Russell & Norvig, 2020). Dalam sistem pengelolaan aduan, AI berperan untuk mengotomatisasi tugas-tugas yang sebelumnya memerlukan analisis manual, seperti membaca isi aduan, memahami konteksnya, dan menentukan kategori serta pihak yang bertanggung jawab menanganinya. Dengan kemampuan ini, AI membantu mengurangi beban kerja petugas dan mempercepat waktu respons terhadap laporan masyarakat, terutama ketika volume aduan yang masuk cukup tinggi dalam waktu bersamaan.

---

#### 2.2.7 Klasifikasi Aduan

Klasifikasi aduan adalah proses pengelompokan aduan ke dalam kategori tertentu berdasarkan isi dan konteks pesan yang disampaikan (Wicaksono & Purwarianti, 2020). Klasifikasi teks secara otomatis memanfaatkan teknik pemrosesan bahasa alami untuk menganalisis dan mengkategorikan dokumen teks ke dalam kelas-kelas yang telah ditentukan sebelumnya, sehingga mempercepat proses penanganan dan meningkatkan akurasi distribusi tugas. Teknik klasifikasi yang umum digunakan meliputi algoritma Naive Bayes, Support Vector Machine (SVM), Random Forest, dan pendekatan berbasis deep learning seperti neural network atau transformer.

---

#### 2.2.8 Deteksi Duplikasi Aduan

Deteksi duplikasi aduan adalah proses identifikasi terhadap dua atau lebih aduan yang memiliki kesamaan isi, topik, atau merujuk pada permasalahan yang sama (Christanto & Tjahyana, 2021). Duplikasi sering terjadi ketika satu permasalahan dilaporkan oleh beberapa warga melalui akun berbeda, atau ketika satu pengguna mengirimkan aduan serupa melalui kanal yang berbeda dalam waktu berdekatan.

Sebagai contoh, jika tiga warga melaporkan masalah "jalan berlubang di Jl. Sudirman" melalui Facebook dan Instagram dalam satu hari, sistem perlu mengenali bahwa ketiga laporan tersebut merujuk pada permasalahan yang sama agar tidak membuat tiga tiket terpisah. Deteksi duplikasi juga membantu mengidentifikasi tingkat urgensi—jika banyak warga melaporkan masalah yang sama secara bersamaan, hal ini mengindikasikan bahwa permasalahan tersebut perlu diprioritaskan.

Metode deteksi duplikasi dapat menggunakan pendekatan kemiripan teks berbasis Cosine Similarity yang membandingkan konten aduan dengan laporan yang masuk dalam rentang waktu tertentu, sehingga aduan duplikat dapat dikelompokkan secara otomatis.

---

#### 2.2.9 Prioritas Eskalasi Aduan

Prioritas eskalasi aduan adalah mekanisme penentuan tingkat urgensi suatu aduan berdasarkan kriteria tertentu, yang menentukan seberapa cepat aduan tersebut harus ditangani (Prasetyo & Suyanto, 2019). Eskalasi merupakan proses peningkatan level penanganan ketika aduan tidak ditindaklanjuti dalam batas waktu yang ditentukan, memastikan bahwa setiap laporan masyarakat mendapatkan perhatian yang semestinya.

Tidak semua aduan memiliki tingkat urgensi yang sama—laporan tentang jalan rusak yang menghambat akses umum tentu lebih mendesak dibanding keluhan administrasi rutin. Sistem eskalasi otomatis bekerja dengan memantau waktu penanganan setiap tiket dan secara otomatis menaikkan prioritas jika batas waktu terlampaui, sehingga aduan tidak terabaikan meskipun petugas sedang menangani volume tinggi. Mekanisme ini juga membantu alokasi sumber daya yang lebih tepat, karena petugas dapat fokus menangani aduan dengan prioritas tertinggi terlebih dahulu.

---

#### 2.2.10 Service Level Agreement (SLA)

Service Level Agreement (SLA) adalah komitmen formal antara penyedia layanan dan pengguna mengenai standar kualitas layanan yang dijanjikan, termasuk batas waktu penyelesaian suatu permintaan atau keluhan (Hidayat & Muttaqin, 2020). SLA berfungsi sebagai acuan untuk mengukur kinerja pelayanan dan memastikan akuntabilitas pihak penyedia layanan dalam memenuhi ekspektasi pengguna. Dalam konteks pelayanan publik, SLA membantu instansi pemerintah untuk menetapkan standar waktu respons yang jelas dan terukur, sehingga masyarakat memiliki kepastian mengenai waktu penyelesaian aduan mereka.

---

#### 2.2.11 Dashboard

Dashboard adalah representasi visual dari data-data penting yang diperlukan untuk mencapai suatu tujuan, yang disusun dan dipadukan dalam satu tampilan sehingga dapat dilihat secara menyeluruh (Irawan & Simargolang, 2019). Dashboard memungkinkan pengguna untuk memantau kondisi terkini, mengidentifikasi tren, dan mengambil keputusan secara cepat berdasarkan data yang disajikan secara ringkas dan informatif.

Dashboard yang efektif dirancang untuk menyajikan informasi penting dalam satu halaman tanpa perlu berpindah-pindah menu, menggunakan visualisasi seperti grafik dan indikator status yang mudah dipahami sekilas, serta mendukung pembaruan data secara berkala atau real-time. Dalam konteks pengelolaan aduan, dashboard membantu manajemen KMC untuk melihat seberapa banyak aduan yang masuk hari ini, berapa yang sudah ditangani, dan mana yang mendekati batas waktu SLA—semua informasi ini tersedia dalam satu tampilan untuk mendukung pengambilan keputusan yang cepat.

---

#### 2.2.12 Sistem Ticketing

Sistem ticketing adalah mekanisme pengelolaan permintaan layanan atau laporan yang menggunakan tiket sebagai unit pencatatan, pelacakan, dan penyelesaian setiap kasus (Mazia dkk., 2021). Setiap tiket merepresentasikan satu aduan atau permasalahan yang memiliki siklus hidup mulai dari pembuatan, penugasan, penanganan, hingga penyelesaian. Dengan pendekatan ticketing, setiap aduan tercatat dengan nomor unik sehingga mudah dilacak status penanganannya secara transparan, mendukung mekanisme disposisi ke pihak yang bertanggung jawab, dan menyediakan riwayat penanganan yang berguna untuk evaluasi kinerja di masa mendatang.

---

#### 2.2.13 Organisasi Perangkat Daerah (OPD)

Organisasi Perangkat Daerah (OPD) adalah instansi pemerintah daerah yang memiliki tugas dan fungsi tertentu dalam penyelenggaraan pemerintahan sesuai dengan Undang-Undang No. 23 Tahun 2014 tentang Pemerintahan Daerah. OPD berperan sebagai pelaksana urusan pemerintahan di tingkat daerah, baik yang bersifat wajib maupun pilihan. Dalam konteks pengelolaan aduan masyarakat, OPD merupakan pihak yang bertanggung jawab untuk menangani permasalahan sesuai dengan bidang tugasnya, seperti Dinas Perhubungan untuk masalah transportasi, Dinas Pekerjaan Umum untuk masalah infrastruktur, atau Dinas Kesehatan untuk masalah layanan kesehatan.

---

#### 2.2.14 Natural Language Processing (NLP)

Natural Language Processing (NLP) adalah cabang kecerdasan buatan yang fokus pada interaksi antara komputer dan bahasa manusia (Jurafsky & Martin, 2023). NLP memungkinkan komputer untuk memahami teks atau percakapan manusia, menafsirkan maksudnya, dan memberikan respons yang sesuai—mirip seperti cara manusia membaca dan memahami sebuah pesan.

Dalam sistem klasifikasi aduan, NLP berperan untuk menganalisis teks aduan yang ditulis masyarakat dengan gaya bahasa sehari-hari, termasuk istilah lokal atau singkatan informal, kemudian mengekstrak informasi penting seperti jenis permasalahan, lokasi kejadian, dan tingkat urgensi. Teknologi ini menjadi fondasi bagi model AI untuk memahami konteks aduan tanpa perlu format input yang kaku atau terstruktur.

---

#### 2.2.15 Large Language Model (LLM)

Large Language Model (LLM) adalah model kecerdasan buatan berbasis arsitektur Transformer yang dilatih pada dataset teks dalam skala besar untuk memahami dan menghasilkan bahasa natural (Vaswani et al., 2017). Berbeda dengan model machine learning konvensional yang perlu dilatih ulang untuk setiap tugas spesifik, LLM memiliki kemampuan generatif yang memungkinkan model untuk menyelesaikan berbagai tugas bahasa hanya dengan pemberian instruksi atau prompt yang tepat—tanpa perlu data pelatihan tambahan yang ekstensif.

Dalam sistem pengelolaan aduan, LLM digunakan untuk mengklasifikasikan teks aduan ke dalam kategori, sub-kategori, dan OPD tujuan secara otomatis. Model ini mampu memahami konteks aduan meskipun ditulis dengan bahasa informal atau menggunakan istilah lokal Ketapang, karena telah dilatih pada dataset bahasa yang sangat luas. Pendekatan ini jauh lebih efisien dibanding metode konvensional yang memerlukan ratusan atau ribuan data berlabel untuk setiap kategori baru.

---




#### 2.2.16 Laravel

Laravel merupakan framework PHP open-source yang dikembangkan oleh Taylor Otwell dan menggunakan pola arsitektur Model-View-Controller (MVC) untuk mempermudah pengembangan aplikasi web secara terstruktur (Harianto dkk., 2019). Laravel menyediakan berbagai fitur bawaan yang mempercepat proses pengembangan, seperti Eloquent ORM untuk pengelolaan database secara object-oriented, Blade sebagai template engine untuk tampilan antarmuka, serta Artisan CLI untuk otomatisasi tugas-tugas pengembangan seperti membuat model, controller, dan migration.

Framework ini juga dilengkapi dengan sistem middleware untuk autentikasi dan autorisasi, migration untuk pengelolaan struktur database secara terversikan, serta mekanisme queue dan job untuk menangani proses berat di background secara asynchronous. Ekosistem Laravel yang kaya—termasuk Laravel Sanctum untuk API authentication dan Laravel Horizon untuk monitoring queue—ditambah dengan komunitas pengembang yang besar serta dokumentasi yang lengkap, menjadikan Laravel salah satu framework PHP paling populer untuk pengembangan aplikasi web modern.

---

#### 2.2.17 PHP (Hypertext Preprocessor)

PHP adalah bahasa pemrograman server-side yang banyak digunakan untuk pengembangan aplikasi web dinamis. PHP pertama kali dikembangkan oleh Rasmus Lerdorf pada tahun 1994 dan terus berkembang hingga menjadi salah satu bahasa pemrograman web paling populer di dunia (PHP Group, 2023). PHP bersifat open-source sehingga dapat digunakan secara gratis, mendukung berbagai sistem manajemen basis data seperti MySQL, PostgreSQL, dan SQLite, serta memiliki komunitas pengembang yang besar dengan dokumentasi yang lengkap.

PHP mendukung paradigma pemrograman berorientasi objek secara penuh, termasuk konsep class, inheritance, interface, trait, dan namespace. Hal ini memungkinkan pengembang untuk menulis kode yang modular, terstruktur, dan mudah dikelola dalam skala proyek yang besar.

---

#### 2.2.18 MySQL

MySQL adalah sistem manajemen basis data relasional (RDBMS) open-source yang menggunakan Structured Query Language (SQL) untuk mengelola dan memanipulasi data (Lim & Silalahi, 2023). MySQL menjadi pilihan yang tepat karena mampu menangani aspek keamanan seperti pengaturan nama host, hak akses, dan kata sandi. Selain itu, MySQL memiliki performa tinggi dalam menangani operasi baca dan tulis data dalam jumlah besar (Jubilee Enterprise, 2018 dalam Lim & Silalahi, 2023).

MySQL mendukung berbagai fitur penting untuk pengembangan aplikasi modern, seperti transaction dan ACID compliance untuk menjaga integritas data, indexing untuk mempercepat proses pencarian, foreign key constraints untuk konsistensi relasi antar tabel, stored procedures dan triggers untuk logika bisnis di level database, serta replication dan backup untuk keamanan data.

---

#### 2.2.19 Arsitektur Model-View-Controller (MVC)

Model-View-Controller (MVC) adalah pola arsitektur perangkat lunak yang membagi struktur aplikasi menjadi tiga komponen utama yang saling terpisah namun saling terhubung (Rosa & Shalahuddin, 2018). Pemisahan ini bertujuan untuk meningkatkan modularitas, memudahkan pengembangan secara paralel, dan mempermudah pemeliharaan kode di masa mendatang.

Komponen **Model** bertanggung jawab terhadap pengelolaan data dan logika bisnis aplikasi, berinteraksi langsung dengan database untuk melakukan operasi seperti penyimpanan, pengambilan, pembaruan, dan penghapusan data. Pada framework Laravel, model diimplementasikan menggunakan Eloquent ORM yang memetakan setiap tabel database menjadi sebuah class PHP. Komponen **View** bertanggung jawab terhadap tampilan antarmuka pengguna, menerima data dari controller dan menyajikannya dalam format yang dapat dibaca melalui browser—pada Laravel dikelola menggunakan Blade Template Engine. Sedangkan **Controller** berperan sebagai perantara yang menerima input dari pengguna melalui request HTTP, memproses logika bisnis dengan memanggil model yang sesuai, dan mengembalikan respons dalam bentuk view atau data.

Arsitektur MVC memberikan separation of concerns yang jelas sehingga setiap komponen dapat dikembangkan dan diuji secara independen, memudahkan pengembangan secara tim karena masing-masing pengembang dapat bekerja pada komponen berbeda tanpa saling mengganggu, serta mempermudah pemeliharaan dan pengembangan fitur baru tanpa harus mengubah keseluruhan sistem.

---

#### 2.2.20 Pemrograman Berorientasi Objek (PBO)

Pemrograman Berorientasi Objek (PBO) atau Object-Oriented Programming (OOP) adalah paradigma pemrograman yang mengorganisasikan perangkat lunak ke dalam objek-objek yang terdiri atas data (atribut) dan perilaku (method) (Rosa & Shalahuddin, 2018). PBO menjadi paradigma yang dominan dalam pengembangan perangkat lunak modern karena mampu merepresentasikan entitas dunia nyata ke dalam struktur kode yang lebih mudah dipahami dan dikelola.

PBO memiliki empat pilar utama. **Encapsulation (Enkapsulasi)** adalah konsep penyembunyian detail implementasi internal suatu objek dari dunia luar, sehingga objek hanya dapat diakses melalui antarmuka yang telah ditentukan dan melindungi data dari modifikasi yang tidak diinginkan. **Inheritance (Pewarisan)** adalah mekanisme yang memungkinkan suatu class untuk mewarisi atribut dan method dari class lain, sehingga mengurangi duplikasi kode dan mendukung penggunaan ulang komponen. **Polymorphism (Polimorfisme)** adalah kemampuan suatu objek untuk mengambil banyak bentuk, di mana method yang sama dapat memiliki perilaku yang berbeda tergantung pada class yang mengimplementasikannya. **Abstraction (Abstraksi)** adalah proses penyederhanaan kompleksitas dengan menampilkan hanya informasi yang relevan dan menyembunyikan detail yang tidak perlu, dapat diimplementasikan melalui abstract class atau interface.

Pada bahasa PHP dan framework Laravel, konsep PBO diterapkan secara luas—setiap model Eloquent merupakan class yang mewarisi class Model dasar, controller merupakan class yang menangani request HTTP, dan middleware merupakan class yang memproses request sebelum mencapai controller.

---

#### 2.2.21 Blade Template Engine

Blade adalah template engine bawaan Laravel yang menyediakan sintaks sederhana namun powerful untuk membuat tampilan antarmuka pengguna (Laravel Documentation, 2023). Blade menggunakan sintaks khusus seperti `{{ }}` untuk menampilkan data dan `@directive` untuk struktur kontrol. Blade mendukung template inheritance sehingga layout dapat digunakan ulang, menyediakan komponen dan slot untuk membuat elemen antarmuka yang reusable, serta melakukan auto-escaping pada output untuk mencegah serangan XSS (Cross-Site Scripting). Blade juga terintegrasi langsung dengan framework Laravel tanpa konfigurasi tambahan, serta mendukung directive kondisional seperti `@if`, `@foreach`, `@switch`, dan custom directive yang memungkinkan pengembang mendefinisikan sintaks template sesuai kebutuhan proyek.

---

#### 2.2.22 Livewire

Livewire adalah framework full-stack untuk Laravel yang memungkinkan pembuatan antarmuka dinamis tanpa perlu menulis JavaScript secara langsung (Livewire Documentation, 2023). Livewire menggunakan pendekatan server-side rendering dengan pembaruan real-time melalui AJAX, sehingga pengembang dapat membangun komponen interaktif menggunakan PHP. Beberapa keunggulan Livewire antara lain:
1. Mendukung pembaruan data secara real-time tanpa perlu reload halaman.
2. Menyediakan data binding dua arah antara form dan server.
3. Mendukung mekanisme polling untuk auto-refresh tampilan secara berkala.
4. Tidak memerlukan API backend terpisah karena berjalan di atas Laravel.

Livewire sangat cocok untuk membangun komponen interaktif pada dashboard seperti notifikasi, pembaruan status, dan tabel data yang perlu di-refresh secara berkala tanpa memerlukan pengetahuan mendalam tentang JavaScript atau framework frontend seperti React atau Vue.

---

#### 2.2.23 Tailwind CSS

Tailwind CSS adalah framework CSS berbasis utility-first yang menyediakan kelas-kelas atomik untuk styling tampilan web (Tailwind Documentation, 2023). Berbeda dengan framework CSS lainnya yang menyediakan komponen siap pakai, Tailwind memberikan building blocks berupa kelas utilitas yang dapat dikombinasikan untuk membuat desain kustom. Beberapa keunggulan Tailwind CSS antara lain:
1. Pendekatan utility-first memungkinkan styling langsung pada elemen HTML tanpa perlu menulis file CSS terpisah.
2. Mendukung desain responsif dengan breakpoint bawaan untuk berbagai ukuran layar.
3. File CSS akhir hanya berisi kelas yang digunakan (tree-shaking) sehingga ukuran file menjadi kecil.
4. Mudah dikustomisasi melalui file konfigurasi `tailwind.config.js`.

---

#### 2.2.24 Laragon

Laragon adalah lingkungan pengembangan web lokal (local development environment) yang ringan, cepat, dan mudah digunakan untuk mengembangkan aplikasi berbasis PHP di sistem operasi Windows (Laragon, 2023). Laragon menyediakan stack lengkap yang mencakup web server (Apache atau Nginx), database (MySQL atau MariaDB), PHP, dan Node.js dalam satu paket terintegrasi.

Beberapa keunggulan Laragon sebagai lingkungan pengembangan antara lain:
1. Proses instalasi dan konfigurasi yang sangat mudah dengan sistem auto-detect.
2. Mendukung multiple versi PHP sehingga pengembang dapat beralih antar versi dengan mudah.
3. Fitur Pretty URLs yang secara otomatis membuat virtual host untuk setiap proyek.
4. Terminal bawaan yang sudah dilengkapi dengan Git, Composer, dan Node.js.
5. Ringan dan cepat karena tidak menggunakan virtual machine seperti Vagrant atau Docker.
6. Mendukung auto-create database saat membuat proyek baru.

Laragon menjadi pilihan populer bagi pengembang PHP karena menyediakan seluruh komponen yang dibutuhkan untuk mengembangkan aplikasi Laravel tanpa perlu menginstal dan mengkonfigurasi setiap komponen secara terpisah.

---

#### 2.2.25 Visual Studio Code

Visual Studio Code (VS Code) adalah code editor yang dikembangkan oleh Microsoft, bersifat open-source, ringan, dan mendukung berbagai bahasa pemrograman (Microsoft, 2023). VS Code telah menjadi salah satu code editor paling populer di kalangan pengembang perangkat lunak karena fleksibilitas dan ekosistem ekstensi yang sangat kaya.

Beberapa keunggulan VS Code antara lain:
1. IntelliSense yang menyediakan auto-completion, parameter hints, dan definisi hover untuk mempercepat penulisan kode.
2. Integrated terminal yang memungkinkan pengembang menjalankan perintah CLI tanpa keluar dari editor.
3. Mendukung kontrol versi (Git) secara bawaan sehingga pengembang dapat melakukan commit, pull, dan push langsung dari editor.
4. Sistem ekstensi yang kaya, termasuk ekstensi untuk PHP, Laravel, Tailwind CSS, dan Livewire yang membantu proses pengembangan.
5. Fitur debugging bawaan yang mendukung breakpoint, call stack, dan variabel watch.
6. Ringan dan cepat meskipun memiliki banyak fitur layaknya Integrated Development Environment (IDE) penuh.

---

#### 2.2.26 Database

Database atau basis data adalah kumpulan data yang terorganisir secara sistematis dan disimpan dalam media penyimpanan elektronik sehingga dapat diakses, dikelola, dan diperbarui secara efisien (Rosa & Shalahuddin, 2018). Dalam pengembangan aplikasi web modern, database menjadi komponen fundamental yang menyimpan seluruh informasi yang dibutuhkan oleh aplikasi.

Database relasional (Relational Database Management System/RDBMS) merupakan jenis database yang paling banyak digunakan, di mana data disimpan dalam bentuk tabel-tabel yang saling berelasi (Lim & Silalahi, 2023). Konsep utama database relasional meliputi:
1. **Tabel** merupakan struktur dasar penyimpanan data yang terdiri atas baris (record) dan kolom (field).
2. **Primary Key** merupakan kolom unik yang mengidentifikasi setiap baris dalam tabel secara unik.
3. **Foreign Key** merupakan kolom yang merujuk ke primary key di tabel lain, membentuk relasi antar tabel.
4. **Index** merupakan struktur data tambahan yang mempercepat pencarian data dalam tabel yang berukuran besar.
5. **Normalisasi** merupakan proses perancangan struktur tabel untuk mengurangi redundansi data dan menjaga integritas data.

Perancangan database yang baik sangat menentukan performa, skalabilitas, dan keandalan suatu aplikasi. Oleh karena itu, tahap perancangan database perlu dilakukan dengan cermat, mulai dari identifikasi entitas, penentuan atribut, hingga penetapan relasi antar tabel.

---

#### 2.2.27 Google AI Studio

Google AI Studio adalah platform pengembangan AI dari Google yang menyediakan akses ke berbagai model Large Language Model melalui REST API (Google AI Documentation, 2024). Platform ini memungkinkan pengembang untuk mengintegrasikan kemampuan AI ke dalam aplikasi melalui API key, dengan fitur seperti:
1. Menyediakan berbagai model AI dengan karakteristik yang berbeda, mulai dari model ringan untuk tugas sederhana hingga model besar untuk tugas kompleks.
2. Menyediakan kuota gratis yang memadai untuk pengembangan dan pengujian.
3. Menyediakan dashboard untuk monitoring penggunaan API.
4. Mendukung berbagai format output termasuk JSON terstruktur yang memudahkan integrasi dengan sistem backend.

Google AI Studio mendukung format prompt yang fleksibel, termasuk system prompt untuk mengatur perilaku model dan user prompt untuk memberikan instruksi spesifik, sehingga pengembang dapat mengkustomisasi respons model sesuai dengan kebutuhan aplikasi.

---

#### 2.2.28 Gemma 4 31B IT

Gemma 4 31B IT adalah Large Language Model open-weight dari Google dengan 31 miliar parameter, merupakan generasi ke-4 dari keluarga Gemma yang dirancang untuk tugas instruction-following dengan dukungan bahasa non-Inggris termasuk bahasa Indonesia (Google Research, 2026). Model ini dilatih pada dataset multilingual yang luas sehingga mampu memahami konteks bahasa dan dialek lokal.

Beberapa keunggulan Gemma 4 31B IT antara lain:
1. Memiliki 31 miliar parameter sehingga mampu memahami konteks kalimat yang kompleks.
2. Mendukung bahasa Indonesia dan variasi dialek lokal.
3. Tersedia pada kuota gratis Google AI Studio dengan kapasitas tokens per menit yang tidak terbatas.
4. Merupakan model open-weight yang memungkinkan transparansi dan reproduktibilitas.

Model ini tergolong dalam kategori instruction-tuned, artinya sudah dilatih khusus untuk mengikuti instruksi yang diberikan dalam bentuk prompt, sehingga sangat cocok untuk tugas-tugas seperti klasifikasi teks, ekstraksi informasi, dan analisis konten yang memerlukan pemahaman mendalam terhadap bahasa Indonesia.

---

#### 2.2.29 Playwright

Playwright adalah framework automation browser yang dikembangkan oleh Microsoft untuk mendukung pengujian dan otomatisasi interaksi pada aplikasi web (Microsoft Documentation, 2023). Playwright mendukung berbagai browser seperti Chromium, Firefox, dan WebKit dengan satu API yang konsisten. Beberapa keunggulan Playwright antara lain:
1. Mendukung mode headless dan headful untuk fleksibilitas penggunaan.
2. Memiliki mekanisme auto-wait yang mengurangi kegagalan akibat elemen belum siap.
3. Mendukung penyimpanan sesi login sehingga tidak perlu login ulang setiap kali dijalankan.
4. Mampu melakukan intersepsi jaringan untuk optimasi proses.

Playwright sering digunakan untuk web scraping, yaitu proses pengambilan data dari halaman web secara otomatis. Berbeda dengan HTTP request biasa, Playwright menjalankan browser sesungguhnya sehingga mampu menangani halaman yang memuat konten secara dinamis melalui JavaScript.

---

#### 2.2.30 Node.js

Node.js adalah runtime environment JavaScript yang berjalan di sisi server, dibangun di atas V8 JavaScript engine dari Google Chrome (Node.js Foundation, 2023). Node.js memungkinkan eksekusi kode JavaScript di luar browser dengan arsitektur yang bersifat asynchronous dan non-blocking I/O. Beberapa keunggulan Node.js antara lain:
1. Arsitektur asynchronous yang efisien untuk tugas-tugas I/O intensive seperti pengambilan data dari web.
2. Memiliki ekosistem package manager (NPM) terbesar di dunia dengan jutaan paket yang tersedia.
3. Mendukung pengembangan full-stack dengan satu bahasa pemrograman.
4. Ringan dan cepat dalam menangani operasi jaringan dan file system.

Node.js sering digunakan sebagai runtime untuk menjalankan tools seperti Playwright, Puppeteer, atau scraping tools lainnya yang memerlukan eksekusi JavaScript di sisi server.

---

#### 2.2.31 Vite

Vite adalah build tool frontend modern yang dikembangkan oleh Evan You untuk mempercepat proses pengembangan aplikasi web (Vite Documentation, 2023). Vite menyediakan dev server yang sangat cepat dan proses bundling yang optimal untuk produksi. Beberapa keunggulan Vite antara lain:
1. Server pengembangan yang dapat berjalan dalam hitungan milidetik berkat penggunaan ES modules.
2. Mendukung Hot Module Replacement (HMR) untuk pembaruan tampilan secara instan saat kode diubah.
3. Menghasilkan bundle produksi yang optimal dengan ukuran file yang kecil menggunakan Rollup.
4. Terintegrasi dengan berbagai framework termasuk Laravel melalui plugin resmi.

Vite menggantikan Laravel Mix yang sebelumnya menjadi build tool standar Laravel, menawarkan kecepatan build yang jauh lebih cepat terutama pada proyek dengan banyak aset frontend.

---

#### 2.2.32 Role-Based Access Control (RBAC)

Role-Based Access Control (RBAC) adalah model keamanan yang mengatur hak akses pengguna terhadap sumber daya sistem berdasarkan peran yang dimiliki, sehingga setiap pengguna hanya dapat mengakses fungsi dan data sesuai dengan tanggung jawabnya (Hidayati & Rochimah, 2019). RBAC terdiri dari tiga komponen utama yaitu pengguna, peran, dan hak akses—setiap pengguna diberikan satu atau lebih peran, dan setiap peran memiliki sekumpulan hak akses tertentu.

Dalam sistem pengelolaan aduan KMC, RBAC memastikan bahwa admin dapat melihat dan mengelola semua aduan, petugas OPD hanya dapat melihat aduan yang ditugaskan ke instansinya, dan operator hanya memiliki akses terbatas untuk input data tanpa hak menghapus. Model ini memudahkan pengelolaan hak akses karena cukup mengubah peran pengguna ketika ada perubahan tanggung jawab, mendukung prinsip least privilege di mana setiap pengguna hanya mendapatkan hak akses minimum yang dibutuhkan, serta mudah diaudit karena setiap aksi dapat dilacak berdasarkan peran yang dimiliki.

---

#### 2.2.33 Black Box Testing

Black Box Testing adalah metode pengujian perangkat lunak yang berfokus pada fungsionalitas sistem tanpa mengetahui struktur internal atau kode program yang diuji (Mustaqbal et al., 2015). Pengujian ini berfokus pada input dan output sistem, yaitu memastikan bahwa fungsi-fungsi yang tersedia berjalan sesuai dengan kebutuhan yang telah ditentukan. Black Box Testing tidak memerlukan pengetahuan tentang kode program sehingga dapat dilakukan oleh penguji yang bukan programmer, dilakukan dari sudut pandang pengguna akhir sehingga lebih relevan dengan pengalaman pengguna nyata, serta efektif untuk menemukan kesalahan pada fungsi, antarmuka, dan validasi data dari berbagai tipe input termasuk input yang valid, tidak valid, dan ekstrem.

Salah satu teknik dalam Black Box Testing adalah Boundary Value Analysis (BVA), yaitu pengujian yang difokuskan pada nilai-nilai batas dari rentang input yang valid. Teknik ini efektif karena sebagian besar kesalahan pada perangkat lunak sering terjadi pada nilai-nilai batas, bukan pada nilai tengah.

---

#### 2.2.34 Unified Modelling Language (UML)

Unified Modelling Language (UML) adalah bahasa pemodelan visual yang digunakan untuk menspesifikasikan, merancang, dan mendokumentasikan sistem perangkat lunak (Rosa & Shalahuddin, 2018). UML menyediakan berbagai jenis diagram yang dapat digunakan untuk memodelkan sistem dari berbagai sudut pandang, baik struktural maupun perilaku. Penggunaan UML dalam pengembangan sistem membantu pengembang dan pemangku kepentingan untuk memiliki pemahaman yang sama mengenai desain sistem sebelum tahap implementasi dimulai.

UML terdiri dari dua kelompok diagram utama: **Diagram Struktural** yang menggambarkan struktur statis sistem seperti Class Diagram, Component Diagram, dan Deployment Diagram, serta **Diagram Perilaku** yang menggambarkan perilaku dinamis sistem seperti Use Case Diagram, Activity Diagram, dan Sequence Diagram.

---

#### 2.2.35 Use Case Diagram

Use Case Diagram adalah diagram yang menggambarkan interaksi antara aktor dengan sistem serta fungsionalitas yang disediakan, menunjukkan bagaimana pengguna berinteraksi dengan sistem untuk mencapai tujuan tertentu (Satzinger et al., 2019). Use case diagram menunjukkan hubungan antara aktor dan use case dalam suatu sistem. Terdapat dua komponen utama dalam use case diagram: **Use Case** merupakan fungsionalitas yang disediakan sistem sebagai unit-unit yang saling bertukar pesan antar unit atau aktor, dan **Aktor** merupakan orang, proses, atau sistem lain yang berinteraksi dengan sistem informasi yang akan dibuat di luar sistem informasi itu sendiri.

Adapun simbol-simbol use case diagram dapat dilihat pada Tabel 2.1.

**Tabel 2.1 Simbol Use Case Diagram**

 No | Simbol | Nama | Keterangan |
----|--------|------|------------|
 1 | (oval) | Use Case | Deskripsi dari urutan aksi-aksi yang ditampilkan sistem yang menghasilkan suatu hasil yang terukur bagi suatu aktor |
 2 | (stick figure) | Aktor | Menspesifikasikan himpunan peran yang pengguna mainkan ketika berinteraksi dengan use case |
 3 | (garis lurus) | Association | Menghubungkan antara aktor dengan use case |
 4 | (panah segitiga) | Generalisasi | Menunjukkan spesialisasi aktor untuk dapat berpartisipasi dengan use case |
 5 | (panah putus-putus «extend») | Extend | Menunjukkan bahwa suatu use case merupakan tambahan fungsional dari use case lainnya jika suatu kondisi terpenuhi |
 6 | (panah putus-putus «include») | Include | Menunjukkan bahwa suatu use case selalu memerlukan use case lainnya untuk menjalankan fungsinya |

Sumber: Waruwu (2019)

Setiap use case dalam diagram dijelaskan lebih rinci melalui use case scenario, yaitu deskripsi langkah-langkah interaksi antara aktor dan sistem dalam menjalankan suatu use case. Use case scenario memuat informasi seperti nama use case, aktor yang terlibat, deskripsi singkat, pre-condition, post-condition, serta alur utama dan alur alternatif dari interaksi tersebut (Nugroho, 2019).

---

#### 2.2.36 Activity Diagram

Activity Diagram merupakan diagram yang menggambarkan alur kerja (workflow) atau aktivitas dari sebuah sistem atau proses bisnis (Harianto dkk., 2019). Activity diagram menunjukkan urutan aktivitas dalam suatu proses, termasuk aktivitas yang dilakukan secara berurutan maupun paralel, serta keputusan (decision) yang mungkin terjadi dalam alur tersebut. Activity diagram berguna untuk memodelkan proses bisnis yang kompleks dan membantu memahami alur logika suatu fitur sebelum diimplementasikan dalam kode program.

Adapun simbol-simbol activity diagram dapat dilihat pada Tabel 2.2.

**Tabel 2.2 Simbol Activity Diagram**

 No | Simbol | Nama | Keterangan |
----|--------|------|------------|
 1 | (persegi panjang bulat) | Activity | Aktivitas yang dilakukan sistem, menunjukkan cara setiap kelas antarmuka berinteraksi satu sama lain |
 2 | (lingkaran hitam penuh) | Initial Node | Status awal dari diagram aktivitas |
 3 | (lingkaran hitam dalam lingkaran) | Activity Final Node | Status akhir dari diagram aktivitas |
 4 | (garis horizontal tebal) | Fork/Join | Penggabungan atau pemisahan beberapa aktivitas pada tahap tertentu |
 5 | (diamond/belah ketupat) | Decision | Percabangan di mana terdapat beberapa opsi aktivitas yang dapat dipilih |
 6 | (garis vertikal pembatas) | Swimlane | Memisahkan organisasi atau aktor yang bertanggung jawab terhadap aktivitas yang terjadi |

Sumber: Hidayat & Saputra (2020)

---

#### 2.2.37 Sequence Diagram

Sequence Diagram adalah diagram yang menggambarkan interaksi antar objek dalam suatu sistem berdasarkan urutan waktu (Herlinah & Musliadi, 2019). Sequence diagram menunjukkan bagaimana pesan dikirim dan diterima antar objek untuk melaksanakan suatu skenario tertentu. Diagram ini berguna untuk memahami alur komunikasi antara komponen-komponen sistem, mulai dari antarmuka pengguna (boundary), logika bisnis (controller), hingga penyimpanan data (entity).

Adapun simbol-simbol sequence diagram dapat dilihat pada Tabel 2.3.

**Tabel 2.3 Simbol Sequence Diagram**

 No | Simbol | Nama | Keterangan |
----|--------|------|------------|
 1 | (stick figure) | Aktor | Orang atau proses yang berinteraksi dengan sistem informasi di luar sistem |
 2 | (garis vertikal putus-putus) | Lifeline | Menyatakan kehidupan suatu objek selama interaksi berlangsung |
 3 | (persegi panjang) | Object | Menyatakan objek yang berinteraksi pesan |
 4 | (panah penuh →) | Synchronous Message | Pesan yang memerlukan respons sebelum pengirim melanjutkan proses |
 5 | (panah putus-putus ←) | Return Message | Menyatakan bahwa objek menghasilkan suatu kembalian ke objek pemanggil |
 6 | (persegi panjang tipis di lifeline) | Activation | Menunjukkan periode waktu di mana objek sedang aktif menjalankan operasi |

Sumber: Ramadhani & Rahmawati (2020)

---

#### 2.2.38 Class Diagram

Class Diagram adalah diagram yang menggambarkan struktur statis dari sistem dengan menunjukkan kelas-kelas yang ada, atribut dan metode yang dimiliki oleh masing-masing kelas, serta hubungan antar kelas tersebut (Puspitasari & Setyawan, 2020). Class diagram merupakan salah satu diagram UML yang paling penting dalam pemodelan berorientasi objek karena menjadi dasar bagi implementasi kode program. Perancangan class diagram yang baik memastikan bahwa struktur kode program mencerminkan arsitektur sistem yang telah direncanakan, sehingga memudahkan proses pengembangan dan pemeliharaan.

Adapun simbol-simbol class diagram dapat dilihat pada Tabel 2.4.

**Tabel 2.4 Simbol Class Diagram**

 No | Simbol | Nama | Keterangan |
----|--------|------|------------|
 1 | (persegi panjang 3 bagian) | Class | Representasi dari kelas yang memiliki nama, atribut, dan metode |
 2 | (garis lurus) | Association | Hubungan antar kelas yang menunjukkan interaksi atau ketergantungan |
 3 | (panah segitiga kosong) | Generalization | Hubungan pewarisan (inheritance) dari kelas anak ke kelas induk |
 4 | (diamond kosong) | Aggregation | Hubungan "has-a" di mana bagian bisa berdiri sendiri tanpa keseluruhan |
 5 | (diamond penuh) | Composition | Hubungan "has-a" di mana bagian tidak bisa berdiri sendiri tanpa keseluruhan |
 6 | (angka di ujung garis) | Multiplicity | Menunjukkan jumlah relasi antar kelas (1, 0..*, 1..*) |

Sumber: Puspitasari & Setyawan (2020)

---

#### 2.2.39 Flowchart

Flowchart adalah representasi grafis dari langkah-langkah dan urutan prosedur dalam sebuah sistem atau proses, yang menggambarkan alur logika secara visual menggunakan simbol-simbol standar (Sutanti et al., 2020). Flowchart membantu analis dan programmer untuk memecah permasalahan menjadi bagian-bagian yang lebih kecil sehingga lebih mudah dianalisis dan dipahami. Dalam pengembangan sistem informasi, flowchart digunakan pada tahap perancangan untuk memodelkan alur proses bisnis dan alur sistem sebelum diimplementasikan dalam kode program.

Penggunaan flowchart memberikan beberapa keuntungan dalam perancangan sistem:
1. Mempermudah komunikasi antara pengembang dan pemangku kepentingan karena menggunakan representasi visual yang mudah dipahami.
2. Membantu mengidentifikasi redundansi atau kesalahan logika dalam alur proses sebelum implementasi.
3. Menjadi dokumentasi yang berguna untuk pemeliharaan sistem di masa mendatang.
4. Memudahkan evaluasi dan optimalisasi proses dengan melihat gambaran keseluruhan alur kerja sistem.

Adapun simbol-simbol yang digunakan dalam flowchart dapat dilihat pada Tabel 2.5.

**Tabel 2.5 Simbol Flowchart**

 No | Simbol | Nama | Keterangan |
----|--------|------|------------|
 1 | (oval) | Terminator | Menunjukkan awal (start) atau akhir (end) dari suatu proses |
 2 | (persegi panjang) | Process | Menunjukkan proses pengolahan atau operasi yang dilakukan oleh sistem |
 3 | (belah ketupat) | Decision | Menunjukkan titik keputusan berdasarkan kondisi tertentu dengan dua atau lebih pilihan |
 4 | (jajaran genjang) | Input/Output | Menyatakan proses input data atau output hasil |
 5 | (panah) | Flow Line | Menghubungkan antar simbol dan menunjukkan arah alur proses |
 6 | (lingkaran kecil) | Connector | Menghubungkan alur yang terputus dalam halaman yang sama atau berbeda |

Sumber: Sutanti et al. (2020)

---

### 2.3 Profil Tempat Pelaksanaan Tugas Akhir

Tugas Akhir ini dilaksanakan di Ketapang Media Center (KMC) yang berada di bawah naungan Dinas Komunikasi dan Informatika Kabupaten Ketapang. KMC merupakan unit yang berperan dalam mengelola informasi dan aduan masyarakat yang masuk melalui media sosial maupun kanal digital lainnya. Dalam pelaksanaan tugasnya, KMC menerima berbagai bentuk laporan masyarakat yang perlu dipantau, diklasifikasikan, dan diteruskan kepada instansi terkait sesuai dengan bidang permasalahannya.

Pada kondisi saat ini, pengelolaan aduan masih banyak dilakukan secara manual sehingga petugas perlu membuka satu per satu akun atau notifikasi yang masuk. Hal ini berpotensi memperlambat proses pencatatan, klasifikasi, dan tindak lanjut aduan, terutama ketika volume laporan yang masuk cukup tinggi dalam waktu yang bersamaan. Keterbatasan ini menjadi latar belakang dikembangkannya sistem yang lebih terstruktur dan otomatis.

Melalui Tugas Akhir ini, sistem dikembangkan sebagai solusi untuk membantu petugas KMC dalam memantau aduan dari Facebook dan Instagram, menampilkan notifikasi secara terstruktur, serta memberikan rekomendasi klasifikasi berbantuan AI. Dengan adanya sistem ini, diharapkan proses pengelolaan aduan di lingkungan KMC menjadi lebih efektif, efisien, dan mudah dipantau.

---

**END OF BAB II**
**Total sub-bab 2.2: 39 sub-bab**
**Revisi sesuai feedback dosen:**
- ✅ Hapus semua "Dalam penelitian ini..."
- ✅ Tambah 5 sub-bab: MVC (2.2.19), PBO (2.2.20), Laragon (2.2.24), VS Code (2.2.25), Database (2.2.26)
- ✅ Ganti "penelitian" → "Tugas Akhir" di 2.3
- ✅ Bahasa natural-akademis (tidak kaku)
- ✅ Tambah Flowchart (2.2.39) per analisis gap vs kakak tingkat