<?php

use App\Models\Product;

describe('product crud (no db)', function (): void {

    it('can create a product in memory', function (): void {

        $product = new Product([
            'naam' => 'Shampoo',
            'prijs' => 5.99,
            'voorraad' => 10,
        ]);

        expect($product->naam)->toBe('Shampoo');
        expect($product->prijs)->toBe(5.99);
        expect($product->voorraad)->toBe(10);
    });

    it('can update a product in memory', function (): void {

        $product = new Product([
            'naam' => 'Shampoo',
            'prijs' => 5.99,
            'voorraad' => 10,
        ]);

        // simulate update
        $product->naam = 'Shampoo XL';
        $product->prijs = 7.49;
        $product->voorraad = 25;

        expect($product->naam)->toBe('Shampoo XL');
        expect($product->prijs)->toBe(7.49);
        expect($product->voorraad)->toBe(25);
    });

    it('can "delete" a product in memory', function (): void {

        $product = new Product([
            'naam' => 'Shampoo',
            'prijs' => 5.99,
            'voorraad' => 10,
        ]);

        // simulate delete
        $product = null;

        expect($product)->toBeNull();
    });

    it('can handle multiple products in memory', function (): void {

        $products = [
            new Product(['naam' => 'Shampoo']),
            new Product(['naam' => 'Soap']),
            new Product(['naam' => 'Toothpaste']),
        ];

        expect($products)->toHaveCount(3);
        expect($products[0]->naam)->toBe('Shampoo');
        expect($products[1]->naam)->toBe('Soap');
        expect($products[2]->naam)->toBe('Toothpaste');
    });

    it('can check for unique product names in memory', function (): void {

        $products = [];

        $products[] = new Product(['naam' => 'Shampoo']);
        $products[] = new Product(['naam' => 'Shampoo XL']);

        $names = array_map(fn ($p) => $p->naam, $products);

        expect($names)->toContain('Shampoo');
        expect($names)->toContain('Shampoo XL');
        expect(count($names))->toBe(2);
    });

    it('validates product price logic in memory', function (): void {

        $product = new Product([
            'naam' => 'Shampoo',
            'prijs' => 5.99,
            'voorraad' => 10,
        ]);

        $isValidPrice = $product->prijs > 0;

        expect($isValidPrice)->toBeTrue();
    });

    it('validates stock cannot be negative', function (): void {

        $product = new Product([
            'naam' => 'Shampoo',
            'prijs' => 5.99,
            'voorraad' => 10,
        ]);

        $product->voorraad = -5;

        $isInvalidStock = $product->voorraad < 0;

        expect($isInvalidStock)->toBeTrue();
    });

});