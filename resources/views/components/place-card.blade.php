@props([
    'place',
])

<article class="group bg-white rounded-2xl border border-[#E5E7E1] overflow-hidden flex flex-col hover:border-[#082D00]/30 hover:shadow-xl transition-all duration-300">
    <!-- Place Image Container -->
    <div class="relative h-48 sm:h-52 w-full overflow-hidden bg-neutral-100">
        <img src="{{ $place['image'] }}" 
             alt="{{ $place['name'] }}" 
             loading="lazy"
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=900&auto=format&fit=crop';">

        <!-- Top Badges -->
        <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 pointer-events-none">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold shadow-sm backdrop-blur-md {{ $place['is_campus'] ? 'bg-[#082D00] text-white' : 'bg-[#9BE96B] text-[#082D00]' }}">
                {{ $place['is_campus'] ? 'Campus' : 'Nearby' }}
            </span>

            @if(!empty($place['status_indicator']))
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/95 text-[#082D00] shadow-sm backdrop-blur-md">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#164606]"></span>
                    {{ $place['status_indicator'] }}
                </span>
            @endif
        </div>

        <!-- Distance pill at bottom right of image -->
        <div class="absolute bottom-3 right-3 pointer-events-none">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-black/70 text-white backdrop-blur-sm">
                <svg class="w-3 h-3 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $place['distance'] }}
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="p-5 flex-1 flex flex-col justify-between">
        <div>
            <!-- Category and Location -->
            <div class="flex items-center justify-between text-xs font-semibold text-[#64705D] mb-1.5">
                <span>{{ $place['category'] }}</span>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-extrabold text-[#082D00] tracking-tight group-hover:text-[#134504] transition-colors leading-snug">
                <a href="{{ route('places.show', $place['slug']) }}" class="focus:outline-none focus:underline">
                    {{ $place['name'] }}
                </a>
            </h3>

            <!-- Tagline / Description -->
            <p class="mt-2 text-sm text-[#64705D] line-clamp-2 leading-relaxed">
                {{ $place['tagline'] }}
            </p>

            <!-- Tags -->
            @if(!empty($place['tags']))
                <div class="mt-3.5 flex flex-wrap gap-1.5">
                    @foreach(array_slice($place['tags'], 0, 3) as $tag)
                        <span class="px-2 py-0.5 rounded-md bg-[#F7F7F2] text-[#64705D] text-[11px] font-medium border border-[#E5E7E1]">
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Card Footer -->
        <div class="mt-5 pt-4 border-t border-[#E5E7E1] flex items-center justify-between">
            <span class="text-xs text-[#64705D] flex items-center gap-1 truncate max-w-[65%]">
                <svg class="w-3.5 h-3.5 text-[#082D00] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="truncate">{{ $place['hours'] }}</span>
            </span>

            <a href="{{ route('places.show', $place['slug']) }}" 
               class="inline-flex items-center gap-1.5 text-xs font-bold text-[#082D00] group-hover:text-[#134504] transition-colors">
                <span>View Details</span>
                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</article>
