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
                
                <!-- Left: Explore Drawer Toggle Button + CampusAround Logo -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Explore Button (Replaces MENU, opens Left-Side Navigation Drawer) -->
                    <button type="button" 
                            @click="navDrawerOpen = true"
                            class="inline-flex items-center gap-2 px-3 sm:px-3.5 py-2 rounded-full bg-white text-[#082D00] border border-[#082D00]/15 hover:border-[#082D00] hover:bg-neutral-50 active:scale-95 transition-all shadow-xs focus:outline-none focus:ring-2 focus:ring-[#082D00]"
                            aria-label="Open Explore Navigation Drawer"
                            :aria-expanded="navDrawerOpen.toString()">
                        <!-- Lucide Menu Hamburger Icon -->
                        <svg class="w-4 h-4 stroke-current shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" x2="21" y1="6" y2="6"/>
                            <line x1="3" x2="21" y1="12" y2="12"/>
                            <line x1="3" x2="21" y1="18" y2="18"/>
                        </svg>
                        <span class="text-xs font-black uppercase tracking-wider text-[#082D00]">Explore</span>
                    </button>

                    <!-- CampusAround Logo with IIT Patna Subtitle -->
                    <a href="{{ route('home') }}" class="group flex items-center gap-2.5 sm:gap-3 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#082D00] rounded-xl p-0.5">
                        <img src="{{ asset('CampusAround-logo.png') }}" 
                             alt="CampusAround Logo" 
                             class="w-9 h-9 sm:w-11 sm:h-11 object-contain shrink-0 transition-transform duration-200 group-hover:scale-105">
                        <div class="flex flex-col">
                            <span class="font-black text-lg sm:text-xl tracking-tight text-[#082D00] leading-none">CampusAround</span>
                            <span class="text-[10px] uppercase tracking-wider font-extrabold text-[#64705D] mt-0.5">IIT Patna</span>
                        </div>
                    </a>
                </div>

                <!-- Center: Clean, spacious, NO duplicate discovery links! -->

                <!-- Right: Navigate Button -->
                <div class="flex items-center">
                    @if(request()->routeIs('locate'))
                        <a href="#locate-controls" 
                           onclick="document.getElementById('locate-controls')?.scrollIntoView({ behavior: 'smooth' }); return false;"
                           class="inline-flex items-center gap-1.5 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-xs sm:text-sm font-extrabold bg-[#082D00] text-[#9BE96B] hover:bg-[#134504] active:scale-95 transition-all shadow-sm">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                            </svg>
                            <span>Navigate</span>
                        </a>
                    @else
                        <a href="{{ route('locate') }}" 
                           class="inline-flex items-center gap-1.5 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-xs sm:text-sm font-extrabold bg-[#082D00] text-[#9BE96B] hover:bg-[#134504] active:scale-95 transition-all shadow-sm">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                            </svg>
                            <span>Navigate</span>
                        </a>
                    @endif
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
                    <img src="{{ asset('CampusAround-logo.png') }}" 
                         alt="CampusAround Logo" 
                         class="w-10 h-10 object-contain shrink-0">
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

            <!-- Drawer Navigation Sections -->
            <div class="p-5 space-y-6">
                
                <!-- Section 1: Discovery -->
                <div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-[#64705D] px-2 mb-2">
                        Discovery
                    </div>
                    <div class="space-y-1">
                        <!-- Inside Tour -->
                        <a href="{{ route('inside') }}" 
                           @click="navDrawerOpen = false"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ $currentRoute === 'inside' ? 'bg-[#9BE96B] text-[#082D00] font-black shadow-2xs' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ $currentRoute === 'inside' ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                                    <path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/>
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm block leading-tight">Inside Tour</span>
                                <span class="text-[11px] text-[#64705D] block leading-tight">Campus facilities & academic zones</span>
                            </div>
                        </a>

                        <!-- Outside Tour -->
                        <a href="{{ route('outside') }}" 
                           @click="navDrawerOpen = false"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ $currentRoute === 'outside' ? 'bg-[#9BE96B] text-[#082D00] font-black shadow-2xs' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ $currentRoute === 'outside' ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm block leading-tight">Outside Tour</span>
                                <span class="text-[11px] text-[#64705D] block leading-tight">Nearby dhabas, cafes & transit</span>
                            </div>
                        </a>

                        <!-- Locate -->
                        <a href="{{ route('locate') }}" 
                           @click="navDrawerOpen = false; if ('{{ $currentRoute }}' === 'locate') { document.getElementById('locate-controls')?.scrollIntoView({ behavior: 'smooth' }); }"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ $currentRoute === 'locate' ? 'bg-[#9BE96B] text-[#082D00] font-black shadow-2xs' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ $currentRoute === 'locate' ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm block leading-tight">Locate & Route</span>
                                <span class="text-[11px] text-[#64705D] block leading-tight">Walking and transit directions</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Section 2: Community -->
                <div class="pt-4 border-t border-[#E5E7E1]">
                    <div class="text-[10px] font-black uppercase tracking-widest text-[#64705D] px-2 mb-2">
                        Community
                    </div>
                    <div class="space-y-1">
                        <!-- Contribute a Place -->
                        <a href="{{ route('contribute') }}" 
                           @click="navDrawerOpen = false"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ $currentRoute === 'contribute' ? 'bg-[#9BE96B] text-[#082D00] font-black shadow-2xs' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ $currentRoute === 'contribute' ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/>
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm block leading-tight">Contribute a Place</span>
                                <span class="text-[11px] text-[#64705D] block leading-tight">Suggest missing shops, photos & menus</span>
                            </div>
                        </a>

                        <!-- Community Guidelines -->
                        <a href="{{ route('about') }}#guidelines" 
                           @click="navDrawerOpen = false"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all text-[#082D00] hover:bg-[#F7F7F2]">
                            <span class="w-8 h-8 rounded-xl bg-[#F7F7F2] text-[#082D00] flex items-center justify-center shrink-0 group-hover:bg-[#9BE96B]">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm block leading-tight">Community Guidelines</span>
                                <span class="text-[11px] text-[#64705D] block leading-tight">Verification rules & student safety</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Section 3: Information -->
                <div class="pt-4 border-t border-[#E5E7E1]">
                    <div class="text-[10px] font-black uppercase tracking-widest text-[#64705D] px-2 mb-2">
                        Information
                    </div>
                    <div class="space-y-1">
                        <!-- About -->
                        <a href="{{ route('about') }}" 
                           @click="navDrawerOpen = false"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ $currentRoute === 'about' ? 'bg-[#9BE96B] text-[#082D00] font-black shadow-2xs' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ $currentRoute === 'about' ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm block leading-tight">About CampusAround</span>
                                <span class="text-[11px] text-[#64705D] block leading-tight">Our mission & IIT Patna edition</span>
                            </div>
                        </a>

                        <!-- Contact -->
                        <a href="{{ route('contact') }}" 
                           @click="navDrawerOpen = false"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all {{ $currentRoute === 'contact' ? 'bg-[#9BE96B] text-[#082D00] font-black shadow-2xs' : 'text-[#082D00] hover:bg-[#F7F7F2]' }}">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ $currentRoute === 'contact' ? 'bg-[#082D00] text-[#9BE96B]' : 'bg-[#F7F7F2] text-[#082D00] group-hover:bg-[#9BE96B]' }}">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm block leading-tight">Contact</span>
                                <span class="text-[11px] text-[#64705D] block leading-tight">Direct project email & assistance</span>
                            </div>
                        </a>

                        <!-- Feedback -->
                        <a href="{{ route('contact', ['type' => 'feedback']) }}" 
                           @click="navDrawerOpen = false"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-2xl transition-all text-[#082D00] hover:bg-[#F7F7F2]">
                            <span class="w-8 h-8 rounded-xl bg-[#F7F7F2] text-[#082D00] flex items-center justify-center shrink-0 group-hover:bg-[#9BE96B]">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                            </span>
                            <div>
                                <span class="font-bold text-sm block leading-tight">Send Feedback</span>
                                <span class="text-[11px] text-[#64705D] block leading-tight">Help improve the experience</span>
                            </div>
                        </a>
                    </div>
                </div>

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
