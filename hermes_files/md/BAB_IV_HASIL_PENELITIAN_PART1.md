# BAB IV
# HASIL PENELITIAN

Bab ini menyajikan hasil pelaksanaan penelitian yang meliputi implementasi Sistem Informasi Manajemen Aduan Multi Channel KMC, hasil pengujian fungsional menggunakan metode *black box testing*, serta hasil pengujian penerimaan pengguna melalui *User Acceptance Testing* (UAT). Sistem yang dikembangkan mengintegrasikan pengambilan data aduan dari Facebook dan Instagram, klasifikasi berbantuan AI menggunakan model Gemma 4 31B IT, deteksi kemungkinan duplikasi, serta manajemen tiket dengan pemantauan SLA dan eskalasi otomatis.

## 4.1 Hasil Penelitian

Hasil penelitian berupa aplikasi web Sistem Informasi Manajemen Aduan Multi Channel KMC yang dapat diakses oleh tiga pihak, yaitu admin KMC, pengguna OPD, dan masyarakat. Sistem dikembangkan menggunakan framework Laravel 13 dengan bahasa pemrograman PHP 8.3.2, basis data MySQL, serta Bootstrap dan Blade untuk antarmuka pengguna. Pengambilan data aduan dari media sosial dilakukan menggunakan Playwright yang dijalankan secara lokal, sedangkan layanan klasifikasi terhubung dengan Google AI Studio melalui API.

Berikut adalah tampilan implementasi sistem berdasarkan peran pengguna.

### 4.1.1 Tampilan Halaman Admin

Halaman admin digunakan oleh admin Ketapang Media Center untuk mengelola notifikasi aduan, memverifikasi hasil klasifikasi AI, mengonfirmasi kemungkinan duplikasi, mengelola tiket, serta mengelola data OPD dan akun pengguna OPD. Admin mengakses sistem melalui halaman login menggunakan email dan kata sandi.

#### 4.1.1.1 Halaman Login Admin

Halaman login merupakan halaman pertama yang diakses admin sebelum masuk ke dashboard. Admin memasukkan email atau username serta kata sandi. Apabila kredensial benar, sistem mengarahkan admin ke dashboard. Apabila kredensial salah, sistem menampilkan pesan validasi dan menolak akses. Tampilan halaman login admin ditunjukkan pada Gambar 4.1.

**[Sisipkan Gambar 4.1 Halaman Login Admin]**

Gambar 4.1 menampilkan formulir login dengan dua kolom masukan yaitu email atau username dan kata sandi. Tombol login terletak di bawah formulir. Halaman login menggunakan validasi sisi server untuk memastikan keamanan autentikasi.

#### 4.1.1.2 Halaman Dashboard Admin

Setelah berhasil masuk, admin diarahkan ke halaman dashboard yang menampilkan ringkasan data tiket, notifikasi terbaru, notifikasi dengan prioritas tinggi, grafik tren aduan, serta distribusi platform sumber aduan. Dashboard menggunakan AJAX polling setiap 10 detik untuk memperbarui data secara otomatis tanpa perlu memuat ulang halaman. Tampilan dashboard admin ditunjukkan pada Gambar 4.2.

**[Sisipkan Gambar 4.2 Halaman Dashboard Admin]**

Gambar 4.2 menampilkan empat kartu ringkasan di bagian atas yang menyajikan jumlah tiket berdasarkan status, yaitu Diterima, Dijawab, Proses Disposisi, dan Selesai. Di bawahnya terdapat dua bagian notifikasi, yaitu Notifikasi Prioritas Tinggi dan Notifikasi Terbaru. Kedua bagian tersebut diperbarui secara otomatis melalui polling. Bagian bawah dashboard menampilkan grafik tren aduan per kategori serta diagram distribusi platform aduan.

#### 4.1.1.3 Halaman Daftar Notifikasi

Halaman daftar notifikasi menampilkan seluruh aduan yang masuk dari Facebook dan Instagram beserta hasil klasifikasi AI. Admin dapat melihat kategori, subkategori, OPD tujuan, prioritas, tingkat kepercayaan, dan status kemungkinan duplikasi. Halaman ini dilengkapi dengan fitur pencarian dan penyaringan berdasarkan status, platform, dan prioritas. Tampilan halaman daftar notifikasi ditunjukkan pada Gambar 4.3.

