<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotoria;
use App\Models\Promotor;
use App\Models\GrupoPromotoria;

class PromotoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar promotores e grupos existentes
        $promotores = Promotor::all();
        $grupos = GrupoPromotoria::all();
        
        if ($promotores->isEmpty() || $grupos->isEmpty()) {
            return; // Não criar promotorias se não houver promotores ou grupos
        }

        $promotorias = [];

        // MACAPÁ - Promotorias Especializadas
        $gruposMacapa = $grupos->where('municipios_id', $grupos->where('nome', '1ª PJ Cível')->first()?->municipios_id);
        if ($gruposMacapa->isNotEmpty()) {
            foreach ($gruposMacapa as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // SANTANA - Promotorias Especializadas
        $gruposSantana = $grupos->where('municipios_id', $grupos->where('nome', '1ª Promotoria Cível e Famílias')->first()?->municipios_id);
        if ($gruposSantana->isNotEmpty()) {
            foreach ($gruposSantana as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // LARANJAL DO JARI
        $gruposLaranjal = $grupos->where('municipios_id', $grupos->where('nome', '1ª Promotoria')->first()?->municipios_id);
        if ($gruposLaranjal->isNotEmpty()) {
            foreach ($gruposLaranjal as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // VITÓRIA DO JARI
        $gruposVitoria = $grupos->where('municipios_id', $grupos->where('nome', 'Promotoria')->first()?->municipios_id);
        if ($gruposVitoria->isNotEmpty()) {
            foreach ($gruposVitoria as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // MAZAGÃO
        $gruposMazagao = $grupos->where('municipios_id', $grupos->where('nome', 'Promotoria')->first()?->municipios_id);
        if ($gruposMazagao->isNotEmpty()) {
            foreach ($gruposMazagao as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // OIAPOQUE
        $gruposOiapoque = $grupos->where('municipios_id', $grupos->where('nome', '1ª Promotoria')->first()?->municipios_id);
        if ($gruposOiapoque->isNotEmpty()) {
            foreach ($gruposOiapoque as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // CALÇOENE
        $gruposCalcoene = $grupos->where('municipios_id', $grupos->where('nome', 'Promotoria')->first()?->municipios_id);
        if ($gruposCalcoene->isNotEmpty()) {
            foreach ($gruposCalcoene as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // AMAPÁ
        $gruposAmapa = $grupos->where('municipios_id', $grupos->where('nome', 'Promotoria')->first()?->municipios_id);
        if ($gruposAmapa->isNotEmpty()) {
            foreach ($gruposAmapa as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // TARTARUGALZINHO
        $gruposTartarugalzinho = $grupos->where('municipios_id', $grupos->where('nome', 'Promotoria')->first()?->municipios_id);
        if ($gruposTartarugalzinho->isNotEmpty()) {
            foreach ($gruposTartarugalzinho as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // FERREIRA GOMES
        $gruposFerreira = $grupos->where('municipios_id', $grupos->where('nome', 'Promotoria')->first()?->municipios_id);
        if ($gruposFerreira->isNotEmpty()) {
            foreach ($gruposFerreira as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // PORTO GRANDE
        $gruposPortoGrande = $grupos->where('municipios_id', $grupos->where('nome', 'Promotoria')->first()?->municipios_id);
        if ($gruposPortoGrande->isNotEmpty()) {
            foreach ($gruposPortoGrande as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // PEDRA BRANCA DO AMAPARI
        $gruposPedraBranca = $grupos->where('municipios_id', $grupos->where('nome', 'Promotoria')->first()?->municipios_id);
        if ($gruposPedraBranca->isNotEmpty()) {
            foreach ($gruposPedraBranca as $grupo) {
                $promotorias[] = [
                    'nome' => $grupo->nome,
                    'promotor_id' => $promotores->random()->id,
                    'grupo_promotoria_id' => $grupo->id
                ];
            }
        }

        // Criar todas as promotorias
        foreach ($promotorias as $promotoria) {
            Promotoria::create($promotoria);
        }
    }
}
