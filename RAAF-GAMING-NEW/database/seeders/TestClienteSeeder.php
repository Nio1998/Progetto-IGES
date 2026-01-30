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
                'email' => 'antoniodelucia@hotmail.com',
                'nome' => 'antonio',
                'cognome' => 'de lucia',
                'data_di_nascita' => '1998-12-06',
                'password' => 'Zlatanpato98',
                'carta_fedelta' => '1234567899',
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
