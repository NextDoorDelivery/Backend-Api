<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->initiateSeed();
    }

    private function initiateSeed(){

        // Foreign key check must be turned off before truncation, then turned back on.
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');


        $data = [

            [
                'id'=>1,
                'name'=>'main admin',
                'email'=>'main_admin@nexteradelivery.com',
                'password'=>bcrypt('nexteradelivery2021@'),
                'isAdmin'=>1,
            ],
            [
                'id'=>2,
                'name'=>'registrar device 1',
                'email'=>'registrar_1@nexteradelivery.com',
                'password'=>bcrypt('registrar_2021@'),
                'isAdmin'=>0,
            ]

        ];

        User::insert($data);

    }

}
