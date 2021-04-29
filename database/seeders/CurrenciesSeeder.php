<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            [
                'symbol' => 'EUR',
                'name' => 'Euro',
                'combined_name' => 'EUR - Euro',
            ],
            [
                'symbol' => 'USD',
                'name' => 'United States Dollar',
                'combined_name' => 'USD - United States Dollar',
            ],
            [
                'symbol' => 'CZK',
                'name' => 'Czech Republic Koruna',
                'combined_name' => 'CZK - Czech Republic Koruna',
            ]
        ]);
    }
}
