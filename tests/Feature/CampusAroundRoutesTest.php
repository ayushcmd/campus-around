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
        $response->assertSee('INSIDE TOUR');
        $response->assertSee('Central Library');
        $response->assertSee('Tutorial');
        $response->assertSee('Block IX');
        $response->assertSee('Aryabhatta');
        $response->assertSee('Verified');
        $response->assertSee('inside-campus-map');
    }

    public function test_outside_tour_page_loads_with_real_nearby_spots(): void
    {
        $response = $this->get('/outside');

        $response->assertStatus(200);
        $response->assertSee('OUTSIDE TOUR');
        $response->assertSee('Bihari Thaath');
        $response->assertSee('Bihta Railway Station');
        $response->assertSee('Angeethi Cafe');
        $response->assertSee('outside-area-map');
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

    public function test_nearby_place_details_loads_correctly(): void
    {
        $response = $this->get('/places/bihari-thaath-restaurant');

        $response->assertStatus(200);
        $response->assertSee('Bihari Thaath');
        $response->assertSee('Bihta-Kanpa Road');
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
        $response->assertSee('Frontend Foundation Only');
        $response->assertSee('Place Photos (Max 3)');
    }

    public function test_about_page_loads_successfully(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('About CampusAround');
        $response->assertSee('The Bihta Campus Reality');
    }
}
