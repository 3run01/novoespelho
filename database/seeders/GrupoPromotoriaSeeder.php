<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GrupoPromotoria;
use App\Models\Municipio;

class GrupoPromotoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar municípios existentes
        $municipios = Municipio::all();
        
        if ($municipios->isEmpty()) {
            return; // Não criar grupos se não houver municípios
        }

        $grupos = [];

        // ENTRÂNCIA FINAL - MACAPÁ
        $macapa = $municipios->where('nome', 'Macapá')->where('entrancia', 'final')->first();
        if ($macapa) {
            $grupos[] = ['nome' => 'Promotorias de Justiça', 'municipios_id' => $macapa->id];
        }

        // ENTRÂNCIA FINAL - SANTANA  
        $santana = $municipios->where('nome', 'Santana')->where('entrancia', 'final')->first();
        if ($santana) {
            $grupos[] = ['nome' => 'Cíveis e Família', 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => 'Criminais e Júri', 'municipios_id' => $santana->id];
            $grupos[] = ['nome' => 'Especializadas', 'municipios_id' => $santana->id];
        }

        // ENTRÂNCIA INICIAL - 1º Núcleo
        $municipios1Nucleo = $municipios->whereIn('nome', ['Laranjal do Jari', 'Vitória do Jari', 'Mazagão'])
                                       ->where('entrancia', 'inicial');
        foreach ($municipios1Nucleo as $municipio) {
            $grupos[] = ['nome' => 'Entrância Inicial - 1º Núcleo', 'municipios_id' => $municipio->id];
        }

        // ENTRÂNCIA INICIAL - 2º Núcleo
        $municipios2Nucleo = $municipios->whereIn('nome', ['Oiapoque', 'Calçoene', 'Amapá'])
                                       ->where('entrancia', 'inicial');
        foreach ($municipios2Nucleo as $municipio) {
            $grupos[] = ['nome' => 'Entrância Inicial - 2º Núcleo', 'municipios_id' => $municipio->id];
        }

        // ENTRÂNCIA INICIAL - 3º Núcleo
        $municipios3Nucleo = $municipios->whereIn('nome', ['Tartarugalzinho', 'Ferreira Gomes', 'Porto Grande', 'Pedra Branca do Amapari'])
                                       ->where('entrancia', 'inicial');
        foreach ($municipios3Nucleo as $municipio) {
            $grupos[] = ['nome' => 'Entrância Inicial - 3º Núcleo', 'municipios_id' => $municipio->id];
        }

        // Criar todos os grupos
        foreach ($grupos as $grupo) {
            GrupoPromotoria::create($grupo);
        }
    }
}
