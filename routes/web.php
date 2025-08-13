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





Route::get('/gestao-espelho', Main::class)->name('gestao-espelho');

Route::get('/municipios', Municipios::class)->name('municipios');

Route::get('/promotores', Promotores::class)->name('promotores');

Route::get('/grupo-promotores', GrupoPromotores::class)->name('grupo-promotores');

Route::get('/promotorias', Promotorias::class)->name('promotorias');


