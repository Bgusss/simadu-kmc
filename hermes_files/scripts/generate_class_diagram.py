import os
import xml.etree.ElementTree as ET

def create_base(name):
    mx = ET.Element("mxfile", host="app.diagrams.net")
    dia = ET.SubElement(mx, "diagram", name=name, id=name.replace(" ", "-"))
    model = ET.SubElement(dia, "mxGraphModel", dx="1000", dy="1000", grid="1", gridSize="10", guides="1", tooltips="1", connect="1", arrows="1", fold="1", page="1", pageScale="1", pageWidth="1100", pageHeight="850", math="0", shadow="0")
    root = ET.SubElement(model, "root")
    ET.SubElement(root, "mxCell", id="0")
    ET.SubElement(root, "mxCell", id="1", parent="0")
    return mx, root

class ClassDiagram:
    def __init__(self, name):
        self.mx, self.root = create_base(name)

    def add_class(self, id_, name, attrs, methods, x, y):
        # Base container
        # Calculate dynamic width based on the longest text length
        max_attr_len = max([len(a) for a in attrs]) if attrs else 0
        max_meth_len = max([len(m) for m in methods]) if methods else 0
        max_name_len = len(name)
        
        longest = max(max_attr_len, max_meth_len, max_name_len)
        # Approximate pixels per character, with a baseline width
        w = max(220, longest * 7.5 + 40)
        
        h = 30 + len(attrs)*22 + len(methods)*22 + (15 if methods else 5)
        c_id = id_
        cell = ET.SubElement(self.root, "mxCell", id=c_id, value=name, vertex="1", parent="1")
        cell.set("style", "swimlane;fontStyle=1;align=center;verticalAlign=top;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=0;resizeParent=1;recursiveResize=0;bounds=[0,0,200,100];fillColor=#DAE8FC;strokeColor=#6C8EBF;fontFamily=Times New Roman;fontSize=14;")
        ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width=str(w), height=str(h), **{"as": "geometry"})

        # Attributes container
        a_id = f"{id_}-a"
        attr_cell = ET.SubElement(self.root, "mxCell", id=a_id, value="", vertex="1", parent=c_id)
        attr_cell.set("style", "text;html=1;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;fontFamily=Times New Roman;fontSize=13;")
        attr_text = "<br>".join([f"- {a}" for a in attrs])
        attr_cell.set("value", attr_text)
        ET.SubElement(attr_cell, "mxGeometry", y="30", width=str(w), height=str(len(attrs)*22 + 5), **{"as": "geometry"})

        # Line separator
        l_id = f"{id_}-l"
        line = ET.SubElement(self.root, "mxCell", id=l_id, value="", vertex="1", parent=c_id)
        line.set("style", "line;strokeWidth=1;fillColor=none;align=left;verticalAlign=middle;spacingTop=-1;spacingLeft=3;spacingRight=3;rotatable=0;labelPosition=right;points=[];portConstraint=eastwest;")
        ET.SubElement(line, "mxGeometry", y=str(30 + len(attrs)*22 + 5), width=str(w), height="8", **{"as": "geometry"})

        # Methods container
        m_id = f"{id_}-m"
        m_cell = ET.SubElement(self.root, "mxCell", id=m_id, value="", vertex="1", parent=c_id)
        m_cell.set("style", "text;html=1;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;fontFamily=Times New Roman;fontSize=13;")
        m_text = "<br>".join([f"+ {m}()" for m in methods])
        m_cell.set("value", m_text)
        ET.SubElement(m_cell, "mxGeometry", y=str(30 + len(attrs)*22 + 13), width=str(w), height=str(len(methods)*22 + 5), **{"as": "geometry"})
        
    def add_relation(self, id_, src, tgt, mult_src, mult_tgt, type_="association"):
        # type_: association, composition, aggregation
        style = "endArrow=none;html=1;rounded=0;fontFamily=Times New Roman;fontSize=13;strokeColor=#333333;strokeWidth=1.5;"
        if type_ == "composition":
            style += "startArrow=diamondThin;startFill=1;"
        elif type_ == "aggregation":
            style += "startArrow=diamondThin;startFill=0;"
        elif type_ == "navigable":
            style += "endArrow=open;"
        
        rel = ET.SubElement(self.root, "mxCell", id=id_, edge="1", parent="1", source=f"{src}-a", target=f"{tgt}-a")
        rel.set("style", style)
        geo = ET.SubElement(rel, "mxGeometry", relative="1", **{"as": "geometry"})
        
        # Source multiplicity
        if mult_src:
            s_label = ET.SubElement(self.root, "mxCell", id=f"{id_}-s", value=mult_src, vertex="1", parent=id_)
            s_label.set("style", "edgeLabel;html=1;align=center;verticalAlign=bottom;resizable=0;points=[];fontFamily=Times New Roman;")
            ET.SubElement(s_label, "mxGeometry", x="-0.8", relative="1", **{"as": "geometry"})
            
        # Target multiplicity
        if mult_tgt:
            t_label = ET.SubElement(self.root, "mxCell", id=f"{id_}-t", value=mult_tgt, vertex="1", parent=id_)
            t_label.set("style", "edgeLabel;html=1;align=center;verticalAlign=bottom;resizable=0;points=[];fontFamily=Times New Roman;")
            ET.SubElement(t_label, "mxGeometry", x="0.8", relative="1", **{"as": "geometry"})

    def save(self, filename):
        tree = ET.ElementTree(self.mx)
        ET.indent(tree, space="  ", level=0)
        path = os.path.join(r"C:\laragon\www\SIMADU-KMC\.hermes\desktop-attachments", filename)
        with open(path, "wb") as f:
            f.write(b'<?xml version="1.0" encoding="UTF-8"?>\n')
            tree.write(f, encoding="utf-8")
        print(f"Generated: {path}")

