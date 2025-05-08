<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class CarDataControllerTest extends TestCase
{
    public function test_grouped_returns_makes_with_models()
    {
        $response = $this->getJson('/car-data');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id',
                         'name',
                         'models' => [
                             '*' => [
                                 'id',
                                 'make_id',
                                 'name'
                             ]
                         ]
                     ]
                 ]);
    }
}