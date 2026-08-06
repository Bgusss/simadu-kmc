# BAB III
# METODOLOGI PENELITIAN DAN PERANCANGAN SISTEM

## 3.1 Metodologi Penelitian

### 3.1.1 Model Pengembangan Sistem

Dalam pengembangan Sistem Informasi Manajemen Aduan Multi Channel KMC, model pengembangan yang digunakan adalah Agile. Agile dipilih karena sifat pengembangannya yang fleksibel dan memungkinkan penyesuaian pada setiap tahap berdasarkan hasil pengujian. Hal ini sesuai dengan kebutuhan sistem yang mengintegrasikan layanan AI, di mana hasil klasifikasi dan aturan bisnis seperti batas waktu SLA perlu diuji dan disesuaikan secara bertahap.

Penerapan Agile pada penelitian ini disederhanakan menjadi empat tahap, yaitu *Requirement*, *Design*, *Development*, dan *Testing*. Keempat tahap tersebut bersifat iteratif, artinya apabila pengujian menemukan kekurangan, proses dapat kembali ke tahap sebelumnya untuk diperbaiki dan diuji ulang. Penjelasan rinci setiap tahap diuraikan pada sub-bagian 3.1.3.

### 3.1.2 Alat dan Bahan

Alat dan bahan digunakan untuk mendukung kegiatan perancangan, pengembangan, pengujian, serta penyusunan laporan. Alat yang digunakan meliputi perangkat keras dan perangkat lunak, sedangkan bahan berupa data yang diperlukan dalam pengembangan sistem dan media cetak laporan. Rincian alat dan bahan ditunjukkan pada Tabel 3.1.

**Tabel 3.1 Alat dan Bahan**

<table border="1" style="border-collapse: collapse; width: 100%;">
  <thead>
    <tr>
      <th style="text-align: center; width: 8%;">No</th>
      <th style="text-align: center; width: 12%;">Jenis</th>
      <th style="text-align: center; width: 30%;">Produk</th>
      <th style="text-align: center; width: 50%;">Keterangan</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td rowspan="14" style="text-align: center; vertical-align: middle;">1</td>
      <td rowspan="14" style="text-align: center; vertical-align: middle;">Alat</td>
      <td>1. Laptop Lenovo ThinkPad T480s</td>
      <td>Perangkat utama untuk perancangan, pengembangan, dan pengujian sistem dengan prosesor Intel Core i5-8350U, RAM 16 GB, SSD 512 GB, serta Windows 10 Pro 64-bit.</td>
    </tr>
    <tr><td>2. Router</td><td>Menghubungkan perangkat pengembangan dengan jaringan internet untuk akses layanan AI, media sosial, dan server.</td></tr>
    <tr><td>3. Visual Studio Code</td><td>Editor kode untuk menulis dan mengelola kode PHP, Blade, JavaScript, serta berkas konfigurasi proyek.</td></tr>
    <tr><td>4. Microsoft Word</td><td>Digunakan untuk menyusun laporan Tugas Akhir dan dokumen pendukung penelitian.</td></tr>
    <tr><td>5. Laragon</td><td>Lingkungan pengembangan lokal untuk menjalankan Apache, PHP, MySQL, dan PhpMyAdmin.</td></tr>
    <tr><td>6. Figma</td><td>Digunakan untuk merancang rancangan antarmuka pengguna sebelum implementasi.</td></tr>
    <tr><td>7. Draw.io</td><td>Digunakan untuk membuat diagram arsitektur, UML, dan ERD.</td></tr>
    <tr><td>8. Google Chrome</td><td>Peramban untuk mengakses dan menguji aplikasi web secara manual.</td></tr>
    <tr><td>9. PHP 8.3.2</td><td>Bahasa pemrograman sisi server yang digunakan sebagai dasar pengembangan aplikasi Laravel.</td></tr>
    <tr><td>10. Laravel 13</td><td>Framework PHP dengan arsitektur MVC untuk membangun logika aplikasi, pengelolaan rute, autentikasi, dan akses basis data.</td></tr>
    <tr><td>11. MySQL</td><td>Sistem manajemen basis data relasional untuk menyimpan data pengguna, notifikasi, klasifikasi AI, tiket, dan riwayat penanganan.</td></tr>
    <tr><td>12. Composer</td><td>Pengelola dependensi PHP untuk memasang dan mengelola paket yang dibutuhkan proyek Laravel.</td></tr>
    <tr><td>13. Node.js</td><td>Runtime JavaScript untuk menjalankan skrip Playwright dan mengelola paket JavaScript melalui npm.</td></tr>
    <tr><td>14. Playwright</td><td>Library otomasi peramban untuk mengambil data aduan dari Facebook dan Instagram.</td></tr>
    <tr>
      <td rowspan="2" style="text-align: center; vertical-align: middle;">2</td>
      <td rowspan="2" style="text-align: center; vertical-align: middle;">Bahan</td>
      <td>1. Data</td>
      <td>Data aduan masyarakat dari Facebook dan Instagram, data kategori dan subkategori, data OPD, serta data tiket yang digunakan untuk pengembangan dan pengujian sistem.</td>
    </tr>
    <tr><td>2. Kertas</td><td>Media cetak untuk laporan Tugas Akhir dan dokumen pendukung penelitian.</td></tr>
  </tbody>
