<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestRiguardaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('riguarda')->insert([
            [
                'prodotto' => 1,
                'ordine' => '15280754012',
                'quantita_acquistata' => 1,
            ],
            [
                'prodotto' => 2,
                'ordine' => '26134054612',
                'quantita_acquistata' => 1,
            ],
            [
                'prodotto' => 3,
                'ordine' => '15280754012',
                'quantita_acquistata' => 1,
            ],
        ]);
    }
}