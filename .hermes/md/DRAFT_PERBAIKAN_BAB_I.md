# ✏️ DRAFT PERBAIKAN BAB I — SIAP COPY-PASTE KE WORD

**Mahasiswa:** Achmad Bagus Aprianto (3042023024)  
**Status:** Siap copy-paste ke LAPORAN TA  
**Tanggal:** 6 Juli 2026  

> 💡 **Petunjuk Penggunaan:**
> - Teks dengan tanda `[GANTI]` berarti Anda perlu menyesuaikan sendiri
> - Teks dengan tanda ✅ TIDAK BERUBAH = salin persis dari laporan lama
> - Teks dengan tanda 🔴 DIPERBAIKI = ganti dengan versi baru di bawah
> - Teks dengan tanda 🟢 DITAMBAHKAN = tambahkan di posisi yang ditentukan

---

# BAB 1
# PENDAHULUAN

---

## 1.1 Latar Belakang

Perkembangan teknologi informasi telah mengubah cara masyarakat berinteraksi dengan instansi pemerintah, terutama dalam menyampaikan aspirasi, laporan, maupun aduan. Saat ini, media sosial menjadi salah satu saluran yang paling sering digunakan masyarakat untuk menyampaikan keluhan karena aksesnya yang mudah, cepat, dan dapat menjangkau banyak pihak secara bersamaan. Kondisi ini mendorong instansi pelayanan publik untuk menyesuaikan diri dengan pola komunikasi digital agar dapat merespons kebutuhan masyarakat secara lebih efektif.

Salah satu instansi yang menerima aduan masyarakat melalui media sosial adalah Ketapang Media Center (KMC) yang berada di bawah naungan Dinas Komunikasi dan Informatika Kabupaten Ketapang. Dalam pelaksanaannya, KMC menerima laporan dan aduan masyarakat melalui berbagai platform seperti WhatsApp, Facebook, Instagram, dan media sosial resmi lainnya. Namun demikian, pada praktiknya pemantauan aduan masih dilakukan secara terpisah dan manual, sehingga petugas harus memeriksa satu per satu pesan atau komentar yang masuk dari masing-masing platform. Kondisi ini tentu membutuhkan waktu dan tenaga yang cukup besar, terutama ketika volume aduan meningkat dalam waktu yang bersamaan.

Meskipun KMC menerima aduan dari berbagai kanal komunikasi, penelitian ini difokuskan pada pengelolaan aduan yang berasal dari Facebook dan Instagram sebagai tahap awal pengembangan sistem. Pemilihan kedua platform tersebut didasarkan pada tingginya interaksi masyarakat dalam menyampaikan informasi, keluhan, maupun aduan melalui media sosial. Dengan demikian, sistem yang dikembangkan diharapkan dapat menjadi fondasi untuk pengembangan kanal aduan lainnya pada tahap berikutnya.

Permasalahan yang muncul tidak hanya terkait banyaknya saluran aduan, tetapi juga pada proses identifikasi isi aduan. Tidak semua aduan yang masuk memiliki kategori dan tingkat urgensi yang sama. Sebagian aduan bersifat mendesak dan perlu segera ditindaklanjuti, sedangkan sebagian lainnya hanya berupa laporan informasi umum atau keluhan rutin. Apabila seluruh aduan diproses tanpa mekanisme pengelompokan dan prioritas yang baik, maka petugas berisiko mengalami keterlambatan dalam menentukan aduan mana yang harus ditangani lebih dahulu. Hal ini dapat berdampak pada menurunnya efektivitas pelayanan dan respons instansi terhadap kebutuhan masyarakat.

Selain itu, aduan yang masuk melalui media sosial juga berpotensi memiliki isi yang serupa atau bahkan duplikat. Satu permasalahan yang sama dapat dilaporkan oleh beberapa akun atau muncul kembali dalam bentuk komentar lanjutan pada postingan yang berbeda. Jika tidak ada sistem yang mampu mengenali kesamaan atau kemiripan aduan, maka petugas dapat saja melakukan pencatatan ganda atau memproses keluhan yang sebenarnya masih berkaitan dengan peristiwa yang sama. Akibatnya, proses penanganan menjadi kurang efisien dan dokumentasi aduan menjadi tidak terstruktur.

