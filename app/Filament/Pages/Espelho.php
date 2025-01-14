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

class Espelho extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Painel de Controle';


    protected static string $view = 'filament.pages.espelho';

    public $promotorias;
    public $titulo;
    public $tipo;
    public $periodo_inicio ='';
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
            $eventoController = new EventoController();
            $eventoController->deleteEvento($id);
    
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

    // Adiciona o evento ao array temporário com todos os campos necessários
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

    // Limpa os campos do formulário
    $this->reset(['titulo', 'tipo', 'periodo_inicio', 'periodo_fim', 'promotor_designado']);
    
    // Fecha o modal
    $this->closeModal();

    // Notifica o usuário
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
            'promotor_designado' => 'required',
            'periodo_inicio' => 'required|date',
            'periodo_fim' => 'required|date',
        ]);

        $this->plantoesTemporarios[] = [
            'promotor_designado_id' => $this->promotor_designado,
            'periodo_inicio' => $this->periodo_inicio,
            'periodo_fim' => $this->periodo_fim,
        ];

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
            'promotor_id' => auth()->id(), // Assuming you want to associate it with the logged-in user
        ]);

        // Reset the input fields
        $this->novo_periodo_inicio = null;
        $this->novo_periodo_fim = null;

        // Optionally, you can add a success message or redirect
    }

    public function confirmarAlteracoes()
    {
        try {
            DB::beginTransaction();

            $ultimoPeriodo = Periodo::orderBy('id', 'desc')->first();
            
            if (!$ultimoPeriodo) {
                throw new \Exception('Nenhum período encontrado. Por favor, cadastre um período primeiro.');
            }

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

            DB::commit();

            // Limpar dados temporários
            $this->eventosTemporarios = [];
            $this->periodosTemporarios = [];
            $this->previewMode = false;

            // Notificar sucesso
            Notification::make()
                ->title('Alterações salvas com sucesso!')
                ->success()
                ->send();

            // Recarregar os dados
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
        return !empty($this->eventosTemporarios) || !empty($this->periodosTemporarios);
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
        // Remove um evento do array temporário
        unset($this->eventosTemporarios[$index]);
        $this->eventosTemporarios = array_values($this->eventosTemporarios);
    }

    public function adicionarPeriodoTemporario()
    {
        $this->validate([
            'novo_periodo_inicio' => 'required|date',
            'novo_periodo_fim' => 'required|date|after_or_equal:novo_periodo_inicio',
        ]);

        $this->periodosTemporarios[] = [
            'periodo_inicio' => $this->novo_periodo_inicio,
            'periodo_fim' => $this->novo_periodo_fim,
        ];

        $this->novo_periodo_inicio = null;
        $this->novo_periodo_fim = null;
    }

    public function hasEventosTemporarios()
    {
        return !empty($this->eventosTemporarios);
    }
}