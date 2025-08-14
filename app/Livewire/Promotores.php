<?php

namespace App\Livewire;

use App\Models\Promotor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;

class Promotores extends Component
{
    use WithPagination;
    
    #[Rule('required|min:2|max:100')]
    public string $nome = '';
    
    #[Rule('nullable|max:100')]
    public ?string $cargo = null;
    
    #[Rule('boolean')]
    public bool $zona_eleitoral = false;
    
    #[Rule('nullable|string|max:50')]
    public ?string $numero_da_zona_eleitoral = null;
    
    #[Rule('nullable|date')]
    public ?string $periodo_inicio = null;
    
    #[Rule('nullable|date')]
    public ?string $periodo_fim = null;
    
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
    
    public function rules()
    {
        $rules = [
            'nome' => 'required|min:2|max:100',
            'cargo' => 'nullable|max:100',
            'zona_eleitoral' => 'boolean',
            'periodo_inicio' => 'nullable|date',
            'periodo_fim' => 'nullable|date',
            'tipo' => 'required|string|max:50',
            'is_substituto' => 'boolean',
            'observacoes' => 'nullable|max:500',
        ];
        
        if ($this->zona_eleitoral) {
            $rules['numero_da_zona_eleitoral'] = 'required|string|max:50';
        } else {
            $rules['numero_da_zona_eleitoral'] = 'nullable|string|max:50';
        }
        
        return $rules;
    }
    
    #[Computed]
    public function promotores()
    {
        return Promotor::query()
            ->when($this->termoBusca, function ($query) {
                $query->where('nome', 'like', '%' . $this->termoBusca . '%');
            })
            ->when($this->filtroTipo, function ($query) {
                $query->where('tipo', $this->filtroTipo);
            })
            ->orderBy('id', 'asc')
            ->paginate(10);
    }
    
    public function updatedTermoBusca()
    {
        $this->resetPage();
    }
    
    public function updatedFiltroTipo()
    {
        $this->resetPage();
    }
    
    public function updatedZonaEleitoral()
    {
        if (!$this->zona_eleitoral) {
            $this->numero_da_zona_eleitoral = null;
        }
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
        $this->cargo = $promotor->cargo;
        $this->zona_eleitoral = $promotor->zona_eleitoral;
        $this->numero_da_zona_eleitoral = $promotor->numero_da_zona_eleitoral;
        $this->periodo_inicio = $promotor->periodo_inicio?->format('Y-m-d');
        $this->periodo_fim = $promotor->periodo_fim?->format('Y-m-d');
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
        
        $dados = [
            'nome' => $this->nome,
            'cargo' => $this->cargo,
            'zona_eleitoral' => $this->zona_eleitoral,
            'numero_da_zona_eleitoral' => $this->zona_eleitoral ? $this->numero_da_zona_eleitoral : null,
            'periodo_inicio' => $this->periodo_inicio ?: null,
            'periodo_fim' => $this->periodo_fim ?: null,
            'tipo' => $this->tipo,
            'is_substituto' => $this->is_substituto,
            'observacoes' => $this->observacoes,
        ];
        
        if ($this->modoEdicao && $this->promotorEditando) {
            $this->promotorEditando->update($dados);
            session()->flash('mensagem', 'Promotor atualizado com sucesso!');
        } else {
            Promotor::create($dados);
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
        $this->cargo = null;
        $this->zona_eleitoral = false;
        $this->numero_da_zona_eleitoral = null;
        $this->periodo_inicio = null;
        $this->periodo_fim = null;
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
    
    /**
     * Gera PDF com a lista de promotores
     */
    public function gerarPdf()
    {
        $promotores = Promotor::query()
            ->when($this->termoBusca, function ($query) {
                $query->where('nome', 'like', '%' . $this->termoBusca . '%');
            })
            ->when($this->filtroTipo, function ($query) {
                $query->where('tipo', $this->filtroTipo);
            })
            ->orderBy('nome', 'asc')
            ->get();
        
        $filtros = [];
        if ($this->termoBusca) {
            $filtros[] = "Busca: {$this->termoBusca}";
        }
        if ($this->filtroTipo) {
            $filtros[] = "Tipo: " . ucfirst($this->filtroTipo);
        }
        
        $dados = [
            'title' => 'Relatório de Promotores',
            'filtros' => $filtros,
            'data' => [
                'promotores' => $promotores->map(function ($promotor) {
                    return [
                        'nome' => $promotor->nome,
                        'cargo' => $promotor->cargo ?? 'N/A',
                        'tipo' => ucfirst($promotor->tipo),
                        'zona_eleitoral' => $promotor->zona_eleitoral ? 'Sim' : 'Não',
                        'numero_zona' => $promotor->numero_da_zona_eleitoral ?? 'N/A',
                        'periodo_inicio' => $promotor->periodo_inicio?->format('d/m/Y') ?? 'N/A',
                        'periodo_fim' => $promotor->periodo_fim?->format('d/m/Y') ?? 'N/A',
                        'substituto' => $promotor->is_substituto ? 'Sim' : 'Não',
                        'observacoes' => $promotor->observacoes ?? 'N/A'
                    ];
                })->toArray()
            ]
        ];
        
        $url = route('pdf.generate', ['viewName' => 'promotores']) . '?' . http_build_query($dados);
        
        return redirect($url);
    }
    
    public function render()
    {
        return view('livewire.configuracoes.promotores');
    }
}
