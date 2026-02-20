<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestFornitoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fornitore')->insert([
            [
                'nome' => 'AMD',
                'indirizzo' => 'Giappone',
                'telefono' => '089343743',
            ],
            [
                'nome' => 'Nvidia',
                'indirizzo' => 'America',
                'telefono' => '089343701',
            ],
            [
                'nome' => 'Sony',
                'indirizzo' => 'Giappone',
                'telefono' => '081931798',
            ],
        ]);
    }
}