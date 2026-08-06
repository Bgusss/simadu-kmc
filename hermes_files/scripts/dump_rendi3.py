from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Laporan Tugas Akhir_Rendiansyah_3042022029.docx")
print("=== DUMP HEADING SETELAH P971 ===")
for i in range(971, 1020):
    t = d.paragraphs[i].text.strip()
    if t:
        print(f"P{i}: {t}")
