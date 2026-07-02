<?php

namespace Tests\Feature;

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
}
