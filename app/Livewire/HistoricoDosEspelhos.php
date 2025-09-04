<?php

namespace App\Livewire;

use App\Models\Espelho;
use App\Models\Periodo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class HistoricoDosEspelhos extends Component
{
    use WithPagination;
    
    public string $termoBusca = '';
    public string $filtroStatus = '';
    public string $filtroPeriodo = '';
    
    protected $listeners = ['espelhoAtualizado' => '$refresh'];
    
    public function mount()
    {
        $this->resetarFiltros();
    }
    
    public function resetarFiltros()
    {
        $this->termoBusca = '';
        $this->filtroStatus = '';
        $this->filtroPeriodo = '';
        $this->resetPage();
    }
    
    #[Computed]
    public function espelhos()
    {
        $query = Periodo::withCount(['eventos', 'espelhos'])
            ->with(['eventos' => function($query) {
                $query->with('promotoria.grupoPromotoria.municipio');
            }]);
        
        // Filtro por termo de busca
        if (!empty($this->termoBusca)) {
            $query->where(function($q) {
                $q->where('periodo_inicio', 'like', '%' . $this->termoBusca . '%')
                  ->orWhere('periodo_fim', 'like', '%' . $this->termoBusca . '%')
                  ->orWhere('status', 'like', '%' . $this->termoBusca . '%');
            });
        }
        
        // Filtro por status do período
        if (!empty($this->filtroStatus)) {
            $query->where('status', $this->filtroStatus);
        }
        
        // Filtro por período específico
        if (!empty($this->filtroPeriodo)) {
            $query->where('id', $this->filtroPeriodo);
        }
        
        return $query->orderBy('periodo_inicio', 'desc')
                    ->paginate(10);
    }
    
    #[Computed]
    public function periodos()
    {
        return Periodo::orderBy('periodo_inicio', 'desc')->get();
    }
    
    public function limparFiltros()
    {
        $this->resetarFiltros();
    }
    
    public function atualizarFiltros()
    {
        $this->resetPage();
    }
    
    public function getStatusPeriodoLabel($status)
    {
        return match($status) {
            'em_processo_publicacao' => 'Em Processo de Publicação',
            'publicado' => 'Publicado',
            'arquivado' => 'Arquivado',
            default => ucfirst($status)
        };
    }
    
    public function getStatusPeriodoColor($status)
    {
        return match($status) {
            'em_processo_publicacao' => 'bg-yellow-200 text-yellow-900 border border-yellow-300',
            'publicado' => 'bg-green-200 text-green-900 border border-green-300',
            'arquivado' => 'bg-gray-200 text-gray-900 border border-gray-300',
            default => 'bg-blue-200 text-blue-900 border border-blue-300'
        };
    }
    
    
    public function gerarPdf($periodoId)
    {
        try {
            $periodo = Periodo::findOrFail($periodoId);
            
            // Redirecionar para o controller de geração de PDF
            return redirect()->route('espelho.pdf.visualizar', ['periodo_id' => $periodoId]);
            
        } catch (\Exception $e) {
            session()->flash('erro', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.historico-dos-espelhos.historico-dos-espelhos');
    }
}
