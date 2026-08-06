from PIL import Image, ImageDraw, ImageFont
from math import atan2, cos, sin, pi
from pathlib import Path

W, H = 2400, 1400
OUT = Path(r"C:\laragon\www\SIMADU-KMC\.hermes\md\GAMBAR_3_2_ARSITEKTUR_SISTEM.png")

img = Image.new("RGB", (W, H), "white")
d = ImageDraw.Draw(img)

# Color palette inspired by the senior-report examples.
NAVY = "#3B638F"
BLUE = "#5C8FBE"
LIGHT_BLUE = "#DCEAF7"
MID_BLUE = "#8CB5D9"
DARK = "#1F2933"
GRAY = "#667085"
LIGHT_GRAY = "#EDF1F5"
CLOUD = "#D9E8F4"
PURPLE = "#8265A7"
BLACK = "#111111"

FONT_DIRS = [
    r"C:\Windows\Fonts",
    r"/c/Windows/Fonts",
    r"/usr/share/fonts/truetype/dejavu",
]

def load_font(names, size, bold=False):
    candidates = []
    for folder in FONT_DIRS:
        for name in names:
            candidates.append(str(Path(folder) / name))
    if bold:
        candidates += ["DejaVuSans-Bold.ttf", "arialbd.ttf"]
    else:
        candidates += ["DejaVuSans.ttf", "arial.ttf"]
    for path in candidates:
        try:
            return ImageFont.truetype(path, size)
        except OSError:
            continue
    return ImageFont.load_default()

FONT_LABEL = load_font(["times.ttf", "Times New Roman.ttf", "arial.ttf"], 30)
FONT_SMALL = load_font(["times.ttf", "Times New Roman.ttf", "arial.ttf"], 23)
FONT_TINY = load_font(["times.ttf", "Times New Roman.ttf", "arial.ttf"], 20)
FONT_BOLD = load_font(["timesbd.ttf", "Times New Roman Bold.ttf", "arialbd.ttf"], 30, bold=True)
FONT_TITLE = load_font(["timesbd.ttf", "Times New Roman Bold.ttf", "arialbd.ttf"], 35, bold=True)


def centered_text(x, y, text, font=FONT_LABEL, fill=BLACK, spacing=6):
    d.multiline_text((x, y), text, font=font, fill=fill, anchor="mm", align="center", spacing=spacing)


def line_arrow(start, end, label=None, label_offset=(0, 0), width=4, color=BLACK, font=FONT_TINY):
    x1, y1 = start
    x2, y2 = end
    d.line((x1, y1, x2, y2), fill=color, width=width)
    angle = atan2(y2 - y1, x2 - x1)
    head = 17
    wing = 8
    p1 = (x2, y2)
    p2 = (x2 - head * cos(angle) + wing * sin(angle), y2 - head * sin(angle) - wing * cos(angle))
    p3 = (x2 - head * cos(angle) - wing * sin(angle), y2 - head * sin(angle) + wing * cos(angle))
    d.polygon((p1, p2, p3), fill=color)
    if label:
        mx, my = (x1 + x2) / 2 + label_offset[0], (y1 + y2) / 2 + label_offset[1]
        # White backing keeps labels readable where paths are close.
        box = d.textbbox((mx, my), label, font=font, anchor="mm")
        d.rounded_rectangle((box[0] - 5, box[1] - 2, box[2] + 5, box[3] + 2), radius=3, fill="white")
        d.text((mx, my), label, font=font, fill=BLACK, anchor="mm")


