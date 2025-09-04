<?php

namespace App\Livewire;

use App\Models\Evento;
use App\Models\Espelho;
use App\Models\Periodo;
use App\Models\Promotoria;
use App\Models\Promotor;
use App\Models\EventoPromotor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class PromotorEventos extends Component
{
    use WithPagination;
    
    #[Rule('required|exists:promotores,id')]
    public string $promotor_id = '';
    
    #[Rule('nullable|min:3|max:200')]
    public string $titulo = '';
    
    #[Rule('nullable')]
    public string $tipo = '';
    
    #[Rule('nullable|date')]
    public string $periodo_inicio = '';
    
    #[Rule('nullable|date|after_or_equal:periodo_inicio')]
    public string $periodo_fim = '';
    
    #[Rule('nullable|exists:promotorias,id')]
    public string $promotoria_id = '';
    
    #[Rule('required')]
    public string $periodo_id = '';
    
    public ?Evento $eventoEditando = null;
    public bool $mostrarModal = false;
    public bool $modoEdicao = false;
    public string $termoBusca = '';
    public ?Periodo $periodoSelecionado = null;
    public ?string $periodoSelecionadoId = null;
    
    public $promotorias = [];
    public $promotores = [];
    public $promotoresListado = [];
    
    public array $eventosDesignacoes = [];

    protected $listeners = [
        'eventoSalvo' => 'atualizarDados',
        'periodoSalvo' => 'recarregarPeriodos'
    ];

    public function mount()
    {
        $this->carregarDados();
        
        $periodoMaisRecente = $this->obterPeriodoMaisRecente();
        
        Log::info('Período mais recente encontrado no mount', [
            'periodo_id' => $periodoMaisRecente?->id,
            'status' => $periodoMaisRecente?->status,
            'inicio' => $periodoMaisRecente?->periodo_inicio?->format('Y-m-d'),
            'fim' => $periodoMaisRecente?->periodo_fim?->format('Y-m-d')
        ]);
        
        $todosPeriodos = Periodo::whereIn('status', ['em_processo_publicacao', 'publicado'])
            ->orderBy('periodo_inicio', 'desc')
            ->get();
        
        Log::info('Períodos disponíveis', [
            'periodos' => $todosPeriodos->map(function($periodo) {
                return [
                    'id' => $periodo->id,
                    'status' => $periodo->status,
                    'inicio' => $periodo->periodo_inicio->format('Y-m-d'),
                    'fim' => $periodo->periodo_fim->format('Y-m-d')
                ];
            })->toArray()
        ]);
        
        $this->periodoSelecionado = $periodoMaisRecente;
        $this->periodoSelecionadoId = $this->periodoSelecionado?->id;
        
        $this->atualizarPromotoresListado();
        
        $this->resetarFormulario();
        
        // Garantir que o período mais recente está sempre selecionado
        $this->periodo_id = $this->periodoSelecionado?->id;
        
        Log::info('Componente PromotorEventos inicializado com sucesso', [
            'periodo_selecionado_id' => $this->periodoSelecionadoId,
            'periodo_id_formulario' => $this->periodo_id
        ]);
    }
    
    /**
     * Obtém o período mais recente, priorizando os em processo de publicação
     */
    private function obterPeriodoMaisRecente()
    {
        $periodosEmProcesso = Periodo::where('status', 'em_processo_publicacao')
            ->orderBy('periodo_inicio', 'desc')
            ->get();
        
        Log::info('Períodos em processo de publicação encontrados', [
            'count' => $periodosEmProcesso->count(),
            'periodos' => $periodosEmProcesso->map(function($periodo) {
                return [
                    'id' => $periodo->id,
                    'inicio' => $periodo->periodo_inicio->format('Y-m-d'),
                    'fim' => $periodo->periodo_fim->format('Y-m-d')
                ];
            })->toArray()
        ]);
        
        if ($periodosEmProcesso->isNotEmpty()) {
            $periodoMaisRecente = $periodosEmProcesso->first();
            Log::info('Selecionado período em processo de publicação mais recente', [
                'id' => $periodoMaisRecente->id,
                'status' => $periodoMaisRecente->status,
                'inicio' => $periodoMaisRecente->periodo_inicio->format('Y-m-d'),
                'fim' => $periodoMaisRecente->periodo_fim->format('Y-m-d')
            ]);
            return $periodoMaisRecente;
        }
        
        $periodosPublicados = Periodo::where('status', 'publicado')
            ->orderBy('periodo_inicio', 'desc')
            ->get();
        
        Log::info('Períodos publicados encontrados', [
            'count' => $periodosPublicados->count(),
            'periodos' => $periodosPublicados->map(function($periodo) {
                return [
                    'id' => $periodo->id,
                    'inicio' => $periodo->periodo_inicio->format('Y-m-d'),
                    'fim' => $periodo->periodo_fim->format('Y-m-d')
                ];
            })->toArray()
        ]);
        
        if ($periodosPublicados->isNotEmpty()) {
            $periodoMaisRecente = $periodosPublicados->first();
            Log::info('Selecionado período publicado mais recente', [
                'id' => $periodoMaisRecente->id,
                'status' => $periodoMaisRecente->status,
                'inicio' => $periodoMaisRecente->periodo_inicio->format('Y-m-d'),
                'fim' => $periodoMaisRecente->periodo_fim->format('Y-m-d')
            ]);
            return $periodoMaisRecente;
        }
        
        Log::warning('Nenhum período em processo de publicação ou publicado encontrado');
        return null;
    }
    
    public function carregarDados()
    {
        $this->promotorias = Promotoria::orderBy('nome')->get();
        // Filtrar apenas promotores substitutos
        $this->promotores = Promotor::where('tipo', 'substituto')->orderBy('nome')->get();
    }
    
    public function getPeriodosProperty()
    {
        Log::info('Buscando períodos para o componente PromotorEventos');
        
        $periodosEmProcesso = Periodo::where('status', 'em_processo_publicacao')
            ->orderBy('periodo_inicio', 'desc')
            ->get();
        
        if ($periodosEmProcesso->isNotEmpty()) {
            Log::info('Períodos em processo encontrados', [
                'count' => $periodosEmProcesso->count(),
                'ids' => $periodosEmProcesso->pluck('id')->toArray()
            ]);
            return $periodosEmProcesso;
        }
        
        $periodosPublicados = Periodo::where('status', 'publicado')
            ->orderBy('periodo_inicio', 'desc')
            ->get();
            
        if ($periodosPublicados->isNotEmpty()) {
            Log::info('Períodos publicados encontrados', [
                'count' => $periodosPublicados->count(),
                'ids' => $periodosPublicados->pluck('id')->toArray()
            ]);
            return $periodosPublicados;
        }
        
        $todosPeriodos = Periodo::orderBy('periodo_inicio', 'desc')->get();
        
        Log::info('Todos os períodos encontrados', [
            'count' => $todosPeriodos->count(),
            'ids' => $todosPeriodos->pluck('id')->toArray()
        ]);
        
        return $todosPeriodos;
    }
    
    /**
     * Obtém os períodos diretamente do banco de dados
     * Este método é usado para forçar a atualização dos períodos
     */
    public function obterPeriodosAtualizados()
    {
        return $this->getPeriodosProperty();
    }
    
    public function atualizarPromotoresListado()
    {
        // Buscar períodos mais recentes (em processo de publicação ou publicados)
        $periodosRecentes = Periodo::whereIn('status', ['em_processo_publicacao', 'publicado'])
            ->orderBy('periodo_inicio', 'desc')
            ->get();
        
        // Se não houver períodos recentes, retornar lista vazia
        if ($periodosRecentes->isEmpty()) {
            $this->promotoresListado = collect();
            return;
        }
        
        // Buscar APENAS promotores substitutos com seus eventos designados
        $promotoresComEventos = Promotor::where('tipo', 'substituto')
            ->with([
                'eventos' => function ($q) use ($periodosRecentes) {
                    $q->with(['promotoria', 'designacoes.promotor'])
                      ->whereIn('periodo_id', $periodosRecentes->pluck('id'))
                      ->orderBy('periodo_inicio');
                }
            ])
            ->when($this->termoBusca, function ($q) {
                $q->where('nome', 'like', '%' . $this->termoBusca . '%');
            })
            ->orderBy('nome')
            ->get();

        Log::info('Carregando promotores substitutos e eventos', [
            'periodos_ids' => $periodosRecentes->pluck('id')->toArray(),
            'periodos_status' => $periodosRecentes->pluck('status')->toArray(),
            'total_promotores_substitutos' => $promotoresComEventos->count()
        ]);

        $this->promotoresListado = $promotoresComEventos;
    }
    
    public function atualizarDados()
    {
        $this->atualizarPromotoresListado();
    }
    
    /**
     * Recarrega os períodos e atualiza a seleção quando um novo período é criado
     */
    public function recarregarPeriodos()
    {
        Log::info('Recarregando períodos após mudança');
        
        // Recarregar o período mais recente
        $periodoMaisRecente = $this->obterPeriodoMaisRecente();
        
        // Se o período selecionado atual não existe mais ou se há um novo período mais recente
        if (!$this->periodoSelecionado || 
            ($periodoMaisRecente && $this->periodoSelecionado->id !== $periodoMaisRecente->id)) {
            
            $this->periodoSelecionado = $periodoMaisRecente;
            $this->periodoSelecionadoId = $this->periodoSelecionado?->id;
            
            // Atualizar o período_id no formulário se estiver vazio
            if (empty($this->periodo_id)) {
                $this->periodo_id = $this->periodoSelecionado?->id;
            }
        }
        
        // Forçar atualização da propriedade periodos
        $this->dispatch('$refresh');
        
        // Atualizar a lista de promotores
        $this->atualizarPromotoresListado();
    }
    
    /**
     * Força a atualização da propriedade periodos
     */
    public function atualizarPeriodos()
    {
        $this->dispatch('$refresh');
    }
    
    public function selecionarPeriodo($periodoId)
    {
        $this->periodoSelecionado = $periodoId ? Periodo::find($periodoId) : null;
        $this->periodoSelecionadoId = $this->periodoSelecionado?->id;
        $this->atualizarPromotoresListado();
        $this->resetPage();
    }

    public function updatedTermoBusca()
    {
        $this->atualizarPromotoresListado();
        $this->resetPage();
    }

    public function updatedPeriodoSelecionadoId($value)
    {
        $this->selecionarPeriodo($value);
    }

    public function abrirModalCriar()
    {
        $this->resetarFormulario();
        $this->modoEdicao = false;
        $this->eventoEditando = null;
        $this->mostrarModal = true;
        
        // Sempre garantir que o período mais recente está selecionado ao criar novo evento
        $periodoMaisRecente = $this->obterPeriodoMaisRecente();
        $this->periodo_id = $periodoMaisRecente?->id;
        
        // Atualizar dados quando abre o modal
        $this->atualizarPromotoresListado();
    }

    public function abrirModalCriarParaPromotor(int $promotorId): void
    {
        $this->abrirModalCriar();
        $this->promotor_id = (string) $promotorId;
    }

    public function abrirModalEditar($eventoId)
    {
        $evento = Evento::with('designacoes.promotor')->find($eventoId);
        
        if (!$evento) {
            session()->flash('erro', 'Evento não encontrado.');
            return;
        }
        
        $this->modoEdicao = true;
        $this->eventoEditando = $evento;
        $this->titulo = $evento->titulo ?? '';
        $this->tipo = $evento->tipo ?? '';
        $this->periodo_inicio = $evento->periodo_inicio ? $evento->periodo_inicio->format('Y-m-d') : '';
        $this->periodo_fim = $evento->periodo_fim ? $evento->periodo_fim->format('Y-m-d') : '';
        $this->promotoria_id = $evento->promotoria_id;
        
        // Sempre tentar selecionar o período mais recente
        $periodoMaisRecente = $this->obterPeriodoMaisRecente();
        
        // Se não houver período específico, usar o período mais recente
        if (!$evento->periodo_id) {
            Log::info('Nenhum período específico para o evento, selecionando período mais recente', [
                'evento_id' => $evento->id,
                'periodo_mais_recente_id' => $periodoMaisRecente?->id,
                'periodo_mais_recente_status' => $periodoMaisRecente?->status
            ]);
            
            $this->periodo_id = $periodoMaisRecente?->id;
        } else {
            // Se houver período, verificar se ainda é o mais recente
            $periodoEvento = Periodo::find($evento->periodo_id);
            
            Log::info('Verificando período do evento', [
                'evento_id' => $evento->id,
                'periodo_evento_id' => $periodoEvento?->id,
                'periodo_evento_status' => $periodoEvento?->status,
                'periodo_mais_recente_id' => $periodoMaisRecente?->id,
                'periodo_mais_recente_status' => $periodoMaisRecente?->status
            ]);
            
            // Se o período do evento não for o mais recente, atualizar
            if (!$periodoEvento || 
                ($periodoMaisRecente && $periodoEvento->id !== $periodoMaisRecente->id)) {
                Log::info('Atualizando período para o mais recente', [
                    'evento_id' => $evento->id,
                    'antigo_periodo_id' => $evento->periodo_id,
                    'novo_periodo_id' => $periodoMaisRecente?->id
                ]);
                
                $this->periodo_id = $periodoMaisRecente?->id;
            } else {
                $this->periodo_id = (string) $evento->periodo_id;
            }
        }
        
        // Buscar o promotor designado para este evento
        $designacao = $evento->designacoes->first();
        if ($designacao) {
            $this->promotor_id = (string) $designacao->promotor_id;
        }

        $this->mostrarModal = true;
    }

    public function fecharModal()
    {
        $this->mostrarModal = false;
        $this->resetarFormulario();
        
        // Atualizar dados quando fecha o modal
        $this->atualizarPromotoresListado();
    }

    public function salvar()
    {
        $this->validate();
        
        $this->validate([
            'promotor_id' => 'required|exists:promotores,id',
        ]);

        // Validar se o promotor selecionado é do tipo substituto
        $promotor = Promotor::find($this->promotor_id);
        if (!$promotor || $promotor->tipo !== 'substituto') {
            session()->flash('erro', 'Apenas promotores substitutos podem ser designados neste componente.');
            return;
        }

        try {
            DB::beginTransaction();
            
            $dadosEvento = [
                'titulo' => $this->titulo ?: null,
                'tipo' => $this->tipo ?: null,
                'periodo_inicio' => $this->periodo_inicio ?: null,
                'periodo_fim' => $this->periodo_fim ?: null,
                'promotoria_id' => $this->promotoria_id ?: null,
                'periodo_id' => $this->periodo_id, 
                'is_urgente' => false,
                'evento_do_substituto' => true, // Sempre true para eventos criados manualmente
            ];
            
            // Verificar se o período do evento sendo editado é diferente do período atual
            $eventoOriginalPeriodoId = $this->eventoEditando ? $this->eventoEditando->periodo_id : null;
            $novoPeriodoId = (int) $this->periodo_id;
            
            if ($this->modoEdicao && $this->eventoEditando) {
                // Se o período do evento sendo editado é diferente do período atual
                if ($eventoOriginalPeriodoId && $eventoOriginalPeriodoId !== $novoPeriodoId) {
                    // Duplicar o evento para o novo período
                    $novoEvento = $this->eventoEditando->replicate();
                    $novoEvento->periodo_id = $novoPeriodoId;
                    $novoEvento->save();
                    
                    // Duplicar as designações de promotores
                    $designacoesOriginais = EventoPromotor::where('evento_id', $this->eventoEditando->id)->get();
                    foreach ($designacoesOriginais as $designacaoOriginal) {
                        $novaDesignacao = $designacaoOriginal->replicate();
                        $novaDesignacao->evento_id = $novoEvento->id;
                        $novaDesignacao->save();
                    }
                    
                    // Atualizar o novo evento com os dados do formulário
                    $novoEvento->update($dadosEvento);
                    $evento = $novoEvento;
                    
                    Log::info('Evento duplicado para novo período', [
                        'evento_original_id' => $this->eventoEditando->id,
                        'evento_original_periodo_id' => $eventoOriginalPeriodoId,
                        'novo_evento_id' => $novoEvento->id,
                        'novo_periodo_id' => $novoPeriodoId
                    ]);
                    
                    ActivityLog::createLog(
                        'info',
                        'Evento duplicado para novo período',
                        [
                            'action' => 'duplicate_evento',
                            'evento_original_id' => $this->eventoEditando->id,
                            'novo_evento_id' => $novoEvento->id,
                            'periodo_original_id' => $eventoOriginalPeriodoId,
                            'novo_periodo_id' => $novoPeriodoId,
                            'new_values' => $dadosEvento
                        ],
                        'duplicate_evento',
                        $novoEvento,
                        $novoPeriodoId
                    );
                    
                    session()->flash('mensagem', 'Evento duplicado para o novo período com sucesso!');
                } else {
                    // Se for o mesmo período, atualiza normalmente
                    $this->eventoEditando->update($dadosEvento);
                    $evento = $this->eventoEditando;
                    
                    ActivityLog::createLog(
                        'info',
                        'Evento atualizado com sucesso',
                        [
                            'action' => 'update_evento',
                            'evento_id' => $evento->id,
                            'titulo' => $evento->titulo,
                            'promotoria_id' => $evento->promotoria_id,
                            'periodo_id' => $evento->periodo_id,
                            'new_values' => $dadosEvento,
                            'old_values' => $this->eventoEditando->getOriginal()
                        ],
                        'update_evento',
                        $evento,
                        $evento->periodo_id
                    );
                    
                    session()->flash('mensagem', 'Evento atualizado com sucesso!');
                }
            } else {
                // Criação de novo evento
                $evento = Evento::create($dadosEvento);
                
                ActivityLog::createLog(
                    'info',
                    'Novo evento criado com sucesso',
                    [
                        'action' => 'create_evento',
                        'evento_id' => $evento->id,
                        'titulo' => $evento->titulo,
                        'promotoria_id' => $evento->promotoria_id,
                        'periodo_id' => $evento->periodo_id,
                        'new_values' => $dadosEvento,
                        'old_values' => null
                    ],
                    'create_evento',
                    $evento,
                    $evento->periodo_id
                );
                
                session()->flash('mensagem', 'Evento criado com sucesso!');
            }
            
            // Limpar e recriar designação do promotor
            EventoPromotor::where('evento_id', $evento->id)->delete();
            
            $promotorId = (int) $this->promotor_id;
            if ($promotorId > 0) {
                EventoPromotor::create([
                    'evento_id' => $evento->id,
                    'promotor_id' => $promotorId,
                    'tipo' => 'substituto', // Sempre definir como substituto
                    'data_inicio_designacao' => $this->periodo_inicio ?: null,
                    'data_fim_designacao' => $this->periodo_fim ?: null,
                    'ordem' => 1,
                    'observacoes' => null,
                ]);
            }

            if (!$this->modoEdicao && $this->periodo_id) {
                $periodo = Periodo::find($this->periodo_id);
                if ($periodo) {
                    $espelho = Espelho::firstOrCreate([
                        'periodo_id' => $periodo->id,
                    ], [
                        'nome' => 'Espelho ' . $periodo->periodo_inicio->format('m/Y'),
                        'status' => 'ativo',
                        'municipio_id' => null,
                        'grupo_promotorias_id' => null,
                        'plantao_atendimento_id' => null,
                    ]);
                    
                    $espelho->eventos()->syncWithoutDetaching([$evento->id => [
                        'ordem' => $espelho->eventos()->count() + 1
                    ]]);
                }
            }
            
            DB::commit();
            $this->fecharModal();
            
            $this->atualizarPromotoresListado();
            $this->dispatch('eventoSalvo');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            ActivityLog::createLog(
                'error',
                'Erro ao salvar evento: ' . $e->getMessage(),
                [
                    'user_id' => auth()->id(),
                    'modo_edicao' => $this->modoEdicao,
                    'evento_id' => $this->eventoEditando?->id,
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'dados_evento' => [
                        'titulo' => $this->titulo,
                        'promotoria_id' => $this->promotoria_id,
                        'periodo_id' => $this->periodo_id
                    ],
                    'stack_trace' => $e->getTraceAsString()
                ],
                'error_save_evento',
                null,
                (int) $this->periodo_id
            );
            
            session()->flash('erro', 'Erro ao salvar evento: ' . $e->getMessage());
        }
    }

    public function deletar(int $eventoId)
    {
        $evento = Evento::find($eventoId);
        
        try {
            DB::beginTransaction();
            
            if (!$evento) {
                ActivityLog::createLog(
                    'warning',
                    'Tentativa de deletar evento inexistente',
                    [
                        'user_id' => auth()->id(),
                        'evento_id' => $eventoId,
                        'old_values' => null,
                        'new_values' => null
                    ],
                    'delete_evento_error',
                    null,
                    null
                );
                
                DB::rollBack();
                session()->flash('erro', 'Evento não encontrado.');
                return;
            }
            
            $evento->espelhos()->detach();
            
            EventoPromotor::where('evento_id', $evento->id)->delete();
            
            $evento->promotores()->detach();
            
            $evento->delete();
            
            ActivityLog::createLog(
                'info',
                'Evento deletado com sucesso',
                [
                    'action' => 'delete_evento',
                    'evento_id' => $eventoId,
                    'evento_titulo' => $evento->titulo,
                    'promotoria_id' => $evento->promotoria_id,
                    'periodo_id' => $evento->periodo_id,
                    'old_values' => $evento->toArray(),
                    'new_values' => null
                ],
                'delete_evento',
                $evento,
                $evento->periodo_id
            );
            
            DB::commit();
            session()->flash('mensagem', 'Evento deletado com sucesso!');
            $this->atualizarPromotoresListado();
            $this->dispatch('eventoSalvo');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            ActivityLog::createLog(
                'error',
                'Erro ao deletar evento: ' . $e->getMessage(),
                [
                    'user_id' => auth()->id(),
                    'evento_id' => $eventoId,
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'dados_evento' => [
                        'titulo' => $this->titulo,
                        'promotoria_id' => $this->promotoria_id,
                        'periodo_id' => $this->periodo_id
                    ],
                    'stack_trace' => $e->getTraceAsString(),
                    'old_values' => $evento ? $evento->toArray() : null,
                    'new_values' => null
                ],
                'error_delete_evento',
                null,
                null
            );
            
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
        $this->promotor_id = '';
        
        // Manter o período atual se já estiver definido, senão usar o mais recente
        if (empty($this->periodo_id)) {
            $periodoMaisRecente = $this->obterPeriodoMaisRecente();
            $this->periodo_id = $periodoMaisRecente?->id;
        }
        
        $this->eventoEditando = null;
        $this->resetValidation();
    }

    /**
     * Lista todas as designações dos promotores substitutos em um período específico
     * Inclui tanto designações manuais quanto automáticas do espelho
     */
    public function listarDesignacoesPromotoresSubstitutos($periodoId = null)
    {
        $periodoId = $periodoId ?: $this->periodoSelecionadoId;
        
        if (!$periodoId) {
            return collect();
        }

        $promotoresSubstitutos = Promotor::where('tipo', 'substituto')
            ->orderBy('nome', 'asc')
            ->get();

        $designacoes = $promotoresSubstitutos->map(function ($promotor) use ($periodoId) {
            // Designações manuais (criadas neste componente - evento_do_substituto = true)
            $designacoesManuais = EventoPromotor::where('promotor_id', $promotor->id)
                ->whereHas('evento', function ($query) use ($periodoId) {
                    $query->where('periodo_id', $periodoId)
                          ->where('evento_do_substituto', true); // Eventos criados manualmente
                })
                ->with(['evento.promotoria', 'evento'])
                ->get();

            // Designações automáticas do espelho (evento_do_substituto = null ou false)
            $designacoesAutomaticas = EventoPromotor::where('promotor_id', $promotor->id)
                ->whereHas('evento', function ($query) use ($periodoId) {
                    $query->where('periodo_id', $periodoId)
                          ->where(function($q) {
                              $q->whereNull('evento_do_substituto')
                                ->orWhere('evento_do_substituto', false);
                          }); // Designações automáticas
                })
                ->with(['evento.promotoria', 'evento'])
                ->get();

            // Converter designações manuais
            $eventosManuais = $designacoesManuais->map(function ($designacao) {
                return [
                    'evento_id' => $designacao->evento_id,
                    'evento_titulo' => $designacao->evento->titulo,
                    'evento_tipo' => $designacao->evento->tipo,
                    'promotoria_nome' => $designacao->evento->promotoria->nome ?? 'N/A',
                    'tipo_designacao' => $designacao->tipo,
                    'data_inicio' => $designacao->data_inicio_designacao?->format('d/m/Y'),
                    'data_fim' => $designacao->data_fim_designacao?->format('d/m/Y'),
                    'observacoes' => $designacao->observacoes,
                    'ordem' => $designacao->ordem,
                    'is_manual' => true, // Marca como manual
                    'is_urgente' => $designacao->evento->is_urgente ?? false,
                    'evento_do_substituto' => $designacao->evento->evento_do_substituto ?? false
                ];
            });

            // Converter designações automáticas
            $eventosAutomaticos = $designacoesAutomaticas->map(function ($designacao) {
                return [
                    'evento_id' => $designacao->evento_id,
                    'evento_titulo' => $designacao->evento->titulo,
                    'evento_tipo' => $designacao->evento->tipo,
                    'promotoria_nome' => $designacao->evento->promotoria->nome ?? 'N/A',
                    'tipo_designacao' => $designacao->tipo,
                    'data_inicio' => $designacao->data_inicio_designacao?->format('d/m/Y'),
                    'data_fim' => $designacao->data_fim_designacao?->format('d/m/Y'),
                    'observacoes' => $designacao->observacoes,
                    'ordem' => $designacao->ordem,
                    'is_manual' => false, // Marca como automática
                    'is_urgente' => $designacao->evento->is_urgente ?? false,
                    'evento_do_substituto' => $designacao->evento->evento_do_substituto ?? false
                ];
            });

            // Combinar todos os eventos
            $todosEventos = $eventosManuais->concat($eventosAutomaticos);

            return [
                'promotor_id' => $promotor->id,
                'promotor_nome' => $promotor->nome,
                'promotor_cargos' => is_array($promotor->cargos) ? implode(', ', $promotor->cargos) : 'N/A',
                'promotor_tipo' => $promotor->tipo,
                'total_eventos' => $todosEventos->count(),
                'total_manuais' => $eventosManuais->count(),
                'total_automaticos' => $eventosAutomaticos->count(),
                'eventos' => $todosEventos
            ];
        });

        return $designacoes;
    }

    /**
     * Obtém estatísticas das designações de promotores substitutos por período
     */
    public function obterEstatisticasDesignacoesSubstitutos($periodoId = null)
    {
        $periodoId = $periodoId ?: $this->periodoSelecionadoId;
        
        if (!$periodoId) {
            return [
                'total_promotores_substitutos' => 0,
                'total_designacoes' => 0,
                'total_manuais' => 0,
                'total_automaticos' => 0,
                'promotores_com_designacoes' => 0
            ];
        }

        $totalPromotoresSubstitutos = Promotor::where('tipo', 'substituto')->count();
        
        // Designações manuais (evento_do_substituto = true)
        $totalManuais = EventoPromotor::whereHas('evento', function ($query) use ($periodoId) {
            $query->where('periodo_id', $periodoId)
                  ->where('evento_do_substituto', true);
        })
        ->whereHas('promotor', function ($query) {
            $query->where('tipo', 'substituto');
        })
        ->count();

        // Designações automáticas (evento_do_substituto = null ou false)
        $totalAutomaticos = EventoPromotor::whereHas('evento', function ($query) use ($periodoId) {
            $query->where('periodo_id', $periodoId)
                  ->where(function($q) {
                      $q->whereNull('evento_do_substituto')
                        ->orWhere('evento_do_substituto', false);
                  });
        })
        ->whereHas('promotor', function ($query) {
            $query->where('tipo', 'substituto');
        })
        ->count();

        $totalDesignacoes = $totalManuais + $totalAutomaticos;

        $promotoresComDesignacoes = EventoPromotor::whereHas('evento', function ($query) use ($periodoId) {
            $query->where('periodo_id', $periodoId);
        })
        ->whereHas('promotor', function ($query) {
            $query->where('tipo', 'substituto');
        })
        ->distinct('promotor_id')
        ->count('promotor_id');

        return [
            'total_promotores_substitutos' => $totalPromotoresSubstitutos,
            'total_designacoes' => $totalDesignacoes,
            'total_manuais' => $totalManuais,
            'total_automaticos' => $totalAutomaticos,
            'promotores_com_designacoes' => $promotoresComDesignacoes
        ];
    }


    public function render()
    {
        if (empty($this->promotoresListado)) {
            $this->atualizarPromotoresListado();
        }
        
        return view('livewire.espelho.promotor-eventos');
    }
}