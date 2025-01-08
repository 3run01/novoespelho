<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MunicipioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('municipios')->insert([
            ['nome' => 'Brasilia'],
            ['nome' => 'São Paulo'],
            ['nome' => 'Rio de Janeiro'],
            ['nome' => 'Belo Horizonte'],
            ['nome' => 'Salvador'],
        ]);

        DB::table('grupo_promotorias')->insert([
            ['nome' => 'Grupo de Brasilia', 'municipios_id' => DB::table('municipios')->where('nome', 'Brasilia')->value('id')],
            ['nome' => 'Grupo de São Paulo', 'municipios_id' => DB::table('municipios')->where('nome', 'São Paulo')->value('id')],
            ['nome' => 'Grupo de Rio de Janeiro', 'municipios_id' => DB::table('municipios')->where('nome', 'Rio de Janeiro')->value('id')],
            ['nome' => 'Grupo de Belo Horizonte', 'municipios_id' => DB::table('municipios')->where('nome', 'Belo Horizonte')->value('id')],
            ['nome' => 'Grupo de Salvador', 'municipios_id' => DB::table('municipios')->where('nome', 'Salvador')->value('id')],
        ]);

        DB::table('promotores')->insert([
            ['nome' => 'Carlos Marcos Paulo', 'is_substituto' => false],
            ['nome' => 'João Silva', 'is_substituto' => false],
            ['nome' => 'Maria Oliveira', 'is_substituto' => false],
            ['nome' => 'Ana Costa', 'is_substituto' => false],
            ['nome' => 'Pedro Santos', 'is_substituto' => false],
        ]);

        DB::table('promotorias')->insert([
            ['nome' => 'Promotoria de Brasilia', 'promotor_id' => DB::table('promotores')->where('nome', 'Carlos Marcos Paulo')->value('id'), 'grupo_promotoria_id' => DB::table('grupo_promotorias')->where('nome', 'Grupo de Brasilia')->value('id')],
            ['nome' => 'Promotoria de São Paulo', 'promotor_id' => DB::table('promotores')->where('nome', 'João Silva')->value('id'), 'grupo_promotoria_id' => DB::table('grupo_promotorias')->where('nome', 'Grupo de São Paulo')->value('id')],
            ['nome' => 'Promotoria de Rio de Janeiro', 'promotor_id' => DB::table('promotores')->where('nome', 'Maria Oliveira')->value('id'), 'grupo_promotoria_id' => DB::table('grupo_promotorias')->where('nome', 'Grupo de Rio de Janeiro')->value('id')],
            ['nome' => 'Promotoria de Belo Horizonte', 'promotor_id' => DB::table('promotores')->where('nome', 'Ana Costa')->value('id'), 'grupo_promotoria_id' => DB::table('grupo_promotorias')->where('nome', 'Grupo de Belo Horizonte')->value('id')],
            ['nome' => 'Promotoria de Salvador', 'promotor_id' => DB::table('promotores')->where('nome', 'Pedro Santos')->value('id'), 'grupo_promotoria_id' => DB::table('grupo_promotorias')->where('nome', 'Grupo de Salvador')->value('id')],
        ]);
    }
}
