@props([
    'place',
])

<article class="group bg-white rounded-3xl border border-[#E5E7E1] overflow-hidden flex flex-col hover:border-[#082D00]/40 hover:shadow-xl transition-all duration-300">
    <!-- Place Image Container -->
    <div class="relative h-48 sm:h-52 w-full overflow-hidden bg-[#082D00]">
        <x-place-image 
            :image="$place['image'] ?? null" 
            :name="$place['name']" 
            :category="$place['category']" 
            :category-id="$place['category_id'] ?? 'academic'" 
        />

        <!-- Top Badges -->
        <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 pointer-events-none z-20">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black shadow-xs {{ $place['is_campus'] ? 'bg-[#082D00] text-white border border-white/20' : 'bg-[#9BE96B] text-[#082D00]' }}">
                {{ $place['is_campus'] ? 'Inside Campus' : 'Nearby Bihta' }}
            </span>

            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-white/95 text-[#082D00] shadow-xs backdrop-blur-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-[#164606]"></span>
                {{ $place['category'] }}
            </span>
        </div>

        <!-- Area / Distance pill at bottom right of image -->
        <div class="absolute bottom-3 right-3 pointer-events-none z-20">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-black/75 text-white backdrop-blur-xs">
                <svg class="w-3 h-3 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                {{ $place['campus_area'] ?? $place['address'] ?? 'IIT Patna' }}
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
        <div>
            <!-- Title -->
            <h3 class="text-lg sm:text-xl font-extrabold text-[#082D00] tracking-tight group-hover:text-[#134504] transition-colors leading-snug">
                <a href="{{ route('places.show', $place['slug']) }}" class="focus:outline-none focus:underline">
                    {{ $place['name'] }}
                </a>
            </h3>

            <!-- Tagline / Factual Short Description -->
            <p class="mt-2 text-sm text-[#64705D] line-clamp-2 leading-relaxed">
                {{ $place['tagline'] ?? $place['description'] }}
            </p>

            <!-- Verified Tags -->
            @if(!empty($place['tags']))
                <div class="mt-3.5 flex flex-wrap gap-1.5">
                    @foreach(array_slice($place['tags'], 0, 3) as $tag)
                        <span class="px-2 py-0.5 rounded-md bg-[#F7F7F2] text-[#64705D] text-[11px] font-semibold border border-[#E5E7E1]">
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- Verification / Source Attribution -->
            @if(!empty($place['source']))
                <div class="mt-4 pt-3 border-t border-[#E5E7E1] text-[11px] text-[#64705D]">
                    <span class="font-bold text-[#082D00]">Source:</span>
                    <span class="truncate block text-[10px] text-[#64705D]">{{ $place['source'] }}</span>
                </div>
            @endif
        </div>

        <!-- Card Footer Actions -->
        <div class="mt-5 pt-4 border-t border-[#E5E7E1] flex items-center justify-between">
            <a href="{{ route('places.show', $place['slug']) }}" 
               class="text-xs font-bold text-[#64705D] hover:text-[#082D00] transition-colors">
                View Details &rarr;
            </a>

            <a href="{{ route('locate', ['destination' => $place['slug']]) }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black bg-[#9BE96B] text-[#082D00] hover:bg-[#8AE157] active:scale-95 transition-all shadow-xs">
                <svg class="w-3 h-3 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                </svg>
                <span>Route</span>
            </a>
        </div>
    </div>
</article>
