<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MunicipioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('municipios')->insert([
            ['nome' => 'Macapá'],
            ['nome' => 'Santana'],
            ['nome' => 'Laranjal do Jari'],
            ['nome' => 'Vitória do Jari'],
            ['nome' => 'Mazagão'],
            ['nome' => 'Oiapoque'],
            ['nome' => 'Calçoene'],
            ['nome' => 'Amapá'],
            ['nome' => 'Tartarugalzinho'],
            ['nome' => 'Ferreira Gomes'],
            ['nome' => 'Porto Grande'],
            ['nome' => 'Pedra Branca do Amapari'],
        ]);

        DB::table('grupo_promotorias')->insert([
            ['nome' => 'Grupo de Macapá', 'municipios_id' => DB::table('municipios')->where('nome', 'Macapá')->value('id')],
            ['nome' => 'Grupo de Santana', 'municipios_id' => DB::table('municipios')->where('nome', 'Santana')->value('id')],
            ['nome' => 'Grupo de Laranjal do Jari', 'municipios_id' => DB::table('municipios')->where('nome', 'Laranjal do Jari')->value('id')],
            ['nome' => 'Grupo de Vitória do Jari', 'municipios_id' => DB::table('municipios')->where('nome', 'Vitória do Jari')->value('id')],
            ['nome' => 'Grupo de Mazagão', 'municipios_id' => DB::table('municipios')->where('nome', 'Mazagão')->value('id')],
            ['nome' => 'Grupo de Oiapoque', 'municipios_id' => DB::table('municipios')->where('nome', 'Oiapoque')->value('id')],
            ['nome' => 'Grupo de Calçoene', 'municipios_id' => DB::table('municipios')->where('nome', 'Calçoene')->value('id')],
            ['nome' => 'Grupo de Amapá', 'municipios_id' => DB::table('municipios')->where('nome', 'Amapá')->value('id')],
            ['nome' => 'Grupo de Tartarugalzinho', 'municipios_id' => DB::table('municipios')->where('nome', 'Tartarugalzinho')->value('id')],
            ['nome' => 'Grupo de Ferreira Gomes', 'municipios_id' => DB::table('municipios')->where('nome', 'Ferreira Gomes')->value('id')],
            ['nome' => 'Grupo de Porto Grande', 'municipios_id' => DB::table('municipios')->where('nome', 'Porto Grande')->value('id')],
            ['nome' => 'Grupo de Pedra Branca do Amapari', 'municipios_id' => DB::table('municipios')->where('nome', 'Pedra Branca do Amapari')->value('id')],
        ]);

        DB::table('promotores')->insert([
            ['nome' => 'Carlos Marcos Paulo', 'is_substituto' => false],
            ['nome' => 'João Silva', 'is_substituto' => false],
            ['nome' => 'Maria Oliveira', 'is_substituto' => false],
            ['nome' => 'Ana Costa', 'is_substituto' => false],
            ['nome' => 'Pedro Santos', 'is_substituto' => false],
        ]);

        DB::table('promotorias')->insert([
            ['nome' => 'Promotoria de Macapá', 'promotor_id' => DB::table('promotores')->where('nome', 'Carlos Marcos Paulo')->value('id'), 'grupo_promotoria_id' => DB::table('grupo_promotorias')->where('nome', 'Grupo de Macapá')->value('id')],
            ['nome' => 'Promotoria de Santana', 'promotor_id' => DB::table('promotores')->where('nome', 'João Silva')->value('id'), 'grupo_promotoria_id' => DB::table('grupo_promotorias')->where('nome', 'Grupo de Santana')->value('id')],
            ['nome' => 'Promotoria de Laranjal do Jari', 'promotor_id' => DB::table('promotores')->where('nome', 'Maria Oliveira')->value('id'), 'grupo_promotoria_id' => DB::table('grupo_promotorias')->where('nome', 'Grupo de Laranjal do Jari')->value('id')],
            ['nome' => 'Promotoria de Vitória do Jari', 'promotor_id' => DB::table('promotores')->where('nome', 'Ana Costa')->value('id'), 'grupo_promotoria_id' => DB::table('grupo_promotorias')->where('nome', 'Grupo de Vitória do Jari')->value('id')],
            ['nome' => 'Promotoria de Mazagão', 'promotor_id' => DB::table('promotores')->where('nome', 'Pedro Santos')->value('id'), 'grupo_promotoria_id' => DB::table('grupo_promotorias')->where('nome', 'Grupo de Mazagão')->value('id')],
        ]);
    }
}
