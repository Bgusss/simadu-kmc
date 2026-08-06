from copy import deepcopy
from pathlib import Path
from shutil import copy2
from zipfile import ZIP_DEFLATED, ZipFile
from xml.etree import ElementTree as ET

SOURCE = Path(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN.docx")
OUTPUT = Path(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\BAB_III_METODOLOGI_DAN_PERANCANGAN_REVISI_3_2.docx")
W_NS = "http://schemas.openxmlformats.org/wordprocessingml/2006/main"
NS = {"w": W_NS}


def qn(local_name: str) -> str:
    return f"{{{W_NS}}}{local_name}"


def paragraph_text(paragraph) -> str:
    return "".join(node.text or "" for node in paragraph.findall(".//w:t", NS))


def set_paragraph_text(paragraph, value: str) -> None:
    """Replace a paragraph while retaining its paragraph style and first run style."""
    paragraph_properties = paragraph.find("w:pPr", NS)
    first_run_properties = paragraph.find("w:r/w:rPr", NS)
    paragraph_properties = deepcopy(paragraph_properties) if paragraph_properties is not None else None
    first_run_properties = deepcopy(first_run_properties) if first_run_properties is not None else None

    for child in list(paragraph):
        paragraph.remove(child)

    if paragraph_properties is not None:
        paragraph.append(paragraph_properties)

    run = ET.SubElement(paragraph, qn("r"))
    if first_run_properties is not None:
        run.append(first_run_properties)
    text_element = ET.SubElement(run, qn("t"))
    if value.startswith(" ") or value.endswith(" "):
        text_element.set("{http://www.w3.org/XML/1998/namespace}space", "preserve")
    text_element.text = value


def replace_paragraph_starting(paragraphs, starts_with: str, replacement: str) -> None:
    matches = [paragraph for paragraph in paragraphs if paragraph_text(paragraph).strip().startswith(starts_with)]
    if len(matches) != 1:
        raise RuntimeError(f"Expected exactly one paragraph beginning with {starts_with!r}; found {len(matches)}")
    set_paragraph_text(matches[0], replacement)


def set_cell_text(cell, value: str) -> None:
    paragraphs = cell.findall("w:p", NS)
    if not paragraphs:
        paragraph = ET.SubElement(cell, qn("p"))
    else:
        paragraph = paragraphs[0]
        for extra in paragraphs[1:]:
            cell.remove(extra)
    set_paragraph_text(paragraph, value)


def set_row_text(row, values: list[str]) -> None:
    cells = row.findall("w:tc", NS)
    if len(cells) != len(values):
        raise RuntimeError(f"Expected {len(values)} cells, found {len(cells)}")
    for cell, value in zip(cells, values):
        set_cell_text(cell, value)


def rebuild_table(table, rows: list[list[str]]) -> None:
    existing_rows = table.findall("w:tr", NS)
    if not existing_rows:
        raise RuntimeError("Table has no rows")
    expected_columns = len(existing_rows[0].findall("w:tc", NS))
    if any(len(row) != expected_columns for row in rows):
        raise RuntimeError("Replacement row has unexpected number of columns")

    header = existing_rows[0]
    templates = existing_rows[1:]
    if not templates:
        raise RuntimeError("Table has no data-row template")
    template = templates[-1]

    for row in templates:
        table.remove(row)

    for values in rows:
        new_row = deepcopy(template)
        set_row_text(new_row, values)
        table.append(new_row)


def main() -> None:
    if not SOURCE.exists():
        raise FileNotFoundError(SOURCE)

    copy2(SOURCE, OUTPUT)

    with ZipFile(SOURCE, "r") as source_archive:
        files = {name: source_archive.read(name) for name in source_archive.namelist()}

    document_xml = files["word/document.xml"]
    namespaces = []
    for _, namespace_data in ET.iterparse(__import__("io").BytesIO(document_xml), events=("start-ns",)):
        if namespace_data not in namespaces:
            namespaces.append(namespace_data)
    for prefix, uri in namespaces:
        ET.register_namespace(prefix or "w", uri)

    root = ET.fromstring(document_xml)
    paragraphs = root.findall(".//w:body/w:p", NS)

    replace_paragraph_starting(
        paragraphs,
        "Arsitektur Sistem Informasi Manajemen Aduan Multi Channel KMC dirancang",
        "Arsitektur Sistem Informasi Manajemen Aduan Multi Channel KMC dirancang sebagai aplikasi web yang mengintegrasikan sumber aduan media sosial, proses pengambilan data, layanan AI, basis data, dan pengguna sistem. Rancangan ini menjadi acuan hubungan antarkomponen dalam pengelolaan aduan, mulai dari data diterima hingga tiket dapat ditindaklanjuti. Rancangan arsitektur ditunjukkan pada Gambar 3.2.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Aduan berasal dari Facebook",
        "Aduan berasal dari Facebook melalui mention pada postingan dan komentar, serta dari pesan langsung Instagram. Sistem dirancang untuk mengambil data aduan secara berkala, menyimpannya sebagai data masuk, kemudian memprosesnya pada aplikasi web dan basis data. Dengan rancangan tersebut, aduan dari beberapa kanal dapat dikelola dalam satu sistem.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Notifikasi yang masuk disaring",
        "Data aduan yang masuk dirancang untuk melalui penyaringan awal agar pesan yang tidak relevan tidak diteruskan ke proses penanganan. Aduan yang layak diproses diklasifikasikan dengan bantuan layanan AI untuk memperoleh kategori, subkategori, OPD tujuan, dan tingkat prioritas. Sistem juga memeriksa kemungkinan duplikasi terhadap aduan yang telah diproses. Aduan yang tidak terindikasi duplikat dapat dibuatkan tiket, sedangkan aduan yang terindikasi duplikat menunggu verifikasi Admin KMC.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Sistem dapat diakses oleh tiga jenis pengguna",
        "Sistem dirancang untuk diakses oleh tiga jenis pengguna, yaitu Admin KMC, Pengguna OPD, dan Masyarakat. Admin KMC dan Pengguna OPD menggunakan halaman yang dibatasi berdasarkan peran masing-masing. Masyarakat dapat menggunakan halaman publik untuk memantau informasi pengelolaan aduan dan melacak perkembangan tiket melalui nomor pelacakan tanpa perlu masuk ke sistem.",
    )
    replace_paragraph_starting(
        paragraphs,
        "3.2.2 Perancangan Arus Data",
        "3.2.2 Perancangan Proses Sistem",
    )
    replace_paragraph_starting(
        paragraphs,
        "Perancangan arus data menggunakan UML",
        "Perancangan proses sistem menggunakan diagram UML untuk menggambarkan interaksi aktor dan fungsi utama pada Sistem Informasi Manajemen Aduan Multi Channel KMC. Aktor utama terdiri dari Admin KMC, Pengguna OPD, dan Masyarakat. Proses sinkronisasi aduan serta layanan AI diposisikan sebagai komponen pendukung yang membantu pengolahan aduan secara otomatis. Rancangan use case sistem ditunjukkan pada Gambar 3.3.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Admin KMC dapat masuk ke dashboard",
        "Admin KMC dapat masuk ke dashboard, memantau notifikasi dan hasil klasifikasi, memverifikasi kemungkinan duplikasi, mengelola tiket, mengelola data serta akun OPD, melihat statistik, dan mengelola profil. Pengguna OPD dapat melihat dashboard dan tiket yang ditugaskan kepada OPD-nya, memberi tanggapan, memperbarui status tiket, serta mengelola profil. Masyarakat dapat melihat informasi monitoring publik serta melacak dan melihat perkembangan tiket melalui nomor pelacakan. Pengambilan data dari media sosial dan pengolahan awal aduan dilakukan melalui proses sinkronisasi sistem.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Alur pengolahan aduan dari media sosial ditunjukkan",
        "Alur pengolahan aduan dari media sosial ditunjukkan pada Gambar 3.4. Proses dimulai ketika sistem melakukan sinkronisasi data aduan dari kanal media sosial. Data sumber yang belum pernah direkam disimpan terlebih dahulu. Pesan kemudian diperiksa untuk menentukan kelayakannya sebagai aduan. Pesan yang tidak layak tidak diteruskan menjadi notifikasi dan tiket, sedangkan pesan yang layak disimpan sebagai notifikasi, diklasifikasikan dengan bantuan AI, dan diperiksa kemungkinan duplikasinya.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Jika tidak ada duplikasi, sistem membuat tiket",
        "Jika tidak ditemukan kemungkinan duplikasi, sistem membuat tiket dengan nomor pelacakan, menetapkan batas waktu penanganan, dan menentukan OPD tujuan berdasarkan hasil klasifikasi. Jika terdapat kemungkinan duplikasi, sistem menandai notifikasi untuk diverifikasi Admin KMC. Admin dapat mengonfirmasi bahwa notifikasi merupakan duplikat sehingga tiket tidak dibuat, atau menyatakan bahwa notifikasi bukan duplikat sehingga sistem dapat membuat tiket berdasarkan hasil klasifikasi yang tersedia.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Tiket yang baru dibuat dikaitkan dengan OPD tujuan",
        "Tiket yang baru dibuat dirancang untuk diteruskan kepada OPD tujuan. Pengguna OPD dapat membaca, memproses, memberi tanggapan, dan menyelesaikan tiket sesuai kewenangannya. Setiap perubahan status dicatat pada riwayat tiket. Apabila tiket yang telah diteruskan kepada OPD belum memperoleh respons hingga melewati batas waktu penanganan, sistem menetapkan tahap tindak lanjut berikutnya dan batas waktu baru. Jika tahap tersebut kembali melewati batas waktu, sistem mencatat eskalasi serta menaikkan prioritas tiket secara bertahap.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Data pengguna dihubungkan dengan data instansi OPD",
        "Data pengguna dihubungkan dengan data instansi OPD sesuai perannya. Setiap OPD dirancang memiliki satu akun pengguna untuk mengakses portal OPD. Data kategori dihubungkan dengan subkategori yang berkaitan dengan OPD tujuan. Setiap notifikasi dapat memiliki satu hasil klasifikasi AI dan satu tiket. Tiket dapat memiliki banyak tanggapan serta riwayat perubahan status. Relasi tersebut dirancang agar data tiket dapat ditelusuri kembali ke notifikasi asal dan hasil klasifikasinya.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Perancangan antar muka bertujuan",
        "Perancangan antarmuka bertujuan menyediakan halaman web yang mudah dipahami oleh setiap pengguna sesuai tugasnya. Halaman untuk Admin KMC dan Pengguna OPD dirancang dengan pembatasan akses berdasarkan peran, sedangkan halaman publik dapat diakses langsung untuk monitoring dan pelacakan tiket. Rancangan halaman antarmuka ditunjukkan pada Tabel 3.4.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Pada dashboard admin, notifikasi prioritas tinggi",
        "Pada dashboard Admin KMC, notifikasi prioritas tinggi dipisahkan dari notifikasi terbaru agar aduan mendesak memperoleh perhatian lebih cepat. Informasi pada dashboard dirancang untuk diperbarui secara berkala tanpa pengguna perlu memuat ulang halaman, sehingga perkembangan aduan dapat dipantau dengan lebih efektif.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Pada daftar tiket, setiap tiket ditampilkan",
        "Pada daftar tiket, setiap tiket dirancang memiliki penanda visual tingkat prioritas agar Admin KMC dan Pengguna OPD dapat mengenali urgensi penanganan tanpa harus membuka detail tiket satu per satu.",
    )
    replace_paragraph_starting(
        paragraphs,
        "Pengujian sistem dirancang menggunakan metode black box testing",
        "Pengujian sistem dirancang menggunakan metode black box testing. Metode ini berfokus pada kesesuaian keluaran sistem terhadap masukan dan kebutuhan fungsional tanpa menilai struktur kode program secara langsung. Perancangan pengujian mencakup fungsi autentikasi, pengelolaan aduan, proses klasifikasi dan duplikasi, manajemen tiket, hak akses, batas waktu penanganan, serta layanan publik. Pendekatan ini digunakan untuk memastikan setiap fungsi utama sistem berjalan sesuai harapan (Mustaqbal et al., 2015).",
    )

    tables = root.findall(".//w:tbl", NS)
    if len(tables) != 5:
        raise RuntimeError(f"Expected five tables, found {len(tables)}")

    rebuild_table(tables[1], [
        ["Admin KMC", "Login, melihat dashboard dan statistik, memantau notifikasi serta hasil klasifikasi, memverifikasi duplikasi, mengelola tiket, mengelola data dan akun OPD, serta mengelola profil."],
        ["Pengguna OPD", "Login, melihat dashboard dan tiket milik OPD-nya, memberi tanggapan, memperbarui status tiket, serta mengelola profil."],
        ["Masyarakat", "Melihat informasi monitoring publik serta melacak dan melihat perkembangan tiket melalui nomor pelacakan."],
        ["Sistem scraper", "Melakukan sinkronisasi data aduan dari Facebook dan Instagram sebagai masukan sistem."],
        ["Layanan AI", "Mendukung penyaringan pesan, klasifikasi aduan, dan pemeriksaan kemungkinan duplikasi."],
    ])

    rebuild_table(tables[2], [
        ["Halaman Login", "Admin KMC dan Pengguna OPD", "Memverifikasi email atau username serta kata sandi, kemudian mengarahkan pengguna ke halaman sesuai peran."],
        ["Dashboard Admin", "Admin KMC", "Menampilkan ringkasan jumlah tiket, notifikasi terbaru, notifikasi prioritas tinggi, tren aduan, dan distribusi sumber aduan."],
        ["Daftar Notifikasi", "Admin KMC", "Menampilkan aduan masuk, hasil klasifikasi, status baca, dan status kemungkinan duplikasi serta menyediakan pencarian dan penyaringan data."],
        ["Manajemen Tiket", "Admin KMC", "Menampilkan daftar dan detail tiket serta mendukung pembuatan, perubahan data, penugasan OPD, dan penghapusan tiket."],
        ["Manajemen OPD", "Admin KMC", "Menambah, mengubah, dan menghapus data OPD beserta akun Pengguna OPD."],
        ["Profil Admin", "Admin KMC", "Mengubah informasi akun, kata sandi, dan foto profil Admin KMC."],
        ["Dashboard OPD", "Pengguna OPD", "Menampilkan ringkasan tiket yang ditugaskan kepada OPD pengguna serta daftar tiket terbaru."],
        ["Tiket OPD", "Pengguna OPD", "Menampilkan daftar dan detail tiket OPD, riwayat status, formulir tanggapan, lampiran, serta pembaruan status tiket."],
        ["Profil OPD", "Pengguna OPD", "Mengubah informasi akun, kata sandi, dan foto profil Pengguna OPD."],
        ["Portal Monitoring dan Pelacakan Publik", "Masyarakat", "Menampilkan informasi monitoring aduan serta memungkinkan pencarian dan penelusuran detail tiket berdasarkan nomor pelacakan."],
    ])

    rebuild_table(tables[3], [
        ["1", "Login Admin", "Admin memasukkan kredensial yang benar.", "Sistem mengarahkan Admin KMC ke dashboard admin."],
        ["2", "Login OPD", "Pengguna OPD memasukkan kredensial yang benar.", "Sistem mengarahkan pengguna ke dashboard OPD."],
        ["3", "Validasi Login", "Pengguna memasukkan kredensial yang salah atau tidak lengkap.", "Sistem menolak login dan menampilkan pesan validasi."],
        ["4", "Otorisasi", "Pengguna OPD membuka halaman khusus Admin KMC.", "Sistem menolak akses ke halaman tersebut."],
        ["5", "Sinkronisasi Facebook", "Data mention Facebook baru diperoleh sistem.", "Data sumber disimpan satu kali dan pesan layak diproses menjadi notifikasi."],
        ["6", "Sinkronisasi Instagram", "Data pesan Instagram baru diperoleh sistem.", "Data sumber disimpan satu kali dan pesan layak diproses menjadi notifikasi."],
        ["7", "Pencegahan Data Ganda", "Sistem memproses tautan atau pesan yang telah direkam.", "Sistem tidak membuat data sumber atau notifikasi ganda."],
        ["8", "Penyaringan Pesan", "Pesan hanya berisi emoji, reaksi singkat, promosi, atau tidak relevan.", "Pesan tidak diteruskan menjadi notifikasi dan tiket."],
        ["9", "Klasifikasi Aduan", "Aduan yang layak diproses dikirim ke layanan AI.", "Sistem menyimpan hasil kategori, subkategori, OPD tujuan, prioritas, tingkat kepercayaan, dan alasan klasifikasi."],
        ["10", "Fallback Klasifikasi", "Layanan AI gagal atau mengembalikan hasil yang tidak valid.", "Sistem menggunakan aturan alternatif sehingga proses pengolahan tetap dapat dilanjutkan."],
        ["11", "Deteksi Duplikasi", "Aduan baru memiliki masalah, lokasi, dan objek yang sama dengan aduan yang sudah memiliki tiket.", "Notifikasi ditandai sebagai kemungkinan duplikasi dan menunggu verifikasi Admin KMC."],
        ["12", "Konfirmasi Duplikasi", "Admin KMC menyatakan notifikasi sebagai duplikat.", "Notifikasi diarsipkan dan sistem tidak membuat tiket baru."],
        ["13", "Konfirmasi Bukan Duplikat", "Admin KMC menyatakan notifikasi bukan duplikat.", "Sistem membuat tiket berdasarkan hasil klasifikasi yang tersedia."],
        ["14", "Pembuatan Tiket Otomatis", "Aduan layak diproses dan tidak terdeteksi sebagai duplikat.", "Sistem membuat nomor pelacakan, tiket, batas waktu penanganan, dan riwayat status awal."],
        ["15", "Pembuatan Tiket Manual", "Admin KMC mengisi data tiket baru secara manual.", "Sistem menyimpan tiket, nomor pelacakan, OPD tujuan, dan riwayat status awal."],
        ["16", "Pengelolaan Tiket", "Admin KMC mengubah kategori, subkategori, OPD tujuan, atau prioritas tiket.", "Sistem menyimpan perubahan data tiket dan penugasan OPD."],
        ["17", "Tanggapan OPD", "Pengguna OPD mengirimkan tanggapan yang valid.", "Sistem menyimpan tanggapan dan memperbarui status tiket apabila tiket belum ditutup."],
        ["18", "Pembatasan Tiket OPD", "Pengguna OPD membuka tiket milik OPD lain.", "Sistem menolak akses ke tiket tersebut."],
        ["19", "Manajemen OPD", "Admin KMC menambah, mengubah, atau menghapus data OPD beserta akun pengguna OPD.", "Sistem menyimpan perubahan data OPD dan akun pengguna sesuai validasi."],
        ["20", "Profil Pengguna", "Admin KMC atau Pengguna OPD memperbarui informasi profilnya.", "Sistem menyimpan perubahan profil sesuai validasi."],
        ["21", "SLA Tahap Pertama", "Tiket yang telah diteruskan kepada OPD belum memperoleh respons hingga melewati batas waktu penanganan.", "Sistem menetapkan tahap disposisi dan batas waktu penanganan berikutnya."],
        ["22", "Eskalasi SLA", "Tiket pada tahap disposisi kembali melewati batas waktu penanganan.", "Sistem mencatat eskalasi dan menaikkan prioritas tiket sesuai aturan."],
        ["23", "Monitoring Publik", "Masyarakat membuka halaman monitoring publik.", "Sistem menampilkan ringkasan informasi pengelolaan aduan yang dapat diakses publik."],
        ["24", "Pelacakan Publik", "Masyarakat memasukkan nomor pelacakan yang tersedia atau tidak tersedia.", "Sistem menampilkan detail tiket yang sesuai atau pesan bahwa tiket tidak ditemukan."],
    ])

    files["word/document.xml"] = ET.tostring(root, encoding="utf-8", xml_declaration=True)
    with ZipFile(OUTPUT, "w", ZIP_DEFLATED) as output_archive:
        for name, content in files.items():
            output_archive.writestr(name, content)

    print(f"Revised document created: {OUTPUT}")


if __name__ == "__main__":
    main()
