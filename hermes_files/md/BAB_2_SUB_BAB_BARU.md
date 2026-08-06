# 5 SUB-BAB BARU UNTUK BAB II
**Tanggal:** 8 Juli 2026, 16:27 WIB
**Mahasiswa:** Achmad Bagus Aprianto (3042023024)

---

## SUB-BAB 1: METODE AGILE (Insert setelah 2.2.1 Sistem Informasi)

#### 2.2.2 Metode Agile

Metode Agile adalah pendekatan pengembangan perangkat lunak yang menekankan pada fleksibilitas, iterasi cepat, dan kolaborasi dalam proses pengembangan (Pressman, 2021). Berbeda dengan metode tradisional yang kaku dan sekuensial, Agile memungkinkan pengembang untuk merespons perubahan kebutuhan dengan cepat melalui siklus pengembangan yang pendek dan berulang. Metode ini pertama kali diperkenalkan melalui Agile Manifesto pada tahun 2001 dan sejak itu menjadi standar industri dalam pengembangan software modern.

Metode Agile memiliki empat nilai inti: individu dan interaksi lebih penting daripada proses dan tools, software yang berfungsi lebih penting daripada dokumentasi yang lengkap, kolaborasi dengan pengguna lebih penting daripada negosiasi kontrak, dan merespons perubahan lebih penting daripada mengikuti rencana awal. Nilai-nilai ini memandu pengembang untuk fokus pada hasil yang dapat digunakan dan fleksibel terhadap perubahan.

Secara umum, penerapan metode Agile melibatkan empat tahapan utama: pengumpulan kebutuhan (requirements), perancangan (design), pengembangan (development), dan pengujian (testing). Setiap tahapan dapat diiterasi sesuai kebutuhan untuk meningkatkan kualitas hasil akhir. Metode ini sangat cocok untuk pengembangan sistem berbasis kecerdasan buatan yang membutuhkan iterasi dan penyesuaian berkelanjutan, karena proses tuning model AI dan testing akurasi memerlukan siklus pengembangan yang fleksibel. Selain itu, metode ini memungkinkan pengembangan bertahap dimana setiap fitur dapat diuji dan diperbaiki sebelum melanjutkan ke fitur berikutnya, sehingga meminimalkan risiko kegagalan sistem secara keseluruhan.

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

Authorization bekerja setelah proses authentication berhasil. Sistem akan memeriksa peran (role) atau permission yang dimiliki pengguna untuk menentukan akses yang diizinkan. Dalam framework web modern seperti Laravel, authorization dapat diimplementasikan menggunakan middleware, gates, dan policies—middleware memastikan hanya pengguna terautentikasi yang dapat mengakses resource tertentu, sementara gates dan policies mengatur aturan akses yang lebih granular berdasarkan peran atau kondisi spesifik. Implementasi yang tepat dari kedua konsep ini sangat penting untuk menjaga keamanan sistem dan memastikan bahwa data sensitif hanya dapat diakses oleh pihak yang berwenang.

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

Terdapat beberapa teknik prompt engineering yang umum digunakan. **Zero-shot prompting** adalah teknik memberikan instruksi tanpa contoh, mengandalkan kemampuan model untuk memahami tugas dari deskripsi saja. **Few-shot prompting** adalah teknik memberikan beberapa contoh input-output sebelum meminta model melakukan tugas yang sebenarnya, sehingga model dapat belajar pola dari contoh tersebut. **Chain-of-thought prompting** adalah teknik yang meminta model untuk menjelaskan cara berpikir atau langkah-langkah sebelum memberikan jawaban akhir, meningkatkan akurasi untuk tugas yang kompleks. **System prompt** adalah instruksi awal yang mendefinisikan peran dan batasan model, seperti "Anda adalah sistem klasifikasi yang harus menghasilkan output dalam format JSON."

Dalam penerapan untuk sistem klasifikasi teks, prompt engineering dapat ditingkatkan melalui beberapa pendekatan. Pertama, pemberian contoh konkret (few-shot examples) yang menunjukkan pola klasifikasi yang benar membantu model memahami ekspektasi output. Kedua, injeksi pengetahuan domain (domain knowledge injection) berupa daftar kategori, sub-kategori, dan aturan klasifikasi dapat meningkatkan akurasi terutama untuk konteks yang spesifik. Ketiga, penyediaan konteks tambahan seperti data historis atau informasi terkait dapat membantu model membuat keputusan yang lebih baik, misalnya dalam mendeteksi duplikasi dengan membandingkan teks baru terhadap data sebelumnya.

Proses pengembangan prompt yang efektif umumnya bersifat iteratif, melibatkan evaluasi hasil pada data uji, identifikasi pola kesalahan, dan perbaikan prompt berdasarkan analisis error. Pendekatan prompt engineering yang tepat dapat mencapai performa tinggi tanpa perlu melakukan fine-tuning model yang membutuhkan biaya komputasi dan data latihan yang besar, menjadikannya solusi yang praktis dan efisien untuk berbagai aplikasi klasifikasi teks.

---

## REFERENSI TAMBAHAN UNTUK 5 SUB-BAB BARU:

1. Pressman, R. S. (2021). *Software Engineering: A Practitioner's Approach* (9th ed.). McGraw-Hill Education.

2. Symfony. (2023). *Composer Documentation*. Retrieved from https://getcomposer.org/doc/

3. Stallings, W. (2019). *Computer Security: Principles and Practice* (4th ed.). Pearson.

4. Chacon, S., & Straub, B. (2014). *Pro Git* (2nd ed.). Apress.

5. White, J., Fu, Q., Hays, S., Sandborn, M., Olea, C., Gilbert, H., ... & Schmidt, D. C. (2023). A prompt pattern catalog to enhance prompt engineering with ChatGPT. *arXiv preprint arXiv:2302.11382*.

---

**END OF 5 NEW SUB-SECTIONS**

---

## SUB-BAB: API (Application Programming Interface)

#### 2.2.XX API (Application Programming Interface)

API (Application Programming Interface) adalah antarmuka yang memungkinkan dua aplikasi atau sistem untuk berkomunikasi dan bertukar data secara terprogram (Satzinger et al., 2019). API menyediakan seperangkat aturan dan protokol yang menentukan bagaimana permintaan data harus dibuat dan bagaimana respons akan dikembalikan, sehingga memungkinkan integrasi antar sistem tanpa perlu mengetahui detail implementasi internal masing-masing sistem.

REST API (Representational State Transfer) adalah gaya arsitektur API yang paling umum digunakan, di mana komunikasi dilakukan melalui protokol HTTP dengan metode standar seperti GET (mengambil data), POST (mengirim data baru), PUT (memperbarui data), dan DELETE (menghapus data). REST API menggunakan format data yang ringan seperti JSON atau XML untuk pertukaran informasi, membuatnya mudah dibaca baik oleh mesin maupun manusia.

Dalam konteks integrasi dengan layanan eksternal, API berperan sebagai jembatan komunikasi yang memungkinkan aplikasi lokal mengakses fungsionalitas dari layanan cloud seperti model kecerdasan buatan, layanan penyimpanan, atau sistem pihak ketiga lainnya. Komunikasi melalui API umumnya menggunakan mekanisme autentikasi seperti API key atau token yang disimpan secara aman di file konfigurasi, memastikan hanya aplikasi yang terotorisasi yang dapat mengakses layanan tersebut.

---

