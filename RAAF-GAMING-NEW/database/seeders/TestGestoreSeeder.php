<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestGestoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gestore')->insert([
            [
                'email' => 'gestore@hotmail.it',
                'ruolo' => 'ordine',
                'password' => '123456789',
            ],
            [
                'email' => 'gestore2@hotmail.it',
                'ruolo' => 'ordine',
                'password' => '987654321',
            ],
            [
                'email' => 'gestore3@hotmail.it',
                'ruolo' => 'prodotto',
                'password' => '1234567888',
            ],
        ]);
    }
}
