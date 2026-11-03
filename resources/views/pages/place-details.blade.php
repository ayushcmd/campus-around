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
                        <div class="flex items-center gap-2 mb-3">
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

                    <!-- Main Hero Image -->
                    <div class="relative w-full h-[320px] sm:h-[420px] rounded-3xl overflow-hidden border border-[#E5E7E1] shadow-sm bg-neutral-100">
                        <img src="{{ $place['image'] }}" 
                             alt="{{ $place['name'] }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute bottom-4 left-4">
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-black/75 text-white text-xs font-bold backdrop-blur-xs">
                                <svg class="w-3.5 h-3.5 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                {{ $place['address'] ?? $place['campus_area'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Place Description Block -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E5E7E1] shadow-sm space-y-4">
                        <h3 class="text-xl font-extrabold text-[#082D00] uppercase tracking-tight">About this spot</h3>
                        <p class="text-[#64705D] text-base leading-relaxed">
                            {{ $place['description'] }}
                        </p>

                        <!-- Highlights & Tags -->
                        <div class="pt-4 border-t border-[#E5E7E1]">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#64705D] mb-3">Key Highlights & Features</h4>
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

                </div>

                <!-- Right Sidebar Details (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Quick Info Panel -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E5E7E1] shadow-sm space-y-6">
                        <h3 class="text-lg font-extrabold text-[#082D00] uppercase tracking-tight pb-3 border-b border-[#E5E7E1]">
                            Location & Access
                        </h3>

                        <!-- Hours -->
                        <div class="flex items-start gap-4">
                            <span class="w-10 h-10 rounded-2xl bg-[#F7F7F2] text-[#082D00] flex items-center justify-center shrink-0 border border-[#E5E7E1]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            </span>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#64705D]">Hours</span>
                                <p class="text-sm font-bold text-[#082D00] mt-0.5">{{ $place['hours'] ?? 'Consult facility notice' }}</p>
                            </div>
                        </div>

                        <!-- Proximity / Area -->
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
                    <div class="bg-white rounded-3xl p-4 border border-[#E5E7E1] shadow-sm">
                        <div class="flex items-center justify-between mb-3 px-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#082D00]">Spot Location</span>
                            <span class="text-[11px] text-[#64705D]">{{ $place['latitude'] }}°N, {{ $place['longitude'] }}°E</span>
                        </div>
                        <div id="place-detail-map" class="w-full h-56 rounded-2xl overflow-hidden border border-[#E5E7E1] z-10"></div>
                    </div>

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
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const coords = [{{ $place['latitude'] }}, {{ $place['longitude'] }}];
            const map = L.map('place-detail-map', {
                center: coords,
                zoom: 16,
                scrollWheelZoom: false,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
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
                        border: 2px solid #FFFFFF;
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
             .bindPopup("<strong>{{ $place['name'] }}</strong><br>{{ $place['campus_area'] }}")
             .openPopup();
        });
    </script>
    @endpush
</x-app-layout>
