<?php

namespace App\Livewire;

use App\Models\GrupoPromotoria;
use App\Models\Municipio;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class GrupoPromotores extends Component
{
    use WithPagination;
    
    // Properties com validação
    #[Rule('required|min:2|max:100')]
    public string $nome = '';
    
    #[Rule('required|min:2|max:200')]
    public string $competencia = '';
    
    #[Rule('required|exists:municipios,id')]
    public ?int $municipios_id = null;
    
    // Estado do componente
    public ?GrupoPromotoria $grupoEditando = null;
    public bool $mostrarModal = false;
    public bool $modoEdicao = false;
    public string $termoBusca = '';
    public string $filtroMunicipio = '';
    
    // Listeners para eventos
    protected $listeners = ['grupoPromotoriaSalvo' => '$refresh'];
    
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
    
    public function abrirModalEditar(GrupoPromotoria $grupo)
    {
        $this->modoEdicao = true;
        $this->grupoEditando = $grupo;
        $this->nome = $grupo->nome;
        $this->competencia = $grupo->competencia;
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
                'competencia' => $this->competencia,
                'municipios_id' => $this->municipios_id,
            ]);
            session()->flash('mensagem', 'Grupo de Promotoria atualizado com sucesso!');
        } else {
            GrupoPromotoria::create([
                'nome' => $this->nome,
                'competencia' => $this->competencia,
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
        $this->competencia = '';
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
        $grupos = GrupoPromotoria::query()
            ->with(['municipio', 'promotorias.promotorTitular'])
            ->when($this->termoBusca, function ($query) {
                $query->where(function ($q) {
                    $q->where('nome', 'like', '%' . $this->termoBusca . '%')
                      ->orWhere('competencia', 'like', '%' . $this->termoBusca . '%');
                });
            })
            ->when($this->filtroMunicipio, function ($query) {
                $query->where('municipios_id', $this->filtroMunicipio);
            })
            ->orderBy('nome')
            ->paginate(10);
        
        $municipios = Municipio::orderBy('nome')->get();
        
        return view('livewire.grupo-promotores', compact('grupos', 'municipios'));
    }
}
