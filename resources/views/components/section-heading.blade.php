@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'actionText' => null,
    'actionHref' => null,
    'align' => 'left', // 'left' or 'center'
])

<div class="mb-8 md:mb-12 {{ $align === 'center' ? 'text-center max-w-3xl mx-auto' : 'flex flex-col md:flex-row md:items-end md:justify-between gap-4' }}">
    <div class="{{ $align === 'center' ? '' : 'max-w-2xl' }}">
        @if($eyebrow)
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#9BE96B]/30 text-[#082D00] text-xs font-bold uppercase tracking-wider mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-[#082D00]"></span>
                {{ $eyebrow }}
            </div>
        @endif

        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-[#082D00] leading-[1.08] uppercase">
            {{ $title }}
        </h2>

        @if($description)
            <p class="mt-3 text-base sm:text-lg text-[#64705D] font-medium leading-relaxed">
                {{ $description }}
            </p>
        @endif
    </div>

    @if($actionText && $actionHref)
        <div class="shrink-0 pt-2 md:pt-0">
            <a href="{{ $actionHref }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#082D00] hover:text-[#134504] group transition-colors">
                <span>{{ $actionText }}</span>
                <span class="w-7 h-7 rounded-full bg-white border border-[#E5E7E1] flex items-center justify-center group-hover:bg-[#9BE96B] group-hover:border-[#9BE96B] transition-all">
                    <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </span>
            </a>
        </div>
    @endif
</div>
