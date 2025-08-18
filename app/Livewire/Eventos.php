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
	
	protected $listeners = ['eventoSalvo' => 'atualizarDados'];

	public function mount()
	{
		$this->carregarDados();
		
		$periodoMaisRecente = Periodo::where('status', 'em_processo_publicacao')
			->orderBy('periodo_inicio', 'desc')
			->first();
		
		if (!$periodoMaisRecente) {
			$periodoMaisRecente = Periodo::where('status', 'publicado')
				->orderBy('periodo_inicio', 'desc')
				->first();
		}
		
		$this->periodoSelecionado = $periodoMaisRecente;
		$this->periodoSelecionadoId = $this->periodoSelecionado?->id;
		
		// Garantir que os dados são carregados após definir o período
		$this->atualizarPromotoriasListado();
		$this->resetarFormulario();
	}
	
	public function carregarDados()
	{
		$this->promotorias = Promotoria::orderBy('nome')->get();
		$this->promotores = Promotor::orderBy('nome')->get();
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
	
	public function atualizarPromotoriasListado()
	{
		// Buscar todos os grupos de promotorias com suas promotorias
		$gruposComPromotorias = \App\Models\GrupoPromotoria::with([
			'promotorias.promotorTitular',
			'municipio',
			'promotorias.eventos' => function ($q) {
				$q->with(['designacoes.promotor']);
				
				
				
				$q->orderBy('periodo_inicio');
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
			'eventos' => function ($q) {
				$q->with(['designacoes.promotor']);
				
				
				
				$q->orderBy('periodo_inicio');
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

		$this->promotoriasListado = $gruposComPromotorias;
	}
	
	public function atualizarDados()
	{
		$this->atualizarPromotoriasListado();
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
		
		$ultimoPeriodo = Periodo::orderBy('created_at', 'desc')->first();
		if ($ultimoPeriodo) {
			$this->periodo_id = (string) $ultimoPeriodo->id;
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
		
		// Atualizar dados quando fecha o modal
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
			
			if ($this->modoEdicao && $this->eventoEditando) {
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
			} else {
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
		$this->periodo_id = '';
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
		// Forçar atualização se não há dados
		if (empty($this->promotoriasListado)) {
			$this->atualizarPromotoriasListado();
		}
		
		return view('livewire.espelho.eventos');
	}
}