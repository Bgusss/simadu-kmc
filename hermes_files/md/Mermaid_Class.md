# Kode Mermaid.js untuk Class Diagram SIMADU-KMC

Gunakan kode di bawah ini pada [Mermaid Live Editor](https://mermaid.live) atau plugin Markdown Anda untuk memvisualisasikan Class Diagram Sistem secara cepat.

```mermaid
classDiagram
    class User {
        -int id
        -string name
        -string username
        -string email
        -enum role
        -int opd_id
        +isAdmin() bool
        +isOpd() bool
    }

    class Opd {
        -int id
        -string name
    }

    class Ticket {
        -int id
        -int notification_id
        -string ticket_number
        -string tracking_number
        -datetime ticket_time
        -string platform
        -string category
        -string sub_category
        -string status
        -int assigned_opd_id
        -string priority
        -datetime sla_deadline
        +updateStatus()
    }

    class Notification {
        -int id
        -string title
        -string sender
        -text message
        -string permalink
        -bool is_read
        -string duplicate_status
    }

    class AIClassification {
        -int id
        -int notification_id
        -string suggested_category
        -string suggested_sub_category
        -string priority
        -float confidence
    }

    class TicketResponse {
        -int id
        -int ticket_id
        -int user_id
        -text message
        -string attachment
    }

    class TicketStatusLog {
        -int id
        -int ticket_id
        -string from_status
        -string to_status
        -int changed_by
        -text note
    }

    %% Relationships
    User "0..*" --> "0..1" Opd : belongs to
    Ticket "0..*" --> "1" Opd : assigned to
    Ticket "0..1" --> "1" Notification : derived from
    Notification "1" --> "0..1" AIClassification : analyzed by
    Ticket "1" --> "0..*" TicketResponse : has response
    Ticket "1" --> "0..*" TicketStatusLog : logs status
    TicketResponse "0..*" --> "1" User : authored by
    TicketStatusLog "0..*" --> "0..1" User : changed by
```