</table>

### 3.1.3 Prosedur Penelitian

Pada pengembangan Sistem Informasi Manajemen Aduan Multi Channel KMC, langkah-langkah penelitian mengikuti metode Agile yang disesuaikan dengan kebutuhan peneliti. Adapun tahapan-tahapannya dapat dilihat pada Gambar 3.1 berikut.

*(Gambar 3.1 Prosedur Penelitian Pengembangan Sistem Informasi Manajemen Aduan Multi Channel KMC)*

**Requirement**

Pada tahap ini dilakukan pengumpulan informasi mengenai kebutuhan sistem berdasarkan hasil observasi selama penulis melaksanakan magang sebagai admin KMC di Dinas Komunikasi dan Informatika Kabupaten Ketapang. Dari observasi tersebut diketahui bahwa proses pengelolaan aduan dari Facebook dan Instagram masih dilakukan secara manual, mulai dari pemantauan, pencatatan, pengelompokan, hingga penerusan ke OPD melalui WhatsApp. Berdasarkan kondisi tersebut, dilakukan analisis kebutuhan sistem yang mencakup pengambilan data aduan secara otomatis, penyaringan pesan tidak relevan, klasifikasi berbantuan AI, deteksi kemungkinan duplikasi, pembuatan tiket, penugasan OPD, pelacakan publik, dan pemantauan batas waktu penanganan.

**Design**

Pada tahap ini dilakukan perancangan sistem berdasarkan kebutuhan yang telah dianalisis sebelumnya. Perancangan mencakup arsitektur sistem, arus data menggunakan diagram UML, struktur basis data menggunakan ERD, serta rancangan antarmuka untuk admin, OPD, dan masyarakat. Selain itu, disusun pula skenario pengujian sistem sebagai acuan pada tahap testing. Alat yang digunakan dalam perancangan adalah Draw.io untuk diagram dan Figma untuk rancangan antarmuka.

**Development**

Pada tahap ini peneliti mulai membangun sistem berdasarkan hasil perancangan yang telah dibuat sebelumnya. Pengembangan dilakukan secara bertahap dengan fokus pada penyelesaian fitur-fitur utama, antara lain sinkronisasi data aduan dari media sosial, penyaringan spam, klasifikasi berbantuan AI, verifikasi duplikasi, manajemen tiket, portal OPD, dan pelacakan tiket publik. Sistem dikembangkan menggunakan framework Laravel dengan bahasa pemrograman PHP, didukung MySQL sebagai basis data, serta Bootstrap dan Blade untuk tampilan antarmuka. Pengembangan dilakukan secara berkelanjutan dan disesuaikan dengan kebutuhan agar sistem dapat berjalan sesuai alur kerja pengelolaan aduan KMC.

**Testing**

