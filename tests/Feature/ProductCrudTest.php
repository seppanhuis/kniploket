<?php
//php artisan test --filter=ProductCrudTest

//NIET aanraken hij werkt hij laat gewoon een error zien maar kan je negeren

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    Schema::create('ProductCategorie', function ($table) {
        $table->id('Id');
        $table->string('Naam');
        $table->boolean('IsActief')->default(true);
        $table->string('Opmerking')->nullable();
        $table->dateTime('DatumAangemaakt');
        $table->dateTime('DatumGewijzigd');
    });

    Schema::create('Leverancier', function ($table) {
        $table->id('Id');
        $table->string('Naam');
        $table->string('Telefoonnummer')->nullable();
        $table->string('Email')->nullable();
        $table->boolean('IsActief')->default(true);
        $table->string('Opmerking')->nullable();
        $table->dateTime('DatumAangemaakt');
        $table->dateTime('DatumGewijzigd');
    });

    Schema::create('Product', function ($table) {
        $table->id('Id');
        $table->string('ProductNaam');
        $table->string('EANCode')->unique();
        $table->integer('Voorraad');
        $table->integer('MinimumVoorraad');
        $table->unsignedInteger('LeverancierId');
        $table->unsignedInteger('CategorieId');
        $table->boolean('IsActief')->default(true);
        $table->string('Opmerking')->nullable();
        $table->dateTime('DatumAangemaakt');
        $table->dateTime('DatumGewijzigd');
    });

    Schema::create('Behandeling', function ($table) {
        $table->id('BehandelingId');
        $table->string('Naam');
        $table->boolean('IsActief')->default(true);
        $table->dateTime('DatumAangemaakt');
        $table->dateTime('DatumGewijzigd');
    });

    Schema::create('BehandelingProduct', function ($table) {
        $table->id('Id');
        $table->unsignedInteger('BehandelingId');
        $table->unsignedInteger('ProductId');
        $table->decimal('Aantal', 6, 2);
        $table->boolean('IsActief')->default(true);
        $table->dateTime('DatumAangemaakt');
        $table->dateTime('DatumGewijzigd');
    });
});

it('shows products with low-stock warnings and creates a new product', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $categoryId = DB::table('ProductCategorie')->insertGetId([
        'Naam' => 'Crèmes',
        'IsActief' => true,
        'DatumAangemaakt' => now(),
        'DatumGewijzigd' => now(),
    ]);

    $supplierId = DB::table('Leverancier')->insertGetId([
        'Naam' => 'Beauty Supply',
        'Telefoonnummer' => '0123456789',
        'Email' => 'info@example.com',
        'IsActief' => true,
        'DatumAangemaakt' => now(),
        'DatumGewijzigd' => now(),
    ]);

    $productId = DB::table('Product')->insertGetId([
        'ProductNaam' => 'Dagcrème',
        'EANCode' => '8711111111111',
        'Voorraad' => 1,
        'MinimumVoorraad' => 3,
        'LeverancierId' => $supplierId,
        'CategorieId' => $categoryId,
        'IsActief' => true,
        'DatumAangemaakt' => now(),
        'DatumGewijzigd' => now(),
    ]);

    $behandelingId = DB::table('Behandeling')->insertGetId([
        'Naam' => 'Gezichtsbehandeling',
        'IsActief' => true,
        'DatumAangemaakt' => now(),
        'DatumGewijzigd' => now(),
    ]);

    DB::table('BehandelingProduct')->insert([
        'BehandelingId' => $behandelingId,
        'ProductId' => $productId,
        'Aantal' => 1.00,
        'IsActief' => true,
        'DatumAangemaakt' => now(),
        'DatumGewijzigd' => now(),
    ]);

    $response = $this->actingAs($user)->get(route('producten.index'));

    $response->assertOk();
    $response->assertSee('Lage voorraad');
    $response->assertSee('Dagcrème');
    $response->assertSee('Beauty Supply');
    $response->assertSee('Gezichtsbehandeling');

    $response = $this->actingAs($user)->post(route('producten.store'), [
        'product_naam' => 'Nieuw product',
        'ean_code' => '8711111111112',
        'voorraad' => 10,
        'minimum_voorraad' => 5,
        'leverancier_id' => $supplierId,
        'categorie_id' => $categoryId,
        'is_actief' => true,
    ]);

    $response->assertRedirect(route('producten.index'));
    $this->assertDatabaseHas('Product', ['ProductNaam' => 'Nieuw product']);
});

it('rejects non-numeric ean codes and stores multiple treatment links', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $categoryId = DB::table('ProductCategorie')->insertGetId([
        'Naam' => 'Crèmes',
        'IsActief' => true,
        'DatumAangemaakt' => now(),
        'DatumGewijzigd' => now(),
    ]);

    $supplierId = DB::table('Leverancier')->insertGetId([
        'Naam' => 'Beauty Supply',
        'Telefoonnummer' => '0123456789',
        'Email' => 'info@example.com',
        'IsActief' => true,
        'DatumAangemaakt' => now(),
        'DatumGewijzigd' => now(),
    ]);

    $treatmentOneId = DB::table('Behandeling')->insertGetId([
        'Naam' => 'Gezichtsbehandeling',
        'IsActief' => true,
        'DatumAangemaakt' => now(),
        'DatumGewijzigd' => now(),
    ]);

    $treatmentTwoId = DB::table('Behandeling')->insertGetId([
        'Naam' => 'Manicure',
        'IsActief' => true,
        'DatumAangemaakt' => now(),
        'DatumGewijzigd' => now(),
    ]);

    $response = $this->actingAs($user)->post(route('producten.store'), [
        'product_naam' => 'Test product',
        'ean_code' => 'ABC123',
        'voorraad' => 4,
        'minimum_voorraad' => 2,
        'leverancier_id' => $supplierId,
        'categorie_id' => $categoryId,
        'is_actief' => true,
        'treatment_ids' => [$treatmentOneId, $treatmentTwoId],
    ]);

    $response->assertSessionHasErrors('ean_code');

    $response = $this->actingAs($user)->post(route('producten.store'), [
        'product_naam' => 'Test product',
        'ean_code' => '8711111111113',
        'voorraad' => 4,
        'minimum_voorraad' => 2,
        'leverancier_id' => $supplierId,
        'categorie_id' => $categoryId,
        'is_actief' => true,
        'treatment_ids' => [$treatmentOneId, $treatmentTwoId],
    ]);

    $response->assertRedirect(route('producten.index'));
    $this->assertDatabaseHas('Product', ['ProductNaam' => 'Test product']);
    $this->assertDatabaseHas('BehandelingProduct', ['ProductId' => 1, 'BehandelingId' => $treatmentOneId]);
    $this->assertDatabaseHas('BehandelingProduct', ['ProductId' => 1, 'BehandelingId' => $treatmentTwoId]);
});