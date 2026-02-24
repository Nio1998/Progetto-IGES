<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestCorriereEspressoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('corriereespresso')->insert([
            [
                'nome' => 'bartolini',
                'sito' => 'bartolini.com',
            ],
            [
                'nome' => 'ups',
                'sito' => 'ups.com',
            ],
            [
                'nome' => 'dhl',
                'sito' => 'dhl.com',
            ],
            [
                'nome' => 'lol',
                'sito' => 'lol.com',
            ],
        ]);
    }
}