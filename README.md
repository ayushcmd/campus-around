# CampusAround — Mobile-First Navigation, Real Campus Data & Apple-Inspired Live Map

> **"Discover your campus and what's around."**  
> *Target Campus: Indian Institute of Technology Patna (Bihta, Bihar, India)*

CampusAround is a modern campus and neighborhood discovery platform built for the student community of IIT Patna. The platform pairs a Wise-inspired high-contrast visual design language (Primary Lime `#9BE96B`, Dark Forest Green `#082D00`, Warm Background `#F7F7F2`) with an Apple-Maps-inspired spatial navigation experience.

---

## 🚀 Key Features Implemented

### 1. Left-Side Navigation Drawer
- **Desktop & Mobile:** Accessible from the menu hamburger button on the left of the navbar.
- **Smooth Drawer Transition:** Slides smoothly from the left of the screen with a subtle backdrop overlay.
- **Core Navigation Structure:** Exactly houses the four primary discovery modes:
  1. **Inside Tour (`/inside`)** — Building icon (Lucide SVG)
  2. **Outside Tour (`/outside`)** — Store icon (Lucide SVG)
  3. **Locate (`/locate`)** — Navigation arrow icon (Lucide SVG)
  4. **Contribute (`/contribute`)** — Plus-circle icon (Lucide SVG)
- **Accessibility:** Closes on navigation click, backdrop click, or pressing the `Escape` key.

### 2. Homepage: Prominent Search & Four Discovery Options
- **Layout Hierarchy:**
  1. Navbar with left-side menu trigger
  2. Bold Hero Heading (`DISCOVER YOUR CAMPUS AND WHAT'S AROUND.`)
  3. Prominent Search Bar with live autocomplete against verified place names, categories, and landmarks
  4. **Four Discovery Options** positioned directly **BELOW** the search bar:
     - **Desktop:** 4-card horizontal row with interactive hover and active indicators
     - **Mobile:** 2x2 responsive grid optimized for one-tap mobile navigation
  5. Category exploration and campus preview below

### 3. Inside Tour — Real IIT Patna Campus Data (`/inside`)
- **Verified Public Sources:** Researched from official IIT Patna websites (`iitp.ac.in`), institute infrastructure reports, and OpenStreetMap.
- **Real Facilities Included:**
  - *Central Library (IIT Patna)*
  - *Tutorial & Lecture Hall Complex (Block IX)*
  - *Academic Blocks (Blocks I–VI, Engineering & Sciences)*
  - *Main Administrative Building (Directorate & Registrar)*
  - *Main Auditorium & Senate Hall*
  - *Boys Hostel Complex (Aryabhatta, Kalam & Raman Blocks)*
  - *Asima Girls' Hostel*
  - *Gymkhana & Sports Arena*
  - *Student Activity Centre (SAC) & Campus Food Court*
  - *Institute Health Centre*
  - *Campus Guest House*
  - *Main Campus Gate 1 & Security Gatehouse*
- **Features:** Category filtering (Academic, Library, Hostel, Auditorium, Sports, Food, Administration, Other), live Leaflet map with campus pins, card grid view, source citation on each card, and "Locate on Map" one-click action.

### 4. Outside Tour — Real Nearby Places Around IIT Patna (`/outside`)
- **Target Radius:** Approximately 2–3 km around the Bihta campus perimeter and along Bihta-Kanpa Road / Bihta Chauraha.
- **Verified Real Nearby Places:**
  - *Bihari Thaath (Bt) Restaurant* (~750m from Gate 1)
  - *BB Kitchen* (~850m from Gate 1)
  - *Angeethi Cafe (Raghopur)* (~1.2 km)
  - *Discovery Food Plaza* (~1.5 km)
  - *Lemons Spice Palace* (~1.8 km)
  - *High Q Food Court (Giri Baba Market)* (~1.9 km)
  - *Bihta Railway Station (BTA)* (~3.8 km)
  - *Bihta Auto & Bus Stand (Chowk)* (~2.0 km)
  - *Kisan Medical & Pharmacy Store* (~1.6 km)
  - *Bihta Super Market & General Store* (~1.5 km)
- **Features:** Verified sources (OpenStreetMap and local business listings), no fabricated hours or fake reviews, list/map view switcher, and direct routing links.

### 5. Locate — Apple-Inspired Live Location & Directions (`/locate`)
- **Minimal Apple-Style Presentation:** Split-view layout (desktop: left direction controls + right interactive map; mobile: prominent map with collapsible directions card).
- **Starting Point & Destination Selection:**
  - Choose start location from inside/outside dropdown or map click
  - Choose destination from inside/outside dropdown, search, or map click
  - Pre-selected route support via query param (e.g. `/locate?destination=central-library`)
- **Strict Location Permission Workflow:**
  - **No automatic GPS requests:** Geolocation is *never* requested on page load.
  - User must explicitly click `"Use my current location"`.
  - Explanatory modal explains privacy and local usage before invoking `navigator.geolocation`.
  - Graceful fallback: If denied or unavailable, clear explanation is shown while keeping manual location selection fully functional.
- **Real Routing Engine (OSRM & OpenStreetMap):**
  - Connects to Open Source Routing Machine (`router.project-osrm.org`) for real road/footpath routing.
  - Supports **Walking** (`foot`), **Driving** (`car`), and **Cycling** (`bike`).
  - Calculates real distance (e.g., `850 m`, `1.4 km`) and road duration (e.g., `12 min`). Never reports straight-line distance as road walking distance.
  - Plots GeoJSON route lines on the Leaflet map.
  - Turn-by-turn maneuvers list.
  - External fallback link to Google Maps navigation if routing is unreachable.

### 6. Contribute — Foundation UI (`/contribute`)
- Notice banner clarifying that database submission is in preview mode and not yet connected to a server.
- Place name, category, inside/outside zone, description, and location landmark fields.
- Client-side photo selector supporting up to 3 image previews with remove buttons.
- Form validation preserves entered fields and displays clear message rather than feigning backend success.

---

## 🎨 Color Palette & Design System

| Token | Hex Code | Purpose |
| :--- | :--- | :--- |
| **Primary Lime** | `#9BE96B` | Hero canvas, active discovery cards, brand highlights, route accents |
| **Forest Green** | `#082D00` | Bold headlines, primary buttons, brand icon, route polyline, footer |
| **Warm Canvas** | `#F7F7F2` | Base background, input backgrounds, alternating sections |
| **Pure White** | `#FFFFFF` | Cards, drawer surfaces, search bar, modals |
| **Secondary Text** | `#64705D` | Subheadings, sources, hours, distances |
| **Border** | `#E5E7E1` | Minimal borders, structural dividers |

---

## 🧪 Automated Testing

All 12 feature and unit test cases pass cleanly:
```bash
php artisan test
```

Test coverage includes:
- Homepage hero with search bar and 4 discovery option cards
- Inside Tour with verified IIT Patna facilities
- Outside Tour with real Bihta businesses
- Locate page with starting point, destination, and routing controls
- Query parameter routing (`/locate?destination=central-library`)
- Place details with official source attribution and direct navigation link
- Nonexistent place 404 handling
- Contribute page validation and preview banner
- About page story and technical roadmap

---

## 🛠️ Local Development

```bash
# 1. Build assets with Vite
npm run build

# 2. Run the application
php artisan serve --port=8000
```
Open `http://127.0.0.1:8000` in your browser.
