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
    # The lifeline head
    cell = ET.SubElement(root, "mxCell", id=id_, value=name, parent="1", vertex="1")
    cell.set("style", "shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Trebuchet MS;fontSize=14;fontStyle=1;")
    geo = ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width="120", height=str(height))
    geo.set("as", "geometry")
    return cell

def add_activation(root, id_, parent_lifeline, y_offset, height):
    cell = ET.SubElement(root, "mxCell", id=id_, value="", parent=parent_lifeline, vertex="1")
    cell.set("style", "html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;")
    geo = ET.SubElement(cell, "mxGeometry", x="55", y=str(y_offset), width="10", height=str(height))
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
    # Provide routing points just to be safe
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

def make_sequence_pengolahan():
    mx, root = create_base_xml("Pengolahan Aduan")
    
    h = 600
    add_lifeline(root, "L1", "Sistem Scraper", 60, 40, h)
    add_lifeline(root, "L2", "Pengontrol", 260, 40, h)
    add_lifeline(root, "L3", "Layanan AI", 460, 40, h)
    add_lifeline(root, "L4", "Basis Data", 660, 40, h)
    
    # Activations
    add_activation(root, "A1", "L1", 60, 480)
    add_activation(root, "A2", "L2", 80, 440)
    add_activation(root, "A3", "L3", 140, 60)
    add_activation(root, "A4", "L3", 260, 60)
    add_activation(root, "A5", "L3", 380, 60)
    add_activation(root, "A6", "L4", 200, 40)
    add_activation(root, "A7", "L4", 480, 40)
    
    # Messages
    add_message(root, "m1", "1. Mengirim data aduan", "A1", "A2", 120)
    add_message(root, "m2", "2. Meminta filter kelayakan", "A2", "A3", 180)
    add_message(root, "m3", "3. Hasil (Layak)", "A3", "A2", 240, True)
    add_message(root, "m4", "4. Menyimpan notifikasi", "A2", "A6", 300)
    add_message(root, "m5", "5. Meminta klasifikasi (Kategori, OPD)", "A2", "A4", 360)
    add_message(root, "m6", "6. Hasil klasifikasi", "A4", "A2", 420, True)
    add_message(root, "m7", "7. Memeriksa kemungkinan duplikasi", "A2", "A5", 480)
    add_message(root, "m8", "8. Hasil deteksi duplikasi", "A5", "A2", 540, True)
    add_message(root, "m9", "9. Menyimpan tiket (Bukan duplikat)", "A2", "A7", 600)
    
    save_xml(mx, "Sequence Diagram Pengolahan Aduan.drawio")

def make_sequence_tindak_lanjut():
    mx, root = create_base_xml("Tindak Lanjut Tiket")
    
    h = 550
    add_lifeline(root, "L1", "Pengguna OPD", 60, 40, h)
    add_lifeline(root, "L2", "Antarmuka", 260, 40, h)
    add_lifeline(root, "L3", "Pengontrol", 460, 40, h)
    add_lifeline(root, "L4", "Basis Data", 660, 40, h)
    
    # Activations
    add_activation(root, "A1", "L1", 60, 420)
    add_activation(root, "A2", "L2", 80, 400)
    add_activation(root, "A3", "L3", 100, 360)
    add_activation(root, "A4", "L4", 140, 60)
    add_activation(root, "A5", "L4", 340, 80)
    
    # Messages
    add_message(root, "m1", "1. Mengakses detail tiket", "A1", "A2", 120)
    add_message(root, "m2", "2. Meminta data tiket", "A2", "A3", 160)
    add_message(root, "m3", "3. Mengambil data", "A3", "A4", 200)
    add_message(root, "m4", "4. Mengembalikan data", "A4", "A3", 260, True)
    add_message(root, "m5", "5. Menampilkan detail", "A3", "A2", 300, True)
    
    add_message(root, "m6", "6. Mengirim tanggapan & status", "A1", "A2", 360)
    add_message(root, "m7", "7. Meneruskan data tanggapan", "A2", "A3", 400)
    add_message(root, "m8", "8. Menyimpan riwayat", "A3", "A5", 440)
    add_message(root, "m9", "9. Konfirmasi tersimpan", "A5", "A3", 500, True)
    add_message(root, "m10", "10. Menampilkan pesan berhasil", "A3", "A2", 540, True)
    
    save_xml(mx, "Sequence Diagram Tindak Lanjut Tiket oleh Pengguna OPD.drawio")

if __name__ == "__main__":
    make_sequence_pengolahan()
    make_sequence_tindak_lanjut()
