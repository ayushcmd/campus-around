<x-app-layout :nav-theme="'light'">
    <x-slot name="title">Inside Tour — Verified IIT Patna Campus Facilities</x-slot>

    <div x-data="insideTourApp({
            places: @json($places),
            initialCategory: '{{ $initialCategory ?? 'all' }}',
            initialSearch: '{{ $initialSearch ?? '' }}'
         })" 
         class="py-8 sm:py-12">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header: Left title/description & Right action -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 sm:mb-10 pb-6 border-b border-[#E5E7E1]">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#082D00] text-[#9BE96B] text-xs font-black uppercase tracking-wider mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#9BE96B] animate-pulse"></span>
                        INSIDE CAMPUS
                    </div>
                    <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-tight">
                        EXPLORE YOUR CAMPUS.
                    </h1>
                    <p class="mt-2 text-base sm:text-lg text-[#64705D] font-medium leading-relaxed">
                        Discover verified facilities, academic buildings, student spaces, and campus landmarks at IIT Patna.
                    </p>
                </div>

                <div class="shrink-0 flex items-center gap-3">
                    <button type="button"
                            @click="scrollToDirectory()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white text-[#082D00] border border-[#082D00]/20 hover:border-[#082D00] hover:bg-[#F7F7F2] font-black text-xs uppercase tracking-wider transition-all shadow-xs">
                        <span>View all facilities</span>
                        <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Search, Filter & View Controls (No redundant empty space) -->
            <div id="campus-directory-controls" class="bg-white rounded-3xl p-4 sm:p-6 border border-[#E5E7E1] shadow-xs mb-8 space-y-4">
                
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
                           placeholder="Search verified campus facilities (e.g. Library, Block IX, Kalam Hostel, Sports, Nescafe)..."
                           class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-full py-3.5 pl-12 pr-10 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                    
                    <button type="button" 
                            x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''; filterPlaces()" 
                            class="absolute inset-y-0 right-4 flex items-center text-[#64705D] hover:text-[#082D00]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Dynamic Category Chips (Calculated directly from dataset, smooth scroll, no oversized scrollbar) -->
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                    @foreach($categories as $cat)
                        <button type="button"
                                @click="setCategory('{{ $cat['id'] }}')"
                                :class="selectedCategory === '{{ $cat['id'] }}' ? 'bg-[#082D00] text-white font-extrabold shadow-xs' : 'bg-white text-[#64705D] border border-[#E5E7E1] hover:border-[#082D00]/30 font-semibold'"
                                class="shrink-0 px-4 py-2 rounded-full text-xs transition-all flex items-center gap-1.5 focus:outline-none">
                            <span>{{ $cat['name'] }}</span>
                            <span class="text-[10px] opacity-75 font-bold">({{ $cat['count'] }})</span>
                        </button>
                    @endforeach
                </div>

                <!-- Control summary bar & Cards/Map toggle -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-[#E5E7E1] text-xs">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-[#64705D]">
                            Showing <span class="text-[#082D00] font-black" x-text="filteredPlaces.length"></span> verified campus facilities
                        </span>
                        <template x-if="searchQuery || selectedCategory !== 'all'">
                            <button type="button" 
                                    @click="resetFilters()" 
                                    class="text-[11px] font-bold text-[#082D00] underline hover:opacity-80">
                                Reset filters
                            </button>
                        </template>
                    </div>

                    <div class="inline-flex rounded-full bg-[#F7F7F2] p-1 border border-[#E5E7E1] self-start sm:self-auto">
                        <button type="button" 
                                @click="viewMode = 'grid'" 
                                :class="viewMode === 'grid' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#64705D] hover:text-[#082D00]'"
                                class="px-3.5 py-1.5 rounded-full font-bold transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.2">
                                <rect width="7" height="7" x="3" y="3" rx="1"/>
                                <rect width="7" height="7" x="14" y="3" rx="1"/>
                                <rect width="7" height="7" x="14" y="14" rx="1"/>
                                <rect width="7" height="7" x="3" y="14" rx="1"/>
                            </svg>
                            <span>Cards</span>
                        </button>
                        <button type="button" 
                                @click="viewMode = 'map'; $nextTick(() => { if(map) { map.invalidateSize(); } })" 
                                :class="viewMode === 'map' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#64705D] hover:text-[#082D00]'"
                                class="px-3.5 py-1.5 rounded-full font-bold transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.2">
                                <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                            </svg>
                            <span>Live Map</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- LIVE MAP VIEW (Only plots places with verified coordinates) -->
            <div x-show="viewMode === 'map'" x-cloak class="mb-10 bg-white rounded-3xl border border-[#E5E7E1] p-4 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4 px-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#082D00] animate-pulse"></span>
                        <span class="text-xs font-black text-[#082D00] uppercase tracking-wider">
                            IIT Patna Spatial Map — <span x-text="mappablePlacesCount"></span> Plotted Locations
                        </span>
                    </div>
                    <span class="text-[11px] text-[#64705D] font-medium">
                        Places without verified coordinates remain fully accessible in Cards view.
                    </span>
                </div>
                <div id="inside-campus-map" class="w-full h-[450px] sm:h-[550px] rounded-2xl overflow-hidden border border-[#E5E7E1] z-10"></div>
            </div>

            <!-- CARDS GRID VIEW (Three columns desktop, two columns tablet, one column mobile) -->
            <div x-show="viewMode === 'grid'" 
                 id="campus-directory-grid" 
                 class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                
                <template x-for="place in filteredPlaces" :key="place.id">
                    <article class="group bg-white rounded-3xl border border-[#E5E7E1] overflow-hidden flex flex-col hover:border-[#082D00]/40 hover:shadow-xl transition-all duration-300">
                        
                        <!-- Image Container with Genuine Image or Category Placeholder -->
                        <div class="relative h-48 sm:h-52 w-full overflow-hidden bg-[#082D00]">
                            
                            <!-- Real Image if available -->
                            <template x-if="place.image">
                                <img :src="place.image" 
                                     :alt="place.name" 
                                     loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     x-on:error="$el.style.display='none'; if($el.nextElementSibling) $el.nextElementSibling.style.display='flex'">
                            </template>

                            <!-- Neutral Category Placeholder if image is missing/unverified -->
                            <div :class="place.image ? 'hidden' : 'flex'" 
                                 class="w-full h-full flex-col items-center justify-center bg-gradient-to-br from-[#082D00] via-[#0D3800] to-[#041700] text-center p-4 relative overflow-hidden select-none">
                                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#9BE96B_1px,transparent_1px)] [background-size:16px_16px]"></div>
                                
                                <div class="relative z-10 w-14 h-14 rounded-2xl bg-[#9BE96B]/15 border border-[#9BE96B]/30 flex items-center justify-center text-[#9BE96B] mb-2 shadow-inner"
                                     x-html="getCategoryIcon(place.category_id)">
                                </div>
                                <div class="relative z-10">
                                    <span class="text-[9px] font-black tracking-widest text-[#9BE96B] uppercase block">IIT PATNA CAMPUS</span>
                                    <span class="text-xs font-bold text-white/90 truncate max-w-[200px] block mt-0.5" x-text="place.category"></span>
                                </div>
                            </div>

                            <!-- Top Badges -->
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 pointer-events-none z-20">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-[#082D00] text-white border border-white/20 shadow-xs">
                                    Inside Campus
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-white/95 text-[#082D00] shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#164606]"></span>
                                    <span x-text="place.category"></span>
                                </span>
                            </div>

                            <!-- Area Pill -->
                            <div class="absolute bottom-3 right-3 pointer-events-none z-20">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-black/75 text-white backdrop-blur-xs">
                                    <span x-text="place.campus_area"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg sm:text-xl font-extrabold text-[#082D00] tracking-tight group-hover:text-[#134504] transition-colors leading-snug">
                                    <a :href="'/places/' + place.slug" class="focus:outline-none focus:underline" x-text="place.name"></a>
                                </h3>

                                <p class="mt-2 text-sm text-[#64705D] line-clamp-2 leading-relaxed" x-text="place.tagline || place.description"></p>

                                <!-- Verified Highlights Tags -->
                                <div class="mt-3.5 flex flex-wrap gap-1.5">
                                    <template x-for="tag in (place.tags || []).slice(0, 3)" :key="tag">
                                        <span class="px-2 py-0.5 rounded-md bg-[#F7F7F2] text-[#64705D] text-[11px] font-semibold border border-[#E5E7E1]" x-text="tag"></span>
                                    </template>
                                </div>

                                <!-- Source Attribution Note -->
                                <div class="mt-4 pt-3 border-t border-[#E5E7E1] text-[11px] text-[#64705D]">
                                    <span class="font-bold text-[#082D00]">Source:</span>
                                    <span class="truncate block text-[10px] text-[#64705D]" x-text="place.source"></span>
                                </div>
                            </div>

                            <!-- Card Footer Actions: View Details and Route -->
                            <div class="mt-5 pt-4 border-t border-[#E5E7E1] flex items-center justify-between gap-2">
                                <a :href="'/places/' + place.slug" 
                                   class="text-xs font-bold text-[#64705D] hover:text-[#082D00] transition-colors">
                                    View Details &rarr;
                                </a>

                                <a :href="'/locate?destination=' + place.slug" 
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black bg-[#9BE96B] text-[#082D00] hover:bg-[#8AE157] active:scale-95 transition-all shadow-xs">
                                    <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                                    </svg>
                                    <span>Route</span>
                                </a>
                            </div>
                        </div>

                    </article>
                </template>

            </div>

            <!-- Empty State Fallback -->
            <div x-show="filteredPlaces.length === 0" x-cloak class="py-12">
                <x-empty-state 
                    title="No facilities found matching your search"
                    message="Try clearing your search terms or selecting 'All Facilities' to browse the complete verified campus directory."
                    resetText="Show all campus facilities"
                    :resetUrl="route('inside')"
                />
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function insideTourApp(config) {
            return {
                places: config.places,
                filteredPlaces: config.places,
                selectedCategory: config.initialCategory || 'all',
                searchQuery: config.initialSearch || '',
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
                    const el = document.getElementById('campus-directory-controls');
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth' });
                    }
                },

                filterPlaces() {
                    const q = this.searchQuery.toLowerCase().trim();
                    const cat = this.selectedCategory;

                    this.filteredPlaces = this.places.filter(place => {
                        const matchCat = (cat === 'all') || (place.category_id === cat);
                        let matchQuery = true;
                        if (q) {
                            const nameMatch = (place.name || '').toLowerCase().includes(q);
                            const descMatch = (place.description || '').toLowerCase().includes(q);
                            const catMatch = (place.category || '').toLowerCase().includes(q);
                            const tagMatch = place.tags && place.tags.some(t => t.toLowerCase().includes(q));
                            matchQuery = nameMatch || descMatch || catMatch || tagMatch;
                        }
                        return matchCat && matchQuery;
                    });

                    this.updateMarkers();
                },

                getCategoryIcon(catId) {
                    const icons = {
                        'library': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/></svg>',
                        'hostel': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
                        'auditorium': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="15" x="2" y="3" rx="2"/><circle cx="12" cy="10" r="3"/><path d="M7 21v-3"/><path d="M17 21v-3"/><path d="M2 18h20"/></svg>',
                        'sports': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>',
                        'food': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"/><path d="M15 2v19"/><path d="M5 2v7a3 3 0 0 0 3 3h0a3 3 0 0 0 3-3V2"/><path d="M8 12v9"/></svg>',
                        'administration': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
                        'healthcare': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
                        'research': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.527a2 2 0 0 1-.211.896L4.72 20.55a1 1 0 0 0 .9 1.45h12.76a1 1 0 0 0 .9-1.45l-5.069-10.127A2 2 0 0 1 14 9.527V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/></svg>',
                        'landmarks': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>',
                        'services': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/></svg>',
                        'academic': '<svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>',
                    };
                    return icons[catId] || icons['academic'];
                },

                initMap() {
                    const defaultCenter = [25.5350, 84.8515];
                    this.map = L.map('inside-campus-map', {
                        center: defaultCenter,
                        zoom: 15,
                        scrollWheelZoom: false,
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(this.map);

                    this.updateMarkers();
                },

                updateMarkers() {
                    if (!this.map) return;
                    this.markers.forEach(m => this.map.removeLayer(m));
                    this.markers = [];

                    this.filteredPlaces.forEach(p => {
                        if (p.latitude && p.longitude) {
                            const icon = L.divIcon({
                                className: 'inside-pin',
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
                                        🏛️
                                    </div>
                                `,
                                iconSize: [32, 32],
                                iconAnchor: [16, 16],
                                popupAnchor: [0, -18],
                            });

                            const marker = L.marker([p.latitude, p.longitude], { icon: icon }).addTo(this.map);
                            const popupHtml = `
                                <div style="min-width: 190px; padding: 2px;">
                                    <span style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #64705D;">${p.category}</span>
                                    <h4 style="margin: 2px 0 4px; font-weight: 800; font-size: 14px; color: #082D00; line-height: 1.2;">${p.name}</h4>
                                    <p style="margin: 0 0 8px; font-size: 11px; color: #64705D;">${p.campus_area || 'IIT Patna'}</p>
                                    <div style="display: flex; gap: 8px; align-items: center; border-top: 1px solid #E5E7E1; padding-top: 6px;">
                                        <a href="/places/${p.slug}" style="font-size: 11px; font-weight: 700; color: #64705D; text-decoration: none;">
                                            Details &rarr;
                                        </a>
                                        <a href="/locate?destination=${p.slug}" style="display: inline-block; font-size: 11px; font-weight: 800; color: #082D00; background: #9BE96B; padding: 2px 8px; border-radius: 999px; text-decoration: none;">
                                            Route
                                        </a>
                                    </div>
                                </div>
                            `;
                            marker.bindPopup(popupHtml);
                            this.markers.push(marker);
                        }
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
