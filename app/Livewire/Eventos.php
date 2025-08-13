<?php

namespace App\Livewire;

use App\Models\Evento;
use App\Models\Espelho;
use App\Models\Periodo;
use App\Models\Promotoria;
use App\Models\Promotor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;

class Eventos extends Component
{
    use WithPagination;
    
    // Properties com validação
    #[Rule('required|min:3|max:200')]
    public string $titulo = '';
    
    #[Rule('required')]
    public string $tipo = '';
    
    #[Rule('required|date')]
    public string $periodo_inicio = '';
    
    #[Rule('required|date|after_or_equal:periodo_inicio')]
    public string $periodo_fim = '';
    
    #[Rule('required|exists:promotorias,id')]
    public string $promotoria_id = '';
    
    public bool $is_urgente = false;
    
    public ?Evento $eventoEditando = null;
    public bool $mostrarModal = false;
    public bool $modoEdicao = false;
    public string $termoBusca = '';
    public ?Periodo $periodoSelecionado = null;
    
    public $promotorias = [];
    public $promotores = [];
    public $periodos = [];
    
    public array $promotoresDesignacoes = [];
    
    protected $listeners = ['eventoSalvo' => '$refresh'];

    public function mount()
    {
        $this->carregarDados();
        $this->periodoSelecionado = Periodo::orderBy('created_at', 'desc')->first();
        $this->resetarFormulario();
    }
    
    public function carregarDados()
    {
        $this->promotorias = Promotoria::orderBy('nome')->get();
        $this->promotores = Promotor::orderBy('nome')->get();
        $this->periodos = Periodo::orderBy('periodo_inicio', 'desc')->get();
    }
    
    public function selecionarPeriodo($periodoId)
    {
        $this->periodoSelecionado = Periodo::find($periodoId);
        $this->resetPage();
    }

    public function abrirModalCriar()
    {
        $this->modoEdicao = false;
        $this->resetarFormulario();
        
        if ($this->periodoSelecionado) {
            $this->periodo_inicio = $this->periodoSelecionado->periodo_inicio->format('Y-m-d');
            $this->periodo_fim = $this->periodoSelecionado->periodo_fim->format('Y-m-d');
        }
        
        $this->promotoresDesignacoes = [[
            'promotor_id' => '',
            'tipo' => 'titular',
            'data_inicio_designacao' => $this->periodo_inicio ?: '',
            'data_fim_designacao' => $this->periodo_fim ?: '',
            'observacoes' => ''
        ]];

        $this->mostrarModal = true;
    }

    public function abrirModalCriarParaPromotoria(int $promotoriaId): void
    {
        $this->abrirModalCriar();
        $this->promotoria_id = (string) $promotoriaId;
    }

