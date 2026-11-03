@props([
    'placeholder' => "Search campus places, food, shops...",
    'action' => route('explore'),
    'value' => '',
    'size' => 'lg', // 'lg' or 'md'
])

@php
    $paddingClass = $size === 'lg' ? 'py-4 pl-14 pr-32 text-base md:text-lg' : 'py-3 pl-12 pr-28 text-sm md:text-base';
    $buttonSize = $size === 'lg' ? 'px-6 py-2.5 text-sm md:text-base' : 'px-5 py-2 text-xs md:text-sm';
    $iconSize = $size === 'lg' ? 'w-6 h-6 left-5' : 'w-5 h-5 left-4';
@endphp

<form action="{{ $action }}" method="GET" class="w-full relative group">
    <div class="relative flex items-center">
        <!-- Search icon -->
        <div class="absolute {{ $iconSize }} text-[#64705D] group-focus-within:text-[#082D00] transition-colors pointer-events-none flex items-center justify-center">
            <svg class="w-full h-full stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.3-4.3"/>
            </svg>
        </div>

        <!-- Input field -->
        <input type="text"
               name="q"
               value="{{ $value }}"
               placeholder="{{ $placeholder }}"
               aria-label="Search places"
               class="w-full bg-white text-[#082D00] placeholder:text-[#64705D]/70 font-medium rounded-full border-2 border-transparent shadow-[0_8px_30px_rgb(0,0,0,0.06)] focus:border-[#082D00] focus:outline-none transition-all duration-200 {{ $paddingClass }}">

        <!-- Explore Button -->
        <div class="absolute right-2">
            <button type="submit" 
                    class="rounded-full bg-[#082D00] text-white font-bold hover:bg-[#134504] active:scale-95 transition-all duration-150 flex items-center gap-1.5 shadow-sm {{ $buttonSize }}">
                <span>Explore</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </div>
</form>
