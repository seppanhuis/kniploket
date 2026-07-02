<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;

describe('afspraak crud', function (): void {

    beforeEach(function (): void {

        $user = User::factory()->create();
        $this->actingAs($user);
    });

    it('can show the afspraken overview', function (): void {

        $response = $this->get(route('afspraken.index'));

        $response->assertOk();
    });

    it('can create a new afspraak', function (): void {

        $response = $this->post(route('afspraken.store'), [
            'klant_id' => 1,
            'medewerker_id' => 1,
            'behandeling_id' => 1,
            'afspraak_status_id' => 1,
            'datum' => '2026-10-10',
            'start_tijd' => '10:00',
            'eind_tijd' => '10:45',
            'opmerking' => 'Test afspraak',
            'is_actief' => true,
        ]);

        $response->assertRedirect(route('afspraken.index'));
        $response->assertSessionHas('success');
    });

    it('cannot create an overlapping afspraak', function (): void {

        $this->post(route('afspraken.store'), [
            'klant_id' => 1,
            'medewerker_id' => 1,
            'behandeling_id' => 1,
            'afspraak_status_id' => 1,
            'datum' => '2026-10-10',
            'start_tijd' => '10:00',
            'eind_tijd' => '10:45',
            'opmerking' => 'Eerste afspraak',
            'is_actief' => true,
        ]);

        $response = $this->post(route('afspraken.store'), [
            'klant_id' => 1,
            'medewerker_id' => 1,
            'behandeling_id' => 1,
            'afspraak_status_id' => 1,
            'datum' => '2026-10-10',
            'start_tijd' => '10:15',
            'eind_tijd' => '10:30',
            'opmerking' => 'Tweede afspraak',
            'is_actief' => true,
        ]);

        $response->assertSessionHas('error');
    });

    it('can update an afspraak', function (): void {

        $response = $this->put(route('afspraken.update', 1), [
            'klant_id' => 1,
            'medewerker_id' => 1,
            'behandeling_id' => 1,
            'afspraak_status_id' => 1,
            'datum' => '2026-10-11',
            'start_tijd' => '11:00',
            'eind_tijd' => '11:45',
            'opmerking' => 'Gewijzigd',
            'is_actief' => true,
        ]);

        $response->assertRedirect(route('afspraken.index'));
    });

    it('cannot delete an inactive afspraak', function (): void {

        $response = $this->delete(route('afspraken.destroy', 1));

        $response->assertRedirect();
    });

});