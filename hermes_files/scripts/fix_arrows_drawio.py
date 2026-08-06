import os
import xml.etree.ElementTree as ET

def create_base(name):
    mx = ET.Element("mxfile", host="app.diagrams.net")
    dia = ET.SubElement(mx, "diagram", name=name, id=name.replace(" ", "-"))
    model = ET.SubElement(dia, "mxGraphModel", dx="1000", dy="1000", grid="1", gridSize="10", guides="1", tooltips="1", connect="1", arrows="1", fold="1", page="1", pageScale="1", pageWidth="850", pageHeight="1100", math="0", shadow="0")
    root = ET.SubElement(model, "root")
    ET.SubElement(root, "mxCell", id="0")
    ET.SubElement(root, "mxCell", id="1", parent="0")
    return mx, root

class SequenceDiagram:
    def __init__(self, name):
        self.mx, self.root = create_base(name)
        self.lifelines = {}
        self.acts = {}

    def add_ll(self, id_, name, x, y, h):
        self.lifelines[id_] = {'x': x, 'y': y, 'h': h}
        cell = ET.SubElement(self.root, "mxCell", id=id_, value=name, parent="1", vertex="1")
        cell.set("style", "shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
        ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width="120", height=str(h), **{"as": "geometry"})

    def add_act(self, id_, ll_id, y_offset, h):
        self.acts[id_] = {'ll': ll_id, 'y_offset': y_offset, 'h': h}
        cell = ET.SubElement(self.root, "mxCell", id=id_, value="", parent=ll_id, vertex="1")
        cell.set("style", "html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
        ET.SubElement(cell, "mxGeometry", x="55", y=str(y_offset), width="10", height=str(h), **{"as": "geometry"})

    def add_msg(self, id_, value, src, tgt, abs_y, is_ret=False):
        s = self.acts[src]
        t = self.acts[tgt]
        s_x = self.lifelines[s['ll']]['x'] + 55
        t_x = self.lifelines[t['ll']]['x'] + 55
        s_abs_y = self.lifelines[s['ll']]['y'] + s['y_offset']
        t_abs_y = self.lifelines[t['ll']]['y'] + t['y_offset']

        exitY = (abs_y - s_abs_y) / s['h']
        entryY = (abs_y - t_abs_y) / t['h']

        if src == tgt:
            exitX, entryX = 1.0, 1.0
            entryY += 30 / t['h']
            style = f"html=1;verticalAlign=bottom;endArrow=block;edgeStyle=orthogonalEdgeStyle;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;exitX={exitX};exitY={exitY:.4f};entryX={entryX};entryY={entryY:.4f};"
        else:
            if s_x < t_x:
                exitX, entryX = 1.0, 0.0
            else:
                exitX, entryX = 0.0, 1.0
            style = f"html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;exitX={exitX};exitY={exitY:.4f};entryX={entryX};entryY={entryY:.4f};"
        
        if is_ret:
            style += "dashed=1;endArrow=open;endSize=8;"
        
        cell = ET.SubElement(self.root, "mxCell", id=id_, value=value, parent="1", edge="1", source=src, target=tgt)
        cell.set("style", style)
        geo = ET.SubElement(cell, "mxGeometry", relative="1", **{"as": "geometry"})
        
        if src == tgt:
            arr = ET.SubElement(geo, "Array", **{"as": "points"})
            ET.SubElement(arr, "mxPoint", x=str(s_x + 60), y=str(abs_y))
            ET.SubElement(arr, "mxPoint", x=str(s_x + 60), y=str(abs_y + 30))

    def save(self, filename):
        tree = ET.ElementTree(self.mx)
        ET.indent(tree, space="  ", level=0)
        path = os.path.join(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments", filename)
        with open(path, "wb") as f:
            f.write(b'<?xml version="1.0" encoding="UTF-8"?>\n')
            tree.write(f, encoding="utf-8")
        print(f"Generated: {path}")

# 1. Tindak Lanjut Tiket
d1 = SequenceDiagram("Tindak Lanjut")
d1.add_ll("L1", "Pengguna OPD", 60, 40, 600)
d1.add_ll("L2", "Antarmuka", 260, 40, 600)
d1.add_ll("L3", "Pengontrol", 460, 40, 600)
d1.add_ll("L4", "Basis Data", 660, 40, 600)

d1.add_act("A1", "L1", 60, 460)
d1.add_act("A2", "L2", 80, 440)
d1.add_act("A3", "L3", 100, 380)
d1.add_act("A4", "L4", 140, 60)
d1.add_act("A5", "L4", 360, 60)

d1.add_msg("m1", "1. Mengakses detail tiket", "A1", "A2", 140)
d1.add_msg("m2", "2. Meminta data tiket", "A2", "A3", 180)
d1.add_msg("m3", "3. Mengambil data", "A3", "A4", 200)
d1.add_msg("m4", "4. Mengembalikan data", "A4", "A3", 240, True)
d1.add_msg("m5", "5. Menampilkan detail tiket", "A3", "A2", 280, True)
d1.add_msg("m6", "6. Mengirim tanggapan & status", "A1", "A2", 340)
d1.add_msg("m7", "7. Meneruskan data tanggapan", "A2", "A3", 380)
d1.add_msg("m8", "8. Menyimpan riwayat penanganan", "A3", "A5", 420)
d1.add_msg("m9", "9. Konfirmasi tersimpan", "A5", "A3", 460, True)
d1.add_msg("m10", "10. Menampilkan pesan berhasil", "A3", "A2", 500, True)
d1.save("Sequence Diagram Tindak Lanjut Tiket oleh Pengguna OPD-3.drawio")

# 2. Verifikasi Duplikasi
d2 = SequenceDiagram("Verifikasi Duplikasi")
d2.add_ll("L1", "Admin KMC", 60, 40, 600)
d2.add_ll("L2", "Antarmuka", 260, 40, 600)
d2.add_ll("L3", "Pengontrol", 460, 40, 600)
d2.add_ll("L4", "Basis Data", 660, 40, 600)

d2.add_act("A1", "L1", 60, 460)
d2.add_act("A2", "L2", 80, 440)
d2.add_act("A3", "L3", 100, 380)
d2.add_act("A4", "L4", 140, 60)
d2.add_act("A5", "L4", 360, 60)

d2.add_msg("m1", "1. Membuka notifikasi kandidat duplikat", "A1", "A2", 140)
d2.add_msg("m2", "2. Meminta data perbandingan", "A2", "A3", 180)
d2.add_msg("m3", "3. Mengambil aduan baru & pembanding", "A3", "A4", 200)
d2.add_msg("m4", "4. Mengembalikan data aduan", "A4", "A3", 240, True)
d2.add_msg("m5", "5. Menampilkan halaman perbandingan", "A3", "A2", 280, True)
d2.add_msg("m6", "6. Konfirmasi status aduan", "A1", "A2", 340)
d2.add_msg("m7", "7. Mengirim keputusan verifikasi", "A2", "A3", 380)
d2.add_msg("m8", "8. Mengupdate status notifikasi", "A3", "A5", 420)
d2.add_msg("m9", "9. Konfirmasi data tersimpan", "A5", "A3", 460, True)
d2.add_msg("m10", "10. Menampilkan pesan berhasil", "A3", "A2", 500, True)
d2.save("Sequence Diagram Verifikasi Duplikasi oleh Admin KMC-3.drawio")

# 3. Eskalasi SLA
d3 = SequenceDiagram("Eskalasi Prioritas")
d3.add_ll("L1", "Penjadwal Sistem", 60, 40, 600)
d3.add_ll("L2", "Command", 260, 40, 600)
d3.add_ll("L3", "Model Tiket", 460, 40, 600)
d3.add_ll("L4", "Basis Data", 660, 40, 600)

d3.add_act("A1", "L1", 60, 360)
d3.add_act("A2", "L2", 80, 340)
d3.add_act("A4", "L4", 100, 60)
d3.add_act("A3", "L3", 180, 180)
d3.add_act("A5", "L4", 280, 60)

d3.add_msg("m1", "1. Memicu pengecekan berkala", "A1", "A2", 120)
d3.add_msg("m2", "2. Meminta daftar tiket aktif", "A2", "A4", 160)
d3.add_msg("m3", "3. Mengembalikan data tiket", "A4", "A2", 200, True)
d3.add_msg("m4", "4. Memproses SLA", "A2", "A3", 240)
d3.add_msg("m5", "5. Memperbarui prioritas & SLA", "A3", "A3", 270) # Self-message
d3.add_msg("m6", "6. Menyimpan log status", "A3", "A5", 340)
d3.add_msg("m7", "7. Konfirmasi pembaruan", "A5", "A3", 380, True)
d3.add_msg("m8", "8. Status pembaruan berhasil", "A3", "A2", 420, True)
d3.save("Sequence Diagram Eskalasi Prioritas Otomatis-3.drawio")
