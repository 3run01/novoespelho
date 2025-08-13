<?php

namespace App\Filament\Pages;

use App\Models\Municipio;
use App\Models\Evento;
use App\Models\Promotor;
use App\Models\Promotoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Http\Controllers\PromotoriaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PlantaoUrgenciaController;
use App\Models\Historico; 
use App\Models\Periodo;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\PlantaoAtendimento;

class Espelho extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Painel de Controle';


    protected static string $view = 'filament.pages.espelho';

    public $promotorias;
    public $titulo;
    public $tipo;
    public $periodo_inicio = '';
    public $periodo_fim = '';
    public $promotor_designado = '';
    public $promotor_titular = '';
    public $promotoria_id = '';
    public $is_urgente = false;
    public $isModalOpen = false;
    public $editingEvento = null;
    public $plantoes = '';
    public $periodos = '';
    public $novo_periodo_inicio = '';
    public $novo_periodo_fim = '';
    public $previewMode = false;
    public $eventosTemporarios = [];
    public $plantoesTemporarios = [];
    public $periodosTemporarios = [];
    public $previewModePlantao = false;
    public $editando = false;
    public $editandoIndex = null;
    public $editingEventoIndex = null;
    public $plantao_periodo_inicio = '';
    public $plantao_periodo_fim = '';
    public $plantao_promotor_designado = '';
    public $ultimoPeriodo;
    public $showConfirmacaoPeriodo = false;
    public $periodoSelecionado;
    public $eventos;

    protected $rules = [
        'titulo' => 'required',
        'tipo' => 'required',
        'periodo_inicio' => 'required|date',
        'periodo_fim' => 'required|date',
        'promotor_titular' => 'required',
        'promotor_designado' => 'required',
        'promotoria_id' => 'required'
    ];

    public function mount()
    {
        $promotoriaController = new PromotoriaController();
        $this->promotorias = $promotoriaController->getPromotorias();
        
        $plantaoController = new PlantaoUrgenciaController();
        $this->plantoes = $plantaoController->listarPlantaoUrgencia();

        $this->periodos = Periodo::all();
        
        $this->ultimoPeriodo = Periodo::orderBy('created_at', 'desc')->first();

        $this->atualizarDados();

        if ($this->ultimoPeriodo) {
            $this->novo_periodo_inicio = $this->ultimoPeriodo->periodo_inicio;
            $this->novo_periodo_fim = $this->ultimoPeriodo->periodo_fim;
        }
    }

    public function atualizarDados()
    {
        if ($this->ultimoPeriodo) {
            $promotoriaController = new PromotoriaController();
            $this->promotorias = $promotoriaController->getPromotoriasByPeriodo($this->ultimoPeriodo->id);
            
            $this->plantoes = PlantaoAtendimento::where('periodo_id', $this->ultimoPeriodo->id)->get();
        } else {
            $this->promotorias = collect();
            $this->plantoes = collect();
        }
    }

    public function setPromotorTitular($promotorId, $promotoriaId)
    {
        $this->promotor_titular = $promotorId;
        $this->promotoria_id = $promotoriaId;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->editingEvento = null;
        $this->reset([
            'titulo',
            'tipo',
            'periodo_inicio',
            'periodo_fim',
            'promotor_designado',
            'promotor_titular',
            'promotoria_id',
            'is_urgente'
        ]);
    }

    public function deleteEvento($id)
    {
        try {
            $evento = DB::table('eventos')->where('id', $id)->first();
            
            $eventoController = new EventoController();
            $eventoController->deleteEvento($id);

            Historico::create([
                'users_id' => auth()->id(),
                'detalhes' => "Excluiu o evento: " . $evento->titulo . 
                    " do período de " . 
                    \Carbon\Carbon::parse($evento->periodo_inicio)->format('d/m/Y') . 
                    " até " . 
                    \Carbon\Carbon::parse($evento->periodo_fim)->format('d/m/Y'),
                'modificado' => now(),
            ]);

            $this->atualizarDados();

            Notification::make()
                ->title('Evento excluído com sucesso')
                ->success()
                ->send();
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('Erro ao excluir evento')
                ->danger()
                ->send();
        }
    }


    public function salvarEvento()
    {
        $this->validate();

        $novoEvento = [
            'promotor_id' => $this->promotor_titular,
            'titulo' => $this->titulo,
            'tipo' => $this->tipo,
            'periodo_inicio' => $this->periodo_inicio,
            'periodo_fim' => $this->periodo_fim,
            'promotor_titular' => $this->promotor_titular,
            'promotor_designado' => $this->promotor_designado,
            'promotoria_id' => $this->promotoria_id,
            'is_urgente' => $this->is_urgente ?? false
        ];

        $this->eventosTemporarios[] = $novoEvento;

        // Registro no histórico
        Historico::create([
            'users_id' => auth()->id(),
            'detalhes' => "Criou um novo evento: " . $this->titulo,
            'modificado' => now(),
        ]);


        Historico::create([
            'users_id' => auth()->id(),
            'detalhes' => "Criou um novo evento: " . 
                $this->titulo . 
                " no período de " . 
                \Carbon\Carbon::parse($this->periodo_inicio)->format('d/m/Y') . 
                " até " . 
                \Carbon\Carbon::parse($this->periodo_fim)->format('d/m/Y'),
            'modificado' => now(),
        ]);
        $this->reset([
            'titulo',
            'tipo',
            'periodo_inicio',
            'periodo_fim',
            'promotor_designado',
            'is_urgente'
        ]);
        
        $this->closeModal();

        Notification::make()
            ->title('Evento adicionado ao preview')
            ->success()
            ->send();
    }

    public function updateEvento($id)
    {
        $latestPeriodo = Periodo::orderBy('created_at', 'desc')->first();

        if (!$latestPeriodo) {
            Notification::make()
                ->title('Erro ao atualizar evento')
                ->body('Nenhum período disponível para associar ao evento.')
                ->danger()
                ->send();
            return;
        }
        
        $eventoController = new EventoController();
        $response = $eventoController->updateEvento($id, [
            'titulo' => $this->titulo,
            'tipo' => $this->tipo,
            'periodo_id' => $latestPeriodo->id, 
            'periodo_inicio' => $this->periodo_inicio,
            'periodo_fim' => $this->periodo_fim,
            'promotor_titular' => $this->promotor_titular,
            'promotor_designado' => $this->promotor_designado,
            'promotoria_id' => $this->promotoria_id,
            'is_urgente' => $this->is_urgente,
        ]);

        if ($response['status'] === 'success') {
            $this->reset(['titulo', 'tipo', 'periodo_inicio', 'periodo_fim', 'promotor_designado', 'promotor_titular']);
            Notification::make()
                ->title($response['message'])
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Erro ao atualizar evento')
                ->body($response['message'])
                ->danger()
                ->send();
        } 
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function cancelEdit()
    {
        $this->editingEvento = null;
        $this->reset([
            'titulo',
            'tipo',
            'periodo_inicio',
            'periodo_fim',
            'promotor_designado',
            'promotor_titular',
            'promotoria_id',
            'is_urgente'
        ]);
    }

    public function setEventoParaEditar($eventoId)
    {
        $evento = DB::table('eventos')->where('id', $eventoId)->first();
        
        $this->editingEvento = $evento;
        $this->titulo = $evento->titulo;
        $this->tipo = $evento->tipo;
        $this->periodo_inicio = $evento->periodo_inicio;
        $this->periodo_fim = $evento->periodo_fim;
        $this->promotor_titular = $evento->promotor_titular_id;
        $this->promotor_designado = $evento->promotor_designado_id;
        $this->promotoria_id = $evento->promotoria_id;
        $this->is_urgente = $evento->is_urgente;
    }

    public function addEvento($promotorId)
    {
        $this->reset([
            'titulo',
            'tipo',
            'periodo_inicio',
            'periodo_fim',
            'promotor_designado',
            'is_urgente',
            'editingEvento'
        ]);
        
        $this->promotor_titular = $promotorId;
        
    }



    public function adicionarPlantaoUrgente()
    {
        $this->validate([
            'plantao_periodo_inicio' => 'required|date',
            'plantao_periodo_fim' => 'required|date|after_or_equal:plantao_periodo_inicio',
            'plantao_promotor_designado' => 'required',
        ], [
            'plantao_promotor_designado.required' => 'O campo "Membro" é obrigatório.',
            'plantao_periodo_inicio.required' => 'O campo "Data Inicial" é obrigatório.',
            'plantao_periodo_fim.required' => 'O campo "Data Final" é obrigatório.',
        ]);

        $this->plantoesTemporarios[] = [
            'promotor_designado_id' => $this->plantao_promotor_designado,
            'periodo_inicio' => $this->plantao_periodo_inicio,
            'periodo_fim' => $this->plantao_periodo_fim,
        ];

        $this->resetPlantaoFields();
        $this->previewModePlantao = true;

        Notification::make()
            ->title('Plantão adicionado ao preview')
            ->success()
            ->send();
    }

    public function salvarPlantoesTemporarios()
    {
        foreach ($this->plantoesTemporarios as $plantao) {
           $plantaoController = new PlantaoUrgenciaController();
           $plantaoController->salvarPlantaoUrgencia($plantao);
        }

        $this->plantoesTemporarios = [];
        $this->previewModePlantao = false;

        Notification::make()
            ->title('Plantões salvos com sucesso')
            ->success()
            ->send();
    }

    public function removePlantaoTemporario($index)
    {
        unset($this->plantoesTemporarios[$index]);
        $this->plantoesTemporarios = array_values($this->plantoesTemporarios);

        if (empty($this->plantoesTemporarios)) {
            $this->previewModePlantao = false;
        }
    }

    

    public function deletePlantaoUrgente($plantaoId)
    {
        try {
            DB::table('plantao_atendimento')->where('id', $plantaoId)->delete();

            Historico::create([
                'users_id' => auth()->id(),
                'detalhes' => 'Excluiu um plantão de urgência: ',
                'modificado' => now(),
            ]);

            $plantaoController = new PlantaoUrgenciaController();
            $this->plantoes = $plantaoController->listarPlantaoUrgencia();

            Notification::make()
                ->title('Plantão excluído com sucesso!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Erro ao excluir plantão')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    

    public function getHeading(): string
    {
        return '';
    }

    public function adicionarPeriodo()
    {
        $this->validate([
            'novo_periodo_inicio' => 'required|date',
            'novo_periodo_fim' => 'required|date|after_or_equal:novo_periodo_inicio',
        ]);

        Periodo::create([
            'periodo_inicio' => $this->novo_periodo_inicio,
            'periodo_fim' => $this->novo_periodo_fim,
            'promotor_id' => auth()->id(),
        ]);

        // Atualiza os dados após adicionar o novo período
        $this->atualizarDados();

        // Reset the input fields
        $this->novo_periodo_inicio = null;
        $this->novo_periodo_fim = null;
    }

    public function confirmarAlteracoes()
    {
        try {
            DB::beginTransaction();

            $ultimoPeriodo = null;
            foreach ($this->periodosTemporarios as $periodo) {
                $ultimoPeriodo = Periodo::create([
                    'periodo_inicio' => $periodo['periodo_inicio'],
                    'periodo_fim' => $periodo['periodo_fim'],
                    'promotor_id' => auth()->id(),
                ]);

                Historico::create([
                    'users_id' => auth()->id(),
                    'detalhes' => "Adicionou um novo período de " . 
                        \Carbon\Carbon::parse($periodo['periodo_inicio'])->format('d/m/Y') . 
                        " até " . 
                        \Carbon\Carbon::parse($periodo['periodo_fim'])->format('d/m/Y'),
                    'modificado' => now(),
                ]);
            }

            if (!$ultimoPeriodo) {
                $ultimoPeriodo = Periodo::orderBy('id', 'desc')->first();
                
                if (!$ultimoPeriodo) {
                    throw new \Exception('Nenhum período encontrado. Por favor, cadastre um período primeiro.');
                }
            }

            // Salva os eventos
            foreach ($this->eventosTemporarios as $evento) {
                DB::table('eventos')->insert([
                    'titulo' => $evento['titulo'],
                    'tipo' => $evento['tipo'],
                    'periodo_inicio' => $evento['periodo_inicio'],
                    'periodo_fim' => $evento['periodo_fim'],
                    'promotor_titular_id' => $evento['promotor_titular'],
                    'promotor_designado_id' => $evento['promotor_designado'],
                    'promotoria_id' => $evento['promotoria_id'],
                    'is_urgente' => $evento['is_urgente'] ?? false,
                    'periodo_id' => $ultimoPeriodo->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Salva os plantões de urgência
            foreach ($this->plantoesTemporarios as $plantao) {
                $plantaoController = new PlantaoUrgenciaController();
                $plantaoController->salvarPlantaoUrgencia($plantao);

                // Registra no histórico a adição do plantão
                $promotorDesignado = $this->promotorias
                    ->where('promotor_id', $plantao['promotor_designado_id'])
                    ->first()
                    ->promotor ?? 'Não definido';

                Historico::create([
                    'users_id' => auth()->id(),
                    'detalhes' => "Adicionou um plantão de urgência para o promotor " . 
                        $promotorDesignado . 
                        " no período de " . 
                        \Carbon\Carbon::parse($plantao['periodo_inicio'])->format('d/m/Y') . 
                        " até " . 
                        \Carbon\Carbon::parse($plantao['periodo_fim'])->format('d/m/Y'),
                    'modificado' => now(),
                ]);
            }

            DB::commit();

            // Limpa todos os dados temporários
            $this->eventosTemporarios = [];
            $this->periodosTemporarios = [];
            $this->plantoesTemporarios = [];
            $this->previewMode = false;
            $this->previewModePlantao = false;

            Notification::make()
                ->title('Todas as alterações foram salvas com sucesso!')
                ->success()
                ->send();

            $this->mount();

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Erro ao salvar as alterações')
                ->body($e->getMessage())
                ->danger()
                ->send();

            \Log::error('Erro ao salvar alterações: ' . $e->getMessage());
        }
    }

    public function hasAlteracoesPendentes()
    {
        return $this->hasEventosTemporarios() || $this->hasPeriodosTemporarios() || $this->hasPlantoesTemporarios();
    }

    public function cancelarPreview()
    {
        $this->eventosTemporarios = [];
        $this->periodosTemporarios = [];
        $this->previewMode = false;
    }

    public function togglePreview()
    {
        $this->previewMode = !$this->previewMode;
        \Log::info('Preview Mode: ' . ($this->previewMode ? 'true' : 'false'));
    }

    public function addEventoTemporario($promotorId)
    {
        $this->eventosTemporarios[] = [
            'promotor_id' => $promotorId,
            'titulo' => '', 
            'tipo' => '', 
            'periodo_inicio' => null,
            'periodo_fim' => null,
            'promotor_titular' => $promotorId,
            'promotor_designado' => '',
            'promotoria_id' => '',
            'is_urgente' => false
        ];

        $this->isModalOpen = true;
    }
    public function editEventoTemporario($index)
    {
        $evento = $this->eventosTemporarios[$index];
        
        // Define o índice que está sendo editado
        $this->editingEventoIndex = $index;
        
        // Preenche os campos do formulário com os dados do evento
        $this->titulo = $evento['titulo'];
        $this->tipo = $evento['tipo'];
        $this->periodo_inicio = $evento['periodo_inicio'];
        $this->periodo_fim = $evento['periodo_fim'];
        $this->promotor_titular = $evento['promotor_titular'];
        $this->promotor_designado = $evento['promotor_designado'];
        $this->promotoria_id = $evento['promotoria_id'];
        $this->is_urgente = $evento['is_urgente'] ?? false;
        
        // Armazena o índice do evento sendo editado para uso posterior
        $this->editingEventoIndex = $index;
    }
    
    public function updateEventoPreview()
    {
        $this->validate([
            'titulo' => 'required',
            'tipo' => 'required',
            'periodo_inicio' => 'required|date',
            'periodo_fim' => 'required|date',
            'promotor_designado' => 'required',
        ]);
    
        // Atualiza o evento no array de eventos temporários
        $this->eventosTemporarios[0] = [
            'promotor_id' => $this->promotor_titular,
            'titulo' => $this->titulo,
            'tipo' => $this->tipo,
            'periodo_inicio' => $this->periodo_inicio,
            'periodo_fim' => $this->periodo_fim,
            'promotor_titular' => $this->promotor_titular,
            'promotor_designado' => $this->promotor_designado,
            'promotoria_id' => $this->promotoria_id,
            'is_urgente' => $this->is_urgente ?? false,
        ];
    
        $this->reset([
            'titulo',
            'tipo',
            'periodo_inicio',
            'periodo_fim',
            'promotor_designado',
            'editingEventoIndex'
        ]);
    
        // Fecha o modal
        $this->closeModal();
    
        Notification::make()
            ->title('Evento atualizado no preview')
            ->success()
            ->send();
    }

    public function removeEventoTemporario($index)
    {
        
        unset($this->eventosTemporarios[$index]);
        $this->eventosTemporarios = array_values($this->eventosTemporarios);
    }

    public function adicionarPeriodoTemporario()
    {
        $this->validate([
            'novo_periodo_inicio' => 'required|date',
            'novo_periodo_fim' => 'required|date|after_or_equal:novo_periodo_inicio',
        ], [
            'novo_periodo_inicio.required' => 'O campo "Data Inicial" é obrigatório.',
            'novo_periodo_fim.required' => 'O campo "Data Final" é obrigatório.',
            'novo_periodo_fim.after_or_equal' => 'A "Data Final" deve ser igual ou posterior à "Data Inicial".',
        ]);

        
        $this->periodosTemporarios[] = [
            'periodo_inicio' => $this->novo_periodo_inicio,
            'periodo_fim' => $this->novo_periodo_fim,
        ];

        
        $this->reset([
            'novo_periodo_inicio',
            'novo_periodo_fim'
        ]);

        Notification::make()
            ->title('Período adicionado com sucesso!')
            ->body('O período foi adicionado ao preview.')
            ->icon('heroicon-o-calendar')
            ->iconColor('success')
            ->duration(3000)
            ->success()
            ->send();
    }

    public function removerPeriodoTemporario($index)
    {
        unset($this->periodosTemporarios[$index]);
        $this->periodosTemporarios = array_values($this->periodosTemporarios);
    }

    public function hasEventosTemporarios()
    {
        return !empty($this->eventosTemporarios);
    }

    public function hasPeriodosTemporarios()
    {
        return !empty($this->periodosTemporarios);
    }

    public function hasPlantoesTemporarios()
    {
        return !empty($this->plantoesTemporarios);
    }

    public function editarPlantaoTemporario($index)
    {
        $this->editando = true;
        $this->editandoIndex = $index;
        $plantao = $this->plantoesTemporarios[$index];
        
        $this->promotor_designado = $plantao['promotor_designado_id'];
        $this->periodo_inicio = $plantao['periodo_inicio'];
        $this->periodo_fim = $plantao['periodo_fim'];
    }

    public function atualizarPlantaoUrgente()
    {
        $this->plantoesTemporarios[$this->editandoIndex] = [
            'promotor_designado_id' => $this->promotor_designado,
            'periodo_inicio' => $this->periodo_inicio,
            'periodo_fim' => $this->periodo_fim
        ];
        
        $this->cancelarEdicao();
    }

    public function cancelarEdicao()
    {
        $this->editando = false;
        $this->editandoIndex = null;
        $this->resetInputs();
    }

    private function resetInputs()
    {
        $this->promotor_designado = null;
        $this->periodo_inicio = null;
        $this->periodo_fim = null;
    }

    public function gerarPDF()
    {
        try {
            // Busca o período mais recente
            $periodo = Periodo::latest()->first();
            
            if (!$periodo) {
                Notification::make()
                    ->title('Erro ao gerar PDF')
                    ->body('Nenhum período encontrado')
                    ->danger()
                    ->send();
                return;
            }

            // Busca os eventos do período
            $eventos = Evento::with(['promotorTitular', 'promotorDesignado', 'promotoria'])
                ->where('periodo_id', $periodo->id)
                ->get();

            // Busca os plantões de urgência do período
            $plantoes = DB::table('plantao_atendimento')
                ->join('users', 'plantao_atendimento.promotor_designado_id', '=', 'users.id')
                ->whereBetween('periodo_inicio', [$periodo->periodo_inicio, $periodo->periodo_fim])
                ->select('plantao_atendimento.*', 'users.name as promotor_nome')
                ->get();

            // Gera o PDF
            $pdf = PDF::loadView('pdf.espelho', [
                'periodo' => $periodo,
                'eventos' => $eventos,
                'plantoes' => $plantoes,
            ]);

            // Registra no histórico
            Historico::create([
                'users_id' => auth()->id(),
                'detalhes' => 'Gerou PDF do espelho',
                'modificado' => now(),
            ]);

            // Define o nome do arquivo
            $fileName = 'espelho_' . Carbon::now()->format('d_m_Y') . '.pdf';

            // Retorna o PDF para download
            return response()->streamDownload(
                fn () => print($pdf->output()),
                $fileName
            );

        } catch (\Exception $e) {
            Notification::make()
                ->title('Erro ao gerar PDF')
                ->body($e->getMessage())
                ->danger()
                ->send();
            
            \Log::error('Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    public function resetFields()
    {
        $this->reset([
            'tipo',
            'titulo',
            'periodo_inicio',
            'periodo_fim',
            'promotor_designado'
        ]);
    }

    public function resetPlantaoFields()
    {
        $this->reset([
            'plantao_periodo_inicio',
            'plantao_periodo_fim',
            'plantao_promotor_designado'
        ]);
    }

    public function adicionarPeriodoTemporarioEFecharModal()
    {
        $this->adicionarPeriodoTemporario();
        $this->showConfirmacaoPeriodo = false;
    }

    public function selecionarPeriodo($periodoId)
    {
        $this->periodoSelecionado = $periodoId;
        $this->atualizarDados(); // Chama o método para atualizar os dados
    }
}