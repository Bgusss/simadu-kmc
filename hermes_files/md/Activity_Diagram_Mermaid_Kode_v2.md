# Kode Mermaid.js Activity Diagram — Swimlane per Aktor (v2)

Berikut adalah kode Mermaid.js untuk keenam Activity Diagram, dihasilkan sesuai spesifikasi pada `Prompt_activity_revisi_v2.md`. Setiap diagram menggunakan `flowchart LR` sebagai root, dengan `subgraph` per aktor (`direction TB` di dalamnya) untuk mensimulasikan swimlane.

---

## 1. Activity Diagram Pengolahan Aduan dari Media Sosial
**Aktor:** Sistem, Admin KMC

```mermaid
flowchart LR
    subgraph Sistem1["Sistem"]
        direction TB
        A1([Mulai])
        A2["Sinkronisasi Aduan Facebook dan Instagram"]
        A3["Simpan Data Mention"]
        D1{"Pesan spam?"}
        A4["Pesan Tidak Diproses<br/>(Notifikasi dan tiket tidak dibuat)"]
        A5["Simpan Notifikasi"]
        A6["Klasifikasi AI<br/>(Kategori, OPD, Prioritas)"]
        D2{"Kemungkinan duplikasi?"}
        A7["Buat Tiket<br/>(Nomor lacak, OPD, SLA)"]
        A8["Tandai Kandidat Duplikasi<br/>(Menunggu verifikasi Admin KMC)"]
        E1([Selesai])
        E2([Selesai])
    end

    subgraph AdminKMC1["Admin KMC"]
        direction TB
        D3{"Dikonfirmasi duplikat?"}
        A9["Konfirmasi Duplikat<br/>(Tiket tidak dibuat)"]
        E3([Selesai])
    end

    A1 --> A2 --> A3 --> D1
    D1 -- Ya --> A4 --> E1
    D1 -- Tidak --> A5 --> A6 --> D2
    D2 -- Tidak --> A7 --> E2
    D2 -- Ya --> A8 --> D3
    D3 -- Ya --> A9 --> E3
    D3 -- Tidak --> A7
```

---

## 2. Activity Diagram Tindak Lanjut Tiket dan Eskalasi SLA
**Aktor:** OPD, Sistem

```mermaid
flowchart LR
    subgraph Sistem2["Sistem"]
        direction TB
        B1([Mulai])
        B2["Tiket Dibuat<br/>(Status: diterima, OPD ditetapkan)"]
        D1{"Ada respons OPD sebelum SLA?"}
        B3["Simpan Tanggapan dan Perbarui Status<br/>(Riwayat penanganan dicatat)"]
        B4["Proses Disposisi<br/>(Tiket masuk proses disposisi, Batas waktu diperbarui)"]
        D2{"Ada respons OPD pada SLA baru?"}
        B5["Eskalasi Tiket<br/>(Prioritas dinaikkan, SLA diatur kembali)"]
        B7["Perbarui Status Selesai"]
        E1([Selesai])
    end

    subgraph OPD2["OPD"]
        direction TB
        C1["Tiket Diteruskan atau Dibaca oleh OPD<br/>(Batas waktu penanganan ditetapkan)"]
        D3{"Tiket selesai?"}
        C2["Lanjut Tindak Lanjut oleh OPD"]
    end

    B1 --> B2 --> C1
    C1 --> D1
    D1 -- Ya --> B3
    D1 -- Tidak --> B4 --> D2
    D2 -- Ya --> B3
    D2 -- Tidak --> B5 --> D2
    B3 --> D3
    D3 -- Tidak --> C2 --> C1
    D3 -- Ya --> B7 --> E1
```

---

## 3. Activity Diagram Login dan Logout
**Aktor:** Pengguna (Admin/OPD), Sistem

```mermaid
flowchart LR
    subgraph Pengguna3["Pengguna (Admin/OPD)"]
        direction TB
        P1([Mulai])
        P2["Mengakses Halaman Login"]
        P3["Memasukkan Email dan Kata Sandi"]
        P4["Menekan Tombol Logout"]
    end

    subgraph Sistem3["Sistem"]
        direction TB
        D1{"Kredensial valid?"}
        D2{"Peran (Role)?"}
        S1["Menampilkan Dashboard Admin KMC"]
        S2["Menampilkan Dashboard OPD"]
        S3["Mengakhiri Sesi"]
        E1([Selesai])
    end

    P1 --> P2 --> P3 --> D1
    D1 -- Tidak --> P2
    D1 -- Ya --> D2
    D2 -- Admin --> S1
    D2 -- OPD --> S2
    S1 --> P4
    S2 --> P4
    P4 --> S3 --> E1
```