def make_class_diagram():
    cd = ClassDiagram("Class Diagram")
    
    # Define classes based on system analysis
    
    # 1. Opd
    cd.add_class("Opd", "Opd", 
                 ["id: integer", "name: string", "created_at: timestamp", "updated_at: timestamp"], 
                 ["users", "tickets", "subCategories"], 
                 420, 40)
                 
    # 2. User
    cd.add_class("User", "User", 
                 ["id: integer", "opd_id: integer", "name: string", "username: string", "email: string", "role: string", "profile_photo: string"], 
                 ["opd", "isAdmin", "isOpd"], 
                 100, 40)
                 
    # 3. Category
    cd.add_class("Category", "Category", 
                 ["id: integer", "name: string"], 
                 ["subCategories"], 
                 740, 40)
                 
    # 4. SubCategory
    cd.add_class("SubCategory", "SubCategory", 
                 ["id: integer", "category_id: integer", "opd_id: integer", "name: string"], 
                 ["category", "opd"], 
                 740, 260)
                 
    # 5. Ticket
    cd.add_class("Ticket", "Ticket", 
                 ["id: integer", "notification_id: integer", "assigned_opd_id: integer", "ticket_number: string", "tracking_number: string", "platform: string", "reporter_name: string", "complaint: text", "priority: string", "status: string", "sla_deadline: timestamp"], 
                 ["notification", "assignedOpd", "statusLogs", "responses", "updateStatus"], 
                 420, 260)
                 
    # 6. Notification
    cd.add_class("Notification", "Notification", 
                 ["id: integer", "duplicate_of_id: integer", "title: string", "message: text", "sender: string", "permalink: string", "is_read: boolean", "duplicate_status: string"], 
                 ["ticket", "aiClassification", "duplicates"], 
                 100, 260)
                 
    # 7. AIClassification
    cd.add_class("AIClassification", "AIClassification", 
                 ["id: integer", "notification_id: integer", "suggested_category: string", "suggested_opds: json", "priority: string", "confidence: float"], 
                 ["notification"], 
                 100, 560)
                 
    # 8. TicketStatusLog
    cd.add_class("TicketStatusLog", "TicketStatusLog", 
                 ["id: integer", "ticket_id: integer", "changed_by: integer", "from_status: string", "to_status: string", "note: text"], 
                 ["ticket", "user"], 
                 420, 640)
                 
    # 9. TicketResponse
    cd.add_class("TicketResponse", "TicketResponse", 
                 ["id: integer", "ticket_id: integer", "user_id: integer", "message: text", "attachment: string"], 
                 ["ticket", "user"], 
                 740, 560)

    # Relations
    # Opd to User (1 to Many)
    cd.add_relation("R1", "Opd", "User", "1", "0..*", "navigable")
    
    # Category to SubCategory (1 to Many)
    cd.add_relation("R2", "Category", "SubCategory", "1", "0..*", "navigable")
    
    # Opd to SubCategory (1 to Many)
    cd.add_relation("R3", "Opd", "SubCategory", "1", "0..*", "navigable")
    
    # Notification to Ticket (1 to 0..1)
    cd.add_relation("R4", "Notification", "Ticket", "1", "0..1", "navigable")
    
    # Opd to Ticket (1 to Many)
    cd.add_relation("R5", "Opd", "Ticket", "1", "0..*", "navigable")
    
    # Notification to AIClassification (1 to 1)
    cd.add_relation("R6", "Notification", "AIClassification", "1", "0..1", "composition")
    
    # Ticket to TicketStatusLog (1 to Many)
    cd.add_relation("R7", "Ticket", "TicketStatusLog", "1", "0..*", "composition")
    
    # Ticket to TicketResponse (1 to Many)
    cd.add_relation("R8", "Ticket", "TicketResponse", "1", "0..*", "composition")

    cd.save("Class Diagram SIMADU-KMC.drawio")

if __name__ == "__main__":
    make_class_diagram()
