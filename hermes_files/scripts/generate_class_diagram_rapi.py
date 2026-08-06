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

class AdvancedClassDiagram:
    def __init__(self, name):
        self.mx, self.root = create_base(name)
        self.classes = {}

    def add_class(self, id_, name, attrs, methods, x, y):
        # Calculate dynamic width & height based on text length
        max_attr_len = max([len(a) for a in attrs]) if attrs else 0
        max_meth_len = max([len(m) for m in methods]) if methods else 0
        longest = max(max_attr_len, max_meth_len, len(name))
        
        w = max(240, longest * 7.5 + 40)
        h = 30 + len(attrs)*22 + len(methods)*22 + (15 if methods else 5)
        
        self.classes[id_] = {'x': x, 'y': y, 'w': w, 'h': h}
        
        # Base container
        c_id = id_
        cell = ET.SubElement(self.root, "mxCell", id=c_id, value=name, vertex="1", parent="1")
        cell.set("style", "swimlane;fontStyle=1;align=center;verticalAlign=top;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=0;resizeParent=1;recursiveResize=0;bounds=[0,0,200,100];fillColor=#DAE8FC;strokeColor=#6C8EBF;fontFamily=Times New Roman;fontSize=15;shadow=1;")
        ET.SubElement(cell, "mxGeometry", x=str(x), y=str(y), width=str(w), height=str(h), **{"as": "geometry"})

        # Attributes
        a_id = f"{id_}-a"
        attr_cell = ET.SubElement(self.root, "mxCell", id=a_id, value="", vertex="1", parent=c_id)
        attr_cell.set("style", "text;html=1;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;fontFamily=Times New Roman;fontSize=14;")
        attr_text = "<br>".join([f"- {a}" for a in attrs])
        attr_cell.set("value", attr_text)
        ET.SubElement(attr_cell, "mxGeometry", y="30", width=str(w), height=str(len(attrs)*22 + 5), **{"as": "geometry"})

        # Line separator
        l_id = f"{id_}-l"
        line = ET.SubElement(self.root, "mxCell", id=l_id, value="", vertex="1", parent=c_id)
        line.set("style", "line;strokeWidth=1;fillColor=none;align=left;verticalAlign=middle;spacingTop=-1;spacingLeft=3;spacingRight=3;rotatable=0;labelPosition=right;points=[];portConstraint=eastwest;")
        ET.SubElement(line, "mxGeometry", y=str(30 + len(attrs)*22 + 5), width=str(w), height="8", **{"as": "geometry"})

        # Methods
        m_id = f"{id_}-m"
        m_cell = ET.SubElement(self.root, "mxCell", id=m_id, value="", vertex="1", parent=c_id)
        m_cell.set("style", "text;html=1;strokeColor=none;fillColor=none;align=left;verticalAlign=top;spacingLeft=4;spacingRight=4;overflow=hidden;rotatable=0;fontFamily=Times New Roman;fontSize=14;")
        m_text = "<br>".join([f"+ {m}()" for m in methods])
        m_cell.set("value", m_text)
        ET.SubElement(m_cell, "mxGeometry", y=str(30 + len(attrs)*22 + 13), width=str(w), height=str(len(methods)*22 + 5), **{"as": "geometry"})

    def add_relation_routed(self, id_, src, tgt, mult_src, mult_tgt, type_="association", waypoints=[]):
        style = "endArrow=none;html=1;rounded=1;fontFamily=Times New Roman;fontSize=14;strokeColor=#333333;strokeWidth=1.5;edgeStyle=orthogonalEdgeStyle;jumpStyle=arc;jumpSize=10;"
        
        if type_ == "composition":
            style += "startArrow=diamondThin;startFill=1;"
        elif type_ == "aggregation":
            style += "startArrow=diamondThin;startFill=0;"
        elif type_ == "navigable":
            style += "endArrow=open;"
        
        rel = ET.SubElement(self.root, "mxCell", id=id_, edge="1", parent="1", source=src, target=tgt)
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