**[Sisipkan Gambar 4.3 Halaman Daftar Notifikasi]**

Gambar 4.3 menampilkan tabel notifikasi yang berisi kolom Platform, Pengirim, Isi Aduan, Kategori, Subkategori, OPD Tujuan, Prioritas, Status Baca, dan Status Duplikasi. Setiap baris dilengkapi dengan tombol aksi untuk melihat detail notifikasi. Bagian atas halaman menyediakan kotak pencarian dan filter dropdown untuk menyaring data.

#### 4.1.1.4 Halaman Detail Notifikasi

Halaman detail notifikasi menampilkan informasi lengkap satu notifikasi aduan, termasuk isi pesan, hasil klasifikasi AI, alasan klasifikasi, tingkat kepercayaan, serta status kemungkinan duplikasi. Apabila sistem mendeteksi kemungkinan duplikasi, halaman ini menampilkan tombol untuk mengonfirmasi bahwa notifikasi merupakan duplikat atau bukan duplikat. Tampilan halaman detail notifikasi ditunjukkan pada Gambar 4.4.

**[Sisipkan Gambar 4.4 Halaman Detail Notifikasi]**

Gambar 4.4 menampilkan informasi notifikasi secara lengkap di bagian atas, diikuti dengan hasil klasifikasi AI yang mencakup kategori, subkategori, OPD tujuan, prioritas, tingkat kepercayaan, dan alasan klasifikasi. Apabila terdapat kemungkinan duplikasi, bagian bawah halaman menampilkan informasi notifikasi pembanding beserta tombol "Konfirmasi Duplikat" dan "Bukan Duplikat".

#### 4.1.1.5 Halaman Daftar Tiket

Halaman daftar tiket menampilkan seluruh tiket yang telah dibuat dari notifikasi aduan. Admin dapat melihat nomor tiket, nomor pelacakan, kategori, subkategori, OPD tujuan, prioritas, status, batas SLA, dan jumlah eskalasi. Halaman ini dilengkap dengan fitur pencarian berdasarkan nomor tiket, nomor pelacakan, atau nama pelapor. Tampilan halaman daftar tiket ditunjukkan pada Gambar 4.5.

**[Sisipkan Gambar 4.5 Halaman Daftar Tiket]**

Gambar 4.5 menampilkan tabel tiket dengan kolom Nomor Tiket, Nomor Pelacakan, Kategori, Subkategori, OPD Tujuan, Prioritas, Status, Batas SLA, dan Jumlah Eskalasi. Prioritas ditampilkan dengan penanda visual berwarna biru untuk prioritas rendah, kuning untuk prioritas sedang, dan merah untuk prioritas tinggi. Setiap baris dilengkapi dengan tombol aksi untuk melihat detail atau mengedit tiket.

#### 4.1.1.6 Halaman Detail Tiket

Halaman detail tiket menampilkan informasi lengkap tiket, riwayat status tiket, tanggapan dari OPD, serta formulir untuk admin memberikan tanggapan atau mengubah status tiket. Admin juga dapat mengubah kategori, subkategori, OPD tujuan, atau prioritas tiket apabila hasil klasifikasi AI tidak sesuai. Tampilan halaman detail tiket ditunjukkan pada Gambar 4.6.

**[Sisipkan Gambar 4.6 Halaman Detail Tiket]**

Gambar 4.6 menampilkan informasi tiket di bagian atas, diikuti dengan riwayat perubahan status tiket yang disajikan secara kronologis. Di bawahnya terdapat daftar tanggapan dari OPD atau admin. Bagian bawah halaman menyediakan formulir untuk memberikan tanggapan baru serta tombol untuk mengubah status tiket atau mengedit data tiket.

#### 4.1.1.7 Halaman Manajemen OPD

Halaman manajemen OPD digunakan admin untuk menambah, mengubah, dan menghapus data OPD beserta akun pengguna OPD. Halaman ini menampilkan daftar OPD dalam bentuk tabel yang mencakup nama OPD, jumlah pengguna yang terdaftar, serta tombol aksi untuk mengedit atau menghapus data. Tampilan halaman manajemen OPD ditunjukkan pada Gambar 4.7.

