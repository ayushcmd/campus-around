@props([
    'theme' => 'light' // 'hero' (on lime background) or 'light' (white/warm background)
])

@php
    $isHero = $theme === 'hero';
    $navBg = $isHero ? 'bg-[#9BE96B]' : 'bg-[#F7F7F2]/95 backdrop-blur-md border-b border-[#E5E7E1]';
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

<div x-data="{ navDrawerOpen: false }"
     @keydown.window.escape="navDrawerOpen = false">
    
    <!-- Top Navbar Bar -->
    <header class="sticky top-0 z-30 w-full transition-colors duration-200 {{ $navBg }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Left: Menu Drawer Toggle + Logo -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Hamburger / Drawer Toggle Button (Left Navigation Trigger) -->
                    <button type="button" 
                            @click="navDrawerOpen = true"
                            class="inline-flex items-center gap-2 p-2.5 rounded-2xl bg-white/80 border border-[#E5E7E1] text-[#082D00] hover:bg-white hover:border-[#082D00]/30 active:scale-95 transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-[#082D00]"
                            aria-label="Open Left Navigation Drawer"
                            :aria-expanded="navDrawerOpen.toString()">
                        <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" x2="21" y1="6" y2="6"/>
                            <line x1="3" x2="21" y1="12" y2="12"/>
                            <line x1="3" x2="21" y1="18" y2="18"/>
                        </svg>
                        <span class="hidden sm:inline text-xs font-black uppercase tracking-wider text-[#082D00]">Menu</span>
                    </button>

                    <!-- CampusAround Logo -->
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

                <!-- Center/Right: Quick 4 Discovery Links on Desktop -->
                <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                    <a href="{{ route('inside') }}" 
                       class="px-3.5 py-2 rounded-full text-xs font-bold transition-all {{ $currentRoute === 'inside' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#082D00] hover:bg-black/5' }}">
                        Inside Tour
                    </a>
                    <a href="{{ route('outside') }}" 
                       class="px-3.5 py-2 rounded-full text-xs font-bold transition-all {{ $currentRoute === 'outside' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#082D00] hover:bg-black/5' }}">
                        Outside Tour
                    </a>
                    <a href="{{ route('locate') }}" 
                       class="px-3.5 py-2 rounded-full text-xs font-bold transition-all {{ $currentRoute === 'locate' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#082D00] hover:bg-black/5' }}">
                        Locate
                    </a>
                    <a href="{{ route('contribute') }}" 
                       class="px-3.5 py-2 rounded-full text-xs font-bold transition-all {{ $currentRoute === 'contribute' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#082D00] hover:bg-black/5' }}">
                        Contribute
                    </a>
                </nav>

                <!-- Far Right Action -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('locate') }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-extrabold bg-[#082D00] text-[#9BE96B] hover:bg-[#134504] active:scale-95 transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                        </svg>
                        <span>Navigate</span>
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- LEFT-SIDE NAVIGATION DRAWER (Slide-in from the left on desktop and mobile) -->
    <!-- Backdrop Overlay -->
    <div x-show="navDrawerOpen"
         x-cloak
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="navDrawerOpen = false"
         class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs"
         aria-hidden="true"></div>

    <!-- Left Drawer Panel -->
    <aside x-show="navDrawerOpen"
           x-cloak
           x-transition:enter="transition ease-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 z-50 w-[85vw] sm:w-80 max-w-sm bg-white border-r border-[#E5E7E1] shadow-2xl flex flex-col justify-between overflow-y-auto"
           aria-label="CampusAround Main Navigation">

        <div>
            <!-- Drawer Header: Brand + Close Button -->
            <div class="p-6 border-b border-[#E5E7E1] flex items-center justify-between bg-[#F7F7F2]">
                <a href="{{ route('home') }}" @click="navDrawerOpen = false" class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center font-black text-lg shadow-xs">
                        <svg class="w-5 h-5 stroke-current" viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m14.5 9.5-5 5"/>
                            <path d="M12 3v3"/>
                            <path d="M12 18v3"/>
                            <path d="M3 12h3"/>
                            <path d="M18 12h3"/>
                        </svg>
                    </span>
                    <div>
                        <span class="font-black text-lg text-[#082D00] tracking-tight block leading-tight">CampusAround</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#64705D]">IIT Patna Edition</span>
                    </div>
                </a>

                <!-- Close Drawer Button -->
                <button type="button" 
                        @click="navDrawerOpen = false"
                        class="p-2 rounded-xl text-[#64705D] hover:text-[#082D00] hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-[#082D00]"
                        aria-label="Close navigation panel">
                    <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Drawer Section: The 4 Primary Discovery Options -->
            <div class="p-6 space-y-2">
                <div class="text-[11px] font-black uppercase tracking-widest text-[#64705D] px-2 mb-3">
                    Core Discovery Tours
                </div>

                <!-- 1. Inside Tour -->
                <a href="{{ route('inside') }}" 
                   @click="navDrawerOpen = false"
                   class="group flex items-start gap-4 p-3.5 rounded-2xl transition-all duration-150 {{ $currentRoute === 'inside' ? 'bg-[#9BE96B] text-[#082D00] font-black shadow-xs' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                    <span class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $currentRoute === 'inside' ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                        <!-- Lucide Building / Landmark SVG -->
                        <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                            <path d="M9 22v-4h6v4"/>
                            <path d="M8 6h.01"/>
                            <path d="M16 6h.01"/>
                            <path d="M8 10h.01"/>
                            <path d="M16 10h.01"/>
                            <path d="M8 14h.01"/>
                            <path d="M16 14h.01"/>
                        </svg>
                    </span>
                    <div>
                        <span class="font-extrabold text-base tracking-tight block leading-snug">Inside Tour</span>
                        <span class="text-xs text-[#64705D] block mt-0.5 font-medium leading-tight">Explore real academic blocks, library, sports & hostels</span>
                    </div>
                </a>

                <!-- 2. Outside Tour -->
                <a href="{{ route('outside') }}" 
                   @click="navDrawerOpen = false"
                   class="group flex items-start gap-4 p-3.5 rounded-2xl transition-all duration-150 {{ $currentRoute === 'outside' ? 'bg-[#9BE96B] text-[#082D00] font-black shadow-xs' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                    <span class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $currentRoute === 'outside' ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                        <!-- Lucide Store / Coffee SVG -->
                        <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                            <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
                            <path d="M2 7h20"/>
                        </svg>
                    </span>
                    <div>
                        <span class="font-extrabold text-base tracking-tight block leading-snug">Outside Tour</span>
                        <span class="text-xs text-[#64705D] block mt-0.5 font-medium leading-tight">Nearby dhabas, cafes, grocery & local Bihta hubs</span>
                    </div>
                </a>

                <!-- 3. Locate -->
                <a href="{{ route('locate') }}" 
                   @click="navDrawerOpen = false"
                   class="group flex items-start gap-4 p-3.5 rounded-2xl transition-all duration-150 {{ $currentRoute === 'locate' ? 'bg-[#9BE96B] text-[#082D00] font-black shadow-xs' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                    <span class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $currentRoute === 'locate' ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                        <!-- Lucide Navigation SVG -->
                        <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                        </svg>
                    </span>
                    <div>
                        <span class="font-extrabold text-base tracking-tight block leading-snug">Locate</span>
                        <span class="text-xs text-[#64705D] block mt-0.5 font-medium leading-tight">Apple-inspired live navigation & route directions</span>
                    </div>
                </a>

                <!-- 4. Contribute -->
                <a href="{{ route('contribute') }}" 
                   @click="navDrawerOpen = false"
                   class="group flex items-start gap-4 p-3.5 rounded-2xl transition-all duration-150 {{ $currentRoute === 'contribute' ? 'bg-[#9BE96B] text-[#082D00] font-black shadow-xs' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                    <span class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $currentRoute === 'contribute' ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                        <!-- Lucide Plus Circle SVG -->
                        <svg class="w-5 h-5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M8 12h8"/>
                            <path d="M12 8v8"/>
                        </svg>
                    </span>
                    <div>
                        <span class="font-extrabold text-base tracking-tight block leading-snug">Contribute</span>
                        <span class="text-xs text-[#64705D] block mt-0.5 font-medium leading-tight">Share missing student places and campus spots</span>
                    </div>
                </a>
            </div>

            <!-- Secondary Links -->
            <div class="px-6 py-4 border-t border-[#E5E7E1] space-y-1">
                <div class="text-[11px] font-black uppercase tracking-widest text-[#64705D] px-2 mb-2">
                    Browse & Info
                </div>
                <a href="{{ route('explore') }}" 
                   @click="navDrawerOpen = false"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl font-bold text-sm text-[#082D00] hover:bg-[#F7F7F2] transition-colors">
                    <span>Directory Index</span>
                    <span class="text-xs text-[#64705D] font-normal">All 22 Places</span>
                </a>
                <a href="{{ route('about') }}" 
                   @click="navDrawerOpen = false"
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl font-bold text-sm text-[#082D00] hover:bg-[#F7F7F2] transition-colors">
                    <span>About CampusAround</span>
                    <span class="text-xs text-[#64705D] font-normal">Our Story</span>
                </a>
            </div>
        </div>

        <!-- Drawer Footer: Campus Info -->
        <div class="p-6 border-t border-[#E5E7E1] bg-[#F7F7F2]">
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#9BE96B]"></span>
                <span class="text-xs font-bold text-[#082D00]">IIT Patna (Bihta, Bihar)</span>
            </div>
            <p class="text-[11px] text-[#64705D] leading-relaxed">
                Student-first discovery with verified locations and real routing.
            </p>
        </div>

    </aside>
</div>
