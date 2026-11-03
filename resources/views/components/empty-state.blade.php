@props([
    'title' => 'No places found',
    'message' => 'Try adjusting your search terms or clearing filters to discover places around campus.',
    'resetUrl' => route('explore'),
    'resetText' => 'Clear all filters',
])

<div class="w-full bg-white rounded-3xl border border-[#E5E7E1] p-10 md:p-16 text-center max-w-xl mx-auto shadow-sm">
    <div class="w-16 h-16 rounded-2xl bg-[#9BE96B]/20 text-[#082D00] flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.3-4.3"/>
            <path d="M8 11h6"/>
        </svg>
    </div>
    <h3 class="text-2xl font-extrabold text-[#082D00] tracking-tight mb-2">{{ $title }}</h3>
    <p class="text-[#64705D] text-base leading-relaxed mb-6">{{ $message }}</p>
    
    @if($resetUrl)
        <a href="{{ $resetUrl }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-[#082D00] text-white font-bold text-sm hover:bg-[#134504] transition-colors shadow-sm">
            {{ $resetText }}
        </a>
    @endif
</div>
