<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Periodo;
use App\Models\Evento;
use App\Models\PlantaoAtendimento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Livewire\HelloWorld;
use App\Livewire\Espelho as EspelhoPage;
use App\Livewire\PlantaoUrgencia;
use App\Livewire\Municipios;
use App\Livewire\Promotores;
use App\Livewire\GrupoPromotores;


Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/hello', HelloWorld::class)->name('hello');

Route::get('/municipios', Municipios::class)->name('municipios');

Route::get('/promotores', Promotores::class)->name('promotores');

Route::get('/grupo-promotores', GrupoPromotores::class)->name('grupo-promotores');


