from pathlib import Path
from zipfile import ZipFile
from xml.etree import ElementTree as ET

DOCX = Path(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN.docx")
NS = {"w": "http://schemas.openxmlformats.org/wordprocessingml/2006/main"}


def text(element):
    return "".join(node.text or "" for node in element.findall(".//w:t", NS))


with ZipFile(DOCX) as archive:
    root = ET.fromstring(archive.read("word/document.xml"))

for index, table in enumerate(root.findall(".//w:tbl", NS), start=1):
    print(f"TABLE {index}")
    for row_index, row in enumerate(table.findall("./w:tr", NS), start=1):
        cells = [text(cell).strip() for cell in row.findall("./w:tc", NS)]
        print(f"  R{row_index}: {cells}")
    print()

print("PARAGRAPHS 100-180")
paragraphs = root.findall(".//w:body/w:p", NS)
for index, paragraph in enumerate(paragraphs, start=1):
    value = text(paragraph).strip()
    if value:
        print(f"P{index}: {value}")
