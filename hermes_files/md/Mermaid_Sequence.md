# Kode Mermaid.js untuk 4 Sequence Diagram Inti Sistem SIMADU-KMC

Gunakan kode-kode di bawah ini pada [Mermaid Live Editor](https://mermaid.live) atau plugin Markdown Anda untuk menghasilkan visualisasi yang siap digunakan. Desain ini mengikuti struktur diagram urutan (Sequence) dengan garis waktu *lifeline*.

---

### 1. Sequence Diagram Pengolahan Aduan (Sumber Otomatis Media Sosial)

```mermaid
sequenceDiagram
    autonumber
    actor L1 as Sistem Scraper
    participant L2 as Pengontrol
    participant L3 as Layanan AI
    participant L4 as Basis Data

    activate L1
    L1->>L2: Mengirim data aduan
    activate L2
    
    L2->>L3: Meminta filter kelayakan
    activate L3
    L3-->>L2: Hasil (Layak)
    deactivate L3
    
    L2->>L3: Meminta klasifikasi (Kategori, OPD, Prioritas)
    activate L3
    L3-->>L2: Hasil klasifikasi
    deactivate L3
    
    L2->>L4: Menyimpan notifikasi
    activate L4
    L4-->>L2: Konfirmasi tersimpan
    deactivate L4
    
    L2->>L3: Memeriksa kemungkinan duplikasi
    activate L3
    L3-->>L2: Hasil deteksi duplikasi
    deactivate L3
    
    L2->>L4: Menyimpan tiket (Jika bukan duplikat)
    activate L4
    L4-->>L2: Konfirmasi tiket tersimpan
    deactivate L4
    
    deactivate L2
    deactivate L1
```

---

### 2. Sequence Diagram Verifikasi Duplikasi oleh Admin KMC

```mermaid
sequenceDiagram
    autonumber
    actor L1 as Admin KMC
    participant L2 as Antarmuka
    participant L3 as Pengontrol
    participant L4 as Basis Data

    activate L1
    L1->>L2: Membuka notifikasi kandidat duplikat
    activate L2
    
    L2->>L3: Meminta data perbandingan
    activate L3
    
    L3->>L4: Mengambil aduan baru & pembanding
    activate L4
    L4-->>L3: Mengembalikan data aduan
    deactivate L4
    
    L3-->>L2: Menampilkan halaman perbandingan
    deactivate L3
    
    L1->>L2: Konfirmasi status aduan (duplikat/bukan)
    
    L2->>L3: Mengirim keputusan verifikasi
    activate L3
    
    L3->>L4: Mengupdate status notifikasi & membuat tiket (jika bukan duplikat)
    activate L4
    L4-->>L3: Konfirmasi data tersimpan
    deactivate L4
    
    L3-->>L2: Menampilkan pesan berhasil
    deactivate L3
    deactivate L2
    deactivate L1
```

---

### 3. Sequence Diagram Tindak Lanjut Tiket oleh Pengguna OPD

```mermaid
sequenceDiagram
    autonumber
    actor L1 as Pengguna OPD
    participant L2 as Antarmuka
    participant L3 as Pengontrol
    participant L4 as Basis Data

    activate L1
    L1->>L2: Mengakses detail tiket
    activate L2
    
    L2->>L3: Meminta data tiket
    activate L3
    
    L3->>L4: Mengambil data
    activate L4
    L4-->>L3: Mengembalikan data
    deactivate L4
    
    L3-->>L2: Menampilkan detail tiket
    deactivate L3
    
    L1->>L2: Mengirim tanggapan & pembaruan status
    
    L2->>L3: Meneruskan data tanggapan
    activate L3
    
    L3->>L4: Menyimpan riwayat penanganan
    activate L4
    L4-->>L3: Konfirmasi tersimpan
    deactivate L4
    
    L3-->>L2: Menampilkan pesan berhasil
    deactivate L3
    deactivate L2
    deactivate L1
```

---

### 4. Sequence Diagram Eskalasi Prioritas Otomatis

```mermaid
sequenceDiagram
    autonumber
    participant L1 as Penjadwal Sistem
    participant L2 as Command
    participant L3 as Model Tiket
    participant L4 as Basis Data

    activate L1
    L1->>L2: Memicu pengecekan berkala (Setiap 30 menit)
    activate L2
    
    L2->>L4: Meminta daftar tiket aktif yang belum direspon
    activate L4
    L4-->>L2: Mengembalikan data tiket
    deactivate L4
    
    L2->>L3: Memproses SLA
    activate L3
    
    Note right of L3: Memeriksa apakah tiket<br>melewati batas SLA
    
    L3->>L3: Memperbarui tingkat prioritas & SLA baru (Eskalasi)
    
    L3->>L4: Menyimpan perubahan tiket & mencatat status log
    activate L4
    L4-->>L3: Konfirmasi pembaruan
    deactivate L4
    
    L3-->>L2: Status pembaruan berhasil
    deactivate L3
    
    deactivate L2
    deactivate L1
```
