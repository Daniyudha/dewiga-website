# Tailwind CSS Migration Plan

## Overview
Replace all custom CSS in `style.css` (2500+ lines) with Tailwind CSS utility classes across all public-facing Blade templates. Admin panel (AdminLTE) stays unchanged.

## Design System Mapping

### Current CSS Variables → Tailwind Config

| CSS Variable | Value | Tailwind Config Key |
|---|---|---|
| `--first-color` | `hsl(145, 45%, 28%)` | `green.600` |
| `--first-color-alt` | `hsl(145, 50%, 22%)` | `green.700` |
| `--first-color-light` | `hsl(145, 35%, 45%)` | `green.500` |
| `--first-color-lighten` | `hsl(145, 30%, 95%)` | `green.50` |
| `--second-color` | `hsl(38, 85%, 52%)` | `amber.500` |
| `--second-color-alt` | `hsl(38, 90%, 42%)` | `amber.600` |
| `--title-color` | `hsl(145, 40%, 18%)` | `green.900` |
| `--text-color` | `hsl(145, 15%, 40%)` | `green.700` |
| `--text-color-light` | `hsl(145, 10%, 65%)` | `green.400` |
| `--border-color` | `hsl(145, 20%, 92%)` | `green.100` |
| `--body-color` | `#fafaf8` | `stone.50` |
| `--container-color` | `#fff` | `white` |
| `--font-heading` | `'Playfair Display', serif` | `font-heading` |
| `--font-body` | `'Poppins', sans-serif` | `font-body` |

### Dark Mode
Current: body class `dark-theme` toggles CSS variable overrides.
Strategy: Tailwind `darkMode: 'class'` — same `dark-theme` class, but colors use Tailwind `dark:` prefix.

### Breakpoints
Current custom breakpoints:
- Mobile: `<1024px` (offcanvas nav)
- Desktop: `>=1024px` (horizontal nav)
- Compact desktop: `1024px-1279px`

Tailwind default breakpoints:
- `sm`: 640px
- `md`: 768px
- `lg`: 1024px
- `xl`: 1280px

Strategy: Use `lg` (1024px) as the breakpoint for mobile↔desktop toggle, same as current.

---

## Step-by-step Tasks

### Phase 1: Tailwind Setup

1. **Install Tailwind CSS v3**
   ```bash
   npm install -D tailwindcss@3 postcss autoprefixer
   npx tailwindcss init -p
   ```

2. **Configure `tailwind.config.js`**
   - Content paths: `./resources/**/*.blade.php`
   - Custom colors matching current palette
   - Custom font families
   - Dark mode: `class` strategy
   - Extend container center + padding
   - Add custom animations (slideDown, etc.)

3. **Create `resources/css/frontend.css`**
   - `@tailwind base;`
   - `@tailwind components;`
   - `@tailwind utilities;`
   - Base layer: body font, heading fonts, smooth scroll, box-sizing
   - Component layer: reusable `.button`, `.section`, `.container` classes via `@apply`
   - Utility layer: custom animation keyframes
   - Dark theme overrides (scrollbar, swiper nav, etc.)

4. **Update `vite.config.js`**
   - Add `resources/css/frontend.css` to input array

5. **Update `frontend.blade.php`**
   - Replace `<link rel="stylesheet" href="...style.css">` with Vite-built CSS
   - Keep all other CDN links (Boxicons, Google Fonts, Swiper CSS)

6. **Build & verify**
   - `npm run build`
   - Verify Tailwind compiles without errors

### Phase 2: Layout Navbar (frontend.blade.php)

7. **Rewrite `<header>` with Tailwind classes**
   - Fixed header: `fixed top-0 left-0 w-full z-[100] transition-colors duration-300`
   - Scroll header: `bg-white shadow-md` (via JS adding class)
   - Nav container: `flex justify-between items-center h-14`

8. **Logo area**
   - `inline-flex items-center gap-2 font-heading font-semibold text-white text-xl`
   - Scroll state: `text-green-900`

9. **Desktop nav menu (`lg:flex`)**
   - Menu items: `lg:flex lg:items-center lg:gap-8 lg:static lg:h-auto lg:bg-transparent lg:shadow-none lg:translate-x-0`
   - Links: `text-white text-sm font-medium hover:text-green-600 transition-colors`
   - Hide icons on desktop: `lg:[&>i]:hidden`

10. **Mobile offcanvas (`lg:hidden`)**
    - Menu panel: `fixed top-0 right-0 w-4/5 max-w-[400px] h-screen bg-white shadow-2xl p-8 pt-20 z-[1000] translate-x-full transition-transform duration-300`
    - Show state: `translate-x-0`
    - Backdrop via separate `<div>` element (instead of `::before` pseudo-element)
    - Close button: `absolute top-5 right-6 text-2xl text-green-900 cursor-pointer`
    - Nav list: `flex flex-col gap-6`
    - Mobile WhatsApp CTA: `flex items-center justify-center gap-2 mt-8`
    - Language selector: `mt-8 pt-6 border-t border-green-100 flex items-center justify-center gap-4`

11. **Desktop CTA button**
    - `hidden lg:inline-flex items-center gap-2 px-4 py-2 text-xs rounded-lg`

12. **Hamburger toggle**
    - `block lg:hidden text-2xl text-white cursor-pointer`

### Phase 3: Homepage Sections

