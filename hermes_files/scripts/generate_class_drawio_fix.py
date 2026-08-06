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

def add_class(root, id_, name, attrs, methods, x, y, w, h):
    # Container / Header
    # We use typical UML Class shape: a vertical swimlane that is stack-layout friendly.
    style_container = "swimlane;fontStyle=1;align=center;verticalAlign=top;childLayout=stackLayout;horizontal=1;startSize=26;horizontalStack=0;resizeParent=1;resizeParentMax=0;resizeLast=0;collapsible=0;marginBottom=0;html=1;fillColor=#ECECFF;strokeColor=#9370DB;fontColor=#333333;fontFamily=Times New Roman;fontSize=14;"
    container = ET.SubElement(root, "mxCell", id=id_, value=name, parent="1", vertex="1", style=style_container)
    ET.SubElement(container, "mxGeometry", x=str(x), y=str(y), width=str(w), height=str(h), **{"as": "geometry"})
    
    # Attributes Box
    attr_val = "\n".join(attrs) if attrs else ""
    style_attrs = "text;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;points=[];portConstraint=eastwest;fontFamily=Times New Roman;fontSize=12;fontColor=#333333;"
    attr_box = ET.SubElement(root, "mxCell", id=id_+"_a", value=attr_val, parent=id_, vertex="1", style=style_attrs)
    attr_h = 26 + (len(attrs) * 16) if attrs else 30
    ET.SubElement(attr_box, "mxGeometry", y="26", width=str(w), height=str(attr_h - 26), **{"as": "geometry"})
    
    # Methods Box (separated by a horizontal line natively handled by the stack layout / style)
    method_val = "\n".join(methods) if methods else ""
    style_methods = "text;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;points=[];portConstraint=eastwest;fontFamily=Times New Roman;fontSize=12;fontColor=#333333;borderTop=1;borderStroke=1;"
    method_box = ET.SubElement(root, "mxCell", id=id_+"_m", value=method_val, parent=id_, vertex="1", style=style_methods)
    method_h = h - attr_h
    ET.SubElement(method_box, "mxGeometry", y=str(attr_h), width=str(w), height=str(method_h), **{"as": "geometry"})
    
def add_edge(root, id_, val_source, val_target, source_id, target_id, style_ext=""):
    # Create the edge
    style = "endArrow=none;html=1;edgeStyle=orthogonalEdgeStyle;rounded=0;fontFamily=Times New Roman;fontSize=12;fontColor=#333333;strokeColor=#333333;" + style_ext
    edge = ET.SubElement(root, "mxCell", id=id_, value="", parent="1", edge="1", source=source_id, target=target_id, style=style)
    geo = ET.SubElement(edge, "mxGeometry", relative="1", **{"as": "geometry"})
    
    # Multiplicity at Source
    if val_source:
        lbl_s = ET.SubElement(root, "mxCell", id=id_+"_s", value=val_source, parent=id_, vertex="1")
        lbl_s.set("style", "edgeLabel;html=1;align=center;verticalAlign=middle;resizable=0;points=[];fontFamily=Times New Roman;fontSize=12;fontColor=#333333;")
        geo_s = ET.SubElement(lbl_s, "mxGeometry", x="-0.8", y="15", relative="1")
        geo_s.set("as", "geometry")
        ET.SubElement(geo_s, "mxPoint", x="0", y="0", **{"as": "offset"})
        
    # Multiplicity at Target
    if val_target:
        lbl_t = ET.SubElement(root, "mxCell", id=id_+"_t", value=val_target, parent=id_, vertex="1")
        lbl_t.set("style", "edgeLabel;html=1;align=center;verticalAlign=middle;resizable=0;points=[];fontFamily=Times New Roman;fontSize=12;fontColor=#333333;")
        geo_t = ET.SubElement(lbl_t, "mxGeometry", x="0.8", y="15", relative="1")
        geo_t.set("as", "geometry")
        ET.SubElement(geo_t, "mxPoint", x="0", y="0", **{"as": "offset"})

