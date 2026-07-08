# 5 SUB-BAB BARU UNTUK BAB II
**Tanggal:** 8 Juli 2026, 16:27 WIB
**Mahasiswa:** Achmad Bagus Aprianto (3042023024)

---

## SUB-BAB 1: METODE AGILE (Insert setelah 2.2.1 Sistem Informasi)

#### 2.2.2 Metode Agile

Metode Agile adalah pendekatan pengembangan perangkat lunak yang menekankan pada fleksibilitas, iterasi cepat, dan kolaborasi dalam proses pengembangan (Pressman, 2021). Berbeda dengan metode tradisional yang kaku dan sekuensial, Agile memungkinkan pengembang untuk merespons perubahan kebutuhan dengan cepat melalui siklus pengembangan yang pendek dan berulang. Metode ini pertama kali diperkenalkan melalui Agile Manifesto pada tahun 2001 dan sejak itu menjadi standar industri dalam pengembangan software modern.

Metode Agile memiliki empat nilai inti: individu dan interaksi lebih penting daripada proses dan tools, software yang berfungsi lebih penting daripada dokumentasi yang lengkap, kolaborasi dengan pengguna lebih penting daripada negosiasi kontrak, dan merespons perubahan lebih penting daripada mengikuti rencana awal. Nilai-nilai ini memandu pengembang untuk fokus pada hasil yang dapat digunakan dan fleksibel terhadap perubahan.

Dalam pengembangan sistem Tugas Akhir ini, metode Agile diterapkan melalui empat tahapan utama:

1. **Requirements (Pengumpulan Kebutuhan)**: Tahap ini meliputi analisis kebutuhan sistem, identifikasi fitur utama seperti klasifikasi AI, scraping multi-channel, dan sistem ticketing, serta penentuan spesifikasi teknis dan fungsional.

2. **Design (Perancangan)**: Tahap perancangan meliputi pembuatan diagram UML (Use Case Diagram, Activity Diagram, Sequence Diagram, dan Class Diagram), perancangan arsitektur sistem berbasis MVC, desain struktur database relasional, dan perancangan antarmuka pengguna (UI/UX).

3. **Development (Pengembangan)**: Tahap implementasi kode menggunakan framework Laravel, integrasi AI classification berbasis Gemma 4 31B IT melalui Google AI Studio, pembangunan scraper multi-channel menggunakan Playwright untuk Facebook dan Instagram, implementasi sistem ticketing dengan SLA 24 jam, dan pengembangan dashboard monitoring real-time.

4. **Testing (Pengujian)**: Tahap pengujian menggunakan metode black box testing untuk memastikan seluruh fungsionalitas sistem berjalan sesuai kebutuhan, serta iterasi tuning untuk meningkatkan akurasi klasifikasi AI dari tahap awal hingga mencapai tingkat akurasi optimal sebesar 97.5%.

Metode Agile dipilih karena sangat cocok untuk pengembangan sistem berbasis kecerdasan buatan yang membutuhkan iterasi dan penyesuaian berkelanjutan. Proses tuning model AI, testing akurasi klasifikasi, dan penyempurnaan prompt engineering memerlukan siklus pengembangan yang fleksibel—karakteristik utama dari metode Agile. Selain itu, metode ini memungkinkan pengembangan bertahap dimana setiap fitur dapat diuji dan diperbaiki sebelum melanjutkan ke fitur berikutnya, sehingga meminimalkan risiko kegagalan sistem secara keseluruhan.

---

## SUB-BAB 2: COMPOSER (Insert setelah 2.2.17 PHP)

#### 2.2.18 Composer

Composer adalah dependency manager untuk PHP yang memudahkan pengelolaan library dan package eksternal dalam proyek aplikasi (Symfony, 2023). Dalam pengembangan aplikasi modern berbasis Laravel, Composer berperan penting untuk menginstal, memperbarui, dan mengelola berbagai package yang dibutuhkan tanpa harus mengunduh dan mengonfigurasi secara manual satu per satu. Composer menggunakan file `composer.json` untuk mendefinisikan package yang dibutuhkan beserta versinya, dan file `composer.lock` untuk memastikan konsistensi versi package di berbagai lingkungan pengembangan.

Composer bekerja dengan mengunduh package dari repository Packagist (repository utama package PHP) dan menginstalnya ke dalam folder `vendor/` pada proyek. Selain itu, Composer juga menangani dependency dari dependency (transitive dependencies) secara otomatis, sehingga pengembang tidak perlu memikirkan package tambahan yang dibutuhkan oleh package utama yang diinstal. Fitur autoloading PSR-4 yang disediakan Composer juga memudahkan pemanggilan class tanpa perlu menulis banyak `require` atau `include` secara manual.

Dalam sistem yang dikembangkan pada Tugas Akhir ini, Composer digunakan untuk mengelola berbagai package Laravel seperti framework Laravel itu sendiri, Laravel Tinker untuk debugging interaktif, OpenAI PHP Laravel untuk integrasi dengan Google AI Studio, Google API Client untuk autentikasi API, serta berbagai package lainnya yang mendukung fungsionalitas sistem. Penggunaan Composer memastikan bahwa seluruh dependency terinstal dengan versi yang kompatibel dan memudahkan proses deployment ke server production.

