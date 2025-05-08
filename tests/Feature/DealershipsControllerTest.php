<?php

namespace Tests\Unit;

use Tests\TestCase;

class DealershipsControllerTest extends TestCase
{
    public function test_index_returns_at_least_one_dealership()
    {
        $response = $this->getJson('/api/dealerships');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id',
                         'name'
                     ]
                 ]);

        $responseData = $response->json();
        $this->assertGreaterThan(0, count($responseData));
    }

    public function test_index_contains_central_dealership()
    {
        $response = $this->getJson('/api/dealerships');
        $response->assertStatus(200);

        $responseData = $response->json();
        $names = array_column($responseData, 'name');
        $this->assertContains('Dealership 1', $names);
    }
}