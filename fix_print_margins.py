import re

p = 'system/resources/views/laporan_kegiatan/print.blade.php'
with open(p, 'r', encoding='utf-8') as f:
    text = f.read()

# Fix the duplicate braces left by the patch
text = text.replace("        }\n        }\n\n        .table-borderless td:first-child {", "        }\n\n        .table-borderless td:first-child {")

# Fix the nested .value-field styles inside @media print that got messed up
bad_media_block = """            .value-field {
                padding: 8px 12px !important;
                vertical-align: top !important;
                border: none !important;
                .value-field p, .value-field ul, .value-field ol {
                    margin: 0 !important;
                    padding: 0 !important;
                    line-height: 1.2 !important;
                }
                .table-borderless td {
                    border: none !important;
                    padding: 0px 4px; /* Sangat rapat */
                    font-family: 'Times New Roman', Times, serif;
                    font-size: 11pt;
                    vertical-align: top;
                    line-height: 1.2 !important;
                }"""

good_media_block = """            .value-field {
                padding: 8px 12px !important;
                vertical-align: top !important;
                border: none !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                hyphens: auto !important;
                line-height: 1.5 !important;
            }
            .value-field p, .value-field ul, .value-field ol {
                margin: 0 !important;
                padding: 0 !important;
                line-height: 1.2 !important;
            }
            .table-borderless td {
                border: none !important;
                padding: 0px 4px; /* Sangat rapat */
                font-family: 'Times New Roman', Times, serif;
                font-size: 11pt;
                vertical-align: top;
                line-height: 1.2 !important;
            }"""

text = text.replace(bad_media_block, good_media_block)

# Override the remaining 1.6 and 1.5 line heights to 1.2 and remove padding inside value-field container 
text = re.sub(r'line-height: 1\.[56] !important;', 'line-height: 1.2 !important;', text)
text = re.sub(r'padding: 8px 12px !important;', 'padding: 0px 4px !important;', text)

with open(p, 'w', encoding='utf-8') as f:
    f.write(text)

print("Applied aggressive margin/padding/line-height resets.")
