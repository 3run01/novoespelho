<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Periodo;
use App\Models\Promotoria;
use App\Models\GrupoPromotoria;
use App\Models\Municipio;
use App\Models\Promotor;
use App\Models\EventoPromotor;
use App\Models\Espelho;
use App\Models\PlantaoAtendimento;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class EspelhoPdfController
{
    
    public function gerarEspelhoCompleto(Request $request)
    {
        try {
            // Se um período específico foi solicitado via query parameter
            if ($request->has('periodo_id')) {
                $periodo = Periodo::findOrFail($request->get('periodo_id'));
            } else {
                // Fallback para o período mais recente (qualquer status)
                $periodo = Periodo::orderBy('periodo_inicio', 'desc')->first();
            }

            if (!$periodo) {
                return response()->json([
                    'error' => true,
                    'message' => 'Nenhum período encontrado'
                ], 404);
            }

            $dados = $this->carregarDadosEspelho($periodo);

            $pdf = Pdf::loadView('pdfs.espelho-completo', $dados);
            
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultMediaType' => 'print'
            ]);

            $filename = "espelho_periodo_{$periodo->periodo_inicio->format('d-m-Y')}_a_{$periodo->periodo_fim->format('d-m-Y')}_{$periodo->status}.pdf";

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF do espelho: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Erro ao gerar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function gerarEspelhoPorMunicipio(Request $request, $municipioId)
    {
        try {
            $municipio = Municipio::findOrFail($municipioId);
            
            // Se um período específico foi solicitado via query parameter
            if ($request->has('periodo_id')) {
                $periodo = Periodo::findOrFail($request->get('periodo_id'));
            } else {
                // Fallback para o período mais recente (qualquer status)
                $periodo = Periodo::orderBy('periodo_inicio', 'desc')->first();
            }

            if (!$periodo) {
                return response()->json([
                    'error' => true,
                    'message' => 'Nenhum período encontrado'
                ], 404);
            }

            $dados = $this->carregarDadosEspelho($periodo, $municipioId);

            $pdf = Pdf::loadView('pdfs.espelho-municipio', $dados);
            
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultMediaType' => 'print'
            ]);

            $filename = "espelho_{$municipio->nome}_{$periodo->periodo_inicio->format('d-m-Y')}_a_{$periodo->periodo_fim->format('d-m-Y')}_{$periodo->status}.pdf";

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF do espelho por município: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Erro ao gerar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function visualizarEspelho(Request $request)
    {
        try {
            // Se um período específico foi solicitado via query parameter
            if ($request->has('periodo_id')) {
                $periodo = Periodo::findOrFail($request->get('periodo_id'));
            } else {
                // Fallback para o período mais recente (qualquer status)
                $periodo = Periodo::orderBy('periodo_inicio', 'desc')->first();
            }

            if (!$periodo) {
                return response()->json([
                    'error' => true,
                    'message' => 'Nenhum período encontrado'
                ], 404);
            }

            $dados = $this->carregarDadosEspelho($periodo);

            $pdf = Pdf::loadView('pdfs.espelho-completo', $dados);
            
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultMediaType' => 'print'
            ]);

            $filename = "espelho_periodo_{$periodo->periodo_inicio->format('d-m-Y')}_a_{$periodo->periodo_fim->format('d-m-Y')}_{$periodo->status}.pdf";

            return $pdf->stream($filename);

        } catch (\Exception $e) {
            \Log::error('Erro ao visualizar PDF do espelho: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Erro ao visualizar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gera PDF de um espelho individual
     */
    public function gerarEspelhoIndividual(Request $request, $espelhoId)
    {
        try {
            $espelho = Espelho::with(['periodo', 'municipio', 'grupoPromotorias', 'plantaoAtendimento', 'eventos'])
                ->findOrFail($espelhoId);

            $dados = $this->carregarDadosEspelho($espelho->periodo, $espelho->municipio_id);

            $pdf = Pdf::loadView('pdfs.espelho-completo', $dados);
            
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultMediaType' => 'print'
            ]);

            $filename = "espelho_{$espelho->id}_{$espelho->periodo->periodo_inicio->format('d-m-Y')}_a_{$espelho->periodo->periodo_fim->format('d-m-Y')}_{$espelho->periodo->status}.pdf";

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF do espelho individual: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Erro ao gerar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

   
    private function carregarDadosEspelho(Periodo $periodo, $municipioId = null)
    {
        $query = Promotoria::with([
            'grupoPromotoria.municipio',
            'promotorTitular',
            'eventos' => function ($query) use ($periodo) {
                $query->where('periodo_id', $periodo->id)
                      ->with(['designacoes.promotor'])
                      ->orderBy('periodo_inicio');
            }
        ]);

        if ($municipioId) {
            $query->whereHas('grupoPromotoria.municipio', function ($q) use ($municipioId) {
                $q->where('id', $municipioId);
            });
        }

        $promotorias = $query->get();

        $promotoriasPorMunicipio = $promotorias->groupBy(function ($promotoria) {
            return optional(optional($promotoria->grupoPromotoria)->municipio)->nome ?? 'Sem município';
        });

        $promotoriasPorMunicipio = $promotoriasPorMunicipio->sortBy(function ($promotorias, $nomeMunicipio) {
            if ($nomeMunicipio === 'Macapá') return 0;
            return 1;
        });

        foreach ($promotoriasPorMunicipio as $nomeMunicipio => $promotoriasMunicipio) {
            $promotoriasPorGrupo = $promotoriasMunicipio->groupBy(function ($promotoria) {
                return optional($promotoria->grupoPromotoria)->nome ?? 'Sem grupo';
            });

            foreach ($promotoriasPorGrupo as $nomeGrupo => $promotoriasDoGrupo) {
                $promotoriasPorGrupo[$nomeGrupo] = $promotoriasDoGrupo->sort(function ($a, $b) {
                    $ordemMacapa = [
                        '1ª PJ Cível' => 1,
                        '2ª PJ Cível' => 2,
                        '1ª PJ da Família' => 3,
                        '2ª PJ da Família' => 4,
                        '3ª PJ da Família' => 5,
                        '4ª PJ da Família' => 6,
                        '1ª PJ Criminal' => 7,
                        '2ª PJ Criminal' => 8,
                        '3ª PJ Criminal' => 9,
                        '4ª PJ Criminal' => 10,
                        '5ª PJ Criminal' => 11,
                        '6ª PJ Criminal' => 12,
                        '7ª PJ Criminal' => 13,
                        '8ª PJ Criminal' => 14,
                        '9ª PJ Criminal' => 15,
                        '10ª PJ Criminal' => 16,
                        '1ª PJ Tribunal do Júri' => 17,
                        '2ª PJ Tribunal do Júri' => 18,
                        '1ª PJ Execução Penal' => 19,
                        '2ª PJ Execução Penal' => 20,
                        '3ª PJ Execução Penal' => 21,
                        '1ª PJ Infância e Juventude' => 22,
                        '2ª PJ Infância e Juventude' => 23,
                        '3ª PJ Infância e Juventude' => 24,
                        '4ª PJ Infância e Juventude' => 25,
                        'Defesa de Direitos Constitucionais' => 26,
                        'Defesa da Educação' => 27,
                        '1ª PJ Defesa da Saúde Pública' => 28,
                        '2ª PJ Defesa da Saúde Pública' => 29,
                        '1ª PJ Defesa da Mulher' => 30,
                        '2ª PJ Defesa da Mulher' => 31,
                        'Central de Violência Doméstica' => 32,
                        'Defesa do Consumidor' => 33,
                        '1ª PJ Meio Ambiente e Conflitos Agrários' => 34,
                        '2ª PJ Meio Ambiente e Conflitos Agrários' => 35,
                        'Urbanismo e Mobilidade Urbana' => 36,
                        '1ª PJ Defesa do Patrimônio Público e Fundações' => 37,
                        '2ª PJ Defesa do Patrimônio Público e Fundações' => 38,
                        '3ª PJ Defesa do Patrimônio Público e Fundações' => 39
                    ];
                    
                    if (isset($ordemMacapa[$a->nome]) && isset($ordemMacapa[$b->nome])) {
                        return $ordemMacapa[$a->nome] - $ordemMacapa[$b->nome];
                    }
                    
                    if (isset($ordemMacapa[$a->nome])) return -1;
                    if (isset($ordemMacapa[$b->nome])) return 1;
                    
                    return strcasecmp($a->nome, $b->nome);
                });
            }

            $promotoriasPorMunicipio[$nomeMunicipio] = $promotoriasPorGrupo;
        }

        // Carregar dados dos promotores substitutos
        $promotoresSubstitutos = $this->carregarDadosPromotoresSubstitutos($periodo);

        // Carregar plantões de urgência
        $plantoesPorMunicipio = $this->carregarDadosPlantoesUrgencia($periodo, $municipioId);

        return [
            'periodo' => $periodo,
            'promotoriasPorMunicipio' => $promotoriasPorMunicipio,
            'promotoresSubstitutos' => $promotoresSubstitutos,
            'plantoesPorMunicipio' => $plantoesPorMunicipio,
            'dataGeracao' => Carbon::now(),
            'titulo' => 'Espelho do Período',
            'subtitulo' => "Período: {$periodo->periodo_inicio->format('d/m/Y')} a {$periodo->periodo_fim->format('d/m/Y')}"
        ];
    }

    /**
     * Carrega os dados dos promotores substitutos para o período
     */
    private function carregarDadosPromotoresSubstitutos(Periodo $periodo)
    {
        $promotoresSubstitutos = Promotor::where('tipo', 'substituto')
            ->orderBy('nome', 'asc')
            ->get();

        $designacoes = $promotoresSubstitutos->map(function ($promotor) use ($periodo) {
            // Designações manuais (criadas no componente PromotorEventos - evento_do_substituto = true)
            $designacoesManuais = EventoPromotor::where('promotor_id', $promotor->id)
                ->whereHas('evento', function ($query) use ($periodo) {
                    $query->where('periodo_id', $periodo->id)
                          ->where('evento_do_substituto', true);
                })
                ->with(['evento.promotoria', 'evento'])
                ->get();

            // Designações automáticas do espelho (evento_do_substituto = null ou false)
            $designacoesAutomaticas = EventoPromotor::where('promotor_id', $promotor->id)
                ->whereHas('evento', function ($query) use ($periodo) {
                    $query->where('periodo_id', $periodo->id)
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
     * Carrega os dados dos plantões de urgência para o período
     */
    private function carregarDadosPlantoesUrgencia(Periodo $periodo, $municipioId = null)
    {
        $query = PlantaoAtendimento::where('periodo_id', $periodo->id)
            ->with([
                'municipio',
                'periodo',
                'promotores' => function ($query) {
                    $query->withPivot(['tipo_designacao', 'data_inicio_designacao', 'data_fim_designacao']);
                }
            ]);

        if ($municipioId) {
            $query->where('municipio_id', $municipioId);
        }

        $plantoes = $query->get();

        // Agrupar plantões por município
        $plantoesPorMunicipio = [];
        foreach ($plantoes as $plantao) {
            $nomeMunicipio = 'Sem município';
            
            if ($plantao->municipio) {
                $nomeMunicipio = $plantao->municipio->nome;
            } elseif ($plantao->nucleo) {
                $nomeMunicipio = 'Entrância Inicial - ' . $plantao->nucleo . 'º Núcleo';
            }
            
            if (!isset($plantoesPorMunicipio[$nomeMunicipio])) {
                $plantoesPorMunicipio[$nomeMunicipio] = collect();
            }
            
            $plantoesPorMunicipio[$nomeMunicipio]->push($plantao);
        }

        // Ordenar municípios: Macapá primeiro, depois Santana, depois os demais
        uksort($plantoesPorMunicipio, function($a, $b) {
            // Macapá sempre primeiro
            if ($a === 'Macapá') {
                return -1;
            }
            if ($b === 'Macapá') {
                return 1;
            }
            
            // Santana em segundo
            if ($a === 'Santana') {
                return -1;
            }
            if ($b === 'Santana') {
                return 1;
            }
            
            // Entrância Inicial por último
            if (strpos($a, 'Entrância Inicial') === 0 && strpos($b, 'Entrância Inicial') !== 0) {
                return 1;
            }
            if (strpos($b, 'Entrância Inicial') === 0 && strpos($a, 'Entrância Inicial') !== 0) {
                return -1;
            }
            
            // Demais municípios em ordem alfabética
            return strcasecmp($a, $b);
        });

        return $plantoesPorMunicipio;
    }
}
