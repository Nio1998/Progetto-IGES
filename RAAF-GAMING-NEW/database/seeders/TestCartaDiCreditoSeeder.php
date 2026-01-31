<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestCartaDiCreditoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('cartadicredito')->insert([
            [
                'codicecarta' => '1234123412341235',
                'data_scadenza' => '12/12/2028',
                'codice_cvv' => '012',
            ],
            [
                'codicecarta' => '4321123412341234',
                'data_scadenza' => '12/12/2030',
                'codice_cvv' => '666',
            ],
        ]);
    }
}
