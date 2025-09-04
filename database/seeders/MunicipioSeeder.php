<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MunicipioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('municipios')->insert([
            // Entrância Final
            ['nome' => 'Macapá', 'entrancia' => 'final', 'nucleo' => null],
            ['nome' => 'Santana', 'entrancia' => 'final', 'nucleo' => null],
            
            // Entrância Inicial - 1º Núcleo
            ['nome' => 'Laranjal do Jari', 'entrancia' => 'inicial', 'nucleo' => 1],
            ['nome' => 'Vitória do Jari', 'entrancia' => 'inicial', 'nucleo' => 1],
            ['nome' => 'Mazagão', 'entrancia' => 'inicial', 'nucleo' => 1],
            
            // Entrância Inicial - 2º Núcleo
            ['nome' => 'Oiapoque', 'entrancia' => 'inicial', 'nucleo' => 2],
            ['nome' => 'Calçoene', 'entrancia' => 'inicial', 'nucleo' => 2],
            ['nome' => 'Amapá', 'entrancia' => 'inicial', 'nucleo' => 2],
            
            // Entrância Inicial - 3º Núcleo
            ['nome' => 'Tartarugalzinho', 'entrancia' => 'inicial', 'nucleo' => 3],
            ['nome' => 'Ferreira Gomes', 'entrancia' => 'inicial', 'nucleo' => 3],
            ['nome' => 'Porto Grande', 'entrancia' => 'inicial', 'nucleo' => 3],
            ['nome' => 'Pedra Branca do Amapari', 'entrancia' => 'inicial', 'nucleo' => 3],
        ]);
    }
}
