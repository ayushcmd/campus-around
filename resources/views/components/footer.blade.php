<footer class="bg-[#082D00] text-white pt-16 pb-12 border-t border-[#164606]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-10 pb-12 border-b border-[#164606]/80">
            
            <!-- Col 1: Brand & Tagline -->
            <div class="md:col-span-2 space-y-4">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-[#9BE96B] text-[#082D00] flex items-center justify-center font-black text-xl shadow-sm">
                        <svg class="w-6 h-6 stroke-current" viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="m14.5 9.5-5 5"/>
                            <path d="M12 3v3"/>
                            <path d="M12 18v3"/>
                            <path d="M3 12h3"/>
                            <path d="M18 12h3"/>
                        </svg>
                    </span>
                    <span class="font-extrabold text-2xl tracking-tight text-white">CampusAround</span>
                </a>
                <p class="text-white/70 text-base max-w-sm font-medium leading-relaxed">
                    Everything around your campus, discovered by students. Discover facilities, food stalls, cafes, and essentials across IIT Patna & Bihta.
                </p>
                <div class="pt-2">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#164606] text-[#9BE96B] text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-[#9BE96B] animate-pulse"></span>
                        IIT Patna Campus Edition (Foundation Phase)
                    </span>
                </div>
            </div>

            <!-- Col 2: Discovery -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#9BE96B] mb-4">Discovery</h3>
                <ul class="space-y-3 text-sm font-medium text-white/80">
                    <li><a href="{{ route('inside') }}" class="hover:text-[#9BE96B] transition-colors">Inside Tour (Campus)</a></li>
                    <li><a href="{{ route('outside') }}" class="hover:text-[#9BE96B] transition-colors">Outside Tour (Bihta)</a></li>
                    <li><a href="{{ route('locate') }}" class="hover:text-[#9BE96B] transition-colors">Locate & Directions</a></li>
                    <li><a href="{{ route('contribute') }}" class="hover:text-[#9BE96B] transition-colors">Contribute Spot</a></li>
                    <li><a href="{{ route('explore') }}" class="hover:text-[#9BE96B] transition-colors">Directory Index</a></li>
                </ul>
            </div>

            <!-- Col 3: Community & About -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#9BE96B] mb-4">Community</h3>
                <ul class="space-y-3 text-sm font-medium text-white/80">
                    <li><a href="{{ route('contribute') }}" class="hover:text-[#9BE96B] transition-colors">Contribute a Place</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-[#9BE96B] transition-colors">About the Project</a></li>
                    <li><a href="{{ route('about') }}#guidelines" class="hover:text-[#9BE96B] transition-colors">Community Guidelines</a></li>
                    <li><a href="{{ route('about') }}#roadmap" class="hover:text-[#9BE96B] transition-colors">Roadmap & Supabase</a></li>
                </ul>
            </div>

            <!-- Col 4: Feedback / Contact -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#9BE96B] mb-4">Feedback</h3>
                <p class="text-xs text-white/60 mb-3 leading-relaxed">
                    Have thoughts or suggestions for the IIT Patna edition?
                </p>
                <a href="mailto:feedback@campusaround.iitp.local" class="inline-flex items-center gap-2 text-sm text-[#9BE96B] font-semibold hover:underline">
                    <span>Contact CampusAround</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

        </div>

        <!-- Bottom bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/50">
            <p>© {{ date('Y') }} CampusAround. Built for IIT Patna student community. Open-source educational project.</p>
            <div class="flex items-center gap-6">
                <span class="text-white/40">Demo Data Only</span>
                <a href="{{ route('about') }}" class="hover:text-white transition-colors">Design System</a>
                <a href="#main-content" class="hover:text-white transition-colors">Back to top ↑</a>
            </div>
        </div>
    </div>
</footer>