def draw_database(cx, y, w=210, h=135):
    # Cylinder-style database server.
    body_top = y + 25
    body_bottom = y + h - 25
    d.rounded_rectangle((cx - w//2, body_top, cx + w//2, body_bottom), radius=12, fill=NAVY, outline=DARK, width=3)
    d.ellipse((cx - w//2, y, cx + w//2, y + 50), fill=LIGHT_BLUE, outline=DARK, width=3)
    d.ellipse((cx - w//2, body_bottom - 25, cx + w//2, body_bottom + 25), fill=BLUE, outline=DARK, width=3)
    for yy in (body_top + 32, body_top + 65):
        d.arc((cx - w//2 + 5, yy - 15, cx + w//2 - 5, yy + 15), 0, 180, fill="#B9D3E8", width=3)
        d.line((cx - w//2 + 5, yy, cx + w//2 - 5, yy), fill="#B9D3E8", width=3)
    centered_text(cx, y + h + 35, "Database Server\n(MySQL)", FONT_LABEL)


def draw_web_server(cx, y, w=155, h=180):
    # Blue server rack with a small web/globe mark.
    d.rounded_rectangle((cx - w//2, y, cx + w//2, y + h), radius=14, fill=BLUE, outline=DARK, width=3)
    d.rounded_rectangle((cx - w//2 + 13, y + 15, cx + w//2 - 13, y + 55), radius=6, fill=LIGHT_BLUE, outline="#335B84", width=2)
    for yy in (y + 72, y + 108, y + 144):
        d.rounded_rectangle((cx - w//2 + 16, yy, cx + w//2 - 16, yy + 20), radius=4, fill="#DDEAF5", outline="#335B84", width=2)
        d.ellipse((cx - w//2 + 27, yy + 5, cx - w//2 + 37, yy + 15), fill="#44A36F")
        d.line((cx - w//2 + 50, yy + 10, cx + w//2 - 30, yy + 10), fill="#678EB5", width=3)
    # Globe overlay.
    gx, gy, gr = cx + w//2 - 10, y + h - 15, 34
    d.ellipse((gx-gr, gy-gr, gx+gr, gy+gr), fill="#F7B548", outline=DARK, width=2)
    d.arc((gx-gr+6, gy-gr+6, gx+gr-6, gy+gr-6), 80, 280, fill="#2C70A8", width=3)
    d.line((gx-gr+5, gy, gx+gr-5, gy), fill="#2C70A8", width=3)
    centered_text(cx, y + h + 37, "Web Server\n(Aplikasi Laravel)", FONT_LABEL)


def draw_ai_service(x, y, w=245, h=115):
    d.rounded_rectangle((x, y, x+w, y+h), radius=16, fill="#F1EDF7", outline=PURPLE, width=4)
    # Small sparkle icon.
    sx, sy = x + 40, y + 40
    d.polygon([(sx, sy-20), (sx+7, sy-7), (sx+20, sy), (sx+7, sy+7), (sx, sy+20), (sx-7, sy+7), (sx-20, sy), (sx-7, sy-7)], fill=PURPLE)
    centered_text(x + 145, y + 42, "Google AI Studio", FONT_BOLD)
    centered_text(x + 145, y + 80, "Gemma 4 31B IT", FONT_SMALL, GRAY)


def draw_cloud(cx, y, w=255, h=125):
    # Cloud icon with Wi-Fi signal.
    d.ellipse((cx-w//2+15, y+35, cx-w//2+135, y+h-5), fill=CLOUD, outline=BLUE, width=3)
    d.ellipse((cx-55, y+10, cx+65, y+h-5), fill=CLOUD, outline=BLUE, width=3)
    d.ellipse((cx+10, y+34, cx+w//2-5, y+h-5), fill=CLOUD, outline=BLUE, width=3)
    d.rounded_rectangle((cx-w//2+40, y+60, cx+w//2-15, y+h-5), radius=30, fill=CLOUD, outline=BLUE, width=3)
    # Repaint the shared cloud base without boundaries.
    for radius, yoff in [(50, 55), (33, 70)]:
        d.arc((cx-radius, y+yoff-radius//2, cx+radius, y+yoff+radius//2), 200, 340, fill=NAVY, width=5)
    d.ellipse((cx-7, y+92, cx+7, y+106), fill=NAVY)
    centered_text(cx, y + h + 34, "Internet", FONT_LABEL)


def draw_pc(cx, y, label, sublabel=None, mobile=False):
    # Monitor with optional CPU tower.
    sw, sh = (145, 92) if not mobile else (105, 132)
    x1, y1 = cx - sw//2, y
    d.rounded_rectangle((x1, y1, x1+sw, y1+sh), radius=6, fill=LIGHT_GRAY, outline=DARK, width=3)
    d.rectangle((x1+10, y1+10, x1+sw-10, y1+sh-12), fill="#E7F2FA", outline="#8BA9C2", width=2)
    if not mobile:
        d.line((cx, y1+sh, cx, y1+sh+25), fill=DARK, width=5)
        d.line((cx-42, y1+sh+27, cx+42, y1+sh+27), fill=DARK, width=5)
    else:
        d.rounded_rectangle((x1+sw+14, y1+17, x1+sw+39, y1+sh), radius=3, fill="#C7D2DB", outline=DARK, width=2)
        d.ellipse((x1+sw+23, y1+28, x1+sw+30, y1+35), fill="#42A36F")
    centered_text(cx, y1+sh+65, label, FONT_LABEL)
    if sublabel:
        centered_text(cx, y1+sh+98, sublabel, FONT_SMALL, GRAY)


def draw_scraper_pc(cx, y):
    draw_pc(cx, y, "Komputer Lokal", "Playwright dan Artisan")
    # Terminal prompt over screen.
    d.rounded_rectangle((cx-51, y+27, cx+51, y+58), radius=4, fill="#28343C")
    d.text((cx-42, y+42), ">_ sync", font=FONT_TINY, fill="#BEE3C6", anchor="lm")


def draw_social_source(cx, y):
    # Two recognisable but simple social icons.
    d.rounded_rectangle((cx-108, y, cx+108, y+110), radius=18, fill="#F8FAFC", outline="#A7B4C1", width=3)
    d.ellipse((cx-82, y+24, cx-30, y+76), fill="#3D70AD", outline=DARK, width=2)
    centered_text(cx-56, y+50, "f", FONT_BOLD, "white")
    d.rounded_rectangle((cx+18, y+24, cx+70, y+76), radius=14, fill="#CD4A82", outline=DARK, width=2)
    d.ellipse((cx+33, y+39, cx+55, y+61), outline="white", width=3)
    d.ellipse((cx+58, y+32, cx+63, y+37), fill="white")
    centered_text(cx, y+145, "Facebook dan Instagram", FONT_LABEL)
    centered_text(cx, y+178, "Sumber Aduan", FONT_SMALL, GRAY)


def draw_person(cx, y, label, group=False):
    if group:
        for ox, scale in [(-32, 0.78), (32, 0.78), (0, 1.0)]:
            r = int(22*scale)
            d.ellipse((cx+ox-r, y-r, cx+ox+r, y+r), fill=LIGHT_BLUE, outline=NAVY, width=3)
            d.pieslice((cx+ox-37*scale, y+r-5, cx+ox+37*scale, y+r+78*scale), 180, 360, fill=BLUE, outline=NAVY, width=3)
    else:
        d.ellipse((cx-27, y-27, cx+27, y+27), fill=LIGHT_BLUE, outline=NAVY, width=3)
        d.pieslice((cx-45, y+18, cx+45, y+123), 180, 360, fill=BLUE, outline=NAVY, width=3)
    centered_text(cx, y+145 if not group else y+150, label, FONT_LABEL)


def draw_boundary(box, label):
    x1, y1, x2, y2 = box
    d.rounded_rectangle((x1, y1, x2, y2), radius=18, outline="#8A98A8", width=3)
    # Dashed effect manually across top/side; label has white backing.
    for x in range(x1+8, x2-8, 20):
        d.line((x, y1, min(x+10, x2-8), y1), fill="#8A98A8", width=3)
    box_t = d.textbbox((x1+18, y1-5), label, font=FONT_SMALL, anchor="lm")
    d.rectangle((box_t[0]-7, box_t[1]-4, box_t[2]+7, box_t[3]+4), fill="white")
    d.text((x1+18, y1-5), label, font=FONT_SMALL, fill=GRAY, anchor="lm")

# Thin border and heading, matching a printable academic diagram.
d.rectangle((25, 25, W-25, H-25), outline="#1F2933", width=3)
centered_text(W//2, 63, "ARSITEKTUR SISTEM INFORMASI MANAJEMEN ADUAN MULTI CHANNEL KMC", FONT_TITLE)

# Boundaries first, then connections, then the components.
draw_boundary((1020, 95, 1420, 630), "Railway Cloud")
draw_boundary((70, 745, 760, 1170), "Komputer Lokal")

# Main vertical and side links, drawn before icons.
# Database <-> Web Server
line_arrow((1165, 410), (1165, 257), "request", (-72, 0))
line_arrow((1235, 257), (1235, 410), "respon", (70, 0))
# Web server <-> Internet
line_arrow((1165, 690), (1165, 605), "request", (-72, 0))
line_arrow((1235, 605), (1235, 690), "respon", (70, 0))
# Web server <-> Google AI
line_arrow((1325, 475), (1635, 475), "prompt", (0, -28))
line_arrow((1635, 530), (1325, 530), "hasil klasifikasi", (0, 28))
# Social source <-> local scraper
line_arrow((350, 860), (420, 860), "request", (0, -28))
line_arrow((420, 905), (350, 905), "data aduan", (0, 30))
# Scraper <-> internet (local service goes through Internet)
line_arrow((625, 810), (1070, 740), "request", (0, -28))
line_arrow((1070, 780), (625, 850), "respon", (0, 30))
# Admin <-> computer
line_arrow((830, 1160), (875, 1160), "request", (0, -25))
line_arrow((875, 1203), (830, 1203), "respon", (0, 25))
# Admin computer <-> Internet
line_arrow((960, 1130), (1110, 850), "request", (-15, -28))
line_arrow((1145, 872), (990, 1150), "respon", (20, 30))
# Internet <-> OPD computer
line_arrow((1295, 850), (1515, 1130), "request", (0, -25))
line_arrow((1485, 1150), (1260, 872), "respon", (0, 28))
# OPD computer <-> OPD users
line_arrow((1610, 1160), (1690, 1160), "request", (0, -25))
line_arrow((1690, 1203), (1610, 1203), "respon", (0, 25))
# Internet <-> public device
line_arrow((1300, 800), (1910, 1100), "request", (20, -28))
line_arrow((1880, 1140), (1295, 830), "respon", (10, 30))
# Public device <-> society
line_arrow((2000, 1160), (2090, 1160), "request", (0, -25))
line_arrow((2090, 1203), (2000, 1203), "respon", (0, 25))

# Components.
draw_database(1200, 120)
draw_web_server(1200, 410)
draw_ai_service(1640, 448)
draw_cloud(1200, 690)
draw_social_source(220, 805)
draw_scraper_pc(520, 800)
draw_person(780, 1090, "Admin KMC")
draw_pc(925, 1080, "Komputer Admin")
draw_pc(1560, 1080, "Perangkat OPD")
draw_person(1740, 1090, "Pengguna OPD", group=True)
draw_pc(1950, 1080, "Perangkat Masyarakat", mobile=True)
draw_person(2140, 1090, "Masyarakat", group=True)

# Small explanatory labels, kept separate from the main flows.
centered_text(1200, 655, "Aplikasi dan basis data diterbitkan melalui Railway", FONT_TINY, GRAY)
centered_text(420, 700, "Scraper dijalankan lokal", FONT_TINY, GRAY)
centered_text(1200, 1335, "Keterangan: Playwright mengambil aduan dari Facebook dan Instagram secara lokal; aplikasi Laravel memproses notifikasi, klasifikasi AI, tiket, dan pemantauan SLA.", FONT_TINY, GRAY)

img.save(OUT, dpi=(300, 300), optimize=True)
print(OUT)
print(f"{W}x{H}px, 300 DPI")
