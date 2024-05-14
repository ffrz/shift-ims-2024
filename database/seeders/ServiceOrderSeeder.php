<?php

namespace Database\Seeders;

use App\Models\ServiceOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ServiceOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ServiceOrder::truncate();
        Schema::enableForeignKeyConstraints();
        ServiceOrder::insert([
            'id' => 1,
            'customer_name' => 'Yana',
            'customer_contact' => '0814-2345-6781',
            'customer_address' => 'Pasapen',
            'device_type' => 'Laptop / Notebook',
            'device' => 'Asus X441',
            'equipments' => 'Tas, charger',
            'device_sn' => '-',
            'problems' => 'Keyboard error',
            'actions' => 'Servis / ganti keyboard',
            'service_status' => 0,
            'order_status' => 0,
            'date_received' => '2024-05-07',
            'date_taken' => null,
            'estimated_cost' => 25000,
            'technician' => '',
            'notes' => '',
        ]);

        ServiceOrder::insert([
            'id' => 2,
            'customer_name' => 'Budi',
            'customer_contact' => '0812-3444-1455',
            'customer_address' => 'Karanganyar',
            'device_type' => 'Laptop / Notebook',
            'device' => 'Asus A407',
            'equipments' => 'Tas, charger',
            'device_sn' => '-',
            'problems' => 'LCD Blank',
            'actions' => 'Ganti LCD',
            'service_status' => 0,
            'order_status' => 0,
            'date_received' => '2024-05-09',
            'date_taken' => null,
            'estimated_cost' => 900000,
            'technician' => '',
            'notes' => '',
        ]);

        ServiceOrder::insert([
            'id' => 3,
            'customer_name' => 'Pa Nana',
            'customer_contact' => '0851-2214-0012',
            'customer_address' => 'Cigadog',
            'device_type' => 'Printer',
            'device' => 'Epson L121',
            'equipments' => '-',
            'device_sn' => '-',
            'problems' => 'Tinta Mampet',
            'actions' => 'Clean Head',
            'service_status' => 0,
            'order_status' => 0,
            'date_received' => '2024-05-09',
            'date_taken' => null,
            'estimated_cost' => 100000,
            'technician' => '',
            'notes' => '',
        ]);
    }
}
