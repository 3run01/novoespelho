<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Evento;

class EventosPromotor extends ChartWidget
{
    protected static ?string $heading = 'Tipos de Eventos ';

    protected function getData(): array
    {
        return [
            $data = $this->typeEventosPromotor(),
            'datasets' => [
                [
                    'label' => 'Eventos',
                    'data' => array_map('intval', $data['total']),
                ],
            ],
            'labels' => $data['tipos'],
            'colors' => ['info'],
            'type' => 'line',
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function typeEventosPromotor(): array 
    {
        $eventos = Evento::select('tipo', \DB::raw('COUNT(*) as total'))
            ->groupBy('tipo')
            ->get();

        $tipos = $eventos->pluck('tipo')->toArray();
        $totais = $eventos->pluck('total')->toArray();

        return [
            'tipos' => $tipos,
            'total' => $totais,
        ];
    }
}