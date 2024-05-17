<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Supplier::truncate();
        Schema::enableForeignKeyConstraints();

        Supplier::insert(['id' => 1, 'name' => 'Tkpd Venusshop']);
        Supplier::insert(['id' => 2, 'name' => 'Cemara Mas Indah']);
        Supplier::insert(['id' => 3, 'name' => 'Calvin Com']);
        Supplier::insert(['id' => 4, 'name' => 'Calvin Acc']);
        Supplier::insert(['id' => 5, 'name' => 'Tkpd Fo Importir']);
        Supplier::insert(['id' => 6, 'name' => 'Tkpd Aquarius']);
    }
}
