<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Periodo;
use App\Models\Evento;
use App\Models\PlantaoAtendimento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class Relatorio extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.relatorio';

    public $dataInicial;
    public $dataFinal;
    public $plantoes;
    public $eventos;
    public $totalDiasPorPromotor;
    public $mostrarTodos = false;
    public $itensPorPagina = 10;
    public $periodos;
    public $periodoSelecionado;

    protected $queryString = ['mostrarTodos'];

    public function mount()
    {
        // Carrega todos os períodos disponíveis
        $this->periodos = Periodo::orderBy('periodo_inicio', 'desc')->get();
        
        // Inicializa com o período mais recente se não estiver mostrando todos
        if (!$this->mostrarTodos) {
            $ultimoPeriodo = $this->periodos->first();
            if ($ultimoPeriodo) {
                $this->periodoSelecionado = $ultimoPeriodo->id;
                $this->dataInicial = $ultimoPeriodo->periodo_inicio;
                $this->dataFinal = $ultimoPeriodo->periodo_fim;
                $this->gerarRelatorio();
            }
        }
    }

    public function toggleMostrarTodos()
    {
        $this->mostrarTodos = !$this->mostrarTodos;
        if ($this->mostrarTodos) {
            $this->gerarRelatorioCompleto();
        } else {
            // Volta para o período mais recente
            $this->selecionarPeriodo($this->periodos->first()->id);
        }
    }

    public function gerarRelatorioCompleto()
    {
        try {
            // Limpa os dados anteriores
            $this->reset(['eventos', 'plantoes', 'totalDiasPorPromotor']);

            // Busca todos os eventos com paginação
            $this->eventos = Evento::with(['promotorTitular', 'promotorDesignado', 'promotoria'])
                ->orderBy('periodo_inicio', 'desc')
                ->paginate($this->itensPorPagina);

            // Adiciona total_dias para cada evento
            $this->eventos->getCollection()->transform(function ($evento) {
                $evento->total_dias = Carbon::parse($evento->periodo_fim)->diffInDays(Carbon::parse($evento->periodo_inicio)) + 1;
                return $evento;
            });

            // Busca todos os plantões com paginação
            $this->plantoes = PlantaoAtendimento::join('promotores', 'plantao_atendimento.promotor_designado_id', '=', 'promotores.id')
                ->select(
                    'plantao_atendimento.*',
                    'promotores.nome as promotor_nome',
                    DB::raw('(plantao_atendimento.periodo_fim::date - plantao_atendimento.periodo_inicio::date) + 1 as total_dias')
                )
                ->orderBy('plantao_atendimento.periodo_inicio', 'desc')
                ->paginate($this->itensPorPagina);

            // Calcula o total geral por promotor
            $this->totalDiasPorPromotor = DB::table('plantao_atendimento')
                ->join('promotores', 'plantao_atendimento.promotor_designado_id', '=', 'promotores.id')
                ->select(
                    'promotores.nome',
                    DB::raw('COUNT(DISTINCT plantao_atendimento.id) as total_plantoes'),
                    DB::raw('SUM((plantao_atendimento.periodo_fim::date - plantao_atendimento.periodo_inicio::date) + 1) as total_dias')
                )
                ->groupBy('promotores.nome')
                ->orderBy('total_dias', 'desc')
                ->get();

            Notification::make()
                ->title('Relatório completo gerado com sucesso')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Erro ao gerar relatório completo')
                ->body('Ocorreu um erro ao buscar todos os dados.')
                ->danger()
                ->send();

            \Log::error('Erro ao gerar relatório completo: ' . $e->getMessage());
        }
    }

    public function gerarRelatorio()
    {
        try {
            $this->validate([
                'dataInicial' => 'required|date',
                'dataFinal' => 'required|date|after_or_equal:dataInicial',
            ]);

            // Limpa os dados anteriores
            $this->reset(['eventos', 'plantoes', 'totalDiasPorPromotor']);

            // Busca os eventos do período específico
            $this->eventos = Evento::with(['promotorTitular', 'promotorDesignado', 'promotoria'])
                ->where('periodo_id', $this->periodoSelecionado) // Filtra pelo período selecionado
                ->orderBy('periodo_inicio')
                ->get()
                ->map(function ($evento) {
                    $evento->total_dias = Carbon::parse($evento->periodo_fim)->diffInDays(Carbon::parse($evento->periodo_inicio)) + 1;
                    return $evento;
                });

            // Busca os plantões do período específico
            $this->plantoes = PlantaoAtendimento::join('promotores', 'plantao_atendimento.promotor_designado_id', '=', 'promotores.id')
                ->where('plantao_atendimento.periodo_id', $this->periodoSelecionado) // Filtra pelo período selecionado
                ->select(
                    'plantao_atendimento.*',
                    'promotores.nome as promotor_nome',
                    DB::raw('(plantao_atendimento.periodo_fim::date - plantao_atendimento.periodo_inicio::date) + 1 as total_dias')
                )
                ->orderBy('plantao_atendimento.periodo_inicio')
                ->get();

            // Calcula o total de dias por promotor para o período específico
            $this->totalDiasPorPromotor = DB::table('plantao_atendimento')
                ->join('promotores', 'plantao_atendimento.promotor_designado_id', '=', 'promotores.id')
                ->where('plantao_atendimento.periodo_id', $this->periodoSelecionado) // Filtra pelo período selecionado
                ->select(
                    'promotores.nome',
                    DB::raw('COUNT(DISTINCT plantao_atendimento.id) as total_plantoes'),
                    DB::raw('SUM((plantao_atendimento.periodo_fim::date - plantao_atendimento.periodo_inicio::date) + 1) as total_dias')
                )
                ->groupBy('promotores.nome')
                ->orderBy('total_dias', 'desc')
                ->get();

            // Notificação de sucesso
            Notification::make()
                ->title('Relatório gerado com sucesso')
                ->success()
                ->send();

        } catch (\Exception $e) {
            // Notificação de erro
            Notification::make()
                ->title('Erro ao gerar relatório')
                ->body('Verifique o período selecionado e tente novamente.')
                ->danger()
                ->send();

            \Log::error('Erro ao gerar relatório: ' . $e->getMessage());
        }
    }

    public function selecionarPeriodo($periodoId)
    {
        $this->periodoSelecionado = $periodoId;
        $periodo = Periodo::find($periodoId);
        if ($periodo) {
            $this->dataInicial = $periodo->periodo_inicio;
            $this->dataFinal = $periodo->periodo_fim;
            $this->gerarRelatorio();
        }
    }
}