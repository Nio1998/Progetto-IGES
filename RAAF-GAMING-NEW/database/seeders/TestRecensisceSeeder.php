<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestRecensisceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('recensisce')->insert([
            ['cliente' => 'f.peluso25@gmail.com','prodotto' => 2, 'voto' => 10, 'commento' => 'bellissmo'],
            ['cliente' => 'antoniomaddaloni@hotmail.com', 'prodotto' => 2, 'voto' => 7,  'commento' => 'vero veramente'],
        ]);
    }
}