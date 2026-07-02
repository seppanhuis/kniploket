<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

describe('klant crud', function (): void {
    beforeEach(function (): void {
        Schema::dropIfExists('Klant');
        Schema::dropIfExists('Gebruiker');

        Schema::create('Gebruiker', function ($table): void {
            $table->id();
            $table->unsignedInteger('RolId')->default(4);
            $table->string('Gebruikersnaam')->unique();
            $table->string('Wachtwoord');
            $table->string('Voornaam');
            $table->string('Achternaam');
            $table->string('Straat');
            $table->integer('Huisnummer');
            $table->string('Toevoeging')->nullable();
            $table->string('Postcode');
            $table->string('Woonplaats');
            $table->string('Telefoonnummer');
            $table->string('Email')->unique();
            $table->timestamp('LaatsteLogin')->nullable();
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking')->nullable();
            $table->timestamps();
        });

        Schema::create('Klant', function ($table): void {
            $table->id();
            $table->foreignId('GebruikerId')->constrained('Gebruiker');
            $table->string('Wensen')->nullable();
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking')->nullable();
            $table->timestamps();
        });
    });

    it('can show the klanten overview and create a new klant', function (): void {
        $user = User::factory()->create();

        $this->actingAs($user);

        $overview = $this->get(route('klanten.index'));
        $overview->assertOk();
        $overview->assertSee('Geen klanten gevonden');

        $response = $this->post(route('klanten.store'), [
            'voornaam' => 'Jan',
            'achternaam' => 'Smit',
            'email' => 'jan@example.com',
            'telefoonnummer' => '0612345678',
            'wensen' => 'Licht',
            'opmerking' => 'Test klant',
            'straat' => 'Hoofdstraat',
            'huisnummer' => '12',
            'toevoeging' => '',
            'postcode' => '1234AB',
            'woonplaats' => 'Utrecht',
            'is_actief' => true,
        ]);

        $response->assertRedirect(route('klanten.index'));
        $response->assertSessionHas('success', 'klant succesvol toegevoegd');
        $this->assertDatabaseHas('Gebruiker', ['Email' => 'jan@example.com']);
        $this->assertDatabaseHas('Klant', ['Opmerking' => 'Test klant']);
    });

    it('stores the huisnummer as an integer when updating a klant', function (): void {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post(route('klanten.store'), [
            'voornaam' => 'Jan',
            'achternaam' => 'Smit',
            'email' => 'jan@example.com',
            'telefoonnummer' => '0612345678',
            'wensen' => 'Licht',
            'opmerking' => 'Test klant',
            'straat' => 'Hoofdstraat',
            'huisnummer' => '12',
            'toevoeging' => '',
            'postcode' => '1234AB',
            'woonplaats' => 'Utrecht',
            'is_actief' => true,
        ]);

        $klantId = DB::table('Klant')->value('Id');

        $response = $this->put(route('klanten.update', $klantId), [
            'voornaam' => 'Jan',
            'achternaam' => 'Smit',
            'email' => 'jan@example.com',
            'telefoonnummer' => '0612345678',
            'wensen' => 'Licht',
            'opmerking' => 'Test klant',
            'straat' => 'Hoofdstraat',
            'huisnummer' => '34',
            'toevoeging' => '',
            'postcode' => '1234AB',
            'woonplaats' => 'Utrecht',
            'is_actief' => true,
        ]);

        $response->assertRedirect(route('klanten.index'));
        $this->assertSame(34, (int) DB::table('Gebruiker')->where('Email', 'jan@example.com')->value('Huisnummer'));
    });

    it('creates a unique username when the base username already exists', function (): void {
        $user = User::factory()->create();

        $this->actingAs($user);

        $firstResponse = $this->post(route('klanten.store'), [
            'voornaam' => 'Sep',
            'achternaam' => "in 't panhuis",
            'email' => 'sep1@example.com',
            'telefoonnummer' => '0612345678',
            'wensen' => 'Licht',
            'opmerking' => 'Eerste klant',
            'straat' => 'Hoofdstraat',
            'huisnummer' => '12',
            'toevoeging' => '',
            'postcode' => '1234AB',
            'woonplaats' => 'Utrecht',
            'is_actief' => true,
        ]);

        $secondResponse = $this->post(route('klanten.store'), [
            'voornaam' => 'Sep',
            'achternaam' => "in 't panhuis",
            'email' => 'sep2@example.com',
            'telefoonnummer' => '0612345678',
            'wensen' => 'Licht',
            'opmerking' => 'Tweede klant',
            'straat' => 'Hoofdstraat',
            'huisnummer' => '12',
            'toevoeging' => '',
            'postcode' => '1234AB',
            'woonplaats' => 'Utrecht',
            'is_actief' => true,
        ]);

        $firstResponse->assertRedirect(route('klanten.index'));
        $secondResponse->assertRedirect(route('klanten.index'));
        $secondResponse->assertSessionHas('success', 'klant succesvol toegevoegd');
        $this->assertDatabaseCount('Gebruiker', 2);
        $this->assertDatabaseHas('Gebruiker', ['Email' => 'sep2@example.com']);
    });
});
