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
    
    #[Rule('required')]
    public string $periodo_id = '';
    
    #[Rule('required')]
    public string $municipio_id = '';
    
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
        // Debug mais detalhado
        \Log::info('=== DEBUG DETALHADO ===');
        \Log::info('Valores das propriedades:', [
            'promotorSelecionado' => $this->promotorSelecionado,
            'dataInicioDesignacao' => $this->dataInicioDesignacao,
            'dataFimDesignacao' => $this->dataFimDesignacao,
            'tipoDesignacao' => $this->tipoDesignacao
        ]);
        
        \Log::info('Tipo das propriedades:', [
            'promotor_tipo' => gettype($this->promotorSelecionado),
            'data_inicio_tipo' => gettype($this->dataInicioDesignacao),
            'data_fim_tipo' => gettype($this->dataFimDesignacao)
        ]);
        
        \Log::info('Verificação booleana:', [
            'promotor_vazio' => empty($this->promotorSelecionado),
            'data_inicio_vazia' => empty($this->dataInicioDesignacao),
            'data_fim_vazia' => empty($this->dataFimDesignacao)
        ]);
        
        \Log::info('Verificação com isset:', [
            'promotor_isset' => isset($this->promotorSelecionado),
            'data_inicio_isset' => isset($this->dataInicioDesignacao),
            'data_fim_isset' => isset($this->dataFimDesignacao)
        ]);
        
        if ($this->promotorSelecionado && $this->dataInicioDesignacao && $this->dataFimDesignacao) {
            \Log::info('Todos os campos estão preenchidos');
            
            $promotor = Promotor::find($this->promotorSelecionado);
            
            if ($promotor) {
                \Log::info('Promotor encontrado:', ['nome' => $promotor->nome]);
                
                $dataInicio = \Carbon\Carbon::parse($this->dataInicioDesignacao);
                $dataFim = \Carbon\Carbon::parse($this->dataFimDesignacao);
                
                \Log::info('Datas parseadas:', [
                    'inicio' => $dataInicio->format('Y-m-d'),
                    'fim' => $dataFim->format('Y-m-d')
                ]);
                
                if ($dataInicio->gt($dataFim)) {
                    \Log::warning('ERRO: Data início maior que data fim');
                    session()->flash('erro', 'A data de início deve ser anterior à data de fim.');
                    return;
                }
                
                \Log::info('Datas válidas, adicionando ao array...');
                
                $this->promotoresPlantao[] = [
                    'id' => $promotor->id,
                    'nome' => $promotor->nome,
                    'data_inicio' => $this->dataInicioDesignacao,
                    'data_fim' => $this->dataFimDesignacao,
                    'tipo' => $this->tipoDesignacao,
                    'ordem' => count($this->promotoresPlantao) + 1,
                    'status' => 'ativo',
                ];
                
                // CORREÇÃO: Passar como array
                \Log::info('Promotor adicionado com sucesso. Total no array:', ['total' => count($this->promotoresPlantao)]);
                
                // Resetar campos
                $this->promotorSelecionado = '';
                $this->dataInicioDesignacao = '';
                $this->dataFimDesignacao = '';
                $this->tipoDesignacao = 'titular';
                
                session()->flash('mensagem', 'Promotor adicionado com sucesso!');
            } else {
                \Log::error('Promotor não encontrado no banco', ['id' => $this->promotorSelecionado]);
            }
        } else {
            \Log::warning('Campos obrigatórios não preenchidos:', [
                'promotor_preenchido' => !empty($this->promotorSelecionado),
                'data_inicio_preenchida' => !empty($this->dataInicioDesignacao),
                'data_fim_preenchida' => !empty($this->dataFimDesignacao)
            ]);
        }
        
        \Log::info('=== FIM DA FUNÇÃO adicionarPromotor ===');
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

    public function getPlantoesProperty()
    {
        return PlantaoAtendimento::query()
            ->with(['municipio', 'periodo', 'promotores'])
            ->when($this->termoBusca, fn ($q) => $q->where('nome', 'like', '%' . $this->termoBusca . '%'))
            ->when($this->filtroMunicipio, fn ($q) => $q->where('municipio_id', $this->filtroMunicipio))
            ->when($this->filtroPeriodo, fn ($q) => $q->where('periodo_id', $this->filtroPeriodo))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getMunicipiosProperty()
    {
        return Municipio::orderBy('nome')->get();
    }

    public function getPeriodosProperty()
    {
        return Periodo::orderBy('periodo_inicio', 'desc')->get();
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

    public function render()
    {
        return view('livewire.espelho.plantao-urgencia');
    }
}