def save_xml(mxfile, filename):
    tree = ET.ElementTree(mxfile)
    ET.indent(tree, space="  ", level=0)
    path = os.path.join(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments", filename)
    with open(path, "wb") as f:
        f.write(b'<?xml version="1.0" encoding="UTF-8"?>\n')
        tree.write(f, encoding="utf-8")
    print(f"Generated: {path}")

def generate_class_diagram():
    mx, root = create_base_xml("Class Diagram")

    # 1. OPD (Top Left)
    add_class(root, "c_opd", "Opd", 
              ["- id: int", "- name: string", "- created_at: datetime"], 
              [], 
              60, 40, 160, 100)

    # 2. User (Below OPD)
    add_class(root, "c_user", "User", 
              ["- id: int", "- name: string", "- email: string", "- role: enum", "- opd_id: int"], 
              ["+ isAdmin(): bool", "+ isOpd(): bool"], 
              60, 200, 160, 140)

    # 3. Ticket (Center Main)
    add_class(root, "c_ticket", "Ticket", 
              ["- id: int", "- notification_id: int", "- ticket_number: string", "- tracking_number: string", "- ticket_time: datetime", "- platform: string", "- category: string", "- sub_category: string", "- status: enum", "- priority: enum", "- assigned_opd_id: int", "- sla_deadline: datetime"], 
              ["+ updateStatus()"], 
              320, 200, 220, 240)

    # 4. Notification (Top Right)
    add_class(root, "c_notification", "Notification", 
              ["- id: int", "- title: string", "- message: text", "- sender: string", "- permalink: string", "- is_read: bool", "- duplicate_status: enum"], 
              [], 
              620, 40, 200, 160)

    # 5. AIClassification (Below Notification)
    add_class(root, "c_ai", "AIClassification", 
              ["- id: int", "- notification_id: int", "- suggested_category: string", "- suggested_sub_category: string", "- priority: string", "- confidence: float"], 
              [], 
              620, 240, 200, 140)

    # 6. TicketStatusLog (Bottom Left)
    add_class(root, "c_log", "TicketStatusLog", 
              ["- id: int", "- ticket_id: int", "- from_status: string", "- to_status: string", "- changed_by: int", "- note: text"], 
              [], 
              60, 480, 180, 140)

    # 7. TicketResponse (Bottom Right)
    add_class(root, "c_response", "TicketResponse", 
              ["- id: int", "- ticket_id: int", "- user_id: int", "- message: text", "- attachment: string"], 
              [], 
              620, 480, 180, 120)

    # Connections / Relasi antar Class
    
    # OPD to User (1 to 0..*)
    add_edge(root, "e1", "1", "0..*", "c_opd", "c_user", "exitX=0.5;exitY=1;entryX=0.5;entryY=0;")
    
    # OPD to Ticket (1 to 0..*)
    add_edge(root, "e2", "1", "0..*", "c_opd", "c_ticket", "exitX=1;exitY=0.5;entryX=0.25;entryY=0;")
    
    # Ticket to Notification (0..1 to 1)
    add_edge(root, "e3", "0..1", "1", "c_ticket", "c_notification", "exitX=0.75;exitY=0;entryX=0;entryY=0.5;")
    
    # Notification to AI (1 to 0..1)
    add_edge(root, "e4", "1", "0..1", "c_notification", "c_ai", "exitX=0.5;exitY=1;entryX=0.5;entryY=0;")
    
    # Ticket to StatusLog (1 to 0..*)
    add_edge(root, "e5", "1", "0..*", "c_ticket", "c_log", "exitX=0;exitY=0.75;entryX=1;entryY=0.5;")
    
    # Ticket to Response (1 to 0..*)
    add_edge(root, "e6", "1", "0..*", "c_ticket", "c_response", "exitX=1;exitY=0.75;entryX=0;entryY=0.5;")
    
    # User to StatusLog (1 to 0..*)
    add_edge(root, "e7", "1", "0..*", "c_user", "c_log", "exitX=0.25;exitY=1;entryX=0.25;entryY=0;")
    
    # User to Response (1 to 0..*)
    add_edge(root, "e8", "1", "0..*", "c_user", "c_response", "exitX=0.75;exitY=1;entryX=0.5;entryY=1;edgeStyle=orthogonalEdgeStyle;")

    save_xml(mx, "Class Diagram Sistem Baru.drawio")

if __name__ == "__main__":
    generate_class_diagram()