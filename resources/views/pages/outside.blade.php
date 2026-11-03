<x-app-layout :nav-theme="'light'">
    <x-slot name="title">Outside Tour — Verified Nearby Spots Around IIT Patna</x-slot>

    <!-- Pre-load data in JSON script tag to prevent HTML attribute quotes truncation -->
    <script id="nearby-places-data" type="application/json">
        {!! json_encode($places, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <!-- Define outsideTourApp function before x-data initializes -->
    <script>
        function outsideTourApp(config) {
            let parsedPlaces = [];
            try {
                const dataEl = document.getElementById('nearby-places-data');
                if (dataEl && dataEl.textContent) {
                    parsedPlaces = JSON.parse(dataEl.textContent);
                }
            } catch (err) {
                console.error('Error parsing nearby places data:', err);
            }

            return {
                places: parsedPlaces,
                filteredPlaces: parsedPlaces,
                selectedCategory: (config && config.initialCategory) || 'all',
                searchQuery: (config && config.initialSearch) || '',
                viewMode: 'grid', // 'grid' or 'map'
                map: null,
                markers: [],

                init() {
                    this.filterPlaces();
                    this.$nextTick(() => {
                        this.initMap();
                    });
                },

                get mappablePlacesCount() {
                    return this.filteredPlaces.filter(p => p.latitude && p.longitude).length;
                },

                setCategory(catId) {
                    this.selectedCategory = catId;
                    this.filterPlaces();
                },

                resetFilters() {
                    this.selectedCategory = 'all';
                    this.searchQuery = '';
                    this.filterPlaces();
                },

                scrollToDirectory() {
                    const el = document.getElementById('nearby-directory-controls');
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth' });
                    }
                },

                filterPlaces() {
                    const q = (this.searchQuery || '').toLowerCase().trim();
                    const cat = this.selectedCategory;

                    this.filteredPlaces = this.places.filter(place => {
                        const matchCat = (cat === 'all') || (place.category_id === cat);
                        let matchQuery = true;
                        if (q) {
                            const nameMatch = (place.name || '').toLowerCase().includes(q);
                            const descMatch = (place.description || '').toLowerCase().includes(q);
                            const catMatch = (place.category || '').toLowerCase().includes(q);
                            const areaMatch = (place.campus_area || '').toLowerCase().includes(q);
                            const tagMatch = place.tags && place.tags.some(t => (t || '').toLowerCase().includes(q));
                            matchQuery = nameMatch || descMatch || catMatch || areaMatch || tagMatch;
                        }
                        return matchCat && matchQuery;
                    });

                    this.updateMarkers();
                },

                getCategoryIcon(catId) {
                    const icons = {
                        'food': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2v20"/><path d="M6 2v20"/><path d="M2 7h8a2 2 0 0 0 2-2V2H2v3a2 2 0 0 0 2 2Z"/></svg>',
                        'restaurants': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>',
                        'cafes': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v2"/><path d="M14 2v2"/><path d="M16 8a4 4 0 0 1 4 4v1a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4v-1a4 4 0 0 1 4-4h8Z"/><path d="M6 17v2a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-2"/></svg>',
                        'grocery': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
                        'transportation': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="16" x="4" y="3" rx="2"/><path d="M4 11h16"/><path d="m8 19-2 3"/><path d="m18 22-2-3"/><circle cx="8" cy="15" r="1"/><circle cx="16" cy="15" r="1"/></svg>',
                        'services': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="6" rx="2"/><line x1="12" x2="12" y1="10" y2="16"/><line x1="9" x2="15" y1="13" y2="13"/><path d="M6 6V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"/></svg>',
                    };
                    return icons[catId] || '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>';
                },

                initMap() {
                    const defaultCenter = [25.5480, 84.8600];
                    const mapEl = document.getElementById('outside-area-map');
                    if (!mapEl || typeof L === 'undefined') return;

                    this.map = L.map('outside-area-map', {
                        center: defaultCenter,
                        zoom: 14,
                        scrollWheelZoom: false,
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(this.map);

                    this.updateMarkers();
                },

                updateMarkers() {
                    if (!this.map || typeof L === 'undefined') return;

                    this.markers.forEach(m => this.map.removeLayer(m));
                    this.markers = [];

                    const mapped = this.filteredPlaces.filter(p => p.latitude && p.longitude);

                    mapped.forEach(p => {
                        const icon = L.divIcon({
                            className: 'outside-pin',
                            html: `
                                <div style="
                                    background-color: #082D00;
                                    color: #9BE96B;
                                    width: 32px;
                                    height: 32px;
                                    border-radius: 9999px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    border: 2px solid #FFFFFF;
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.25);
                                    font-weight: 800;
                                    font-size: 13px;
                                ">
                                    📍
                                </div>
                            `,
                            iconSize: [32, 32],
                            iconAnchor: [16, 16],
                            popupAnchor: [0, -18],
                        });

                        const marker = L.marker([p.latitude, p.longitude], { icon: icon }).addTo(this.map);
                        const popupHtml = `
                            <div style="min-width: 200px; font-family: Inter, sans-serif; padding: 2px;">
                                <span style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #64705D; letter-spacing: 0.05em;">${p.category}</span>
                                <h4 style="margin: 2px 0 4px; font-weight: 800; font-size: 14px; color: #082D00; line-height: 1.2;">${p.name}</h4>
                                <p style="margin: 0 0 6px; font-size: 11px; color: #64705D;">${p.campus_area || p.address || ''}</p>
                                <div style="display: flex; gap: 8px; margin-top: 6px; padding-top: 6px; border-top: 1px solid #E5E7E1;">
                                    <a href="/places/${p.slug}" style="font-size: 11px; font-weight: 800; color: #082D00; text-decoration: underline;">
                                        Details &rarr;
                                    </a>
                                    <a href="/locate?destination=${p.slug}" style="font-size: 11px; font-weight: 800; color: #164606; text-decoration: underline;">
                                        Navigate &rarr;
                                    </a>
                                </div>
                            </div>
                        `;
                        marker.bindPopup(popupHtml);
                        this.markers.push(marker);
                    });

                    if (mapped.length > 0 && this.viewMode === 'map') {
                        const group = L.featureGroup(this.markers);
                        this.map.fitBounds(group.getBounds(), { padding: [50, 50], maxZoom: 15 });
                    }
                }
            };
        }
    </script>

    <div x-data="outsideTourApp({
            initialCategory: '{{ $initialCategory ?? 'all' }}',
            initialSearch: '{{ $initialSearch ?? '' }}'
         })" 
         class="py-8 sm:py-14">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="max-w-3xl mb-8 sm:mb-12">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#9BE96B] text-[#082D00] text-xs font-black uppercase tracking-wider mb-3 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#082D00] animate-pulse"></span>
                    Bihta & Campus Periphery (2–3 km)
                </div>
                <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-none">
                    OUTSIDE TOUR — NEARBY SPOTS.
                </h1>
                <p class="mt-3 text-base sm:text-lg text-[#64705D] font-medium leading-relaxed">
                    Discover useful places around IIT Patna, including local food, cafes, shops, healthcare, and transport. Researched from OpenStreetMap and verified public listings.
                </p>
            </div>

            <!-- Search, Filter & View Controls -->
            <div id="nearby-directory-controls" class="bg-white rounded-3xl p-4 sm:p-6 border border-[#E5E7E1] shadow-xs mb-8 space-y-4">
                
                <!-- Search input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-[#64705D]">
                        <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                    <input type="text"
                           x-model="searchQuery"
                           @input="filterPlaces()"
                           placeholder="Filter nearby places (e.g. Bihari Thaath, Angeethi, Momos, Station, Pharmacy, ATM)..."
                           class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-full py-3.5 pl-12 pr-10 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                    <button type="button" 
                            x-show="searchQuery" 
                            @click="searchQuery = ''; filterPlaces()" 
                            class="absolute inset-y-0 right-3.5 flex items-center text-[#64705D] hover:text-[#082D00]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Category Pills Horizontal Scroll -->
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                    @foreach($categories as $cat)
                        <button type="button"
                                @click="setCategory('{{ $cat['id'] }}')"
                                :class="selectedCategory === '{{ $cat['id'] }}' ? 'bg-[#9BE96B] text-[#082D00] font-black border-[#082D00] shadow-xs' : 'bg-white text-[#64705D] border border-[#E5E7E1] hover:border-[#082D00]/30 font-semibold'"
                                class="shrink-0 px-4 py-2 rounded-full text-xs transition-all flex items-center gap-1.5 border cursor-pointer">
                            <span>{{ $cat['name'] }}</span>
                            <span class="text-[10px] opacity-75">({{ $cat['count'] }})</span>
                        </button>
                    @endforeach
                </div>

                <!-- View Toggle Mode on Mobile & Desktop -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-2 border-t border-[#E5E7E1] text-xs gap-3">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-[#64705D]">
                            Showing <span class="text-[#082D00] font-extrabold" x-text="filteredPlaces.length"></span> of <span class="text-[#082D00] font-extrabold" x-text="places.length"></span> verified spots
                        </span>
                        <template x-if="selectedCategory !== 'all' || searchQuery">
                            <button type="button" 
                                    @click="resetFilters()"
                                    class="text-[11px] font-bold text-[#082D00] underline hover:text-[#134504] ml-2">
                                Clear filters
                            </button>
                        </template>
                    </div>

                    <div class="inline-flex rounded-full bg-[#F7F7F2] p-1 border border-[#E5E7E1] self-start sm:self-auto">
                        <button type="button" 
                                @click="viewMode = 'grid'" 
                                :class="viewMode === 'grid' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#64705D] hover:text-[#082D00]'"
                                class="px-4 py-1.5 rounded-full font-extrabold transition-all flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                            <span>Cards</span>
                        </button>
                        <button type="button" 
                                @click="viewMode = 'map'; $nextTick(() => { if(map) { map.invalidateSize(); updateMarkers(); } })" 
                                :class="viewMode === 'map' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#64705D] hover:text-[#082D00]'"
                                class="px-4 py-1.5 rounded-full font-extrabold transition-all flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                            <span>Area Map (<span x-text="mappablePlacesCount"></span>)</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- MAP VIEW -->
            <div x-show="viewMode === 'map'" x-cloak class="mb-10 bg-white rounded-3xl border border-[#E5E7E1] p-4 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 px-2 gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#9BE96B] border border-[#082D00]"></span>
                        <span class="text-xs font-black text-[#082D00] uppercase tracking-wider">Bihta Periphery Spatial Map</span>
                    </div>
                    <span class="text-xs text-[#64705D] font-medium">Click any pin for spot name, details, and navigation link</span>
                </div>
                <div id="outside-area-map" class="w-full h-[450px] sm:h-[550px] rounded-2xl overflow-hidden border border-[#E5E7E1] z-10"></div>
            </div>

            <!-- CARDS GRID VIEW -->
            <div id="nearby-directory-grid" x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <template x-for="place in filteredPlaces" :key="place.id">
                    <article class="group bg-white rounded-3xl border border-[#E5E7E1] overflow-hidden flex flex-col hover:border-[#082D00]/40 hover:shadow-xl transition-all duration-300">
                        
                        <!-- Image Container with Neutral Vector Fallback -->
                        <div class="relative h-48 sm:h-52 w-full overflow-hidden bg-neutral-100 flex items-center justify-center">
                            
                            <!-- Authentic Image (if verified) -->
                            <img x-show="place.image" 
                                 :src="place.image || ''" 
                                 :alt="place.name" 
                                 loading="lazy"
                                 x-on:error="place.image = null"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                            <!-- Clean Neutral Category Vector Placeholder -->
                            <div x-show="!place.image" class="w-full h-full flex flex-col items-center justify-center p-6 text-center select-none bg-radial from-[#F7F7F2] to-[#ECEEE7]">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-[#E5E7E1] flex items-center justify-center text-[#082D00] shadow-2xs mb-2.5">
                                    <span x-html="getCategoryIcon(place.category_id)"></span>
                                </div>
                                <span class="text-xs font-black uppercase tracking-wider text-[#082D00]" x-text="place.category"></span>
                                <span class="text-[11px] font-medium text-[#64705D] mt-0.5" x-text="place.campus_area"></span>
                            </div>

                            <!-- Top Badges -->
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 pointer-events-none">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-[#9BE96B] text-[#082D00] shadow-xs"
                                      x-text="place.category">
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/95 text-[#082D00] shadow-xs border border-[#E5E7E1]/50 backdrop-blur-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#164606]"></span>
                                    Verified Spot
                                </span>
                            </div>

                            <!-- Area / Distance Pill -->
                            <div class="absolute bottom-3 right-3 pointer-events-none">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-black/75 text-white backdrop-blur-xs"
                                      x-text="place.campus_area">
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-extrabold text-[#082D00] tracking-tight group-hover:text-[#134504] transition-colors leading-snug">
                                    <a :href="'/places/' + place.slug" class="focus:outline-none focus:underline" x-text="place.name"></a>
                                </h3>
                                <p class="mt-2 text-sm text-[#64705D] line-clamp-2 leading-relaxed" x-text="place.tagline"></p>

                                <!-- Hours & Price indicator when available -->
                                <div class="mt-3.5 space-y-1.5 text-xs">
                                    <template x-if="place.opening_hours">
                                        <div class="flex items-center gap-2 text-[#082D00] font-semibold">
                                            <svg class="w-3.5 h-3.5 text-[#164606] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                            <span class="truncate" x-text="place.opening_hours"></span>
                                        </div>
                                    </template>
                                    <template x-if="place.price_range">
                                        <div class="flex items-center gap-2 text-[#64705D] font-medium">
                                            <span class="text-xs font-bold text-[#082D00]">₹</span>
                                            <span class="truncate" x-text="place.price_range"></span>
                                        </div>
                                    </template>
                                    <template x-if="place.menu && place.menu.length > 0">
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-[#9BE96B]/20 text-[#082D00] text-[11px] font-bold border border-[#9BE96B]/40">
                                            <span>🍽️ Menu Available (<span x-text="place.menu.length"></span> items)</span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Tags -->
                                <div class="mt-3.5 flex flex-wrap gap-1.5">
                                    <template x-for="tag in place.tags" :key="tag">
                                        <span class="px-2 py-0.5 rounded-md bg-[#F7F7F2] text-[#64705D] text-[11px] font-semibold border border-[#E5E7E1]" x-text="tag"></span>
                                    </template>
                                </div>

                                <!-- Source Attribution Note -->
                                <div class="mt-4 pt-3 border-t border-[#E5E7E1] text-[11px] text-[#64705D]">
                                    <span class="font-bold text-[#082D00]">Source:</span>
                                    <span class="truncate block text-[10px] text-[#64705D]" x-text="place.source"></span>
                                </div>
                            </div>

                            <!-- Card Footer Actions -->
                            <div class="mt-5 pt-4 border-t border-[#E5E7E1] flex flex-wrap items-center justify-between gap-2">
                                <a :href="'/places/' + place.slug" 
                                   class="text-xs font-bold text-[#64705D] hover:text-[#082D00] transition-colors py-1">
                                    Full Details &rarr;
                                </a>

                                <div class="flex items-center gap-2">
                                    <a :href="'/contribute?place=' + place.slug" 
                                       title="Suggest an edit for this spot"
                                       class="p-2 rounded-full bg-[#F7F7F2] text-[#64705D] hover:text-[#082D00] hover:bg-[#E5E7E1] transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <a :href="'/locate?destination=' + place.slug" 
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black bg-[#082D00] text-white hover:bg-[#134504] active:scale-95 transition-all shadow-xs">
                                        <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                                        </svg>
                                        <span>Route</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            <!-- Empty State Fallback -->
            <div id="no-results-state" x-show="filteredPlaces.length === 0" x-cloak class="py-12">
                <x-empty-state 
                    title="No nearby spots match your criteria"
                    message="Try clearing your search query or choosing 'All Nearby' to browse all verified destinations in Bihta."
                    resetText="Reset all filters"
                    :resetUrl="route('outside')"
                />
            </div>

        </div>
    </div>
</x-app-layout>
