<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Party;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Party::truncate();
        Schema::enableForeignKeyConstraints();

        Customer::insert(['id' => 11, 'type' => Party::TYPE_CUSTOMER, 'id2' => 1, 'name' => 'Iman Sadawangi']);
        Customer::insert(['id' => 12, 'type' => Party::TYPE_CUSTOMER, 'id2' => 1, 'name' => 'Ajis Pasapen']);
        Customer::insert(['id' => 13, 'type' => Party::TYPE_CUSTOMER, 'id2' => 1, 'name' => 'Bang IT']);
        Customer::insert(['id' => 14, 'type' => Party::TYPE_CUSTOMER, 'id2' => 1, 'name' => 'Ade Rizki Sukamandi']);
        Customer::insert(['id' => 15, 'type' => Party::TYPE_CUSTOMER, 'id2' => 1, 'name' => 'Zea Zio Butique']);
        Customer::insert(['id' => 16, 'type' => Party::TYPE_CUSTOMER, 'id2' => 1, 'name' => 'Agis BS Motor Putra']);
    }
}
