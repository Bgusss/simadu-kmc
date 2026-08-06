# Kode Mermaid.js untuk 6 Activity Diagram SIMADU-KMC

Gunakan kode-kode di bawah ini pada [Mermaid Live Editor](https://mermaid.live) atau plugin Markdown Anda untuk menghasilkan visualisasi yang siap digunakan.

---

### 1. Activity Diagram Pengolahan Aduan dari Media Sosial

```mermaid
stateDiagram-v2
    direction TB
    
    [*] --> Sinkronisasi : Mulai
    Sinkronisasi : Sinkronisasi Aduan Facebook dan Instagram
    Sinkronisasi --> SimpanMention
    SimpanMention : Simpan Data Mention
    SimpanMention --> CekSpam
    
    state CekSpam <<choice>>
    CekSpam --> PesanDitolak : Ya
    CekSpam --> SimpanNotifikasi : Tidak
    
    PesanDitolak : Pesan Tidak Diproses (Notifikasi dan tiket tidak dibuat)
    PesanDitolak --> [*] : Selesai
    
    SimpanNotifikasi : Simpan Notifikasi
    SimpanNotifikasi --> KlasifikasiAI
    KlasifikasiAI : Klasifikasi AI (Kategori, OPD, Prioritas)
    KlasifikasiAI --> CekDuplikasi
    
    state CekDuplikasi <<choice>>
    CekDuplikasi --> BuatTiket : Tidak
    CekDuplikasi --> TandaiKandidat : Ya
    
    TandaiKandidat : Tandai Kandidat Duplikasi (Menunggu verifikasi Admin KMC)
    TandaiKandidat --> KonfirmasiDuplikat
    
    state KonfirmasiDuplikat <<choice>>
    KonfirmasiDuplikat --> TiketDitolak : Ya
    KonfirmasiDuplikat --> BuatTiket : Tidak
    
    TiketDitolak : Konfirmasi Duplikat (Tiket tidak dibuat)
    TiketDitolak --> [*] : Selesai
    
    BuatTiket : Buat Tiket (Nomor lacak, OPD, SLA)
    BuatTiket --> [*] : Selesai
```

---

### 2. Activity Diagram Tindak Lanjut Tiket dan Eskalasi SLA

```mermaid
stateDiagram-v2
    direction TB
    
    [*] --> TiketDibuat : Mulai
    TiketDibuat : Tiket Dibuat (Status diterima, OPD ditetapkan)
    TiketDibuat --> TiketDiteruskan
    
    TiketDiteruskan : Tiket Diteruskan atau Dibaca oleh OPD (Batas waktu penanganan ditetapkan)
    TiketDiteruskan --> CekResponsAwal
    
    state CekResponsAwal <<choice>>
    CekResponsAwal --> SimpanTanggapan : Ya
    CekResponsAwal --> ProsesDisposisi : Tidak
    
    ProsesDisposisi : Proses Disposisi (Tiket masuk proses disposisi, Batas waktu diperbarui)
    ProsesDisposisi --> CekResponsSLA
    
    state CekResponsSLA <<choice>>
    CekResponsSLA --> SimpanTanggapan : Ya
    CekResponsSLA --> Eskalasi : Tidak
    
    Eskalasi : Eskalasi Tiket (Prioritas dinaikkan, SLA diatur kembali)
    Eskalasi --> CekResponsSLA
    
    SimpanTanggapan : Simpan Tanggapan dan Perbarui Status (Riwayat penanganan dicatat)
    SimpanTanggapan --> CekSelesai
    
    state CekSelesai <<choice>>
    CekSelesai --> LanjutTindakLanjut : Tidak
    CekSelesai --> StatusSelesai : Ya
    
    LanjutTindakLanjut : Lanjut Tindak Lanjut oleh OPD
    LanjutTindakLanjut --> TiketDiteruskan
    
    StatusSelesai : Perbarui Status selesai
    StatusSelesai --> [*] : Selesai
```

---

### 3. Activity Diagram Login dan Logout

```mermaid
stateDiagram-v2
    direction TB
    
    [*] --> BukaLogin : Mulai
    BukaLogin : Mengakses Halaman Login
    BukaLogin --> IsiKredensial
    
    IsiKredensial : Memasukkan Email dan Kata Sandi
    IsiKredensial --> CekValid
    
    state CekValid <<choice>>
    CekValid --> BukaLogin : Tidak
    CekValid --> CekRole : Ya
    
    state CekRole <<choice>>
    CekRole --> DashboardAdmin : Admin
    CekRole --> DashboardOPD : OPD
    
    DashboardAdmin : Menampilkan Dashboard Admin KMC
    DashboardOPD : Menampilkan Dashboard OPD
    
    DashboardAdmin --> TekanLogout
    DashboardOPD --> TekanLogout
    
    TekanLogout : Menekan Tombol Logout
    TekanLogout --> AkhiriSesi
    AkhiriSesi : Mengakhiri Sesi
    AkhiriSesi --> [*] : Selesai
```

---

### 4. Activity Diagram Pembuatan Tiket Manual

```mermaid
stateDiagram-v2
    direction TB
    
    [*] --> BukaForm : Mulai
    BukaForm : Mengakses Formulir Buat Tiket
    BukaForm --> IsiData
    
    IsiData : Mengisi Data Pelapor, Isi Aduan, Kategori, Prioritas, dan OPD
    IsiData --> SimpanTiket
    
    SimpanTiket : Menyimpan Tiket
    SimpanTiket --> CekValidData
    
    state CekValidData <<choice>>
    CekValidData --> TampilError : Tidak
    CekValidData --> GenerateLacak : Ya
    
    TampilError : Menampilkan Pesan Kesalahan
    TampilError --> IsiData
    
    GenerateLacak : Menghasilkan Nomor Pelacakan dan Menetapkan SLA
    GenerateLacak --> SimpanDB
    
    SimpanDB : Menyimpan Data Tiket ke Basis Data
    SimpanDB --> TeruskanOPD
    
    TeruskanOPD : Meneruskan Tiket ke Dashboard OPD Tujuan
    TeruskanOPD --> [*] : Selesai
```

---

### 5. Activity Diagram Manajemen Data dan Akun OPD

```mermaid
stateDiagram-v2
    direction TB
    
    [*] --> BukaManajemen : Mulai
    BukaManajemen : Mengakses Halaman Manajemen OPD
    BukaManajemen --> PilihTindakan
    
    state PilihTindakan <<choice>>
    PilihTindakan --> IsiFormTambah : Tambah
    PilihTindakan --> IsiFormUbah : Ubah
    PilihTindakan --> HapusOPD : Hapus
    
    IsiFormTambah : Mengisi Formulir Data Instansi dan Kredensial Akun
    IsiFormUbah : Mengubah Data Instansi atau Kredensial Akun
    HapusOPD : Menekan Tombol Hapus OPD
    
    IsiFormTambah --> ProsesSimpan
    IsiFormUbah --> ProsesSimpan
    
    ProsesSimpan : Menyimpan Data
    ProsesSimpan --> ValidasiForm
    
    state ValidasiForm <<choice>>
    ValidasiForm --> IsiFormUbah : Tidak (Kembali ke form)
    ValidasiForm --> UpdateDB : Ya
    
    UpdateDB : Memperbarui Data OPD di Basis Data
    UpdateDB --> [*] : Selesai
    
    HapusOPD --> KonfirmasiHapus
    
    state KonfirmasiHapus <<choice>>
    KonfirmasiHapus --> [*] : Batal
    KonfirmasiHapus --> DeleteDB : Ya
    
    DeleteDB : Menghapus Data OPD dari Basis Data
    DeleteDB --> [*] : Selesai
```

---

### 6. Activity Diagram Pelacakan Tiket oleh Masyarakat

```mermaid
stateDiagram-v2
    direction TB
    
    [*] --> BukaPortal : Mulai
    BukaPortal : Mengakses Portal Publik SIMADU-KMC
    BukaPortal --> IsiResi
    
    IsiResi : Memasukkan Nomor Pelacakan (Resi)
    IsiResi --> CariTiket
    
    CariTiket : Mencari Data Tiket di Basis Data
    CariTiket --> CekKetemu
    
    state CekKetemu <<choice>>
    CekKetemu --> TidakKetemu : Tidak
    CekKetemu --> Ketemu : Ya
    
    TidakKetemu : Menampilkan Pesan Tiket Tidak Ditemukan
    TidakKetemu --> [*] : Selesai
    
    Ketemu : Menampilkan Informasi Tiket, Status, OPD, dan Riwayat Penanganan
    Ketemu --> [*] : Selesai
```