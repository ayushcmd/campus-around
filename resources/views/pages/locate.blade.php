<x-app-layout :nav-theme="'light'">
    <x-slot name="title">Locate & Route — Spatial Directions & Live Campus Map (IIT Patna)</x-slot>

    <!-- Pre-load verified place datasets in JSON script tags to avoid HTML attribute quote truncation -->
    <script id="locate-inside-data" type="application/json">
        {!! json_encode($insidePlaces, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script id="locate-outside-data" type="application/json">
        {!! json_encode($outsidePlaces, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script id="locate-start-data" type="application/json">
        {!! json_encode($initialStart, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script id="locate-dest-data" type="application/json">
        {!! json_encode($initialDest, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <!-- Component Logic Definition Registered Globally -->
    <script>
        function locateApp() {
            // Helper to parse JSON from script tags safely
            function parseJsonFromScript(id) {
                try {
                    const el = document.getElementById(id);
                    return el && el.textContent ? JSON.parse(el.textContent) : null;
                } catch (e) {
                    console.error('Failed to parse ' + id, e);
                    return null;
                }
            }

            const rawInside = parseJsonFromScript('locate-inside-data') || [];
            const rawOutside = parseJsonFromScript('locate-outside-data') || [];
            const rawStart = parseJsonFromScript('locate-start-data');
            const rawDest = parseJsonFromScript('locate-dest-data');

            return {
                insidePlaces: rawInside,
                outsidePlaces: rawOutside,
                allPlaces: [...rawInside, ...rawOutside],

                // Selection state
                startPlace: rawStart,
                destPlace: rawDest,
                startCoords: rawStart && rawStart.latitude && rawStart.longitude ? [rawStart.latitude, rawStart.longitude] : null,
                destCoords: rawDest && rawDest.latitude && rawDest.longitude ? [rawDest.latitude, rawDest.longitude] : null,
                isGpsStart: false,

                // Searchable dropdown state
                startDropdownOpen: false,
                destDropdownOpen: false,
                startSearch: '',
                destSearch: '',

                // Navigation & routing
                travelMode: 'driving', // 'driving', 'walking', 'cycling'
                isCalculating: false,
                routeData: null,
                routingError: null,
                unmappedNotice: null,
                modeNotice: null,
                showInstructions: true,

                // Geolocation state (strictly requested on-demand only)
                showGpsModal: false,
                gpsStatus: 'idle', // 'idle', 'requesting', 'active', 'denied', 'unavailable'
                gpsCoords: null,
                gpsAccuracy: null,
                gpsMessage: null,

                // Leaflet Map Elements
                map: null,
                tileLayer: null,
                startMarker: null,
                destMarker: null,
                gpsMarker: null,
                gpsCircle: null,
                routeLayer: null,
                placesLayerGroup: null,
                showCampusMarkers: true,
                mapLoaded: false,
                tileError: false,

                init() {
                    // Check initial destination coordinate validity
                    if (this.destPlace && (!this.destPlace.latitude || !this.destPlace.longitude)) {
                        this.unmappedNotice = `Note: Coordinates for "${this.destPlace.name}" are currently being mapped. Navigation directions are temporarily unavailable for this place.`;
                    }

                    const launchMap = () => {
                        this.initMap();
                        if (this.startCoords && this.destCoords && this.travelMode === 'driving') {
                            this.calculateRoute();
                        }
                    };

                    // Robust initialization across Alpine lifecycle
                    if (typeof this.$nextTick === 'function') {
                        this.$nextTick(launchMap);
                    } else {
                        setTimeout(launchMap, 50);
                    }

                    // Safety net fallback to ensure map always mounts
                    setTimeout(() => {
                        if (!this.map) {
                            launchMap();
                        }
                    }, 200);

                    // Handle window resize for Leaflet map invalidateSize
                    window.addEventListener('resize', () => {
                        if (this.map) {
                            this.map.invalidateSize();
                        }
                    });
                },

                initMap() {
                    const container = document.getElementById('locate-map');
                    if (!container) return;

                    // Clean up any existing map instance to prevent duplicate initialization
                    if (this.map) {
                        this.map.remove();
                        this.map = null;
                    }

                    // Default center: IIT Patna Admin & Central Campus
                    const defaultCenter = [25.5357, 84.8512];
                    const initialCenter = this.destCoords || this.startCoords || defaultCenter;
                    const initialZoom = (this.destCoords || this.startCoords) ? 16 : 15;

                    try {
                        this.map = L.map('locate-map', {
                            center: initialCenter,
                            zoom: initialZoom,
                            scrollWheelZoom: false,
                            zoomControl: true,
                        });

                        // Standard OpenStreetMap tile layer with full attribution
                        this.tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors &copy; <a href="http://project-osrm.org/" target="_blank" rel="noopener">OSRM</a>'
                        }).addTo(this.map);

                        this.tileLayer.on('tileerror', () => {
                            this.tileError = true;
                        });

                        this.tileLayer.on('tileload', () => {
                            this.tileError = false;
                        });

                        this.mapLoaded = true;

                        // Add subtle interactive markers for all mapped places
                        this.renderPlaceMarkers();

                        // Map click handler to set points interactively
                        this.map.on('click', (e) => {
                            this.handleMapClick(e.latlng.lat, e.latlng.lng);
                        });

                        // Ensure tiles render completely after layout settle
                        setTimeout(() => {
                            if (this.map) this.map.invalidateSize();
                        }, 250);

                        this.updateMarkers();

                    } catch (err) {
                        console.error('Leaflet initialization error:', err);
                    }
                },

                renderPlaceMarkers() {
                    if (!this.map) return;
                    if (this.placesLayerGroup) {
                        this.map.removeLayer(this.placesLayerGroup);
                    }

                    const markers = [];
                    const mappedPlaces = this.allPlaces.filter(p => p.latitude && p.longitude);

                    mappedPlaces.forEach(p => {
                        const isInside = p.is_campus;
                        const markerDot = L.circleMarker([p.latitude, p.longitude], {
                            radius: 6,
                            fillColor: isInside ? '#082D00' : '#2563EB',
                            color: '#FFFFFF',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.85
                        });

                        const popupContent = `
                            <div class="p-1 max-w-[220px] text-xs">
                                <div class="font-black text-[#082D00] text-sm leading-tight">${p.name}</div>
                                <div class="text-[10px] uppercase font-bold text-[#64705D] mt-0.5">${p.category} &bull; ${isInside ? 'IIT Patna' : 'Bihta'}</div>
                                <p class="text-[11px] text-neutral-600 mt-1 line-clamp-2 leading-snug">${p.tagline || p.description || ''}</p>
                                <div class="mt-2.5 pt-2 border-t border-neutral-200 flex flex-col gap-1.5">
                                    <button onclick="window.__setLocatePoint('${p.id}', 'start')" class="w-full text-left px-2 py-1 bg-[#082D00] text-white text-[11px] font-bold rounded-lg hover:bg-[#134504] transition-colors">
                                        &bull; Set as Starting Point
                                    </button>
                                    <button onclick="window.__setLocatePoint('${p.id}', 'dest')" class="w-full text-left px-2 py-1 bg-[#9BE96B] text-[#082D00] text-[11px] font-bold rounded-lg hover:bg-[#88d959] transition-colors">
                                        &bull; Set as Destination
                                    </button>
                                </div>
                            </div>
                        `;

                        markerDot.bindPopup(popupContent, { maxWidth: 240 });
                        markers.push(markerDot);
                    });

                    this.placesLayerGroup = L.layerGroup(markers);
                    if (this.showCampusMarkers) {
                        this.placesLayerGroup.addTo(this.map);
                    }

                    // Expose popup callback hook to global window
                    window.__setLocatePoint = (placeId, target) => {
                        const found = this.allPlaces.find(x => x.id === placeId);
                        if (!found) return;
                        if (target === 'start') {
                            this.selectStart(found);
                        } else {
                            this.selectDest(found);
                        }
                        if (this.map) this.map.closePopup();
                    };
                },

                toggleCampusMarkers() {
                    this.showCampusMarkers = !this.showCampusMarkers;
                    if (!this.map || !this.placesLayerGroup) return;
                    if (this.showCampusMarkers) {
                        this.map.addLayer(this.placesLayerGroup);
                    } else {
                        this.map.removeLayer(this.placesLayerGroup);
                    }
                },

                handleMapClick(lat, lng) {
                    if (!this.startCoords) {
                        this.startCoords = [lat, lng];
                        this.startPlace = {
                            id: 'custom-start',
                            name: `Map Point (${lat.toFixed(4)}°N, ${lng.toFixed(4)}°E)`,
                            category: 'Selected Location',
                            is_campus: true,
                            latitude: lat,
                            longitude: lng
                        };
                        this.isGpsStart = false;
                        this.unmappedNotice = null;
                        this.updateMarkers();
                    } else if (!this.destCoords) {
                        this.destCoords = [lat, lng];
                        this.destPlace = {
                            id: 'custom-dest',
                            name: `Map Point (${lat.toFixed(4)}°N, ${lng.toFixed(4)}°E)`,
                            category: 'Selected Location',
                            is_campus: true,
                            latitude: lat,
                            longitude: lng
                        };
                        this.unmappedNotice = null;
                        this.updateMarkers();
                        if (this.travelMode === 'driving') {
                            this.calculateRoute();
                        }
                    }
                },

                // Searchable dropdown filtered getters
                get filteredStartInside() {
                    const q = this.startSearch.toLowerCase().trim();
                    if (!q) return this.insidePlaces;
                    return this.insidePlaces.filter(p => 
                        (p.name && p.name.toLowerCase().includes(q)) ||
                        (p.category && p.category.toLowerCase().includes(q)) ||
                        (p.address && p.address.toLowerCase().includes(q))
                    );
                },

                get filteredStartOutside() {
                    const q = this.startSearch.toLowerCase().trim();
                    if (!q) return this.outsidePlaces;
                    return this.outsidePlaces.filter(p => 
                        (p.name && p.name.toLowerCase().includes(q)) ||
                        (p.category && p.category.toLowerCase().includes(q)) ||
                        (p.address && p.address.toLowerCase().includes(q))
                    );
                },

                get filteredDestInside() {
                    const q = this.destSearch.toLowerCase().trim();
                    if (!q) return this.insidePlaces;
                    return this.insidePlaces.filter(p => 
                        (p.name && p.name.toLowerCase().includes(q)) ||
                        (p.category && p.category.toLowerCase().includes(q)) ||
                        (p.address && p.address.toLowerCase().includes(q))
                    );
                },

                get filteredDestOutside() {
                    const q = this.destSearch.toLowerCase().trim();
                    if (!q) return this.outsidePlaces;
                    return this.outsidePlaces.filter(p => 
                        (p.name && p.name.toLowerCase().includes(q)) ||
                        (p.category && p.category.toLowerCase().includes(q)) ||
                        (p.address && p.address.toLowerCase().includes(q))
                    );
                },

                selectStart(place) {
                    this.startDropdownOpen = false;
                    this.startSearch = '';
                    this.isGpsStart = false;

                    if (!place.latitude || !place.longitude) {
                        this.unmappedNotice = `Coordinates for "${place.name}" are not available yet. Please pick another facility for directions.`;
                        this.startPlace = place;
                        this.startCoords = null;
                        this.clearRoute();
                        return;
                    }

                    this.unmappedNotice = null;
                    this.startPlace = place;
                    this.startCoords = [place.latitude, place.longitude];
                    this.updateMarkers();

                    if (this.destCoords && this.travelMode === 'driving') {
                        this.calculateRoute();
                    }
                },

                selectDest(place) {
                    this.destDropdownOpen = false;
                    this.destSearch = '';

                    // Check same place conflict
                    if (this.startPlace && this.startPlace.id === place.id && !this.isGpsStart) {
                        this.unmappedNotice = 'Starting point and destination cannot be the same facility. Please pick a different destination.';
                        return;
                    }

                    if (!place.latitude || !place.longitude) {
                        this.unmappedNotice = `Coordinates for "${place.name}" are not available yet. Please pick another facility for directions.`;
                        this.destPlace = place;
                        this.destCoords = null;
                        this.clearRoute();
                        return;
                    }

                    this.unmappedNotice = null;
                    this.destPlace = place;
                    this.destCoords = [place.latitude, place.longitude];
                    this.updateMarkers();

                    if (this.startCoords && this.travelMode === 'driving') {
                        this.calculateRoute();
                    }
                },

                swapPoints() {
                    if (this.isGpsStart) {
                        this.unmappedNotice = 'Current GPS location can only be used as a Starting Point.';
                        return;
                    }

                    const tempPlace = this.startPlace;
                    this.startPlace = this.destPlace;
                    this.destPlace = tempPlace;

                    const tempCoords = this.startCoords;
                    this.startCoords = this.destCoords;
                    this.destCoords = tempCoords;

                    this.unmappedNotice = null;
                    this.updateMarkers();

                    if (this.startCoords && this.destCoords && this.travelMode === 'driving') {
                        this.calculateRoute();
                    }
                },

                clearStart() {
                    this.startPlace = null;
                    this.startCoords = null;
                    this.isGpsStart = false;
                    this.clearRoute();
                    this.updateMarkers();
                },

                clearDest() {
                    this.destPlace = null;
                    this.destCoords = null;
                    this.clearRoute();
                    this.updateMarkers();
                },

                // Geolocation Handler — strictly on explicit user action
                requestCurrentLocation() {
                    this.showGpsModal = true;
                },

                proceedWithGeolocation() {
                    this.showGpsModal = false;
                    this.gpsStatus = 'requesting';
                    this.gpsMessage = 'Requesting browser location permission...';

                    if (!navigator.geolocation) {
                        this.gpsStatus = 'unavailable';
                        this.gpsMessage = 'Geolocation is not supported by your browser. Please select a starting facility manually.';
                        return;
                    }

                    // Security check: Geolocation requires HTTPS or localhost
                    const isSecure = window.location.protocol === 'https:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
                    if (!isSecure) {
                        this.gpsStatus = 'unavailable';
                        this.gpsMessage = 'Browser geolocation requires a secure HTTPS connection or localhost. Please select a facility manually.';
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            const accuracy = Math.round(pos.coords.accuracy || 15);

                            this.gpsCoords = [lat, lng];
                            this.gpsAccuracy = accuracy;
                            this.startCoords = [lat, lng];
                            this.isGpsStart = true;
                            this.startPlace = null;
                            this.gpsStatus = 'active';
                            this.gpsMessage = `Located at ${lat.toFixed(4)}°N, ${lng.toFixed(4)}°E (Accuracy: ~${accuracy}m)`;
                            this.unmappedNotice = null;

                            this.updateMarkers();
                            if (this.destCoords && this.travelMode === 'driving') {
                                this.calculateRoute();
                            }
                        },
                        (err) => {
                            if (err.code === err.PERMISSION_DENIED) {
                                this.gpsStatus = 'denied';
                                this.gpsMessage = 'Location access was denied. You can manually choose any campus facility or click on the map.';
                            } else if (err.code === err.TIMEOUT) {
                                this.gpsStatus = 'unavailable';
                                this.gpsMessage = 'Location request timed out. Please try again or choose a campus facility manually.';
                            } else {
                                this.gpsStatus = 'unavailable';
                                this.gpsMessage = 'Unable to determine GPS location. Please select a facility from the list.';
                            }
                        },
                        { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
                    );
                },

                setMode(mode) {
                    this.travelMode = mode;
                    this.routingError = null;

                    if (mode === 'walking') {
                        this.modeNotice = 'Pedestrian Routing Note: The free public OSRM server only provides road driving directions and does not include campus footpaths. We do not provide fabricated walking directions. You can view the road route in Driving mode or open Google Maps for turn-by-turn walking guidance.';
                        this.clearRouteLayerOnly();
                    } else if (mode === 'cycling') {
                        this.modeNotice = 'Cycling Routing Note: Dedicated cycle paths are not differentiated on the public OSRM demonstration server. Road network directions are available in Driving mode, or open Google Maps for cycling navigation.';
                        this.clearRouteLayerOnly();
                    } else {
                        this.modeNotice = null;
                        if (this.startCoords && this.destCoords) {
                            this.calculateRoute();
                        }
                    }
                },

                async calculateRoute() {
                    if (!this.startCoords || !this.destCoords) {
                        this.routingError = 'Please select both a valid starting point and a destination with verified coordinates.';
                        return;
                    }

                    if (this.travelMode !== 'driving') {
                        // Driving is the genuine profile supported by the public OSRM demonstration server
                        return;
                    }

                    this.isCalculating = true;
                    this.routingError = null;

                    const startLng = this.startCoords[1];
                    const startLat = this.startCoords[0];
                    const destLng = this.destCoords[1];
                    const destLat = this.destCoords[0];

                    // Free public OSRM road routing endpoint
                    const url = `https://router.project-osrm.org/route/v1/driving/${startLng},${startLat};${destLng},${destLat}?overview=full&geometries=geojson&steps=true`;

                    try {
                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), 10000);

                        const res = await fetch(url, { signal: controller.signal });
                        clearTimeout(timeoutId);

                        if (!res.ok) {
                            throw new Error(`Routing provider responded with status ${res.status}`);
                        }

                        const data = await res.json();

                        if (data.code !== 'Ok' || !data.routes || data.routes.length === 0) {
                            throw new Error('No road connection could be found between these coordinates on the road network.');
                        }

                        const route = data.routes[0];
                        const distanceMeters = route.distance;
                        const durationSeconds = route.duration;

                        // Parse turn-by-turn maneuvers safely from provider
                        const rawSteps = (route.legs && route.legs[0] && route.legs[0].steps) ? route.legs[0].steps : [];
                        const formattedSteps = rawSteps.map((s, idx) => {
                            let text = s.name ? `Proceed onto ${s.name}` : 'Continue straight';
                            if (s.maneuver) {
                                const type = s.maneuver.type;
                                const modifier = s.maneuver.modifier;
                                if (type === 'depart') {
                                    text = s.name ? `Head ${modifier || ''} on ${s.name}` : 'Start navigation';
                                } else if (type === 'arrive') {
                                    text = 'Arrive at destination';
                                } else if (type === 'turn') {
                                    text = `Turn ${modifier || ''} ${s.name ? 'onto ' + s.name : ''}`.trim();
                                } else if (type === 'roundabout') {
                                    text = `Enter roundabout and take exit ${s.maneuver.exit || 1}`;
                                } else if (type === 'end of road') {
                                    text = `At the end of the road, turn ${modifier || 'straight'}`;
                                } else if (type === 'fork') {
                                    text = `Keep ${modifier || 'left'} at fork`;
                                } else {
                                    text = `${type} ${modifier || ''} ${s.name ? 'onto ' + s.name : ''}`.trim();
                                }
                            }

                            return {
                                id: idx,
                                instruction: text,
                                distance: Math.round(s.distance),
                                modifier: s.maneuver ? s.maneuver.modifier : null,
                                type: s.maneuver ? s.maneuver.type : null
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
                            provider: 'OSRM (OpenStreetMap Road Network)',
                            steps: formattedSteps.length > 0 ? formattedSteps : [
                                { id: 0, instruction: 'Follow connecting campus road network to destination', distance: Math.round(distanceMeters) }
                            ]
                        };

                        // Render polyline on Leaflet map
                        this.renderRoutePolyline(route.geometry);

                    } catch (err) {
                        console.warn('Routing service error:', err);
                        this.clearRouteLayerOnly();
                        this.routingError = 'Road route could not be calculated via the public routing service (Network or service limitation). You can view the direct route in Google Maps below.';
                    } finally {
                        this.isCalculating = false;
                    }
                },

                renderRoutePolyline(geometry) {
                    if (!this.map) return;
                    if (this.routeLayer) {
                        this.map.removeLayer(this.routeLayer);
                        this.routeLayer = null;
                    }

                    this.routeLayer = L.geoJSON(geometry, {
                        style: {
                            color: '#082D00',
                            weight: 5,
                            opacity: 0.9,
                            lineJoin: 'round',
                            lineCap: 'round',
                            dashArray: null
                        }
                    }).addTo(this.map);

                    const bounds = this.routeLayer.getBounds();
                    if (bounds.isValid()) {
                        this.map.fitBounds(bounds, { padding: [50, 50] });
                    }
                },

                clearRoute() {
                    this.routeData = null;
                    this.routingError = null;
                    this.clearRouteLayerOnly();
                },

                clearRouteLayerOnly() {
                    if (this.map && this.routeLayer) {
                        this.map.removeLayer(this.routeLayer);
                        this.routeLayer = null;
                    }
                },

                updateMarkers() {
                    if (!this.map) return;

                    // Clean existing markers
                    if (this.startMarker) {
                        this.map.removeLayer(this.startMarker);
                        this.startMarker = null;
                    }
                    if (this.destMarker) {
                        this.map.removeLayer(this.destMarker);
                        this.destMarker = null;
                    }
                    if (this.gpsMarker) {
                        this.map.removeLayer(this.gpsMarker);
                        this.gpsMarker = null;
                    }
                    if (this.gpsCircle) {
                        this.map.removeLayer(this.gpsCircle);
                        this.gpsCircle = null;
                    }

                    // 1. Start Marker
                    if (this.startCoords) {
                        if (this.isGpsStart) {
                            // Pulsing GPS dot
                            const gpsIcon = L.divIcon({
                                className: 'gps-pin',
                                html: `
                                    <div class="relative flex items-center justify-center w-8 h-8">
                                        <span class="absolute w-8 h-8 rounded-full bg-[#082D00]/30 animate-ping"></span>
                                        <span class="relative w-5 h-5 rounded-full bg-[#082D00] border-2 border-[#9BE96B] flex items-center justify-center shadow-md">
                                            <span class="w-2 h-2 rounded-full bg-[#9BE96B]"></span>
                                        </span>
                                    </div>
                                `,
                                iconSize: [32, 32],
                                iconAnchor: [16, 16],
                                popupAnchor: [0, -16]
                            });

                            this.gpsMarker = L.marker(this.startCoords, { icon: gpsIcon }).addTo(this.map);
                            this.gpsMarker.bindPopup(`<strong>My Current Location (GPS)</strong><br><span class="text-xs text-neutral-600">Accuracy: ~${this.gpsAccuracy || 15}m</span>`);

                            if (this.gpsAccuracy && this.gpsAccuracy > 10) {
                                this.gpsCircle = L.circle(this.startCoords, {
                                    radius: this.gpsAccuracy,
                                    color: '#082D00',
                                    fillColor: '#9BE96B',
                                    fillOpacity: 0.15,
                                    weight: 1.5
                                }).addTo(this.map);
                            }
                        } else {
                            // Standard Start "A" Pin
                            const startIcon = L.divIcon({
                                className: 'start-pin',
                                html: `
                                    <div style="background-color: #082D00; color: #FFFFFF; width: 34px; height: 34px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; border: 2.5px solid #FFFFFF; box-shadow: 0 4px 12px rgba(0,0,0,0.35); font-weight: 900; font-size: 13px;">
                                        A
                                    </div>
                                `,
                                iconSize: [34, 34],
                                iconAnchor: [17, 17],
                                popupAnchor: [0, -17]
                            });

                            this.startMarker = L.marker(this.startCoords, { icon: startIcon }).addTo(this.map);
                            const name = this.startPlace ? this.startPlace.name : 'Starting Point';
                            this.startMarker.bindPopup(`<strong>${name}</strong><br><span class="text-xs text-neutral-600">Starting Point</span>`);
                        }
                    }

                    // 2. Destination Marker "B"
                    if (this.destCoords) {
                        const destIcon = L.divIcon({
                            className: 'dest-pin',
                            html: `
                                <div style="background-color: #9BE96B; color: #082D00; width: 34px; height: 34px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; border: 2.5px solid #082D00; box-shadow: 0 4px 12px rgba(0,0,0,0.25); font-weight: 900; font-size: 13px;">
                                    B
                                </div>
                            `,
                            iconSize: [34, 34],
                            iconAnchor: [17, 17],
                            popupAnchor: [0, -17]
                        });

                        this.destMarker = L.marker(this.destCoords, { icon: destIcon }).addTo(this.map);
                        const name = this.destPlace ? this.destPlace.name : 'Destination';
                        this.destMarker.bindPopup(`<strong>${name}</strong><br><span class="text-xs text-neutral-600">Destination</span>`);
                    }

                    // If not calculating a route, fit bounds to points
                    if (!this.routeLayer) {
                        this.fitPoints();
                    }
                },

                fitPoints() {
                    if (!this.map) return;
                    if (this.startCoords && this.destCoords) {
                        const bounds = L.latLngBounds([this.startCoords, this.destCoords]);
                        if (bounds.isValid()) {
                            this.map.fitBounds(bounds, { padding: [60, 60] });
                        }
                    } else if (this.startCoords) {
                        this.map.setView(this.startCoords, 16);
                    } else if (this.destCoords) {
                        this.map.setView(this.destCoords, 16);
                    }
                },

                recenterMap() {
                    if (!this.map) return;

                    if (this.routeLayer && this.routeLayer.getBounds().isValid()) {
                        this.map.fitBounds(this.routeLayer.getBounds(), { padding: [50, 50] });
                    } else if (this.startCoords && this.destCoords) {
                        this.fitPoints();
                    } else if (this.destCoords) {
                        this.map.setView(this.destCoords, 16);
                    } else if (this.startCoords) {
                        this.map.setView(this.startCoords, 16);
                    } else if (this.gpsCoords) {
                        this.map.setView(this.gpsCoords, 16);
                    } else {
                        // IIT Patna Central Campus default
                        this.map.setView([25.5357, 84.8512], 15);
                    }
                },

                get externalGoogleMapsUrl() {
                    if (!this.destCoords) return null;
                    const destParam = `${this.destCoords[0]},${this.destCoords[1]}`;
                    const originParam = this.startCoords ? `&origin=${this.startCoords[0]},${this.startCoords[1]}` : '';
                    const modeMap = {
                        driving: 'driving',
                        walking: 'walking',
                        cycling: 'bicycling'
                    };
                    const travelMode = modeMap[this.travelMode] || 'driving';
                    return `https://www.google.com/maps/dir/?api=1${originParam}&destination=${destParam}&travelmode=${travelMode}`;
                }
            };
        }
        window.locateApp = locateApp;
        if (window.Alpine) {
            window.Alpine.data('locateApp', locateApp);
        } else {
            document.addEventListener('alpine:init', () => {
                if (window.Alpine) window.Alpine.data('locateApp', locateApp);
            });
        }
    </script>

    <div x-data="locateApp()" class="py-6 sm:py-10">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="mb-6 sm:mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#082D00] text-[#9BE96B] text-xs font-black uppercase tracking-wider mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#9BE96B] animate-ping"></span>
                        Spatial Navigation
                    </div>
                    <h1 class="text-3xl sm:text-5xl font-black uppercase tracking-tight text-[#082D00] leading-none">
                        WHERE DO YOU WANT TO GO?
                    </h1>
                    <p class="text-sm sm:text-base text-[#64705D] font-medium mt-1.5 max-w-2xl">
                        Compute real road directions across IIT Patna campus and Bihta neighborhood. Select verified campus buildings, nearby dhabas, cafes, or use your GPS position.
                    </p>
                </div>

                <div class="shrink-0 flex items-center gap-2">
                    <span class="text-xs font-extrabold text-[#082D00] bg-white px-3.5 py-1.5 rounded-full border border-[#E5E7E1] shadow-2xs flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#082D00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                        <span>OpenStreetMap &bull; OSRM Engine</span>
                    </span>
                </div>
            </div>

            <!-- Main Layout: Desktop 2-Col (Controls 5 cols, Map 7 cols) / Mobile Stacked -->
            <div id="locate-controls" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- LEFT NAVIGATION CONTROL PANEL (5 cols on desktop) -->
                <div class="lg:col-span-5 space-y-5">
                    
                    <!-- Selection Card -->
                    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-[#E5E7E1] shadow-sm space-y-5">
                        
                        <!-- Travel Mode Buttons -->
                        <div>
                            <div class="text-[11px] font-black uppercase tracking-wider text-[#64705D] mb-1.5">Select Travel Mode</div>
                            <div class="flex items-center justify-between p-1 bg-[#F7F7F2] rounded-full border border-[#E5E7E1]">
                                <button type="button" 
                                        @click="setMode('driving')"
                                        :class="travelMode === 'driving' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#64705D] hover:text-[#082D00]'"
                                        class="flex-1 py-2 px-3 rounded-full text-xs font-black transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                                    <span>Driving</span>
                                </button>
                                <button type="button" 
                                        @click="setMode('walking')"
                                        :class="travelMode === 'walking' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#64705D] hover:text-[#082D00]'"
                                        class="flex-1 py-2 px-3 rounded-full text-xs font-black transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><path d="m9 20 3-6 3 6"/><path d="m6 8 6 2 6-2"/><path d="M12 10v4"/></svg>
                                    <span>Walking</span>
                                </button>
                                <button type="button" 
                                        @click="setMode('cycling')"
                                        :class="travelMode === 'cycling' ? 'bg-[#082D00] text-white shadow-xs' : 'text-[#64705D] hover:text-[#082D00]'"
                                        class="flex-1 py-2 px-3 rounded-full text-xs font-black transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="18.5" cy="17.5" r="3.5"/><circle cx="5.5" cy="17.5" r="3.5"/><circle cx="15" cy="5" r="1"/><path d="M12 17.5V14l-3-3 4-3 2 3h2"/></svg>
                                    <span>Cycling</span>
                                </button>
                            </div>
                        </div>

                        <!-- 1. Starting Location Field (Searchable Combobox) -->
                        <div class="relative" @click.outside="startDropdownOpen = false">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-black uppercase tracking-wider text-[#082D00]">
                                    Starting Point
                                </label>
                                <!-- Explicit Current Location Button (Prompts GPS permission only when clicked) -->
                                <button type="button" 
                                        @click="requestCurrentLocation()"
                                        class="text-[11px] font-black text-[#082D00] hover:text-[#134504] underline flex items-center gap-1 cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-[#082D00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>Use my current location</span>
                                </button>
                            </div>

                            <!-- Trigger Box -->
                            <div @click="startDropdownOpen = !startDropdownOpen; if(startDropdownOpen) $nextTick(() => $refs.startSearchInput.focus())"
                                 class="w-full bg-[#F7F7F2] text-[#082D00] font-semibold rounded-2xl px-4 py-3 border border-[#E5E7E1] hover:border-[#082D00] focus:border-[#082D00] cursor-pointer transition-all text-sm flex items-center justify-between gap-2 shadow-2xs">
                                <div class="flex items-center gap-2.5 truncate">
                                    <template x-if="startPlace">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="w-6 h-6 rounded-full bg-[#082D00] text-white flex items-center justify-center text-xs font-black shrink-0">A</span>
                                            <span class="font-extrabold text-[#082D00] truncate" x-text="startPlace.name"></span>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-neutral-200 text-[#082D00] shrink-0" x-text="startPlace.category"></span>
                                        </div>
                                    </template>
                                    <template x-if="!startPlace && isGpsStart">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="w-6 h-6 rounded-full bg-[#082D00] text-[#9BE96B] flex items-center justify-center text-xs font-black shrink-0">📍</span>
                                            <span class="font-extrabold text-[#082D00] truncate">My Current Location (GPS)</span>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#9BE96B]/30 text-[#082D00] shrink-0" x-text="'~' + (gpsAccuracy || 15) + 'm'"></span>
                                        </div>
                                    </template>
                                    <template x-if="!startPlace && !isGpsStart">
                                        <span class="text-[#64705D] font-medium">-- Choose starting location --</span>
                                    </template>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <template x-if="startPlace || isGpsStart">
                                        <button type="button" @click.stop="clearStart()" class="p-1 rounded-full text-neutral-400 hover:text-neutral-700 hover:bg-neutral-200 transition-colors" title="Clear Start">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </template>
                                    <svg class="w-4 h-4 text-[#64705D] transition-transform duration-200" :class="startDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>

                            <!-- Searchable Dropdown Overlay Menu -->
                            <div x-show="startDropdownOpen" 
                                 x-cloak
                                 class="absolute z-40 left-0 right-0 mt-2 bg-white rounded-2xl border border-[#E5E7E1] shadow-2xl overflow-hidden max-h-80 flex flex-col">
                                <div class="p-2.5 border-b border-[#E5E7E1] bg-[#F7F7F2] sticky top-0 z-10">
                                    <input x-ref="startSearchInput"
                                           type="text" 
                                           x-model="startSearch"
                                           placeholder="Type to filter campus building, dhaba, cafe..."
                                           class="w-full bg-white text-xs font-bold rounded-xl px-3 py-2 border border-[#E5E7E1] focus:border-[#082D00] focus:outline-none">
                                </div>

                                <div class="overflow-y-auto flex-1 p-2 divide-y divide-neutral-100">
                                    <!-- Current GPS option if located -->
                                    <template x-if="gpsCoords">
                                        <div class="py-1">
                                            <button type="button" 
                                                    @click="startCoords = gpsCoords; isGpsStart = true; startPlace = null; startDropdownOpen = false; updateMarkers()"
                                                    class="w-full text-left p-2.5 rounded-xl hover:bg-[#9BE96B]/20 transition-colors flex items-center justify-between text-xs cursor-pointer">
                                                <div class="flex items-center gap-2 font-bold text-[#082D00]">
                                                    <span class="w-5 h-5 rounded-full bg-[#082D00] text-[#9BE96B] flex items-center justify-center text-[10px]">📍</span>
                                                    <span>My Current Location (GPS)</span>
                                                </div>
                                                <span class="text-[10px] text-[#64705D]" x-text="'±' + (gpsAccuracy || 15) + 'm'"></span>
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Inside Campus Section -->
                                    <div class="py-1.5">
                                        <div class="px-2 py-1 text-[10px] font-black uppercase tracking-wider text-[#64705D] bg-neutral-50 rounded-lg">
                                            🏛️ Inside Campus Facilities
                                        </div>
                                        <div class="space-y-0.5 mt-1">
                                            <template x-for="p in filteredStartInside" :key="p.id">
                                                <button type="button" 
                                                        @click="selectStart(p)"
                                                        class="w-full text-left p-2 rounded-xl hover:bg-[#F7F7F2] transition-colors flex items-center justify-between gap-2 text-xs cursor-pointer">
                                                    <div class="truncate">
                                                        <div class="font-extrabold text-[#082D00] truncate" x-text="p.name"></div>
                                                        <div class="text-[10px] text-[#64705D]" x-text="p.category"></div>
                                                    </div>
                                                    <div class="shrink-0">
                                                        <template x-if="p.latitude && p.longitude">
                                                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block" title="Coordinates verified"></span>
                                                        </template>
                                                        <template x-if="!p.latitude || !p.longitude">
                                                            <span class="text-[9px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded">No coords</span>
                                                        </template>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Outside Campus Section -->
                                    <div class="py-1.5">
                                        <div class="px-2 py-1 text-[10px] font-black uppercase tracking-wider text-[#64705D] bg-neutral-50 rounded-lg">
                                            🏪 Outside Campus &amp; Bihta
                                        </div>
                                        <div class="space-y-0.5 mt-1">
                                            <template x-for="p in filteredStartOutside" :key="p.id">
                                                <button type="button" 
                                                        @click="selectStart(p)"
                                                        class="w-full text-left p-2 rounded-xl hover:bg-[#F7F7F2] transition-colors flex items-center justify-between gap-2 text-xs cursor-pointer">
                                                    <div class="truncate">
                                                        <div class="font-extrabold text-[#082D00] truncate" x-text="p.name"></div>
                                                        <div class="text-[10px] text-[#64705D]" x-text="p.category"></div>
                                                    </div>
                                                    <div class="shrink-0">
                                                        <template x-if="p.latitude && p.longitude">
                                                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block" title="Coordinates verified"></span>
                                                        </template>
                                                        <template x-if="!p.latitude || !p.longitude">
                                                            <span class="text-[9px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded">No coords</span>
                                                        </template>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- GPS Status / Info Message -->
                            <div x-show="gpsMessage" x-cloak class="mt-2 text-xs p-2.5 rounded-xl border flex items-center justify-between gap-2"
                                 :class="gpsStatus === 'denied' || gpsStatus === 'unavailable' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-[#9BE96B]/20 text-[#082D00] border-[#9BE96B]'">
                                <span class="leading-snug" x-text="gpsMessage"></span>
                                <template x-if="gpsStatus === 'denied' || gpsStatus === 'unavailable'">
                                    <button type="button" @click="proceedWithGeolocation()" class="shrink-0 font-black underline text-xs">Retry</button>
                                </template>
                            </div>
                        </div>

                        <!-- Swap Locations Button (Well-spaced, no negative margins) -->
                        <div class="flex justify-center py-1">
                            <button type="button" 
                                    @click="swapPoints()" 
                                    class="p-2.5 rounded-full bg-[#F7F7F2] border border-[#E5E7E1] text-[#082D00] hover:bg-neutral-200 active:scale-95 transition-all shadow-2xs cursor-pointer"
                                    title="Swap Start and Destination">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m7 15 5 5 5-5"/>
                                    <path d="m7 9 5-5 5 5"/>
                                </svg>
                            </button>
                        </div>

                        <!-- 2. Destination Field (Searchable Combobox) -->
                        <div class="relative" @click.outside="destDropdownOpen = false">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-black uppercase tracking-wider text-[#082D00]">
                                    Destination
                                </label>
                                <span class="text-[11px] font-bold text-[#64705D]">Where are you going?</span>
                            </div>

                            <!-- Trigger Box -->
                            <div @click="destDropdownOpen = !destDropdownOpen; if(destDropdownOpen) $nextTick(() => $refs.destSearchInput.focus())"
                                 class="w-full bg-[#F7F7F2] text-[#082D00] font-semibold rounded-2xl px-4 py-3 border border-[#E5E7E1] hover:border-[#082D00] focus:border-[#082D00] cursor-pointer transition-all text-sm flex items-center justify-between gap-2 shadow-2xs">
                                <div class="flex items-center gap-2.5 truncate">
                                    <template x-if="destPlace">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="w-6 h-6 rounded-full bg-[#9BE96B] text-[#082D00] border border-[#082D00] flex items-center justify-center text-xs font-black shrink-0">B</span>
                                            <span class="font-extrabold text-[#082D00] truncate" x-text="destPlace.name"></span>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-neutral-200 text-[#082D00] shrink-0" x-text="destPlace.category"></span>
                                        </div>
                                    </template>
                                    <template x-if="!destPlace">
                                        <span class="text-[#64705D] font-medium">-- Choose destination --</span>
                                    </template>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <template x-if="destPlace">
                                        <button type="button" @click.stop="clearDest()" class="p-1 rounded-full text-neutral-400 hover:text-neutral-700 hover:bg-neutral-200 transition-colors" title="Clear Destination">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </template>
                                    <svg class="w-4 h-4 text-[#64705D] transition-transform duration-200" :class="destDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>

                            <!-- Searchable Dropdown Overlay Menu -->
                            <div x-show="destDropdownOpen" 
                                 x-cloak
                                 class="absolute z-40 left-0 right-0 mt-2 bg-white rounded-2xl border border-[#E5E7E1] shadow-2xl overflow-hidden max-h-80 flex flex-col">
                                <div class="p-2.5 border-b border-[#E5E7E1] bg-[#F7F7F2] sticky top-0 z-10">
                                    <input x-ref="destSearchInput"
                                           type="text" 
                                           x-model="destSearch"
                                           placeholder="Type to filter campus building, dhaba, cafe..."
                                           class="w-full bg-white text-xs font-bold rounded-xl px-3 py-2 border border-[#E5E7E1] focus:border-[#082D00] focus:outline-none">
                                </div>

                                <div class="overflow-y-auto flex-1 p-2 divide-y divide-neutral-100">
                                    <!-- Inside Campus Section -->
                                    <div class="py-1.5">
                                        <div class="px-2 py-1 text-[10px] font-black uppercase tracking-wider text-[#64705D] bg-neutral-50 rounded-lg">
                                            🏛️ Inside Campus Facilities
                                        </div>
                                        <div class="space-y-0.5 mt-1">
                                            <template x-for="p in filteredDestInside" :key="p.id">
                                                <button type="button" 
                                                        @click="selectDest(p)"
                                                        class="w-full text-left p-2 rounded-xl hover:bg-[#F7F7F2] transition-colors flex items-center justify-between gap-2 text-xs cursor-pointer">
                                                    <div class="truncate">
                                                        <div class="font-extrabold text-[#082D00] truncate" x-text="p.name"></div>
                                                        <div class="text-[10px] text-[#64705D]" x-text="p.category"></div>
                                                    </div>
                                                    <div class="shrink-0">
                                                        <template x-if="p.latitude && p.longitude">
                                                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block" title="Coordinates verified"></span>
                                                        </template>
                                                        <template x-if="!p.latitude || !p.longitude">
                                                            <span class="text-[9px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded">No coords</span>
                                                        </template>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Outside Campus Section -->
                                    <div class="py-1.5">
                                        <div class="px-2 py-1 text-[10px] font-black uppercase tracking-wider text-[#64705D] bg-neutral-50 rounded-lg">
                                            🏪 Outside Campus &amp; Bihta
                                        </div>
                                        <div class="space-y-0.5 mt-1">
                                            <template x-for="p in filteredDestOutside" :key="p.id">
                                                <button type="button" 
                                                        @click="selectDest(p)"
                                                        class="w-full text-left p-2 rounded-xl hover:bg-[#F7F7F2] transition-colors flex items-center justify-between gap-2 text-xs cursor-pointer">
                                                    <div class="truncate">
                                                        <div class="font-extrabold text-[#082D00] truncate" x-text="p.name"></div>
                                                        <div class="text-[10px] text-[#64705D]" x-text="p.category"></div>
                                                    </div>
                                                    <div class="shrink-0">
                                                        <template x-if="p.latitude && p.longitude">
                                                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block" title="Coordinates verified"></span>
                                                        </template>
                                                        <template x-if="!p.latitude || !p.longitude">
                                                            <span class="text-[9px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded">No coords</span>
                                                        </template>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unmapped Notice if chosen place has no coords -->
                        <div x-show="unmappedNotice" x-cloak class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-start gap-2">
                            <span class="text-base shrink-0 leading-none">⚠️</span>
                            <span class="leading-relaxed font-semibold" x-text="unmappedNotice"></span>
                        </div>

                        <!-- Mode Notice when Walking or Cycling is picked -->
                        <div x-show="modeNotice" x-cloak class="p-4 rounded-2xl bg-[#9BE96B]/15 border border-[#9BE96B] text-xs text-[#082D00] space-y-2.5">
                            <div class="flex items-center gap-1.5 font-black uppercase text-[11px]">
                                <span>ℹ️</span>
                                <span x-text="travelMode + ' routing preview'"></span>
                            </div>
                            <p class="leading-relaxed" x-text="modeNotice"></p>
                            <div class="flex flex-wrap gap-2 pt-1">
                                <button type="button" 
                                        @click="setMode('driving')" 
                                        class="px-3 py-1.5 rounded-full bg-[#082D00] text-white text-[11px] font-black hover:bg-[#134504] transition-colors cursor-pointer">
                                    Switch to Driving Road Route &rarr;
                                </button>
                                <template x-if="externalGoogleMapsUrl">
                                    <a :href="externalGoogleMapsUrl" target="_blank" rel="noopener noreferrer"
                                       class="px-3 py-1.5 rounded-full bg-white text-[#082D00] border border-[#082D00] text-[11px] font-black hover:bg-neutral-50 transition-colors inline-flex items-center gap-1">
                                        <span>Open in Google Maps</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </template>
                            </div>
                        </div>

                        <!-- Primary Action Button: Calculate Route -->
                        <div class="pt-2">
                            <button type="button"
                                    @click="calculateRoute()"
                                    :disabled="!startCoords || !destCoords || isCalculating"
                                    class="w-full py-4 px-6 rounded-full bg-[#082D00] text-white font-black text-sm hover:bg-[#134504] active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                                <template x-if="!isCalculating">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#9BE96B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                        <span>Show Route & Directions</span>
                                    </div>
                                </template>
                                <template x-if="isCalculating">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                        <span>Calculating Real Road Route...</span>
                                    </div>
                                </template>
                            </button>
                        </div>

                    </div>

                    <!-- ROUTE SUMMARY & STEP-BY-STEP INSTRUCTIONS -->
                    <template x-if="routeData">
                        <div class="bg-white rounded-3xl p-5 sm:p-6 border border-[#E5E7E1] shadow-sm space-y-4">
                            
                            <!-- Header with distance & duration -->
                            <div class="flex items-center justify-between pb-3 border-b border-[#E5E7E1]">
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-[#64705D]">Trip Summary</span>
                                    <div class="flex items-baseline gap-2 mt-0.5">
                                        <h3 class="text-2xl sm:text-3xl font-black text-[#082D00]" x-text="routeData.formattedDuration"></h3>
                                        <span class="text-sm font-black text-[#64705D]" x-text="'(' + routeData.formattedDistance + ')'"></span>
                                    </div>
                                    <div class="text-[10px] text-[#64705D] mt-0.5 font-bold" x-text="'Routed via ' + routeData.provider"></div>
                                </div>
                                <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase bg-[#082D00] text-[#9BE96B]"
                                      x-text="travelMode">
                                </span>
                            </div>

                            <!-- Turn-by-turn guidance collapsible section -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-black uppercase tracking-wider text-[#082D00]">Turn-by-turn Guidance</span>
                                    <button type="button" @click="showInstructions = !showInstructions" class="text-xs font-bold text-[#64705D] hover:text-[#082D00] cursor-pointer">
                                        <span x-text="showInstructions ? 'Hide steps' : 'Show steps'"></span>
                                    </button>
                                </div>

                                <div x-show="showInstructions" x-transition class="space-y-1.5 max-h-72 overflow-y-auto pr-1">
                                    <template x-for="(step, idx) in routeData.steps" :key="step.id">
                                        <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#F7F7F2] transition-colors text-xs border border-transparent hover:border-[#E5E7E1]">
                                            <span class="w-6 h-6 rounded-full bg-[#F7F7F2] border border-[#E5E7E1] text-[#082D00] font-black flex items-center justify-center shrink-0 text-[11px]" x-text="idx + 1"></span>
                                            <div class="flex-1">
                                                <p class="font-extrabold text-[#082D00] leading-snug" x-text="step.instruction"></p>
                                                <template x-if="step.distance > 0">
                                                    <span class="text-[11px] text-[#64705D] font-bold" x-text="step.distance + ' m'"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- External Navigation Action -->
                            <div class="pt-3 border-t border-[#E5E7E1] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs">
                                <span class="text-[#64705D] font-medium">Open in navigation app:</span>
                                <a :href="externalGoogleMapsUrl" target="_blank" rel="noopener noreferrer" 
                                   class="font-black text-[#082D00] hover:text-[#134504] underline flex items-center gap-1.5">
                                    <span>Open in Google Maps</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        </div>
                    </template>

                    <!-- Error Notice if Routing Fails -->
                    <div x-show="routingError" x-cloak class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-2">
                        <div class="flex items-center gap-2 font-black">
                            <span>⚠️</span>
                            <span>Road route calculation notice</span>
                        </div>
                        <p class="leading-relaxed" x-text="routingError"></p>
                        <template x-if="externalGoogleMapsUrl">
                            <a :href="externalGoogleMapsUrl" target="_blank" rel="noopener noreferrer" 
                               class="inline-flex items-center gap-1 mt-1 font-black underline text-[#082D00]">
                                <span>Open destination in Google Maps &rarr;</span>
                            </a>
                        </template>
                    </div>

                </div>

                <!-- RIGHT MAP PANEL (7 cols on desktop) -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-[#E5E7E1] p-4 shadow-sm relative">
                    
                    <!-- Top Map Control Bar -->
                    <div class="flex flex-wrap items-center justify-between mb-3 px-1 gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#9BE96B] border border-[#082D00]"></span>
                            <span class="text-xs font-black text-[#082D00] uppercase tracking-wider">Live Campus &amp; Bihta Map</span>
                        </div>
                        
                        <div class="flex items-center gap-2 text-xs">
                            <!-- Toggle subtle facility markers -->
                            <button type="button" 
                                    @click="toggleCampusMarkers()" 
                                    class="px-2.5 py-1 rounded-full border border-[#E5E7E1] bg-[#F7F7F2] font-bold text-[#082D00] hover:bg-neutral-200 transition-colors text-[11px] cursor-pointer">
                                <span x-text="showCampusMarkers ? 'Hide Campus Spots' : 'Show Campus Spots'"></span>
                            </button>

                            <!-- Recenter Button -->
                            <button type="button" 
                                    @click="recenterMap()" 
                                    class="px-3 py-1 rounded-full bg-[#082D00] text-[#9BE96B] font-black text-xs hover:bg-[#134504] transition-all cursor-pointer flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m12 8 4 4-4 4M8 12h8"/></svg>
                                <span>Recenter</span>
                            </button>
                        </div>
                    </div>

                    <!-- Map Container with real responsive height -->
                    <div class="relative w-full h-[380px] sm:h-[480px] lg:h-[620px] rounded-2xl overflow-hidden border border-[#E5E7E1]">
                        <div id="locate-map" class="w-full h-full z-10"></div>

                        <!-- Offline / Tile load error notice -->
                        <div x-show="tileError" x-cloak class="absolute top-3 left-3 right-3 z-20 p-2.5 bg-amber-500/95 text-white rounded-xl text-xs font-bold shadow-lg flex items-center justify-between">
                            <span>Map tiles are loading slowly or temporarily unreachable. The map remains interactive.</span>
                            <button type="button" @click="tileError = false" class="underline ml-2 text-[11px]">Dismiss</button>
                        </div>
                    </div>

                    <!-- Map Helper Legend -->
                    <div class="mt-3 px-1 flex flex-wrap items-center justify-between gap-3 text-xs text-[#64705D]">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center gap-1.5 font-extrabold text-[#082D00]">
                                <span class="w-3 h-3 rounded-full bg-[#082D00]"></span> Start Point (A)
                            </span>
                            <span class="inline-flex items-center gap-1.5 font-extrabold text-[#082D00]">
                                <span class="w-3 h-3 rounded-full bg-[#9BE96B] border border-[#082D00]"></span> Destination (B)
                            </span>
                            <span class="inline-flex items-center gap-1.5 font-bold text-[#64705D]">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#082D00]"></span> Campus Spots
                            </span>
                        </div>
                        <span class="text-[11px] font-medium text-[#64705D]">Tip: Click map anywhere or tap a spot to choose points</span>
                    </div>

                </div>

            </div>

        </div>

        <!-- Geolocation Permission Modal Prompt (Only shown when user clicks "Use my current location") -->
        <div x-show="showGpsModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" x-transition>
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-[#E5E7E1] text-center" @click.away="showGpsModal = false">
                <div class="w-14 h-14 rounded-2xl bg-[#9BE96B] text-[#082D00] flex items-center justify-center mx-auto mb-4 font-black text-2xl shadow-xs">
                    📍
                </div>
                <h3 class="text-xl font-black uppercase text-[#082D00]">Use Current Location?</h3>
                <p class="text-xs sm:text-sm text-[#64705D] mt-2 leading-relaxed">
                    CampusAround will request browser GPS permission to detect your position on campus. Your coordinates are used locally for routing and are never stored on any server or analytics.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row gap-2">
                    <button type="button" 
                            @click="proceedWithGeolocation()"
                            class="flex-1 py-3 px-4 rounded-full bg-[#082D00] text-white text-xs font-black hover:bg-[#134504] transition-colors cursor-pointer">
                        Allow &amp; Detect Location
                    </button>
                    <button type="button" 
                            @click="showGpsModal = false"
                            class="flex-1 py-3 px-4 rounded-full bg-[#F7F7F2] text-[#082D00] border border-[#E5E7E1] text-xs font-bold hover:bg-neutral-100 transition-colors cursor-pointer">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
