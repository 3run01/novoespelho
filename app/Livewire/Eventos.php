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

class Eventos extends Component
{
	use WithPagination;

	/**
	 * Cache em memória para períodos do getter durante o ciclo de renderização.
	 */
	protected ?\Illuminate\Support\Collection $periodosCache = null;

	#[Rule('nullable|min:3|max:200')]
	public string $titulo = '';

	#[Rule('nullable')]
	public string $tipo = '';

	#[Rule('nullable|date')]
	public string $periodo_inicio = '';

	#[Rule('nullable|date|after_or_equal:periodo_inicio')]
	public string $periodo_fim = '';

	#[Rule('required|exists:promotorias,id')]
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
	public $promotoriasListado = [];

	public array $promotoresDesignacoes = [];

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

		$this->atualizarPromotoriasListado();

		$this->resetarFormulario();

		$this->periodo_id = $this->periodoSelecionado?->id;

		Log::info('Componente Eventos inicializado com sucesso', [
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
		$this->promotores = Promotor::orderBy('nome')->get();
	}

	public function getPeriodosProperty()
	{
		if ($this->periodosCache !== null) {
			return $this->periodosCache;
		}

		Log::info('Buscando períodos para o componente Eventos');

		$periodos = Periodo::whereIn('status', ['em_processo_publicacao', 'publicado'])
			->orderBy('periodo_inicio', 'desc')
			->get();

		if ($periodos->isEmpty()) {
			$periodos = Periodo::orderBy('periodo_inicio', 'desc')->get();
		}

		$this->periodosCache = $periodos;

		return $this->periodosCache;
	}

	/**
	 * Obtém os períodos diretamente do banco de dados
	 * Este método é usado para forçar a atualização dos períodos
	 */
	public function obterPeriodosAtualizados()
	{
		return $this->getPeriodosProperty();
	}

	public function atualizarPromotoriasListado()
	{
		$periodosRecentes = Periodo::whereIn('status', ['em_processo_publicacao', 'publicado'])
			->orderBy('periodo_inicio', 'desc')
			->get();

		if ($periodosRecentes->isEmpty()) {
			$this->promotoriasListado = collect();
			return;
		}

		$gruposComPromotorias = \App\Models\GrupoPromotoria::with([
			// Eager loading completo para evitar re-acessos no Blade
			'promotorias.promotorTitular',
			'promotorias.grupoPromotoria.municipio',
			'municipio',
			'promotorias.eventos' => function ($q) use ($periodosRecentes) {
				$q->with(['designacoes.promotor'])
				  ->where(function($query) use ($periodosRecentes) {
					  $query->whereIn('periodo_id', $periodosRecentes->pluck('id'))
						  ->whereRaw('periodo_id = (
							  SELECT periodo_id
							  FROM eventos e2
							  WHERE e2.promotoria_id = eventos.promotoria_id
							  AND e2.periodo_id IN (' . $periodosRecentes->pluck('id')->implode(',') . ')
							  ORDER BY e2.periodo_inicio DESC
							  LIMIT 1
						  )');
				  })
				  ->where(function($query) {
					  $query->whereNull('evento_do_substituto')
							->orWhere('evento_do_substituto', false);
				  })
				  ->orderBy('periodo_inicio');
			}
		])
		->when($this->termoBusca, function ($q) {
			$q->whereHas('promotorias', function ($query) {
				$query->where('nome', 'like', '%' . $this->termoBusca . '%');
			});
		})
		->orderBy('nome')
		->get();

		$promotoriasSemGrupo = \App\Models\Promotoria::with([
			'promotorTitular',
			'eventos' => function ($q) use ($periodosRecentes) {
				$q->with(['designacoes.promotor'])
				  // Filtrar apenas o período mais recente para cada promotoria
				  //Mudar essa query direta aqui!!!
				  ->where(function($query) use ($periodosRecentes) {
					  $query->whereIn('periodo_id', $periodosRecentes->pluck('id'))
						  ->whereRaw('periodo_id = (
							  SELECT periodo_id
							  FROM eventos e2
							  WHERE e2.promotoria_id = eventos.promotoria_id
							  AND e2.periodo_id IN (' . $periodosRecentes->pluck('id')->implode(',') . ')
							  ORDER BY e2.periodo_inicio DESC
							  LIMIT 1
						  )');
				  })
				  ->where(function($query) {
					  $query->whereNull('plantao_do_substituto')
							->orWhere('plantao_do_substituto', false);
				  })
				  ->where(function($query) {
					  $query->whereNull('evento_do_substituto')
							->orWhere('evento_do_substituto', false);
				  })
				  ->orderBy('periodo_inicio');
			}
		])
		->whereNull('grupo_promotoria_id')
		->when($this->termoBusca, function ($q) {
			$q->where('nome', 'like', '%' . $this->termoBusca . '%');
		})
		->orderBy('nome')
		->get();

		if ($promotoriasSemGrupo->isNotEmpty()) {
			$grupoVirtual = new \App\Models\GrupoPromotoria();
			$grupoVirtual->nome = 'Promotorias Avulsas';
			$grupoVirtual->promotorias = $promotoriasSemGrupo;
			$gruposComPromotorias->push($grupoVirtual);
		}

		//
		Log::info('Carregando promotorias e eventos', [
			'periodos_ids' => $periodosRecentes->pluck('id')->toArray(),
			'periodos_status' => $periodosRecentes->pluck('status')->toArray(),
			'total_grupos' => $gruposComPromotorias->count(),
			'total_promotorias' => $gruposComPromotorias->sum(function($grupo) {
				return $grupo->promotorias->count();
			})
		]);

		$this->promotoriasListado = $gruposComPromotorias;
	}







	public function atualizarDados()
	{
		$this->atualizarPromotoriasListado();
	}

	/**
	 * Recarrega os períodos e atualiza a seleção quando um novo período é criado
	 */
	public function recarregarPeriodos()
	{
		Log::info('Recarregando períodos após mudança');

		// Invalidar cache dos períodos antes de recalcular
		$this->periodosCache = null;

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

		// Atualizar a lista de promotorias
		$this->atualizarPromotoriasListado();

	}

	/**
	 * Força a atualização da propriedade periodos
	 */
	public function atualizarPeriodos()
	{
		$this->periodosCache = null;
		$this->dispatch('$refresh');
	}

	public function selecionarPeriodo($periodoId)
	{
		$this->periodoSelecionado = $periodoId ? Periodo::find($periodoId) : null;
		$this->periodoSelecionadoId = $this->periodoSelecionado?->id;
		$this->atualizarPromotoriasListado();
		$this->resetPage();
	}

	public function updatedTermoBusca()
	{
		$this->atualizarPromotoriasListado();
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
		$this->atualizarPromotoriasListado();
	}

	public function abrirModalCriarParaPromotoria(int $promotoriaId): void
	{
		$this->abrirModalCriar();
		$this->promotoria_id = (string) $promotoriaId;

		// Não é necessário atualizar aqui, pois o problema é no carregamento inicial
		// $this->atualizarPromotoriasListado();
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

		$this->promotoresDesignacoes = $evento->designacoes->map(function ($designacao) {
			return [
				'uid' => (string) Str::uuid(),
				'promotor_id' => (string) $designacao->promotor_id,
				'tipo' => $designacao->tipo ?? 'titular',
				'data_inicio_designacao' => optional($designacao->data_inicio_designacao)?->format('Y-m-d') ?: '',
				'data_fim_designacao' => optional($designacao->data_fim_designacao)?->format('Y-m-d') ?: '',
				'observacoes' => $designacao->observacoes ?? ''
			];
		})->toArray();

		if (empty($this->promotoresDesignacoes)) {
			$this->promotoresDesignacoes = [[
				'uid' => (string) Str::uuid(),
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

		$this->atualizarPromotoriasListado();
	}

	public function salvar()
	{
		$this->validate();

		$this->validate([
			'promotoresDesignacoes' => 'array|min:1',
			'promotoresDesignacoes.*.promotor_id' => 'required|exists:promotores,id',
			'promotoresDesignacoes.*.tipo' => 'nullable|in:titular,substituto,respondendo,auxiliando',
			'promotoresDesignacoes.*.data_inicio_designacao' => 'nullable|date',
			'promotoresDesignacoes.*.data_fim_designacao' => 'nullable|date',
			'promotoresDesignacoes.*.observacoes' => 'nullable|string|max:500',
		]);

		try {
			DB::beginTransaction();

			$dadosEvento = [
				'titulo' => $this->titulo ?: null,
				'tipo' => $this->tipo ?: null,
				'periodo_inicio' => $this->periodo_inicio ?: null,
				'periodo_fim' => $this->periodo_fim ?: null,
				'promotoria_id' => $this->promotoria_id,
				'periodo_id' => $this->periodo_id,
				'is_urgente' => false,
				'promotores_designacoes' => $this->promotoresDesignacoes
			];

			// Verificar se o período do evento sendo editado é diferente do período atual
			$eventoOriginalPeriodoId = $this->eventoEditando ? $this->eventoEditando->periodo_id : null;
			$novoPeriodoId = (int) $this->periodo_id;

			if ($this->modoEdicao && $this->eventoEditando) {
				// Se o período do evento sendo editado é diferente do período atual
				if ($eventoOriginalPeriodoId && $eventoOriginalPeriodoId !== $novoPeriodoId) {
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

			EventoPromotor::where('evento_id', $evento->id)->delete();
			$ordem = 1;
			foreach ($this->promotoresDesignacoes as $designacao) {
				$promotorId = (int) ($designacao['promotor_id'] ?? 0);
				if ($promotorId <= 0) {
					continue;
				}
				EventoPromotor::create([
					'evento_id' => $evento->id,
					'promotor_id' => $promotorId,
					'tipo' => $designacao['tipo'] ?: 'titular',
					'data_inicio_designacao' => $designacao['data_inicio_designacao'] ?: null,
					'data_fim_designacao' => $designacao['data_fim_designacao'] ?: null,
					'ordem' => $ordem++,
					'observacoes' => $designacao['observacoes'] ?: null,
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

			$this->atualizarPromotoriasListado();
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
			$this->atualizarPromotoriasListado();
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

		// Manter o período atual se já estiver definido, senão usar o mais recente
		if (empty($this->periodo_id)) {
			$periodoMaisRecente = $this->obterPeriodoMaisRecente();
			$this->periodo_id = $periodoMaisRecente?->id;
		}

		$this->eventoEditando = null;
		$this->resetValidation();
	}


	public function adicionarLinhaPromotor(): void
	{
		$this->promotoresDesignacoes[] = [
			'uid' => (string) Str::uuid(),
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


	public function render()
	{
		if (empty($this->promotoriasListado)) {
			$this->atualizarPromotoriasListado();
		}

		return view('livewire.espelho.eventos');
	}

	public function criarNovoPeriodo($dadosPeriodo)
	{
		// Iniciar transação
		DB::beginTransaction();

		try {
			// Criar novo período
			$novoPeriodo = Periodo::create($dadosPeriodo);

			// Buscar período anterior mais recente
			$periodoAnterior = Periodo::orderBy('periodo_fim', 'desc')
				->where('id', '!=', $novoPeriodo->id)
				->first();

			if ($periodoAnterior) {
				// Buscar eventos do período anterior
				$eventosAnteriores = Evento::where('periodo_id', $periodoAnterior->id)->get();

				// Duplicar eventos para o novo período
				foreach ($eventosAnteriores as $eventoAntigo) {
					// Criar novo evento com os mesmos dados do evento anterior, mantendo o original
					$novoEvento = Evento::create([
						'titulo' => $eventoAntigo->titulo,
						'tipo' => $eventoAntigo->tipo,
						'periodo_inicio' => $eventoAntigo->periodo_inicio,
						'periodo_fim' => $eventoAntigo->periodo_fim,
						'promotoria_id' => $eventoAntigo->promotoria_id,
						'periodo_id' => $novoPeriodo->id,
						'is_urgente' => $eventoAntigo->is_urgente
					]);

					$designacoesAntigas = EventoPromotor::where('evento_id', $eventoAntigo->id)->get();
					foreach ($designacoesAntigas as $designacaoAntiga) {
						EventoPromotor::create([
							'evento_id' => $novoEvento->id,
							'promotor_id' => $designacaoAntiga->promotor_id,
							'tipo' => $designacaoAntiga->tipo,
							'data_inicio_designacao' => $designacaoAntiga->data_inicio_designacao,
							'data_fim_designacao' => $designacaoAntiga->data_fim_designacao,
							'ordem' => $designacaoAntiga->ordem,
							'observacoes' => $designacaoAntiga->observacoes
						]);
					}

					// Duplicar associações com espelhos
					$espelhos = $eventoAntigo->espelhos;
					foreach ($espelhos as $espelho) {
						$novoEspelho = Espelho::firstOrCreate(
							['periodo_id' => $novoPeriodo->id],
							[
								'nome' => 'Espelho ' . $novoPeriodo->periodo_inicio->format('m/Y'),
								'status' => 'ativo',
								'municipio_id' => null,
								'grupo_promotorias_id' => null,
								'plantao_atendimento_id' => null,
							]
						);

						$novoEspelho->eventos()->syncWithoutDetaching([$novoEvento->id => [
							'ordem' => $novoEspelho->eventos()->count() + 1
						]]);
					}
				}

				Log::info('Eventos duplicados para novo período', [
					'periodo_anterior_id' => $periodoAnterior->id,
					'novo_periodo_id' => $novoPeriodo->id,
					'total_eventos_duplicados' => $eventosAnteriores->count()
				]);
			}

			DB::commit();
			return $novoPeriodo;
		} catch (\Exception $e) {
			DB::rollBack();

			Log::error('Erro ao criar novo período e duplicar eventos', [
				'error_message' => $e->getMessage(),
				'error_file' => $e->getFile(),
				'error_line' => $e->getLine(),
				'periodo_dados' => $dadosPeriodo
			]);

			throw $e;
		}
	}

	public function duplicarEventosParaNovoPeriodo(Periodo $novoPeriodo)
	{
		// Iniciar transação
		DB::beginTransaction();

		try {
			// Buscar período anterior mais recente
			$periodoAnterior = Periodo::orderBy('periodo_fim', 'desc')
				->where('id', '!=', $novoPeriodo->id)
				->first();

			if ($periodoAnterior) {
				// Buscar eventos do período anterior
				$eventosAnteriores = Evento::where('periodo_id', $periodoAnterior->id)->get();

				// Duplicar eventos para o novo período
				foreach ($eventosAnteriores as $eventoAntigo) {
					// Criar novo evento com os mesmos dados do evento anterior, mantendo o original
					$novoEvento = Evento::create([
						'titulo' => $eventoAntigo->titulo,
						'tipo' => $eventoAntigo->tipo,
						'periodo_inicio' => $eventoAntigo->periodo_inicio,
						'periodo_fim' => $eventoAntigo->periodo_fim,
						'promotoria_id' => $eventoAntigo->promotoria_id,
						'periodo_id' => $novoPeriodo->id,
						'is_urgente' => $eventoAntigo->is_urgente
					]);

					// Duplicar designações de promotores
					$designacoesAntigas = EventoPromotor::where('evento_id', $eventoAntigo->id)->get();
					foreach ($designacoesAntigas as $designacaoAntiga) {
						EventoPromotor::create([
							'evento_id' => $novoEvento->id,
							'promotor_id' => $designacaoAntiga->promotor_id,
							'tipo' => $designacaoAntiga->tipo,
							'data_inicio_designacao' => $designacaoAntiga->data_inicio_designacao,
							'data_fim_designacao' => $designacaoAntiga->data_fim_designacao,
							'ordem' => $designacaoAntiga->ordem,
							'observacoes' => $designacaoAntiga->observacoes
						]);
					}

					// Duplicar associações com espelhos
					$espelhos = $eventoAntigo->espelhos;
					foreach ($espelhos as $espelho) {
						$novoEspelho = Espelho::firstOrCreate(
							['periodo_id' => $novoPeriodo->id],
							[
								'nome' => 'Espelho ' . $novoPeriodo->periodo_inicio->format('m/Y'),
								'status' => 'ativo',
								'municipio_id' => null,
								'grupo_promotorias_id' => null,
								'plantao_atendimento_id' => null,
							]
						);

						$novoEspelho->eventos()->syncWithoutDetaching([$novoEvento->id => [
							'ordem' => $novoEspelho->eventos()->count() + 1
						]]);
					}
				}

				// Log detalhado da duplicação
				Log::info('Eventos duplicados para novo período', [
					'periodo_anterior_id' => $periodoAnterior->id,
					'novo_periodo_id' => $novoPeriodo->id,
					'total_eventos_duplicados' => $eventosAnteriores->count()
				]);
			}

			DB::commit();
			return $novoPeriodo;
		} catch (\Exception $e) {
			DB::rollBack();

			// Log de erro detalhado
			Log::error('Erro ao duplicar eventos para novo período', [
				'error_message' => $e->getMessage(),
				'error_file' => $e->getFile(),
				'error_line' => $e->getLine(),
				'periodo_id' => $novoPeriodo->id
			]);

			throw $e;
		}
	}

}
