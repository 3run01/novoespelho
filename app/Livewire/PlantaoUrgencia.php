<?php

namespace App\Livewire;

use App\Models\PlantaoAtendimento;
use App\Models\Promotor;
use App\Models\Periodo;
use App\Models\Municipio;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Str;

class PlantaoUrgencia extends Component
{
    use WithPagination;

    #[Rule('required')]
    public string $periodo_id = '';

    public ?int $municipio_id = null;

    #[Rule('required|min:2|max:100')]
    public string $nome = '';

    #[Rule('nullable|max:500')]
    public string $observacoes = '';

    public ?PlantaoAtendimento $plantaoEditando = null;
    public bool $mostrarModal = false;
    public bool $modoEdicao = false;
    public string $termoBusca = '';
    public string $filtroMunicipio = '';
    public string $filtroPeriodo = '';

    // Propriedades para seleção em cascata
    public string $entranciaSelecionada = '';
    public string $nucleoSelecionado = '';

    // Nova estrutura baseada em Eventos.php
    public array $promotoresDesignacoes = [];

    protected $listeners = ['plantaoSalvo' => '$refresh'];

    public function mount()
    {
        // Definir automaticamente o período mais recente
        $periodoMaisRecente = Periodo::where('status', 'em_processo_publicacao')
            ->orderBy('periodo_inicio', 'desc')
            ->first();

        if (!$periodoMaisRecente) {
            $periodoMaisRecente = Periodo::where('status', 'publicado')
                ->orderBy('periodo_inicio', 'desc')
                ->first();
        }

        if ($periodoMaisRecente) {
            $this->filtroPeriodo = (string) $periodoMaisRecente->id;
        }

        $this->resetarFormulario();
    }

    public function abrirModalCriar()
    {
        $this->modoEdicao = false;
        $this->resetarFormulario();

        $ultimoPeriodo = Periodo::orderBy('created_at', 'desc')->first();
        if ($ultimoPeriodo) {
            $this->periodo_id = $ultimoPeriodo->id;
        }

        // Inicializar com uma designação vazia
        $this->promotoresDesignacoes = [[
            'uid' => (string) Str::uuid(),
            'promotor_id' => '',
            'tipo' => 'titular',
            'data_inicio_designacao' => '',
            'data_fim_designacao' => '',
            'observacoes' => ''
        ]];

        $this->mostrarModal = true;
    }

