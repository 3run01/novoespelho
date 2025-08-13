<?php

namespace App\Filament\Widgets;

use App\Models\Evento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\PlantaoAtendimento;

class Plantoes extends BaseWidget
{
    protected function getStats(): array
    {
        $chart = Evento::count();
        $totalMudancas = PlantaoAtendimento::count();
        return [
            Stat::make('Total de Eventos', Evento::count())
                ->description('Todos os eventos cadastrados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([$chart - 4, $chart + ($chart + 1),$chart + ($chart + 2), $chart + ($chart + 3)])
                ->color('success'),
            Stat::make('Total de Plantões de Emergência', $totalMudancas)
                ->description('Total de plantões de emergência')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([$totalMudancas - 4, $totalMudancas + ($totalMudancas + 1),$totalMudancas + ($totalMudancas + 2), $totalMudancas + ($totalMudancas + 3)])
                ->color('success'),
        ];
    }
}

