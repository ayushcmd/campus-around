<x-app-layout :nav-theme="'hero'">
    <x-slot name="title">CampusAround — Discover IIT Patna & What's Around</x-slot>

    <!-- SECTION A: HERO SECTION (Wise-inspired Lime Green Canvas) -->
    <section class="relative bg-[#9BE96B] text-[#082D00] pt-6 pb-20 sm:pt-10 sm:pb-28 lg:pt-14 lg:pb-32 overflow-hidden"
             x-data="homeSearchApp({ allPlaces: @json($allPlaces) })">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center lg:text-left">
                
                <!-- Eyebrow Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#082D00] text-[#9BE96B] text-xs font-black uppercase tracking-widest mb-6 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#9BE96B] animate-ping"></span>
                    <span>IIT Patna (Bihta) Campus & Periphery</span>
                </div>

                <!-- Giant Bold Headline (Wise visual language) -->
                <h1 class="text-4xl sm:text-6xl md:text-7xl lg:text-[76px] font-black tracking-tight uppercase leading-[0.98] text-[#082D00]">
                    Discover your campus and what's around.
                </h1>

                <!-- Supporting Description -->
                <p class="mt-5 text-base sm:text-xl lg:text-2xl font-medium text-[#082D00]/85 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Verified facilities, academic blocks, street food, cafes, and live navigation around IIT Patna.
                </p>

                <!-- PROMINENT SEARCH BAR (Appears BEFORE the 4 discovery options) -->
                <div class="mt-8 sm:mt-10 max-w-2xl mx-auto lg:mx-0 relative">
                    <div class="relative flex items-center">
                        <div class="absolute w-6 h-6 left-5 text-[#64705D] pointer-events-none flex items-center justify-center">
                            <svg class="w-full h-full stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>
                        </div>

                        <input type="text"
                               x-model="searchQuery"
                               @input="performSearch()"
                               @focus="isOpen = true"
                               @keydown.escape="isOpen = false"
                               placeholder="Search campus buildings, library, dhabas, cafes, transit..."
                               class="w-full bg-white text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-full py-4 pl-14 pr-28 text-base md:text-lg border-2 border-transparent shadow-lg focus:border-[#082D00] focus:outline-none transition-all">

                        <div class="absolute right-2">
                            <button type="button" 
                                    @click="if(searchQuery.trim().length > 0) window.location.href = '/explore?q=' + encodeURIComponent(searchQuery)"
                                    class="rounded-full bg-[#082D00] text-white font-bold px-6 py-2.5 text-sm hover:bg-[#134504] active:scale-95 transition-all flex items-center gap-1.5 shadow-sm">
                                <span>Search</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Search Autocomplete / Instant Results Dropdown -->
                    <div x-show="isOpen && searchQuery.trim().length > 0" 
                         x-cloak
                         @click.away="isOpen = false"
                         class="absolute top-full left-0 right-0 mt-3 bg-white rounded-3xl border border-[#E5E7E1] shadow-2xl p-4 z-40 max-h-96 overflow-y-auto text-left">
                        
                        <div class="text-[11px] font-black uppercase tracking-wider text-[#64705D] px-3 pb-2 border-b border-[#E5E7E1] flex items-center justify-between">
                            <span>Matching verified places</span>
                            <span x-text="searchResults.length + ' found'"></span>
                        </div>

                        <template x-if="searchResults.length > 0">
                            <div class="divide-y divide-[#E5E7E1] mt-1">
                                <template x-for="item in searchResults" :key="item.id">
                                    <div class="py-3 px-3 hover:bg-[#F7F7F2] rounded-2xl transition-colors flex items-center justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full"
                                                      :class="item.is_campus ? 'bg-[#082D00] text-white' : 'bg-[#9BE96B] text-[#082D00]'"
                                                      x-text="item.is_campus ? 'Inside' : 'Outside'">
                                                </span>
                                                <span class="text-xs font-semibold text-[#64705D]" x-text="item.category"></span>
                                            </div>
                                            <h4 class="font-extrabold text-sm text-[#082D00]" x-text="item.name"></h4>
                                            <p class="text-xs text-[#64705D] line-clamp-1 mt-0.5" x-text="item.address || item.campus_area"></p>
                                        </div>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <a :href="'/places/' + item.slug" 
                                               class="px-3 py-1.5 rounded-full text-xs font-bold bg-[#F7F7F2] text-[#082D00] border border-[#E5E7E1] hover:bg-neutral-200 transition-colors">
                                                View
                                            </a>
                                            <a :href="'/locate?destination=' + item.slug" 
                                               class="px-3 py-1.5 rounded-full text-xs font-bold bg-[#082D00] text-white hover:bg-[#134504] transition-colors flex items-center gap-1">
                                                <span>Route</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="searchResults.length === 0">
                            <div class="py-8 text-center text-[#64705D]">
                                <p class="text-sm font-bold text-[#082D00]">No places found.</p>
                                <p class="text-xs mt-1">Try another keyword like "library", "hostel", "dhaba", or "station".</p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- FOUR MAIN DISCOVERY OPTIONS (Directly BELOW the search bar) -->
                <!-- Desktop: 4 in a horizontal row | Mobile: 2x2 responsive grid -->
                <div class="mt-8 sm:mt-10 pt-8 border-t border-[#082D00]/15">
                    <p class="text-xs uppercase font-black tracking-widest text-[#082D00]/70 mb-4 text-left">
                        Discovery Modes
                    </p>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 text-left">
                        
                        <!-- 1. Inside Tour -->
                        <a href="{{ route('inside') }}" 
                           class="group bg-white/90 hover:bg-white p-4 sm:p-5 rounded-3xl border border-[#082D00]/10 hover:border-[#082D00] shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
                            <div>
                                <div class="w-10 h-10 rounded-2xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center font-bold mb-3 shadow-xs group-hover:scale-105 transition-transform">
                                    <!-- Building SVG -->
                                    <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                                        <path d="M9 22v-4h6v4"/>
                                        <path d="M8 6h.01"/>
                                        <path d="M16 6h.01"/>
                                        <path d="M8 10h.01"/>
                                        <path d="M16 10h.01"/>
                                    </svg>
                                </div>
                                <h3 class="font-black text-base sm:text-lg text-[#082D00] tracking-tight group-hover:text-[#134504]">
                                    Inside Tour
                                </h3>
                                <p class="text-xs text-[#64705D] mt-1 font-medium leading-relaxed line-clamp-2">
                                    Real academic blocks, library, auditorium, and student hostels.
                                </p>
                            </div>
                            <div class="mt-3 pt-2 flex items-center gap-1 text-xs font-extrabold text-[#082D00]">
                                <span>Explore Campus</span>
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>

                        <!-- 2. Outside Tour -->
                        <a href="{{ route('outside') }}" 
                           class="group bg-white/90 hover:bg-white p-4 sm:p-5 rounded-3xl border border-[#082D00]/10 hover:border-[#082D00] shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
                            <div>
                                <div class="w-10 h-10 rounded-2xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center font-bold mb-3 shadow-xs group-hover:scale-105 transition-transform">
                                    <!-- Store SVG -->
                                    <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                                        <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
                                    </svg>
                                </div>
                                <h3 class="font-black text-base sm:text-lg text-[#082D00] tracking-tight group-hover:text-[#134504]">
                                    Outside Tour
                                </h3>
                                <p class="text-xs text-[#64705D] mt-1 font-medium leading-relaxed line-clamp-2">
                                    Real eateries, momos, cafes, daily grocery & Bihta stations.
                                </p>
                            </div>
                            <div class="mt-3 pt-2 flex items-center gap-1 text-xs font-extrabold text-[#082D00]">
                                <span>Browse Periphery</span>
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>

                        <!-- 3. Locate -->
                        <a href="{{ route('locate') }}" 
                           class="group bg-white/90 hover:bg-white p-4 sm:p-5 rounded-3xl border border-[#082D00]/10 hover:border-[#082D00] shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
                            <div>
                                <div class="w-10 h-10 rounded-2xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center font-bold mb-3 shadow-xs group-hover:scale-105 transition-transform">
                                    <!-- Navigation SVG -->
                                    <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                                    </svg>
                                </div>
                                <h3 class="font-black text-base sm:text-lg text-[#082D00] tracking-tight group-hover:text-[#134504]">
                                    Locate
                                </h3>
                                <p class="text-xs text-[#64705D] mt-1 font-medium leading-relaxed line-clamp-2">
                                    Apple-style live navigation, route directions & real road times.
                                </p>
                            </div>
                            <div class="mt-3 pt-2 flex items-center gap-1 text-xs font-extrabold text-[#082D00]">
                                <span>Start Routing</span>
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>

                        <!-- 4. Contribute -->
                        <a href="{{ route('contribute') }}" 
                           class="group bg-white/90 hover:bg-white p-4 sm:p-5 rounded-3xl border border-[#082D00]/10 hover:border-[#082D00] shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
                            <div>
                                <div class="w-10 h-10 rounded-2xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center font-bold mb-3 shadow-xs group-hover:scale-105 transition-transform">
                                    <!-- Plus Circle SVG -->
                                    <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M8 12h8"/>
                                        <path d="M12 8v8"/>
                                    </svg>
                                </div>
                                <h3 class="font-black text-base sm:text-lg text-[#082D00] tracking-tight group-hover:text-[#134504]">
                                    Contribute
                                </h3>
                                <p class="text-xs text-[#64705D] mt-1 font-medium leading-relaxed line-clamp-2">
                                    Add missing spots, tea stalls, or services around campus.
                                </p>
                            </div>
                            <div class="mt-3 pt-2 flex items-center gap-1 text-xs font-extrabold text-[#082D00]">
                                <span>Share a Spot</span>
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>

                    </div>
                </div>

            </div>
        </div>

        <!-- Decorative Subtle Background Pattern -->
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

    <!-- SECTION C: EXPLORE CAMPUS (REAL CAMPUS DATA PREVIEW) -->
    <section class="py-16 sm:py-24 bg-white border-y border-[#E5E7E1]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading 
                eyebrow="Inside Campus"
                title="EXPLORE YOUR CAMPUS."
                description="Verified facilities inside the 501-acre IIT Patna campus in Bihta. Supported by official institute maps and directories."
                actionText="View all campus facilities"
                :actionHref="route('inside')"
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach(array_slice($campusPlaces, 0, 6) as $place)
                    <x-place-card :place="$place" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION D: NEARBY DISCOVERY (REAL BIHTA NEARBY PLACES) -->
    <section class="py-16 sm:py-24 bg-[#F7F7F2]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-heading 
                eyebrow="Outside the gates"
                title="GOOD PLACES, JUST AROUND CAMPUS."
                description="Real eateries, dhabas, cafes, and transit stations located within 2-3 km of campus along Bihta-Kanpa Road and Bihta Chowk."
                actionText="See all nearby spots"
                :actionHref="route('outside')"
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach(array_slice($nearbyPlaces, 0, 6) as $place)
                    <x-place-card :place="$place" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- Map Script & Search Helper -->
    @push('scripts')
    <script>
        function homeSearchApp(config) {
            return {
                allPlaces: config.allPlaces,
                searchQuery: '',
                searchResults: [],
                isOpen: false,
                performSearch() {
                    const q = this.searchQuery.toLowerCase().trim();
                    if (!q) {
                        this.searchResults = [];
                        return;
                    }
                    this.searchResults = this.allPlaces.filter(p => {
                        const nameMatch = p.name.toLowerCase().includes(q);
                        const catMatch = p.category.toLowerCase().includes(q);
                        const descMatch = (p.description || '').toLowerCase().includes(q);
                        const addrMatch = (p.address || p.campus_area || '').toLowerCase().includes(q);
                        return nameMatch || catMatch || descMatch || addrMatch;
                    }).slice(0, 6);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
