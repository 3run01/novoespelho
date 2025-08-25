<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Periodo;
use App\Models\Evento;
use App\Models\PlantaoAtendimento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Livewire\Main;
use App\Livewire\Espelho as EspelhoPage;
use App\Livewire\PlantaoUrgencia;
use App\Livewire\Municipios;
use App\Livewire\Promotores;
use App\Livewire\GrupoPromotores;
use App\Livewire\Promotorias;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\EspelhoPdfController;





Route::get('/gestao-espelho', Main::class)->name('gestao-espelho');

Route::get('/municipios', Municipios::class)->name('municipios');

Route::get('/promotores', Promotores::class)->name('promotores');

Route::get('/grupo-promotores', GrupoPromotores::class)->name('grupo-promotores');

Route::get('/promotorias', Promotorias::class)->name('promotorias');

// Rotas para PDFs
Route::prefix('pdf')->group(function () {
    Route::get('/generate/{viewName}', [PdfController::class, 'generateFromView'])->name('pdf.generate');
    Route::post('/generate-html', [PdfController::class, 'generateFromHtml'])->name('pdf.generate-html');
    Route::get('/stream/{viewName}', [PdfController::class, 'streamPdf'])->name('pdf.stream');
    Route::get('/report/{reportType}', [PdfController::class, 'generateReport'])->name('pdf.report');
    Route::post('/save/{viewName}', [PdfController::class, 'savePdf'])->name('pdf.save');
});

// Rotas para PDFs do Espelho
Route::prefix('espelho')->group(function () {
    Route::get('/pdf/completo', [EspelhoPdfController::class, 'gerarEspelhoCompleto'])->name('espelho.pdf.completo');
    Route::get('/pdf/municipio/{municipioId}', [EspelhoPdfController::class, 'gerarEspelhoPorMunicipio'])->name('espelho.pdf.municipio');
    Route::get('/pdf/visualizar', [EspelhoPdfController::class, 'visualizarEspelho'])->name('espelho.pdf.visualizar');
});


