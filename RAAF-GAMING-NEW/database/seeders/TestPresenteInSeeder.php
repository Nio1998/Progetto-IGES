<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestPresenteInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('presente_in')->insert([
            [
                'magazzino' => 'Italia,Nocera Superiore',
                'prodotto' => 1,
                'quantita_disponibile' => 195,
            ],
            [
                'magazzino' => 'Italia,Nocera Superiore',
                'prodotto' => 2,
                'quantita_disponibile' => 150,
            ],
            [
                'magazzino' => 'Italia,Solofra',
                'prodotto' => 1,
                'quantita_disponibile' => 80,
            ],
            [
                'magazzino' => 'Italia,Solofra',
                'prodotto' => 3,
                'quantita_disponibile' => 120,
            ],

        ]);
    }
}
