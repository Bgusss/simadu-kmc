import os
import xml.etree.ElementTree as ET

# Definisi gaya standar yang mirip dengan file user
STYLE_SWIMLANE = "swimlane;whiteSpace=wrap;html=1;fontSize=20;startSize=40;"
STYLE_RECT = "html=1;whiteSpace=wrap;strokeWidth=1;fillColor=light-dark(#ECECFF,#1f2020);strokeColor=light-dark(#9370DB,#cccccc);fontColor=light-dark(#333333,#cccccc);fontFamily=Trebuchet MS,Verdana,Arial,sans-serif;fontSize=16;"
STYLE_RHOMBUS = "rhombus;html=1;strokeWidth=1;whiteSpace=wrap;fillColor=light-dark(#ECECFF,#1f2020);strokeColor=light-dark(#9370DB,#cccccc);fontColor=light-dark(#333333,#cccccc);fontFamily=Trebuchet MS,Verdana,Arial,sans-serif;fontSize=16;"
STYLE_START = "ellipse;html=1;shape=startState;fillColor=#000000;strokeColor=#ff0000;"
STYLE_END = "ellipse;html=1;shape=endState;fillColor=#000000;strokeColor=#ff0000;"
STYLE_EDGE = "edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;fontSize=16;fontFamily=Trebuchet MS,Verdana,Arial,sans-serif;fontColor=light-dark(#333333,#cccccc);strokeColor=light-dark(#333333,#cccccc);"

def create_base_xml(diagram_name):
    mxfile = ET.Element("mxfile", host="app.diagrams.net")
    diagram = ET.SubElement(mxfile, "diagram", name=diagram_name, id="diagram-" + diagram_name.replace(" ", "-"))
    model = ET.SubElement(diagram, "mxGraphModel", dx="2292", dy="1833", grid="1", gridSize="10", guides="1", tooltips="1", connect="1", arrows="1", fold="1", page="1", pageScale="1", pageWidth="850", pageHeight="1100", math="0", shadow="0")
    root = ET.SubElement(model, "root")
    
    # Base layers
    ET.SubElement(root, "mxCell", id="0")
    ET.SubElement(root, "mxCell", id="1", parent="0")
    return mxfile, root

def add_swimlane(root, parent_id, id_, value, x, y, w, h):
    cell = ET.SubElement(root, "mxCell", id=id_, parent=parent_id, style=STYLE_SWIMLANE, value=value, vertex="1")
    geo = ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width=str(w), height=str(h))
    geo.set("as", "geometry")
    return cell

def add_rect(root, parent_id, id_, value, x, y, w, h):
    uo = ET.SubElement(root, "UserObject", label=value, id=id_)
    cell = ET.SubElement(uo, "mxCell", parent=parent_id, style=STYLE_RECT, vertex="1")
    geo = ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width=str(w), height=str(h))
    geo.set("as", "geometry")
    return uo

def add_rhombus(root, parent_id, id_, value, x, y, w, h):
    uo = ET.SubElement(root, "UserObject", label=value, id=id_)
    cell = ET.SubElement(uo, "mxCell", parent=parent_id, style=STYLE_RHOMBUS, vertex="1")
    geo = ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width=str(w), height=str(h))
    geo.set("as", "geometry")
    return uo

def add_start(root, parent_id, id_, x, y):
    cell = ET.SubElement(root, "mxCell", id=id_, parent=parent_id, style=STYLE_START, value="", vertex="1")
    geo = ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width="42", height="42")
    geo.set("as", "geometry")
    return cell

def add_end(root, parent_id, id_, x, y):
    cell = ET.SubElement(root, "mxCell", id=id_, parent=parent_id, style=STYLE_END, value="", vertex="1")
    geo = ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width="42", height="42")
    geo.set("as", "geometry")
    return cell

