<x-app-layout :nav-theme="'light'">
    <x-slot name="title">Outside Tour — Real Nearby Spots Around IIT Patna</x-slot>

    <div x-data="outsideTourApp({
            places: @json($places),
            initialCategory: '{{ $initialCategory ?? 'all' }}',
            initialSearch: '{{ $initialSearch ?? '' }}'
         })" 
         class="py-8 sm:py-14">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="max-w-3xl mb-8 sm:mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#9BE96B] text-[#082D00] text-xs font-black uppercase tracking-wider mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#082D00] animate-pulse"></span>
                    Bihta & Campus Periphery (2–3 km)
                </div>
                <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-none">
                    OUTSIDE TOUR — NEARBY SPOTS.
                </h1>
                <p class="mt-3 text-base sm:text-lg text-[#64705D] font-medium leading-relaxed">
                    Real eateries, local dhabas, study cafes, pharmacies, and railway transit points around the IIT Patna campus in Bihta. Sourced from public listings and local community maps.
                </p>
            </div>

            <!-- Search, Filter & View Controls -->
            <div class="bg-white rounded-3xl p-4 sm:p-6 border border-[#E5E7E1] shadow-xs mb-8 space-y-4">
                
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
                           placeholder="Filter nearby places (e.g. Bihari Thaath, Angeethi, station, pharmacy)..."
                           class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-full py-3.5 pl-12 pr-10 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                </div>

                <!-- Category Pills Horizontal Scroll -->
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                    @foreach($categories as $cat)
                        <button type="button"
                                @click="setCategory('{{ $cat['id'] }}')"
                                :class="selectedCategory === '{{ $cat['id'] }}' ? 'bg-[#9BE96B] text-[#082D00] font-black border-[#082D00] shadow-xs' : 'bg-white text-[#64705D] border border-[#E5E7E1] hover:border-[#082D00]/30 font-semibold'"
                                class="shrink-0 px-4 py-2 rounded-full text-xs transition-all flex items-center gap-1.5 border">
                            <span>{{ $cat['name'] }}</span>
                            <span class="text-[10px] opacity-70">({{ $cat['count'] }})</span>
                        </button>
                    @endforeach
                </div>

                <!-- View Toggle Mode on Mobile & Desktop -->
                <div class="flex items-center justify-between pt-2 border-t border-[#E5E7E1] text-xs">
                    <span class="font-bold text-[#64705D]">
                        Showing <span class="text-[#082D00]" x-text="filteredPlaces.length"></span> verified places around campus
                    </span>

                    <div class="inline-flex rounded-full bg-[#F7F7F2] p-1 border border-[#E5E7E1]">
                        <button type="button" 
                                @click="viewMode = 'grid'" 
                                :class="viewMode === 'grid' ? 'bg-[#082D00] text-white' : 'text-[#64705D]'"
                                class="px-3 py-1 rounded-full font-bold transition-all flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                            <span>Cards</span>
                        </button>
                        <button type="button" 
                                @click="viewMode = 'map'; $nextTick(() => { if(map) map.invalidateSize(); })" 
                                :class="viewMode === 'map' ? 'bg-[#082D00] text-white' : 'text-[#64705D]'"
                                class="px-3 py-1 rounded-full font-bold transition-all flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                            <span>Area Map</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- MAP VIEW -->
            <div x-show="viewMode === 'map'" x-cloak class="mb-10 bg-white rounded-3xl border border-[#E5E7E1] p-4 shadow-sm">
                <div class="flex items-center justify-between mb-3 px-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#9BE96B] border border-[#082D00]"></span>
                        <span class="text-xs font-bold text-[#082D00] uppercase tracking-wider">Bihta Periphery Spatial Map</span>
                    </div>
                    <span class="text-xs text-[#64705D] font-medium">Radius ~2–3 km</span>
                </div>
                <div id="outside-area-map" class="w-full h-[450px] sm:h-[550px] rounded-2xl overflow-hidden border border-[#E5E7E1] z-10"></div>
            </div>

            <!-- CARDS GRID VIEW -->
            <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <template x-for="place in filteredPlaces" :key="place.id">
                    <article class="group bg-white rounded-3xl border border-[#E5E7E1] overflow-hidden flex flex-col hover:border-[#082D00]/40 hover:shadow-xl transition-all duration-300">
                        <!-- Image Container -->
                        <div class="relative h-48 sm:h-52 w-full overflow-hidden bg-neutral-100">
                            <img :src="place.image" 
                                 :alt="place.name" 
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                            <!-- Top Badges -->
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 pointer-events-none">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-[#9BE96B] text-[#082D00] shadow-xs"
                                      x-text="place.category">
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/95 text-[#082D00] shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#164606]"></span>
                                    Verified
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

                                <!-- Tags -->
                                <div class="mt-3 flex flex-wrap gap-1.5">
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
                            <div class="mt-5 pt-3 border-t border-[#E5E7E1] flex items-center justify-between gap-2">
                                <a :href="'/places/' + place.slug" 
                                   class="text-xs font-bold text-[#64705D] hover:text-[#082D00]">
                                    Full Details &rarr;
                                </a>

                                <a :href="'/locate?destination=' + place.slug" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-[#082D00] text-white hover:bg-[#134504] active:scale-95 transition-all shadow-xs">
                                    <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                                    </svg>
                                    <span>Get Route</span>
                                </a>
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            <!-- Empty State Fallback -->
            <div x-show="filteredPlaces.length === 0" x-cloak class="py-12">
                <x-empty-state 
                    title="No nearby places match this filter"
                    message="Try clearing your search terms or selecting 'All Nearby' to browse all verified destinations in Bihta."
                    resetText="Show all nearby spots"
                    :resetUrl="route('outside')"
                />
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function outsideTourApp(config) {
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
                setCategory(catId) {
                    this.selectedCategory = catId;
                    this.filterPlaces();
                },
                filterPlaces() {
                    const q = this.searchQuery.toLowerCase().trim();
                    const cat = this.selectedCategory;

                    this.filteredPlaces = this.places.filter(place => {
                        const matchCat = (cat === 'all') || (place.category_id === cat);
                        let matchQuery = true;
                        if (q) {
                            const nameMatch = place.name.toLowerCase().includes(q);
                            const descMatch = (place.description || '').toLowerCase().includes(q);
                            const tagMatch = place.tags && place.tags.some(t => t.toLowerCase().includes(q));
                            matchQuery = nameMatch || descMatch || tagMatch;
                        }
                        return matchCat && matchQuery;
                    });

                    this.updateMarkers();
                },
                initMap() {
                    const defaultCenter = [25.5480, 84.8600];
                    this.map = L.map('outside-area-map', {
                        center: defaultCenter,
                        zoom: 14,
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
                                className: 'outside-pin',
                                html: `
                                    <div style="
                                        background-color: #9BE96B;
                                        color: #082D00;
                                        width: 32px;
                                        height: 32px;
                                        border-radius: 9999px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        border: 2px solid #082D00;
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
                                <div style="min-width: 180px;">
                                    <span style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #64705D;">${p.category}</span>
                                    <h4 style="margin: 2px 0 4px; font-weight: 800; font-size: 14px; color: #082D00;">${p.name}</h4>
                                    <p style="margin: 0 0 6px; font-size: 11px; color: #64705D;">${p.campus_area}</p>
                                    <a href="/locate?destination=${p.slug}" style="display: inline-block; font-size: 11px; font-weight: 800; color: #082D00; text-decoration: underline;">
                                        Navigate Here &rarr;
                                    </a>
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