---

## SUB-BAB 3: AUTHENTICATION & AUTHORIZATION (Insert sebelum 2.2.32 RBAC)

#### 2.2.31 Authentication dan Authorization

Authentication dan Authorization adalah dua konsep keamanan fundamental dalam sistem informasi yang saling melengkapi (Stallings, 2019). **Authentication (autentikasi)** adalah proses memverifikasi identitas pengguna yang mencoba mengakses sistem, biasanya melalui kombinasi username dan password, token, atau metode biometrik. Sedangkan **Authorization (otorisasi)** adalah proses menentukan hak akses pengguna yang telah terautentikasi—menentukan data atau fitur apa saja yang boleh diakses oleh pengguna tersebut.

Proses authentication umumnya melibatkan tiga faktor: something you know (sesuatu yang Anda ketahui, seperti password), something you have (sesuatu yang Anda miliki, seperti token atau kartu akses), dan something you are (sesuatu yang Anda miliki secara biologis, seperti sidik jari). Dalam konteks aplikasi web, authentication paling umum menggunakan kombinasi email/username dan password yang di-hash menggunakan algoritma aman seperti bcrypt atau Argon2 untuk melindungi credential pengguna.

Authorization bekerja setelah proses authentication berhasil. Sistem akan memeriksa peran (role) atau permission yang dimiliki pengguna untuk menentukan akses yang diizinkan. Dalam aplikasi Laravel, authorization dapat diimplementasikan menggunakan middleware, gates, dan policies. Middleware authentication seperti `auth` memastikan bahwa hanya pengguna yang sudah login yang dapat mengakses route tertentu, sedangkan middleware tambahan seperti `role:admin` atau `role:opd` dapat digunakan untuk membatasi akses berdasarkan peran pengguna.

Dalam sistem yang dikembangkan pada Tugas Akhir ini, authentication diimplementasikan menggunakan custom AuthController yang menangani proses login dan logout. Sistem menggunakan session-based authentication dimana Laravel menyimpan informasi pengguna yang sudah login dalam session dan menggunakan cookie untuk melacak session tersebut. Authorization diterapkan melalui middleware custom yang memisahkan hak akses berdasarkan peran: role `admin` memiliki akses penuh ke seluruh fitur sistem termasuk manajemen pengguna dan konfigurasi sistem, role `opd` memiliki akses untuk mengelola tiket aduan yang masuk ke OPD masing-masing, dan role `public` memiliki akses terbatas hanya untuk melacak status aduan mereka.

Implementasi authentication dan authorization yang tepat sangat penting untuk menjaga keamanan data aduan masyarakat dan memastikan bahwa hanya pihak yang berwenang yang dapat mengakses dan memproses informasi sensitif dalam sistem.

---

## SUB-BAB 4: GIT & VERSION CONTROL (Insert setelah 2.2.25 Visual Studio Code)

#### 2.2.26 Git dan Version Control System

Git adalah sistem kontrol versi terdistribusi (distributed version control system) yang digunakan untuk melacak perubahan pada source code selama proses pengembangan software (Chacon & Straub, 2014). Berbeda dengan sistem kontrol versi terpusat, Git memberikan setiap pengembang salinan lengkap dari repository beserta seluruh riwayat perubahan, sehingga memungkinkan pengembangan offline dan kolaborasi yang lebih fleksibel. Git pertama kali dikembangkan oleh Linus Torvalds pada tahun 2005 untuk mengelola pengembangan kernel Linux dan sejak itu menjadi standar industri dalam version control.

Version control system (VCS) memiliki beberapa manfaat penting dalam pengembangan software. Pertama, VCS memungkinkan pengembang untuk melacak setiap perubahan kode dengan detail—siapa yang mengubah, kapan diubah, dan apa yang diubah. Kedua, VCS memfasilitasi kolaborasi tim dengan memungkinkan banyak pengembang bekerja pada bagian berbeda dari proyek secara bersamaan tanpa saling menimpa pekerjaan. Ketiga, VCS menyediakan mekanisme branching dan merging yang memungkinkan eksperimen dengan fitur baru tanpa mengganggu kode yang sudah stabil. Keempat, VCS berfungsi sebagai backup otomatis dimana setiap versi kode tersimpan dan dapat dikembalikan jika terjadi kesalahan.

Konsep utama dalam Git meliputi repository (tempat penyimpanan project dan history-nya), commit (snapshot perubahan kode pada waktu tertentu), branch (jalur pengembangan terpisah dari kode utama), merge (penggabungan perubahan dari satu branch ke branch lain), dan remote repository (salinan repository yang disimpan di server seperti GitHub atau GitLab). Workflow Git yang umum adalah: developer melakukan perubahan pada local repository, membuat commit untuk menyimpan perubahan tersebut, dan kemudian push ke remote repository untuk berbagi dengan tim atau sebagai backup.

