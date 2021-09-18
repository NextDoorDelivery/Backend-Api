<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\LkVerificationStatus AS LkVerificationStatusModel;

class LkVerificationStatus extends Seeder
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
        LkVerificationStatusModel::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');


        $data = [

            [
                'LkVerificationStatusId'=> 1,
                'description' => 'success'
            ],
            [
                'LkVerificationStatusId'=> 2,
                'description' => 'error'
            ],
            [
                'LkVerificationStatusId'=> 3,
                'description' => 'in-progress'
            ],
            [
                'LkVerificationStatusId'=> 4,
                'description' => 'unverified'
            ],
            [
                'LkVerificationStatusId'=> 5,
                'description' => 'customer-verified'
            ],

        ];

        LkVerificationStatusModel::insert($data);

    }
}
