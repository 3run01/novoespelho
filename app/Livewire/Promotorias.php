<?php

namespace App\Livewire;

use App\Models\Promotoria;
use App\Models\Promotor;
use App\Models\GrupoPromotoria;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;

class Promotorias extends Component
{
    use WithPagination;
    
    // Properties com validação
    #[Rule('required|min:2|max:100')]
    public string $nome = '';
    
    #[Rule('nullable')]
    public ?string $promotor_id = null; // Mudei para string para aceitar 'sem_titular'
    
    #[Rule('required|exists:grupo_promotorias,id')]
    public ?int $grupo_promotoria_id = null;
    
    #[Rule('nullable|max:255')]
    public ?string $competencia = null;
    
    #[Rule('nullable|date')]
    public ?string $titularidade_promotor_data_inicio = null;
    
    #[Rule('nullable|date')]
    public ?string $vacancia_data_inicio = null;
    
    // Removida a propriedade tipo_titular
    
    // Estado do componente
    public ?Promotoria $promotoriaEditando = null;
    public bool $mostrarModal = false;
    public bool $modoEdicao = false;
    public string $termoBusca = '';
    public string $filtroGrupo = '';
    public string $filtroPromotor = '';
    
    // Dados carregados no mount
    public $grupos;
    public $promotores;
    
    // Listeners para eventos
    protected $listeners = ['promotoriaSalva' => '$refresh'];
    
    public function mount()
    {
        $this->resetarFormulario();
        $this->carregarDados();
    }
    
    private function carregarDados()
    {
        try {
            $this->grupos = GrupoPromotoria::orderBy('id', 'asc')->get();
            $this->promotores = Promotor::orderBy('id', 'asc')->get();
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar dados no componente Promotorias: ' . $e->getMessage());
            $this->grupos = collect([]);
            $this->promotores = collect([]);
        }
    }
    
    #[Computed]
    public function promotorias()
    {
        try {
            return Promotoria::query()
                ->with(['promotorTitular', 'grupoPromotoria.municipio'])
                ->when($this->termoBusca, function ($query) {
                    $query->where('nome', 'like', '%' . $this->termoBusca . '%');
                })
                ->when($this->filtroGrupo, function ($query) {
                    $query->where('grupo_promotoria_id', $this->filtroGrupo);
                })
                ->when($this->filtroPromotor, function ($query) {
                    $query->where('promotor_id', $this->filtroPromotor);
                })
                ->orderBy('id', 'asc')
                ->paginate(10);
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar promotorias: ' . $e->getMessage());
            return collect([])->paginate(10);
        }
    }
    
    public function abrirModalCriar()
    {
        $this->modoEdicao = false;
        $this->resetarFormulario();
        $this->mostrarModal = true;
    }
    
    public function abrirModalEditar(Promotoria $promotoria)
    {
        $this->modoEdicao = true;
        $this->promotoriaEditando = $promotoria;
        $this->nome = $promotoria->nome;
        $this->grupo_promotoria_id = $promotoria->grupo_promotoria_id;
        $this->competencia = $promotoria->competencia;
        $this->titularidade_promotor_data_inicio = $promotoria->titularidade_promotor_data_inicio;
        $this->vacancia_data_inicio = $promotoria->vacancia_data_inicio;
        
        // Define o promotor_id baseado nos dados existentes
        if ($promotoria->promotor_id) {
            $this->promotor_id = (string) $promotoria->promotor_id;
        } elseif ($promotoria->vacancia_data_inicio) {
            $this->promotor_id = 'sem_titular';
        } else {
            $this->promotor_id = null;
        }
        
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
        
        $dados = [
            'nome' => $this->nome,
            'grupo_promotoria_id' => $this->grupo_promotoria_id,
            'competencia' => $this->competencia,
        ];
        
        // Define os campos baseado no promotor_id
        if ($this->promotor_id === 'sem_titular') {
            $dados['promotor_id'] = null;
            $dados['titularidade_promotor_data_inicio'] = null;
            $dados['vacancia_data_inicio'] = $this->vacancia_data_inicio;
        } elseif ($this->promotor_id && $this->promotor_id !== 'sem_titular') {
            $dados['promotor_id'] = (int) $this->promotor_id;
            $dados['titularidade_promotor_data_inicio'] = $this->titularidade_promotor_data_inicio;
            $dados['vacancia_data_inicio'] = null;
        } else {
            // Selecione uma opção - limpa tudo
            $dados['promotor_id'] = null;
            $dados['titularidade_promotor_data_inicio'] = null;
            $dados['vacancia_data_inicio'] = null;
        }
        
        if ($this->modoEdicao && $this->promotoriaEditando) {
            $this->promotoriaEditando->update($dados);
            session()->flash('mensagem', 'Promotoria atualizada com sucesso!');
        } else {
            Promotoria::create($dados);
            session()->flash('mensagem', 'Promotoria criada com sucesso!');
        }
        
        $this->fecharModal();
        $this->dispatch('promotoriaSalva');
    }
    
    public function deletar(Promotoria $promotoria)
    {
        try {
            $promotoria->delete();
            session()->flash('mensagem', 'Promotoria deletada com sucesso!');
            $this->dispatch('promotoriaSalva');
        } catch (\Exception $e) {
            session()->flash('erro', 'Não é possível deletar esta promotoria pois está sendo usada em outras partes do sistema.');
        }
    }
    
    public function resetarFormulario()
    {
        $this->nome = '';
        $this->promotor_id = null;
        $this->grupo_promotoria_id = null;
        $this->competencia = null;
        $this->titularidade_promotor_data_inicio = null;
        $this->vacancia_data_inicio = null;
        $this->promotoriaEditando = null;
        $this->resetValidation();
    }
    
    public function limparFiltros()
    {
        $this->termoBusca = '';
        $this->filtroGrupo = '';
        $this->filtroPromotor = '';
        $this->resetPage();
    }
    
    public function render()
    {
        return view('livewire.promotorias');
    }
}
