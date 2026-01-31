<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestCartaFedeltaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cartadicredito')->insert([
            [
                'codice' => '1234567897',
                'punti' => '0',
            ],
            [
                'codice' => '1234567898',
                'punti' => '20',
            ],
        ]);
    }
}