Pada tahap ini peneliti melakukan pengujian sistem untuk memastikan seluruh fitur yang dikembangkan berjalan sesuai kebutuhan. Pengujian dilakukan menggunakan metode *black box testing*, yaitu menguji fungsi sistem berdasarkan masukan dan keluaran tanpa melihat struktur kode. Selain itu, dilakukan pula pengujian penerimaan pengguna melalui UAT untuk mengetahui apakah sistem dapat diterima oleh pengguna yang terlibat. Apabila ditemukan kesalahan atau kekurangan selama pengujian, perbaikan dilakukan dan pengujian diulang kembali.

### 3.1.4 Objek Penelitian

Objek penelitian ini adalah proses pengelolaan aduan masyarakat di Ketapang Media Center (KMC), Dinas Komunikasi dan Informatika Kabupaten Ketapang, serta aplikasi web Sistem Informasi Manajemen Aduan Multi Channel KMC yang dikembangkan untuk mendukung proses tersebut. Fokus penelitian berada pada pengelolaan aduan non-darurat yang disampaikan masyarakat melalui Facebook dan Instagram KMC.

Aplikasi yang dikembangkan berfungsi untuk mengambil data aduan, menyimpan notifikasi, memfilter pesan yang tidak layak diproses, mengklasifikasikan isi aduan, mendeteksi kemungkinan duplikasi, membuat tiket, meneruskan tiket ke OPD, serta mencatat perkembangan penanganan. Sistem tidak ditujukan sebagai layanan penanganan kedaruratan. Aduan yang bersifat darurat tetap perlu diarahkan kepada Call Center 112 atau instansi penanganan darurat yang berwenang.

Pengguna sistem terdiri dari tiga pihak. Admin KMC mengelola notifikasi, memverifikasi hasil duplikasi, mengelola tiket, dan mengelola akun OPD. Pengguna OPD menerima tiket yang ditugaskan kepada instansinya, memberikan tanggapan, serta memperbarui status penanganan. Masyarakat menggunakan halaman publik untuk melacak perkembangan tiket melalui nomor pelacakan tanpa harus masuk ke sistem.

### 3.1.5 Prosedur Pengumpulan Data

Prosedur pengumpulan data pada penelitian ini dilakukan melalui observasi dan studi literatur. Penulis tidak menggunakan wawancara sebagai sumber data penelitian.

1. **Observasi**

   Observasi dilakukan selama penulis melaksanakan magang sebagai admin KMC di Dinas Komunikasi dan Informatika Kabupaten Ketapang. Aktivitas yang diamati meliputi pemantauan aduan dari media sosial, pencatatan informasi aduan, pengelompokan masalah, penentuan OPD tujuan, serta tindak lanjut aduan. Hasil observasi digunakan untuk menyusun kebutuhan fungsional dan alur kerja sistem.

2. **Studi Literatur**

   Studi literatur dilakukan dengan mempelajari buku, jurnal, dokumentasi resmi, dan penelitian terdahulu yang berkaitan dengan manajemen aduan, aplikasi web, LLM, *prompt engineering*, sistem tiket, SLA, UML, basis data, dan pengujian perangkat lunak. Studi ini digunakan sebagai dasar teoritis dan sebagai acuan dalam memilih pendekatan pengembangan sistem.

3. **Dokumentasi Data Sistem**

   Data pendukung pengembangan diperoleh dari struktur data aduan Facebook dan Instagram, data master kategori, subkategori, dan OPD, serta data tiket yang digunakan selama pengembangan dan pengujian. Data tersebut dipakai untuk merancang struktur basis data dan format keluaran klasifikasi. Sistem tidak melakukan pelatihan ulang atau *fine-tuning* model LLM. Data master dan contoh aduan digunakan sebagai konteks pada prompt agar keluaran AI sesuai dengan kategori dan OPD yang tersedia pada sistem.

## 3.2 Perancangan Sistem

### 3.2.1 Arsitektur Sistem

Arsitektur Sistem Informasi Manajemen Aduan Multi Channel KMC dirancang sebagai aplikasi web berbasis Laravel yang menghubungkan sumber aduan media sosial, scraper lokal, layanan AI, basis data MySQL, dan pengguna sistem. Rancangan arsitektur ditunjukkan pada Gambar 3.2.

**[Sisipkan Gambar 3.2 Arsitektur Sistem Informasi Manajemen Aduan Multi Channel KMC]**