def make_clean_class_diagram():
    cd = AdvancedClassDiagram("Class Diagram")
    
    # Grid Layout Strategy to prevent overlap
    # Column 1 (X: 50): User, TicketStatusLog
    # Column 2 (X: 450): Opd, Ticket, TicketResponse
    # Column 3 (X: 850): SubCategory, Notification, AIClassification
    # Column 4 (X: 1250): Category
    
    # Row 1 (Y: 50)
    cd.add_class("User", "User", 
                 ["id: integer", "opd_id: integer", "name: string", "username: string", "email: string", "role: string", "profile_photo: string"], 
                 ["opd", "isAdmin", "isOpd"], 
                 50, 50)
                 
    cd.add_class("Opd", "Opd", 
                 ["id: integer", "name: string", "created_at: timestamp", "updated_at: timestamp"], 
                 ["users", "tickets", "subCategories"], 
                 450, 50)
                 
    cd.add_class("SubCategory", "SubCategory", 
                 ["id: integer", "category_id: integer", "opd_id: integer", "name: string"], 
                 ["category", "opd"], 
                 850, 50)

    cd.add_class("Category", "Category", 
                 ["id: integer", "name: string"], 
                 ["subCategories"], 
                 1250, 50)
                 
    # Row 2 (Y: 380)
    cd.add_class("Ticket", "Ticket", 
                 ["id: integer", "notification_id: integer", "assigned_opd_id: integer", "ticket_number: string", "tracking_number: string", "platform: string", "reporter_name: string", "complaint: text", "priority: string", "status: string", "sla_deadline: timestamp"], 
                 ["notification", "assignedOpd", "statusLogs", "responses", "updateStatus"], 
                 450, 380)
                 
    cd.add_class("Notification", "Notification", 
                 ["id: integer", "duplicate_of_id: integer", "title: string", "message: text", "sender: string", "permalink: string", "is_read: boolean", "duplicate_status: string"], 
                 ["ticket", "aiClassification", "duplicates"], 
                 850, 380)

    # Row 3 (Y: 760)
    cd.add_class("TicketStatusLog", "TicketStatusLog", 
                 ["id: integer", "ticket_id: integer", "changed_by: integer", "from_status: string", "to_status: string", "note: text"], 
                 ["ticket", "user"], 
                 50, 760)

    cd.add_class("TicketResponse", "TicketResponse", 
                 ["id: integer", "ticket_id: integer", "user_id: integer", "message: text", "attachment: string"], 
                 ["ticket", "user"], 
                 450, 760)

    cd.add_class("AIClassification", "AIClassification", 
                 ["id: integer", "notification_id: integer", "suggested_category: string", "suggested_opds: json", "priority: string", "confidence: float"], 
                 ["notification"], 
                 850, 760)


    # ==========================
    # RELATIONS WITH MANUAL WAYPOINTS TO FIX WEIRD ARROWS
    # ==========================
    
    # 1. Opd to User (Row 1, straight horizontal)
    cd.add_relation_routed("R1", "Opd", "User", "1", "0..*", "navigable", waypoints=[(390, 100)])
    
    # 2. Category to SubCategory (Row 1, straight horizontal)
    cd.add_relation_routed("R2", "Category", "SubCategory", "1", "0..*", "navigable")
    
    # 3. Opd to SubCategory (Row 1, straight horizontal)
    cd.add_relation_routed("R3", "Opd", "SubCategory", "1", "0..*", "navigable", waypoints=[(730, 100)])
    
    # 4. Notification to Ticket (Row 2, straight horizontal)
    cd.add_relation_routed("R4", "Notification", "Ticket", "1", "0..1", "navigable")
    
    # 5. Opd to Ticket (Column 2, straight vertical)
    cd.add_relation_routed("R5", "Opd", "Ticket", "1", "0..*", "navigable")
    
    # 6. Notification to AIClassification (Column 3, straight vertical)
    cd.add_relation_routed("R6", "Notification", "AIClassification", "1", "0..1", "composition")
    
    # 7. Ticket to TicketStatusLog (Ticket -> TicketStatusLog)
    cd.add_relation_routed("R7", "Ticket", "TicketStatusLog", "1", "0..*", "composition", waypoints=[(430, 680), (160, 680)])
    
    # 8. Ticket to TicketResponse (Ticket -> TicketResponse)
    cd.add_relation_routed("R8", "Ticket", "TicketResponse", "1", "0..*", "composition", waypoints=[(580, 680), (850, 680)])

    # 9. User to TicketStatusLog (Clean routing down left side)
    cd.add_relation_routed("R9", "User", "TicketStatusLog", "1", "0..*", "navigable", waypoints=[(90, 310), (90, 720)])
    
    # 10. User to TicketResponse (Straight line across middle, below Ticket)
    # Using specific anchor points so it doesn't loop down to the bottom of the page
    cd.add_relation_routed("R10", "User", "TicketResponse", "1", "0..*", "navigable", waypoints=[(110, 330), (110, 700), (810, 700)])
    
    # 11. Notification self-reference (Duplicates) - Tight loop on top-right
    cd.add_relation_routed("R11", "Notification", "Notification", "1", "0..*", "navigable", waypoints=[(1090, 420), (1120, 420), (1120, 350), (1030, 350)])

    cd.save("Class Diagram SIMADU-KMC Final.drawio")

if __name__ == "__main__":
    make_clean_class_diagram()
