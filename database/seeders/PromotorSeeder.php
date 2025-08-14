<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotor;

class PromotorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotores = [
            [
                'nome' => 'Dr. João Silva',
                'tipo' => 'titular',
                'is_substituto' => false,
                'observacoes' => 'Promotor titular da 1ª Promotoria de Justiça'
            ],
            [
                'nome' => 'Dra. Maria Santos',
                'tipo' => 'titular',
                'is_substituto' => false,
                'observacoes' => 'Promotora titular da 2ª Promotoria de Justiça'
            ],
            [
                'nome' => 'Dr. Carlos Oliveira',
                'tipo' => 'substituto',
                'is_substituto' => true,
                'observacoes' => 'Promotor substituto atuando em plantões'
            ],
            [
                'nome' => 'Dra. Ana Costa',
                'tipo' => 'auxiliar',
                'is_substituto' => false,
                'observacoes' => 'Promotora auxiliar da 1ª Promotoria'
            ],
            [
                'nome' => 'Dr. Pedro Lima',
                'tipo' => 'substituto',
                'is_substituto' => true,
                'observacoes' => 'Promotor substituto para férias e licenças'
            ]
        ];

        foreach ($promotores as $promotor) {
            Promotor::create($promotor);
        }
    }
}
