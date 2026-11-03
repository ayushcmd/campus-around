# CampusAround — Campus & Neighborhood Discovery Platform

> **"Discover your campus and what's around."**  
> *Initial target campus: IIT Patna (Bihta)*

CampusAround is a modern, student-focused discovery platform helping students uncover campus facilities, nearby street food stalls, cafes, grocery shops, restaurants, transportation hubs, and hidden spots around campus.

Inspired by the visual design language of **Wise**, CampusAround pairs a bright lime green identity with dark forest green typography, generous whitespace, spacious responsive grids, and high-contrast, uncluttered components.

---

## 🎨 Visual Identity & Color System

| Token | Hex Code | Role & Usage |
| :--- | :--- | :--- |
| **Primary Lime Green** | `#9BE96B` | Hero section background, active category states, badges, CTA highlights |
| **Dark Forest Green** | `#082D00` | High-contrast headlines, primary buttons, brand icon, footer background |
| **Warm Background** | `#F7F7F2` | Main website canvas, alternating sections, secondary backgrounds |
| **Pure White** | `#FFFFFF` | Cards, search surfaces, dropdown menus, content panels |
| **Secondary Text** | `#64705D` | Subtitles, helper text, distances, hours, and metadata |
| **Border** | `#E5E7E1` | Card outlines, dividers, clean structural borders |

Typography is powered by clean modern sans-serif fonts (**Inter** and **Manrope**) loaded with responsive scale (desktop hero ~82px, section headings 36–48px).

---

## 🛠️ Technology Stack

- **Backend & Application:** PHP 8.2+ / Laravel 12 / Laravel Blade templates
- **Frontend & Styling:** Tailwind CSS v4 with custom theme tokens
- **Lightweight Interactivity:** Alpine.js (mobile navigation drawer, live directory filtering, map interactions, form preview modal)
- **Maps:** Leaflet & OpenStreetMap tiles (centered on IIT Patna Bihta coordinates: `25.5357° N, 84.8512° E`)
- **Package Management:** Composer & NPM / Vite
- **Testing:** PHPUnit / Laravel Test Suite (10 feature & unit tests)

---

## 📂 Architecture & Project Structure

```
CampusAround/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── PlaceController.php          # Handles all 5 application routes
│   ├── Services/
│   │   └── PlaceRepository.php              # Structured IIT Patna demo dataset & query methods
│   └── View/
│       └── Components/
│           └── AppLayout.php                # Layout component wrapper
├── resources/
│   ├── css/
│   │   └── app.css                          # Tailwind v4 theme, Leaflet styles, custom tokens
│   ├── js/
│   │   ├── app.js                           # Alpine.js, Leaflet, and Lucide bootstrapping
│   │   └── bootstrap.js                     # Axios configuration
│   └── views/
│       ├── components/
│       │   ├── button.blade.php             # Rounded pill button (primary, lime, secondary, ghost)
│       │   ├── category-card.blade.php      # Category chip with SVG icons & counts
│       │   ├── empty-state.blade.php        # Friendly zero-results placeholder
│       │   ├── footer.blade.php             # Dark forest green footer with quick links
│       │   ├── navbar.blade.php             # Responsive header with mobile Alpine.js drawer
│       │   ├── place-card.blade.php         # White rounded card with image, badges & distance
│       │   ├── search-input.blade.php       # Prominent white pill search interface
│       │   └── section-heading.blade.php    # Bold uppercase heading with eyebrow badge
│       ├── layouts/
│       │   └── app.blade.php                # Master HTML layout
│       └── pages/
│           ├── home.blade.php               # Homepage (Sections A through G)
│           ├── explore.blade.php            # Directory with live search & scope filters
│           ├── place-details.blade.php      # Detailed place page with mini Leaflet map
│           ├── contribute.blade.php         # Student submission form (foundation preview)
│           └── about.blade.php              # Project story, Bihta campus background & roadmap
├── routes/
│   └── web.php                              # Application routes
└── tests/
    └── Feature/
        └── CampusAroundRoutesTest.php       # 10 automated test cases
```