Aduan berasal dari Facebook, baik melalui mention pada postingan maupun komentar, serta dari pesan langsung Instagram. Data aduan diambil secara otomatis oleh scraper yang dijalankan pada komputer lokal, kemudian dikirim melalui internet menuju web server untuk diproses dan disimpan ke basis data.

Notifikasi yang masuk disaring untuk memisahkan pesan yang layak diproses dari pesan yang tidak relevan. Aduan yang lolos penyaringan diklasifikasikan oleh layanan AI untuk menentukan kategori, subkategori, OPD tujuan, dan prioritas penanganan. Sistem juga memeriksa kemungkinan duplikasi dengan aduan yang sebelumnya telah diproses. Apabila tidak ditemukan duplikasi, sistem membuat tiket dan menetapkan OPD tujuan. Apabila ditemukan kemungkinan duplikasi, notifikasi menunggu konfirmasi admin sebelum tiket dibuat.

Sistem dapat diakses oleh tiga jenis pengguna, yaitu admin KMC, pengguna OPD, dan masyarakat. Admin dan OPD mengakses sistem melalui antarmuka web dengan hak akses sesuai peran masing-masing. Masyarakat dapat mengakses portal pelacakan tiket secara publik tanpa perlu masuk ke sistem.

### 3.2.2 Perancangan Arus Data

Perancangan arus data menggunakan UML untuk menggambarkan interaksi pengguna dan proses utama sistem. Aktor yang terlibat adalah admin KMC, pengguna OPD, masyarakat, Facebook, Instagram, layanan AI Google AI Studio, dan sistem penjadwalan Laravel. Rancangan *use case* sistem ditunjukkan pada Gambar 3.3.

**[Sisipkan Gambar 3.3 Use Case Diagram Sistem Informasi Manajemen Aduan Multi Channel KMC]**

Admin KMC dapat masuk ke dashboard, memantau notifikasi, melihat hasil klasifikasi, memverifikasi kemungkinan duplikasi, mengelola tiket, mengelola data OPD, dan melihat statistik. Pengguna OPD dapat melihat tiket yang ditugaskan kepada OPD-nya, memberi tanggapan, memperbarui status, serta mengelola profil. Masyarakat dapat mencari dan melihat perkembangan tiket dengan nomor pelacakan. Proses sinkronisasi data media sosial dijalankan oleh sistem melalui scraper lokal dan perintah Artisan.

**Tabel 3.2 Deskripsi Aktor dan Hak Akses Sistem**

| Aktor | Hak Akses Utama |
|---|---|
| Admin KMC | Login, melihat dashboard, memantau notifikasi, memverifikasi duplikasi, mengelola tiket, mengelola data OPD dan akun OPD, serta melihat statistik. |
| Pengguna OPD | Login, melihat tiket yang ditugaskan kepada OPD-nya, memberi tanggapan, memperbarui status tiket, dan mengelola profil. |
| Masyarakat | Melacak tiket melalui nomor pelacakan dan melihat informasi status yang bersifat publik. |
| Sistem scraper | Menjalankan perintah Artisan pada komputer lokal yang memanggil skrip Playwright untuk mengambil data dari Facebook dan Instagram, kemudian menyimpan hasilnya ke basis data. |
| Google AI Studio | Memberikan keluaran klasifikasi, penilaian spam, dan penilaian kemungkinan duplikasi berdasarkan prompt yang dikirim aplikasi. |

Alur pengolahan aduan dari media sosial ditunjukkan pada Gambar 3.4. Proses dimulai ketika admin menjalankan sinkronisasi lokal atau sistem penjadwalan lokal menjalankan perintah sinkronisasi. Data hasil Playwright disimpan apabila belum pernah direkam. Pesan kemudian diperiksa oleh filter spam. Pesan yang tidak layak diproses tidak dibuatkan notifikasi tiket. Sebaliknya, pesan yang layak diproses diklasifikasikan oleh AI dan diperiksa kemungkinan duplikasinya.

**[Sisipkan Gambar 3.4 Activity Diagram Pengolahan Aduan dari Media Sosial]**

