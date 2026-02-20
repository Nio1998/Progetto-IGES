<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestParteDiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('parte_di')->insert([
            ['videogioco' => 11, 'categoria' => 'Avventura'],
            ['videogioco' => 22, 'categoria' => 'Arcade'],
            ['videogioco' => 17, 'categoria' => 'Avventura'],
        ]);
    }
}