    public function abrirModalEditar(Evento $evento)
    {
        $this->modoEdicao = true;
        $this->eventoEditando = $evento;
        $this->titulo = $evento->titulo ?? '';
        $this->tipo = $evento->tipo;
        $this->periodo_inicio = $evento->periodo_inicio->format('Y-m-d');
        $this->periodo_fim = $evento->periodo_fim->format('Y-m-d');
        $this->promotoria_id = $evento->promotoria_id;
        $this->is_urgente = $evento->is_urgente;
        // Preenche designações existentes
        $this->promotoresDesignacoes = $evento->promotores->map(function ($promotor) {
            return [
                'promotor_id' => (string) $promotor->id,
                'tipo' => $promotor->pivot->tipo ?? 'titular',
                'data_inicio_designacao' => optional($promotor->pivot->data_inicio_designacao) ? \Carbon\Carbon::parse($promotor->pivot->data_inicio_designacao)->format('Y-m-d') : '',
                'data_fim_designacao' => optional($promotor->pivot->data_fim_designacao) ? \Carbon\Carbon::parse($promotor->pivot->data_fim_designacao)->format('Y-m-d') : '',
                'observacoes' => $promotor->pivot->observacoes ?? ''
            ];
        })->toArray();

        if (empty($this->promotoresDesignacoes)) {
            $this->promotoresDesignacoes = [[
                'promotor_id' => '',
                'tipo' => 'titular',
                'data_inicio_designacao' => $this->periodo_inicio ?: '',
                'data_fim_designacao' => $this->periodo_fim ?: '',
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
        
        // Validação das designações
        $this->validate([
            'promotoresDesignacoes' => 'array|min:1',
            'promotoresDesignacoes.*.promotor_id' => 'required|exists:promotores,id',
            'promotoresDesignacoes.*.tipo' => 'required|in:titular,substituto,plantao,outro',
            'promotoresDesignacoes.*.data_inicio_designacao' => 'required|date',
            'promotoresDesignacoes.*.data_fim_designacao' => 'required|date|after_or_equal:promotoresDesignacoes.*.data_inicio_designacao',
            'promotoresDesignacoes.*.observacoes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            
            $dadosEvento = [
                'titulo' => $this->titulo,
                'tipo' => $this->tipo,
                'periodo_inicio' => $this->periodo_inicio,
                'periodo_fim' => $this->periodo_fim,
                'promotoria_id' => $this->promotoria_id,
                'is_urgente' => $this->is_urgente
            ];
            
            if ($this->modoEdicao && $this->eventoEditando) {
                $this->eventoEditando->update($dadosEvento);
                $evento = $this->eventoEditando;
                session()->flash('mensagem', 'Evento atualizado com sucesso!');
            } else {
                $evento = Evento::create($dadosEvento);
                session()->flash('mensagem', 'Evento criado com sucesso!');
            }
            
            // Sincroniza designações de promotores
            $syncData = [];
            foreach ($this->promotoresDesignacoes as $i => $designacao) {
                $syncData[(int) $designacao['promotor_id']] = [
                    'tipo' => $designacao['tipo'] ?? 'titular',
                    'data_inicio_designacao' => $designacao['data_inicio_designacao'],
                    'data_fim_designacao' => $designacao['data_fim_designacao'],
                    'ordem' => $i + 1,
                    'observacoes' => $designacao['observacoes'] ?? null,
                ];
            }
            $evento->promotores()->sync($syncData);

            // Vincular ao espelho do período selecionado (ou criar espelho se não existir)
            if (!$this->modoEdicao && $this->periodoSelecionado) {
                $espelho = Espelho::firstOrCreate([
                    'periodo_id' => $this->periodoSelecionado->id,
                    'municipio_id' => 1, // Valor padrão - pode ser ajustado
                    'grupo_promotorias_id' => 1, // Valor padrão - pode ser ajustado
                    'plantao_atendimento_id' => 1, // Valor padrão - pode ser ajustado
                ], [
                    'nome' => 'Espelho ' . $this->periodoSelecionado->periodo_inicio->format('m/Y'),
                    'status' => 'ativo'
                ]);
                
                // Vincular evento ao espelho
                $espelho->eventos()->syncWithoutDetaching([$evento->id => [
                    'ordem' => $espelho->eventos()->count() + 1
                ]]);
            }
            
            DB::commit();
            $this->fecharModal();
            $this->dispatch('eventoSalvo');
            
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('erro', 'Erro ao salvar evento: ' . $e->getMessage());
        }
    }

    public function deletar(Evento $evento)
    {
        try {
            DB::beginTransaction();
            
            // Remove vinculações com espelhos
            $evento->espelhos()->detach();
            
            // Remove vinculações com promotores
            $evento->promotores()->detach();
            
            // Remove o evento
            $evento->delete();
            
            DB::commit();
            session()->flash('mensagem', 'Evento deletado com sucesso!');
            $this->dispatch('eventoSalvo');
            
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('erro', 'Não é possível deletar este evento: ' . $e->getMessage());
        }
    }

    public function resetarFormulario()
    {
        $this->titulo = '';
        $this->tipo = '';
        $this->periodo_inicio = '';
        $this->periodo_fim = '';
        $this->promotoria_id = '';
        $this->is_urgente = false;
        $this->eventoEditando = null;
        $this->resetValidation();
    }

    public function render()
    {
        $promotoriasListado = Promotoria::with(['eventos' => function ($q) {
                $q->with(['promotores'])
                  ->when($this->periodoSelecionado, function ($query) {
                      $query->where('periodo_inicio', '>=', $this->periodoSelecionado->periodo_inicio)
                            ->where('periodo_fim', '<=', $this->periodoSelecionado->periodo_fim);
                  })
                  ->orderBy('periodo_inicio');
            }])
            ->when($this->termoBusca, function ($q) {
                $q->where('nome', 'like', '%' . $this->termoBusca . '%');
            })
            ->orderBy('nome')
            ->get();

        return view('livewire.eventos', [
            'promotoriasListado' => $promotoriasListado
        ]);
    }

    public function adicionarLinhaPromotor(): void
    {
        $this->promotoresDesignacoes[] = [
            'promotor_id' => '',
            'tipo' => 'substituto',
            'data_inicio_designacao' => $this->periodo_inicio ?: '',
            'data_fim_designacao' => $this->periodo_fim ?: '',
            'observacoes' => ''
        ];
    }

    public function removerLinhaPromotor(int $index): void
    {
        if (isset($this->promotoresDesignacoes[$index])) {
            array_splice($this->promotoresDesignacoes, $index, 1);
        }
    }
}