**[Sisipkan Gambar 4.7 Halaman Manajemen OPD]**

Gambar 4.7 menampilkan tabel OPD yang berisi kolom Nama OPD, Jumlah Pengguna, dan Aksi. Bagian atas halaman menyediakan tombol "Tambah OPD" untuk menambahkan data OPD baru. Setiap baris tabel dilengkapi dengan tombol "Edit" dan "Hapus".

#### 4.1.1.8 Halaman Tambah/Edit OPD

Halaman tambah atau edit OPD menyediakan formulir untuk memasukkan nama OPD dan mengelola akun pengguna OPD yang terkait. Admin dapat menambahkan beberapa akun pengguna sekaligus dengan menekan tombol "Tambah Pengguna". Tampilan halaman tambah OPD ditunjukkan pada Gambar 4.8.

**[Sisipkan Gambar 4.8 Halaman Tambah/Edit OPD]**

Gambar 4.8 menampilkan formulir dengan kolom Nama OPD di bagian atas, diikuti dengan bagian daftar pengguna yang memiliki kolom Nama, Email, Username, dan Password. Tombol "Tambah Pengguna" memungkinkan admin menambahkan baris baru untuk akun pengguna tambahan. Tombol "Simpan" terletak di bagian bawah formulir.

#### 4.1.1.9 Halaman Profil Admin

Halaman profil admin memungkinkan admin mengubah informasi akun seperti nama, email, username, serta mengganti kata sandi. Halaman ini menyediakan dua bagian formulir, yaitu formulir informasi profil dan formulir perubahan kata sandi. Tampilan halaman profil admin ditunjukkan pada Gambar 4.9.

**[Sisipkan Gambar 4.9 Halaman Profil Admin]**

Gambar 4.9 menampilkan dua bagian formulir. Bagian pertama berisi kolom Nama, Email, dan Username dengan tombol "Simpan Profil". Bagian kedua berisi kolom Kata Sandi Lama, Kata Sandi Baru, dan Konfirmasi Kata Sandi Baru dengan tombol "Ubah Kata Sandi".

### 4.1.2 Tampilan Halaman OPD

Halaman OPD digunakan oleh pengguna dari Organisasi Perangkat Daerah untuk melihat tiket yang ditugaskan kepada OPD-nya, memberikan tanggapan, serta memperbarui status penanganan tiket. Pengguna OPD mengakses sistem melalui halaman login menggunakan email dan kata sandi yang telah didaftarkan oleh admin.

#### 4.1.2.1 Halaman Login OPD

Halaman login OPD memiliki tampilan yang sama dengan halaman login admin, namun mengarahkan pengguna ke dashboard OPD setelah berhasil masuk. Tampilan halaman login OPD ditunjukkan pada Gambar 4.10.

**[Sisipkan Gambar 4.10 Halaman Login OPD]**

Gambar 4.10 menampilkan formulir login dengan dua kolom masukan yaitu email atau username dan kata sandi. Sistem memverifikasi kredensial dan mengarahkan pengguna ke dashboard OPD apabila kredensial benar.

#### 4.1.2.2 Halaman Dashboard OPD

Setelah berhasil masuk, pengguna OPD diarahkan ke halaman dashboard yang menampilkan ringkasan tiket yang ditugaskan kepada OPD-nya. Dashboard menampilkan jumlah tiket berdasarkan status serta daftar tiket terbaru yang memerlukan tindak lanjut. Tampilan dashboard OPD ditunjukkan pada Gambar 4.11.

**[Sisipkan Gambar 4.11 Halaman Dashboard OPD]**

Gambar 4.11 menampilkan empat kartu ringkasan yang menyajikan jumlah tiket berdasarkan status yaitu Diterima, Dijawab, Proses Disposisi, dan Selesai. Di bawahnya terdapat tabel daftar tiket terbaru yang memuat kolom Nomor Tiket, Nomor Pelacakan, Kategori, Prioritas, Status, dan Batas SLA.

#### 4.1.2.3 Halaman Daftar Tiket OPD

