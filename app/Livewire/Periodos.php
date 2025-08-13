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
        return Periodo::query()
            ->orderBy('periodo_inicio', 'desc')
            ->paginate(10);
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
            Periodo::create([
                'periodo_inicio' => $this->periodoInicio,
                'periodo_fim' => $this->periodoFim
            ]);
            session()->flash('mensagem', 'Período criado com sucesso!');
        }
        
        $this->fecharModal();
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
        return view('livewire.periodos');
    }
}
