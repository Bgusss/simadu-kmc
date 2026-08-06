# Kode Mermaid.js untuk Entity Relationship Diagram (ERD) SIMADU-KMC

Gunakan kode di bawah ini pada [Mermaid Live Editor](https://mermaid.live) atau plugin Markdown yang mendukung Mermaid.js untuk menghasilkan visualisasi **ERD** dengan notasi Crow's Foot.

```mermaid
erDiagram
    %% Definisi Entitas dan Atribut
    
    users {
        bigint id PK
        bigint opd_id FK
        varchar name
        varchar username
        varchar email
        varchar password
        enum role
        varchar profile_photo
        timestamp created_at
        timestamp updated_at
    }

    opds {
        bigint id PK
        varchar name
        timestamp created_at
        timestamp updated_at
    }

    categories {
        bigint id PK
        varchar name
        timestamp created_at
        timestamp updated_at
    }

    sub_categories {
        bigint id PK
        bigint category_id FK
        bigint opd_id FK
        varchar name
        timestamp created_at
        timestamp updated_at
    }

    notifications {
        bigint id PK
        bigint duplicate_of_id FK
        varchar title
        text message
        varchar sender
        varchar permalink
        boolean is_read
        varchar duplicate_status
        timestamp created_at
        timestamp updated_at
    }

    ai_classifications {
        bigint id PK
        bigint notification_id FK
        varchar suggested_category
        varchar suggested_sub_category
        json suggested_opds
        enum priority
        decimal confidence
        text reasoning
        timestamp created_at
        timestamp updated_at
    }

    tickets {
        bigint id PK
        bigint notification_id FK
        bigint assigned_opd_id FK
        varchar ticket_number
        varchar tracking_number
        varchar platform
        varchar reporter_name
        text complaint
        enum priority
        enum status
        timestamp sla_deadline
        timestamp created_at
        timestamp updated_at
    }

    ticket_responses {
        bigint id PK
        bigint ticket_id FK
        bigint user_id FK
        text message
        varchar attachment
        timestamp created_at
        timestamp updated_at
    }

    ticket_status_logs {
        bigint id PK
        bigint ticket_id FK
        bigint changed_by FK
        varchar from_status
        varchar to_status
        text note
        timestamp created_at
    }

    %% ----------------------------------------------------
    %% DEFINISI RELASI (Crow's Foot Notation)
    %% ----------------------------------------------------
    
    opds ||--o{ users : "memiliki"
    opds ||--o{ sub_categories : "menangani"
    categories ||--o{ sub_categories : "mencakup"
    opds ||--o{ tickets : "ditugaskan ke"
    
    notifications ||--o| tickets : "menghasilkan"
    notifications ||--o| ai_classifications : "dianalisis oleh"
    notifications ||--o{ notifications : "duplicate of"
    
    tickets ||--o{ ticket_status_logs : "mencatat"
    tickets ||--o{ ticket_responses : "memiliki"
    
    users ||--o{ ticket_status_logs : "mengubah"
    users ||--o{ ticket_responses : "menjawab"
```

---

### Penjelasan Notasi Relasi (Crow's Foot):
*   `||--o{` : Relasi **Satu-ke-Banyak** *(One-to-Many)*. Contoh: Satu OPD (`||`) memiliki banyak Users (`o{`).
*   `||--o|` : Relasi **Satu-ke-Nol/Satu** *(One-to-Zero or One)*. Contoh: Satu Notifikasi (`||`) menghasilkan paling banyak satu Tiket (`o|`).

Kode ini sudah mencakup setiap atribut dengan tipe datanya, serta penanda **PK (Primary Key)** dan **FK (Foreign Key)** yang akurat sesuai migrasi basis data sistem Anda.
