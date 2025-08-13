<?php

namespace App\Livewire;

use App\Models\PlantaoAtendimento;
use App\Models\Promotor;
use App\Models\Periodo;
use App\Models\Municipio;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class PlantaoUrgencia extends Component
{
    use WithPagination;
    
    // Properties com validação
    #[Rule('required')]
    public string $periodo_id = '';
    
    #[Rule('required')]
    public string $municipio_id = '';
    
    #[Rule('required|min:2|max:100')]
    public string $nome = '';
    
    #[Rule('nullable|max:500')]
    public string $observacoes = '';
    
    // Estado do componente
    public ?PlantaoAtendimento $plantaoEditando = null;
    public bool $mostrarModal = false;
    public bool $modoEdicao = false;
    public string $termoBusca = '';
    public string $filtroMunicipio = '';
    public string $filtroPeriodo = '';
    
    // Para gerenciar promotores do plantão
    public array $promotoresPlantao = [];
    public string $promotorSelecionado = '';
    public string $dataInicioDesignacao = '';
    public string $dataFimDesignacao = '';
    public string $tipoDesignacao = 'titular';
    
    protected $listeners = ['plantaoSalvo' => '$refresh'];

    public function mount()
    {
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
        
        $this->mostrarModal = true;
    }

    public function abrirModalEditar(PlantaoAtendimento $plantao)
    {
        $this->modoEdicao = true;
        $this->plantaoEditando = $plantao;
        $this->periodo_id = $plantao->periodo_id;
        $this->municipio_id = $plantao->municipio_id;
        $this->nome = $plantao->nome ?? '';
        $this->observacoes = $plantao->observacoes ?? '';
        
        $this->promotoresPlantao = $plantao->promotores()
            ->withPivot(['data_inicio_designacao', 'data_fim_designacao', 'ordem', 'tipo_designacao', 'status'])
            ->get()
            ->map(function($promotor) {
                return [
                    'id' => $promotor->id,
                    'nome' => $promotor->nome,
                    'data_inicio' => $promotor->pivot->data_inicio_designacao,
                    'data_fim' => $promotor->pivot->data_fim_designacao,
                    'tipo' => $promotor->pivot->tipo_designacao,
                    'ordem' => $promotor->pivot->ordem,
                    'status' => $promotor->pivot->status,
                ];
            })->toArray();
            
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
        
        if ($this->modoEdicao && $this->plantaoEditando) {
            $this->plantaoEditando->update([
                'periodo_id' => $this->periodo_id,
                'municipio_id' => $this->municipio_id,
                'nome' => $this->nome,
                'observacoes' => $this->observacoes,
            ]);
            
            $this->atualizarPromotores($this->plantaoEditando);
            
            session()->flash('mensagem', 'Plantão de urgência atualizado com sucesso!');
        } else {
            $plantao = PlantaoAtendimento::create([
                'periodo_id' => $this->periodo_id,
                'municipio_id' => $this->municipio_id,
                'nome' => $this->nome,
                'observacoes' => $this->observacoes,
            ]);
            
            $this->atualizarPromotores($plantao);
            
            session()->flash('mensagem', 'Plantão de urgência criado com sucesso!');
        }
        
        $this->fecharModal();
        $this->dispatch('plantaoSalvo');
    }

    public function deletar(PlantaoAtendimento $plantao)
    {
        try {
            $plantao->delete();
            session()->flash('mensagem', 'Plantão de urgência deletado com sucesso!');
            $this->dispatch('plantaoSalvo');
        } catch (\Exception $e) {
            session()->flash('erro', 'Não é possível deletar este plantão pois está sendo usado em outras partes do sistema.');
        }
    }

    public function adicionarPromotor()
    {
        if ($this->promotorSelecionado && $this->dataInicioDesignacao && $this->dataFimDesignacao) {
            $promotor = Promotor::find($this->promotorSelecionado);
            
            if ($promotor) {
                $this->promotoresPlantao[] = [
                    'id' => $promotor->id,
                    'nome' => $promotor->nome,
                    'data_inicio' => $this->dataInicioDesignacao,
                    'data_fim' => $this->dataFimDesignacao,
                    'tipo' => $this->tipoDesignacao,
                    'ordem' => count($this->promotoresPlantao) + 1,
                    'status' => 'ativo',
                ];
                
                // Resetar campos de promotor
                $this->promotorSelecionado = '';
                $this->dataInicioDesignacao = '';
                $this->dataFimDesignacao = '';
                $this->tipoDesignacao = 'titular';
            }
        }
    }

    public function removerPromotor($index)
    {
        unset($this->promotoresPlantao[$index]);
        $this->promotoresPlantao = array_values($this->promotoresPlantao);
    }

    private function atualizarPromotores(PlantaoAtendimento $plantao)
    {
        // Remover todos os promotores existentes
        $plantao->promotores()->detach();
        
        // Adicionar novos promotores
        foreach ($this->promotoresPlantao as $promotorData) {
            $plantao->promotores()->attach($promotorData['id'], [
                'data_inicio_designacao' => $promotorData['data_inicio'],
                'data_fim_designacao' => $promotorData['data_fim'],
                'ordem' => $promotorData['ordem'],
                'tipo_designacao' => $promotorData['tipo'],
                'status' => $promotorData['status'],
            ]);
        }
    }

    public function resetarFormulario()
    {
        $this->periodo_id = '';
        $this->municipio_id = '';
        $this->nome = '';
        $this->observacoes = '';
        $this->plantaoEditando = null;
        $this->promotoresPlantao = [];
        $this->promotorSelecionado = '';
        $this->dataInicioDesignacao = '';
        $this->dataFimDesignacao = '';
        $this->tipoDesignacao = 'titular';
        $this->resetValidation();
    }

    public function render()
    {
        $plantoes = PlantaoAtendimento::query()
            ->with(['municipio', 'periodo', 'promotores'])
            ->when($this->termoBusca, function ($query) {
                $query->where('nome', 'like', '%' . $this->termoBusca . '%');
            })
            ->when($this->filtroMunicipio, function ($query) {
                $query->where('municipio_id', $this->filtroMunicipio);
            })
            ->when($this->filtroPeriodo, function ($query) {
                $query->where('periodo_id', $this->filtroPeriodo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $municipios = Municipio::orderBy('nome')->get();
        $periodos = Periodo::orderBy('periodo_inicio', 'desc')->get();
        $promotores = Promotor::orderBy('nome')->get();
        
        return view('livewire.plantao-urgencia', compact('plantoes', 'municipios', 'periodos', 'promotores'));
    }
}
