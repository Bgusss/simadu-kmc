from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN_REVISI_UML_LENGKAP.docx")
print("=== DUMP USER WORD ===")
for i, p in enumerate(d.paragraphs):
    t = p.text.strip()
    if '3.2' in t:
        print(f"P{i}: {t}")
