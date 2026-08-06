from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Laporan Tugas Akhir_Rendiansyah_3042022029.docx")
print("=== CEK PARAGRAPH 993 DAN SEKITARNYA ===")
for j in range(988, 996):
    print(f"P{j}: {d.paragraphs[j].text.strip()}")
