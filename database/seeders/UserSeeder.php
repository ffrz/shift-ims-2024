<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        User::insert([
            'username' => 'admin',
            'password' => Hash::make('12345'),
            'is_active' => true,
            'is_admin' => true,
            'fullname' => 'Administrator',
            'group_id' => 1,
        ]);
        User::insert([
            'username' => 'kasir',
            'password' => Hash::make('12345'),
            'is_active' => true,
            'is_admin' => false,
            'fullname' => 'Kasir',
            'group_id' => 2,
        ]);

        // $faker = \Faker\Factory::create('id_ID');
        // DB::beginTransaction();
        // $pw = Hash::make('12345');
        // for ($i = 3; $i <= 100; $i++) {
        //     User::insert([
        //         'id' => $i,
        //         'username' => 'user' . $i,
        //         'password' => $pw,
        //         'is_active' => rand(0, 1),
        //         'is_admin' => rand(0, 1),
        //         'fullname' => $faker->name(),
        //         'group_id' => rand(1, 2),
        //     ]);
        // }
        // DB::commit();
    }
}
