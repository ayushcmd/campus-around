<x-app-layout :nav-theme="'light'">
    <x-slot name="title">Explore Places — CampusAround (IIT Patna)</x-slot>

    <div x-data="explorePageApp({
            allPlaces: @json($allPlaces),
            initialCategory: '{{ $initialCategory ?? 'all' }}',
            initialSearch: '{{ $initialSearch ?? '' }}',
            initialType: '{{ $initialType ?? 'all' }}'
         })" 
         class="py-10 sm:py-16">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="max-w-3xl mb-8 sm:mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#9BE96B]/30 text-[#082D00] text-xs font-bold uppercase tracking-wider mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#082D00]"></span>
                    Campus Directory
                </div>
                <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-none">
                    EXPLORE ALL PLACES.
                </h1>
                <p class="mt-3 text-base sm:text-lg text-[#64705D] font-medium leading-relaxed">
                    Filter by category, search by keywords, or toggle between on-campus facilities and nearby Bihta destinations.
                </p>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-3xl p-4 sm:p-6 border border-[#E5E7E1] shadow-sm mb-8 space-y-5">
                
                <!-- Search row -->
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
                           placeholder="Type to filter facilities, food, cafes, shops..."
                           class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-medium rounded-full py-3.5 pl-12 pr-10 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                    
                    <button x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''; filterPlaces()" 
                            type="button" 
                            class="absolute inset-y-0 right-4 flex items-center text-[#64705D] hover:text-[#082D00]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Scope Filter & Stats Row -->
                <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-[#E5E7E1]">
                    
                    <!-- Location Scope Toggle -->
                    <div class="flex items-center gap-1.5 p-1 bg-[#F7F7F2] rounded-full border border-[#E5E7E1]">
                        <button type="button"
                                @click="setLocationType('all')"
                                :class="locationType === 'all' ? 'bg-[#082D00] text-white shadow-sm' : 'text-[#082D00] hover:bg-black/5'"
                                class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">
                            All Locations
                        </button>
                        <button type="button"
                                @click="setLocationType('campus')"
                                :class="locationType === 'campus' ? 'bg-[#082D00] text-white shadow-sm' : 'text-[#082D00] hover:bg-black/5'"
                                class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">
                            🏛️ Campus Only
                        </button>
                        <button type="button"
                                @click="setLocationType('nearby')"
                                :class="locationType === 'nearby' ? 'bg-[#082D00] text-white shadow-sm' : 'text-[#082D00] hover:bg-black/5'"
                                class="px-4 py-1.5 rounded-full text-xs font-bold transition-all">
                            📍 Nearby Only
                        </button>
                    </div>

                    <!-- Counter -->
                    <div class="text-xs font-bold text-[#64705D]">
                        Showing <span class="text-[#082D00]" x-text="filteredPlaces.length"></span> places
                    </div>
                </div>

                <!-- Category Chips Horizontal Carousel -->
                <div class="pt-2 flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                    @foreach($categories as $cat)
                        <button type="button"
                                @click="setCategory('{{ $cat['id'] }}')"
                                :class="selectedCategory === '{{ $cat['id'] }}' ? 'bg-[#9BE96B] text-[#082D00] border-[#082D00] font-extrabold shadow-sm' : 'bg-white text-[#64705D] border-[#E5E7E1] hover:border-[#082D00]/40 font-semibold'"
                                class="shrink-0 px-4 py-2 rounded-full border text-xs transition-all flex items-center gap-1.5">
                            <span>{{ $cat['name'] }}</span>
                        </button>
                    @endforeach
                </div>

            </div>

            <!-- Active Filters Indicators -->
            <div x-show="selectedCategory !== 'all' || searchQuery !== '' || locationType !== 'all'" class="flex items-center gap-2 mb-6 text-xs flex-wrap">
                <span class="font-bold text-[#64705D]">Active filters:</span>
                <template x-if="selectedCategory !== 'all'">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-[#9BE96B] text-[#082D00] font-bold">
                        Category: <span x-text="selectedCategory"></span>
                        <button type="button" @click="setCategory('all')" class="ml-1 text-[#082D00] hover:opacity-70">&times;</button>
                    </span>
                </template>
                <template x-if="locationType !== 'all'">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-[#082D00] text-white font-bold">
                        Scope: <span x-text="locationType"></span>
                        <button type="button" @click="setLocationType('all')" class="ml-1 text-white hover:opacity-70">&times;</button>
                    </span>
                </template>
                <template x-if="searchQuery">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-neutral-200 text-[#082D00] font-bold">
                        Query: "<span x-text="searchQuery"></span>"
                        <button type="button" @click="searchQuery = ''; filterPlaces()" class="ml-1 text-[#082D00] hover:opacity-70">&times;</button>
                    </span>
                </template>
                <button type="button" @click="resetAllFilters()" class="text-[#082D00] underline font-bold hover:text-black">
                    Clear all
                </button>
            </div>

            <!-- Places Grid -->
            <div x-show="filteredPlaces.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <template x-for="place in filteredPlaces" :key="place.slug">
                    <article class="group bg-white rounded-2xl border border-[#E5E7E1] overflow-hidden flex flex-col hover:border-[#082D00]/30 hover:shadow-xl transition-all duration-300">
                        <!-- Image -->
                        <div class="relative h-48 sm:h-52 w-full overflow-hidden bg-neutral-100">
                            <img :src="place.image" 
                                 :alt="place.name"
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=900&auto=format&fit=crop';">
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 pointer-events-none">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold shadow-sm"
                                      :class="place.is_campus ? 'bg-[#082D00] text-white' : 'bg-[#9BE96B] text-[#082D00]'"
                                      x-text="place.is_campus ? 'Campus' : 'Nearby'">
                                </span>
                                <template x-if="place.status_indicator">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/95 text-[#082D00] shadow-sm"
                                          x-text="place.status_indicator">
                                    </span>
                                </template>
                            </div>

                            <!-- Distance / Area -->
                            <div class="absolute bottom-3 right-3 pointer-events-none">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-black/70 text-white backdrop-blur-sm"
                                      x-text="place.campus_area || place.distance || 'IIT Patna'">
                                </span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-semibold text-[#64705D]" x-text="place.category"></span>
                                <h3 class="text-xl font-extrabold text-[#082D00] tracking-tight group-hover:text-[#134504] transition-colors leading-snug mt-1">
                                    <a :href="'/places/' + place.slug" class="focus:outline-none focus:underline" x-text="place.name"></a>
                                </h3>
                                <p class="mt-2 text-sm text-[#64705D] line-clamp-2 leading-relaxed" x-text="place.tagline"></p>
                                
                                <div class="mt-3.5 flex flex-wrap gap-1.5" x-show="place.tags && place.tags.length > 0">
                                    <template x-for="tag in place.tags.slice(0, 3)" :key="tag">
                                        <span class="px-2 py-0.5 rounded-md bg-[#F7F7F2] text-[#64705D] text-[11px] font-medium border border-[#E5E7E1]" x-text="tag"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-t border-[#E5E7E1] flex items-center justify-between">
                                <a :href="'/places/' + place.slug" class="text-xs font-bold text-[#64705D] hover:text-[#082D00]">
                                    Details
                                </a>
                                <a :href="'/locate?destination=' + place.slug" class="inline-flex items-center gap-1 text-xs font-extrabold px-3 py-1.5 rounded-full bg-[#9BE96B] text-[#082D00] hover:bg-[#8AE157] transition-all">
                                    <span>Route</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            <!-- Empty State Component -->
            <div x-show="filteredPlaces.length === 0" x-cloak class="py-12">
                <x-empty-state 
                    title="No matching places found"
                    message="We couldn't find any places matching your current filters. Try searching with a different term or clearing your filters."
                />
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function explorePageApp(config) {
            return {
                places: config.allPlaces,
                filteredPlaces: config.allPlaces,
                selectedCategory: config.initialCategory || 'all',
                searchQuery: config.initialSearch || '',
                locationType: config.initialType || 'all',
                init() {
                    this.filterPlaces();
                },
                setCategory(catId) {
                    this.selectedCategory = catId;
                    this.filterPlaces();
                },
                setLocationType(type) {
                    this.locationType = type;
                    this.filterPlaces();
                },
                resetAllFilters() {
                    this.selectedCategory = 'all';
                    this.searchQuery = '';
                    this.locationType = 'all';
                    this.filterPlaces();
                },
                filterPlaces() {
                    const q = this.searchQuery.toLowerCase().trim();
                    const cat = this.selectedCategory;
                    const scope = this.locationType;

                    this.filteredPlaces = this.places.filter(place => {
                        // Category match
                        const matchCat = (cat === 'all') || (place.category_id === cat);

                        // Location scope match
                        let matchScope = true;
                        if (scope === 'campus') {
                            matchScope = place.is_campus === true;
                        } else if (scope === 'nearby') {
                            matchScope = place.is_campus === false;
                        }

                        // Search query match
                        let matchQuery = true;
                        if (q) {
                            const nameMatch = place.name.toLowerCase().includes(q);
                            const descMatch = place.description.toLowerCase().includes(q);
                            const tagMatch = place.tags && place.tags.some(t => t.toLowerCase().includes(q));
                            const locMatch = place.location_label.toLowerCase().includes(q);
                            matchQuery = nameMatch || descMatch || tagMatch || locMatch;
                        }

                        return matchCat && matchScope && matchQuery;
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
