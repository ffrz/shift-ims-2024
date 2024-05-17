<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        Product::insert([
            'category_id' => 1,
            'code' => 'Kabel USB Extension 1,5m',
            'stock' => 3,
            'uom' => 'pcs',
            'cost' => 12500,
            'price' => 25000,
        ]);
        Product::insert([
            'category_id' => 1,
            'code' => 'Kabel USB Extension 3m',
            'stock' => 5,
            'uom' => 'pcs',
            'cost' => 20000,
            'price' => 35000,
        ]);
        Product::insert([
            'category_id' => 1,
            'code' => 'Kabel USB Extension 5m',
            'stock' => 2,
            'uom' => 'pcs',
            'cost' => 25000,
            'price' => 45000,
        ]);

        Product::insert([
            'category_id' => 2,
            'code' => 'Laptop Asus A416MA 8GB',
            'stock' => 1,
            'uom' => 'unit',
            'cost' => 4150000,
            'price' => 4900000,
        ]);

        Product::insert([
            'category_id' => 2,
            'code' => 'Laptop HP14S 4GB',
            'stock' => 1,
            'uom' => 'unit',
            'cost' => 4500000,
            'price' => 5000000,
        ]);
    }
}