Dalam pengembangan sistem Tugas Akhir ini, Git digunakan untuk melacak seluruh perubahan kode dari awal pengembangan hingga versi final. Setiap fitur utama seperti authentication, AI classification, scraping module, dan ticketing system dikembangkan dalam branch terpisah sebelum di-merge ke branch main setelah testing. Penggunaan Git memudahkan proses debugging dengan memungkinkan rollback ke versi sebelumnya jika ditemukan bug setelah penambahan fitur baru. Commit history yang terstruktur juga berfungsi sebagai dokumentasi timeline pengembangan yang dapat dijadikan referensi dalam penulisan laporan Tugas Akhir.

---

## SUB-BAB 5: PROMPT ENGINEERING (Insert setelah 2.2.15 LLM)

#### 2.2.16 Prompt Engineering

Prompt Engineering adalah teknik merancang dan mengoptimalkan instruksi teks (prompt) yang diberikan kepada model bahasa besar (Large Language Model/LLM) untuk mendapatkan output yang akurat dan relevan sesuai kebutuhan (White et al., 2023). Dalam konteks penggunaan LLM untuk klasifikasi teks, kualitas prompt secara langsung mempengaruhi akurasi dan konsistensi hasil klasifikasi. Prompt engineering mencakup pemilihan kata kunci yang tepat, penyusunan struktur instruksi yang jelas, pemberian contoh (few-shot examples), dan penentuan format output yang diinginkan.

Terdapat beberapa teknik prompt engineering yang umum digunakan. **Zero-shot prompting** adalah teknik memberikan instruksi tanpa contoh, mengandalkan kemampuan model untuk memahami tugas dari deskripsi saja. **Few-shot prompting** adalah teknik memberikan beberapa contoh input-output sebelum meminta model melakukan tugas yang sebenarnya, sehingga model dapat belajar pola dari contoh tersebut. **Chain-of-thought prompting** adalah teknik yang meminta model untuk menjelaskan cara berpikir atau langkah-langkah sebelum memberikan jawaban akhir, meningkatkan akurasi untuk tugas yang kompleks. **System prompt** adalah instruksi awal yang mendefinisikan peran dan batasan model, seperti "Anda adalah sistem klasifikasi aduan yang harus selalu menghasilkan output dalam format JSON."

Dalam sistem yang dikembangkan pada Tugas Akhir ini, prompt engineering diterapkan secara intensif untuk meningkatkan akurasi klasifikasi aduan. System prompt dirancang untuk mendefinisikan peran LLM sebagai sistem klasifikasi aduan KMC Ketapang dengan aturan output yang ketat (format JSON valid dengan field kategori, sub_kategori, dan opd). Teknik few-shot prompting digunakan dengan memberikan 3-5 contoh klasifikasi aduan yang benar sebagai referensi, misalnya "Air PDAM mati 3 hari" → Sub: Air Bersih, Kategori: Layanan PDAM, OPD: PDAM Ketapang.

Selain itu, prompt juga di-inject dengan domain knowledge spesifik berupa mapping 50+ sub-kategori aduan ke kategori dan OPD yang sesuai, menggunakan data nyata dari KMC Ketapang. Hal ini membantu model memahami konteks lokal seperti istilah "lampu tratak" (lampu jalan dalam dialek lokal), nama jalan di Ketapang, dan struktur OPD Kabupaten Ketapang. Context injection dengan 20 aduan terbaru juga digunakan untuk membantu model mendeteksi duplikasi—aduan baru dibandingkan dengan aduan yang baru masuk dalam rentang waktu 7 hari terakhir.

Proses iterasi dan tuning prompt dilakukan secara bertahap untuk meningkatkan akurasi dari tahap awal (sekitar 85%) hingga mencapai tingkat akurasi final sebesar 97.5%. Setiap iterasi melibatkan evaluasi hasil klasifikasi pada sample data, identifikasi pola kesalahan, dan perbaikan prompt berdasarkan error analysis. Teknik prompt engineering yang tepat terbukti sangat efektif untuk mencapai performa tinggi tanpa perlu melakukan fine-tuning model yang membutuhkan biaya komputasi dan data latihan yang besar.

---

## REFERENSI TAMBAHAN UNTUK 5 SUB-BAB BARU:

1. Pressman, R. S. (2021). *Software Engineering: A Practitioner's Approach* (9th ed.). McGraw-Hill Education.

2. Symfony. (2023). *Composer Documentation*. Retrieved from https://getcomposer.org/doc/

3. Stallings, W. (2019). *Computer Security: Principles and Practice* (4th ed.). Pearson.

4. Chacon, S., & Straub, B. (2014). *Pro Git* (2nd ed.). Apress.

5. White, J., Fu, Q., Hays, S., Sandborn, M., Olea, C., Gilbert, H., ... & Schmidt, D. C. (2023). A prompt pattern catalog to enhance prompt engineering with ChatGPT. *arXiv preprint arXiv:2302.11382*.

---

**END OF 5 NEW SUB-SECTIONS**
