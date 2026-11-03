<?php

namespace Tests\Feature;

use Tests\TestCase;

class CampusAroundRoutesTest extends TestCase
{
    public function test_homepage_loads_successfully_with_refined_navbar_and_discovery_modes(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('CampusAround');
        $response->assertSee('CampusAround-logo.png');
        $response->assertSee('Explore');
        $response->assertSee('Navigate');
        $response->assertSee('DISCOVERY MODES');
        $response->assertSee('Inside Tour');
        $response->assertSee('Explore campus buildings, facilities, and student spaces.');
        $response->assertSee('Outside Tour');
        $response->assertSee('Discover nearby food, cafes, shops, and useful services.');
        $response->assertSee('Locate');
        $response->assertSee('Choose a destination and explore your route.');
        $response->assertSee('Contribute');
        $response->assertSee('Share a useful place with the campus community.');
    }

    public function test_official_logo_and_branding_elements_are_rendered(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('CampusAround-logo.png');
        $response->assertSee('favicon.ico');
        $response->assertSee('apple-touch-icon');
        $response->assertSee('alt="CampusAround Logo"', false);

        $aboutResponse = $this->get('/about');
        $aboutResponse->assertStatus(200);
        $aboutResponse->assertSee('CampusAround-logo.png');
    }

    public function test_homepage_has_iit_patna_identity_card(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('IIT PATNA');
        $response->assertSee('Indian Institute of Technology Patna');
        $response->assertSee('Bihta, Patna, Bihar, India');
        $response->assertSee('Discover campus facilities, student spaces, and useful places around IIT Patna.');
        $response->assertSee('Explore Campus');
        $response->assertSee('View Campus Map');
        $response->assertSee('images/iit-patna-campus.jpg');
    }

    public function test_inside_tour_page_loads_with_real_campus_facilities(): void
    {
        $response = $this->get('/inside');

        $response->assertStatus(200);
        $response->assertSee('INSIDE CAMPUS');
        $response->assertSee('EXPLORE YOUR CAMPUS.');
        $response->assertSee('Central Library');
        $response->assertSee('Block IX');
        $response->assertSee('Aryabhatta');
        $response->assertSee('Kalam Hostel');
        $response->assertSee('Main Entrance');
        $response->assertSee('inside-campus-map');
    }

    public function test_inside_tour_receives_actual_facility_dataset_with_unique_ids(): void
    {
        $places = \App\Services\PlaceRepository::getInsidePlaces();
        $categories = \App\Services\PlaceRepository::getInsideCategories();

        $this->assertCount(39, $places);

        // Verify unique IDs and Slugs
        $ids = array_column($places, 'id');
        $slugs = array_column($places, 'slug');
        $this->assertCount(count($places), array_unique($ids), 'All facility IDs must be unique');
        $this->assertCount(count($places), array_unique($slugs), 'All facility slugs must be unique');

        // All Facilities count matches actual records
        $allCat = array_values(array_filter($categories, fn($c) => $c['id'] === 'all'))[0] ?? null;
        $this->assertNotNull($allCat);
        $this->assertEquals(39, $allCat['count']);
    }

    public function test_inside_tour_html_renders_data_script_and_directory_container(): void
    {
        $response = $this->get('/inside');

        $response->assertStatus(200);
        $response->assertSee('INSIDE CAMPUS');
        $response->assertSee('EXPLORE YOUR CAMPUS.');
        $response->assertSee('campus-places-data');
        $response->assertSee('campus-directory-grid');
        $response->assertSee('function insideTourApp');
        $response->assertSee('All Facilities');
        $response->assertSee('(39)');

        // Facility names appear in rendered output
        $response->assertSee('Central Library');
        $response->assertSee('Main Entrance (Gate 1)');
        $response->assertSee('Kalam Hostel');
        $response->assertSee('Block IX');
        $response->assertSee('Auditorium & Event Complex', false);
    }

    public function test_inside_tour_filtering_logic(): void
    {
        $places = \App\Services\PlaceRepository::getInsidePlaces();

        // Academic category filter
        $academic = array_filter($places, fn($p) => $p['category_id'] === 'academic');
        $this->assertCount(8, $academic);

        // Hostel category filter
        $hostels = array_filter($places, fn($p) => $p['category_id'] === 'hostel');
        $this->assertCount(5, $hostels);

        // Search returns matching facilities
        $kalamMatches = array_filter($places, fn($p) => str_contains(strtolower($p['name']), 'kalam'));
        $this->assertCount(1, $kalamMatches);
        $this->assertEquals('Kalam Hostel', array_values($kalamMatches)[0]['name']);

        // Search and category together
        $diningCoffee = array_filter($places, fn($p) => $p['category_id'] === 'food' && str_contains(strtolower($p['name']), 'nescafe'));
        $this->assertCount(1, $diningCoffee);
    }

