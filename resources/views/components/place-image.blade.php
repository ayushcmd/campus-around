@props([
    'image' => null,
    'name' => 'Campus Facility',
    'category' => 'Campus Facility',
    'categoryId' => 'academic',
    'imgClass' => 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105',
])

<div class="relative w-full h-full overflow-hidden bg-[#082D00] select-none">
    @if(!empty($image))
        <img src="{{ $image }}" 
             alt="{{ $name }}" 
             loading="lazy" 
             class="{{ $imgClass }}"
             onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
    @endif

    <div class="{{ !empty($image) ? 'hidden' : 'flex' }} w-full h-full flex-col items-center justify-center bg-gradient-to-br from-[#082D00] via-[#0D3800] to-[#041700] text-center p-4 relative overflow-hidden">
        <!-- Background subtle dot grid pattern -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#9BE96B_1px,transparent_1px)] [background-size:16px_16px]"></div>

        <!-- Center Icon Box -->
        <div class="relative z-10 w-14 h-14 rounded-2xl bg-[#9BE96B]/15 border border-[#9BE96B]/30 flex items-center justify-center text-[#9BE96B] mb-2 shadow-inner">
            @switch($categoryId)
                @case('library')
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                        <path d="M6 6h10"/>
                        <path d="M6 10h10"/>
                    </svg>
                    @break

                @case('hostel')
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    @break

                @case('auditorium')
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="15" x="2" y="3" rx="2"/>
                        <circle cx="12" cy="10" r="3"/>
                        <path d="M7 21v-3"/>
                        <path d="M17 21v-3"/>
                        <path d="M2 18h20"/>
                    </svg>
                    @break

                @case('sports')
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
                        <path d="M4 22h16"/>
                        <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
                        <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                    </svg>
                    @break

                @case('food')
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2"/>
                        <path d="M15 2v19"/>
                        <path d="M5 2v7a3 3 0 0 0 3 3h0a3 3 0 0 0 3-3V2"/>
                        <path d="M8 12v9"/>
                    </svg>
                    @break

                @case('administration')
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                    @break

                @case('healthcare')
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                    @break

                @case('research')
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 2v7.527a2 2 0 0 1-.211.896L4.72 20.55a1 1 0 0 0 .9 1.45h12.76a1 1 0 0 0 .9-1.45l-5.069-10.127A2 2 0 0 1 14 9.527V2"/>
                        <path d="M8.5 2h7"/>
                        <path d="M7 16h10"/>
                    </svg>
                    @break

                @case('landmarks')
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                    </svg>
                    @break

                @case('services')
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                        <path d="M9 22v-4h6v4"/>
                        <path d="M8 6h.01"/>
                        <path d="M16 6h.01"/>
                        <path d="M8 10h.01"/>
                        <path d="M16 10h.01"/>
                    </svg>
                    @break

                @default
                    <svg class="w-7 h-7 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
            @endswitch
        </div>

        <!-- Label below icon -->
        <div class="relative z-10">
            <span class="text-[9px] font-black tracking-widest text-[#9BE96B] uppercase block">IIT PATNA CAMPUS</span>
            <span class="text-xs font-bold text-white/90 truncate max-w-[200px] block mt-0.5">{{ $category }}</span>
        </div>
    </div>
</div>
