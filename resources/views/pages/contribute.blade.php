<x-app-layout :nav-theme="'light'">
    <x-slot name="title">Contribute a Place — CampusAround (IIT Patna)</x-slot>

    <div x-data="contributeApp()" class="py-10 sm:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-10 text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#9BE96B]/30 text-[#082D00] text-xs font-bold uppercase tracking-wider mb-3">
                    <span>🌱 Community Contribution</span>
                </div>
                <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-tight">
                    ADD A SPOT TO CAMPUSAROUND.
                </h1>
                <p class="mt-3 text-base sm:text-lg text-[#64705D] font-medium leading-relaxed">
                    Know a great study nook, cheap stationery shop, or late-night chai stall? Share it to make life easier for everyone at IIT Patna.
                </p>
            </div>

            <!-- Informational Notice Banner -->
            <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-[#9BE96B]/20 border border-[#9BE96B] flex items-start gap-3.5">
                <span class="w-8 h-8 rounded-xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center shrink-0 mt-0.5 font-bold text-sm">
                    ℹ
                </span>
                <div class="text-xs sm:text-sm text-[#082D00] leading-relaxed">
                    <span class="font-extrabold">Foundation Preview Mode:</span> Submissions in this phase provide interactive client-side validation and workflow demonstration. Real-time PostgreSQL database persistence with moderation queues will be enabled in the Supabase rollout phase.
                </div>
            </div>

            <!-- Contribution Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-[#E5E7E1] shadow-sm">
                <form @submit.prevent="submitContribution()" class="space-y-8">
                    
                    <!-- Section 1: Basic Information -->
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tight text-[#082D00] pb-3 border-b border-[#E5E7E1] mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#082D00] text-white text-xs flex items-center justify-center font-bold">1</span>
                            Place Details
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Place Name -->
                            <div class="sm:col-span-2">
                                <label for="place_name" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Place Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="place_name"
                                       x-model="form.name"
                                       required
                                       placeholder="e.g. Bihta Hot Samosa & Tea Point"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select id="category_id"
                                        x-model="form.category_id"
                                        required
                                        class="w-full bg-[#F7F7F2] text-[#082D00] font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                                    <option value="" disabled>Select category</option>
                                    @foreach($categories as $cat)
                                        @if($cat['id'] !== 'all')
                                            <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- Campus Location Scope -->
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Location Type <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button"
                                            @click="form.is_campus = true"
                                            :class="form.is_campus ? 'bg-[#082D00] text-white' : 'bg-[#F7F7F2] text-[#082D00] hover:bg-black/5'"
                                            class="py-3 px-3 rounded-2xl font-bold text-xs border border-[#E5E7E1] transition-all text-center">
                                        🏛️ On Campus
                                    </button>
                                    <button type="button"
                                            @click="form.is_campus = false"
                                            :class="!form.is_campus ? 'bg-[#9BE96B] text-[#082D00] border-[#082D00]' : 'bg-[#F7F7F2] text-[#082D00] hover:bg-black/5'"
                                            class="py-3 px-3 rounded-2xl font-bold text-xs border border-[#E5E7E1] transition-all text-center">
                                        📍 Nearby / Bihta
                                    </button>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-2">
                                <label for="description" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Description & Helpful Tips
                                </label>
                                <textarea id="description"
                                          rows="3"
                                          x-model="form.description"
                                          placeholder="Tell fellow students what to order, best times to visit, special student discounts, or seating notes..."
                                          class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-medium rounded-2xl p-4 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Location & Directions -->
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tight text-[#082D00] pb-3 border-b border-[#E5E7E1] mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#082D00] text-white text-xs flex items-center justify-center font-bold">2</span>
                            Location & Proximity
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Landmark -->
                            <div class="sm:col-span-2">
                                <label for="location_label" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Landmark / Directions <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="location_label"
                                       x-model="form.location_label"
                                       required
                                       placeholder="e.g. 50m outside Gate 1, right beside the tea shop"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                            </div>

                            <!-- Operating Hours -->
                            <div>
                                <label for="hours" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Typical Hours
                                </label>
                                <input type="text"
                                       id="hours"
                                       x-model="form.hours"
                                       placeholder="e.g. 9:00 AM – 10:00 PM"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                            </div>

                            <!-- Proximity -->
                            <div>
                                <label for="distance" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Approx. Distance from Gates
                                </label>
                                <input type="text"
                                       id="distance"
                                       x-model="form.distance"
                                       placeholder="e.g. 200m from Gate 1"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Photo Upload Placeholder -->
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tight text-[#082D00] pb-3 border-b border-[#E5E7E1] mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#082D00] text-white text-xs flex items-center justify-center font-bold">3</span>
                            Photos & Visuals
                        </h2>

                        <!-- Drag and Drop UI -->
                        <div class="border-2 border-dashed border-[#E5E7E1] hover:border-[#082D00]/50 rounded-3xl p-8 text-center bg-[#F7F7F2]/50 hover:bg-[#F7F7F2] transition-colors cursor-pointer"
                             @click="fakeUploadTrigger()">
                            <div class="w-12 h-12 rounded-2xl bg-white text-[#082D00] flex items-center justify-center mx-auto mb-3 shadow-sm border border-[#E5E7E1]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="block text-sm font-extrabold text-[#082D00]">Drag & drop place photos or click to browse</span>
                            <span class="block text-xs text-[#64705D] mt-1">JPEG, PNG, or WEBP up to 5MB (UI preview in foundation)</span>
                            <template x-if="uploadedFile">
                                <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#9BE96B] text-[#082D00] text-xs font-bold">
                                    <span>Selected: <span x-text="uploadedFile"></span></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-[#E5E7E1] flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-xs text-[#64705D]">
                            Submitted contributions undergo moderation before appearing publicly.
                        </span>
                        <x-button variant="primary" size="lg" type="submit">
                            <span>Submit Place for Review</span>
                            <svg class="w-4 h-4 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </x-button>
                    </div>

                </form>
            </div>

        </div>

        <!-- Success Confirmation Modal -->
        <div x-show="submitted" 
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition>
            <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl border border-[#E5E7E1] text-center"
                 @click.away="submitted = false">
                <div class="w-16 h-16 rounded-full bg-[#9BE96B] text-[#082D00] flex items-center justify-center mx-auto mb-4 font-black text-2xl shadow-sm">
                    ✓
                </div>
                <h3 class="text-2xl font-black uppercase text-[#082D00]">Contribution Received!</h3>
                <p class="text-sm text-[#64705D] mt-2 leading-relaxed">
                    Thank you! We've registered <strong class="text-[#082D00]" x-text="form.name"></strong> for validation. In the upcoming Supabase phase, this will be written to the database moderation table.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row gap-2">
                    <button type="button" 
                            @click="submitted = false; resetForm()"
                            class="flex-1 py-3 rounded-full bg-[#082D00] text-white text-xs font-bold hover:bg-[#134504]">
                        Add Another Spot
                    </button>
                    <a href="{{ route('explore') }}" 
                       class="flex-1 py-3 rounded-full bg-[#F7F7F2] text-[#082D00] border border-[#E5E7E1] text-xs font-bold hover:bg-neutral-100 flex items-center justify-center">
                        Back to Explore
                    </a>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function contributeApp() {
            return {
                submitted: false,
                uploadedFile: null,
                form: {
                    name: '',
                    category_id: '',
                    is_campus: false,
                    description: '',
                    location_label: '',
                    hours: '',
                    distance: ''
                },
                fakeUploadTrigger() {
                    this.uploadedFile = 'photo_sample_' + Math.floor(Math.random() * 900 + 100) + '.jpg';
                },
                submitContribution() {
                    if (!this.form.name || !this.form.category_id || !this.form.location_label) {
                        alert('Please fill in the required fields: Name, Category, and Location.');
                        return;
                    }
                    this.submitted = true;
                },
                resetForm() {
                    this.form = {
                        name: '',
                        category_id: '',
                        is_campus: false,
                        description: '',
                        location_label: '',
                        hours: '',
                        distance: ''
                    };
                    this.uploadedFile = null;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
