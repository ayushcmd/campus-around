<x-app-layout :nav-theme="'light'">
    <x-slot name="title">Contribute a Place — CampusAround (IIT Patna)</x-slot>

    <div x-data="contributeApp()" class="py-8 sm:py-14">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8 text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#082D00] text-[#9BE96B] text-xs font-black uppercase tracking-wider mb-3">
                    <span>Community Suggestions</span>
                </div>
                <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-tight">
                    KNOW A PLACE WE'RE MISSING?
                </h1>
                <p class="mt-3 text-base sm:text-lg text-[#64705D] font-medium leading-relaxed">
                    Help students discover useful places around campus. Add a missing tea stall, stationery shop, study nook, or transit point.
                </p>
            </div>

            <!-- Informational Notice Banner -->
            <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-[#9BE96B]/20 border border-[#9BE96B] flex items-start gap-3.5">
                <span class="w-8 h-8 rounded-xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center shrink-0 mt-0.5 font-bold text-sm">
                    ℹ
                </span>
                <div class="text-xs sm:text-sm text-[#082D00] leading-relaxed">
                    <span class="font-extrabold">Frontend Foundation Only:</span> This form is for UI preview and field validation. Cloud database persistence and photo storage will be hooked up to Supabase in a future phase. Submissions will not be saved to a database yet.
                </div>
            </div>

            <!-- Submission Notice Alert (Preserves data, informs user) -->
            <div x-show="submittedNotice" x-cloak class="mb-8 p-5 rounded-2xl bg-amber-50 border border-amber-300 flex items-start gap-3.5">
                <span class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 mt-0.5 font-bold text-base">
                    ⚠
                </span>
                <div class="text-xs sm:text-sm text-amber-950 leading-relaxed">
                    <h4 class="font-black text-amber-900 uppercase">Backend Submission Not Yet Connected</h4>
                    <p class="mt-1">
                        Form validation passed! However, the database backend has not been connected yet in this foundation phase. Your entered details for <strong class="text-black" x-text="form.name"></strong> have been preserved in the form fields below and were not stored on a server.
                    </p>
                </div>
            </div>

            <!-- Contribution Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-[#E5E7E1] shadow-sm">
                <form @submit.prevent="validateAndNotice()" class="space-y-8">
                    
                    <!-- Section 1: Place Information -->
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
                                       placeholder="e.g. Campus Corner Tea & Samosa Stall"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select id="category_id"
                                        x-model="form.category"
                                        required
                                        class="w-full bg-[#F7F7F2] text-[#082D00] font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                                    <option value="" disabled>Select a category</option>
                                    <option value="Campus Facility">Campus Facility</option>
                                    <option value="Food">Food & Street Food</option>
                                    <option value="Cafe">Cafe</option>
                                    <option value="Restaurant">Restaurant</option>
                                    <option value="Grocery">Grocery</option>
                                    <option value="Shop">Shop</option>
                                    <option value="Transportation">Transportation</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Scope -->
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Zone <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button"
                                            @click="form.is_campus = true"
                                            :class="form.is_campus ? 'bg-[#082D00] text-white shadow-xs' : 'bg-[#F7F7F2] text-[#082D00] hover:bg-black/5'"
                                            class="py-3 px-3 rounded-2xl font-bold text-xs border border-[#E5E7E1] transition-all text-center">
                                        🏛️ Inside Campus
                                    </button>
                                    <button type="button"
                                            @click="form.is_campus = false"
                                            :class="!form.is_campus ? 'bg-[#9BE96B] text-[#082D00] border-[#082D00] shadow-xs' : 'bg-[#F7F7F2] text-[#082D00] hover:bg-black/5'"
                                            class="py-3 px-3 rounded-2xl font-bold text-xs border border-[#E5E7E1] transition-all text-center">
                                        📍 Outside / Bihta
                                    </button>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-2">
                                <label for="description" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Description & Student Recommendations
                                </label>
                                <textarea id="description"
                                          rows="3"
                                          x-model="form.description"
                                          placeholder="Share what makes this spot useful, signature items, student discounts, or seating notes..."
                                          class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-medium rounded-2xl p-4 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Location Information -->
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tight text-[#082D00] pb-3 border-b border-[#E5E7E1] mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#082D00] text-white text-xs flex items-center justify-center font-bold">2</span>
                            Location & Proximity
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Address / Landmark -->
                            <div class="sm:col-span-2">
                                <label for="location_label" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Address or Nearby Landmark <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="location_label"
                                       x-model="form.location"
                                       required
                                       placeholder="e.g. 50m outside Gate 1, Bihta Road"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all">
                            </div>

                            <!-- Optional Current Location Fill -->
                            <div class="sm:col-span-2 flex items-center justify-between text-xs p-3 rounded-2xl bg-[#F7F7F2] border border-[#E5E7E1]">
                                <span class="text-[#64705D]">Have GPS coordinates from on-site?</span>
                                <button type="button" 
                                        @click="requestGpsForForm()"
                                        class="font-bold text-[#082D00] underline hover:text-black">
                                    Optional: Detect current coordinates
                                </button>
                            </div>

                            <template x-if="form.coordinates">
                                <div class="sm:col-span-2 text-xs text-[#082D00] font-bold">
                                    Detected GPS: <span x-text="form.coordinates[0].toFixed(5) + '°N, ' + form.coordinates[1].toFixed(5) + '°E'"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Section 3: Photo Upload (Up to 3 Photos with Real Previews & Removal) -->
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tight text-[#082D00] pb-3 border-b border-[#E5E7E1] mb-6 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-[#082D00] text-white text-xs flex items-center justify-center font-bold">3</span>
                                <span>Place Photos (Max 3)</span>
                            </div>
                            <span class="text-xs font-bold text-[#64705D]" x-text="photos.length + '/3 selected'"></span>
                        </h2>

                        <!-- Photo upload input -->
                        <div x-show="photos.length < 3" class="mb-4">
                            <label class="border-2 border-dashed border-[#E5E7E1] hover:border-[#082D00]/50 rounded-3xl p-6 text-center bg-[#F7F7F2]/50 hover:bg-[#F7F7F2] transition-colors cursor-pointer block">
                                <input type="file" 
                                       accept="image/*" 
                                       @change="handlePhotoSelect($event)" 
                                       class="sr-only">
                                <div class="w-10 h-10 rounded-2xl bg-white text-[#082D00] flex items-center justify-center mx-auto mb-2 shadow-xs border border-[#E5E7E1]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <span class="block text-xs font-black uppercase tracking-wider text-[#082D00]">Choose a photo from device</span>
                                <span class="block text-[11px] text-[#64705D] mt-0.5">JPEG, PNG or WEBP (Max 3 photos, local preview only)</span>
                            </label>
                        </div>

                        <!-- Photo Previews Grid with Remove Button -->
                        <div x-show="photos.length > 0" class="grid grid-cols-3 gap-3">
                            <template x-for="(photo, index) in photos" :key="index">
                                <div class="relative h-28 sm:h-36 rounded-2xl overflow-hidden border border-[#E5E7E1] bg-neutral-100 group">
                                    <img :src="photo.url" alt="Preview photo" class="w-full h-full object-cover">
                                    <button type="button" 
                                            @click="removePhoto(index)"
                                            class="absolute top-2 right-2 p-1.5 rounded-full bg-black/70 hover:bg-black text-white text-xs transition-colors"
                                            title="Remove photo">
                                        <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-[#E5E7E1] flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-xs text-[#64705D]">
                            Validated locally. Backend connection scheduled for upcoming Supabase sprint.
                        </span>
                        <x-button variant="primary" size="lg" type="submit">
                            <span>Submit Place for Review</span>
                            <svg class="w-4 h-4 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </x-button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function contributeApp() {
            return {
                submittedNotice: false,
                form: {
                    name: '',
                    category: '',
                    is_campus: true,
                    description: '',
                    location: '',
                    coordinates: null,
                },
                photos: [],

                handlePhotoSelect(e) {
                    const files = e.target.files;
                    if (!files || files.length === 0) return;
                    if (this.photos.length >= 3) {
                        alert('You can upload a maximum of 3 photos.');
                        return;
                    }
                    const file = files[0];
                    const url = URL.createObjectURL(file);
                    this.photos.push({ name: file.name, url: url });
                    e.target.value = '';
                },

                removePhoto(index) {
                    if (this.photos[index] && this.photos[index].url) {
                        URL.revokeObjectURL(this.photos[index].url);
                    }
                    this.photos.splice(index, 1);
                },

                requestGpsForForm() {
                    if (!navigator.geolocation) {
                        alert('Geolocation is not supported by your browser.');
                        return;
                    }
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            this.form.coordinates = [pos.coords.latitude, pos.coords.longitude];
                        },
                        (err) => {
                            alert('Location permission was denied or unavailable.');
                        }
                    );
                },

                validateAndNotice() {
                    if (!this.form.name || !this.form.category || !this.form.location) {
                        alert('Please complete the required fields: Name, Category, and Location.');
                        return;
                    }
                    this.submittedNotice = true;
                    window.scrollTo({ top: 150, behavior: 'smooth' });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
