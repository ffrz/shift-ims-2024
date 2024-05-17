<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Customer::truncate();
        Schema::enableForeignKeyConstraints();

        Customer::insert(['id' => 1, 'name' => 'Iman Sadawangi']);
        Customer::insert(['id' => 2, 'name' => 'Ajis Pasapen']);
        Customer::insert(['id' => 3, 'name' => 'Bang IT']);
        Customer::insert(['id' => 4, 'name' => 'Ade Rizki Sukamandi']);
        Customer::insert(['id' => 5, 'name' => 'Zea Zio Butique']);
        Customer::insert(['id' => 6, 'name' => 'Agis BS Motor Putra']);
    }
}
