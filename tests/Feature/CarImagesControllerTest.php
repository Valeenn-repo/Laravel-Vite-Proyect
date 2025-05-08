<?php

namespace Tests\Unit;

use Tests\TestCase;

class CarImagesControllerTest extends TestCase
{
    public function test_store_requires_parameters()
    {
        $response = $this->postJson('/api/car-images', []);
        $response->assertStatus(422); // Espera error de validación
    }
}