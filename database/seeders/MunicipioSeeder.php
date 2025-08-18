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
            ['nome' => 'Macapá', 'entrancia' => 'final'],
            ['nome' => 'Santana', 'entrancia' => 'final'],
            
            // Entrância Inicial
            ['nome' => 'Laranjal do Jari', 'entrancia' => 'inicial'],
            ['nome' => 'Vitória do Jari', 'entrancia' => 'inicial'],
            ['nome' => 'Mazagão', 'entrancia' => 'inicial'],
            ['nome' => 'Oiapoque', 'entrancia' => 'inicial'],
            ['nome' => 'Calçoene', 'entrancia' => 'inicial'],
            ['nome' => 'Amapá', 'entrancia' => 'inicial'],
            ['nome' => 'Tartarugalzinho', 'entrancia' => 'inicial'],
            ['nome' => 'Ferreira Gomes', 'entrancia' => 'inicial'],
            ['nome' => 'Porto Grande', 'entrancia' => 'inicial'],
            ['nome' => 'Pedra Branca do Amapari', 'entrancia' => 'inicial'],
        ]);
    }
}
