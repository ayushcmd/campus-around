@props([
    'variant' => 'primary', // 'primary', 'lime', 'secondary', 'ghost'
    'size' => 'md',        // 'sm', 'md', 'lg'
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = "inline-flex items-center justify-center font-bold tracking-tight rounded-full transition-all duration-150 active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#082D00]";

    $variantClasses = match($variant) {
        'lime' => 'bg-[#9BE96B] text-[#082D00] hover:bg-[#8AE157] shadow-sm',
        'secondary' => 'bg-white text-[#082D00] border border-[#E5E7E1] hover:border-[#082D00]/40 hover:bg-neutral-50 shadow-sm',
        'ghost' => 'bg-transparent text-[#082D00] hover:bg-black/5',
        default => 'bg-[#082D00] text-white hover:bg-[#134504] shadow-sm',
    };

    $sizeClasses = match($size) {
        'sm' => 'px-4 py-2 text-xs gap-1.5',
        'lg' => 'px-7 py-3.5 text-base gap-2.5',
        default => 'px-5 py-2.5 text-sm gap-2',
    };

    $classes = "{$baseClasses} {$variantClasses} {$sizeClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
