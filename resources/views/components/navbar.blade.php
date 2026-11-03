@props([
    'theme' => 'light' // 'hero' (on lime background) or 'light' (white/warm background)
])

@php
    $isHero = $theme === 'hero';
    $navBg = $isHero ? 'bg-[#9BE96B]' : 'bg-[#F7F7F2]/90 backdrop-blur-md border-b border-[#E5E7E1]';
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

<header x-data="{ mobileOpen: false, scrolled: false }"
        @keydown.window.escape="mobileOpen = false"
        class="sticky top-0 z-40 w-full transition-colors duration-200 {{ $navBg }}"
        :class="{ 'shadow-sm': scrolled && '{{ !$isHero }}' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Left: Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="group flex items-center gap-3 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#082D00] rounded-xl p-1">
                    <span class="w-10 h-10 rounded-xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center font-black text-xl shadow-sm transition-transform duration-200 group-hover:scale-105">
                        <svg class="w-6 h-6 stroke-current" viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m14.5 9.5-5 5"/>
                            <path d="M12 3v3"/>
                            <path d="M12 18v3"/>
                            <path d="M3 12h3"/>
                            <path d="M18 12h3"/>
                        </svg>
                    </span>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-xl tracking-tight text-[#082D00] leading-none">CampusAround</span>
                        <span class="text-[10px] uppercase tracking-wider font-semibold text-[#64705D] mt-0.5">IIT Patna</span>
                    </div>
                </a>
            </div>

            <!-- Center: Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                <a href="{{ route('explore') }}" 
                   class="px-4 py-2 rounded-full text-sm font-semibold transition-colors duration-150 {{ $currentRoute === 'explore' ? 'bg-[#082D00] text-white' : 'text-[#082D00] hover:bg-black/5' }}">
                    Explore
                </a>
                <a href="{{ route('explore', ['type' => 'campus']) }}" 
                   class="px-4 py-2 rounded-full text-sm font-semibold transition-colors duration-150 {{ request('type') === 'campus' ? 'bg-[#082D00] text-white' : 'text-[#082D00] hover:bg-black/5' }}">
                    Campus
                </a>
                <a href="{{ route('explore', ['type' => 'nearby']) }}" 
                   class="px-4 py-2 rounded-full text-sm font-semibold transition-colors duration-150 {{ request('type') === 'nearby' ? 'bg-[#082D00] text-white' : 'text-[#082D00] hover:bg-black/5' }}">
                    Nearby
                </a>
                <a href="{{ route('contribute') }}" 
                   class="px-4 py-2 rounded-full text-sm font-semibold transition-colors duration-150 {{ $currentRoute === 'contribute' ? 'bg-[#082D00] text-white' : 'text-[#082D00] hover:bg-black/5' }}">
                    Contribute
                </a>
            </nav>

            <!-- Right: Actions -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('about') }}" 
                   class="px-4 py-2 text-sm font-semibold text-[#082D00] hover:text-[#082D00]/75 transition-colors">
                    About
                </a>
                <a href="{{ route('explore') }}" 
                   class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-sm font-bold bg-[#082D00] text-white hover:bg-[#134504] active:scale-95 transition-all shadow-sm">
                    Get Started
                </a>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex md:hidden">
                <button type="button" 
                        @click="mobileOpen = !mobileOpen" 
                        class="p-2.5 rounded-xl text-[#082D00] hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-[#082D00]"
                        :aria-expanded="mobileOpen.toString()"
                        aria-label="Toggle navigation menu">
                    <svg x-show="!mobileOpen" class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" x2="20" y1="12" y2="12"/>
                        <line x1="4" x2="20" y1="6" y2="6"/>
                        <line x1="4" x2="20" y1="18" y2="18"/>
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Navigation Panel -->
    <div x-show="mobileOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         @click.away="mobileOpen = false"
         class="md:hidden border-b border-[#E5E7E1] bg-white px-4 pt-3 pb-6 shadow-xl">
        <div class="flex flex-col space-y-2">
            <a href="{{ route('explore') }}" 
               @click="mobileOpen = false"
               class="flex items-center justify-between px-4 py-3 rounded-xl font-bold text-base {{ $currentRoute === 'explore' && !request('type') ? 'bg-[#9BE96B] text-[#082D00]' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                <span>Explore Everything</span>
                <span class="text-xs bg-black/5 px-2 py-0.5 rounded-full font-medium">All</span>
            </a>
            <a href="{{ route('explore', ['type' => 'campus']) }}" 
               @click="mobileOpen = false"
               class="flex items-center justify-between px-4 py-3 rounded-xl font-bold text-base {{ request('type') === 'campus' ? 'bg-[#9BE96B] text-[#082D00]' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                <span>Campus Facilities</span>
                <span class="text-xs bg-black/5 px-2 py-0.5 rounded-full font-medium">IITP</span>
            </a>
            <a href="{{ route('explore', ['type' => 'nearby']) }}" 
               @click="mobileOpen = false"
               class="flex items-center justify-between px-4 py-3 rounded-xl font-bold text-base {{ request('type') === 'nearby' ? 'bg-[#9BE96B] text-[#082D00]' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                <span>Nearby & Bihta</span>
                <span class="text-xs bg-black/5 px-2 py-0.5 rounded-full font-medium">Outdoors</span>
            </a>
            <a href="{{ route('contribute') }}" 
               @click="mobileOpen = false"
               class="flex items-center justify-between px-4 py-3 rounded-xl font-bold text-base {{ $currentRoute === 'contribute' ? 'bg-[#9BE96B] text-[#082D00]' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                <span>Contribute a Place</span>
                <span class="text-xs bg-[#082D00] text-[#9BE96B] px-2 py-0.5 rounded-full font-semibold">+ Add</span>
            </a>
            <a href="{{ route('about') }}" 
               @click="mobileOpen = false"
               class="flex items-center justify-between px-4 py-3 rounded-xl font-bold text-base {{ $currentRoute === 'about' ? 'bg-[#9BE96B] text-[#082D00]' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                <span>About CampusAround</span>
                <span class="text-xs text-[#64705D]">Story</span>
            </a>

            <div class="pt-4 border-t border-[#E5E7E1] mt-2">
                <a href="{{ route('explore') }}" 
                   @click="mobileOpen = false"
                   class="w-full py-3.5 px-4 rounded-full bg-[#082D00] text-white text-center font-bold text-base block shadow-sm hover:bg-[#134504]">
                    Get Started Exploring
                </a>
            </div>
        </div>
    </div>
</header>
