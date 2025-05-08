<?php

namespace Tests\Unit;

use Tests\TestCase;

class CarControllerTest extends TestCase
{
    public function test_index_returns_cars_list()
    {
        $response = $this->getJson('/api/cars');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    "make_name",
                    "model_name",
                    "id",
                    "vin",
                    "model_id",
                    "dealership_id",
                    "year",
                    "mileage",
                    "color",
                    "price",
                    "status",
                    "transmission_type",
                    "fuel_type",
                    "image_url"
                ]
            ]);
    }

    public function test_show_returns_single_car()
    {
        $response = $this->getJson('/api/cars/1');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     "make_name",
                     "model_name",
                     "id",
                     "vin",
                     "model_id",
                     "dealership_id",
                     "year",
                     "mileage",
                     "color",
                     "price",
                     "status",
                     "transmission_type",
                     "fuel_type",
                     "photos" => [
                         '*' => [
                             "id",
                             "url"
                         ]
                     ]
                 ]);
    }
}
