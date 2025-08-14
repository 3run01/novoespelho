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
    public $periodos = [];
    public $plantoes = [];
    public $promotorias = [];
    public $promotoriasPorMunicipio = [];

    public function mount()
    {
        $this->carregarDados();
    }

    public function carregarDados()
    {
        // Carregar períodos
        $this->periodos = Periodo::orderBy('periodo_inicio', 'desc')->get();
        
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

        $this->organizarPromotoriasPorMunicipio();
    }

    private function organizarPromotoriasPorMunicipio()
    {
        $promotoriasPorMunicipio = [];

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

        ksort($promotoriasPorMunicipio);
        
        $this->promotoriasPorMunicipio = $promotoriasPorMunicipio;
    }

    public function render()
    {
        return view('livewire.espelho.espelho');
    }
}