Jika tidak ada duplikasi, sistem membuat tiket dengan nomor pelacakan, menetapkan batas waktu penanganan, dan menetapkan OPD tujuan berdasarkan hasil klasifikasi. Jika ada kemungkinan duplikasi, sistem menandai notifikasi dan menunggu tindakan admin. Admin dapat menyatakan notifikasi tersebut sebagai duplikat sehingga tiket tidak dibuat, atau menyatakan bukan duplikat sehingga sistem membuat tiket dari hasil klasifikasi yang telah tersimpan.

Alur tindak lanjut tiket oleh OPD dan pemantauan SLA ditunjukkan pada Gambar 3.5.

**[Sisipkan Gambar 3.5 Activity Diagram Tindak Lanjut Tiket dan Eskalasi SLA]**

Tiket yang baru dibuat dikaitkan dengan OPD tujuan. OPD dapat membaca, memproses, memberi tanggapan, dan menyelesaikan tiket. Setiap perubahan status dicatat pada riwayat tiket. Apabila tiket melewati batas waktu penanganan tanpa respons, sistem mengubah status dan menetapkan batas waktu baru. Apabila tiket kembali melewati batas waktu tersebut, sistem mencatat eskalasi dan menaikkan prioritas tiket secara bertahap.

### 3.2.3 Perancangan Basis Data

Basis data dirancang menggunakan MySQL untuk menyimpan informasi yang diperlukan oleh sistem secara terstruktur. Entitas utama pada perancangan ini adalah pengguna, OPD, kategori, subkategori, notifikasi, hasil klasifikasi AI, tiket, tanggapan tiket, dan riwayat status tiket. Hubungan antarentitas ditunjukkan pada Gambar 3.6.

**[Sisipkan Gambar 3.6 Entity Relationship Diagram Sistem Informasi Manajemen Aduan Multi Channel KMC]**

Data pengguna dihubungkan dengan data instansi OPD sesuai perannya. Data kategori dihubungkan dengan subkategori yang masing-masing berkaitan dengan OPD tujuan. Setiap notifikasi yang masuk dapat memiliki satu hasil klasifikasi AI dan satu tiket. Tiket dapat memiliki banyak tanggapan dan riwayat perubahan status. Relasi tersebut dirancang agar data tiket dapat ditelusuri kembali ke notifikasi asal dan hasil klasifikasinya.

### 3.2.4 Perancangan Antar Muka

Perancangan antar muka bertujuan menyediakan tampilan yang mudah dipahami oleh setiap pengguna sesuai dengan tugasnya. Antarmuka dirancang menggunakan Blade Template dan Bootstrap. Tampilan admin dan OPD hanya dapat diakses setelah pengguna masuk menggunakan akun masing-masing, sedangkan halaman publik dapat diakses langsung untuk pelacakan tiket.

**Tabel 3.4 Rancangan Halaman Antarmuka**

| Halaman | Pengguna | Rancangan Fungsi |
|---|---|---|
| Halaman Login | Admin dan OPD | Memverifikasi email atau username serta kata sandi, kemudian mengarahkan pengguna sesuai peran. |
| Dashboard Admin | Admin KMC | Menampilkan jumlah tiket berdasarkan status, notifikasi terbaru, notifikasi prioritas tinggi, grafik tren aduan, dan distribusi platform. Data dashboard diperbarui melalui AJAX polling. |
| Daftar Notifikasi | Admin KMC | Menampilkan aduan masuk, hasil klasifikasi AI, status baca, dan status kemungkinan duplikasi. Admin dapat mencari serta menyaring data. |
| Detail/Daftar Tiket | Admin KMC | Menampilkan tiket, informasi kategori, OPD tujuan, prioritas, status, riwayat, dan tindakan pengelolaan tiket. |
| Manajemen OPD | Admin KMC | Menambah, mengubah, dan menghapus data OPD beserta akun pengguna OPD. |
| Dashboard OPD | Pengguna OPD | Menampilkan ringkasan tiket yang ditugaskan kepada OPD pengguna dan daftar tiket terbaru. |
| Detail Tiket OPD | Pengguna OPD | Menampilkan isi aduan, riwayat status, formulir tanggapan, unggahan lampiran, dan pilihan pembaruan status. |
| Portal Pelacakan Publik | Masyarakat | Memungkinkan pencarian tiket berdasarkan nomor pelacakan dan menampilkan informasi status tiket yang dapat diakses publik. |

