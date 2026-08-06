from pathlib import Path
from docx import Document

SOURCE = Path(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN_REVISI_UML_RENDIANSYAH_FIX.docx")
OUTPUT = Path(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN_REVISI_UML_RENDIANSYAH_FINAL.docx")

def main() -> None:
    doc = Document(SOURCE)
    
    for p in doc.paragraphs:
        t = p.text
        
        # 1. Perbaikan Gambar 3.6 Login (semula tertulis 3.13)
        if "[ Sisipkan Gambar 3.13 — Activity Diagram Login" in t:
            p.text = "[ Sisipkan Gambar 3.6 — Activity Diagram Login dan Logout (GAMBAR_3_6_ACTIVITY_LOGIN) ]"
            
        # 2. Perbaikan Gambar 3.7 Tiket Manual (semula tertulis 3.10)
        elif "[ Sisipkan Gambar 3.10 — Activity Diagram Pembuatan" in t:
            p.text = "[ Sisipkan Gambar 3.7 — Activity Diagram Pembuatan Tiket Manual (GAMBAR_3_7_ACTIVITY_TIKET_MANUAL) ]"
            
        # 3. Perbaikan Gambar 3.8 Manajemen OPD (semula tertulis 3.11)
        elif "[ Sisipkan Gambar 3.11 — Activity Diagram Manajemen" in t:
            p.text = "[ Sisipkan Gambar 3.8 — Activity Diagram Manajemen Data dan Akun OPD (GAMBAR_3_8_ACTIVITY_MANAJEMEN_OPD) ]"
            
        # 4. Perbaikan Gambar 3.9 Pelacakan Publik (semula tertulis 3.12)
        elif "[ Sisipkan Gambar 3.12 — Activity Diagram Pelacakan" in t:
            p.text = "[ Sisipkan Gambar 3.9 — Activity Diagram Pelacakan Tiket oleh Masyarakat (GAMBAR_3_9_ACTIVITY_PELACAKAN_PUBLIK) ]"
            
        # 5. Perbaikan teks dalam deskripsi Class Diagram (P94)
        elif "Class diagram pada Gambar 3.12 menggambarkan struktur" in t:
            p.text = "Class diagram pada Gambar 3.14 menggambarkan struktur kelas utama yang mendukung sistem. Kelas pengguna terhubung dengan OPD sesuai peran pengguna. Notifikasi berkaitan dengan hasil klasifikasi dan dapat menghasilkan tiket. Tiket terhubung dengan OPD tujuan, tanggapan tiket, serta riwayat perubahan status. Rancangan ini digunakan sebagai acuan struktur objek pada proses pengembangan sistem."
            
    doc.save(OUTPUT)
    print(f"Saved: {OUTPUT}")

if __name__ == "__main__":
    main()