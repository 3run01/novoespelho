<?php

namespace App\Livewire;

use App\Models\GrupoPromotoria;
use App\Models\Municipio;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;

class GrupoPromotores extends Component
{
    use WithPagination;
    
    #[Rule('required|min:2|max:100')]
    public string $nome = '';
    
    #[Rule('required|exists:municipios,id')]
    public ?int $municipios_id = null;
    
    public ?GrupoPromotoria $grupoEditando = null;
    public bool $mostrarModal = false;
    public bool $modoEdicao = false;
    public string $termoBusca = '';
    public string $filtroMunicipio = '';
    
    protected $listeners = ['grupoPromotoriaSalvo' => '$refresh'];
    
    public function mount()
    {
        $this->resetarFormulario();
    }
    
    #[Computed]
    public function grupos()
    {
        return GrupoPromotoria::query()
            ->with(['municipio', 'promotorias.promotorTitular'])
            ->when($this->termoBusca, function ($query) {
                $query->where('nome', 'like', '%' . $this->termoBusca . '%');
            })
            ->when($this->filtroMunicipio, function ($query) {
                $query->where('municipios_id', $this->filtroMunicipio);
            })
            ->orderBy('id', 'asc')
            ->paginate(10);
    }
    
    #[Computed]
    public function municipios()
    {
        return Municipio::orderBy('id', 'asc')->get();
    }
    
    public function abrirModalCriar()
    {
        $this->modoEdicao = false;
        $this->resetarFormulario();
        $this->mostrarModal = true;
    }
    
    public function abrirModalEditar(GrupoPromotoria $grupo)
    {
        $this->modoEdicao = true;
        $this->grupoEditando = $grupo;
        $this->nome = $grupo->nome;
        $this->municipios_id = $grupo->municipios_id;
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
        
        if ($this->modoEdicao && $this->grupoEditando) {
            $this->grupoEditando->update([
                'nome' => $this->nome,
                'municipios_id' => $this->municipios_id,
            ]);
            session()->flash('mensagem', 'Grupo de Promotoria atualizado com sucesso!');
        } else {
            GrupoPromotoria::create([
                'nome' => $this->nome,
                'municipios_id' => $this->municipios_id,
            ]);
            session()->flash('mensagem', 'Grupo de Promotoria criado com sucesso!');
        }
        
        $this->fecharModal();
        $this->dispatch('grupoPromotoriaSalvo');
    }
    
    public function deletar(GrupoPromotoria $grupo)
    {
        try {
            $grupo->delete();
            session()->flash('mensagem', 'Grupo de Promotoria deletado com sucesso!');
            $this->dispatch('grupoPromotoriaSalvo');
        } catch (\Exception $e) {
            session()->flash('erro', 'Não é possível deletar este grupo pois está sendo usado em outras partes do sistema.');
        }
    }
    
    public function resetarFormulario()
    {
        $this->nome = '';
        $this->municipios_id = null;
        $this->grupoEditando = null;
        $this->resetValidation();
    }
    
    public function limparFiltros()
    {
        $this->termoBusca = '';
        $this->filtroMunicipio = '';
        $this->resetPage();
    }
    
    public function render()
    {
        return view('livewire.configuracoes.grupo-promotores');
    }
}