Pada dashboard admin, notifikasi prioritas tinggi dipisahkan dari notifikasi terbaru agar aduan mendesak memperoleh perhatian lebih cepat. Data dashboard diperbarui secara berkala tanpa perlu memuat ulang halaman sehingga pengguna dapat memantau perkembangan secara langsung.

Pada daftar tiket, setiap tiket ditampilkan dengan penanda visual untuk tingkat prioritasnya sehingga admin dapat mengenali urgensi penanganan tiket tanpa membuka detail satu per satu.

### 3.2.5 Perancangan Pengujian Sistem

Pengujian sistem dirancang menggunakan metode *black box testing*. Metode ini berfokus pada kesesuaian keluaran sistem terhadap masukan dan kebutuhan fungsional tanpa menilai struktur kode program secara langsung. Perancangan pengujian mencakup skenario normal, masukan tidak valid, pembatasan hak akses, serta kondisi khusus pada proses AI dan SLA. Pendekatan ini sesuai untuk memastikan fungsi yang diakses pengguna berjalan sesuai harapan (Mustaqbal et al., 2015).

**Tabel 3.5 Rancangan Skenario Black Box Testing**

| No. | Fitur yang Diuji | Skenario Pengujian | Hasil yang Diharapkan |
|---:|---|---|---|
| 1 | Login | Admin memasukkan kredensial yang benar. | Sistem mengarahkan admin ke dashboard admin. |
| 2 | Login | OPD memasukkan kredensial yang benar. | Sistem mengarahkan pengguna ke dashboard OPD. |
| 3 | Login | Pengguna memasukkan kredensial yang salah atau kosong. | Sistem menolak login dan menampilkan pesan validasi. |
| 4 | Otorisasi | Pengguna OPD membuka halaman khusus admin. | Sistem menolak akses. |
| 5 | Sinkronisasi Facebook | Data mention Facebook baru berhasil diperoleh scraper. | Data mention dan notifikasi tersimpan satu kali. |
| 6 | Sinkronisasi Instagram | Data pesan Instagram baru berhasil diperoleh scraper. | Data mention dan notifikasi tersimpan satu kali. |
| 7 | Pencegahan data ganda | Scraper memproses tautan atau pesan yang telah tersimpan. | Sistem tidak membuat data mention atau notifikasi ganda. |
| 8 | Filter spam | Pesan hanya berisi emoji, reaksi singkat, atau promosi. | Pesan ditandai tidak layak dan tidak dibuatkan tiket. |
| 9 | Klasifikasi AI | Aduan valid dikirimkan ke layanan AI. | Sistem menyimpan kategori, subkategori, OPD, prioritas, kepercayaan, dan alasan. |
| 10 | Fallback klasifikasi | Layanan AI gagal atau hasil respons tidak valid. | Sistem menggunakan aturan *fallback* dan proses tidak berhenti. |
| 11 | Deteksi duplikasi | Aduan baru memiliki masalah, lokasi, dan objek yang sama dengan aduan bertiket. | Notifikasi ditandai sebagai kemungkinan duplikasi dan menunggu konfirmasi admin. |
| 12 | Konfirmasi duplikasi | Admin menyatakan notifikasi sebagai duplikat. | Notifikasi diarsipkan dan sistem tidak membuat tiket baru. |
| 13 | Konfirmasi bukan duplikat | Admin menyatakan notifikasi bukan duplikat. | Sistem membuat tiket otomatis dari hasil klasifikasi yang tersimpan. |
| 14 | Pembuatan tiket | Aduan valid tidak terdeteksi sebagai duplikat. | Sistem membuat nomor pelacakan, tiket, batas waktu penanganan, dan riwayat status awal. |
| 15 | Pengelolaan tiket | Admin mengubah kategori, subkategori, OPD, atau prioritas tiket. | Sistem menyimpan perubahan data tiket dan OPD tujuan. |
| 16 | Tanggapan OPD | Pengguna OPD mengirimkan tanggapan yang valid. | Sistem menyimpan tanggapan dan memperbarui status tiket apabila tiket belum ditutup. |
| 17 | Pembatasan tiket OPD | Pengguna OPD membuka tiket milik OPD lain. | Sistem menolak akses. |
| 18 | SLA tahap pertama | Tiket yang belum direspons melewati batas waktu penanganan. | Sistem mengubah status tiket ke tahap disposisi dan menetapkan batas waktu baru. |
| 19 | Eskalasi SLA | Tiket pada tahap disposisi kembali melewati batas waktu. | Sistem mencatat eskalasi dan menaikkan prioritas tiket. |
| 20 | Pelacakan publik | Masyarakat memasukkan nomor pelacakan yang tersedia atau tidak tersedia. | Sistem menampilkan detail tiket yang sesuai atau pesan bahwa tiket tidak ditemukan. |

