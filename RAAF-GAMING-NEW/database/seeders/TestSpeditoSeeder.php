<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSpeditoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('spedito')->insert([
            [
                'ordine' => '15280754012',
                'corriere_espresso' => 'SDA',
                'data_consegna' => '2022-02-02',
            ],
            [
                'ordine' => '26134054612',
                'corriere_espresso' => 'DHL',
                'data_consegna' => '2022-03-03',
            ],
        ]);
    }
}