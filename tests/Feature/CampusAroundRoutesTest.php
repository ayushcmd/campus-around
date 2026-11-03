<?php

namespace Tests\Feature;

use Tests\TestCase;

class CampusAroundRoutesTest extends TestCase
{
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('CampusAround');
        $response->assertSee('Central Library');
        $response->assertSee('IIT Patna Campus');
    }

    public function test_explore_page_loads_successfully(): void
    {
        $response = $this->get('/explore');

        $response->assertStatus(200);
        $response->assertSee('EXPLORE ALL PLACES');
        $response->assertSee('Central Library');
        $response->assertSee('Campus Facilities');
    }

    public function test_explore_page_with_query_params(): void
    {
        $response = $this->get('/explore?category=food-drinks&type=nearby');

        $response->assertStatus(200);
        $response->assertSee('EXPLORE ALL PLACES');
    }

    public function test_campus_place_detail_page(): void
    {
        $response = $this->get('/places/central-library');

        $response->assertStatus(200);
        $response->assertSee('Central Library');
        $response->assertSee('Inside Campus');
        $response->assertSee('About this spot');
        $response->assertSee('Similar places around campus');
    }

    public function test_nearby_place_detail_page(): void
    {
        $response = $this->get('/places/gate-1-hot-momos');

        $response->assertStatus(200);
        $response->assertSee('Gate 1 Hot Momos');
        $response->assertSee('Outside Campus');
    }

    public function test_nonexistent_place_returns_404(): void
    {
        $response = $this->get('/places/unknown-random-spot');

        $response->assertStatus(404);
    }

    public function test_contribute_page_loads_successfully(): void
    {
        $response = $this->get('/contribute');

        $response->assertStatus(200);
        $response->assertSee('ADD A SPOT TO CAMPUSAROUND');
        $response->assertSee('Foundation Preview Mode');
        $response->assertSee('Place Details');
    }

    public function test_about_page_loads_successfully(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('About CampusAround');
        $response->assertSee('The Bihta Campus Reality');
        $response->assertSee('Phase 1: Project Foundation');
    }
}