    public function test_inside_tour_view_details_and_route_slugs(): void
    {
        $places = \App\Services\PlaceRepository::getInsidePlaces();

        foreach (array_slice($places, 0, 5) as $place) {
            $detailsResponse = $this->get('/places/' . $place['slug']);
            $detailsResponse->assertStatus(200);
            $detailsResponse->assertSee($place['name']);

            $routeResponse = $this->get('/locate?destination=' . $place['slug']);
            $routeResponse->assertStatus(200);
            $routeResponse->assertSee($place['slug']);
        }
    }

    public function test_inside_tour_spatial_coordinates_integrity(): void
    {
        $places = \App\Services\PlaceRepository::getInsidePlaces();

        $withCoords = 0;
        $withoutCoords = 0;

        foreach ($places as $place) {
            if ($place['latitude'] !== null && $place['longitude'] !== null) {
                $withCoords++;
                // Verify within IIT Patna / Bihta latitude & longitude bounding box
                $this->assertGreaterThan(25.5, $place['latitude']);
                $this->assertLessThan(25.6, $place['latitude']);
                $this->assertGreaterThan(84.8, $place['longitude']);
                $this->assertLessThan(84.9, $place['longitude']);
            } else {
                $withoutCoords++;
                $this->assertNull($place['latitude']);
                $this->assertNull($place['longitude']);
            }
        }

        $this->assertGreaterThan(20, $withCoords, 'Verified coordinates exist for mapped facilities');
        $this->assertGreaterThan(5, $withoutCoords, 'Facilities without coordinates are explicitly null');
    }

    public function test_outside_tour_page_loads_with_real_nearby_spots(): void
    {
        $response = $this->get('/outside');

        $response->assertStatus(200);
        $response->assertSee('OUTSIDE TOUR');
        $response->assertSee('nearby-places-data');
        $response->assertSee('nearby-directory-grid');
        $response->assertSee('function outsideTourApp');
        $response->assertSee('Bihari Thaath');
        $response->assertSee('Bihta Railway Station');
        $response->assertSee('Angeethi Cafe');
        $response->assertSee('Maa Tara Xerox');
        $response->assertSee('outside-area-map');
    }

    public function test_outside_tour_dataset_and_menu_integrity(): void
    {
        $places = \App\Services\PlaceRepository::getOutsidePlaces();
        $categories = \App\Services\PlaceRepository::getOutsideCategories();

        $this->assertCount(12, $places);

        // Unique IDs & Slugs
        $ids = array_column($places, 'id');
        $slugs = array_column($places, 'slug');
        $this->assertCount(count($places), array_unique($ids));
        $this->assertCount(count($places), array_unique($slugs));

        // Food places have verified menus with positive prices
        $foodPlaces = array_filter($places, fn($p) => in_array($p['category_id'], ['food', 'restaurants', 'cafes']));
        $this->assertGreaterThanOrEqual(6, count($foodPlaces));
        foreach ($foodPlaces as $fp) {
            $this->assertNotEmpty($fp['menu'], "Place {$fp['name']} should have verified menu items");
            foreach ($fp['menu'] as $item) {
                $this->assertArrayHasKey('name', $item);
                $this->assertArrayHasKey('price', $item);
                $this->assertGreaterThan(0, $item['price']);
            }
        }

        // All places have coordinates
        foreach ($places as $p) {
            $this->assertNotNull($p['latitude']);
            $this->assertNotNull($p['longitude']);
            $this->assertGreaterThan(25.5, $p['latitude']);
            $this->assertLessThan(25.6, $p['latitude']);
            $this->assertGreaterThan(84.8, $p['longitude']);
            $this->assertLessThan(84.9, $p['longitude']);
        }
    }

    public function test_locate_page_loads_with_routing_controls(): void
    {
        $response = $this->get('/locate');

        $response->assertStatus(200);
        $response->assertSee('WHERE DO YOU WANT TO GO?');
        $response->assertSee('Starting Point');
        $response->assertSee('Destination');
        $response->assertSee('Use my current location');
        $response->assertSee('Show Route & Directions', false);
        $response->assertSee('locate-map');
    }

    public function test_locate_page_with_destination_query_parameter(): void
    {
        $response = $this->get('/locate?destination=central-library');

        $response->assertStatus(200);
        $response->assertSee('WHERE DO YOU WANT TO GO?');
        $response->assertSee('locate-dest-data');
        $response->assertSee('Central Library');
    }

    public function test_locate_page_supports_preselection_by_id_and_slug(): void
    {
        $responseBySlug = $this->get('/locate?start=main-entrance&destination=bihari-thaath-restaurant');
        $responseBySlug->assertStatus(200);
        $responseBySlug->assertSee('Main Entrance (Gate 1)');
        $responseBySlug->assertSee('Bihari Thaath');

        $responseById = $this->get('/locate?start=iitp-main-entrance&destination=out-bihari-thaath');
        $responseById->assertStatus(200);
        $responseById->assertSee('Main Entrance (Gate 1)');
        $responseById->assertSee('Bihari Thaath');
    }

