from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Laporan Tugas Akhir_Rendiansyah_3042022029.docx")
print("=== DAFTAR HEADING DIAGRAM RENDIANSYAH ===")
for p in d.paragraphs:
    t = p.text.strip()
    if t.startswith(("Activity Diagram", "Sequence Diagram")):
        if len(t) < 100:
            print(t)