---

## 4. Activity Diagram Pembuatan Tiket Manual
**Aktor:** Admin, Sistem

```mermaid
flowchart LR
    subgraph Admin4["Admin"]
        direction TB
        M1([Mulai])
        M2["Mengakses Formulir Buat Tiket"]
        M3["Mengisi Data Pelapor, Isi Aduan,<br/>Kategori, Prioritas, dan OPD"]
        M4["Menyimpan Tiket"]
    end

    subgraph Sistem4["Sistem"]
        direction TB
        D1{"Data valid dan lengkap?"}
        S1["Menampilkan Pesan Kesalahan"]
        S2["Menghasilkan Nomor Pelacakan<br/>dan Menetapkan SLA"]
        S3["Menyimpan Data Tiket ke Basis Data"]
        S4["Meneruskan Tiket ke Dashboard OPD Tujuan"]
        E1([Selesai])
    end

    M1 --> M2 --> M3 --> M4 --> D1
    D1 -- Tidak --> S1 --> M3
    D1 -- Ya --> S2 --> S3 --> S4 --> E1
```

---

## 5. Activity Diagram Manajemen Data dan Akun OPD
**Aktor:** Admin, Sistem

```mermaid
flowchart LR
    subgraph Admin5["Admin"]
        direction TB
        A1([Mulai])
        A2["Mengakses Halaman Manajemen OPD"]
        D1{"Pilih Tindakan?"}
        A3["Mengisi Formulir Data Instansi<br/>dan Kredensial Akun"]
        A4["Mengubah Data Instansi<br/>atau Kredensial Akun"]
        A5["Menekan Tombol Hapus OPD"]
        A6["Menyimpan Data"]
        D3{"Konfirmasi Hapus?"}
    end

    subgraph Sistem5["Sistem"]
        direction TB
        D2{"Validasi Data?"}
        S1["Memperbarui Data OPD di Basis Data"]
        S2["Menghapus Data OPD dari Basis Data"]
        E1([Selesai])
        E2([Selesai])
        E3([Selesai])
    end

    A1 --> A2 --> D1
    D1 -- Tambah --> A3 --> A6
    D1 -- Ubah --> A4 --> A6
    D1 -- Hapus --> A5 --> D3
    A6 --> D2
    D2 -- Tidak --> A3
    D2 -- Tidak --> A4
    D2 -- Ya --> S1 --> E1
    D3 -- Batal --> E2
    D3 -- Ya --> S2 --> E3
```

---

## 6. Activity Diagram Pelacakan Tiket oleh Masyarakat
**Aktor:** Masyarakat, Sistem

```mermaid
flowchart LR
    subgraph Masyarakat6["Masyarakat"]
        direction TB
        M1([Mulai])
        M2["Mengakses Portal Publik SIMADU-KMC"]
        M3["Memasukkan Nomor Pelacakan (Resi)"]
    end

    subgraph Sistem6["Sistem"]
        direction TB
        S1["Mencari Data Tiket di Basis Data"]
        D1{"Nomor pelacakan ditemukan?"}
        S2["Menampilkan Pesan Tiket Tidak Ditemukan"]
        S3["Menampilkan Informasi Tiket, Status,<br/>OPD, dan Riwayat Penanganan"]
        E1([Selesai])
        E2([Selesai])
    end

    M1 --> M2 --> M3 --> S1 --> D1
    D1 -- Tidak --> S2 --> E1
    D1 -- Ya --> S3 --> E2
```

---

**Catatan implementasi:**
- Semua node keputusan (`{...}`) dan proses (`[...]`) dibungkus tanda kutip ganda agar tanda kurung, koma, dan tanda tanya di dalam label tidak memicu galat sintaks Mermaid.
- Pada Diagram 5, karena Mermaid tidak dapat mengarahkan panah balik secara kondisional ke jalur asal (Tambah/Ubah), panah "Tidak" dari `Validasi Data?` digambar rangkap menuju kedua node formulir (`Mengisi Formulir...` dan `Mengubah Data...`).
- ID subgraph diberi akhiran angka (mis. `Sistem1`, `Sistem2`) hanya agar tidak bentrok bila beberapa diagram digabung ke satu file `.mmd`; bila tiap diagram dirender terpisah, akhiran ini boleh dihapus.