    public function test_locate_page_embeds_verified_datasets_and_on_demand_privacy_dialog(): void
    {
        $response = $this->get('/locate');

        $response->assertStatus(200);
        $response->assertSee('locate-inside-data');
        $response->assertSee('locate-outside-data');
        $response->assertSee('Use Current Location?');
        $response->assertSee('never stored on any server', false);
        $response->assertSee('Recenter');
        $response->assertSee('OpenStreetMap');
        $response->assertSee('OSRM Engine');
        $response->assertSee('Driving');
        $response->assertSee('Walking');
        $response->assertSee('Cycling');
    }

    public function test_place_details_page_loads_with_source_attribution(): void
    {
        $response = $this->get('/places/central-library');

        $response->assertStatus(200);
        $response->assertSee('Central Library');
        $response->assertSee('Source & Verification', false);
        $response->assertSee('Navigate to this Spot in Locate');
        $response->assertSee('place-detail-map');
    }

    public function test_nearby_place_details_loads_correctly_with_menu(): void
    {
        $response = $this->get('/places/bihari-thaath-restaurant');

        $response->assertStatus(200);
        $response->assertSee('Bihari Thaath');
        $response->assertSee('Bihta-Kanpa Road');
        $response->assertSee('Traditional Bihari Thali');
        $response->assertSee('Litti Chokha');
        $response->assertSee('₹140');
        $response->assertSee('Suggest an Edit');
        $response->assertSee('Navigate to this Spot in Locate');
    }

    public function test_nonexistent_place_returns_404(): void
    {
        $response = $this->get('/places/invalid-imaginary-spot');

        $response->assertStatus(404);
    }

    public function test_contribute_page_loads_with_foundation_preview_notice(): void
    {
        $response = $this->get('/contribute');

        $response->assertStatus(200);
        $response->assertSee("KNOW A PLACE WE'RE MISSING?", false);
        $response->assertSee('Place Photos (Max 3)');
    }

    public function test_contribute_page_prefills_selected_place(): void
    {
        $response = $this->get('/contribute?place=bihari-thaath-restaurant&action=menu');

        $response->assertStatus(200);
        $response->assertSee('Bihari Thaath');
        $response->assertSee('selected-place-data');
    }

    public function test_contact_page_loads_successfully(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee('CONTACT & FEEDBACK');
        $response->assertSee('Send General Feedback');
        $response->assertSee('Report Incorrect Information');
        $response->assertSee('feedback@campusaround.iitp.local');
    }

    public function test_about_page_loads_successfully_with_guidelines(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('About CampusAround');
        $response->assertSee('The Bihta Campus Reality');
        $response->assertSee('Community Guidelines & Data Policy', false);
    }

    public function test_vercel_deployment_configuration_is_valid(): void
    {
        // 1. Verify vercel.json exists and contains correct PHP runtime & route mappings
        $vercelJsonPath = base_path('vercel.json');
        $this->assertFileExists($vercelJsonPath);

        $vercelConfig = json_decode(file_get_contents($vercelJsonPath), true);
        $this->assertIsArray($vercelConfig);
        $this->assertArrayHasKey('functions', $vercelConfig);
        $this->assertStringContainsString('vercel-php', $vercelConfig['functions']['api/index.php']['runtime']);
        $this->assertEquals('public', $vercelConfig['outputDirectory']);
        $this->assertArrayHasKey('rewrites', $vercelConfig);
        $this->assertArrayHasKey('env', $vercelConfig);
        $this->assertEquals('/tmp/views', $vercelConfig['env']['VIEW_COMPILED_PATH']);
        $this->assertEquals('cookie', $vercelConfig['env']['SESSION_DRIVER']);
        $this->assertEquals('array', $vercelConfig['env']['CACHE_STORE']);

        // 2. Verify api/index.php exists and points to public/index.php
        $apiIndex = base_path('api/index.php');
        $this->assertFileExists($apiIndex);
        $apiContent = file_get_contents($apiIndex);
        $this->assertStringContainsString("public/index.php", $apiContent);
        $this->assertStringContainsString("/tmp/views", $apiContent);

        // 3. Verify .vercelignore exists and protects sensitive/heavy folders
        $vercelIgnorePath = base_path('.vercelignore');
        $this->assertFileExists($vercelIgnorePath);
        $ignoreContent = file_get_contents($vercelIgnorePath);
        $this->assertStringContainsString('/vendor', $ignoreContent);
        $this->assertStringContainsString('.env', $ignoreContent);

        // 4. Verify compiled Vite assets exist for production distribution
        $manifestPath = public_path('build/manifest.json');
        $this->assertFileExists($manifestPath);
    }
}
