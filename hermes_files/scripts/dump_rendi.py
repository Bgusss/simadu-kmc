from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Laporan Tugas Akhir_Rendiansyah_3042022029.docx")
print("=== DUMP RENDIANSYAH STARTING FROM P781 ===")
for i in range(781, 840):
    t = d.paragraphs[i].text.strip()
    if t:
        print(f"P{i}: {t}")
