import os
import xml.etree.ElementTree as ET

def create_base(name):
    mx = ET.Element("mxfile", host="app.diagrams.net")
    dia = ET.SubElement(mx, "diagram", name=name, id=name.replace(" ", "-"))
    model = ET.SubElement(dia, "mxGraphModel", dx="1200", dy="1200", grid="1", gridSize="10", guides="1", tooltips="1", connect="1", arrows="1", fold="1", page="1", pageScale="1", pageWidth="1400", pageHeight="1100", math="0", shadow="0")
    root = ET.SubElement(model, "root")
    ET.SubElement(root, "mxCell", id="0")
    ET.SubElement(root, "mxCell", id="1", parent="0")
    return mx, root

class ERDDiagram:
    def __init__(self, name):
        self.mx, self.root = create_base(name)
        self.entities = {}

    def add_entity(self, id_, name, attrs, x, y):
        h = 30 + len(attrs)*22 + 5
        max_attr_len = max([len(a) for a in attrs]) if attrs else 0
        longest = max(max_attr_len, len(name))
        w = max(240, longest * 7.5 + 40)
        
        self.entities[id_] = {'x': x, 'y': y, 'w': w, 'h': h}
        
        cell = ET.SubElement(self.root, "mxCell", id=id_, value=name, vertex="1", parent="1")
        cell.set("style", "swimlane;fontStyle=1;align=center;verticalAlign=top;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=0;resizeParent=1;recursiveResize=0;bounds=[0,0,200,100];fillColor=#FFF2CC;strokeColor=#D6B656;fontFamily=Times New Roman;fontSize=15;shadow=1;")
        ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width=str(w), height=str(h), **{"as": "geometry"})

        a_id = f"{id_}-a"
        attr_cell = ET.SubElement(self.root, "mxCell", id=a_id, value="", vertex="1", parent=id_)
        attr_cell.set("style", "text;html=1;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;fontFamily=Times New Roman;fontSize=14;")
        
        attr_lines = []
        for a in attrs:
            if "PK" in a:
                attr_lines.append(f"<b>{a}</b>")
            elif "FK" in a:
                attr_lines.append(f"<i>{a}</i>")
            else:
                attr_lines.append(a)
                
        attr_text = "<br>".join(attr_lines)
        attr_cell.set("value", attr_text)
        ET.SubElement(attr_cell, "mxGeometry", y="30", width=str(w), height=str(len(attrs)*22 + 5), **{"as": "geometry"})

    def add_relationship(self, id_, src, tgt, mult_src, mult_tgt, label="", waypoints=[], src_pt=None, tgt_pt=None):
        style = "endArrow=none;html=1;rounded=1;fontFamily=Times New Roman;fontSize=14;strokeColor=#333333;strokeWidth=1.5;edgeStyle=orthogonalEdgeStyle;jumpStyle=arc;jumpSize=10;"
        
        if src_pt:
            style += f"exitX={src_pt[0]};exitY={src_pt[1]};"
        if tgt_pt:
            style += f"entryX={tgt_pt[0]};entryY={tgt_pt[1]};"
            
        if mult_tgt == "0..*" or mult_tgt == "1..*":
            style += "endArrow=dash;endSize=8;"
        else:
            style += "endArrow=oval;endSize=6;"
            
        rel = ET.SubElement(self.root, "mxCell", id=id_, value=label, edge="1", parent="1", source=src, target=tgt)
        rel.set("style", style)
        geo = ET.SubElement(rel, "mxGeometry", relative="1", **{"as": "geometry"})
        
        if waypoints:
            arr = ET.SubElement(geo, "Array", **{"as": "points"})
            for (wx, wy) in waypoints:
                ET.SubElement(arr, "mxPoint", x=str(wx), y=str(wy))
        
        if mult_src:
            s_label = ET.SubElement(self.root, "mxCell", id=f"{id_}-s", value=mult_src, vertex="1", parent=id_)
            s_label.set("style", "edgeLabel;html=1;align=center;verticalAlign=bottom;resizable=0;points=[];fontFamily=Times New Roman;labelBackgroundColor=#ffffff;")
            ET.SubElement(s_label, "mxGeometry", x="-0.8", relative="1", **{"as": "geometry"})
            
        if mult_tgt:
            t_label = ET.SubElement(self.root, "mxCell", id=f"{id_}-t", value=mult_tgt, vertex="1", parent=id_)
            t_label.set("style", "edgeLabel;html=1;align=center;verticalAlign=bottom;resizable=0;points=[];fontFamily=Times New Roman;labelBackgroundColor=#ffffff;")
            ET.SubElement(t_label, "mxGeometry", x="0.8", relative="1", **{"as": "geometry"})

    def save(self, filename):
        tree = ET.ElementTree(self.mx)
        ET.indent(tree, space="  ", level=0)
        path = os.path.join(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments", filename)
        with open(path, "wb") as f:
            f.write(b'<?xml version="1.0" encoding="UTF-8"?>\n')
            tree.write(f, encoding="utf-8")
        print(f"Generated: {path}")

def make_erd_diagram():
    erd = ERDDiagram("Entity Relationship Diagram")
    
    # Let's adjust layouts to prevent intersecting lines
    # users (Left, Middle-Top)
    erd.add_entity("users", "users", 
                   ["id (PK): bigint", "opd_id (FK): bigint", "name: varchar", "username: varchar", "email: varchar", "password: varchar", "role: enum", "profile_photo: varchar"], 
                   50, 200)
                   
    # opds (Center, Top)
    erd.add_entity("opds", "opds", 
                   ["id (PK): bigint", "name: varchar", "created_at: timestamp", "updated_at: timestamp"], 
                   450, 50)
                   
    # categories (Right, Top)
    erd.add_entity("categories", "categories", 
                   ["id (PK): bigint", "name: varchar", "created_at: timestamp"], 
                   1250, 50)
                   
    # sub_categories (Right-Center, Top)
    erd.add_entity("sub_categories", "sub_categories", 
                   ["id (PK): bigint", "category_id (FK): bigint", "opd_id (FK): bigint", "name: varchar", "created_at: timestamp"], 
                   850, 50)

    # tickets (Center, Middle)
    erd.add_entity("tickets", "tickets", 
                   ["id (PK): bigint", "notification_id (FK): bigint", "assigned_opd_id (FK): bigint", "ticket_number: varchar", "tracking_number: varchar", "platform: varchar", "reporter_name: varchar", "complaint: text", "priority: enum", "status: enum", "sla_deadline: timestamp"], 
                   450, 380)
                   
    # notifications (Right, Middle)
    erd.add_entity("notifications", "notifications", 
                   ["id (PK): bigint", "duplicate_of_id (FK): bigint", "title: varchar", "message: text", "sender: varchar", "permalink: varchar", "is_read: boolean", "duplicate_status: varchar"], 
                   850, 380)

    # ticket_status_logs (Left, Bottom)
    erd.add_entity("ticket_status_logs", "ticket_status_logs", 
                   ["id (PK): bigint", "ticket_id (FK): bigint", "changed_by (FK): bigint", "from_status: varchar", "to_status: varchar", "note: text", "created_at: timestamp"], 
                   50, 760)

    # ticket_responses (Center, Bottom)
    erd.add_entity("ticket_responses", "ticket_responses", 
                   ["id (PK): bigint", "ticket_id (FK): bigint", "user_id (FK): bigint", "message: text", "attachment: varchar", "created_at: timestamp"], 
                   450, 760)

    # ai_classifications (Right, Bottom)
    erd.add_entity("ai_classifications", "ai_classifications", 
                   ["id (PK): bigint", "notification_id (FK): bigint", "suggested_category: varchar", "suggested_sub_category: varchar", "suggested_opds: json", "priority: enum", "confidence: decimal"], 
                   850, 760)

    # ==========================
    # RELATIONSHIPS WITH SPECIFIC ROUTING & ANCHORS
    # ==========================
    
    # 1. opds to users
    erd.add_relationship("R1", "opds", "users", "1", "0..*", "memiliki", src_pt=(0, 0.5), tgt_pt=(0.5, 0))
    
    # 2. opds to sub_categories (1 to Many)
    erd.add_relationship("R2", "opds", "sub_categories", "1", "0..*", "menangani", src_pt=(1, 0.5), tgt_pt=(0, 0.5))
    
    # 3. categories to sub_categories (1 to Many)
    erd.add_relationship("R3", "categories", "sub_categories", "1", "0..*", "mencakup", src_pt=(0, 0.5), tgt_pt=(1, 0.5))
    
    # 4. opds to tickets (1 to Many) - Straight down
    erd.add_relationship("R4", "opds", "tickets", "1", "0..*", "ditugaskan ke", src_pt=(0.5, 1), tgt_pt=(0.5, 0))
    
    # 5. notifications to tickets (1 to 0..1)
    erd.add_relationship("R5", "notifications", "tickets", "1", "0..1", "menghasilkan", src_pt=(0, 0.5), tgt_pt=(1, 0.5))
    
    # 6. notifications to ai_classifications (1 to 0..1) - Straight down
    erd.add_relationship("R6", "notifications", "ai_classifications", "1", "0..1", "dianalisis oleh", src_pt=(0.5, 1), tgt_pt=(0.5, 0))
    
    # 7. tickets to ticket_status_logs (1 to Many)
    # Routed down then left to avoid crossing users' line
    erd.add_relationship("R7", "tickets", "ticket_status_logs", "1", "0..*", "mencatat", src_pt=(0.2, 1), tgt_pt=(0.5, 0), waypoints=[(500, 680), (170, 680)])
    
    # 8. tickets to ticket_responses (1 to Many) - Straight down
    erd.add_relationship("R8", "tickets", "ticket_responses", "1", "0..*", "memiliki", src_pt=(0.5, 1), tgt_pt=(0.5, 0))

    # 9. users to ticket_status_logs (1 to Many) - Straight vertical down on the left side of logs
    erd.add_relationship("R9", "users", "ticket_status_logs", "1", "0..*", "mengubah", src_pt=(0.2, 1), tgt_pt=(0.2, 0))
    
    # 10. users to ticket_responses (1 to Many) - Routed cleanly across the middle
    erd.add_relationship("R10", "users", "ticket_responses", "1", "0..*", "menjawab", src_pt=(0.8, 1), tgt_pt=(0.1, 0), waypoints=[(242, 640), (474, 640)])
    
    # 11. notifications self-reference (duplicates) - Tight loop on top-right
    erd.add_relationship("R11", "notifications", "notifications", "1", "0..*", "duplicate of", src_pt=(1, 0.2), tgt_pt=(1, 0.8), waypoints=[(1120, 420), (1120, 520)])

    erd.save("Entity Relationship Diagram SIMADU-KMC.drawio")

if __name__ == "__main__":
    make_erd_diagram()
