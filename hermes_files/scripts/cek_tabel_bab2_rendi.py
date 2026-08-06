from docx import Document
d = Document(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Laporan Tugas Akhir_Rendiansyah_3042022029.docx")
print("=== DUMP BAB II TABLES IN RENDI ===")
for i, table in enumerate(d.tables):
    # Check if this table is in Bab II
    # Typically table 0 to 12 are in Bab II or early Bab III
    first_cell_text = table.cell(0, 0).text.strip()
    # Check if the parent of table is in BAB II
    # We can just print the header
    header = [c.text.strip().replace('\n', ' ') for c in table.rows[0].cells[:4]]
    print(f"Table {i}: {header}")
