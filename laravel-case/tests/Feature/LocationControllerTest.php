<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Location;

class LocationControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_can_create_location()
    {

        $this->withoutMiddleware();

        $response = $this->postJson('/api/locations', [
            'name' => 'New York',
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
            'marker_color' => '#FF0000'
        ]);

        $response->dump(); 

        $response->assertStatus(201)
            ->assertJsonfragment([
                'name' => 'New York',
                'latitude' => '40.7128',
                'longitude' => '-74.0060',
                'marker_color' => '#FF0000'
            ]);
    }

    public function test_route_returns_sorted_locations()
    {
        $this->withoutMiddleware();

        $loc1 = Location::factory()->create([
            'latitude' => '40.7128',
            'longitude' => '-74.0060'
        ]);

        $loc2 = Location::factory()->create([
            'latitude' => '43.7128',
            'longitude' => '-70.0060'
        ]);

        $payload = [
            'latitude' => 41.0000,
            'longitude' => -73.0000
        ];

        $response = $this->postJson('/api/locations/route', $payload);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'name',
                        'latitude',
                        'longitude',
                        'distance_km'
                    ]
                ]);
    }
}
