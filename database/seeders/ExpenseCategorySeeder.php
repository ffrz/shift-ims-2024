<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ExpenseCategory::truncate();
        Schema::enableForeignKeyConstraints();
        ExpenseCategory::insert(['id' => 1, 'name' => 'Gaji Karyawan']);
        ExpenseCategory::insert(['id' => 2, 'name' => 'Listrik']);
        ExpenseCategory::insert(['id' => 3, 'name' => 'Internet']);
        ExpenseCategory::insert(['id' => 4, 'name' => 'Iuran Sampah']);
        ExpenseCategory::insert(['id' => 5, 'name' => 'Air PDAM']);
        ExpenseCategory::insert(['id' => 6, 'name' => 'Air Galon']);
        ExpenseCategory::insert(['id' => 7, 'name' => 'Lain-lain']);
    }
}