Hasil pelaksanaan skenario tersebut disajikan pada BAB IV. Setiap pengujian dinyatakan berhasil apabila hasil aktual sesuai dengan hasil yang diharapkan pada tabel rancangan pengujian.

### 3.2.6 Perancangan Pengujian Penerimaan Pengguna

Pengujian penerimaan pengguna dirancang untuk mengetahui apakah sistem dapat diterima dan dipahami oleh pengguna yang terlibat dalam pengelolaan aduan. Responden yang direncanakan adalah admin KMC dan pengguna dari OPD yang menggunakan portal OPD. Pengujian dilakukan setelah responden mencoba fungsi yang sesuai dengan perannya, kemudian mengisi kuesioner.

Kuesioner menggunakan skala Likert lima tingkat, yaitu Sangat Tidak Setuju bernilai 1, Tidak Setuju bernilai 2, Netral bernilai 3, Setuju bernilai 4, dan Sangat Setuju bernilai 5. Nilai jawaban digunakan untuk menghitung persentase penerimaan pengguna dengan rumus berikut.

> Persentase penerimaan = (Total skor diperoleh / Total skor maksimum) × 100%

**Tabel 3.6 Rancangan Pernyataan UAT**

| No. | Aspek | Pernyataan |
|---:|---|---|
| 1 | Kemudahan penggunaan | Tampilan sistem mudah dipahami dan digunakan. |
| 2 | Kemudahan penggunaan | Menu dan navigasi membantu pengguna menemukan fungsi yang diperlukan. |
| 3 | Kesesuaian fungsi | Fitur notifikasi dan tiket membantu pengelolaan aduan. |
| 4 | Kesesuaian fungsi | Informasi kategori, OPD tujuan, dan prioritas membantu proses tindak lanjut. |
| 5 | Kejelasan informasi | Detail tiket dan riwayat status mudah dipahami. |
| 6 | Kejelasan informasi | Informasi batas SLA dan status disposisi membantu pemantauan tindak lanjut. |
| 7 | Efisiensi | Sistem membantu mengurangi kebutuhan pencatatan aduan secara manual. |
| 8 | Efisiensi | Portal OPD membantu OPD mengetahui tiket yang perlu ditangani. |
| 9 | Kepuasan | Sistem memenuhi kebutuhan dasar pengelolaan aduan KMC. |
| 10 | Kepuasan | Pengguna bersedia menggunakan sistem untuk proses pengelolaan aduan. |

Hasil UAT tidak digunakan untuk menilai kepuasan masyarakat terhadap penyelesaian masalah di lapangan. UAT hanya digunakan untuk mengukur penerimaan pengguna terhadap aplikasi yang dibangun, kemudahan penggunaannya, serta kesesuaian fungsinya dengan proses pengelolaan aduan.

---

**Catatan penyisipan gambar di Word:** gunakan berkas `GAMBAR_3_1_PROSEDUR_PENELITIAN.svg`, `GAMBAR_3_2_ARSITEKTUR_SISTEM.svg`, `GAMBAR_3_3_USE_CASE_DIAGRAM.svg`, `GAMBAR_3_4_ACTIVITY_PENGOLAHAN_ADUAN.svg`, `GAMBAR_3_5_ACTIVITY_TINDAK_LANJUT_SLA.svg`, dan `GAMBAR_3_6_ERD.svg` dari folder `.hermes/md/`. Konversi gambar ke PNG terlebih dahulu jika Microsoft Word tidak dapat menyisipkan SVG secara langsung.
