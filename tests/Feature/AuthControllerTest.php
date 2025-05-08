<?php

namespace Tests\Unit;

use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_login_requires_email_and_password()
    {
        $response = $this->postJson('/api/login', []);
        $response->assertStatus(422); // Espera error de validación
    }
}