def add_edge(root, parent_id, id_, value, source_id, target_id, style_ext=""):
    style = STYLE_EDGE + style_ext
    if value:
        uo = ET.SubElement(root, "UserObject", label=value, id=id_)
        cell = ET.SubElement(uo, "mxCell", edge="1", parent=parent_id, source=source_id, target=target_id, style=style)
        geo = ET.SubElement(cell, "mxGeometry", relative="1")
        geo.set("as", "geometry")
        return uo
    else:
        cell = ET.SubElement(root, "mxCell", id=id_, edge="1", parent=parent_id, source=source_id, target=target_id, style=style)
        geo = ET.SubElement(cell, "mxGeometry", relative="1")
        geo.set("as", "geometry")
        return cell

def save_xml(mxfile, filename):
    tree = ET.ElementTree(mxfile)
    ET.indent(tree, space="  ", level=0)
    path = os.path.join(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments", filename)
    with open(path, "wb") as f:
        f.write(b'<?xml version="1.0" encoding="UTF-8"?>\n')
        tree.write(f, encoding="utf-8")
    print(f"Generated: {path}")

# ==========================================
# 1. Tindak Lanjut Tiket & Eskalasi SLA
# ==========================================
def make_tindak_lanjut():
    mx, root = create_base_xml("Page-1")
    
    # Swimlanes
    add_swimlane(root, "1", "lane-opd", "Pengguna OPD", 1250, 30, 320, 1080)
    add_swimlane(root, "1", "lane-sis", "Sistem", 1570, 30, 570, 1080)
    
    # lane-sis nodes
    add_start(root, "lane-sis", "sis-start", 264, 40)
    add_rect(root, "lane-sis", "sis-dibuat", "Tiket Dibuat\n(Status: diterima\nOPD ditetapkan)", 195, 120, 180, 75)
    add_rect(root, "lane-sis", "sis-teruskan", "Tiket Diteruskan atau Dibaca\noleh OPD (Batas waktu SLA\npenanganan ditetapkan)", 160, 230, 250, 75)
    add_rhombus(root, "lane-sis", "sis-respons-sla", "Ada respons OPD\nsebelum SLA?", 215, 340, 140, 140)
    add_rect(root, "lane-sis", "sis-disposisi", "Proses Disposisi\n(Tiket masuk proses disposisi,\nBatas waktu diperbarui)", 380, 370, 170, 80)
    add_rhombus(root, "lane-sis", "sis-respons-sla2", "Ada respons OPD\npada SLA baru?", 395, 490, 140, 140)
    add_rect(root, "lane-sis", "sis-eskalasi", "Eskalasi Tiket\n(Prioritas dinaikkan,\nSLA diatur kembali)", 380, 670, 170, 80)
    add_rect(root, "lane-sis", "sis-simpan", "Simpan Tanggapan dan\nPerbarui Status\n(Riwayat penanganan dicatat)", 175, 680, 220, 75)
    add_rhombus(root, "lane-sis", "sis-selesai", "Tiket selesai?", 215, 790, 140, 140)
    add_rect(root, "lane-sis", "sis-tutup", "Perbarui Status selesai", 195, 965, 180, 60)
    add_end(root, "lane-sis", "sis-end", 264, 1045)
    
    # lane-opd nodes
    add_rect(root, "lane-opd", "opd-buka", "Membuka Tiket dan\nMembaca Detail", 70, 380, 180, 60)
    add_rect(root, "lane-opd", "opd-tindak", "Mengisi Tanggapan &\nMemperbarui Status", 60, 480, 200, 60)
    add_rect(root, "lane-opd", "opd-lanjut", "Lanjut Tiket dan Tindak\nLanjut oleh OPD", 70, 830, 180, 60)
    
    # Connections (parent="1" to allow cross-lane routing)
    add_edge(root, "1", "e1", "", "sis-start", "sis-dibuat")
    add_edge(root, "1", "e2", "", "sis-dibuat", "sis-teruskan")
    add_edge(root, "1", "e3", "", "sis-teruskan", "sis-respons-sla")
    
    # respons-sla -> opd-buka (Ya)
    add_edge(root, "1", "e4", "Ya", "sis-respons-sla", "opd-buka", ";exitX=0;exitY=0.5;entryX=1;entryY=0.5;")
    # respons-sla -> sis-disposisi (Tidak)
    add_edge(root, "1", "e5", "Tidak", "sis-respons-sla", "sis-disposisi", ";exitX=1;exitY=0.5;entryX=0;entryY=0.5;")
    
    add_edge(root, "1", "e6", "", "sis-disposisi", "sis-respons-sla2")
    
    # respons-sla2 -> opd-buka (Ya)
    add_edge(root, "1", "e7", "Ya", "sis-respons-sla2", "opd-buka", ";exitX=0;exitY=0.5;entryX=1;entryY=0.5;")
    # respons-sla2 -> sis-eskalasi (Tidak)
    add_edge(root, "1", "e8", "Tidak", "sis-respons-sla2", "sis-eskalasi", ";exitX=0.5;exitY=1;entryX=0.5;entryY=0;")
    
    # loops from eskalasi back to respons-sla2
    add_edge(root, "1", "e9", "", "sis-eskalasi", "sis-respons-sla2", ";exitX=1;exitY=0.5;entryX=1;entryY=0.5;edgeStyle=orthogonalEdgeStyle;rounded=0;")
    
    add_edge(root, "1", "e10", "", "opd-buka", "opd-tindak")
    add_edge(root, "1", "e11", "", "opd-tindak", "sis-simpan", ";exitX=0.5;exitY=1;entryX=0;entryY=0.5;edgeStyle=orthogonalEdgeStyle;")
    
    add_edge(root, "1", "e12", "", "sis-simpan", "sis-selesai")
    
    # selesai -> opd-lanjut (Tidak)
    add_edge(root, "1", "e13", "Tidak", "sis-selesai", "opd-lanjut", ";exitX=0;exitY=0.5;entryX=1;entryY=0.5;")
    # selesai -> sis-tutup (Ya)
    add_edge(root, "1", "e14", "Ya", "sis-selesai", "sis-tutup", ";exitX=0.5;exitY=1;entryX=0.5;entryY=0;")
    
    add_edge(root, "1", "e15", "", "opd-lanjut", "sis-teruskan", ";exitX=0.5;exitY=0;entryX=0;entryY=0.5;edgeStyle=orthogonalEdgeStyle;rounded=0;")
    add_edge(root, "1", "e16", "", "sis-tutup", "sis-end")
    
    save_xml(mx, "Activity Diagram Tindak Lanjut Tiket dan Eskalasi SLA.drawio")

# ==========================================
# 2. Login dan Logout
# ==========================================
def make_login_logout():
    mx, root = create_base_xml("Page-1")
    add_swimlane(root, "1", "lane-usr", "Pengguna (Admin/OPD)", 1250, 30, 320, 1000)
    add_swimlane(root, "1", "lane-sis", "Sistem", 1570, 30, 450, 1000)
    
    # usr
    add_start(root, "lane-usr", "usr-start", 139, 40)
    add_rect(root, "lane-usr", "usr-akses", "Mengakses Halaman Login", 65, 120, 190, 60)
    add_rect(root, "lane-usr", "usr-input", "Memasukkan Email\ndan Kata Sandi", 60, 220, 200, 60)
    add_rect(root, "lane-usr", "usr-db-admin", "Menampilkan Dashboard\nAdmin KMC", 60, 520, 200, 60)
    add_rect(root, "lane-usr", "usr-db-opd", "Menampilkan Dashboard\nOPD", 60, 620, 200, 60)
    add_rect(root, "lane-usr", "usr-logout", "Menekan Tombol Logout", 70, 740, 180, 60)
    
    # sis
    add_rhombus(root, "lane-sis", "sis-valid", "Kredensial\nvalid?", 155, 220, 140, 140)
    add_rhombus(root, "lane-sis", "sis-role", "Peran\n(Role)?", 155, 410, 140, 140)
    add_rect(root, "lane-sis", "sis-out", "Mengakhiri Sesi", 135, 740, 180, 60)
    add_end(root, "lane-sis", "sis-end", 204, 860)
    
    # Edges
    add_edge(root, "1", "e1", "", "usr-start", "usr-akses")
    add_edge(root, "1", "e2", "", "usr-akses", "usr-input")
    add_edge(root, "1", "e3", "", "usr-input", "sis-valid", ";exitX=1;exitY=0.5;entryX=0;entryY=0.5;")
    
    # valid -> usr-akses (Tidak)
    add_edge(root, "1", "e4", "Tidak", "sis-valid", "usr-akses", ";exitX=0.5;exitY=0;entryX=1;entryY=0.5;edgeStyle=orthogonalEdgeStyle;")
    # valid -> sis-role (Ya)
    add_edge(root, "1", "e5", "Ya", "sis-valid", "sis-role")
    
    # role -> usr-db-admin (Admin)
    add_edge(root, "1", "e6", "Admin", "sis-role", "usr-db-admin", ";exitX=0;exitY=0.5;entryX=1;entryY=0.5;")
    # role -> usr-db-opd (OPD)
    add_edge(root, "1", "e7", "OPD", "sis-role", "usr-db-opd", ";exitX=0.5;exitY=1;entryX=1;entryY=0.5;edgeStyle=orthogonalEdgeStyle;")
    
    add_edge(root, "1", "e8", "", "usr-db-admin", "usr-logout")
    add_edge(root, "1", "e9", "", "usr-db-opd", "usr-logout")
    add_edge(root, "1", "e10", "", "usr-logout", "sis-out", ";exitX=1;exitY=0.5;entryX=0;entryY=0.5;")
    add_edge(root, "1", "e11", "", "sis-out", "sis-end")
    
    save_xml(mx, "Activity Diagram Login dan Logout.drawio")

# ==========================================
# 3. Pembuatan Tiket Manual
# ==========================================
def make_tiket_manual():
    mx, root = create_base_xml("Page-1")
    add_swimlane(root, "1", "lane-adm", "Admin KMC", 1250, 30, 320, 1050)
    add_swimlane(root, "1", "lane-sis", "Sistem", 1570, 30, 450, 1050)
    
    # adm
    add_start(root, "lane-adm", "adm-start", 139, 40)
    add_rect(root, "lane-adm", "adm-akses", "Mengakses Formulir\nBuat Tiket", 65, 120, 190, 60)
    add_rect(root, "lane-adm", "adm-isi", "Mengisi Data Pelapor,\nIsi Aduan, Kategori,\nPrioritas, dan OPD", 50, 220, 220, 75)
    add_rect(root, "lane-adm", "adm-simpan", "Menekan Tombol Simpan", 70, 340, 180, 60)
    
    # sis
    add_rhombus(root, "lane-sis", "sis-valid", "Data valid\ndan lengkap?", 155, 340, 140, 140)
    add_rect(root, "lane-sis", "sis-error", "Menampilkan Pesan Kesalahan", 135, 520, 180, 60)
    add_rect(root, "lane-sis", "sis-gen", "Menghasilkan Nomor Pelacakan\ndan Menetapkan SLA", 115, 620, 220, 70)
    add_rect(root, "lane-sis", "sis-db", "Menyimpan Data Tiket\nke Basis Data", 125, 730, 200, 60)
    add_rect(root, "lane-sis", "sis-opd", "Meneruskan Tiket ke\nDashboard OPD Tujuan", 115, 830, 220, 60)
    add_end(root, "lane-sis", "sis-end", 204, 940)
    
    # Edges
    add_edge(root, "1", "e1", "", "adm-start", "adm-akses")
    add_edge(root, "1", "e2", "", "adm-akses", "adm-isi")
    add_edge(root, "1", "e3", "", "adm-isi", "adm-simpan")
    add_edge(root, "1", "e4", "", "adm-simpan", "sis-valid", ";exitX=1;exitY=0.5;entryX=0;entryY=0.5;")
    
    # valid -> error (Tidak)
    add_edge(root, "1", "e5", "Tidak", "sis-valid", "sis-error")
    add_edge(root, "1", "e6", "", "sis-error", "adm-isi", ";exitX=0.5;exitY=0;entryX=1;entryY=0.5;edgeStyle=orthogonalEdgeStyle;")
    
    # valid -> gen (Ya)
    add_edge(root, "1", "e7", "Ya", "sis-valid", "sis-gen", ";exitX=0.5;exitY=1;entryX=0.5;entryY=0;")
    add_edge(root, "1", "e8", "", "sis-gen", "sis-db")
    add_edge(root, "1", "e9", "", "sis-db", "sis-opd")
    add_edge(root, "1", "e10", "", "sis-opd", "sis-end")
    
    save_xml(mx, "Activity Diagram Pembuatan Tiket Manual.drawio")

# ==========================================
# 4. Manajemen Data dan Akun OPD
# ==========================================
def make_manajemen_opd():
    mx, root = create_base_xml("Page-1")
    add_swimlane(root, "1", "lane-adm", "Admin KMC", 1250, 30, 320, 1050)
    add_swimlane(root, "1", "lane-sis", "Sistem", 1570, 30, 450, 1050)
    
    # adm
    add_start(root, "lane-adm", "adm-start", 139, 40)
    add_rect(root, "lane-adm", "adm-akses", "Mengakses Halaman\nManajemen OPD", 55, 120, 210, 60)
    add_rhombus(root, "lane-adm", "adm-pilih", "Pilih Tindakan?", 90, 220, 140, 140)
    add_rect(root, "lane-adm", "adm-tambah", "Mengisi Formulir Data\nInstansi dan Kredensial", 50, 400, 220, 75)
    add_rect(root, "lane-adm", "adm-ubah", "Mengubah Data Instansi\natau Kredensial Akun", 50, 500, 220, 75)
    add_rect(root, "lane-adm", "adm-hapus", "Menekan Tombol Hapus OPD", 60, 610, 200, 60)
    
    # sis
    add_rect(root, "lane-sis", "sis-simpan", "Menyimpan Data", 135, 450, 180, 60)
    add_rhombus(root, "lane-sis", "sis-valid", "Validasi Data?", 155, 550, 140, 140)
    add_rect(root, "lane-sis", "sis-update", "Memperbarui Data OPD\ndi Basis Data", 125, 730, 200, 60)
    add_rhombus(root, "lane-sis", "sis-konf", "Konfirmasi Hapus?", 155, 830, 140, 140)
    add_rect(root, "lane-sis", "sis-delete", "Menghapus Data OPD\ndari Basis Data", 125, 930, 200, 60)
    add_end(root, "lane-sis", "sis-end", 204, 1000)
    
    # Edges
    add_edge(root, "1", "e1", "", "adm-start", "adm-akses")
    add_edge(root, "1", "e2", "", "adm-akses", "adm-pilih")
    
    add_edge(root, "1", "e3", "Tambah", "adm-pilih", "adm-tambah")
    add_edge(root, "1", "e4", "Ubah", "adm-pilih", "adm-ubah", ";exitX=0.5;exitY=1;entryX=0.5;entryY=0;")
    add_edge(root, "1", "e5", "Hapus", "adm-pilih", "adm-hapus", ";exitX=0;exitY=0.5;entryX=0.5;entryY=0;edgeStyle=orthogonalEdgeStyle;")
    
    add_edge(root, "1", "e6", "", "adm-tambah", "sis-simpan", ";exitX=1;exitY=0.5;entryX=0.5;entryY=0;edgeStyle=orthogonalEdgeStyle;")
    add_edge(root, "1", "e7", "", "adm-ubah", "sis-simpan", ";exitX=1;exitY=0.5;entryX=0.5;entryY=0.5;edgeStyle=orthogonalEdgeStyle;")
    
    add_edge(root, "1", "e8", "", "sis-simpan", "sis-valid")
    
    # valid -> adm-ubah (Tidak)
    add_edge(root, "1", "e9", "Tidak", "sis-valid", "adm-ubah", ";exitX=0;exitY=0.5;entryX=1;entryY=0.5;")
    # valid -> update (Ya)
    add_edge(root, "1", "e10", "Ya", "sis-valid", "sis-update", ";exitX=0.5;exitY=1;entryX=0.5;entryY=0;")
    
    add_edge(root, "1", "e11", "", "adm-hapus", "sis-konf", ";exitX=1;exitY=0.5;entryX=0.5;entryY=0;edgeStyle=orthogonalEdgeStyle;")
    
    # konf -> delete (Ya)
    add_edge(root, "1", "e12", "Ya", "sis-konf", "sis-delete")
    # konf -> end (Batal)
    add_edge(root, "1", "e13", "Batal", "sis-konf", "sis-end", ";exitX=1;exitY=0.5;entryX=1;entryY=0.5;edgeStyle=orthogonalEdgeStyle;")
    
    add_edge(root, "1", "e14", "", "sis-update", "sis-end")
    add_edge(root, "1", "e15", "", "sis-delete", "sis-end")
    
    save_xml(mx, "Activity Diagram Data dan Akun OPD.drawio")

# ==========================================
# 5. Pelacakan Tiket oleh Masyarakat
# ==========================================
def make_pelacakan_publik():
    mx, root = create_base_xml("Page-1")
    add_swimlane(root, "1", "lane-mas", "Masyarakat", 1250, 30, 320, 800)
    add_swimlane(root, "1", "lane-sis", "Sistem", 1570, 30, 450, 800)
    
    # mas
    add_start(root, "lane-mas", "mas-start", 139, 40)
    add_rect(root, "lane-mas", "mas-akses", "Mengakses Portal Publik\nSIMADU-KMC", 55, 120, 210, 60)
    add_rect(root, "lane-mas", "mas-input", "Memasukkan Nomor\nPelacakan (Resi)", 60, 220, 200, 60)
    
    # sis
    add_rect(root, "lane-sis", "sis-cari", "Mencari Data Tiket\ndi Basis Data", 125, 220, 200, 60)
    add_rhombus(root, "lane-sis", "sis-temu", "Nomor pelacakan\nditemukan?", 155, 330, 140, 140)
    add_rect(root, "lane-sis", "sis-no-find", "Menampilkan Pesan Tiket\nTidak Ditemukan", 115, 520, 220, 60)
    add_rect(root, "lane-sis", "sis-find", "Menampilkan Informasi Tiket,\nStatus, OPD, dan Riwayat", 105, 620, 240, 60)
    add_end(root, "lane-sis", "sis-end", 204, 720)
    
    # Edges
    add_edge(root, "1", "e1", "", "mas-start", "mas-akses")
    add_edge(root, "1", "e2", "", "mas-akses", "mas-input")
    add_edge(root, "1", "e3", "", "mas-input", "sis-cari", ";exitX=1;exitY=0.5;entryX=0;entryY=0.5;")
    add_edge(root, "1", "e4", "", "sis-cari", "sis-temu")
    
    # temu -> no-find (Tidak)
    add_edge(root, "1", "e5", "Tidak", "sis-temu", "sis-no-find")
    # temu -> find (Ya)
    add_edge(root, "1", "e6", "Ya", "sis-temu", "sis-find", ";exitX=0.5;exitY=1;entryX=0.5;entryY=0;")
    
    add_edge(root, "1", "e7", "", "sis-no-find", "sis-end")
    add_edge(root, "1", "e8", "", "sis-find", "sis-end")
    
    save_xml(mx, "Activity Diagram Pelacakan Tiket oleh Masyarakat.drawio")

if __name__ == "__main__":
    make_tindak_lanjut()
    make_login_logout()
    make_tiket_manual()
    make_manajemen_opd()
    make_pelacakan_publik()