13. **Hero Section**
    - Container: `relative h-screen min-h-[600px] flex items-center justify-center overflow-hidden`
    - Background images: `absolute inset-0 w-full h-full object-cover object-center opacity-0 transition-opacity duration-1000`
    - Overlay: `absolute inset-0 bg-gradient-to-b from-black/30 to-black/50`
    - Content: `relative z-10 text-center max-w-3xl px-6`
    - Subtitle: `text-amber-500 uppercase tracking-widest text-sm font-semibold mb-4`
    - Title: `text-white text-5xl md:text-7xl font-heading mb-4 leading-tight`
    - Description: `text-white/90 text-lg mb-8 leading-relaxed`
    - Actions: `flex justify-center gap-4 flex-wrap`

14. **Value Proposition Section**
    - Container: `grid gap-12 md:grid-cols-2 items-center`
    - Image with ornate border: `relative w-[266px] h-[316px] bg-green-50 rounded-[135px_135px_16px_16px]`
    - Cards grid: `grid gap-6 sm:grid-cols-2 mt-8`
    - Card: `bg-white border border-green-100 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all`
    - Icon: `w-[50px] h-[50px] rounded-xl flex items-center justify-center text-white text-2xl mb-4`

15. **Featured Activities**
    - Grid: `grid gap-6 sm:grid-cols-2 md:grid-cols-3 mt-10`
    - First card spans: `md:col-span-2 md:row-span-2`
    - Card overlay: `absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-6`

16. **Popular Packages (Swiper)**
    - Keep Swiper structure, just update styles to Tailwind
    - Card: `bg-white rounded-2xl p-2 pb-6 shadow-sm hover:shadow-lg transition-shadow`

17. **Testimonials**
    - Card: `bg-white rounded-2xl p-8 border border-green-100 max-w-2xl mx-auto`
    - Stars: `text-amber-500 text-lg mb-4`
    - Text: `italic text-sm leading-relaxed mb-6`

18. **Statistics**
    - Grid: `grid gap-8 sm:grid-cols-2 md:grid-cols-4 text-center`
    - Number: `text-4xl font-bold text-green-600 font-heading block`

19. **Blog Section**
    - Card: `bg-white rounded-2xl overflow-hidden border border-green-100 hover:shadow-lg hover:-translate-y-1 transition-all`
    - Image: `w-full h-[220px] object-cover`

20. **Gallery + Logos sections**
    - Keep the infinite scroll animation CSS for gallery slider
    - Logos: `grid grid-cols-3 md:grid-cols-4 gap-8 justify-items-center`

### Phase 4: Inner Pages

21. **About Us Page**
    - Page hero: `h-[45vh] min-h-[320px]` (same hero pattern with overlay)
    - Breadcrumb: `flex items-center gap-2 text-sm text-green-700 py-4`
    - Two-column layout sections (alternating image/text)

22. **Contact Page**
    - Contact info cards: `grid grid-cols-2 gap-4`
    - Card box: `bg-stone-50 border-2 border-green-100 rounded-lg p-5 hover:shadow-md`
    - Form: `max-w-2xl mx-auto p-8 rounded-2xl shadow-md`
    - Form inputs: Tailwind's form styling

23. **Gallery Page**
    - Simple layout with Elfsight embed - minimal changes

24. **Blog Index / Category / Show Pages**
    - Blog grid: `grid gap-10 sm:grid-cols-[450px] justify-center md:grid-cols-3 md:gap-8`
    - Category filter pills: flex wrap with button styles
    - Blog detail: prose-like styling for content

25. **Travel Packages Index / Show Pages**
    - Package grid: same as blog index grid
    - Package detail: two-column content layout
    - Package info box: flex wrap with price/type/location
    - FAQ accordion: keep JS, update to Tailwind

### Phase 5: Footer

26. **Footer Layout** 🔥 (current issue being fixed)
    - Container: `flex flex-col gap-10 md:flex-row md:justify-between`
    - Logo + description: `max-w-xs`
    - Content columns: `flex flex-wrap gap-8` 
    - Each column: `flex-1 min-w-[180px]` (responsive columns)
    - `sm:` → 2 columns, `md:` → 4 columns (using flex basis)
    - Social links: `flex gap-4 text-xl`
    - Footer info: `flex flex-col md:flex-row md:justify-between items-center gap-6 pt-12 mt-12 border-t border-green-100`

### Phase 6: Cleanup & Verification

27. **Remove `style.css` link** from `frontend.blade.php`
28. **Remove or archive `style.css`** (keep as backup)
29. **Remove `style.scss` and SCSS source files** (no longer needed)
30. **Verify all 7 pages** HTTP 200 via curl
31. **Verify dark mode** toggling
32. **Verify mobile offcanvas** menu
33. **Verify Swiper sliders** functionality
34. **Verify ScrollReveal** animations
35. **Verify lazy loading**

---

## Critical Considerations

### JavaScript Compatibility
- `main.js` uses `classList.add/remove` extensively
- All CSS class names must match between Tailwind output and JS selectors
- `.scroll-header` → keep as class (or use Tailwind's `@apply` in component layer)
- `.show-menu`, `.show-scroll`, `.dark-theme` → keep as-is (JS-toggled)
- `.active-link` → keep for nav active state
- `.accordion-open` → keep for FAQ accordion

### Swiper CSS
- Keep `swiper-bundle.min.css` import
- Swiper navigation/pagination styling kept in Tailwind component layer

### What Stays as Custom CSS (minimal)
A small `frontend.css` with `@layer components` will contain:
- `.scroll-header` styles (background/shadow changes)
- `.show-menu` transform transition
- `.active-link` color override
- Swiper button positioning
- Dark mode scrollbar styling
- Gallery infinite scroll animation
- `.show-scroll` class

### Asset Pipeline
- Vite builds `resources/css/frontend.css` → `public/build/assets/`
- The `@vite()` directive in Blade automatically adds the correct URL
- No more direct `<link>` to `style.css`