Berdasarkan kondisi tersebut, dibutuhkan sebuah sistem informasi yang mampu membantu KMC dalam mengelola aduan masyarakat secara terpusat. Sistem yang dibangun diharapkan dapat menampilkan notifikasi aduan secara real-time, menyimpan data aduan ke dalam basis data, serta memberikan rekomendasi awal terhadap kategori aduan. Pada tahap pengembangan ini, sistem juga dirancang untuk memanfaatkan Artificial Intelligence (AI) sebagai pendukung dalam proses klasifikasi aduan, pemberian saran prioritas, dan pengambilan keputusan awal sebelum dilakukan validasi oleh admin. Dengan demikian, AI tidak menggantikan peran petugas, melainkan membantu mempercepat proses analisis terhadap isi aduan yang masuk.

✅ **2 PARAGRAF TERAKHIR LATAR BELAKANG (sudah final, JANGAN DIUBAH):**

Berdasarkan kebutuhan tersebut, penulis mengembangkan Sistem Informasi Manajemen Aduan Multi Channel KMC sebagai solusi untuk membantu petugas dalam memantau dan mengelola aduan masyarakat melalui media sosial. Pada tahap implementasi penelitian ini, integrasi sistem difokuskan pada Facebook dan Instagram sebagai sumber aduan utama, dengan mengembangkan empat jenis mekanisme pengambilan data otomatis, yaitu pemantauan mention postingan pada akun resmi Facebook KMC, pemantauan mention Facebook KMC pada komentar postingan masyarakat, dan pemantauan pesan langsung (direct message) Instagram. Kedua platform tersebut dipilih karena tingginya volume interaksi masyarakat dan ketersediaan mekanisme pengambilan data yang memadai.

Sistem yang dikembangkan dilengkapi mekanisme Service Level Agreement (SLA) 24 jam dan eskalasi prioritas otomatis untuk memastikan setiap aduan mendapat penanganan yang tepat waktu, serta halaman khusus bagi Organisasi Perangkat Daerah (OPD) terkait untuk melakukan tindak lanjut aduan sesuai bidangnya. Dengan adanya sistem informasi manajemen aduan yang terpusat dan berbantuan AI, KMC diharapkan dapat meningkatkan kualitas layanan publik, mempercepat respons terhadap aduan masyarakat, dan menciptakan proses pengelolaan aduan yang lebih efektif, rapi, serta terdokumentasi dengan baik.

---

## 1.2 Rumusan Masalah

✅ **TIDAK BERUBAH — salin persis dari laporan lama:**

Berdasarkan latar belakang yang telah diuraikan, maka rumusan masalah dalam penelitian ini adalah sebagai berikut:

1. Bagaimana mengembangkan Sistem Informasi Manajemen Aduan KMC berbasis web yang mampu mengelola aduan masyarakat secara terpusat dari Facebook dan Instagram?
2. Bagaimana menerapkan AI untuk membantu proses klasifikasi aduan berdasarkan isi pesan yang diterima dari Facebook dan Instagram?
3. Bagaimana menampilkan notifikasi aduan secara cepat dan terstruktur pada dashboard admin agar proses pemantauan lebih efektif?
4. Bagaimana menyediakan rekomendasi kategori, subkategori, tingkat prioritas, dan OPD terkait sebagai bahan pertimbangan admin sebelum pembuatan tiket aduan?
5. Bagaimana menyimpan data aduan dan hasil klasifikasi AI ke dalam basis data agar dapat digunakan kembali dalam proses pengelolaan aduan berikutnya?

---

## 1.3 Batasan Masalah

Agar pembahasan dalam penelitian ini lebih terarah dan tidak menyimpang dari tujuan yang telah ditetapkan, maka batasan masalah pada penelitian ini adalah sebagai berikut:

