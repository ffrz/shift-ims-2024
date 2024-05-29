<?php

namespace Database\Seeders;

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

        Party::insert(['id' => 1, 'type' => Party::TYPE_SUPPLIER, 'id2' => 1, 'name' => 'Tkpd Venusshop']);
        Party::insert(['id' => 2, 'type' => Party::TYPE_SUPPLIER, 'id2' => 2, 'name' => 'Cemara Mas Indah']);
        Party::insert(['id' => 3, 'type' => Party::TYPE_SUPPLIER, 'id2' => 3, 'name' => 'Calvin Com']);
        Party::insert(['id' => 4, 'type' => Party::TYPE_SUPPLIER, 'id2' => 4, 'name' => 'Calvin Acc']);
        Party::insert(['id' => 5, 'type' => Party::TYPE_SUPPLIER, 'id2' => 5, 'name' => 'Tkpd Fo Importir']);
        Party::insert(['id' => 6, 'type' => Party::TYPE_SUPPLIER, 'id2' => 6, 'name' => 'Tkpd Aquarius']);

        Party::insert(['id' => 11, 'type' => Party::TYPE_CUSTOMER, 'id2' => 1, 'name' => 'Iman Sadawangi']);
        Party::insert(['id' => 12, 'type' => Party::TYPE_CUSTOMER, 'id2' => 2, 'name' => 'Ajis Pasapen']);
        Party::insert(['id' => 13, 'type' => Party::TYPE_CUSTOMER, 'id2' => 3, 'name' => 'Bang IT']);
        Party::insert(['id' => 14, 'type' => Party::TYPE_CUSTOMER, 'id2' => 4, 'name' => 'Ade Rizki Sukamandi']);
        Party::insert(['id' => 15, 'type' => Party::TYPE_CUSTOMER, 'id2' => 5, 'name' => 'Zea Zio Butique']);
        Party::insert(['id' => 16, 'type' => Party::TYPE_CUSTOMER, 'id2' => 6, 'name' => 'Agis BS Motor Putra']);
    }
}
