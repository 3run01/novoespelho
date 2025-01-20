<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Periodo;
use App\Models\Evento;
use App\Models\PlantaoAtendimento;
use Illuminate\Support\Facades\DB;

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