✅ **TIDAK BERUBAH — poin 1 s.d. 5:**

1. Sistem yang dikembangkan merupakan Sistem Informasi Manajemen Aduan KMC berbasis web untuk membantu pengelolaan aduan masyarakat.
2. Sumber aduan yang digunakan pada penelitian ini dibatasi pada Facebook dan Instagram sebagai media sosial yang dipantau oleh sistem.
3. Pengambilan data aduan pada penelitian ini dibatasi pada Facebook dan Instagram serta tidak mencakup platform lain seperti WhatsApp, Telegram, atau media komunikasi lainnya.
4. AI digunakan untuk memberikan rekomendasi klasifikasi aduan, meliputi kategori, subkategori, tingkat prioritas, dan OPD terkait.
5. Hasil klasifikasi AI bersifat rekomendasi dan tetap memerlukan validasi admin sebelum aduan diproses lebih lanjut.

✅ **POIN 6 (sudah final, JANGAN DIUBAH):**

6. Penelitian ini mencakup proses pengelolaan aduan mulai dari penerimaan, notifikasi, klasifikasi, pembuatan tiket, disposisi ke OPD, hingga mekanisme eskalasi otomatis berbasis Service Level Agreement (SLA) 24 jam. Sistem dilengkapi Halaman OPD untuk memfasilitasi tindak lanjut aduan, namun evaluasi kualitas penyelesaian dan kepuasan masyarakat terhadap hasil penanganan aduan di lapangan tidak termasuk dalam ruang lingkup penelitian ini.

✅ **TIDAK BERUBAH — poin 7 (terakhir):**

7. Integrasi dengan media sosial lain serta pengembangan fitur lanjutan dapat dilakukan pada penelitian berikutnya.

---

## 1.4 Tujuan Penelitian

✅ **TIDAK BERUBAH — salin persis dari laporan lama:**

Tujuan dari penelitian ini adalah sebagai berikut:

1. Mengembangkan Sistem Informasi Manajemen Aduan KMC berbasis web untuk membantu pengelolaan aduan masyarakat secara terpusat.
2. Mengintegrasikan aduan dari Facebook dan Instagram ke dalam satu sistem agar proses pemantauan menjadi lebih mudah dan terstruktur.
3. Menerapkan AI untuk membantu proses klasifikasi aduan berdasarkan isi pesan yang masuk.
4. Menyediakan rekomendasi kategori, subkategori, tingkat prioritas, dan OPD terkait sebagai bahan pertimbangan admin dalam pengelolaan aduan.
5. Menyimpan data aduan dan hasil klasifikasi AI ke dalam basis data agar dapat digunakan kembali dalam proses pengelolaan aduan berikutnya.

---

## 1.5 Manfaat Penelitian

✅ **TIDAK BERUBAH — salin persis dari laporan lama:**

Penelitian ini diharapkan dapat memberikan manfaat baik secara teoritis maupun praktis bagi berbagai pihak yang terkait. Adapun manfaat penelitian ini adalah sebagai berikut:

**1. Bagi Ketapang Media Center (KMC)**

- Membantu admin KMC dalam memantau dan mengelola aduan masyarakat yang berasal dari media sosial secara lebih efektif dan terstruktur.
- Mempermudah proses identifikasi aduan melalui rekomendasi klasifikasi yang dihasilkan oleh AI.
- Membantu menentukan prioritas penanganan aduan sehingga aduan yang bersifat mendesak dapat segera ditindaklanjuti.
- Menyediakan data aduan yang terdokumentasi dengan baik untuk mendukung proses pelaporan dan evaluasi pelayanan publik.

**2. Bagi Masyarakat**

- Mendukung peningkatan kualitas pelayanan publik melalui pengelolaan aduan yang lebih cepat dan terorganisir.
- Membantu memastikan bahwa aduan masyarakat yang disampaikan melalui media sosial dapat terdata dan dipantau dengan lebih baik.

**3. Bagi Penulis**

