<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ProductCategory::truncate();
        Schema::enableForeignKeyConstraints();
        ProductCategory::insert(['id' => 1, 'name' => 'Aksesoris']);
        ProductCategory::insert(['id' => 2, 'name' => 'Laptop']);
        ProductCategory::insert(['id' => 3, 'name' => 'Printer']);
        ProductCategory::insert(['id' => 4, 'name' => 'Networking']);
        ProductCategory::insert(['id' => 5, 'name' => 'CCTV']);
    }
}
