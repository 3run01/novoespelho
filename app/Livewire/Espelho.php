<?php

namespace App\Livewire;

use App\Models\Periodo;
use App\Models\PlantaoAtendimento;
use App\Models\Promotoria;
use App\Models\GrupoPromotoria;
use App\Models\Municipio;
use App\Models\Promotor;
use App\Models\EventoPromotor;
use Livewire\Component;

class Espelho extends Component
{
    public $periodos;
    public $plantoes;
    public $promotorias;
    public $promotoriasPorMunicipio;
    public $plantoesPorMunicipio;
    public $promotoresSubstitutos;

    public function mount()
    {
        // Inicializar as coleções
        $this->periodos = collect();
        $this->plantoes = collect();
        $this->promotorias = collect();
        $this->promotoriasPorMunicipio = collect();
        $this->plantoesPorMunicipio = collect();
        $this->promotoresSubstitutos = collect();
        
        $this->carregarDados();
    }

    public function carregarDados()
    {
        try {
            // Carregar o período mais relevante seguindo a prioridade:
            // 1º: Período publicado (mais recente se houver múltiplos)
            // 2º: Período em processo de publicação (mais recente se não houver publicado)
            // 3º: Período arquivado (apenas se não houver outros)
            $periodoPublicado = Periodo::where('status', 'publicado')
                ->orderBy('periodo_inicio', 'desc')
                ->first();
            
            if ($periodoPublicado) {
                $this->periodos = collect([$periodoPublicado]);
            } else {
                $periodoEmProcesso = Periodo::where('status', 'em_processo_publicacao')
                    ->orderBy('periodo_inicio', 'desc')
                    ->first();
                
                if ($periodoEmProcesso) {
                    $this->periodos = collect([$periodoEmProcesso]);
                } else {
                    // Se não houver período publicado nem em processo, pega o mais recente (mesmo que arquivado)
                    $periodoMaisRecente = Periodo::orderBy('periodo_inicio', 'desc')->first();
                    $this->periodos = $periodoMaisRecente ? collect([$periodoMaisRecente]) : collect();
                }
            }
            
            // Carregar plantões de urgência com try/catch
            try {
                if ($this->periodos->isNotEmpty()) {
                    $periodoId = $this->periodos->first()->id;
                    $this->plantoes = PlantaoAtendimento::where('periodo_id', $periodoId)
                        ->with([
                            'municipio',
                            'periodo',
                            'promotores' => function ($query) {
                                $query->withPivot(['tipo_designacao', 'data_inicio_designacao', 'data_fim_designacao']);
                            }
                        ])->get();
                } else {
                    $this->plantoes = collect();
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao carregar plantões: ' . $e->getMessage());
                $this->plantoes = collect();
            }
            
            // Carregar promotorias com try/catch
            try {
                $this->promotorias = GrupoPromotoria::with([
                    'promotorias.promotorTitular',
                    'promotorias.eventos' => function ($query) {
                        $query->with(['designacoes.promotor'])
                              ->orderBy('periodo_inicio');
                    }
                ])->orderBy('nome')->get();
            } catch (\Exception $e) {
                \Log::error('Erro ao carregar promotorias: ' . $e->getMessage());
                $this->promotorias = collect();
            }

            $this->organizarDadosPorMunicipio();
            $this->carregarPromotoresSubstitutos();
        } catch (\Exception $e) {
            \Log::error('Erro geral ao carregar dados do espelho: ' . $e->getMessage());
            // Inicializar coleções vazias em caso de erro
            $this->periodos = collect();
            $this->plantoes = collect();
            $this->promotorias = collect();
            $this->promotoriasPorMunicipio = collect();
            $this->plantoesPorMunicipio = collect();
            $this->promotoresSubstitutos = collect();
        }
    }

    private function organizarDadosPorMunicipio()
    {
        try {
            $promotoriasPorMunicipio = [];
            $plantoesPorMunicipio = [];

            // Se não há período selecionado, não carregar dados
            if ($this->periodos->isEmpty()) {
                $this->promotoriasPorMunicipio = collect();
                $this->plantoesPorMunicipio = collect();
                return;
            }

            $periodoId = $this->periodos->first()->id;

            $promotorias = Promotoria::with([
                'grupoPromotoria.municipio',
                'promotorTitular',
                'eventos' => function ($query) use ($periodoId) {
                    $query->where('periodo_id', $periodoId)
                          ->with(['designacoes.promotor'])
                          ->orderBy('periodo_inicio');
                }
            ])->get();

            foreach ($promotorias as $promotoria) {
                $nomeMunicipio = $promotoria->grupoPromotoria && $promotoria->grupoPromotoria->municipio 
                    ? $promotoria->grupoPromotoria->municipio->nome 
                    : 'Sem município';
                
                if (!isset($promotoriasPorMunicipio[$nomeMunicipio])) {
                    $promotoriasPorMunicipio[$nomeMunicipio] = collect();
                }
                
                $promotoriasPorMunicipio[$nomeMunicipio]->push($promotoria);
            }

            foreach ($this->plantoes as $plantao) {
                $nomeMunicipio = $plantao->municipio ? $plantao->municipio->nome : 'Sem município';
                
                if (!isset($plantoesPorMunicipio[$nomeMunicipio])) {
                    $plantoesPorMunicipio[$nomeMunicipio] = collect();
                }
                
                $plantoesPorMunicipio[$nomeMunicipio]->push($plantao);
            }

            ksort($promotoriasPorMunicipio);
            ksort($plantoesPorMunicipio);
            
            $this->promotoriasPorMunicipio = collect($promotoriasPorMunicipio);
            $this->plantoesPorMunicipio = collect($plantoesPorMunicipio);
        } catch (\Exception $e) {
            \Log::error('Erro ao organizar dados por município: ' . $e->getMessage());
            $this->promotoriasPorMunicipio = collect();
            $this->plantoesPorMunicipio = collect();
        }
    }

    private function carregarPromotoresSubstitutos()
    {
        try {
            if ($this->periodos->isEmpty()) {
                $this->promotoresSubstitutos = collect();
                return;
            }

            $periodoId = $this->periodos->first()->id;

            $promotoresSubstitutos = Promotor::where('tipo', 'substituto')
                ->orderBy('nome', 'asc')
                ->get();

            $designacoes = $promotoresSubstitutos->map(function ($promotor) use ($periodoId) {
                try {
                    // Designações manuais (criadas no componente PromotorEventos - evento_do_substituto = true)
                    $designacoesManuais = EventoPromotor::where('promotor_id', $promotor->id)
                        ->whereHas('evento', function ($query) use ($periodoId) {
                            $query->where('periodo_id', $periodoId)
                                  ->where('evento_do_substituto', true);
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
                                  });
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
                            'is_manual' => true,
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
                            'is_manual' => false,
                            'is_urgente' => $designacao->evento->is_urgente ?? false,
                            'evento_do_substituto' => $designacao->evento->evento_do_substituto ?? false
                        ];
                    });

                    // Combinar todos os eventos
                    $todosEventos = $eventosManuais->concat($eventosAutomaticos)->map(function($evento) {
                        return (object) $evento;
                    });

                    return (object) [
                        'promotor_id' => $promotor->id,
                        'promotor_nome' => $promotor->nome,
                        'promotor_cargos' => is_array($promotor->cargos) ? implode(', ', $promotor->cargos) : 'N/A',
                        'promotor_tipo' => $promotor->tipo,
                        'total_eventos' => $todosEventos->count(),
                        'total_manuais' => $eventosManuais->count(),
                        'total_automaticos' => $eventosAutomaticos->count(),
                        'eventos' => $todosEventos
                    ];
                } catch (\Exception $e) {
                    \Log::error('Erro ao processar promotor substituto: ' . $e->getMessage(), [
                        'promotor_id' => $promotor->id ?? 'desconhecido'
                    ]);
                    return (object) [
                        'promotor_id' => $promotor->id,
                        'promotor_nome' => $promotor->nome,
                        'promotor_cargos' => 'N/A',
                        'promotor_tipo' => $promotor->tipo,
                        'total_eventos' => 0,
                        'total_manuais' => 0,
                        'total_automaticos' => 0,
                        'eventos' => collect()
                    ];
                }
            });

            $this->promotoresSubstitutos = $designacoes;
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar promotores substitutos: ' . $e->getMessage());
            $this->promotoresSubstitutos = collect();
        }
    }

    /**
     * Obtém o ID do município pelo nome
     */
    public function getMunicipioId($nomeMunicipio)
    {
        $municipio = Municipio::where('nome', $nomeMunicipio)->first();
        return $municipio ? $municipio->id : 1; // Retorna 1 como fallback
    }

    public function render()
    {
        return view('livewire.espelho.espelho');
    }
}