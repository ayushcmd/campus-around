<x-app-layout :nav-theme="'light'">
    <x-slot name="title">{{ $place['name'] }} — CampusAround (IIT Patna)</x-slot>

    <div class="py-8 sm:py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-[#64705D] mb-6 sm:mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-[#082D00]">Home</a>
                <span>/</span>
                <a href="{{ $place['is_campus'] ? route('inside') : route('outside') }}" class="hover:text-[#082D00]">
                    {{ $place['is_campus'] ? 'Inside Tour' : 'Outside Tour' }}
                </a>
                <span>/</span>
                <span class="text-[#082D00] truncate max-w-xs">{{ $place['name'] }}</span>
            </nav>

            <!-- Main Header Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- Left Details (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <!-- Badges -->
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ $place['is_campus'] ? 'bg-[#082D00] text-white' : 'bg-[#9BE96B] text-[#082D00]' }}">
                                {{ $place['is_campus'] ? 'Inside Campus' : 'Nearby Bihta' }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-[#64705D] border border-[#E5E7E1]">
                                {{ $place['category'] }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#9BE96B]/25 text-[#082D00]">
                                <span class="w-2 h-2 rounded-full bg-[#164606]"></span>
                                {{ $place['verification_status'] }}
                            </span>
                        </div>

                        <!-- Place Title -->
                        <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-tight">
                            {{ $place['name'] }}
                        </h1>

                        <!-- Tagline -->
                        <p class="mt-3 text-lg text-[#64705D] font-medium leading-relaxed">
                            {{ $place['tagline'] }}
                        </p>
                    </div>

                    <!-- Main Hero Image with Clean Vector Fallback -->
                    <div class="relative w-full h-[320px] sm:h-[420px] rounded-3xl overflow-hidden border border-[#E5E7E1] shadow-sm bg-[#082D00]">
                        <x-place-image 
                            :image="$place['image'] ?? null" 
                            :name="$place['name']" 
                            :category="$place['category']" 
                            :category-id="$place['category_id'] ?? 'academic'" 
                            img-class="w-full h-full object-cover"
                        />
                        <div class="absolute bottom-4 left-4 z-20">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-black/75 text-white text-xs font-bold backdrop-blur-xs">
                                <svg class="w-3.5 h-3.5 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                {{ $place['campus_area'] ?? $place['address'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Action Strip -->
                    <div class="flex flex-wrap items-center gap-3 p-4 bg-white rounded-3xl border border-[#E5E7E1] shadow-xs">
                        <a href="{{ route('locate', ['destination' => $place['slug']]) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#082D00] text-white text-xs font-extrabold hover:bg-[#134504] transition-all shadow-xs">
                            <svg class="w-3.5 h-3.5 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                            <span>Navigate</span>
                        </a>

                        @if(!empty($place['phone']))
                            <a href="tel:{{ $place['phone'] }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-[#F7F7F2] text-[#082D00] text-xs font-bold border border-[#E5E7E1] hover:bg-neutral-200 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>Call: {{ $place['phone'] }}</span>
                            </a>
                        @endif

                        @if(!empty($place['website']))
                            <a href="{{ $place['website'] }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-[#F7F7F2] text-[#082D00] text-xs font-bold border border-[#E5E7E1] hover:bg-neutral-200 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" x2="22" y1="12" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                <span>Website</span>
                            </a>
                        @endif

                        @if(!empty($place['menu']) && count($place['menu']) > 0)
                            <a href="#menu-section"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-[#9BE96B]/30 text-[#082D00] text-xs font-bold border border-[#9BE96B] hover:bg-[#9BE96B]/50 transition-all">
                                <span>🍽️ View Menu</span>
                            </a>
                        @endif

                        <a href="{{ route('contribute', ['place' => $place['slug']]) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-white text-[#64705D] text-xs font-bold border border-[#E5E7E1] hover:text-[#082D00] hover:border-[#082D00] transition-all ml-auto">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Suggest an Edit</span>
                        </a>
                    </div>

                    <!-- Place Description Block -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E5E7E1] shadow-sm space-y-4">
                        <h3 class="text-xl font-extrabold text-[#082D00] uppercase tracking-tight">About this spot</h3>
                        <p class="text-[#64705D] text-base leading-relaxed">
                            {{ $place['description'] }}
                        </p>

                        <!-- Highlights & Tags -->
                        <div class="pt-4 border-t border-[#E5E7E1]">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#64705D] mb-3">Key Highlights & Tags</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($place['tags'] as $tag)
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#F7F7F2] text-[#082D00] text-xs font-bold border border-[#E5E7E1]">
                                        <svg class="w-3.5 h-3.5 text-[#164606]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Source Citation -->
                        <div class="pt-4 border-t border-[#E5E7E1] bg-[#F7F7F2] -mx-6 sm:-mx-8 -mb-6 sm:-mb-8 p-4 sm:p-6 rounded-b-3xl">
                            <span class="text-xs font-bold uppercase text-[#082D00] block mb-1">Source & Verification</span>
                            <p class="text-xs text-[#64705D] leading-relaxed">
                                {{ $place['source'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Section: Menu and Food Prices (for dining, cafes, and dhabas) -->
                    @if(in_array($place['category_id'] ?? '', ['food', 'restaurants', 'cafes']))
                        <div id="menu-section" class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E5E7E1] shadow-sm scroll-mt-24 space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-[#E5E7E1]">
                                <div>
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#9BE96B]/20 text-[#082D00] text-xs font-bold mb-1">
                                        <span>Verified Menu Items</span>
                                    </div>
                                    <h3 class="text-xl font-black uppercase text-[#082D00] tracking-tight">Food Menu & Prices</h3>
                                </div>
                                <a href="{{ route('contribute', ['place' => $place['slug'], 'action' => 'menu']) }}"
                                   class="text-xs font-bold text-[#082D00] underline hover:text-[#134504] self-start sm:self-auto">
                                    Suggest Menu Update &rarr;
                                </a>
                            </div>

                            @if(!empty($place['menu']) && count($place['menu']) > 0)
                                @php
                                    // Group menu by item category if available
                                    $groupedMenu = [];
                                    foreach ($place['menu'] as $item) {
                                        $cat = $item['category'] ?? 'Popular Items';
                                        $groupedMenu[$cat][] = $item;
                                    }
                                @endphp

                                <div class="space-y-6">
                                    @foreach($groupedMenu as $categoryName => $items)
                                        <div>
                                            <h4 class="text-xs font-black uppercase tracking-wider text-[#64705D] mb-3 pb-1 border-b border-[#E5E7E1]/60">
                                                {{ $categoryName }}
                                            </h4>
                                            <div class="divide-y divide-[#E5E7E1]">
                                                @foreach($items as $menuItem)
                                                    <div class="py-3 flex items-start justify-between gap-4">
                                                        <div>
                                                            <h5 class="text-sm font-extrabold text-[#082D00]">{{ $menuItem['name'] }}</h5>
                                                            @if(!empty($menuItem['description']))
                                                                <p class="text-xs text-[#64705D] mt-0.5 leading-relaxed">{{ $menuItem['description'] }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="text-right shrink-0">
                                                            <span class="text-sm font-black text-[#082D00] bg-[#F7F7F2] px-2.5 py-1 rounded-lg border border-[#E5E7E1]">
                                                                ₹{{ $menuItem['price'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="pt-3 text-[11px] text-[#64705D] flex items-center justify-between border-t border-[#E5E7E1]">
                                    <span>Prices subject to seasonal revision by management. No fabricated items.</span>
                                    <span class="font-bold text-[#082D00]">₹ In Indian Rupees</span>
                                </div>
                            @else
                                <!-- Menu Unverified Notice -->
                                <div class="p-6 rounded-2xl bg-[#F7F7F2] border border-[#E5E7E1] text-center space-y-3">
                                    <span class="w-10 h-10 mx-auto rounded-full bg-white flex items-center justify-center text-lg border border-[#E5E7E1]">
                                        📋
                                    </span>
                                    <div>
                                        <h4 class="text-sm font-bold text-[#082D00]">Menu information has not been verified yet.</h4>
                                        <p class="text-xs text-[#64705D] mt-1 max-w-md mx-auto">
                                            Do you have an updated photo of their printed menu or student prices? Help keep the campus community informed.
                                        </p>
                                    </div>
                                    <div class="pt-2">
                                        <x-button variant="primary" size="sm" :href="route('contribute', ['place' => $place['slug'], 'action' => 'menu'])">
                                            <span>Add Menu for {{ $place['name'] }}</span>
                                        </x-button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Community Contribution Options directly for this spot -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E5E7E1] shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-extrabold uppercase text-[#082D00] tracking-tight">
                                Contribute Information for this Spot
                            </h3>
                            <span class="text-xs text-[#64705D]">Community Driven</span>
                        </div>
                        <p class="text-xs text-[#64705D] leading-relaxed">
                            Have updated timings, a clear photograph, or menu corrections for <strong class="text-[#082D00]">{{ $place['name'] }}</strong>? Submit a suggestion for student moderation review.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                            <a href="{{ route('contribute', ['place' => $place['slug'], 'action' => 'details']) }}"
                               class="p-3 rounded-2xl bg-[#F7F7F2] border border-[#E5E7E1] text-center hover:border-[#082D00] hover:bg-neutral-100 transition-all flex flex-col items-center">
                                <span class="text-base mb-1">📝</span>
                                <span class="text-xs font-bold text-[#082D00]">Suggest Edit</span>
                                <span class="text-[10px] text-[#64705D] mt-0.5">Hours, contact & info</span>
                            </a>

                            <a href="{{ route('contribute', ['place' => $place['slug'], 'action' => 'photo']) }}"
                               class="p-3 rounded-2xl bg-[#F7F7F2] border border-[#E5E7E1] text-center hover:border-[#082D00] hover:bg-neutral-100 transition-all flex flex-col items-center">
                                <span class="text-base mb-1">📸</span>
                                <span class="text-xs font-bold text-[#082D00]">Add Photo</span>
                                <span class="text-[10px] text-[#64705D] mt-0.5">Upload verified image</span>
                            </a>

                            <a href="{{ route('contribute', ['place' => $place['slug'], 'action' => 'location']) }}"
                               class="p-3 rounded-2xl bg-[#F7F7F2] border border-[#E5E7E1] text-center hover:border-[#082D00] hover:bg-neutral-100 transition-all flex flex-col items-center">
                                <span class="text-base mb-1">📍</span>
                                <span class="text-xs font-bold text-[#082D00]">Fix Location</span>
                                <span class="text-[10px] text-[#64705D] mt-0.5">Coordinates & gate access</span>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Right Sidebar Details (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Quick Info Panel -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E5E7E1] shadow-sm space-y-6">
                        <h3 class="text-lg font-extrabold text-[#082D00] uppercase tracking-tight pb-3 border-b border-[#E5E7E1]">
                            Location & Contact Info
                        </h3>

                        <!-- Hours (Only if available) -->
                        @if(!empty($place['opening_hours']) || !empty($place['hours']))
                            <div class="flex items-start gap-4">
                                <span class="w-10 h-10 rounded-2xl bg-[#F7F7F2] text-[#082D00] flex items-center justify-center shrink-0 border border-[#E5E7E1]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                </span>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-[#64705D]">Operating Hours</span>
                                    <p class="text-sm font-bold text-[#082D00] mt-0.5">{{ $place['opening_hours'] ?? $place['hours'] }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Proximity / Address -->
                        <div class="flex items-start gap-4">
                            <span class="w-10 h-10 rounded-2xl bg-[#F7F7F2] text-[#082D00] flex items-center justify-center shrink-0 border border-[#E5E7E1]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </span>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#64705D]">Campus Area</span>
                                <p class="text-sm font-bold text-[#082D00] mt-0.5">{{ $place['campus_area'] }}</p>
                                <p class="text-[11px] text-[#64705D] mt-0.5">{{ $place['address'] }}</p>
                            </div>
                        </div>

                        <!-- Price Range (Only if verified) -->
                        @if(!empty($place['price_range']))
                            <div class="flex items-start gap-4">
                                <span class="w-10 h-10 rounded-2xl bg-[#F7F7F2] text-[#082D00] flex items-center justify-center shrink-0 border border-[#E5E7E1] font-black text-sm">
                                    ₹
                                </span>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-[#64705D]">Price Range</span>
                                    <p class="text-sm font-bold text-[#082D00] mt-0.5">{{ $place['price_range'] }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Phone / Contact (Only if verified) -->
                        @if(!empty($place['phone']))
                            <div class="flex items-start gap-4">
                                <span class="w-10 h-10 rounded-2xl bg-[#F7F7F2] text-[#082D00] flex items-center justify-center shrink-0 border border-[#E5E7E1]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-[#64705D]">Contact Number</span>
                                    <a href="tel:{{ $place['phone'] }}" class="text-sm font-bold text-[#082D00] mt-0.5 hover:underline block">
                                        {{ $place['phone'] }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Live Route Button (Hooks into /locate) -->
                        <div class="pt-2">
                            <a href="{{ route('locate', ['destination' => $place['slug']]) }}"
                               class="w-full py-3.5 px-6 rounded-full bg-[#082D00] text-white font-black text-sm hover:bg-[#134504] active:scale-95 transition-all shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                <span>Navigate to this Spot in Locate</span>
                            </a>
                        </div>
                    </div>

                    <!-- Mini Map Card -->
                    @if(!empty($place['latitude']) && !empty($place['longitude']))
                        <div class="bg-white rounded-3xl p-4 border border-[#E5E7E1] shadow-sm">
                            <div class="flex items-center justify-between mb-3 px-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-[#082D00]">Spot Location</span>
                                <span class="text-[11px] text-[#64705D]">{{ $place['latitude'] }}°N, {{ $place['longitude'] }}°E</span>
                            </div>
                            <div id="place-detail-map" class="w-full h-56 rounded-2xl overflow-hidden border border-[#E5E7E1] z-10"></div>
                        </div>
                    @else
                        <div class="bg-white rounded-3xl p-6 border border-[#E5E7E1] shadow-sm text-center">
                            <span class="w-10 h-10 mx-auto rounded-2xl bg-[#F7F7F2] text-[#64705D] flex items-center justify-center mb-2 border border-[#E5E7E1]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                            </span>
                            <h4 class="text-xs font-black uppercase tracking-wider text-[#082D00]">GPS Coordinates Unsurveyed</h4>
                            <p class="text-xs text-[#64705D] mt-1">Exact satellite coordinates are not yet independently confirmed for this location.</p>
                        </div>
                    @endif

                </div>

            </div>

            <!-- Related Places Section -->
            @if(count($relatedPlaces) > 0)
                <div class="mt-20 pt-16 border-t border-[#E5E7E1]">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <span class="text-xs font-extrabold uppercase tracking-wider text-[#64705D]">More to explore</span>
                            <h2 class="text-2xl sm:text-3xl font-black uppercase text-[#082D00]">Similar places around campus</h2>
                        </div>
                        <a href="{{ $place['is_campus'] ? route('inside') : route('outside') }}" class="text-sm font-bold text-[#082D00] hover:underline">
                            Browse all &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                        @foreach($relatedPlaces as $related)
                            <x-place-card :place="$related" />
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Mini map initialization -->
    @if(!empty($place['latitude']) && !empty($place['longitude']))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof L === 'undefined') return;
            const coords = [{{ $place['latitude'] }}, {{ $place['longitude'] }}];
            const map = L.map('place-detail-map', {
                center: coords,
                zoom: 16,
                scrollWheelZoom: false,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const customIcon = L.divIcon({
                className: 'custom-single-pin',
                html: `
                    <div style="
                        background-color: {{ $place['is_campus'] ? '#082D00' : '#9BE96B' }};
                        color: {{ $place['is_campus'] ? '#9BE96B' : '#082D00' }};
                        width: 32px;
                        height: 32px;
                        border-radius: 9999px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: 2px solid {{ $place['is_campus'] ? '#FFFFFF' : '#082D00' }};
                        box-shadow: 0 4px 10px rgba(0,0,0,0.25);
                        font-weight: 800;
                        font-size: 13px;
                    ">
                        {{ $place['is_campus'] ? '🏛️' : '📍' }}
                    </div>
                `,
                iconSize: [32, 32],
                iconAnchor: [16, 16],
                popupAnchor: [0, -18],
            });

            L.marker(coords, { icon: customIcon })
             .addTo(map)
             .bindPopup("<strong>{{ addslashes($place['name']) }}</strong><br>{{ addslashes($place['campus_area'] ?? '') }}")
             .openPopup();
        });
    </script>
    @endpush
    @endif
</x-app-layout>
