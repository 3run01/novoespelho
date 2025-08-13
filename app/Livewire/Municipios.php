<?php

namespace App\Livewire;

use App\Models\Municipio;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;

class Municipios extends Component
{
    use WithPagination;

    #[Rule('required|min:2|max:100|unique:municipios,nome')]
    public string $nome = '';

    public ?Municipio $municipioEditando = null;
    public bool $mostrarModal = false;
    public bool $modoEdicao = false;
    public string $termoBusca = '';

    protected $listeners = ['municipioSalvo' => '$refresh'];

    public function mount()
    {
        $this->resetarFormulario();
    }

    #[Computed]
    public function municipios()
    {
        return Municipio::query()
            ->when($this->termoBusca, function ($query) {
                $query->where('nome', 'like', '%' . $this->termoBusca . '%');
            })
            ->orderBy('id', 'asc') 
            ->paginate(10);
    }

    public function abrirModalCriar()
    {
        $this->modoEdicao = false;
        $this->resetarFormulario();
        $this->mostrarModal = true;
    }

    public function abrirModalEditar(Municipio $municipio)
    {
        $this->modoEdicao = true;
        $this->municipioEditando = $municipio;
        $this->nome = $municipio->nome;
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

        if ($this->modoEdicao && $this->municipioEditando) {
            $this->municipioEditando->update(['nome' => $this->nome]);
            session()->flash('mensagem', 'Município atualizado com sucesso!');
        } else {
            Municipio::create(['nome' => $this->nome]);
            session()->flash('mensagem', 'Município criado com sucesso!');
        }

        $this->fecharModal();
        $this->resetPage(); // Reset da paginação para mostrar a ordem correta
        $this->dispatch('municipioSalvo');
    }

    public function deletar(Municipio $municipio)
    {
        try {
            $municipio->delete();
            session()->flash('mensagem', 'Município deletado com sucesso!');
            $this->resetPage(); // Reset da paginação para mostrar a ordem correta
            $this->dispatch('municipioSalvo');
        } catch (\Exception $e) {
            session()->flash('erro', 'Não é possível deletar este município pois está sendo usado em outras partes do sistema.');
        }
    }

    public function resetarFormulario()
    {
        $this->nome = '';
        $this->municipioEditando = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.municipios');
    }
}
