<?php

namespace Tests\Feature;

use App\Models\Klant;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class KlantCrudTest extends TestCase
{
    public function test_klanten_index_route_loads()
    {
        $response = $this->get('/klanten');

        // mag 200 of 302 zijn (login redirect)
        $this->assertContains($response->getStatusCode(), [200, 302]);
    }

    public function test_klanten_store_route_exists()
    {
        $response = $this->post('/klanten', [
            'naam' => 'Test klant',
            'huisnummer' => 12
        ]);

        // meestal redirect (302)
        $this->assertContains($response->getStatusCode(), [200, 302, 422]);
    }

    public function test_klant_met_afspraken_kan_niet_worden_verwijderd()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $klantModel = Mockery::mock(Klant::class);
        $klantModel->shouldReceive('spGetKlantById')
            ->once()
            ->with(1)
            ->andReturn((object) ['Email' => 'klant@example.com']);
        $klantModel->shouldReceive('heeftAfspraken')
            ->once()
            ->with(1)
            ->andReturn(true);
        $klantModel->shouldReceive('spDeleteKlant')->never();

        $this->app->instance(Klant::class, $klantModel);

        $response = $this->delete(route('klanten.destroy', 1));

        $response->assertRedirect(route('klanten.index'));
        $response->assertSessionHas('error', 'klant kan niet worden verwijderd omdat er nog afspraken gekoppeld zijn');
    }
}
