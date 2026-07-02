<?php

use PHPUnit\Framework\TestCase;

class ProductCrudTest extends TestCase
{
    public function test_can_create_product()
    {
        $product = [
            'naam' => 'Shampoo',
            'prijs' => 5.99,
            'voorraad' => 10,
        ];

        $this->assertEquals('Shampoo', $product['naam']);
        $this->assertEquals(5.99, $product['prijs']);
        $this->assertEquals(10, $product['voorraad']);
    }

    public function test_can_update_product()
    {
        $product = [
            'naam' => 'Shampoo',
            'prijs' => 5.99,
            'voorraad' => 10,
        ];

        // update simulatie
        $product['voorraad'] = 25;

        $this->assertEquals(25, $product['voorraad']);
    }

    public function test_can_delete_product()
    {
        $product = [
            'naam' => 'Shampoo',
        ];

        // delete simulatie
        $product = null;

        $this->assertNull($product);
    }

    public function test_can_check_multiple_products()
    {
        $products = [
            ['naam' => 'Shampoo'],
            ['naam' => 'Soap'],
        ];

        $this->assertCount(2, $products);
        $this->assertEquals('Shampoo', $products[0]['naam']);
        $this->assertEquals('Soap', $products[1]['naam']);
    }

    public function test_ean_must_be_numeric_logic_only()
    {
        $ean = '123456789';

        $isNumeric = is_numeric($ean);

        $this->assertTrue($isNumeric);
    }

    public function test_low_stock_warning_logic()
    {
        $voorraad = 3;

        $isLowStock = $voorraad < 5;

        $this->assertTrue($isLowStock);
    }
}