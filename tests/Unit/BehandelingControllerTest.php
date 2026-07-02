<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\BehandelingController;
use App\Models\Behandeling;
use Illuminate\Http\Request;
use Mockery;

class BehandelingControllerTest extends TestCase
{
    public function test_index_returns_view_data_without_database()
    {
        // mock model
        $mock = Mockery::mock(Behandeling::class);

        $mock->shouldReceive('sp_GetAllBehandelingen')
            ->once()
            ->andReturn([
                (object)[
                    'BehandelingId' => 1,
                    'Naam' => 'Test',
                    'Prijs' => 10
                ]
            ]);

        // controller maken
        $controller = new BehandelingController();

        // dependency injecten via reflection (simpel hackje)
        $reflection = new \ReflectionClass($controller);
        $property = $reflection->getProperty('behandelingModel');
        $property->setAccessible(true);
        $property->setValue($controller, $mock);

        // run method
        $response = $controller->index();

        // check view data
        $this->assertEquals('Behandelingen', $response->getData()['title']);
        $this->assertCount(1, $response->getData()['behandelingen']);
    }
}
