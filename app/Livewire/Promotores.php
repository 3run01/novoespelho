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

    #[Rule('nullable|string|max:100')]
    public string $novoCargo = '';
    public array $cargos = [];

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
            'cargos' => 'nullable|array',
            'cargos.*' => 'string|max:100',
            'zona_eleitoral' => 'boolean',
            'periodo_inicio' => 'nullable|date',
            'periodo_fim' => 'nullable|date',
            'tipo' => 'required|string|max:50',
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

    /**
     * Lista todos os promotores que são substitutos
     */
    #[Computed]
    public function promotoresSubstitutos()
    {
        return Promotor::query()
            ->where('tipo', 'substituto')
            ->orderBy('nome', 'asc')
            ->get();
    }

    /**
     * Método para obter promotores substitutos com filtros opcionais
     */
    public function obterPromotoresSubstitutos($filtros = [])
    {
        $query = Promotor::query()->where('tipo', 'substituto');

        if (isset($filtros['nome']) && !empty($filtros['nome'])) {
            $query->where('nome', 'like', '%' . $filtros['nome'] . '%');
        }

        if (isset($filtros['tipo']) && !empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        if (isset($filtros['zona_eleitoral']) && $filtros['zona_eleitoral'] !== null) {
            $query->where('zona_eleitoral', $filtros['zona_eleitoral']);
        }

        return $query->orderBy('nome', 'asc')->get();
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
        $this->cargos = is_array($promotor->cargos) ? $promotor->cargos : [];
        $this->zona_eleitoral = $promotor->zona_eleitoral;
        $this->numero_da_zona_eleitoral = $promotor->numero_da_zona_eleitoral;
        $this->periodo_inicio = $promotor->periodo_inicio?->format('Y-m-d');
        $this->periodo_fim = $promotor->periodo_fim?->format('Y-m-d');
        $this->tipo = $promotor->tipo;
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

        // Sanitizar cargos: limpar espaços, remover vazios e duplicados
        $cargosArray = collect($this->cargos)
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => $v !== '')
            ->unique()
            ->values()
            ->all();

        $dados = [
            'nome' => $this->nome,
            'cargos' => !empty($cargosArray) ? $cargosArray : null,
            'zona_eleitoral' => $this->zona_eleitoral,
            'numero_da_zona_eleitoral' => $this->zona_eleitoral ? $this->numero_da_zona_eleitoral : null,
            'periodo_inicio' => $this->periodo_inicio ?: null,
            'periodo_fim' => $this->periodo_fim ?: null,
            'tipo' => $this->tipo,
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
        $this->novoCargo = '';
        $this->cargos = [];
        $this->zona_eleitoral = false;
        $this->numero_da_zona_eleitoral = null;
        $this->periodo_inicio = null;
        $this->periodo_fim = null;
        $this->tipo = 'titular';
        $this->observacoes = '';
        $this->promotorEditando = null;
        $this->resetValidation();
    }

    public function addCargo()
    {
        $valor = trim((string) $this->novoCargo);
        if ($valor === '') {
            return;
        }

        if (mb_strlen($valor) > 100) {
            $this->addError('novoCargo', 'O cargo deve ter no máximo 100 caracteres.');
            return;
        }

        // Sem limite de quantidade de cargos

        // Evitar duplicados (case-insensitive)
        $existe = collect($this->cargos)
            ->map(fn ($v) => mb_strtolower(trim((string) $v)))
            ->contains(mb_strtolower($valor));

        if ($existe) {
            $this->addError('novoCargo', 'Este cargo já foi adicionado.');
            return;
        }

        $this->cargos[] = $valor;
        $this->novoCargo = '';
        $this->resetErrorBag('novoCargo');
    }

    public function removeCargo(int $index)
    {
        if (!array_key_exists($index, $this->cargos)) {
            return;
        }
        unset($this->cargos[$index]);
        $this->cargos = array_values($this->cargos);
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
                    $cargosString = 'N/A';
                    if (is_array($promotor->cargos) && !empty($promotor->cargos)) {
                        $cargosString = implode(', ', $promotor->cargos);
                    }
                    return [
                        'nome' => $promotor->nome,
                        'cargo' => $cargosString,
                        'tipo' => ucfirst($promotor->tipo),
                        'zona_eleitoral' => $promotor->zona_eleitoral ? 'Sim' : 'Não',
                        'numero_zona' => $promotor->numero_da_zona_eleitoral ?? 'N/A',
                        'periodo_inicio' => $promotor->periodo_inicio?->format('d/m/Y') ?? 'N/A',
                        'periodo_fim' => $promotor->periodo_fim?->format('d/m/Y') ?? 'N/A',
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
