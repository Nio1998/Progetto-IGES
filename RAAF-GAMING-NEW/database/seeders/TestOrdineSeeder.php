<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestOrdineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ordine')->insert([
            [
                'codice' => '15280754012',
                'data_acquisto' => '2021-12-31',
                'indirizzo_di_consegna' => 'viale croce',
                'cliente' => 'f.peluso25@gmail.com',
                'prezzo_totale' => 78.831,
                'gestore' => 'ordine@admin.com',
                'stato' => 'spedito',
                'metodo_di_pagamento' => '2134567891234567',
            ],
            [
                'codice' => '26134054612',
                'data_acquisto' => '2021-12-30',
                'indirizzo_di_consegna' => 'viale croce',
                'cliente' => 'f.peluso25@gmail.com',
                'prezzo_totale' => 80.5,
                'gestore' => 'ordine@admin.com',
                'stato' => 'spedito',
                'metodo_di_pagamento' => '2134567891234567',
            ],
            [
                'codice' => '7777777777',
                'data_acquisto' => '2021-12-30',
                'indirizzo_di_consegna' => 'viale croce',
                'cliente' => 'f.peluso25@gmail.com',
                'prezzo_totale' => 10.5,
                'gestore' => null,
                'stato' => 'in elaborazione',
                'metodo_di_pagamento' => '2134567891234567',
            ],
        ]);
    }
}