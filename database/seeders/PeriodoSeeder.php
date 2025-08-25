<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PeriodoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('periodos')->insert([
            [
                'periodo_inicio' => '2025-08-05',
                'periodo_fim' => '2025-08-12',
                'status' => 'em_processo_publicacao'
            ]
        ]);
    }
}
