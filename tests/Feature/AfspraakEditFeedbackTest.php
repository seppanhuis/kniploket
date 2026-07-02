<?php

use Illuminate\Support\Facades\Auth;

describe('Afspraak bewerken feedback', function () {
    test('toont een foutmelding wanneer de update overlap bevat', function () {
        Auth::shouldReceive('user')->andReturn(new class {
            public $name = 'Test Gebruiker';
            public $email = 'test@example.com';

            public function initials()
            {
                return 'TG';
            }
        });

        session()->put('error', 'Dit tijdslot is al bezet.');

        $afspraak = (object) [
            'Id' => 1,
            'KlantId' => 1,
            'MedewerkerId' => 1,
            'BehandelingId' => 1,
            'AfspraakStatusId' => 1,
            'Datum' => '2026-07-02',
            'StartTijd' => '10:00:00',
            'EindTijd' => '11:00:00',
            'Opmerking' => 'Test',
            'IsActief' => 1,
        ];

        $html = view('afspraken.edit', [
            'title' => 'Afspraak wijzigen',
            'afspraak' => $afspraak,
            'klanten' => collect(),
            'medewerkers' => collect(),
            'behandelingen' => collect(),
            'statussen' => collect(),
        ])->render();

        expect($html)->toContain('Dit tijdslot is al bezet.');
    });
});
