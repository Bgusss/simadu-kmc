import os
import xml.etree.ElementTree as ET

def create_base_xml(diagram_name):
    mxfile = ET.Element("mxfile", host="app.diagrams.net")
    diagram = ET.SubElement(mxfile, "diagram", name=diagram_name, id="diagram-" + diagram_name.replace(" ", "-"))
    model = ET.SubElement(diagram, "mxGraphModel", dx="1000", dy="1000", grid="1", gridSize="10", guides="1", tooltips="1", connect="1", arrows="1", fold="1", page="1", pageScale="1", pageWidth="850", pageHeight="1100", math="0", shadow="0")
    root = ET.SubElement(model, "root")
    ET.SubElement(root, "mxCell", id="0")
    ET.SubElement(root, "mxCell", id="1", parent="0")
    return mxfile, root

def add_lifeline(root, id_, name, x, y, height):
    cell = ET.SubElement(root, "mxCell", id=id_, value=name, parent="1", vertex="1")
    cell.set("style", "shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Trebuchet MS;fontSize=14;fontStyle=1;")
    geo = ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width="140", height=str(height))
    geo.set("as", "geometry")
    return cell

def add_activation(root, id_, parent_lifeline, y_offset, height):
    cell = ET.SubElement(root, "mxCell", id=id_, value="", parent=parent_lifeline, vertex="1")
    cell.set("style", "html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;")
    geo = ET.SubElement(cell, "mxGeometry", x="65", y=str(y_offset), width="10", height=str(height))
    geo.set("as", "geometry")
    return cell

def add_message(root, id_, value, source_id, target_id, y_pos, is_return=False):
    style = "html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Trebuchet MS;fontSize=13;fontColor=#333333;strokeColor=#333333;"
    if is_return:
        style += "dashed=1;endArrow=open;endSize=8;"
    
    cell = ET.SubElement(root, "mxCell", id=id_, value=value, parent="1", edge="1", source=source_id, target=target_id)
    cell.set("style", style)
    geo = ET.SubElement(cell, "mxGeometry", relative="1")
    geo.set("as", "geometry")
    arr = ET.SubElement(geo, "Array")
    arr.set("as", "points")
    ET.SubElement(arr, "mxPoint", x="0", y=str(y_pos))
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
# 1. Detail Pengolahan Aduan
# ==========================================
def make_detail_pengolahan():
    mx, root = create_base_xml("Pengolahan Aduan")
    h = 750
    add_lifeline(root, "L1", "Scraper\n(Playwright)", 40, 40, h)
    add_lifeline(root, "L2", "Controller\n(Artisan Command)", 240, 40, h)
    add_lifeline(root, "L3", "Layanan AI\n(Google Gemini)", 440, 40, h)
    add_lifeline(root, "L4", "Model\n(Notifikasi & Tiket)", 640, 40, h)
    add_lifeline(root, "L5", "Basis Data\n(MySQL)", 840, 40, h)
    
    # Activations
    add_activation(root, "A1", "L1", 60, 600)
    add_activation(root, "A2", "L2", 80, 560)
    add_activation(root, "A3", "L3", 220, 60)
    add_activation(root, "A4", "L3", 340, 60)
    add_activation(root, "A5", "L3", 460, 60)
    add_activation(root, "A6", "L4", 120, 480)
    add_activation(root, "A7", "L5", 140, 60)
    add_activation(root, "A8", "L5", 540, 60)
    
    # Messages
    add_message(root, "m1", "1. Mengambil data aduan medsos", "A1", "A2", 100)
    add_message(root, "m2", "2. Menyimpan raw mention", "A2", "A6", 140)
    add_message(root, "m3", "3. Simpan data ke tabel mentions", "A6", "A7", 160)
    add_message(root, "m4", "4. Konfirmasi tersimpan", "A7", "A6", 180, True)
    
    add_message(root, "m5", "5. Meminta filter kelayakan (spam check)", "A2", "A3", 240)
    add_message(root, "m6", "6. Mengembalikan status layak diproses", "A3", "A2", 280, True)
    
    add_message(root, "m7", "7. Meminta klasifikasi (Kategori, OPD, Prioritas)", "A2", "A3", 360)
    add_message(root, "m8", "8. Mengembalikan JSON hasil klasifikasi", "A3", "A2", 400, True)
    
    add_message(root, "m9", "9. Meminta perbandingan duplikasi (Semantik)", "A2", "A3", 480)
    add_message(root, "m10", "10. Mengembalikan status bukan duplikat", "A3", "A2", 520, True)
    
    add_message(root, "m11", "11. Membuat tiket & nomor pelacakan", "A2", "A6", 560)
    add_message(root, "m12", "12. Simpan tiket ke tabel tickets", "A6", "A8", 580)
    add_message(root, "m13", "13. Konfirmasi tiket tersimpan", "A8", "A6", 600, True)
    add_message(root, "m14", "14. Mengirim respons sukses", "A6", "A2", 620, True)
    
    save_xml(mx, "Sequence Diagram Pengolahan Aduan.drawio")

