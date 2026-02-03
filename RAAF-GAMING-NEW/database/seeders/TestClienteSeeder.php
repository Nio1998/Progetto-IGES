<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cliente')->insert([
            [
                'email' => 'f.peluso25@gmail.com',
                'nome' => 'Francesco',
                'cognome' => 'Peluso',
                'data_di_nascita' => '2000-08-24',
                'password' => 'veloce123',
                'carta_fedelta' => '1234567897',
                'cartadicredito' => '1234123412341235',
            ],
            [
                'email' => 'antoniomaddaloni@hotmail.com',
                'nome' => 'antonio',
                'cognome' => 'maddaloni',
                'data_di_nascita' => '1998-11-15',
                'password' => 'Nola123',
                'carta_fedelta' => '1234567898',
                'cartadicredito' => '1234123412341236',
            ],
        ]);

    }
}
