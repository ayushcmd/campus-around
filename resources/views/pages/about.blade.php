<x-app-layout :nav-theme="'light'">
    <x-slot name="title">About CampusAround — Student-First Campus Discovery</x-slot>

    <div class="py-12 sm:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <!-- Hero / Mission -->
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#9BE96B]/30 text-[#082D00] text-xs font-black uppercase tracking-wider">
                    <span>Our Purpose</span>
                </div>
                <h1 class="text-4xl sm:text-6xl font-black uppercase tracking-tight text-[#082D00] leading-none">
                    EVERYTHING AROUND YOUR CAMPUS, DISCOVERED BY STUDENTS.
                </h1>
                <p class="text-lg sm:text-xl text-[#64705D] font-medium leading-relaxed pt-2">
                    When you step onto a massive 500-acre residential campus like IIT Patna in Bihta, figuring out where to find good food, study spaces, daily groceries, or printing shops shouldn't take a whole semester of trial and error.
                </p>
            </div>

            <!-- The Problem & Solution Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-3xl p-8 border border-[#E5E7E1] shadow-sm space-y-4">
                    <span class="w-12 h-12 rounded-2xl bg-[#F7F7F2] text-[#082D00] flex items-center justify-center font-bold text-xl border border-[#E5E7E1]">
                        🔍
                    </span>
                    <h3 class="text-xl font-extrabold text-[#082D00] uppercase tracking-tight">The Bihta Campus Reality</h3>
                    <p class="text-sm text-[#64705D] leading-relaxed">
                        IIT Patna sits in Bihta, surrounded by vibrant local markets, street food stalls, and essentials that aren't properly mapped or listed on commercial food delivery apps. Crucial knowledge is usually passed down informally through seniors or lost in noisy WhatsApp groups.
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-[#E5E7E1] shadow-sm space-y-4">
                    <span class="w-12 h-12 rounded-2xl bg-[#9BE96B] text-[#082D00] flex items-center justify-center font-bold text-xl">
                        💡
                    </span>
                    <h3 class="text-xl font-extrabold text-[#082D00] uppercase tracking-tight">The CampusAround Solution</h3>
                    <p class="text-sm text-[#64705D] leading-relaxed">
                        A clean, student-curated directory combining campus facilities with nearby neighborhood spots. Clear directions from campus gates, typical operating hours, student-tested recommendations, and open spatial maps.
                    </p>
                </div>
            </div>

            <!-- Core Design Principles (Inspired by Wise) -->
            <div class="bg-[#082D00] rounded-3xl text-white p-8 sm:p-12 space-y-6">
                <span class="text-xs font-bold uppercase tracking-wider text-[#9BE96B]">Design Philosophy</span>
                <h2 class="text-2xl sm:text-4xl font-black uppercase tracking-tight text-white leading-tight">
                    NO CLUTTER. JUST HIGH CONTRAST, CLARITY, AND SPEED.
                </h2>
                <p class="text-white/80 text-base leading-relaxed">
                    Inspired by the modern minimalist design principles of Wise, CampusAround embraces high-contrast primary lime green (<code class="text-[#9BE96B]">#9BE96B</code>), dark forest green typography (<code class="text-[#9BE96B]">#082D00</code>), and warm neutral card surfaces. We prioritize clear typography and fast page loads over excessive animations or heavy dashboard bloat.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 border-t border-[#164606]">
                    <div>
                        <h4 class="text-sm font-black uppercase text-[#9BE96B]">1. Student First</h4>
                        <p class="text-xs text-white/70 mt-1">Direct directions relative to campus gates and hostel blocks.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase text-[#9BE96B]">2. Zero Junk Ads</h4>
                        <p class="text-xs text-white/70 mt-1">No fake reviews, no sponsored noise, no clutter.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase text-[#9BE96B]">3. Open & Modular</h4>
                        <p class="text-xs text-white/70 mt-1">Lightweight Laravel Blade foundation ready for Supabase backend.</p>
                    </div>
                </div>
            </div>

            <!-- Technical Roadmap -->
            <div class="space-y-6" id="roadmap">
                <h3 class="text-2xl font-black uppercase text-[#082D00]">Architecture & Next Steps</h3>
                <div class="border border-[#E5E7E1] rounded-3xl bg-white divide-y divide-[#E5E7E1]">
                    
                    <div class="p-6 sm:p-8 flex items-start gap-4">
                        <span class="w-8 h-8 rounded-full bg-[#9BE96B] text-[#082D00] font-black flex items-center justify-center shrink-0 text-sm">
                            ✓
                        </span>
                        <div>
                            <h4 class="font-bold text-base text-[#082D00]">Phase 1: Project Foundation & UI System (Current)</h4>
                            <p class="text-xs sm:text-sm text-[#64705D] mt-1">
                                Laravel 12 application scaffold, Blade components, Tailwind CSS v4 design system, Alpine.js interactions, OpenStreetMap Leaflet integration, and responsive viewports.
                            </p>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 flex items-start gap-4">
                        <span class="w-8 h-8 rounded-full bg-[#F7F7F2] text-[#082D00] font-black flex items-center justify-center shrink-0 text-sm border border-[#E5E7E1]">
                            2
                        </span>
                        <div>
                            <h4 class="font-bold text-base text-[#082D00]">Phase 2: Supabase PostgreSQL Persistence</h4>
                            <p class="text-xs sm:text-sm text-[#64705D] mt-1">
                                Schema migrations for places, tags, and student submissions. Supabase client integration for storage of community photos.
                            </p>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 flex items-start gap-4">
                        <span class="w-8 h-8 rounded-full bg-[#F7F7F2] text-[#082D00] font-black flex items-center justify-center shrink-0 text-sm border border-[#E5E7E1]">
                            3
                        </span>
                        <div>
                            <h4 class="font-bold text-base text-[#082D00]">Phase 3: Student Moderation & Reviews</h4>
                            <p class="text-xs sm:text-sm text-[#64705D] mt-1">
                                Lightweight authentication for campus community members to verify information, suggest hours corrections, and report closures.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Call to action -->
            <div class="text-center pt-8">
                <x-button variant="primary" size="lg" :href="route('explore')">
                    <span>Start Exploring IIT Patna</span>
                    <svg class="w-4 h-4 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </x-button>
            </div>

        </div>
    </div>
</x-app-layout>
