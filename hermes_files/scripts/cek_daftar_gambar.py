from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Laporan Tugas Akhir_Rendiansyah_3042022029.docx")
print("=== CARI DARI DAFTAR GAMBAR ===")
# Let's inspect the first 150 paragraphs for list of figures (Daftar Gambar)
for i in range(210, 275):
    t = d.paragraphs[i].text.strip()
    if t:
        print(f"P{i}: {t}")
