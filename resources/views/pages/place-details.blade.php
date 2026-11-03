<x-app-layout :nav-theme="'light'">
    <x-slot name="title">{{ $place['name'] }} — CampusAround IIT Patna</x-slot>

    <div class="py-10 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-[#64705D] mb-6 sm:mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-[#082D00]">Home</a>
                <span>/</span>
                <a href="{{ route('explore') }}" class="hover:text-[#082D00]">Explore</a>
                <span>/</span>
                <a href="{{ route('explore', ['category' => $place['category_id']]) }}" class="hover:text-[#082D00]">{{ $place['category'] }}</a>
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider {{ $place['is_campus'] ? 'bg-[#082D00] text-white' : 'bg-[#9BE96B] text-[#082D00]' }}">
                                {{ $place['is_campus'] ? 'On Campus' : 'Nearby Bihta' }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-[#64705D] border border-[#E5E7E1]">
                                {{ $place['category'] }}
                            </span>
                            @if(!empty($place['status_indicator']))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#9BE96B]/20 text-[#082D00]">
                                    <span class="w-2 h-2 rounded-full bg-[#164606]"></span>
                                    {{ $place['status_indicator'] }}
                                </span>
                            @endif
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
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/75 text-white text-xs font-bold backdrop-blur-md">
                                <svg class="w-3.5 h-3.5 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $place['location_label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Place Description Block -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E5E7E1] shadow-sm space-y-4">
                        <h3 class="text-xl font-extrabold text-[#082D00] uppercase tracking-tight">About this spot</h3>
                        <p class="text-[#64705D] text-base leading-relaxed">
                            {{ $place['description'] }}
                        </p>

                        <!-- Tags / Amenities -->
                        <div class="pt-4 border-t border-[#E5E7E1]">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#64705D] mb-3">Key Highlights & Amenities</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($place['tags'] as $tag)
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#F7F7F2] text-[#082D00] text-xs font-bold border border-[#E5E7E1]">
                                        <svg class="w-3.5 h-3.5 text-[#164606]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
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
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#64705D]">Hours</span>
                                <p class="text-sm font-bold text-[#082D00] mt-0.5">{{ $place['hours'] }}</p>
                                <span class="text-[11px] text-[#64705D]">Student community verified demo data</span>
                            </div>
                        </div>

                        <!-- Distance from Core -->
                        <div class="flex items-start gap-4">
                            <span class="w-10 h-10 rounded-2xl bg-[#F7F7F2] text-[#082D00] flex items-center justify-center shrink-0 border border-[#E5E7E1]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </span>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-[#64705D]">Proximity</span>
                                <p class="text-sm font-bold text-[#082D00] mt-0.5">{{ $place['distance'] }}</p>
                                <p class="text-[11px] text-[#64705D]">{{ $place['location_label'] }}</p>
                            </div>
                        </div>

                        <!-- Directions Action Button -->
                        <div class="pt-2">
                            <button type="button" 
                                    onclick="alert('Directions navigation preview: IIT Patna Bihta Campus (Coordinates: {{ $place['coordinates'][0] }}, {{ $place['coordinates'][1] }}). In production this opens navigation apps.')"
                                    class="w-full py-3.5 px-6 rounded-full bg-[#082D00] text-white font-bold text-sm hover:bg-[#134504] active:scale-95 transition-all shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 20l-5.447-2.724A2 2 0 013 15.485V5.515a2 2 0 011.553-1.954L9 2m0 18l6-3m-6 3V2m6 15l5.447 2.724A2 2 0 0021 17.938V7.967a2 2 0 00-1.553-1.954L15 4m0 13V4m0 0L9 2"/></svg>
                                <span>Get Directions (Preview)</span>
                            </button>
                        </div>
                    </div>

                    <!-- Mini Map Card -->
                    <div class="bg-white rounded-3xl p-4 border border-[#E5E7E1] shadow-sm">
                        <div class="flex items-center justify-between mb-3 px-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#082D00]">Spot Location</span>
                            <span class="text-[11px] text-[#64705D]">{{ $place['coordinates'][0] }}°N, {{ $place['coordinates'][1] }}°E</span>
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
                        <a href="{{ route('explore') }}" class="text-sm font-bold text-[#082D00] hover:underline">
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
            const coords = @json($place['coordinates']);
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
                        background-color: {{ $place['is_campus'] ? '#082D00' : '#86D953' }};
                        color: {{ $place['is_campus'] ? '#FFFFFF' : '#082D00' }};
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
             .bindPopup("<strong>{{ $place['name'] }}</strong><br>{{ $place['location_label'] }}")
             .openPopup();
        });
    </script>
    @endpush
</x-app-layout>
