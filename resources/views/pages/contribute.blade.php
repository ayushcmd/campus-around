<x-app-layout :nav-theme="'light'">
    <x-slot name="title">
        @if($selectedPlace)
            Contribute Information for {{ $selectedPlace['name'] }} — CampusAround (IIT Patna)
        @else
            Contribute a Place — CampusAround (IIT Patna)
        @endif
    </x-slot>

    <!-- Pre-load selected place in JSON script tag to prevent HTML attribute quotes truncation -->
    <script id="selected-place-data" type="application/json">
        {!! json_encode($selectedPlace, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <script>
        function contributeApp(config) {
            let initialPlace = null;
            try {
                const dataEl = document.getElementById('selected-place-data');
                if (dataEl && dataEl.textContent) {
                    initialPlace = JSON.parse(dataEl.textContent);
                }
            } catch (err) {
                console.error('Error parsing selected place data:', err);
            }

            const initialAction = (config && config.action) || 'details';

            return {
                selectedPlace: initialPlace,
                activeTab: initialAction, // 'details', 'menu', 'photo', 'location'
                submittedNotice: false,
                form: {
                    name: initialPlace ? initialPlace.name : '',
                    category: initialPlace ? initialPlace.category : '',
                    is_campus: initialPlace ? initialPlace.is_campus : false,
                    description: initialPlace ? initialPlace.description : '',
                    location: initialPlace ? (initialPlace.address || initialPlace.campus_area || '') : '',
                    phone: initialPlace ? (initialPlace.phone || '') : '',
                    opening_hours: initialPlace ? (initialPlace.opening_hours || '') : '',
                    price_range: initialPlace ? (initialPlace.price_range || '') : '',
                    coordinates: (initialPlace && initialPlace.latitude && initialPlace.longitude) 
                        ? [initialPlace.latitude, initialPlace.longitude] 
                        : null,
                },
                suggestedMenuItems: (initialPlace && initialPlace.menu && initialPlace.menu.length > 0)
                    ? initialPlace.menu.map(m => ({ name: m.name, price: m.price, category: m.category || 'General', description: m.description || '' }))
                    : [
                        { name: '', price: '', category: '', description: '' }
                    ],
                photos: [],

                addMenuItem() {
                    this.suggestedMenuItems.push({ name: '', price: '', category: '', description: '' });
                },

                removeMenuItem(index) {
                    if (this.suggestedMenuItems.length > 1) {
                        this.suggestedMenuItems.splice(index, 1);
                    } else {
                        this.suggestedMenuItems = [{ name: '', price: '', category: '', description: '' }];
                    }
                },

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
                    if (!this.form.name || !this.form.location) {
                        alert('Please fill in the place name and address / location.');
                        return;
                    }
                    this.submittedNotice = true;
                    window.scrollTo({ top: 120, behavior: 'smooth' });
                }
            };
        }
    </script>

    <div x-data="contributeApp({ action: '{{ $action ?? 'details' }}' })" class="py-8 sm:py-14">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8 text-center max-w-2xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#082D00] text-[#9BE96B] text-xs font-black uppercase tracking-wider mb-3 shadow-2xs">
                    <span>Community Contributions</span>
                </div>
                
                <template x-if="selectedPlace">
                    <div>
                        <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-tight">
                            SUGGEST AN EDIT FOR <span class="text-[#134504]" x-text="selectedPlace.name"></span>
                        </h1>
                        <p class="mt-3 text-base text-[#64705D] font-medium leading-relaxed">
                            Have an updated phone number, new opening hours, photo, or menu price updates? Submit verified updates for this spot.
                        </p>
                    </div>
                </template>
                <template x-if="!selectedPlace">
                    <div>
                        <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-tight">
                            KNOW A PLACE WE'RE MISSING?
                        </h1>
                        <p class="mt-3 text-base sm:text-lg text-[#64705D] font-medium leading-relaxed">
                            Help students discover useful spots around IIT Patna. Add a missing tea stall, dhaba, stationery shop, pharmacy, or transit pickup.
                        </p>
                    </div>
                </template>
            </div>

            <!-- Active Place Context Pill Banner (when opened from shop details) -->
            <template x-if="selectedPlace">
                <div class="mb-6 p-4 rounded-2xl bg-white border border-[#082D00]/20 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-[#9BE96B] text-[#082D00] flex items-center justify-center font-black text-lg shrink-0">
                            📍
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black text-[#082D00]" x-text="selectedPlace.name"></span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#F7F7F2] text-[#64705D] border border-[#E5E7E1]" x-text="selectedPlace.category"></span>
                            </div>
                            <span class="text-[11px] text-[#64705D]" x-text="selectedPlace.campus_area || selectedPlace.address"></span>
                        </div>
                    </div>
                    <a href="{{ route('contribute') }}" class="text-xs font-bold text-[#64705D] hover:text-[#082D00] underline self-start sm:self-auto">
                        Suggest a new place instead &rarr;
                    </a>
                </div>
            </template>

            <!-- Mode Switcher Tabs -->
            <div class="flex items-center gap-2 mb-6 overflow-x-auto no-scrollbar pb-1">
                <button type="button" 
                        @click="activeTab = 'details'"
                        :class="activeTab === 'details' ? 'bg-[#082D00] text-white shadow-xs' : 'bg-white text-[#64705D] border border-[#E5E7E1] hover:border-[#082D00]/30'"
                        class="px-4 py-2 rounded-full text-xs font-bold transition-all shrink-0">
                    1. Basic Info & Timings
                </button>
                <button type="button" 
                        @click="activeTab = 'menu'"
                        :class="activeTab === 'menu' ? 'bg-[#082D00] text-white shadow-xs' : 'bg-white text-[#64705D] border border-[#E5E7E1] hover:border-[#082D00]/30'"
                        class="px-4 py-2 rounded-full text-xs font-bold transition-all shrink-0">
                    2. Menu & Prices
                </button>
                <button type="button" 
                        @click="activeTab = 'photo'"
                        :class="activeTab === 'photo' ? 'bg-[#082D00] text-white shadow-xs' : 'bg-white text-[#64705D] border border-[#E5E7E1] hover:border-[#082D00]/30'"
                        class="px-4 py-2 rounded-full text-xs font-bold transition-all shrink-0">
                    3. Photos (<span x-text="photos.length"></span>/3)
                </button>
                <button type="button" 
                        @click="activeTab = 'location'"
                        :class="activeTab === 'location' ? 'bg-[#082D00] text-white shadow-xs' : 'bg-white text-[#64705D] border border-[#E5E7E1] hover:border-[#082D00]/30'"
                        class="px-4 py-2 rounded-full text-xs font-bold transition-all shrink-0">
                    4. Location & GPS
                </button>
            </div>

            <!-- Informational Notice Banner -->
            <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-[#9BE96B]/20 border border-[#9BE96B] flex items-start gap-3.5">
                <span class="w-8 h-8 rounded-xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center shrink-0 mt-0.5 font-bold text-sm">
                    ℹ
                </span>
                <div class="text-xs sm:text-sm text-[#082D00] leading-relaxed">
                    <span class="font-extrabold">Student Moderation Concept:</span> Contributions are validated locally and queued for student moderator review before publishing. This ensures business phone numbers, menu prices, and coordinates remain authentic.
                </div>
            </div>

            <!-- Submission Notice Alert (Preserves data, informs user) -->
            <div x-show="submittedNotice" x-cloak class="mb-8 p-5 rounded-2xl bg-emerald-50 border border-emerald-300 flex items-start gap-3.5">
                <span class="w-8 h-8 rounded-xl bg-[#082D00] text-[#9BE96B] flex items-center justify-center shrink-0 mt-0.5 font-bold text-base">
                    ✓
                </span>
                <div class="text-xs sm:text-sm text-emerald-950 leading-relaxed">
                    <h4 class="font-black text-[#082D00] uppercase">Contribution Logged for Review</h4>
                    <p class="mt-1">
                        Thank you! Your suggestions for <strong class="text-[#082D00]" x-text="form.name"></strong> have been validated and recorded for peer moderation. Verified submissions will appear in the upcoming directory sync.
                    </p>
                </div>
            </div>

            <!-- Contribution Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-[#E5E7E1] shadow-sm">
                <form @submit.prevent="validateAndNotice()" class="space-y-8">
                    
                    <!-- TAB 1: Place Details & Contact -->
                    <div x-show="activeTab === 'details'">
                        <h2 class="text-lg font-black uppercase tracking-tight text-[#082D00] pb-3 border-b border-[#E5E7E1] mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#082D00] text-white text-xs flex items-center justify-center font-bold">1</span>
                            Place Details & Timings
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
                                       placeholder="e.g. Bihari Thaath (Bt) Restaurant"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select id="category_id"
                                        x-model="form.category"
                                        required
                                        class="w-full bg-[#F7F7F2] text-[#082D00] font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                                    <option value="" disabled>Select a category</option>
                                    <option value="Food & Dhabas">Food & Dhabas</option>
                                    <option value="Restaurants">Restaurants</option>
                                    <option value="Cafes">Cafes</option>
                                    <option value="Grocery & Markets">Grocery & Markets</option>
                                    <option value="Pharmacies & Services">Pharmacies & Services</option>
                                    <option value="Transit & Stations">Transit & Stations</option>
                                    <option value="Campus Facility">Campus Facility</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Scope / Zone -->
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

                            <!-- Operating Hours -->
                            <div>
                                <label for="opening_hours" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Operating Hours
                                </label>
                                <input type="text"
                                       id="opening_hours"
                                       x-model="form.opening_hours"
                                       placeholder="e.g. 10:00 AM – 10:30 PM (Daily)"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="phone" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Public Business Phone
                                </label>
                                <input type="tel"
                                       id="phone"
                                       x-model="form.phone"
                                       placeholder="e.g. +91 98765 43210 (verified public line only)"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-2">
                                <label for="description" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-2">
                                    Description & Student Notes
                                </label>
                                <textarea id="description"
                                          rows="3"
                                          x-model="form.description"
                                          placeholder="Share what makes this spot useful, student-favorite dishes, discounts, seating quality, or transit connectivity..."
                                          class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-medium rounded-2xl p-4 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" @click="activeTab = 'menu'" class="px-5 py-2.5 rounded-full bg-[#082D00] text-[#9BE96B] font-bold text-xs hover:bg-[#134504] transition-all">
                                Next: Menu & Prices &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- TAB 2: Menu and Food Prices -->
                    <div x-show="activeTab === 'menu'">
                        <div class="flex items-center justify-between pb-3 border-b border-[#E5E7E1] mb-6">
                            <h2 class="text-lg font-black uppercase tracking-tight text-[#082D00] flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-[#082D00] text-white text-xs flex items-center justify-center font-bold">2</span>
                                <span>Suggested Menu Items & Prices</span>
                            </h2>
                            <button type="button" 
                                    @click="addMenuItem()" 
                                    class="text-xs font-bold text-[#082D00] bg-[#9BE96B] px-3 py-1.5 rounded-full hover:bg-[#88dc55] transition-colors">
                                + Add Another Item
                            </button>
                        </div>

                        <p class="text-xs text-[#64705D] mb-4">
                            Suggest accurate items and real prices. Do not invent items or prices. Skip this section if not applicable (e.g. for pharmacies or railway stations).
                        </p>

                        <div class="space-y-4">
                            <template x-for="(item, index) in suggestedMenuItems" :key="index">
                                <div class="p-4 rounded-2xl bg-[#F7F7F2] border border-[#E5E7E1] relative space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-black uppercase text-[#64705D]" x-text="'Item #' + (index + 1)"></span>
                                        <button type="button" 
                                                @click="removeMenuItem(index)" 
                                                class="text-red-500 hover:text-red-700 text-xs font-bold">
                                            Remove
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                        <div class="sm:col-span-6">
                                            <input type="text" 
                                                   x-model="item.name" 
                                                   placeholder="Item name (e.g. Traditional Bihari Thali)" 
                                                   class="w-full bg-white rounded-xl px-3 py-2 text-xs font-semibold border border-[#E5E7E1] text-[#082D00]">
                                        </div>
                                        <div class="sm:col-span-3">
                                            <input type="number" 
                                                   x-model="item.price" 
                                                   placeholder="Price (₹)" 
                                                   class="w-full bg-white rounded-xl px-3 py-2 text-xs font-semibold border border-[#E5E7E1] text-[#082D00]">
                                        </div>
                                        <div class="sm:col-span-3">
                                            <input type="text" 
                                                   x-model="item.category" 
                                                   placeholder="Category (e.g. Thalis)" 
                                                   class="w-full bg-white rounded-xl px-3 py-2 text-xs font-semibold border border-[#E5E7E1] text-[#082D00]">
                                        </div>
                                    </div>
                                    <div>
                                        <input type="text" 
                                               x-model="item.description" 
                                               placeholder="Short description (e.g. Rice, dal, 2 sabzi, 4 rotis)" 
                                               class="w-full bg-white rounded-xl px-3 py-2 text-xs font-medium border border-[#E5E7E1] text-[#64705D]">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <button type="button" @click="activeTab = 'details'" class="text-xs font-bold text-[#64705D] hover:text-[#082D00]">
                                &larr; Back to Details
                            </button>
                            <button type="button" @click="activeTab = 'photo'" class="px-5 py-2.5 rounded-full bg-[#082D00] text-[#9BE96B] font-bold text-xs hover:bg-[#134504] transition-all">
                                Next: Place Photos &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- TAB 3: Place Photos -->
                    <div x-show="activeTab === 'photo'">
                        <h2 class="text-lg font-black uppercase tracking-tight text-[#082D00] pb-3 border-b border-[#E5E7E1] mb-6 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-[#082D00] text-white text-xs flex items-center justify-center font-bold">3</span>
                                <span>Place Photos (Max 3)</span>
                            </div>
                            <span class="text-xs font-bold text-[#64705D]" x-text="photos.length + '/3 selected'"></span>
                        </h2>

                        <p class="text-xs text-[#64705D] mb-4">
                            Only upload authentic photographs of the storefront, printed menu board, or seating area. Stock images are rejected during moderation.
                        </p>

                        <!-- Photo upload input -->
                        <div x-show="photos.length < 3" class="mb-4">
                            <label class="border-2 border-dashed border-[#E5E7E1] hover:border-[#082D00]/50 rounded-3xl p-6 text-center bg-[#F7F7F2]/50 hover:bg-[#F7F7F2] transition-colors cursor-pointer block">
                                <input type="file" 
                                       accept="image/*" 
                                       @change="handlePhotoSelect($event)" 
                                       class="sr-only">
                                <div class="w-10 h-10 rounded-2xl bg-white text-[#082D00] flex items-center justify-center mx-auto mb-2 shadow-xs border border-[#E5E7E1]">
                                    📸
                                </div>
                                <span class="text-xs font-bold text-[#082D00] block">Choose a photo from your device</span>
                                <span class="text-[11px] text-[#64705D] mt-1 block">PNG, JPG, or WEBP up to 5MB</span>
                            </label>
                        </div>

                        <!-- Previews -->
                        <div x-show="photos.length > 0" class="grid grid-cols-3 gap-3 mb-4">
                            <template x-for="(p, idx) in photos" :key="idx">
                                <div class="relative h-24 rounded-2xl overflow-hidden border border-[#E5E7E1] group">
                                    <img :src="p.url" class="w-full h-full object-cover">
                                    <button type="button" 
                                            @click="removePhoto(idx)" 
                                            class="absolute top-1 right-1 w-6 h-6 rounded-full bg-black/75 text-white flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                        ×
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <button type="button" @click="activeTab = 'menu'" class="text-xs font-bold text-[#64705D] hover:text-[#082D00]">
                                &larr; Back to Menu
                            </button>
                            <button type="button" @click="activeTab = 'location'" class="px-5 py-2.5 rounded-full bg-[#082D00] text-[#9BE96B] font-bold text-xs hover:bg-[#134504] transition-all">
                                Next: Location & GPS &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- TAB 4: Location & GPS -->
                    <div x-show="activeTab === 'location'">
                        <h2 class="text-lg font-black uppercase tracking-tight text-[#082D00] pb-3 border-b border-[#E5E7E1] mb-6 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#082D00] text-white text-xs flex items-center justify-center font-bold">4</span>
                            Location & Coordinates
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
                                       placeholder="e.g. Bihta-Kanpa Road, ~750m outside Gate 1"
                                       class="w-full bg-[#F7F7F2] text-[#082D00] placeholder:text-[#64705D]/70 font-semibold rounded-2xl px-4 py-3.5 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                            </div>

                            <!-- Optional Current Location Fill -->
                            <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between text-xs p-4 rounded-2xl bg-[#F7F7F2] border border-[#E5E7E1] gap-2">
                                <span class="text-[#64705D]">Are you currently at the spot in Bihta?</span>
                                <button type="button" 
                                        @click="requestGpsForForm()"
                                        class="font-black text-[#082D00] underline hover:text-[#134504] self-start sm:self-auto flex items-center gap-1.5">
                                    <span>Detect current GPS coordinates</span>
                                </button>
                            </div>

                            <template x-if="form.coordinates">
                                <div class="sm:col-span-2 text-xs text-[#082D00] font-bold p-3 rounded-xl bg-[#9BE96B]/20 border border-[#9BE96B]">
                                    Detected GPS: <span x-text="form.coordinates[0].toFixed(5) + '°N, ' + form.coordinates[1].toFixed(5) + '°E'"></span>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <button type="button" @click="activeTab = 'photo'" class="text-xs font-bold text-[#64705D] hover:text-[#082D00]">
                                &larr; Back to Photos
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6 border-t border-[#E5E7E1] flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-xs text-[#64705D]">
                            Submitted to campus community moderation. No immediate database overwrite.
                        </span>
                        <x-button variant="primary" size="lg" type="submit">
                            <span x-text="selectedPlace ? 'Submit Suggestion for ' + selectedPlace.name : 'Submit Place for Review'"></span>
                            <svg class="w-4 h-4 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </x-button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
