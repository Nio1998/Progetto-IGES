<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestMagazzinoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('magazzino')->insert([
            [
                'indirizzo' => 'Italia,Nocera Superiore',
                'capienza' => 1000,
            ],
            [
                'indirizzo' => 'Italia,Solofra',
                'capienza' => 1000,
            ],
        ]);
    }
}