---

## 🚀 Implemented Pages & Features

### 1. Homepage (`/`)
- **Section A (Hero):** Bright lime green (`#9BE96B`) canvas, bold headline (`DISCOVER YOUR CAMPUS AND WHAT'S AROUND.`), prominent search bar, and quick shortcut pills.
- **Section B (Categories):** 8 categories (Campus Facilities, Food & Drinks, Cafes, Grocery, Restaurants, Shops, Transportation, All Places).
- **Section C (Campus Facilities):** Sample cards for Central Library, Tutorial Block 9, Senate Hall, Gymkhana, and Central Dining.
- **Section D (Nearby Discovery):** Off-campus student spots around Gate 1 and Bihta (Momos, Study Cafes, Chai Stalls, Xerox shops).
- **Section E (Leaflet Map):** Spatial map preview centered at IIT Patna with interactive location list; clicking any item pans to the marker and opens the popup.
- **Section F (Community CTA):** "Know a place we're missing?" call-to-action leading to contribution.
- **Section G (Footer):** Dark forest green minimal footer with navigation links and project disclaimer.

### 2. Explore Directory (`/explore`)
- Interactive real-time filtering with Alpine.js.
- Location scope toggles: **All Locations**, **🏛️ Campus Only**, **📍 Nearby Only**.
- Category filter pills with active visual state.
- Dynamic result counter and clear-filter shortcuts.
- Responsive empty-state design when zero results match.

### 3. Place Details (`/places/{slug}`)
- Semantic breadcrumbs navigation.
- High-resolution place banner with proximity badges.
- Detailed description, student highlights, and key amenities tags.
- Operating hours and relative gate distances.
- Interactive mini Leaflet map pinned to exact coordinates.
- "Similar places around campus" related places grid.

### 4. Contribute a Place (`/contribute`)
- Foundation submission UI with an informational preview mode banner.
- Form fields: Name, Category, On-Campus / Nearby selector, Description, Landmark/Directions, Hours, Distance.
- Drag-and-drop photo upload dropzone placeholder.
- Interactive Alpine.js submission confirmation modal with form reset.

### 5. About Page (`/about`)
- Campus background highlighting IIT Patna's 500-acre residential campus in Bihta.
- Wise-inspired design philosophy breakdown.
- Multi-phase project roadmap.

---

## 💻 Local Setup & Development Instructions

### Prerequisites
- PHP 8.2 or 8.4
- Composer 2+
- Node.js 18+ & NPM

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/AYUSHCMD-GITHUB/CampusAround.git
   cd CampusAround
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM dependencies & build assets:**
   ```bash
   npm install
   npm run build
   ```

4. **Environment setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Start the Laravel development server:**
   ```bash
   php artisan serve
   ```
   Open `http://127.0.0.1:8000` in your browser.

6. **(Optional) Run Vite in watch mode during development:**
   ```bash
   npm run dev
   ```

---

## 🧪 Running Automated Tests

Run the test suite using Artisan:
```bash
php artisan test
```

All 10 test cases in `Tests\Feature\CampusAroundRoutesTest` and `Tests\Unit\ExampleTest` verify that:
- Every route returns HTTP `200`
- Slug routing loads the correct place details
- Unknown slugs return HTTP `404`
- Filter query parameters are handled without errors

---

## 📌 Features Intentionally Deferred (Future Phases)

To preserve a lightweight, beginner-friendly architecture for the foundation:
- **Supabase PostgreSQL Integration:** Remote database tables and migrations are planned for Phase 2.
- **User Authentication:** Student logins, OAuth, and email verification.
- **Storage Submissions:** Actual cloud photo upload pipelines.
- **Admin Moderation:** Verification queues for student-contributed spots.
- **Ratings & Reviews:** Community star ratings and comment feeds.
