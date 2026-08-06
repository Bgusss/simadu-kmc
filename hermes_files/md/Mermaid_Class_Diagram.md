# Kode Mermaid.js untuk Class Diagram SIMADU-KMC

Gunakan kode di bawah ini pada [Mermaid Live Editor](https://mermaid.live) atau plugin Markdown Anda untuk menghasilkan visualisasi **Class Diagram** yang akurat, dengan semua relasi yang terhubung dengan benar.

```mermaid
classDiagram
    %% Definisi Kelas User
    class User {
        - integer id
        - integer opd_id
        - string name
        - string username
        - string email
        - string role
        - string profile_photo
        + opd()
        + isAdmin()
        + isOpd()
    }

    %% Definisi Kelas OPD
    class Opd {
        - integer id
        - string name
        - timestamp created_at
        - timestamp updated_at
        + users()
        + tickets()
        + subCategories()
    }

    %% Definisi Kelas Category
    class Category {
        - integer id
        - string name
        + subCategories()
    }

    %% Definisi Kelas SubCategory
    class SubCategory {
        - integer id
        - integer category_id
        - integer opd_id
        - string name
        + category()
        + opd()
    }

    %% Definisi Kelas Notification
    class Notification {
        - integer id
        - integer duplicate_of_id
        - string title
        - text message
        - string sender
        - string permalink
        - boolean is_read
        - string duplicate_status
        + ticket()
        + aiClassification()
        + duplicates()
    }

    %% Definisi Kelas AIClassification
    class AIClassification {
        - integer id
        - integer notification_id
        - string suggested_category
        - json suggested_opds
        - string priority
        - float confidence
        + notification()
    }

    %% Definisi Kelas Ticket
    class Ticket {
        - integer id
        - integer notification_id
        - integer assigned_opd_id
        - string ticket_number
        - string tracking_number
        - string platform
        - string reporter_name
        - text complaint
        - string priority
        - string status
        - timestamp sla_deadline
        + notification()
        + assignedOpd()
        + statusLogs()
        + responses()
        + updateStatus()
    }

    %% Definisi Kelas TicketStatusLog
    class TicketStatusLog {
        - integer id
        - integer ticket_id
        - integer changed_by
        - string from_status
        - string to_status
        - text note
        + ticket()
        + user()
    }

    %% Definisi Kelas TicketResponse
    class TicketResponse {
        - integer id
        - integer ticket_id
        - integer user_id
        - text message
        - string attachment
        + ticket()
        + user()
    }

    %% ----------------------------------------------------
    %% DEFINISI RELASI & MULTIPLISITAS
    %% ----------------------------------------------------
    
    %% OPD ke Entitas Lain
    Opd "1" --> "0..*" User : memiliki
    Opd "1" --> "0..*" Ticket : ditugaskan ke
    Opd "1" --> "0..*" SubCategory : menangani

    %% Category ke SubCategory
    Category "1" --> "0..*" SubCategory : mencakup
    
    %% Ticket & Relasi Utama
    Notification "1" --> "0..1" Ticket : menghasilkan
    Ticket "1" *-- "0..*" TicketStatusLog : mencatat riwayat
    Ticket "1" *-- "0..*" TicketResponse : memiliki tanggapan
    
    %% AI & Duplikasi
    Notification "1" *-- "0..1" AIClassification : dianalisis oleh
    Notification "1" --> "0..*" Notification : duplicate of
    
    %% Relasi User ke Log & Response (yang sempat terlewat di gambar lama)
    User "1" --> "0..*" TicketStatusLog : diubah oleh
    User "1" --> "0..*" TicketResponse : dijawab oleh
```

---

### Perbaikan dalam Kode Mermaid Ini:
1. **Semua Hubungan Lengkap:** Saya sudah menghubungkan kelas `User` ke `TicketStatusLog` dan `TicketResponse` (karena log dan tanggapan dicatat oleh pengguna tertentu).
2. **Relasi Diri Sendiri (Self-Referencing):** Saya menambahkan relasi `Notification` ke `Notification` sendiri untuk menampung fungsi duplikasi aduan (`duplicate_of_id`).
3. **Simbol UML Standar PBO:** 
   *   `-` (Private) untuk semua kolom/atribut database.
   *   `+` (Public) untuk semua metode/fungsi.
   *   `*--` untuk *Composition* (Misal: Riwayat Log dan Tanggapan mutlak bergantung pada Tiket; jika Tiket dihapus, Log-nya juga dihapus).
   *   `-->` untuk *Association* biasa.
