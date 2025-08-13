<?php

namespace App\Livewire;

use App\Models\Promotor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class Promotores extends Component
{
    use WithPagination;
    
    #[Rule('required|min:2|max:100')]
    public string $nome = '';
    
    #[Rule('required|string|max:50')]
    public string $tipo = 'titular';
    
    #[Rule('boolean')]
    public bool $is_substituto = false;
    
    #[Rule('nullable|max:500')]
    public ?string $observacoes = '';
    
    public ?Promotor $promotorEditando = null;
    public bool $mostrarModal = false;
    public bool $modoEdicao = false;
    public string $termoBusca = '';
    public string $filtroTipo = '';
    
    protected $listeners = ['promotorSalvo' => '$refresh'];
    
    public function mount()
    {
        $this->resetarFormulario();
    }
    
    public function abrirModalCriar()
    {
        $this->modoEdicao = false;
        $this->resetarFormulario();
        $this->mostrarModal = true;
    }
    
    public function abrirModalEditar(Promotor $promotor)
    {
        $this->modoEdicao = true;
        $this->promotorEditando = $promotor;
        $this->nome = $promotor->nome;
        $this->tipo = $promotor->tipo;
        $this->is_substituto = $promotor->is_substituto;
        $this->observacoes = $promotor->observacoes;
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
        
        if ($this->modoEdicao && $this->promotorEditando) {
            $this->promotorEditando->update([
                'nome' => $this->nome,
                'tipo' => $this->tipo,
                'is_substituto' => $this->is_substituto,
                'observacoes' => $this->observacoes,
            ]);
            session()->flash('mensagem', 'Promotor atualizado com sucesso!');
        } else {
            Promotor::create([
                'nome' => $this->nome,
                'tipo' => $this->tipo,
                'is_substituto' => $this->is_substituto,
                'observacoes' => $this->observacoes,
            ]);
            session()->flash('mensagem', 'Promotor criado com sucesso!');
        }
        
        $this->fecharModal();
        $this->dispatch('promotorSalvo');
    }
    
    public function deletar(Promotor $promotor)
    {
        try {
            $promotor->delete();
            session()->flash('mensagem', 'Promotor deletado com sucesso!');
            $this->dispatch('promotorSalvo');
        } catch (\Exception $e) {
            session()->flash('erro', 'Não é possível deletar este promotor pois está sendo usado em outras partes do sistema.');
        }
    }
    
    public function resetarFormulario()
    {
        $this->nome = '';
        $this->tipo = 'titular';
        $this->is_substituto = false;
        $this->observacoes = '';
        $this->promotorEditando = null;
        $this->resetValidation();
    }
    
    public function limparFiltros()
    {
        $this->termoBusca = '';
        $this->filtroTipo = '';
        $this->resetPage();
    }
    
    public function render()
    {
        $promotores = Promotor::query()
            ->when($this->termoBusca, function ($query) {
                $query->where('nome', 'like', '%' . $this->termoBusca . '%');
            })
            ->when($this->filtroTipo, function ($query) {
                $query->where('tipo', $this->filtroTipo);
            })
            ->orderBy('nome')
            ->paginate(10);
        
        return view('livewire.promotores', compact('promotores'));
    }
}
