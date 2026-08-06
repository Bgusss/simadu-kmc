import os
import xml.etree.ElementTree as ET

def parse_xml_to_dict(file_path):
    tree = ET.parse(file_path)
    root = tree.getroot()
    return root

# Diagram 2: Sequence Diagram Tindak Lanjut Tiket oleh Pengguna OPD
def make_sequence_tindak_lanjut():
    mxfile = ET.Element("mxfile", host="app.diagrams.net")
    diagram = ET.SubElement(mxfile, "diagram", name="Tindak Lanjut Tiket", id="diagram-Tindak-Lanjut-Tiket")
    model = ET.SubElement(diagram, "mxGraphModel", dx="1077", dy="705", grid="1", gridSize="10", guides="1", tooltips="1", connect="1", arrows="1", fold="1", page="1", pageScale="1", pageWidth="850", pageHeight="1100", math="0", shadow="0")
    root = ET.SubElement(model, "root")
    ET.SubElement(root, "mxCell", id="0")
    ET.SubElement(root, "mxCell", id="1", parent="0")

    # Lifelines
    L1 = ET.SubElement(root, "mxCell", id="L1", value="Pengguna OPD", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L1, "mxGeometry", height="610", width="120", x="60", y="40", **{"as": "geometry"})
    
    L2 = ET.SubElement(root, "mxCell", id="L2", value="Antarmuka", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L2, "mxGeometry", height="610", width="120", x="260", y="40", **{"as": "geometry"})
    
    L3 = ET.SubElement(root, "mxCell", id="L3", value="Pengontrol", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L3, "mxGeometry", height="610", width="120", x="460", y="40", **{"as": "geometry"})
    
    L4 = ET.SubElement(root, "mxCell", id="L4", value="Basis Data", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L4, "mxGeometry", height="610", width="120", x="660", y="40", **{"as": "geometry"})

    # Activations
    A1 = ET.SubElement(L1, "mxCell", id="A1", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A1, "mxGeometry", height="470", width="10", x="55", y="60", **{"as": "geometry"})

    A2 = ET.SubElement(L2, "mxCell", id="A2", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A2, "mxGeometry", height="450", width="10", x="55", y="80", **{"as": "geometry"})

    A3 = ET.SubElement(L3, "mxCell", id="A3", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A3, "mxGeometry", height="400", width="10", x="55", y="100", **{"as": "geometry"})

    A4 = ET.SubElement(L4, "mxCell", id="A4", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A4, "mxGeometry", height="60", width="10", x="55", y="140", **{"as": "geometry"})

    A5 = ET.SubElement(L4, "mxCell", id="A5", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A5, "mxGeometry", height="60", width="10", x="55", y="360", **{"as": "geometry"})

    # Messages
    m1 = ET.SubElement(root, "mxCell", id="m1", value="1. Mengakses detail tiket", parent="1", edge="1", source="A1", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m1, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="260", y="120")

    m2 = ET.SubElement(root, "mxCell", id="m2", value="2. Meminta data tiket", parent="1", edge="1", source="A2", target="A3", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m2, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="460", y="160")

    m3 = ET.SubElement(root, "mxCell", id="m3", value="3. Mengambil data", parent="1", edge="1", source="A3", target="A4", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m3, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="660", y="200")

    m4 = ET.SubElement(root, "mxCell", id="m4", value="4. Mengembalikan data", parent="1", edge="1", source="A4", target="A3", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m4, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="530", y="240")

    m5 = ET.SubElement(root, "mxCell", id="m5", value="5. Menampilkan detail tiket", parent="1", edge="1", source="A3", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m5, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="330", y="280")

    m6 = ET.SubElement(root, "mxCell", id="m6", value="6. Mengirim tanggapan & pembaruan status", parent="1", edge="1", source="A1", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m6, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="260", y="340")

    m7 = ET.SubElement(root, "mxCell", id="m7", value="7. Meneruskan data tanggapan", parent="1", edge="1", source="A2", target="A3", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m7, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="460", y="380")

    m8 = ET.SubElement(root, "mxCell", id="m8", value="8. Menyimpan riwayat penanganan", parent="1", edge="1", source="A3", target="A5", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m8, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="660", y="420")

    m9 = ET.SubElement(root, "mxCell", id="m9", value="9. Konfirmasi tersimpan", parent="1", edge="1", source="A5", target="A3", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m9, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="530", y="460")

    m10 = ET.SubElement(root, "mxCell", id="m10", value="10. Menampilkan pesan berhasil", parent="1", edge="1", source="A3", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m10, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="330", y="500")

    tree = ET.ElementTree(mxfile)
    ET.indent(tree, space="  ", level=0)
    with open(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Sequence Diagram Tindak Lanjut Tiket oleh Pengguna OPD-2.drawio", "wb") as f:
        f.write(b'<?xml version="1.0" encoding="UTF-8"?>\n')
        tree.write(f, encoding="utf-8")

# Diagram 3: Sequence Diagram Verifikasi Duplikasi oleh Admin KMC
def make_sequence_verifikasi_duplikasi():
    mxfile = ET.Element("mxfile", host="app.diagrams.net")
    diagram = ET.SubElement(mxfile, "diagram", name="Verifikasi Duplikasi", id="diagram-Verifikasi-Duplikasi")
    model = ET.SubElement(diagram, "mxGraphModel", dx="1077", dy="705", grid="1", gridSize="10", guides="1", tooltips="1", connect="1", arrows="1", fold="1", page="1", pageScale="1", pageWidth="850", pageHeight="1100", math="0", shadow="0")
    root = ET.SubElement(model, "root")
    ET.SubElement(root, "mxCell", id="0")
    ET.SubElement(root, "mxCell", id="1", parent="0")

    L1 = ET.SubElement(root, "mxCell", id="L1", value="Admin KMC", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L1, "mxGeometry", height="610", width="120", x="60", y="40", **{"as": "geometry"})
    
    L2 = ET.SubElement(root, "mxCell", id="L2", value="Antarmuka", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L2, "mxGeometry", height="610", width="120", x="260", y="40", **{"as": "geometry"})
    
    L3 = ET.SubElement(root, "mxCell", id="L3", value="Pengontrol", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L3, "mxGeometry", height="610", width="120", x="460", y="40", **{"as": "geometry"})
    
    L4 = ET.SubElement(root, "mxCell", id="L4", value="Basis Data", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L4, "mxGeometry", height="610", width="120", x="660", y="40", **{"as": "geometry"})

    A1 = ET.SubElement(L1, "mxCell", id="A1", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A1, "mxGeometry", height="470", width="10", x="55", y="60", **{"as": "geometry"})

    A2 = ET.SubElement(L2, "mxCell", id="A2", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A2, "mxGeometry", height="450", width="10", x="55", y="80", **{"as": "geometry"})

    A3 = ET.SubElement(L3, "mxCell", id="A3", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A3, "mxGeometry", height="400", width="10", x="55", y="100", **{"as": "geometry"})

    A4 = ET.SubElement(L4, "mxCell", id="A4", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A4, "mxGeometry", height="60", width="10", x="55", y="140", **{"as": "geometry"})

    A5 = ET.SubElement(L4, "mxCell", id="A5", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A5, "mxGeometry", height="60", width="10", x="55", y="360", **{"as": "geometry"})

    m1 = ET.SubElement(root, "mxCell", id="m1", value="1. Membuka notifikasi kandidat duplikat", parent="1", edge="1", source="A1", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m1, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="260", y="120")

    m2 = ET.SubElement(root, "mxCell", id="m2", value="2. Meminta data perbandingan", parent="1", edge="1", source="A2", target="A3", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m2, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="460", y="160")

    m3 = ET.SubElement(root, "mxCell", id="m3", value="3. Mengambil aduan baru & pembanding", parent="1", edge="1", source="A3", target="A4", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m3, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="660", y="200")

    m4 = ET.SubElement(root, "mxCell", id="m4", value="4. Mengembalikan data aduan", parent="1", edge="1", source="A4", target="A3", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m4, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="530", y="240")

    m5 = ET.SubElement(root, "mxCell", id="m5", value="5. Menampilkan halaman perbandingan", parent="1", edge="1", source="A3", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m5, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="330", y="280")

    m6 = ET.SubElement(root, "mxCell", id="m6", value="6. Konfirmasi status aduan (duplikat/bukan)", parent="1", edge="1", source="A1", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m6, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="260", y="340")

    m7 = ET.SubElement(root, "mxCell", id="m7", value="7. Mengirim keputusan verifikasi", parent="1", edge="1", source="A2", target="A3", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m7, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="460", y="380")

    m8 = ET.SubElement(root, "mxCell", id="m8", value="8. Update status & membuat tiket (jika bukan duplikat)", parent="1", edge="1", source="A3", target="A5", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m8, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="660", y="420")

    m9 = ET.SubElement(root, "mxCell", id="m9", value="9. Konfirmasi data tersimpan", parent="1", edge="1", source="A5", target="A3", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m9, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="530", y="460")

    m10 = ET.SubElement(root, "mxCell", id="m10", value="10. Menampilkan pesan berhasil", parent="1", edge="1", source="A3", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m10, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="330", y="500")

    tree = ET.ElementTree(mxfile)
    ET.indent(tree, space="  ", level=0)
    with open(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Sequence Diagram Verifikasi Duplikasi oleh Admin KMC-2.drawio", "wb") as f:
        f.write(b'<?xml version="1.0" encoding="UTF-8"?>\n')
        tree.write(f, encoding="utf-8")

# Diagram 4: Sequence Diagram Eskalasi Prioritas Otomatis
def make_sequence_eskalasi_sla():
    mxfile = ET.Element("mxfile", host="app.diagrams.net")
    diagram = ET.SubElement(mxfile, "diagram", name="Eskalasi Prioritas", id="diagram-Eskalasi-Prioritas")
    model = ET.SubElement(diagram, "mxGraphModel", dx="1077", dy="705", grid="1", gridSize="10", guides="1", tooltips="1", connect="1", arrows="1", fold="1", page="1", pageScale="1", pageWidth="850", pageHeight="1100", math="0", shadow="0")
    root = ET.SubElement(model, "root")
    ET.SubElement(root, "mxCell", id="0")
    ET.SubElement(root, "mxCell", id="1", parent="0")

    L1 = ET.SubElement(root, "mxCell", id="L1", value="Penjadwal Sistem", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L1, "mxGeometry", height="610", width="120", x="60", y="40", **{"as": "geometry"})
    
    L2 = ET.SubElement(root, "mxCell", id="L2", value="Command", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L2, "mxGeometry", height="610", width="120", x="260", y="40", **{"as": "geometry"})
    
    L3 = ET.SubElement(root, "mxCell", id="L3", value="Model Tiket", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L3, "mxGeometry", height="610", width="120", x="460", y="40", **{"as": "geometry"})
    
    L4 = ET.SubElement(root, "mxCell", id="L4", value="Basis Data", parent="1", vertex="1", style="shape=umlLifeline;perimeter=lifelinePerimeter;whiteSpace=wrap;html=1;container=1;collapsible=0;recursiveResize=0;outlineConnect=0;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;fontStyle=1;")
    ET.SubElement(L4, "mxGeometry", height="610", width="120", x="660", y="40", **{"as": "geometry"})

    A1 = ET.SubElement(L1, "mxCell", id="A1", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A1, "mxGeometry", height="370", width="10", x="55", y="60", **{"as": "geometry"})

    A2 = ET.SubElement(L2, "mxCell", id="A2", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A2, "mxGeometry", height="350", width="10", x="55", y="80", **{"as": "geometry"})

    A3 = ET.SubElement(L4, "mxCell", id="A3", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A3, "mxGeometry", height="60", width="10", x="55", y="100", **{"as": "geometry"})

    A4 = ET.SubElement(L3, "mxCell", id="A4", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A4, "mxGeometry", height="200", width="10", x="55", y="180", **{"as": "geometry"})

    A5 = ET.SubElement(L4, "mxCell", id="A5", value="", vertex="1", style="html=1;points=[];perimeter=orthogonalPerimeter;fillColor=#E8E8E8;strokeColor=#333333;fontFamily=Times New Roman;")
    ET.SubElement(A5, "mxGeometry", height="60", width="10", x="55", y="280", **{"as": "geometry"})

    m1 = ET.SubElement(root, "mxCell", id="m1", value="1. Memicu pengecekan berkala (Setiap 30 menit)", parent="1", edge="1", source="A1", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m1, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="260", y="120")

    m2 = ET.SubElement(root, "mxCell", id="m2", value="2. Meminta daftar tiket aktif belum direspon", parent="1", edge="1", source="A2", target="A3", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m2, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="460", y="160")

    m3 = ET.SubElement(root, "mxCell", id="m3", value="3. Mengembalikan data tiket", parent="1", edge="1", source="A3", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m3, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="530", y="200")

    m4 = ET.SubElement(root, "mxCell", id="m4", value="4. Memproses SLA", parent="1", edge="1", source="A2", target="A4", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m4, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="330", y="240")

    # Self call
    m5 = ET.SubElement(root, "mxCell", id="m5", value="5. Memperbarui tingkat prioritas & SLA baru", parent="1", edge="1", source="A4", target="A4", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m5, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="535", y="260")
    ET.SubElement(arr, "mxPoint", x="555", y="260")
    ET.SubElement(arr, "mxPoint", x="555", y="290")
    ET.SubElement(arr, "mxPoint", x="535", y="290")

    m6 = ET.SubElement(root, "mxCell", id="m6", value="6. Menyimpan perubahan & log status", parent="1", edge="1", source="A4", target="A5", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;")
    geo = ET.SubElement(m6, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="660", y="320")

    m7 = ET.SubElement(root, "mxCell", id="m7", value="7. Konfirmasi pembaruan", parent="1", edge="1", source="A5", target="A4", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m7, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="530", y="360")

    m8 = ET.SubElement(root, "mxCell", id="m8", value="8. Status pembaruan berhasil", parent="1", edge="1", source="A4", target="A2", style="html=1;verticalAlign=bottom;endArrow=block;edgeStyle=elbowEdgeStyle;elbow=vertical;curved=0;rounded=0;fontFamily=Times New Roman;fontSize=13;fontColor=#333333;strokeColor=#333333;dashed=1;endArrow=open;endSize=8;")
    geo = ET.SubElement(m8, "mxGeometry", relative="1", **{"as": "geometry"})
    arr = ET.SubElement(geo, "Array", **{"as": "points"})
    ET.SubElement(arr, "mxPoint", x="330", y="400")

    tree = ET.ElementTree(mxfile)
    ET.indent(tree, space="  ", level=0)
    with open(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments\Sequence Diagram Eskalasi Prioritas Otomatis-2.drawio", "wb") as f:
        f.write(b'<?xml version="1.0" encoding="UTF-8"?>\n')
        tree.write(f, encoding="utf-8")

if __name__ == "__main__":
    make_sequence_tindak_lanjut()
    make_sequence_verifikasi_duplikasi()
    make_sequence_eskalasi_sla()
