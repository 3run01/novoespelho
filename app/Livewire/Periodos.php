<?php

namespace App\Livewire;

use App\Models\Periodo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;

class Periodos extends Component
{
    use WithPagination;
    
    #[Rule('required|date')]
    public string $periodoInicio = '';
    
    #[Rule('required|date|after:periodo_inicio')]
    public string $periodoFim = '';
    
    public ?Periodo $periodoEditando = null;
    public bool $mostrarModal = false;
    public bool $modoEdicao = false;
    
    protected $listeners = ['periodoSalvo' => '$refresh'];
    
    public function mount()
    {
        $this->resetarFormulario();
    }
    
    #[Computed]
    public function periodos()
    {
        $periodosEmProcesso = Periodo::where('status', 'em_processo_publicacao')
            ->orderBy('periodo_inicio', 'desc')
            ->get();
        
        if ($periodosEmProcesso->isNotEmpty()) {
            return $periodosEmProcesso;
        }
        
        $periodoPublicado = Periodo::where('status', 'publicado')->first();
        
        if ($periodoPublicado) {
            return collect([$periodoPublicado]);
        }
        
        return collect([]);
    }
    
    public function abrirModalCriar()
    {
        $this->modoEdicao = false;
        $this->resetarFormulario();
        $this->mostrarModal = true;
    }
    
    public function abrirModalEditar(Periodo $periodo)
    {
        $this->modoEdicao = true;
        $this->periodoEditando = $periodo;
        $this->periodoInicio = $periodo->periodo_inicio->format('Y-m-d');
        $this->periodoFim = $periodo->periodo_fim->format('Y-m-d');
        $this->mostrarModal = true;
    }
    
    public function fecharModal()
    {
        $this->mostrarModal = false;
        $this->resetarFormulario();
    }
    
    public function salvar()
    {
        $this->validate();
        
        if ($this->modoEdicao && $this->periodoEditando) {
            $this->periodoEditando->update([
                'periodo_inicio' => $this->periodoInicio,
                'periodo_fim' => $this->periodoFim
            ]);
            session()->flash('mensagem', 'Período atualizado com sucesso!');
        } else {
            // Criar novo período
            $novoPeriodo = Periodo::create([
                'periodo_inicio' => $this->periodoInicio,
                'periodo_fim' => $this->periodoFim,
                'status' => 'em_processo_publicacao' // Definir status padrão
            ]);
            
            // Buscar o componente de Eventos para duplicar eventos
            $eventosComponent = app(\App\Livewire\Eventos::class);
            
            try {
                // Chamar método de duplicação de eventos passando o período já criado
                $eventosComponent->duplicarEventosParaNovoPeriodo($novoPeriodo);
                
                session()->flash('mensagem', 'Período criado com sucesso e eventos duplicados!');
            } catch (\Exception $e) {
                // Se falhar na duplicação, remover o período criado
                $novoPeriodo->delete();
                
                session()->flash('erro', 'Erro ao criar período: ' . $e->getMessage());
                
                \Illuminate\Support\Facades\Log::error('Erro ao duplicar eventos para novo período', [
                    'error_message' => $e->getMessage(),
                    'periodo_inicio' => $this->periodoInicio,
                    'periodo_fim' => $this->periodoFim
                ]);
            }
        }
        
        $this->fecharModal();
        $this->dispatch('periodoSalvo');
    }
    
    public function publicar(Periodo $periodo)
    {
        $periodo->publicar();
        session()->flash('mensagem', 'Período publicado com sucesso!');
        $this->dispatch('periodoSalvo');
    }
    
    public function deletar(Periodo $periodo)
    {
        try {
            $periodo->delete();
            session()->flash('mensagem', 'Período deletado com sucesso!');
            $this->dispatch('periodoSalvo');
        } catch (\Exception $e) {
            session()->flash('erro', 'Não é possível deletar este período pois está sendo usado em outras partes do sistema.');
        }
    }
    
    public function resetarFormulario()
    {
        $this->periodoInicio = '';
        $this->periodoFim = '';
        $this->periodoEditando = null;
        $this->resetValidation();
    }
    
    public function render()
    {
        return view('livewire.espelho.periodos');
    }
}
