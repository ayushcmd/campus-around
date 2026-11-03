@props([
    'category',
    'active' => false,
    'href' => null,
])

@php
    $svgIcon = match($category['id'] ?? '') {
        'all' => '<path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/>',
        'campus-facilities' => '<path d="M3 21h18M3 10h18M5 10v11M19 10v11M9 10v11M15 10v11M12 2 2 7h20L12 2z"/>',
        'food-drinks' => '<path d="M18 2v6a3 3 0 0 1-3 3 3 3 0 0 1-3-3V2M15 11v11M4 2v10a3 3 0 0 0 3 3h1a3 3 0 0 0 3-3V2M7 15v7"/>',
        'cafes' => '<path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"/>',
        'grocery' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/>',
        'restaurants' => '<path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8M10 12h4"/>',
        'shops' => '<path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/>',
        'transportation' => '<path d="M8 6v6M16 6v6M2 12h20M7 19h10M4 19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v13z"/>',
        default => '<circle cx="12" cy="12" r="10"/><path d="m12 8 4 4-4 4M8 12h8"/>',
    };
@endphp

@if($href)
    <a href="{{ $href }}" 
       class="group flex flex-col p-5 rounded-2xl border transition-all duration-200 {{ $active ? 'bg-[#9BE96B] border-[#082D00] text-[#082D00] shadow-md scale-[1.02]' : 'bg-white border-[#E5E7E1] hover:border-[#082D00]/30 hover:bg-[#F7F7F2]/50 text-[#082D00]' }}">
        <div class="flex items-center justify-between mb-3">
            <span class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors {{ $active ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    {!! $svgIcon !!}
                </svg>
            </span>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $active ? 'bg-black/10 text-[#082D00]' : 'bg-[#F7F7F2] text-[#64705D]' }}">
                {{ $category['count'] }}
            </span>
        </div>
        <h4 class="font-extrabold text-base tracking-tight mb-1">{{ $category['name'] }}</h4>
        @if(!empty($category['description']))
            <p class="text-xs font-medium text-[#64705D] line-clamp-2 leading-relaxed">{{ $category['description'] }}</p>
        @endif
    </a>
@else
    <div {{ $attributes->merge(['class' => 'cursor-pointer group flex flex-col p-5 rounded-2xl border transition-all duration-200 ' . ($active ? 'bg-[#9BE96B] border-[#082D00] text-[#082D00] shadow-md scale-[1.02]' : 'bg-white border-[#E5E7E1] hover:border-[#082D00]/30 hover:bg-[#F7F7F2]/50 text-[#082D00]')]) }}>
        <div class="flex items-center justify-between mb-3">
            <span class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors {{ $active ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    {!! $svgIcon !!}
                </svg>
            </span>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $active ? 'bg-black/10 text-[#082D00]' : 'bg-[#F7F7F2] text-[#64705D]' }}">
                {{ $category['count'] }}
            </span>
        </div>
        <h4 class="font-extrabold text-base tracking-tight mb-1">{{ $category['name'] }}</h4>
        @if(!empty($category['description']))
            <p class="text-xs font-medium text-[#64705D] line-clamp-2 leading-relaxed">{{ $category['description'] }}</p>
        @endif
    </div>
@endif