# ==========================================
# 2. Detail Tindak Lanjut Tiket
# ==========================================
def make_detail_tindak_lanjut():
    mx, root = create_base_xml("Tindak Lanjut")
    h = 600
    add_lifeline(root, "L1", "Pengguna OPD", 40, 40, h)
    add_lifeline(root, "L2", "Tampilan\n(Dashboard OPD)", 240, 40, h)
    add_lifeline(root, "L3", "Controller\n(OpdController)", 440, 40, h)
    add_lifeline(root, "L4", "Model\n(Ticket & Log)", 640, 40, h)
    add_lifeline(root, "L5", "Basis Data\n(MySQL)", 840, 40, h)
    
    # Activations
    add_activation(root, "A1", "L1", 60, 480)
    add_activation(root, "A2", "L2", 80, 440)
    add_activation(root, "A3", "L3", 100, 400)
    add_activation(root, "A4", "L4", 140, 60)
    add_activation(root, "A5", "L5", 160, 40)
    add_activation(root, "A6", "L4", 320, 160)
    add_activation(root, "A7", "L5", 380, 80)
    
    # Messages
    add_message(root, "m1", "1. Mengakses detail tiket", "A1", "A2", 120)
    add_message(root, "m2", "2. Request(GET /opd/tickets/{id})", "A2", "A3", 140)
    add_message(root, "m3", "3. findOrFail(id)", "A3", "A4", 160)
    add_message(root, "m4", "4. Query Select", "A4", "A5", 180)
    add_message(root, "m5", "5. Return ResultSet", "A5", "A4", 200, True)
    add_message(root, "m6", "6. Mengembalikan Object Ticket", "A4", "A3", 220, True)
    add_message(root, "m7", "7. Render view(detail tiket)", "A3", "A2", 240, True)
    add_message(root, "m8", "8. Menampilkan antarmuka tiket", "A2", "A1", 260, True)
    
    add_message(root, "m9", "9. Memasukkan tanggapan & status", "A1", "A2", 300)
    add_message(root, "m10", "10. Post(Data Tanggapan)", "A2", "A3", 320)
    add_message(root, "m11", "11. create(TicketResponse, Log)", "A3", "A6", 360)
    add_message(root, "m12", "12. Insert Into DB", "A6", "A7", 400)
    add_message(root, "m13", "13. Return Success", "A7", "A6", 440, True)
    add_message(root, "m14", "14. Konfirmasi Data Tersimpan", "A6", "A3", 460, True)
    add_message(root, "m15", "15. Redirect back with Success", "A3", "A2", 480, True)
    add_message(root, "m16", "16. Menampilkan pesan berhasil", "A2", "A1", 500, True)
    
    save_xml(mx, "Sequence Diagram Tindak Lanjut Tiket oleh Pengguna OPD.drawio")

