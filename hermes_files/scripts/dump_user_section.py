from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN_REVISI_UML_LENGKAP.docx")
print("=== DUMP BETWEEN P42 AND P64 ===")
for i in range(42, 65):
    t = d.paragraphs[i].text.strip()
    # print if not empty or even if empty to see exact paragraph indices
    print(f"P{i}: {t}")
