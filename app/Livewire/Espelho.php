<?php

namespace App\Livewire;

use App\Models\Periodo;
use App\Models\PlantaoAtendimento;
use App\Models\Promotoria;
use App\Models\GrupoPromotoria;
use App\Models\Municipio;
use Livewire\Component;

class Espelho extends Component
{
    public $periodos;
    public $plantoes;
    public $promotorias;
    public $promotoriasPorMunicipio;
    public $plantoesPorMunicipio;

    public function mount()
    {
        // Inicializar as coleções
        $this->periodos = collect();
        $this->plantoes = collect();
        $this->promotorias = collect();
        $this->promotoriasPorMunicipio = collect();
        $this->plantoesPorMunicipio = collect();
        
        $this->carregarDados();
    }

    public function carregarDados()
    {
        // Carregar o período mais relevante seguindo a prioridade:
        // 1º: Período publicado (mais recente se houver múltiplos)
        // 2º: Período em processo de publicação (mais recente se não houver publicado)
        // 3º: Período arquivado (apenas se não houver outros)
        $periodoPublicado = Periodo::where('status', 'publicado')
            ->orderBy('periodo_inicio', 'desc')
            ->first();
        
        if ($periodoPublicado) {
            $this->periodos = collect([$periodoPublicado]);
        } else {
            $periodoEmProcesso = Periodo::where('status', 'em_processo_publicacao')
                ->orderBy('periodo_inicio', 'desc')
                ->first();
            
            if ($periodoEmProcesso) {
                $this->periodos = collect([$periodoEmProcesso]);
            } else {
                // Se não houver período publicado nem em processo, pega o mais recente (mesmo que arquivado)
                $periodoMaisRecente = Periodo::orderBy('periodo_inicio', 'desc')->first();
                $this->periodos = $periodoMaisRecente ? collect([$periodoMaisRecente]) : collect();
            }
        }
        
        // Carregar plantões de urgência
        $this->plantoes = PlantaoAtendimento::with([
            'municipio',
            'periodo',
            'promotores' => function ($query) {
                $query->withPivot(['tipo_designacao', 'data_inicio_designacao', 'data_fim_designacao']);
            }
        ])->get();
        
        $this->promotorias = GrupoPromotoria::with([
            'promotorias.promotorTitular',
            'promotorias.eventos' => function ($query) {
                $query->with(['designacoes.promotor'])
                      ->orderBy('periodo_inicio');
            }
        ])->orderBy('nome')->get();

        $this->organizarDadosPorMunicipio();
    }

    private function organizarDadosPorMunicipio()
    {
        $promotoriasPorMunicipio = [];
        $plantoesPorMunicipio = [];

        $promotorias = Promotoria::with([
            'grupoPromotoria.municipio',
            'promotorTitular',
            'eventos' => function ($query) {
                $query->with(['designacoes.promotor'])
                      ->orderBy('periodo_inicio');
            }
        ])->get();

        foreach ($promotorias as $promotoria) {
            $nomeMunicipio = $promotoria->grupoPromotoria && $promotoria->grupoPromotoria->municipio 
                ? $promotoria->grupoPromotoria->municipio->nome 
                : 'Sem município';
            
            if (!isset($promotoriasPorMunicipio[$nomeMunicipio])) {
                $promotoriasPorMunicipio[$nomeMunicipio] = collect();
            }
            
            $promotoriasPorMunicipio[$nomeMunicipio]->push($promotoria);
        }


        foreach ($this->plantoes as $plantao) {
            $nomeMunicipio = $plantao->municipio ? $plantao->municipio->nome : 'Sem município';
            
            if (!isset($plantoesPorMunicipio[$nomeMunicipio])) {
                $plantoesPorMunicipio[$nomeMunicipio] = collect();
            }
            
            $plantoesPorMunicipio[$nomeMunicipio]->push($plantao);
        }

        ksort($promotoriasPorMunicipio);
        ksort($plantoesPorMunicipio);
        
        $this->promotoriasPorMunicipio = collect($promotoriasPorMunicipio);
        $this->plantoesPorMunicipio = collect($plantoesPorMunicipio);
    }

    public function render()
    {
        return view('livewire.espelho.espelho');
    }
}