# ==========================================
# 3. Detail Verifikasi Duplikasi
# ==========================================
def make_detail_verifikasi():
    mx, root = create_base_xml("Verifikasi Duplikasi")
    h = 600
    add_lifeline(root, "L1", "Admin KMC", 40, 40, h)
    add_lifeline(root, "L2", "Tampilan\n(Halaman Admin)", 240, 40, h)
    add_lifeline(root, "L3", "Controller\n(NotificationController)", 440, 40, h)
    add_lifeline(root, "L4", "Model\n(Notification)", 640, 40, h)
    add_lifeline(root, "L5", "Basis Data\n(MySQL)", 840, 40, h)
    
    # Activations
    add_activation(root, "A1", "L1", 60, 480)
    add_activation(root, "A2", "L2", 80, 440)
    add_activation(root, "A3", "L3", 100, 400)
    add_activation(root, "A4", "L4", 140, 60)
    add_activation(root, "A5", "L5", 160, 40)
    add_activation(root, "A6", "L4", 320, 160)
    add_activation(root, "A7", "L5", 380, 80)
    
    # Messages
    add_message(root, "m1", "1. Membuka detail kandidat duplikat", "A1", "A2", 120)
    add_message(root, "m2", "2. Request(GET /notification/{id})", "A2", "A3", 140)
    add_message(root, "m3", "3. find(id) with duplicate_of_id", "A3", "A4", 160)
    add_message(root, "m4", "4. Query Select perbandingan", "A4", "A5", 180)
    add_message(root, "m5", "5. Return ResultSet", "A5", "A4", 200, True)
    add_message(root, "m6", "6. Mengembalikan Object", "A4", "A3", 220, True)
    add_message(root, "m7", "7. Render view(komparasi)", "A3", "A2", 240, True)
    add_message(root, "m8", "8. Menampilkan antarmuka komparasi", "A2", "A1", 260, True)
    
    add_message(root, "m9", "9. Konfirmasi (Bukan Duplikat)", "A1", "A2", 300)
    add_message(root, "m10", "10. Post(Status Konfirmasi)", "A2", "A3", 320)
    add_message(root, "m11", "11. update(status) & create(Ticket)", "A3", "A6", 360)
    add_message(root, "m12", "12. Update & Insert Into DB", "A6", "A7", 400)
    add_message(root, "m13", "13. Return Success", "A7", "A6", 440, True)
    add_message(root, "m14", "14. Konfirmasi Data Tersimpan", "A6", "A3", 460, True)
    add_message(root, "m15", "15. Redirect back with Success", "A3", "A2", 480, True)
    add_message(root, "m16", "16. Menampilkan notifikasi berhasil", "A2", "A1", 500, True)
    
    save_xml(mx, "Sequence Diagram Verifikasi Duplikasi oleh Admin KMC.drawio")

# ==========================================
# 4. Detail Eskalasi SLA
# ==========================================
def make_detail_eskalasi():
    mx, root = create_base_xml("Eskalasi SLA")
    h = 550
    add_lifeline(root, "L1", "Cron / Scheduler\n(Sistem)", 40, 40, h)
    add_lifeline(root, "L2", "Console Command\n(CheckEscalation)", 240, 40, h)
    add_lifeline(root, "L3", "Model\n(Ticket)", 440, 40, h)
    add_lifeline(root, "L4", "Model\n(TicketStatusLog)", 640, 40, h)
    add_lifeline(root, "L5", "Basis Data\n(MySQL)", 840, 40, h)
    
    # Activations
    add_activation(root, "A1", "L1", 60, 420)
    add_activation(root, "A2", "L2", 80, 380)
    add_activation(root, "A3", "L3", 140, 60)
    add_activation(root, "A4", "L5", 160, 40)
    
    add_activation(root, "A5", "L3", 260, 160)
    add_activation(root, "A6", "L4", 320, 80)
    add_activation(root, "A7", "L5", 380, 80)
    
    # Messages
    add_message(root, "m1", "1. Memicu eksekusi jadwal", "A1", "A2", 100)
    add_message(root, "m2", "2. Memanggil processEscalation()", "A2", "A2", 120)
    add_message(root, "m3", "3. where('sla_deadline', '<', now())", "A2", "A3", 140)
    add_message(root, "m4", "4. Query Select tiket overdue", "A3", "A4", 160)
    add_message(root, "m5", "5. Return ResultSet", "A4", "A3", 180, True)
    add_message(root, "m6", "6. Mengembalikan Object Ticket", "A3", "A2", 200, True)
    
    add_message(root, "m7", "7. Foreach tiket: Hitung prioritas baru", "A2", "A2", 240)
    add_message(root, "m8", "8. update(priority, sla_deadline)", "A2", "A5", 280)
    
    add_message(root, "m9", "9. create(Log Eskalasi)", "A2", "A6", 320)
    add_message(root, "m10", "10. Insert Into DB", "A6", "A7", 380)
    add_message(root, "m11", "11. Return Success", "A7", "A6", 420, True)
    add_message(root, "m12", "12. Mengembalikan Object Log", "A6", "A2", 440, True)
    add_message(root, "m13", "13. Eksekusi command selesai", "A2", "A1", 460, True)
    
    save_xml(mx, "Sequence Diagram Eskalasi Prioritas Otomatis.drawio")

if __name__ == "__main__":
    make_detail_pengolahan()
    make_detail_tindak_lanjut()
    make_detail_verifikasi()
    make_detail_eskalasi()
