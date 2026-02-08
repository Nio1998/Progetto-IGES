<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestProdottoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     DB::table('prodotto')->insert([
            [
                'codice_prodotto' => 1,
                'prezzo' => 10.5,
                'sconto' => 0,
                'data_uscita' => '2021-12-25',
                'nome' => 'FIFA',
                'quantita_fornitura' => 12,
                'data_fornitura' => '2020-12-20',
                'fornitore' => 'Sony',
                'gestore' => 'prodotto@admin.com',
            ],
            [
                'codice_prodotto' => 2,
                'prezzo' => 15.5,
                'sconto' => 0,
                'data_uscita' => '2020-12-22',
                'nome' => 'PES',
                'quantita_fornitura' => 12,
                'data_fornitura' => '2019-12-20',
                'fornitore' => 'Activision',
                'gestore' => 'prodotto@admin.com',
            ],
            [
                'codice_prodotto' => 3,
                'prezzo' => 59.99,
                'sconto' => 10,
                'data_uscita' => '2022-03-15',
                'nome' => 'Elden Ring',
                'quantita_fornitura' => 25,
                'data_fornitura' => '2022-03-10',
                'fornitore' => 'Bandai Namco',
                'gestore' => 'prodotto@admin.com',
            ],
            [
                'codice_prodotto' => 4,
                'prezzo' => 49.99,
                'sconto' => 20,
                'data_uscita' => '2026-03-15',
                'nome' => 'Expedition 33',
                'quantita_fornitura' => 15,
                'data_fornitura' => '2022-03-10',
                'fornitore' => 'Sony',
                'gestore' => 'prodotto@admin.com',
            ],
        ]);
    }
}
