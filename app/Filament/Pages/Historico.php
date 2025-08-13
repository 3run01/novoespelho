<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Http\Controllers\HistoricoController;

class Historico extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?string $navigationGroup = 'Outras Informações';
    protected static string $view = 'filament.pages.historico';
    protected static ?string $title = 'Histórico';
    
    public $search = '';
    public $filter = '';
    
    public function getLogs()
    {
        $historicoController = new HistoricoController();
        return $historicoController->historico($this->search, $this->filter);
    }
    
    protected function getViewData(): array
    {
        return [
            'logs' => $this->getLogs(),
        ];
    }
}