<?php

namespace Database\Seeders;

use App\Models\CostCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CostCategorySedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        CostCategory::truncate();
        Schema::enableForeignKeyConstraints();
        CostCategory::insert(['id' => 1, 'name' => 'Gaji Karyawan']);
        CostCategory::insert(['id' => 2, 'name' => 'Listrik']);
        CostCategory::insert(['id' => 3, 'name' => 'Internet']);
        CostCategory::insert(['id' => 4, 'name' => 'Iuran Sampah']);
        CostCategory::insert(['id' => 5, 'name' => 'Air PDAM']);
        CostCategory::insert(['id' => 6, 'name' => 'Air Galon']);
        CostCategory::insert(['id' => 7, 'name' => 'Lain-lain']);
    }
}
