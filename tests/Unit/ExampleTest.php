<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test para comprobar que la suma funciona correctamente.
     */
    public function test_sum()
    {
        $this->assertEquals(5, 2 + 3);
    }

    /**
     * Test para comprobar que una cadena contiene otra.
     */
    public function test_string_contains()
    {
        $this->assertStringContainsString('Cars', 'VCars');
    }

    /**
     * Test para comprobar que un array tiene una clave específica.
     */
    public function test_array_has_key()
    {
        $array = ['marca' => 'Toyota', 'modelo' => 'Corolla'];
        $this->assertArrayHasKey('modelo', $array);
    }

    /**
     * Test para comprobar que un valor es nulo.
     */
    public function test_null_value()
    {
        $value = null;
        $this->assertNull($value);
    }
}