Halaman daftar tiket OPD menampilkan seluruh tiket yang ditugaskan kepada OPD pengguna. Pengguna OPD hanya dapat melihat tiket yang ditugaskan kepada OPD-nya sendiri dan tidak dapat mengakses tiket OPD lain. Halaman ini dilengkapi dengan fitur pencarian dan penyaringan berdasarkan status dan prioritas. Tampilan halaman daftar tiket OPD ditunjukkan pada Gambar 4.12.

**[Sisipkan Gambar 4.12 Halaman Daftar Tiket OPD]**

Gambar 4.12 menampilkan tabel tiket dengan kolom Nomor Tiket, Nomor Pelacakan, Kategori, Subkategori, Prioritas, Status, dan Batas SLA. Setiap baris dilengkapi dengan tombol "Lihat Detail" untuk membuka halaman detail tiket.

#### 4.1.2.4 Halaman Detail Tiket OPD

Halaman detail tiket OPD menampilkan informasi lengkap tiket, isi aduan masyarakat, riwayat status tiket, serta tanggapan yang telah diberikan. Pengguna OPD dapat memberikan tanggapan baru, mengunggah lampiran, serta memperbarui status tiket menjadi Dibaca, Dijawab, Proses Disposisi, atau Selesai. Tampilan halaman detail tiket OPD ditunjukkan pada Gambar 4.13.

**[Sisipkan Gambar 4.13 Halaman Detail Tiket OPD]**

Gambar 4.13 menampilkan informasi tiket di bagian atas, termasuk nomor tiket, nomor pelacakan, kategori, subkategori, prioritas, dan isi aduan. Bagian tengah menampilkan riwayat status dan tanggapan yang telah diberikan. Bagian bawah menyediakan formulir tanggapan dengan kolom isi tanggapan, unggahan lampiran, dan pilihan status baru. Tombol "Kirim Tanggapan" terletak di bawah formulir.

#### 4.1.2.5 Halaman Profil OPD

Halaman profil OPD memungkinkan pengguna OPD mengubah informasi akun pribadi seperti nama, email, username, serta mengganti kata sandi. Pengguna OPD tidak dapat mengubah informasi OPD atau mengelola pengguna lain. Tampilan halaman profil OPD ditunjukkan pada Gambar 4.14.

**[Sisipkan Gambar 4.14 Halaman Profil OPD]**

Gambar 4.14 menampilkan dua bagian formulir. Bagian pertama berisi kolom Nama, Email, dan Username dengan tombol "Simpan Profil". Bagian kedua berisi kolom Kata Sandi Lama, Kata Sandi Baru, dan Konfirmasi Kata Sandi Baru dengan tombol "Ubah Kata Sandi".

### 4.1.3 Tampilan Portal Pelacakan Publik

Portal pelacakan publik digunakan oleh masyarakat untuk melacak perkembangan tiket tanpa perlu masuk ke sistem. Masyarakat hanya perlu memasukkan nomor pelacakan yang diperoleh dari notifikasi balasan admin atau OPD di media sosial. Portal ini dapat diakses tanpa autentikasi.

#### 4.1.3.1 Halaman Pelacakan Tiket Publik

Halaman pelacakan tiket publik menyediakan kolom pencarian untuk memasukkan nomor pelacakan. Setelah masyarakat memasukkan nomor pelacakan dan menekan tombol "Lacak Tiket", sistem menampilkan informasi status tiket, kategori, OPD yang menangani, serta riwayat penanganan yang bersifat publik. Tampilan halaman pelacakan tiket publik ditunjukkan pada Gambar 4.15.

**[Sisipkan Gambar 4.15 Halaman Pelacakan Tiket Publik]**

Gambar 4.15 menampilkan formulir pencarian dengan kolom Nomor Pelacakan dan tombol "Lacak Tiket" di bagian atas. Setelah pencarian dilakukan, bagian bawah halaman menampilkan informasi tiket yang mencakup nomor tiket, status, kategori, subkategori, OPD yang menangani, serta riwayat penanganan. Apabila nomor pelacakan tidak ditemukan, sistem menampilkan pesan bahwa tiket tidak ditemukan.
