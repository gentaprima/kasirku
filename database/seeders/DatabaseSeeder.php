<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('tbl_users')->insert(
            [
                [
                    'email' => "admin@gmail.com",
                    'nama_lengkap' => "Admin",
                    'password' => Hash::make('admin'),
                    'role' => 1,
                ],
                [
                    'email' => "genta@gmail.com",
                    'nama_lengkap' => "Genta",
                    'password' => Hash::make('genta'),
                    'role' => 0,
                ]
            ]
               
        );

        DB::table('tbl_topping')->insert([
            [
                'topping_name' => "Tanpa Topping",
                'price'        => 0,
                'type'         => "Makanan"
            ],
            [
                'topping_name' => "Keju",
                'price'        => 3000,
                'type'         => "Makanan"
            ],
            [
                'topping_name' => "Telor",
                'price'        => 3000,
                'type'         => "Makanan"
            ],
            [
                'topping_name' => "Kornet",
                'price'        => 4000,
                'type'         => "Makanan"
            ],
        ]);
    }
}
