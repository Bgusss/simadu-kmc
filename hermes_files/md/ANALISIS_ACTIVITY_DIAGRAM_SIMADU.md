# Audit Activity Diagram SIMADU-KMC

## Dasar pemeriksaan
- `app/Console/Commands/SyncFacebookCommentMentions.php`
- `app/Services/TicketingService.php`
- `app/Console/Commands/CheckEscalation.php`
- `app/Http/Controllers/Opd/OpdController.php`
- `app/Models/Ticket.php`

## Gambar 3.4 Activity Diagram Pengolahan Aduan dari Media Sosial

### Status: perlu koreksi

1. **Urutan penyimpanan notifikasi belum tepat.**
   Diagram saat ini menampilkan proses “Simpan Mention dan Notifikasi” sebelum keputusan “Pesan spam?”. Pada alur sistem, mention sumber memang direkam lebih dahulu, tetapi notifikasi hanya disimpan setelah pesan lolos pemeriksaan kelayakan. Pesan spam tidak menghasilkan notifikasi dan tidak diproses menjadi tiket.

2. **Koreksi alur yang sesuai sistem:**
   `Sinkronisasi Aduan → Simpan Mention → Pesan spam? → [Ya] Selesai / [Tidak] Simpan Notifikasi → Klasifikasi → Kemungkinan duplikasi? → [Tidak] Buat Tiket dan teruskan ke OPD / [Ya] Menunggu verifikasi Admin KMC`.

3. Jalur duplikasi sudah sesuai: kandidat duplikasi tidak dibuatkan tiket otomatis dan menunggu keputusan Admin KMC. Jika Admin menyatakan bukan duplikasi, tiket dibuat; jika dikonfirmasi duplikasi, tiket tidak dibuat.

## Gambar 3.5 Activity Diagram Tindak Lanjut Tiket dan Eskalasi SLA

### Status: substansi utama sesuai, perlu penyempurnaan istilah dan alur

1. Setelah tiket dibuat, sistem mencatat status awal diterima lalu meneruskan tiket ke OPD. Diagram sudah menggambarkan kedua keadaan tersebut.

2. Tanggapan OPD secara otomatis tercatat dan menyebabkan status tiket menjadi dijawab. Pengguna OPD juga dapat memperbarui status secara terpisah. Oleh sebab itu, kotak proses sebaiknya memakai label:
   `Simpan Tanggapan / Perbarui Status Tiket`
   dan keterangan:
   `Riwayat penanganan dicatat`.

3. Jalur SLA sudah sesuai secara konsep: bila belum ada tindak lanjut setelah batas waktu pertama, tiket masuk proses disposisi; bila masih belum ada respons setelah batas waktu berikutnya, prioritas dieskalasi dan siklus penanganan berlanjut.

4. Status yang dipakai dalam diagram laporan sebaiknya ditulis naratif, bukan nilai teknis. Ganti `Status: proses_disposisi` menjadi `Tiket masuk proses disposisi`.

## Kesimpulan

- Gambar 3.4: belum sesuai sepenuhnya dan perlu direvisi pada posisi penyimpanan notifikasi.
- Gambar 3.5: sudah sesuai pada alur utama, tetapi perlu perapian istilah agar mencerminkan tanggapan dan pembaruan status OPD secara tepat.
- Setelah koreksi tersebut, kedua Activity Diagram akan konsisten dengan alur sistem aktif dan narasi BAB III.
