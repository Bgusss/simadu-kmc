from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN_REVISI_FINAL_UML.docx")
print("=== VERIFIKASI SEKUENS FINAL DI WORD ===")
for i, p in enumerate(d.paragraphs):
    t = p.text.strip()
    if "Gambar" in t or "Sisipkan Gambar" in t:
        print(f"P{i}: {t}")
