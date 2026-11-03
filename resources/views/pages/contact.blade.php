<x-app-layout :nav-theme="'light'">
    <x-slot name="title">Contact & Feedback — CampusAround (IIT Patna)</x-slot>

    <!-- Pre-load all places in JSON script tag for place selection -->
    <script id="all-places-data" type="application/json">
        {!! json_encode($allPlaces, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <script>
        function contactApp(config) {
            let parsedPlaces = [];
            try {
                const dataEl = document.getElementById('all-places-data');
                if (dataEl && dataEl.textContent) {
                    parsedPlaces = JSON.parse(dataEl.textContent);
                }
            } catch (err) {
                console.error('Error parsing places data:', err);
            }

            const initialType = (config && config.initialType) || 'feedback';

            return {
                places: parsedPlaces,
                activeTab: initialType, // 'feedback', 'report'
                submittedNotice: false,
                submittedMessage: '',
                form: {
                    name: '',
                    email: '',
                    place_slug: (config && config.selectedSlug) || '',
                    issue_type: 'incorrect_hours', // 'incorrect_name', 'incorrect_address', 'incorrect_menu', 'incorrect_phone', 'incorrect_hours', 'incorrect_image', 'closed'
                    message: '',
                },

                submitFeedback() {
                    if (!this.form.message) {
                        alert('Please enter your feedback or comments.');
                        return;
                    }
                    this.submittedMessage = 'Thank you for your feedback! Your notes have been submitted to the IIT Patna CampusAround student team.';
                    this.submittedNotice = true;
                    this.form.message = '';
                    window.scrollTo({ top: 120, behavior: 'smooth' });
                },

                submitReport() {
                    if (!this.form.place_slug || !this.form.message) {
                        alert('Please select the affected spot and describe the discrepancy.');
                        return;
                    }
                    this.submittedMessage = 'Correction report logged for student moderation review. We appreciate your vigilance in keeping our directory accurate!';
                    this.submittedNotice = true;
                    this.form.message = '';
                    window.scrollTo({ top: 120, behavior: 'smooth' });
                }
            };
        }
    </script>

    <div x-data="contactApp({ 
            initialType: '{{ $initialType ?? 'feedback' }}',
            selectedSlug: '{{ $selectedPlace['slug'] ?? '' }}' 
         })" 
         class="py-8 sm:py-14">
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8 text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#9BE96B] text-[#082D00] text-xs font-black uppercase tracking-wider mb-3 shadow-2xs">
                    <span>Community Communication</span>
                </div>
                <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-none">
                    CONTACT &amp; FEEDBACK
                </h1>
                <p class="mt-3 text-base sm:text-lg text-[#64705D] font-medium leading-relaxed">
                    Have thoughts, suggestions, or spotted an inaccurate detail around IIT Patna? Let us know so the student community gets verified facts.
                </p>
            </div>

            <!-- Tab Switcher (Feedback vs Report) -->
            <div class="flex items-center justify-center gap-3 mb-8">
                <button type="button"
                        @click="activeTab = 'feedback'"
                        :class="activeTab === 'feedback' ? 'bg-[#082D00] text-white shadow-xs' : 'bg-white text-[#64705D] border border-[#E5E7E1] hover:border-[#082D00]/30'"
                        class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-wider transition-all">
                    Send General Feedback
                </button>
                <button type="button"
                        @click="activeTab = 'report'"
                        :class="activeTab === 'report' ? 'bg-[#082D00] text-white shadow-xs' : 'bg-white text-[#64705D] border border-[#E5E7E1] hover:border-[#082D00]/30'"
                        class="px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-wider transition-all">
                    Report Incorrect Information
                </button>
            </div>

            <!-- Submission Confirmation Banner -->
            <div x-show="submittedNotice" x-cloak class="mb-8 p-5 rounded-2xl bg-emerald-50 border border-emerald-300 flex items-start gap-3.5">
                <span class="w-8 h-8 rounded-xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center shrink-0 mt-0.5 font-bold text-base">
                    ✓
                </span>
                <div class="text-xs sm:text-sm text-emerald-950 leading-relaxed">
                    <h4 class="font-black text-[#082D00] uppercase">Notice Received</h4>
                    <p class="mt-1" x-text="submittedMessage"></p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-[#E5E7E1] shadow-sm mb-10">
                
                <!-- TAB 1: GENERAL FEEDBACK -->
                <div x-show="activeTab === 'feedback'">
                    <div class="mb-6 pb-4 border-b border-[#E5E7E1]">
                        <h2 class="text-xl font-black uppercase tracking-tight text-[#082D00]">
                            Share Your Thoughts on CampusAround
                        </h2>
                        <p class="text-xs text-[#64705D] mt-1">
                            What features would make discovering IIT Patna and Bihta easier for you?
                        </p>
                    </div>

                    <form @submit.prevent="submitFeedback()" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="feedback_name" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Your Name / Roll No (Optional)
                                </label>
                                <input type="text"
                                       id="feedback_name"
                                       x-model="form.name"
                                       placeholder="e.g. Student or visitor name"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                            </div>

                            <div>
                                <label for="feedback_email" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Email (Optional, for replies)
                                </label>
                                <input type="email"
                                       id="feedback_email"
                                       x-model="form.email"
                                       placeholder="e.g. your_id@iitp.ac.in"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="feedback_message" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                Suggestions & Feedback <span class="text-red-500">*</span>
                            </label>
                            <textarea id="feedback_message"
                                      rows="4"
                                      required
                                      x-model="form.message"
                                      placeholder="Share your experience using the site, ideas for hostel discovery, transit additions, or UI feedback..."
                                      class="w-full bg-[#F7F7F2] text-[#082D00] font-medium rounded-2xl p-4 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm"></textarea>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <x-button variant="primary" size="lg" type="submit">
                                <span>Send Feedback</span>
                                <svg class="w-4 h-4 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </x-button>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: REPORT INCORRECT INFORMATION -->
                <div x-show="activeTab === 'report'">
                    <div class="mb-6 pb-4 border-b border-[#E5E7E1]">
                        <h2 class="text-xl font-black uppercase tracking-tight text-[#082D00]">
                            Report an Inaccurate Place or Detail
                        </h2>
                        <p class="text-xs text-[#64705D] mt-1">
                            Help us keep every shop, facility, and bus stand accurate. Tell us which place needs fixing.
                        </p>
                    </div>

                    <form @submit.prevent="submitReport()" class="space-y-6">
                        <!-- Spot Selection -->
                        <div>
                            <label for="report_place" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                Select Spot with Incorrect Details <span class="text-red-500">*</span>
                            </label>
                            <select id="report_place"
                                    x-model="form.place_slug"
                                    required
                                    class="w-full bg-[#F7F7F2] text-[#082D00] font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                                <option value="" disabled>-- Choose a place from the directory --</option>
                                <optgroup label="📍 Outside Spots (Bihta)">
                                    <template x-for="p in places.filter(pl => !pl.is_campus)" :key="p.slug">
                                        <option :value="p.slug" x-text="p.name + ' (' + p.category + ')'"></option>
                                    </template>
                                </optgroup>
                                <optgroup label="🏛️ Inside Campus Facilities">
                                    <template x-for="p in places.filter(pl => pl.is_campus)" :key="p.slug">
                                        <option :value="p.slug" x-text="p.name + ' (' + p.category + ')'"></option>
                                    </template>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Discrepancy Type -->
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                Nature of Discrepancy <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <label class="p-3 rounded-2xl border text-xs font-semibold flex items-center gap-2 cursor-pointer transition-all"
                                       :class="form.issue_type === 'incorrect_hours' ? 'bg-[#082D00] text-white border-[#082D00]' : 'bg-[#F7F7F2] text-[#082D00] border-[#E5E7E1]'">
                                    <input type="radio" value="incorrect_hours" x-model="form.issue_type" class="sr-only">
                                    <span>⏰ Incorrect Hours</span>
                                </label>
                                <label class="p-3 rounded-2xl border text-xs font-semibold flex items-center gap-2 cursor-pointer transition-all"
                                       :class="form.issue_type === 'incorrect_menu' ? 'bg-[#082D00] text-white border-[#082D00]' : 'bg-[#F7F7F2] text-[#082D00] border-[#E5E7E1]'">
                                    <input type="radio" value="incorrect_menu" x-model="form.issue_type" class="sr-only">
                                    <span>🍽️ Incorrect Menu/Price</span>
                                </label>
                                <label class="p-3 rounded-2xl border text-xs font-semibold flex items-center gap-2 cursor-pointer transition-all"
                                       :class="form.issue_type === 'incorrect_phone' ? 'bg-[#082D00] text-white border-[#082D00]' : 'bg-[#F7F7F2] text-[#082D00] border-[#E5E7E1]'">
                                    <input type="radio" value="incorrect_phone" x-model="form.issue_type" class="sr-only">
                                    <span>📞 Inactive Phone</span>
                                </label>
                                <label class="p-3 rounded-2xl border text-xs font-semibold flex items-center gap-2 cursor-pointer transition-all"
                                       :class="form.issue_type === 'incorrect_address' ? 'bg-[#082D00] text-white border-[#082D00]' : 'bg-[#F7F7F2] text-[#082D00] border-[#E5E7E1]'">
                                    <input type="radio" value="incorrect_address" x-model="form.issue_type" class="sr-only">
                                    <span>📍 Wrong Location/GPS</span>
                                </label>
                                <label class="p-3 rounded-2xl border text-xs font-semibold flex items-center gap-2 cursor-pointer transition-all"
                                       :class="form.issue_type === 'incorrect_image' ? 'bg-[#082D00] text-white border-[#082D00]' : 'bg-[#F7F7F2] text-[#082D00] border-[#E5E7E1]'">
                                    <input type="radio" value="incorrect_image" x-model="form.issue_type" class="sr-only">
                                    <span>📸 Unrelated Image</span>
                                </label>
                                <label class="p-3 rounded-2xl border text-xs font-semibold flex items-center gap-2 cursor-pointer transition-all"
                                       :class="form.issue_type === 'closed' ? 'bg-[#082D00] text-white border-[#082D00]' : 'bg-[#F7F7F2] text-[#082D00] border-[#E5E7E1]'">
                                    <input type="radio" value="closed" x-model="form.issue_type" class="sr-only">
                                    <span>🚫 Permanently Closed</span>
                                </label>
                            </div>
                        </div>

                        <!-- Correction Description -->
                        <div>
                            <label for="report_message" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                Details of Correction <span class="text-red-500">*</span>
                            </label>
                            <textarea id="report_message"
                                      rows="4"
                                      required
                                      x-model="form.message"
                                      placeholder="State what is incorrect and provide the accurate information (e.g. 'Opening time is actually 11 AM instead of 10 AM, and Litti Chokha is now ₹80')..."
                                      class="w-full bg-[#F7F7F2] text-[#082D00] font-medium rounded-2xl p-4 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm"></textarea>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <x-button variant="primary" size="lg" type="submit">
                                <span>Submit Correction</span>
                                <svg class="w-4 h-4 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </x-button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- General Contact Info Block -->
            <div class="p-6 sm:p-8 rounded-3xl bg-[#082D00] text-white space-y-4">
                <div class="flex items-center gap-2 text-xs font-bold text-[#9BE96B] uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-[#9BE96B]"></span>
                    Direct Project Contact
                </div>
                <h3 class="text-xl sm:text-2xl font-black uppercase tracking-tight">
                    CampusAround IIT Patna Project Team
                </h3>
                <p class="text-xs sm:text-sm text-white/80 leading-relaxed max-w-xl">
                    CampusAround is an open-source student-driven initiative for the Indian Institute of Technology Patna (Bihta). We welcome suggestions from students, faculty, campus staff, and local vendors.
                </p>
                <div class="pt-3 flex flex-wrap items-center gap-4 text-xs">
                    <a href="mailto:feedback@campusaround.iitp.local" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-[#9BE96B] font-bold border border-white/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>feedback@campusaround.iitp.local</span>
                    </a>
                    <span class="text-white/50">Response within 24–48 hours</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
