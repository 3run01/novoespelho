<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Periodo;
use App\Models\Evento;
use App\Models\PlantaoAtendimento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Route::get('/', function () {
    return redirect('/admin/espelho'); 
});



Route::get('/download-pdf', function () {
    // Busca o período atual ou o último período cadastrado
    $periodo = Periodo::orderBy('created_at', 'desc')->first();
    
    if (!$periodo) {
        return redirect()->back()->with('error', 'Nenhum período encontrado');
    }
    
    // Busca os eventos relacionados ao período
    $eventos = Evento::with(['promotorTitular', 'promotorDesignado', 'promotoria'])
        ->where('periodo_id', $periodo->id)
        ->orderBy('periodo_inicio')
        ->get();
    
    // Ajuste na busca dos plantões usando a mesma estrutura do Controller
    $plantoes = DB::table('plantao_atendimento as pa')
        ->join('promotores as p', 'pa.promotor_designado_id', '=', 'p.id')
        ->select(
            'pa.id as plantao_id',
            'pa.periodo_inicio',
            'pa.periodo_fim',
            'pa.promotor_designado_id',
            'p.nome as promotor'
        )
        ->where('pa.periodo_id', $periodo->id)
        ->orderBy('pa.periodo_inicio')
        ->get();

    // Debug para verificar os plantões
    \Log::info('Plantões encontrados:', ['plantoes' => $plantoes->toArray()]);

    // Configuração do PDF com opções para imagens
    $pdf = PDF::loadView('filament.pages.components.PDF.pdf', [
        'periodo' => $periodo,
        'eventos' => $eventos,
        'plantoes' => $plantoes
    ])->setPaper('a4');
    
    return $pdf->download('espelho_eventos.pdf');
})->name('download-pdf');

Route::get('/download-relatorio-pdf/{periodoId?}', function ($periodoId = null) {
    // Se não houver período específico, pega o último
    if (!$periodoId) {
        $periodo = Periodo::orderBy('created_at', 'desc')->first();
    } else {
        $periodo = Periodo::findOrFail($periodoId);
    }
    
    if (!$periodo) {
        return redirect()->back()->with('error', 'Nenhum período encontrado');
    }

    // Busca os eventos do período
    $eventos = Evento::with(['promotorTitular', 'promotorDesignado', 'promotoria'])
        ->where('periodo_id', $periodo->id)
        ->orderBy('periodo_inicio')
        ->get()
        ->map(function ($evento) {
            $evento->total_dias = abs(Carbon::parse($evento->periodo_fim)->diffInDays(Carbon::parse($evento->periodo_inicio))) + 1;
            return $evento;
        });

    // Busca os plantões do período
    $plantoes = PlantaoAtendimento::join('promotores', 'plantao_atendimento.promotor_designado_id', '=', 'promotores.id')
        ->where('plantao_atendimento.periodo_id', $periodo->id)
        ->select(
            'plantao_atendimento.*',
            'promotores.nome as promotor_nome',
            DB::raw('ABS(plantao_atendimento.periodo_fim::date - plantao_atendimento.periodo_inicio::date) + 1 as total_dias')
        )
        ->orderBy('plantao_atendimento.periodo_inicio')
        ->get();

    // Calcula o total por promotor
    $totalDiasPorPromotor = DB::table('plantao_atendimento')
        ->join('promotores', 'plantao_atendimento.promotor_designado_id', '=', 'promotores.id')
        ->where('plantao_atendimento.periodo_id', $periodo->id)
        ->select(
            'promotores.nome',
            DB::raw('COUNT(DISTINCT plantao_atendimento.id) as total_plantoes'),
            DB::raw('SUM(ABS(plantao_atendimento.periodo_fim::date - plantao_atendimento.periodo_inicio::date) + 1) as total_dias')
        )
        ->groupBy('promotores.nome')
        ->orderBy('total_dias', 'desc')
        ->get();

    $pdf = PDF::loadView('filament.pages.components.PDF.relatorio-pdf', [
        'periodo' => $periodo,
        'eventos' => $eventos,
        'plantoes' => $plantoes,
        'totalDiasPorPromotor' => $totalDiasPorPromotor
    ])->setPaper('a4');
    
    return $pdf->download('relatorio_periodo.pdf');
})->name('download-relatorio-pdf');

