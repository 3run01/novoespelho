<?php

namespace App\Livewire;

use App\Models\Promotoria;
use App\Models\Periodo;
use App\Models\Evento;
use App\Models\PlantaoAtendimento;
use Livewire\Component;

class Espelho extends Component
{
    public $promotorias;
    public $periodos;
    public $plantoes;

    public function mount()
    {
        $this->promotorias = Promotoria::with([
            'grupoPromotoria.municipio',
            'eventos' => function($query) {
                $query->with('promotores');
            }
        ])->get();

        $this->periodos = Periodo::orderBy('periodo_inicio', 'desc')->get();

        $this->plantoes = PlantaoAtendimento::with([
            'municipio',
            'periodo',
            'promotores'
        ])->get();
    }

    public function getPromotoriasPorMunicipioProperty()
    {
        return $this->promotorias->groupBy(function($promotoria) {
            return $promotoria->grupoPromotoria?->municipio?->nome ?? 'Sem município';
        });
    }

    public function render()
    {
        return view('livewire.espelho');
    }
}
