from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN_REVISI_UML_LENGKAP.docx")
print("=== DUMP ALL PARAGRAPHS FROM SOURCE ===")
for i, p in enumerate(d.paragraphs):
    t = p.text.strip()
    if t:
        print(f"P{i}: {t}")