    public function abrirModalEditar($plantaoId)
    {
        $plantao = PlantaoAtendimento::findOrFail($plantaoId);

        $this->modoEdicao = true;
        $this->plantaoEditando = $plantao;
        $this->periodo_id = $plantao->periodo_id;
        $this->municipio_id = $plantao->municipio_id;
        $this->nome = $plantao->nome ?? '';
        $this->observacoes = $plantao->observacoes ?? '';

        // Carregar entrância e núcleo do município
        $municipio = $plantao->municipio;
        if ($municipio->entrancia === 'final') {
            $this->nucleoSelecionado = '';
            if ($municipio->nome === 'Macapá') {
                $this->entranciaSelecionada = 'final_macapa';
            } elseif ($municipio->nome === 'Santana') {
                $this->entranciaSelecionada = 'final_santana';
            }
        } else {
            $this->entranciaSelecionada = 'inicial';
            $this->nucleoSelecionado = (string) $plantao->nucleo;
        }

        // Carregar designações existentes
        $this->promotoresDesignacoes = $plantao->promotores()
            ->withPivot(['data_inicio_designacao', 'data_fim_designacao', 'ordem', 'tipo_designacao', 'status'])
            ->get()
            ->map(function($promotor) {
                // Tratar datas que podem ser string ou Carbon
                $dataInicio = $promotor->pivot->data_inicio_designacao;
                $dataFim = $promotor->pivot->data_fim_designacao;

                if ($dataInicio && !is_string($dataInicio)) {
                    $dataInicio = $dataInicio->format('Y-m-d');
                } elseif (is_string($dataInicio)) {
                    $dataInicio = \Carbon\Carbon::parse($dataInicio)->format('Y-m-d');
                } else {
                    $dataInicio = '';
                }

                if ($dataFim && !is_string($dataFim)) {
                    $dataFim = $dataFim->format('Y-m-d');
                } elseif (is_string($dataFim)) {
                    $dataFim = \Carbon\Carbon::parse($dataFim)->format('Y-m-d');
                } else {
                    $dataFim = '';
                }

                return [
                    'uid' => (string) Str::uuid(),
                    'promotor_id' => (string) $promotor->id,
                    'tipo' => $promotor->pivot->tipo_designacao ?? 'titular',
                    'data_inicio_designacao' => $dataInicio,
                    'data_fim_designacao' => $dataFim,
                    'observacoes' => ''
                ];
            })->toArray();

        if (empty($this->promotoresDesignacoes)) {
            $this->promotoresDesignacoes = [[
                'uid' => (string) Str::uuid(),
                'promotor_id' => '',
                'tipo' => 'titular',
                'data_inicio_designacao' => '',
                'data_fim_designacao' => '',
                'observacoes' => ''
            ]];
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

        // Validar seleção em cascata
        $this->validate([
            'entranciaSelecionada' => 'required|in:final_macapa,final_santana,inicial',
        ]);

        // Se for entrância inicial, validar núcleo
        if ($this->entranciaSelecionada === 'inicial') {
            $this->validate([
                'nucleoSelecionado' => 'required|in:1,2,3',
            ]);
        }

        // Determinar o município baseado na entrância e núcleo selecionados
        $this->municipio_id = $this->determinarMunicipioId();

        // Validar designações de promotores
        $this->validate([
            'promotoresDesignacoes' => 'array|min:1',
            'promotoresDesignacoes.*.promotor_id' => 'required|exists:promotores,id',
            'promotoresDesignacoes.*.tipo' => 'nullable|in:titular,substituto',
            'promotoresDesignacoes.*.data_inicio_designacao' => 'nullable|date',
            'promotoresDesignacoes.*.data_fim_designacao' => 'nullable|date',
        ]);

        if ($this->modoEdicao && $this->plantaoEditando) {
            $this->plantaoEditando->update([
                'periodo_id' => $this->periodo_id,
                'municipio_id' => $this->municipio_id,
                'nucleo' => $this->entranciaSelecionada === 'inicial' ? (int) $this->nucleoSelecionado : null,
                'nome' => $this->nome,
                'observacoes' => $this->observacoes,
            ]);

            $this->atualizarPromotores($this->plantaoEditando);

            session()->flash('mensagem', 'Plantão de urgência atualizado com sucesso!');
        } else {
            $plantao = PlantaoAtendimento::create([
                'periodo_id' => $this->periodo_id,
                'municipio_id' => $this->municipio_id,
                'nucleo' => $this->entranciaSelecionada === 'inicial' ? (int) $this->nucleoSelecionado : null,
                'nome' => $this->nome,
                'observacoes' => $this->observacoes,
            ]);

            $this->atualizarPromotores($plantao);

            session()->flash('mensagem', 'Plantão de urgência criado com sucesso!');
        }

        $this->fecharModal();
        $this->dispatch('plantaoSalvo');
    }

    public function deletar($plantaoId)
    {
        try {
            $plantao = PlantaoAtendimento::findOrFail($plantaoId);
            $plantao->delete();
            session()->flash('mensagem', 'Plantão de urgência deletado com sucesso!');
            $this->dispatch('plantaoSalvo');
        } catch (\Exception $e) {
            session()->flash('erro', 'Não é possível deletar este plantão pois está sendo usado em outras partes do sistema.');
        }
    }

    // Nova função para adicionar linha de promotor
    public function adicionarLinhaPromotor(): void
    {
        $this->promotoresDesignacoes[] = [
            'uid' => (string) Str::uuid(),
            'promotor_id' => '',
            'tipo' => 'substituto',
            'data_inicio_designacao' => '',
            'data_fim_designacao' => '',
            'observacoes' => ''
        ];
    }

    // Nova função para remover linha de promotor
    public function removerLinhaPromotor(int $index): void
    {
        if (isset($this->promotoresDesignacoes[$index])) {
            array_splice($this->promotoresDesignacoes, $index, 1);
        }
    }

    private function atualizarPromotores(PlantaoAtendimento $plantao)
    {
        // Remover todos os promotores existentes
        $plantao->promotores()->detach();

        // Adicionar novos promotores baseado nas designações
        $ordem = 1;
        foreach ($this->promotoresDesignacoes as $designacao) {
            $promotorId = (int) ($designacao['promotor_id'] ?? 0);
            if ($promotorId <= 0) {
                continue;
            }

            $plantao->promotores()->attach($promotorId, [
                'data_inicio_designacao' => $designacao['data_inicio_designacao'] ?: null,
                'data_fim_designacao' => $designacao['data_fim_designacao'] ?: null,
                'ordem' => $ordem++,
                'tipo_designacao' => $designacao['tipo'] ?: 'titular',
                'status' => 'ativo',
            ]);
        }
    }

    public function resetarFormulario()
    {
        $this->periodo_id = '';
        $this->municipio_id = null;
        $this->nome = '';
        $this->observacoes = '';
        $this->plantaoEditando = null;
        $this->promotoresDesignacoes = [];
        $this->entranciaSelecionada = '';
        $this->nucleoSelecionado = '';
        $this->resetValidation();
    }

    public function getPlantoesProperty()
    {
        return PlantaoAtendimento::query()
            ->with(['municipio', 'periodo', 'promotores'])
            ->when($this->termoBusca, fn ($q) => $q->where('nome', 'like', '%' . $this->termoBusca . '%'))
            ->when($this->filtroMunicipio, fn ($q) => $q->where('municipio_id', $this->filtroMunicipio))
            ->when($this->filtroPeriodo, fn ($q) => $q->where('periodo_id', $this->filtroPeriodo))
            ->when($this->entranciaSelecionada, function ($q) {
                if ($this->entranciaSelecionada === 'final_macapa') {
                    $q->whereHas('municipio', fn ($mq) => $mq->where('nome', 'Macapá'));
                } elseif ($this->entranciaSelecionada === 'final_santana') {
                    $q->whereHas('municipio', fn ($mq) => $mq->where('nome', 'Santana'));
                } elseif ($this->entranciaSelecionada === 'inicial') {
                    // Para entrância inicial, mostrar plantões sem município específico
                    $q->whereNull('municipio_id');
                }
            })
            ->when($this->nucleoSelecionado && $this->entranciaSelecionada === 'inicial', function ($q) {
                $q->where('nucleo', $this->nucleoSelecionado);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getEntranciasProperty()
    {
        return [
            ['value' => 'final_macapa', 'label' => 'Entrância Final - Macapá'],
            ['value' => 'final_santana', 'label' => 'Entrância Final - Santana'],
            ['value' => 'inicial', 'label' => 'Entrância Inicial'],
        ];
    }

    public function getNucleosProperty()
    {
        if ($this->entranciaSelecionada !== 'inicial') {
            return [];
        }

        return [
            ['value' => '1', 'label' => '1º Núcleo (Laranjal do Jari, Vitória do Jari, Mazagão)'],
            ['value' => '2', 'label' => '2º Núcleo (Oiapoque, Calçoene, Amapá)'],
            ['value' => '3', 'label' => '3º Núcleo (Tartarugalzinho, Ferreira Gomes, Porto Grande, Pedra Branca do Amapari)'],
        ];
    }

    private function determinarMunicipioId()
    {
        if ($this->entranciaSelecionada === 'final_macapa') {
            $municipio = Municipio::where('nome', 'Macapá')->first();
            return $municipio ? $municipio->id : null;
        }

        if ($this->entranciaSelecionada === 'final_santana') {
            $municipio = Municipio::where('nome', 'Santana')->first();
            return $municipio ? $municipio->id : null;
        }

        // Para entrância inicial, retornamos null pois não há um município específico
        // O plantão será associado ao núcleo como um todo
        return null;
    }

    public function getMunicipiosProperty()
    {
        if (!$this->entranciaSelecionada) {
            return [];
        }

        // Se for entrância final (Macapá ou Santana), retorna o município específico
        if ($this->entranciaSelecionada === 'final_macapa') {
            return Municipio::where('nome', 'Macapá')->get();
        }

        if ($this->entranciaSelecionada === 'final_santana') {
            return Municipio::where('nome', 'Santana')->get();
        }

        // Se for entrância inicial, retorna todos os municípios do núcleo selecionado
        if ($this->entranciaSelecionada === 'inicial' && $this->nucleoSelecionado) {
            return Municipio::where('entrancia', 'inicial')
                ->where('nucleo', $this->nucleoSelecionado)
                ->orderBy('nome')
                ->get();
        }

        return [];
    }

    public function getPeriodosProperty()
    {
        // Priorizar períodos em processo de publicação, depois publicados
        $periodosEmProcesso = Periodo::where('status', 'em_processo_publicacao')
            ->orderBy('periodo_inicio', 'desc')
            ->get();

        if ($periodosEmProcesso->isNotEmpty()) {
            return $periodosEmProcesso;
        }

        return Periodo::where('status', 'publicado')
            ->orderBy('periodo_inicio', 'desc')
            ->get();
    }

    public function getPromotoresProperty()
    {
        return Promotor::orderBy('nome')->get();
    }

    public function updatedTermoBusca()
    {
        $this->resetPage();
    }

    public function updatedFiltroMunicipio()
    {
        $this->resetPage();
    }

    public function updatedFiltroPeriodo()
    {
        $this->resetPage();
    }

    public function updatedEntranciaSelecionada()
    {
        $this->nucleoSelecionado = '';
        $this->municipio_id = null;
        $this->resetPage();
    }

    public function updatedNucleoSelecionado()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.espelho.plantao-urgencia');
    }
}
