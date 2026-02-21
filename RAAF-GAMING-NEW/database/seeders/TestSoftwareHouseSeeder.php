<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSoftwareHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('softwarehouse')->insert([
            [
                'nomesfh' => 'Activision',
                'logo' => null,
            ],
            [
                'nomesfh' => 'CD Project Red',
                'logo' => null,
            ],
            [
                'nomesfh' => 'Electronic Arts',
                'logo' => null,
            ],
        ]);
    }
}