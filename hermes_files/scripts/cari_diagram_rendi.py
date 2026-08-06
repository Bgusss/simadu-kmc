from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Laporan Tugas Akhir_Rendiansyah_3042022029.docx")
print("=== CARI HEADING LAIN DI UML RENDIANSYAH ===")
for i, p in enumerate(d.paragraphs):
    t = p.text.strip()
    if i > 838:
        # Cari kata kunci Sequence Diagram, Class Diagram, dll
        if any(x in t for x in ['Sequence Diagram', 'Class Diagram', 'Usecase Scenario', 'Use Case Scenario']):
            print(f"P{i}: {t}")