- Menambah wawasan dan pengalaman dalam menerapkan ilmu yang telah diperoleh selama perkuliahan, khususnya dalam bidang pengembangan perangkat lunak dan kecerdasan buatan.
- Meningkatkan kemampuan dalam merancang, membangun, dan mengimplementasikan sistem informasi berbasis web yang terintegrasi dengan teknologi AI.
- Memenuhi salah satu persyaratan akademik untuk memperoleh gelar Ahli Madya pada Program Studi Diploma III Teknologi Informasi Politeknik Negeri Ketapang.

**4. Bagi Program Studi dan Peneliti Selanjutnya**

- Menjadi referensi dalam pengembangan penelitian yang berkaitan dengan sistem informasi manajemen aduan berbasis media sosial.
- Menjadi bahan kajian dalam pengembangan penerapan AI pada bidang pelayanan publik.
- Menjadi dasar bagi penelitian selanjutnya untuk mengembangkan integrasi dengan media sosial lain serta penyempurnaan fitur-fitur sistem yang belum diimplementasikan.

---

## 1.6 Sistematika Penulisan

✅ **TIDAK BERUBAH — salin persis dari laporan lama:**

Sistematika penulisan laporan tugas akhir ini disusun untuk memberikan gambaran singkat mengenai isi dari masing-masing bab yang dibahas, sehingga pembaca dapat memahami alur pembahasan secara terstruktur. Adapun sistematika penulisan laporan ini adalah sebagai berikut:

**BAB I PENDAHULUAN**

Bab ini menjelaskan latar belakang masalah, rumusan masalah, batasan masalah, tujuan penelitian, manfaat penelitian, serta sistematika penulisan yang digunakan dalam penyusunan laporan tugas akhir.

**BAB II TINJAUAN PUSTAKA**

Bab ini memuat teori-teori yang mendukung penelitian, penelitian terdahulu yang relevan, tinjauan pustaka yang berkaitan dengan judul penelitian, serta profil tempat penelitian yaitu Ketapang Media Center.

**BAB III METODOLOGI PENELITIAN DAN PERANCANGAN SISTEM**

Bab ini menjelaskan metode penelitian yang digunakan, alat dan bahan yang dipakai, prosedur penelitian, objek penelitian, serta perancangan sistem yang meliputi arsitektur sistem, perancangan arus data, basis data, antarmuka, dan pengujian sistem.

**BAB IV HASIL PENELITIAN**

Bab ini membahas hasil implementasi sistem yang telah dibangun, hasil pengujian sistem, serta hasil pengujian penerimaan pengguna terhadap sistem yang dikembangkan.

**BAB V PENUTUP**

Bab ini berisi kesimpulan dari hasil penelitian yang telah dilakukan serta saran untuk pengembangan sistem pada penelitian selanjutnya.

---

# 📋 RINGKASAN PERUBAHAN — CHECKLIST

| No | Bagian | Tindakan | Keterangan |
|----|--------|----------|------------|
| 1 | 1.1 Latar Belakang | 🔴 **GANTI** 2 paragraf terakhir | Tambahkan keterangan 4 jenis scraper + SLA + Portal OPD |
| 2 | 1.2 Rumusan Masalah | ✅ Tidak berubah | — |
| 3 | 1.3 Batasan Masalah — Poin 1-5 | ✅ Tidak berubah | — |
| 4 | 1.3 Batasan Masalah — **Poin 6** | 🔴 **GANTI** | Sesuaikan dengan fitur SLA + Portal OPD yang sudah ada |
| 5 | 1.3 Batasan Masalah — Poin 7 | ✅ Tidak berubah | — |
| 6 | 1.4 Tujuan Penelitian | ✅ Tidak berubah | — |
| 7 | 1.5 Manfaat Penelitian | ✅ Tidak berubah | — |
| 8 | 1.6 Sistematika Penulisan | ✅ Tidak berubah | — |

**Total perubahan: 2 bagian saja (Latar Belakang + Batasan Masalah poin 6)**  
**Estimasi waktu pengerjaan: 10–15 menit**

---

*Dibuat oleh Kiro (Hermes Agent) — 6 Juli 2026*
