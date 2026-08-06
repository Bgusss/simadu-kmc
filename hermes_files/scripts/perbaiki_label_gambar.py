from pathlib import Path
from docx import Document

FILE_PATH = Path(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN_REVISI_ACTIVITY_LENGKAP.docx")
OUTPUT_PATH = Path(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN_REVISI_FINAL_UML.docx")

def main() -> None:
    doc = Document(FILE_PATH)
    
    # Map label gambar/penanda yang salah akibat otomatisasi regex python-docx sebelumnya
    # Gambar 3.6 -> Login (GAMBAR_3_6_ACTIVITY_LOGIN)
    # Gambar 3.7 -> Tiket Manual (GAMBAR_3_7_ACTIVITY_TIKET_MANUAL)
    # Gambar 3.8 -> OPD (GAMBAR_3_8_ACTIVITY_MANAJEMEN_OPD)
    # Gambar 3.9 -> Pelacakan (GAMBAR_3_9_ACTIVITY_PELACAKAN_PUBLIK)
    # Gambar 3.10 -> Sequence Pengolahan (GAMBAR_3_10_SEQUENCE_PENGOLAHAN_ADUAN)
    # Gambar 3.11 -> Sequence Tindak Lanjut (GAMBAR_3_11_SEQUENCE_TINDAK_LANJUT_TIKET)
    # Gambar 3.12 -> Class Diagram (GAMBAR_3_12_CLASS_DIAGRAM)
    # Gambar 3.13 -> ERD (GAMBAR_3_13_ERD)
    
    for p in doc.paragraphs:
        t = p.text
        
        # 1. Perbaikan Gambar 3.6 Login
        if "[ Sisipkan Gambar 3.13 — Activity Diagram Login" in t:
            p.text = "[ Sisipkan Gambar 3.6 — Activity Diagram Login dan Logout (GAMBAR_3_6_ACTIVITY_LOGIN) ]"
        
        # 2. Perbaikan Gambar 3.7 Tiket Manual
        elif "[ Sisipkan Gambar 3.10 — Activity Diagram Pembuatan" in t:
            p.text = "[ Sisipkan Gambar 3.7 — Activity Diagram Pembuatan Tiket Manual (GAMBAR_3_7_ACTIVITY_TIKET_MANUAL) ]"
            
        # 3. Perbaikan Gambar 3.8 Manajemen OPD
        elif "[ Sisipkan Gambar 3.11 — Activity Diagram Manajemen" in t:
            p.text = "[ Sisipkan Gambar 3.8 — Activity Diagram Manajemen Data dan Akun OPD (GAMBAR_3_8_ACTIVITY_MANAJEMEN_OPD) ]"
            
        # 4. Perbaikan Gambar 3.9 Pelacakan Publik
        elif "[ Sisipkan Gambar 3.12 — Activity Diagram Pelacakan" in t:
            p.text = "[ Sisipkan Gambar 3.9 — Activity Diagram Pelacakan Tiket oleh Masyarakat (GAMBAR_3_9_ACTIVITY_PELACAKAN_PUBLIK) ]"
            
        # 5. Perbaikan Gambar 3.10 Sequence Pengolahan
        elif "[ Sisipkan Gambar 3.10 — Sequence Diagram Pengolahan" in t:
            p.text = "[ Sisipkan Gambar 3.10 — Sequence Diagram Pengolahan Aduan (GAMBAR_3_10_SEQUENCE_PENGOLAHAN_ADUAN) ]"
            
        # 6. Perbaikan Gambar 3.11 Sequence Tindak Lanjut
        elif "[ Sisipkan Gambar 3.11 — Sequence Diagram Tindak Lanjut" in t:
            p.text = "[ Sisipkan Gambar 3.11 — Sequence Diagram Tindak Lanjut Tiket oleh Pengguna OPD (GAMBAR_3_11_SEQUENCE_TINDAK_LANJUT_TIKET) ]"
            
        # 7. Perbaikan Gambar 3.12 Class Diagram
        elif "[ Sisipkan Gambar 3.12 — Class Diagram" in t:
            p.text = "[ Sisipkan Gambar 3.12 — Class Diagram (GAMBAR_3_12_CLASS_DIAGRAM) ]"
            
        # 8. Perbaikan Gambar 3.13 ERD
        elif "[ Sisipkan Gambar 3.13 — Entity Relationship" in t:
            p.text = "[ Sisipkan Gambar 3.13 — Entity Relationship Diagram (GAMBAR_3_13_ERD) ]"
            
    doc.save(OUTPUT_PATH)
    print(f"Saved: {OUTPUT_PATH}")

if __name__ == "__main__":
    main()