<x-app-layout :nav-theme="'light'">
    <x-slot name="title">Locate & Route — Apple-Inspired Live Directions (IIT Patna)</x-slot>

    <div x-data="locateApp({
            allPlaces: @json($allPlaces),
            insidePlaces: @json($insidePlaces),
            outsidePlaces: @json($outsidePlaces),
            initialStart: @json($initialStart),
            initialDest: @json($initialDest)
         })" 
         class="py-6 sm:py-10">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#082D00] text-[#9BE96B] text-xs font-black uppercase tracking-wider mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#9BE96B] animate-ping"></span>
                        Spatial Navigation
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-[#082D00] leading-none">
                        WHERE DO YOU WANT TO GO?
                    </h1>
                    <p class="text-sm text-[#64705D] font-medium mt-1">
                        Choose your starting point and destination to compute real walking and road directions across IIT Patna & Bihta.
                    </p>
                </div>

                <div class="shrink-0 flex items-center gap-2">
                    <span class="text-xs font-bold text-[#64705D] bg-white px-3 py-1.5 rounded-full border border-[#E5E7E1] shadow-2xs">
                        OpenStreetMap & OSRM Engine
                    </span>
                </div>
            </div>

            <!-- Main Layout: Desktop 2-Col (Panel 5 cols, Map 7 cols) / Mobile Stacked -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- LEFT NAVIGATION CONTROL PANEL (5 cols on desktop) -->
                <div class="lg:col-span-5 space-y-5">
                    
                    <!-- Selection Card -->
                    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-[#E5E7E1] shadow-sm space-y-4">
                        
                        <!-- Travel Mode Buttons -->
                        <div class="flex items-center justify-between p-1 bg-[#F7F7F2] rounded-full border border-[#E5E7E1]">
                            <button type="button" 
                                    @click="setMode('walking')"
                                    :class="travelMode === 'walking' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#64705D] hover:text-[#082D00]'"
                                    class="flex-1 py-2 px-3 rounded-full text-xs font-extrabold transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><path d="m9 20 3-6 3 6"/><path d="m6 8 6 2 6-2"/><path d="M12 10v4"/></svg>
                                <span>Walking</span>
                            </button>
                            <button type="button" 
                                    @click="setMode('driving')"
                                    :class="travelMode === 'driving' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#64705D] hover:text-[#082D00]'"
                                    class="flex-1 py-2 px-3 rounded-full text-xs font-extrabold transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                                <span>Driving</span>
                            </button>
                            <button type="button" 
                                    @click="setMode('cycling')"
                                    :class="travelMode === 'cycling' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#64705D] hover:text-[#082D00]'"
                                    class="flex-1 py-2 px-3 rounded-full text-xs font-extrabold transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="18.5" cy="17.5" r="3.5"/><circle cx="5.5" cy="17.5" r="3.5"/><circle cx="15" cy="5" r="1"/><path d="M12 17.5V14l-3-3 4-3 2 3h2"/></svg>
                                <span>Cycling</span>
                            </button>
                        </div>

                        <!-- 1. Starting Location Field -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="start_select" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00]">
                                    Starting Point
                                </label>
                                <!-- Explicit Current Location Button (Will prompt GPS only when clicked) -->
                                <button type="button" 
                                        @click="requestCurrentLocation()"
                                        class="text-[11px] font-extrabold text-[#082D00] hover:text-[#134504] underline flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-[#082D00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>Use my current location</span>
                                </button>
                            </div>

                            <select id="start_select"
                                    x-model="startId" 
                                    @change="onStartChange()"
                                    class="w-full bg-[#F7F7F2] text-[#082D00] font-semibold rounded-2xl px-4 py-3 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                                <option value="">-- Choose starting location --</option>
                                <template x-if="gpsLocation">
                                    <option value="gps">📍 My Current Location (GPS)</option>
                                </template>
                                <optgroup label="🏛️ Inside Campus Facilities">
                                    <template x-for="p in insidePlaces" :key="p.id">
                                        <option :value="p.id" x-text="p.name + ' (' + p.category + ')'"></option>
                                    </template>
                                </optgroup>
                                <optgroup label="📍 Outside Campus / Bihta">
                                    <template x-for="p in outsidePlaces" :key="p.id">
                                        <option :value="p.id" x-text="p.name + ' (' + p.category + ')'"></option>
                                    </template>
                                </optgroup>
                            </select>

                            <!-- GPS Status / Info Message -->
                            <div x-show="gpsMessage" x-cloak class="mt-2 text-xs p-2.5 rounded-xl border"
                                 :class="gpsError ? 'bg-red-50 text-red-700 border-red-200' : 'bg-[#9BE96B]/20 text-[#082D00] border-[#9BE96B]'">
                                <span x-text="gpsMessage"></span>
                            </div>
                        </div>

                        <!-- Swap Locations Button -->
                        <div class="flex justify-center -my-1">
                            <button type="button" 
                                    @click="swapPoints()" 
                                    class="p-2 rounded-full bg-[#F7F7F2] border border-[#E5E7E1] text-[#082D00] hover:bg-neutral-200 active:scale-95 transition-all"
                                    title="Swap Start and Destination">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m7 15 5 5 5-5"/>
                                    <path d="m7 9 5-5 5 5"/>
                                </svg>
                            </button>
                        </div>

                        <!-- 2. Destination Field -->
                        <div>
                            <label for="dest_select" class="block text-xs font-extrabold uppercase tracking-wider text-[#082D00] mb-1.5">
                                Destination
                            </label>

                            <select id="dest_select"
                                    x-model="destId" 
                                    @change="onDestChange()"
                                    class="w-full bg-[#F7F7F2] text-[#082D00] font-semibold rounded-2xl px-4 py-3 border border-[#E5E7E1] focus:border-[#082D00] focus:bg-white focus:outline-none transition-all text-sm">
                                <option value="">-- Choose destination --</option>
                                <optgroup label="🏛️ Inside Campus Facilities">
                                    <template x-for="p in insidePlaces" :key="p.id">
                                        <option :value="p.id" x-text="p.name + ' (' + p.category + ')'"></option>
                                    </template>
                                </optgroup>
                                <optgroup label="📍 Outside Campus / Bihta">
                                    <template x-for="p in outsidePlaces" :key="p.id">
                                        <option :value="p.id" x-text="p.name + ' (' + p.category + ')'"></option>
                                    </template>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Action Button: Show Route -->
                        <div class="pt-2">
                            <button type="button"
                                    @click="calculateRoute()"
                                    :disabled="!startCoords || !destCoords || isCalculating"
                                    class="w-full py-3.5 px-6 rounded-full bg-[#082D00] text-white font-extrabold text-sm hover:bg-[#134504] active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-sm flex items-center justify-center gap-2">
                                <template x-if="!isCalculating">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                        <span>Show Route & Directions</span>
                                    </div>
                                </template>
                                <template x-if="isCalculating">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                        <span>Calculating Real Route...</span>
                                    </div>
                                </template>
                            </button>
                        </div>

                    </div>

                    <!-- ROUTE SUMMARY & DIRECTIONS PANEL -->
                    <div x-show="routeData" x-cloak class="bg-white rounded-3xl p-5 sm:p-6 border border-[#E5E7E1] shadow-sm space-y-4">
                        
                        <!-- Header with distance & duration -->
                        <div class="flex items-center justify-between pb-3 border-b border-[#E5E7E1]">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-[#64705D]">Estimated Trip</span>
                                <div class="flex items-baseline gap-2 mt-0.5">
                                    <h3 class="text-2xl font-black text-[#082D00]" x-text="routeData.formattedDuration"></h3>
                                    <span class="text-sm font-bold text-[#64705D]" x-text="'(' + routeData.formattedDistance + ')'"></span>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase"
                                  :class="travelMode === 'walking' ? 'bg-[#9BE96B] text-[#082D00]' : 'bg-[#082D00] text-white'"
                                  x-text="travelMode">
                            </span>
                        </div>

                        <!-- Turn-by-turn Directions List -->
                        <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-[#082D00] block mb-2">Step-by-step guidance</span>
                            
                            <template x-for="(step, idx) in routeData.steps" :key="idx">
                                <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F7F7F2] transition-colors text-xs">
                                    <span class="w-6 h-6 rounded-full bg-[#F7F7F2] border border-[#E5E7E1] text-[#082D00] font-bold flex items-center justify-center shrink-0 text-[11px]" x-text="idx + 1"></span>
                                    <div class="flex-1">
                                        <p class="font-bold text-[#082D00] leading-snug" x-text="step.instruction"></p>
                                        <span class="text-[11px] text-[#64705D]" x-text="step.distance + ' m'"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- External Navigation Fallback -->
                        <div class="pt-3 border-t border-[#E5E7E1] flex items-center justify-between text-xs">
                            <span class="text-[#64705D]">Open in external app:</span>
                            <a :href="externalGoogleMapsUrl" target="_blank" rel="noopener noreferrer" 
                               class="font-bold text-[#082D00] hover:underline flex items-center gap-1">
                                <span>Google Maps Directions</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Error Notice if Routing Fails -->
                    <div x-show="routingError" x-cloak class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-2">
                        <div class="flex items-center gap-2 font-bold">
                            <span>⚠</span>
                            <span>Direct road route could not be calculated</span>
                        </div>
                        <p class="leading-relaxed" x-text="routingError"></p>
                        <template x-if="externalGoogleMapsUrl">
                            <a :href="externalGoogleMapsUrl" target="_blank" rel="noopener noreferrer" 
                               class="inline-block mt-2 font-bold underline text-[#082D00]">
                                Open route in Google Maps &rarr;
                            </a>
                        </template>
                    </div>

                </div>

                <!-- RIGHT MAP PANEL (7 cols on desktop) -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-[#E5E7E1] p-4 shadow-sm relative">
                    
                    <!-- Top Map Bar -->
                    <div class="flex items-center justify-between mb-3 px-2">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-[#9BE96B] border border-[#082D00]"></span>
                            <span class="text-xs font-bold text-[#082D00] uppercase tracking-wider">Live Spatial Map (IIT Patna)</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <button type="button" @click="fitBounds()" class="font-bold text-[#64705D] hover:text-[#082D00]">
                                Recenter
                            </button>
                        </div>
                    </div>

                    <!-- Leaflet DOM element -->
                    <div id="locate-map" class="w-full h-[450px] sm:h-[550px] lg:h-[620px] rounded-2xl overflow-hidden border border-[#E5E7E1] z-10"></div>

                    <!-- Map Helper Legend -->
                    <div class="mt-3 px-2 flex flex-wrap items-center justify-between gap-3 text-xs text-[#64705D]">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center gap-1.5 font-bold text-[#082D00]">
                                <span class="w-3 h-3 rounded-full bg-[#082D00]"></span> Start Point
                            </span>
                            <span class="inline-flex items-center gap-1.5 font-bold text-[#082D00]">
                                <span class="w-3 h-3 rounded-full bg-[#9BE96B] border border-[#082D00]"></span> Destination
                            </span>
                        </div>
                        <span class="text-[11px]">Click map anywhere to choose points</span>
                    </div>

                </div>

            </div>

        </div>

        <!-- Geolocation Permission Modal Prompt -->
        <div x-show="showGpsPrompt" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" x-transition>
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-[#E5E7E1] text-center" @click.away="showGpsPrompt = false">
                <div class="w-14 h-14 rounded-2xl bg-[#9BE96B] text-[#082D00] flex items-center justify-center mx-auto mb-4 font-black text-2xl shadow-xs">
                    📍
                </div>
                <h3 class="text-xl font-black uppercase text-[#082D00]">Use Current Location?</h3>
                <p class="text-xs sm:text-sm text-[#64705D] mt-2 leading-relaxed">
                    CampusAround will request browser GPS permission to detect your position on campus. Your coordinates are used locally for routing and are never stored on any server.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row gap-2">
                    <button type="button" 
                            @click="proceedWithGeolocation()"
                            class="flex-1 py-3 px-4 rounded-full bg-[#082D00] text-white text-xs font-black hover:bg-[#134504] transition-colors">
                        Allow & Detect Location
                    </button>
                    <button type="button" 
                            @click="showGpsPrompt = false"
                            class="flex-1 py-3 px-4 rounded-full bg-[#F7F7F2] text-[#082D00] border border-[#E5E7E1] text-xs font-bold hover:bg-neutral-100 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function locateApp(config) {
            return {
                allPlaces: config.allPlaces,
                insidePlaces: config.insidePlaces,
                outsidePlaces: config.outsidePlaces,
                
                startId: config.initialStart ? config.initialStart.id : '',
                destId: config.initialDest ? config.initialDest.id : '',
                startCoords: config.initialStart ? [config.initialStart.latitude, config.initialStart.longitude] : null,
                destCoords: config.initialDest ? [config.initialDest.latitude, config.initialDest.longitude] : null,

                travelMode: 'walking', // 'walking', 'driving', 'cycling'
                isCalculating: false,
                routeData: null,
                routingError: null,

                // GPS Permission & Tracking
                showGpsPrompt: false,
                gpsLocation: null,
                gpsMessage: null,
                gpsError: false,

                // Leaflet Map Elements
                map: null,
                startMarker: null,
                destMarker: null,
                routeLayer: null,

                init() {
                    this.$nextTick(() => {
                        this.initMap();
                        if (this.startCoords && this.destCoords) {
                            this.calculateRoute();
                        }
                    });
                },

                initMap() {
                    const center = this.destCoords || this.startCoords || [25.5357, 84.8512];
                    this.map = L.map('locate-map', {
                        center: center,
                        zoom: 15,
                        scrollWheelZoom: false,
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap &copy; OSRM'
                    }).addTo(this.map);

                    // Map click handler to pick locations
                    this.map.on('click', (e) => {
                        if (!this.startCoords) {
                            this.startCoords = [e.latlng.lat, e.latlng.lng];
                            this.startId = '';
                            this.gpsMessage = 'Start set to selected map point';
                            this.gpsError = false;
                            this.updateMarkers();
                        } else if (!this.destCoords) {
                            this.destCoords = [e.latlng.lat, e.latlng.lng];
                            this.destId = '';
                            this.updateMarkers();
                            this.calculateRoute();
                        }
                    });

                    this.updateMarkers();
                },

                setMode(mode) {
                    this.travelMode = mode;
                    if (this.startCoords && this.destCoords) {
                        this.calculateRoute();
                    }
                },

                onStartChange() {
                    if (this.startId === 'gps') {
                        if (this.gpsLocation) {
                            this.startCoords = this.gpsLocation;
                        }
                    } else {
                        const place = this.allPlaces.find(p => p.id === this.startId);
                        if (place && place.latitude && place.longitude) {
                            this.startCoords = [place.latitude, place.longitude];
                        } else {
                            this.startCoords = null;
                        }
                    }
                    this.updateMarkers();
                },

                onDestChange() {
                    const place = this.allPlaces.find(p => p.id === this.destId);
                    if (place && place.latitude && place.longitude) {
                        this.destCoords = [place.latitude, place.longitude];
                    } else {
                        this.destCoords = null;
                    }
                    this.updateMarkers();
                },

                swapPoints() {
                    const tempId = this.startId;
                    this.startId = this.destId;
                    this.destId = tempId;

                    const tempCoords = this.startCoords;
                    this.startCoords = this.destCoords;
                    this.destCoords = tempCoords;

                    this.updateMarkers();
                    if (this.startCoords && this.destCoords) {
                        this.calculateRoute();
                    }
                },

                requestCurrentLocation() {
                    this.showGpsPrompt = true;
                },

                proceedWithGeolocation() {
                    this.showGpsPrompt = false;
                    this.gpsMessage = 'Detecting current position...';
                    this.gpsError = false;

                    if (!navigator.geolocation) {
                        this.gpsError = true;
                        this.gpsMessage = 'Geolocation is not supported by your browser. Please select a starting point manually.';
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            this.gpsLocation = [pos.coords.latitude, pos.coords.longitude];
                            this.startCoords = this.gpsLocation;
                            this.startId = 'gps';
                            this.gpsError = false;
                            this.gpsMessage = `Located at ${pos.coords.latitude.toFixed(4)}°N, ${pos.coords.longitude.toFixed(4)}°E (Accuracy: ~${Math.round(pos.coords.accuracy)}m)`;
                            this.updateMarkers();
                            if (this.destCoords) {
                                this.calculateRoute();
                            }
                        },
                        (err) => {
                            this.gpsError = true;
                            if (err.code === err.PERMISSION_DENIED) {
                                this.gpsMessage = 'Location access was denied. You can manually choose any campus facility or point on the map.';
                            } else {
                                this.gpsMessage = 'Unable to determine GPS coordinates. Please select a starting point from the list.';
                            }
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                },

                updateMarkers() {
                    if (!this.map) return;

                    if (this.startMarker) {
                        this.map.removeLayer(this.startMarker);
                        this.startMarker = null;
                    }
                    if (this.destMarker) {
                        this.map.removeLayer(this.destMarker);
                        this.destMarker = null;
                    }

                    if (this.startCoords) {
                        const startIcon = L.divIcon({
                            className: 'start-pin',
                            html: `
                                <div style="
                                    background-color: #082D00;
                                    color: #FFFFFF;
                                    width: 32px;
                                    height: 32px;
                                    border-radius: 9999px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    border: 2px solid #FFFFFF;
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
                                    font-weight: 900;
                                    font-size: 13px;
                                ">
                                    A
                                </div>
                            `,
                            iconSize: [32, 32],
                            iconAnchor: [16, 16],
                            popupAnchor: [0, -16]
                        });
                        this.startMarker = L.marker(this.startCoords, { icon: startIcon })
                            .addTo(this.map)
                            .bindPopup("<strong>Starting Point</strong>");
                    }

                    if (this.destCoords) {
                        const destIcon = L.divIcon({
                            className: 'dest-pin',
                            html: `
                                <div style="
                                    background-color: #9BE96B;
                                    color: #082D00;
                                    width: 32px;
                                    height: 32px;
                                    border-radius: 9999px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    border: 2px solid #082D00;
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
                                    font-weight: 900;
                                    font-size: 13px;
                                ">
                                    B
                                </div>
                            `,
                            iconSize: [32, 32],
                            iconAnchor: [16, 16],
                            popupAnchor: [0, -16]
                        });
                        this.destMarker = L.marker(this.destCoords, { icon: destIcon })
                            .addTo(this.map)
                            .bindPopup("<strong>Destination</strong>");
                    }

                    this.fitBounds();
                },

                fitBounds() {
                    if (!this.map) return;
                    if (this.startCoords && this.destCoords) {
                        const bounds = L.latLngBounds([this.startCoords, this.destCoords]);
                        this.map.fitBounds(bounds, { padding: [50, 50] });
                    } else if (this.startCoords) {
                        this.map.setView(this.startCoords, 16);
                    } else if (this.destCoords) {
                        this.map.setView(this.destCoords, 16);
                    } else {
                        this.map.setView([25.5357, 84.8512], 15);
                    }
                },

                async calculateRoute() {
                    if (!this.startCoords || !this.destCoords) return;

                    this.isCalculating = true;
                    this.routingError = null;

                    // Map our travel mode to OSRM profile
                    let osrmProfile = 'foot';
                    if (this.travelMode === 'driving') osrmProfile = 'car';
                    else if (this.travelMode === 'cycling') osrmProfile = 'bike';

                    const startLng = this.startCoords[1];
                    const startLat = this.startCoords[0];
                    const destLng = this.destCoords[1];
                    const destLat = this.destCoords[0];

                    const url = `https://router.project-osrm.org/route/v1/${osrmProfile}/${startLng},${startLat};${destLng},${destLat}?overview=full&geometries=geojson&steps=true`;

                    try {
                        const res = await fetch(url);
                        if (!res.ok) throw new Error(`OSRM HTTP error: ${res.status}`);
                        const data = await res.json();

                        if (!data.routes || data.routes.length === 0) {
                            throw new Error('No navigable route found between these points on the road network.');
                        }

                        const route = data.routes[0];
                        const distanceMeters = route.distance;
                        const durationSeconds = route.duration;

                        // Clean step directions
                        const rawSteps = (route.legs && route.legs[0] && route.legs[0].steps) ? route.legs[0].steps : [];
                        const formattedSteps = rawSteps.map(s => {
                            let text = s.name ? `Proceed onto ${s.name}` : 'Continue straight';
                            if (s.maneuver) {
                                const type = s.maneuver.type;
                                const modifier = s.maneuver.modifier ? ` (${s.maneuver.modifier})` : '';
                                if (type === 'depart') text = 'Start navigation';
                                else if (type === 'arrive') text = 'Arrive at destination';
                                else if (type === 'turn') text = `Turn ${s.maneuver.modifier || ''} onto ${s.name || 'path'}`;
                                else text = `${type}${modifier} onto ${s.name || 'path'}`;
                            }
                            return {
                                instruction: text,
                                distance: Math.round(s.distance)
                            };
                        });

                        this.routeData = {
                            distance: distanceMeters,
                            formattedDistance: distanceMeters >= 1000 ? `${(distanceMeters / 1000).toFixed(2)} km` : `${Math.round(distanceMeters)} m`,
                            duration: durationSeconds,
                            formattedDuration: durationSeconds >= 3600 
                                ? `${Math.floor(durationSeconds / 3600)} hr ${Math.round((durationSeconds % 3600) / 60)} min`
                                : `${Math.max(1, Math.round(durationSeconds / 60))} min`,
                            geometry: route.geometry,
                            steps: formattedSteps.length > 0 ? formattedSteps : [
                                { instruction: 'Follow local pedestrian path to destination', distance: Math.round(distanceMeters) }
                            ]
                        };

                        // Render real route geometry on Leaflet map
                        if (this.routeLayer) {
                            this.map.removeLayer(this.routeLayer);
                        }

                        this.routeLayer = L.geoJSON(route.geometry, {
                            style: {
                                color: '#082D00',
                                weight: 5,
                                opacity: 0.85,
                                lineJoin: 'round',
                                lineCap: 'round'
                            }
                        }).addTo(this.map);

                        this.map.fitBounds(this.routeLayer.getBounds(), { padding: [40, 40] });

                    } catch (err) {
                        console.warn('Routing service error:', err);
                        this.routingError = 'The live road routing engine is temporarily unavailable or cannot find a road network connection between these points. You can view the direct directions in Google Maps below.';
                        if (this.routeLayer) {
                            this.map.removeLayer(this.routeLayer);
                            this.routeLayer = null;
                        }
                    } finally {
                        this.isCalculating = false;
                    }
                },

                get externalGoogleMapsUrl() {
                    if (!this.startCoords || !this.destCoords) return null;
                    return `https://www.google.com/maps/dir/?api=1&origin=${this.startCoords[0]},${this.startCoords[1]}&destination=${this.destCoords[0]},${this.destCoords[1]}&travelmode=${this.travelMode}`;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
