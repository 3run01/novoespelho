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
use Illuminate\Support\Str;

class Eventos extends Component
{
	use WithPagination;
	
	// Properties com validação - removidas as obrigatoriedades
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
	
	// Removida a propriedade is_urgente
	
	public ?Evento $eventoEditando = null;
	public bool $mostrarModal = false;
	public bool $modoEdicao = false;
	public string $termoBusca = '';
	public ?Periodo $periodoSelecionado = null;
	public ?string $periodoSelecionadoId = null;
	
	public $promotorias = [];
	public $promotores = [];
	public $periodos = [];
	public $promotoriasListado = [];
	
	public array $promotoresDesignacoes = [];
	
	protected $listeners = ['eventoSalvo' => 'atualizarDados'];

	public function mount()
	{
		$this->carregarDados();
		$this->periodoSelecionado = Periodo::orderBy('created_at', 'desc')->first();
		$this->periodoSelecionadoId = $this->periodoSelecionado?->id;
		$this->atualizarPromotoriasListado();
		$this->resetarFormulario();
	}
	
	public function carregarDados()
	{
		$this->promotorias = Promotoria::orderBy('nome')->get();
		$this->promotores = Promotor::orderBy('nome')->get();
		$this->periodos = Periodo::orderBy('periodo_inicio', 'desc')->get();
	}
	
	public function atualizarPromotoriasListado()
	{
		$this->promotoriasListado = \App\Models\GrupoPromotoria::with([
			'promotorias.promotorTitular',
			'promotorias.eventos' => function ($q) {
				$q->with(['designacoes.promotor'])
				  ->when($this->periodoSelecionado, function ($query) {
					  $query->where('periodo_inicio', '>=', $this->periodoSelecionado->periodo_inicio)
							->where('periodo_fim', '<=', $this->periodoSelecionado->periodo_fim);
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
		$this->modoEdicao = false;
		$this->resetarFormulario();
		
		if ($this->periodoSelecionado) {
			$this->periodo_inicio = $this->periodoSelecionado->periodo_inicio->format('Y-m-d');
			$this->periodo_fim = $this->periodoSelecionado->periodo_fim->format('Y-m-d');
		}
		
		$this->promotoresDesignacoes = [[
			'uid' => (string) Str::uuid(),
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

	public function abrirModalEditar($eventoId)
	{
		// Carregar o evento com as designações (cada linha da pivot)
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
	}

	public function salvar()
	{
		$this->validate();
		
		// Validação das designações - ainda mais flexíveis
		$this->validate([
			'promotoresDesignacoes' => 'array|min:1',
			'promotoresDesignacoes.*.promotor_id' => 'required|exists:promotores,id',
			'promotoresDesignacoes.*.tipo' => 'nullable|in:titular,substituto,plantao,outro',
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
				'is_urgente' => false
			];
			
			if ($this->modoEdicao && $this->eventoEditando) {
				$this->eventoEditando->update($dadosEvento);
				$evento = $this->eventoEditando;
				session()->flash('mensagem', 'Evento atualizado com sucesso!');
			} else {
				$evento = Evento::create($dadosEvento);
				session()->flash('mensagem', 'Evento criado com sucesso!');
			}
			
			// Recria as designações (permite várias por mesmo promotor)
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

			// Criar espelho apenas se o período estiver selecionado - SEM valores fixos
			if (!$this->modoEdicao && $this->periodoSelecionado) {
				$espelho = Espelho::firstOrCreate([
					'periodo_id' => $this->periodoSelecionado->id,
				], [
					'nome' => 'Espelho ' . $this->periodoSelecionado->periodo_inicio->format('m/Y'),
					'status' => 'ativo',
					'municipio_id' => null,
					'grupo_promotorias_id' => null,
					'plantao_atendimento_id' => null,
				]);
				
				// Vincular evento ao espelho
				$espelho->eventos()->syncWithoutDetaching([$evento->id => [
					'ordem' => $espelho->eventos()->count() + 1
				]]);
			}
			
			DB::commit();
			$this->fecharModal();
			
			// Forçar atualização da listagem após salvar
			$this->atualizarPromotoriasListado();
			$this->dispatch('eventoSalvo');
			
		} catch (\Exception $e) {
			DB::rollback();
			session()->flash('erro', 'Erro ao salvar evento: ' . $e->getMessage());
		}
	}

	public function deletar(int $eventoId)
	{
		try {
			DB::beginTransaction();
			
			$evento = Evento::find($eventoId);
			if (!$evento) {
				DB::rollBack();
				session()->flash('erro', 'Evento não encontrado.');
				return;
			}
			
			// Remove vinculações com espelhos
			$evento->espelhos()->detach();
			
			// Remove todas as designações explicitamente
			EventoPromotor::where('evento_id', $evento->id)->delete();
			
			// Remove vinculações many-to-many apenas por garantia
			$evento->promotores()->detach();
			
			// Remove o evento
			$evento->delete();
			
			DB::commit();
			session()->flash('mensagem', 'Evento deletado com sucesso!');
			$this->atualizarPromotoriasListado();
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
		$this->eventoEditando = null;
		$this->resetValidation();
	}
	
	public function render()
	{
		return view('livewire.eventos');
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
}
