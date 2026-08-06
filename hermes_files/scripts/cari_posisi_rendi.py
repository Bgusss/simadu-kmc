from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Laporan Tugas Akhir_Rendiansyah_3042022029.docx")
found = False
for i, p in enumerate(d.paragraphs):
    t = p.text.strip()
    if 'Perancangan Arus Data' in t or '3.2.3' in t:
        print(f"P{i}: {t}")
        found = True
if not found:
    print("Tidak ditemukan perancangan arus data secara persis.")
