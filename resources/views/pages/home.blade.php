<x-app-layout :nav-theme="'hero'">
    <x-slot name="title">CampusAround — Discover IIT Patna & What's Around</x-slot>

    <!-- SECTION A: HERO SECTION (Wise-inspired Lime Green Canvas) -->
    <section class="relative bg-[#9BE96B] text-[#082D00] pt-8 pb-20 sm:pt-12 sm:pb-28 lg:pt-16 lg:pb-36 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl">
                
                <!-- Eyebrow Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#082D00] text-[#9BE96B] text-xs font-extrabold uppercase tracking-widest mb-6 sm:mb-8 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#9BE96B] animate-ping"></span>
                    <span>IIT Patna Campus & Neighborhood Guide</span>
                </div>

                <!-- Giant Bold Headline (Wise visual language) -->
                <h1 class="text-4xl sm:text-6xl md:text-7xl lg:text-[82px] font-black tracking-tight uppercase leading-[0.98] text-[#082D00]">
                    Discover your campus and what's around.
                </h1>

                <!-- Supporting Description -->
                <p class="mt-6 sm:mt-8 text-lg sm:text-xl lg:text-2xl font-medium text-[#082D00]/85 max-w-2xl leading-relaxed">
                    Find campus facilities, food spots, shops, cafes, and hidden gems around IIT Patna. Discovered, mapped, and reviewed by students.
                </p>

                <!-- Search Interface (Visually prominent white surface) -->
                <div class="mt-8 sm:mt-10 max-w-2xl">
                    <x-search-input size="lg" placeholder="Search campus facilities, momos, cafes, xerox..." />
                </div>

                <!-- Quick Category Shortcuts Pills -->
                <div class="mt-8 pt-6 border-t border-[#082D00]/15">
                    <p class="text-xs uppercase font-extrabold tracking-wider text-[#082D00]/70 mb-3">Popular shortcuts:</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('explore', ['category' => 'campus-facilities']) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#082D00]/10 hover:bg-[#082D00] hover:text-white transition-all">
                            <span>🏛️ Campus Library & Blocks</span>
                        </a>
                        <a href="{{ route('explore', ['category' => 'food-drinks']) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#082D00]/10 hover:bg-[#082D00] hover:text-white transition-all">
                            <span>🥟 Gate 1 Momos & Snacks</span>
                        </a>
                        <a href="{{ route('explore', ['category' => 'cafes']) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#082D00]/10 hover:bg-[#082D00] hover:text-white transition-all">
                            <span>☕ Study Cafes</span>
                        </a>
                        <a href="{{ route('explore', ['category' => 'shops']) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#082D00]/10 hover:bg-[#082D00] hover:text-white transition-all">
                            <span>🖨️ Printing & Xerox</span>
                        </a>
                        <a href="{{ route('explore', ['type' => 'nearby']) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#082D00]/10 hover:bg-[#082D00] hover:text-white transition-all">
                            <span>📍 Bihta Market</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Decorative Subtle Background Pattern (geometric contour) -->
        <div class="absolute right-0 bottom-0 translate-x-1/4 translate-y-1/4 w-[500px] h-[500px] lg:w-[700px] lg:h-[700px] rounded-full border-[60px] lg:border-[90px] border-[#8AE157]/40 pointer-events-none -z-0"></div>
    </section>


    <!-- SECTION B: CATEGORY DISCOVERY -->
    <section class="py-16 sm:py-24 bg-[#F7F7F2]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading 
                eyebrow="Browse by category"
                title="WHAT ARE YOU LOOKING FOR TODAY?"
                description="Browse every essential spot around campus or filter by your current mood and requirements."
                actionText="Explore all categories"
                :actionHref="route('explore')"
            />

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                @foreach($categories as $category)
                    <x-category-card 
                        :category="$category" 
                        :href="route('explore', ['category' => $category['id']])" 
                    />
                @endforeach
            </div>
        </div>
    </section>


    <!-- SECTION C: EXPLORE CAMPUS -->
    <section class="py-16 sm:py-24 bg-white border-y border-[#E5E7E1]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading 
                eyebrow="On Campus"
                title="EXPLORE YOUR CAMPUS."
                description="Discover the core facilities, quiet study corners, auditoriums, and recreation spaces inside the IIT Patna 500-acre campus."
                actionText="View all campus facilities"
                :actionHref="route('explore', ['type' => 'campus'])"
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach(array_slice($campusPlaces, 0, 6) as $place)
                    <x-place-card :place="$place" />
                @endforeach
            </div>
        </div>
    </section>


    <!-- SECTION D: NEARBY DISCOVERY -->
    <section class="py-16 sm:py-24 bg-[#F7F7F2]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading 
                eyebrow="Outside the gates"
                title="GOOD PLACES, JUST AROUND CAMPUS."
                description="Explore beloved street food corners, late-night tea hubs, grocery essentials, and print stores just outside Gate 1, Gate 2, and in Bihta."
                actionText="See all nearby spots"
                :actionHref="route('explore', ['type' => 'nearby'])"
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach(array_slice($nearbyPlaces, 0, 6) as $place)
                    <x-place-card :place="$place" />
                @endforeach
            </div>
        </div>
    </section>


    <!-- SECTION E: LEAFLET MAP PREVIEW -->
    <section class="py-16 sm:py-24 bg-white border-t border-[#E5E7E1]" id="campus-map-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading 
                eyebrow="Interactive Discovery"
                title="CAMPUS & PERIMETER MAP."
                description="Locate key buildings inside IIT Patna and popular off-campus student hotspots with real spatial orientation."
            />

            <!-- Map Layout: Desktop side-by-side / Mobile stacked -->
            <div x-data="campusMapApp()" x-init="initMap()" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Map Container (7 cols on desktop) -->
                <div class="lg:col-span-7 bg-[#F7F7F2] rounded-3xl border border-[#E5E7E1] overflow-hidden shadow-sm relative">
                    <div class="p-4 border-b border-[#E5E7E1] bg-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#9BE96B] border border-[#082D00]"></span>
                            <span class="text-xs font-bold text-[#082D00] uppercase tracking-wider">IIT Patna Coordinates (25.5357° N, 84.8512° E)</span>
                        </div>
                        <button type="button" @click="resetView()" class="text-xs font-semibold text-[#64705D] hover:text-[#082D00] underline">
                            Reset View
                        </button>
                    </div>

                    <!-- Leaflet DOM element -->
                    <div id="homepage-map" class="w-full h-[420px] sm:h-[480px] lg:h-[560px] z-10"></div>

                    <!-- Map overlay legend -->
                    <div class="p-3 bg-white/90 backdrop-blur-md border-t border-[#E5E7E1] flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center gap-1.5 font-bold text-[#082D00]">
                                <span class="w-3 h-3 rounded-full bg-[#082D00]"></span> Campus Facility
                            </span>
                            <span class="inline-flex items-center gap-1.5 font-bold text-[#082D00]">
                                <span class="w-3 h-3 rounded-full bg-[#9BE96B] border border-[#082D00]"></span> Nearby Hub
                            </span>
                        </div>
                        <span class="text-[#64705D] text-[11px]">Click markers or list items to pan</span>
                    </div>
                </div>

                <!-- Interactive Place List (5 cols on desktop) -->
                <div class="lg:col-span-5 flex flex-col space-y-4">
                    <div class="bg-white rounded-2xl border border-[#E5E7E1] p-4 flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-sm text-[#082D00]">Interactive Locations</h4>
                            <p class="text-xs text-[#64705D]">Select any spot to highlight on the map</p>
                        </div>
                        <span class="text-xs font-extrabold px-2.5 py-1 rounded-full bg-[#9BE96B] text-[#082D00]">
                            {{ count($allPlaces) }} Mapped
                        </span>
                    </div>

                    <!-- Scrollable list of places -->
                    <div class="space-y-3 max-h-[480px] overflow-y-auto pr-1">
                        @foreach($allPlaces as $idx => $p)
                            <div @click="selectPlace({{ $idx }})"
                                 class="p-4 rounded-2xl border transition-all cursor-pointer bg-white"
                                 :class="activeIdx === {{ $idx }} ? 'border-[#082D00] bg-[#9BE96B]/15 shadow-sm' : 'border-[#E5E7E1] hover:border-[#082D00]/30 hover:bg-[#F7F7F2]'">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full {{ $p['is_campus'] ? 'bg-[#082D00] text-white' : 'bg-[#9BE96B] text-[#082D00]' }}">
                                                {{ $p['is_campus'] ? 'Campus' : 'Nearby' }}
                                            </span>
                                            <span class="text-xs font-semibold text-[#64705D]">{{ $p['category'] }}</span>
                                        </div>
                                        <h5 class="font-extrabold text-[#082D00] text-base leading-snug">{{ $p['name'] }}</h5>
                                        <p class="text-xs text-[#64705D] mt-1 line-clamp-1">{{ $p['location_label'] }}</p>
                                    </div>
                                    <a href="{{ route('places.show', $p['slug']) }}" class="shrink-0 p-2 text-[#082D00] hover:bg-black/5 rounded-full" title="View details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- SECTION F: COMMUNITY CONTRIBUTION CTA -->
    <section class="py-16 sm:py-24 bg-[#F7F7F2]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-[#082D00] rounded-3xl text-white p-8 sm:p-12 lg:p-16 overflow-hidden shadow-xl">
                <!-- Lime decorative accent ring -->
                <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full border-[30px] border-[#9BE96B]/20 pointer-events-none"></div>

                <div class="relative z-10 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#164606] text-[#9BE96B] text-xs font-extrabold uppercase tracking-wider mb-4">
                        <span>🤝 Community Driven</span>
                    </div>

                    <h2 class="text-3xl sm:text-5xl font-black tracking-tight uppercase leading-tight text-white">
                        Know a place we're missing?
                    </h2>

                    <p class="mt-4 text-base sm:text-lg text-white/80 font-medium leading-relaxed">
                        Help other students discover useful places around campus by sharing a spot you know. From late-night study nooks to the best samosa joint in Bihta.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                        <x-button variant="lime" size="lg" :href="route('contribute')">
                            <span>Contribute a Place</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </x-button>

                        <span class="text-xs text-white/60 sm:max-w-xs leading-relaxed">
                            Share location details, tags, and photos. No account required for foundation testing.
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Script -->
    @push('scripts')
    <script>
        function campusMapApp() {
            return {
                map: null,
                markers: [],
                activeIdx: null,
                places: @json($allPlaces),
                initMap() {
                    const defaultCenter = [25.5357, 84.8512];
                    this.map = L.map('homepage-map', {
                        center: defaultCenter,
                        zoom: 15,
                        scrollWheelZoom: false,
                    });

                    // High-contrast clean OpenStreetMap tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(this.map);

                    // Add markers
                    this.places.forEach((place, index) => {
                        const isCampus = place.is_campus;
                        const markerColor = isCampus ? '#082D00' : '#86D953';
                        const textColor = isCampus ? '#FFFFFF' : '#082D00';

                        // Custom HTML DivIcon for modern pin style
                        const customIcon = L.divIcon({
                            className: 'custom-campus-pin',
                            html: `
                                <div style="
                                    background-color: ${markerColor};
                                    color: ${textColor};
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
                                    ${isCampus ? '🏛️' : '📍'}
                                </div>
                            `,
                            iconSize: [32, 32],
                            iconAnchor: [16, 16],
                            popupAnchor: [0, -18],
                        });

                        const marker = L.marker(place.coordinates, { icon: customIcon }).addTo(this.map);

                        const popupHtml = `
                            <div style="font-family: inherit; min-width: 180px;">
                                <span style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #64705D;">${place.category}</span>
                                <h4 style="margin: 3px 0 6px; font-weight: 800; font-size: 14px; color: #082D00;">${place.name}</h4>
                                <p style="margin: 0 0 8px; font-size: 11px; color: #64705D;">${place.location_label}</p>
                                <a href="/places/${place.slug}" style="display: inline-block; font-size: 11px; font-weight: 800; color: #082D00; text-decoration: underline;">
                                    View Place &rarr;
                                </a>
                            </div>
                        `;

                        marker.bindPopup(popupHtml);
                        marker.on('click', () => {
                            this.activeIdx = index;
                        });

                        this.markers.push(marker);
                    });
                },
                selectPlace(index) {
                    this.activeIdx = index;
                    const place = this.places[index];
                    if (this.map && this.markers[index]) {
                        this.map.flyTo(place.coordinates, 16, { duration: 1 });
                        this.markers[index].openPopup();
                    }
                },
                resetView() {
                    this.activeIdx = null;
                    if (this.map) {
                        this.map.flyTo([25.5357, 84.8512], 15, { duration: 1 });
                        this.map.closePopup();
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
