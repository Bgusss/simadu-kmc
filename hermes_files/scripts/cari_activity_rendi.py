from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Laporan Tugas Akhir_Rendiansyah_3042022029.docx")
print("=== DUMP HEADING SEBELUM P892 ===")
for i, p in enumerate(d.paragraphs):
    t = p.text.strip()
    # Cari Activity Diagram di Rendiansyah
    if 'Activity Diagram' in t and not t.startswith('Gambar'):
        print(f"P{i}: {t}")
