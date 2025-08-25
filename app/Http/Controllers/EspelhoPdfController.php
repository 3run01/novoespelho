<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Periodo;
use App\Models\Promotoria;
use App\Models\GrupoPromotoria;
use App\Models\Municipio;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class EspelhoPdfController
{
    
    public function gerarEspelhoCompleto(Request $request)
    {
        try {
            $periodo = Periodo::where('status', 'publicado')
                ->orderBy('periodo_inicio', 'desc')
                ->first();

            if (!$periodo) {
                return response()->json([
                    'error' => true,
                    'message' => 'Nenhum período publicado encontrado'
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

            $filename = "espelho_periodo_{$periodo->periodo_inicio->format('d-m-Y')}_a_{$periodo->periodo_fim->format('d-m-Y')}.pdf";

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
            
            $periodo = Periodo::where('status', 'publicado')
                ->orderBy('periodo_inicio', 'desc')
                ->first();

            if (!$periodo) {
                return response()->json([
                    'error' => true,
                    'message' => 'Nenhum período publicado encontrado'
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

            $filename = "espelho_{$municipio->nome}_{$periodo->periodo_inicio->format('d-m-Y')}_a_{$periodo->periodo_fim->format('d-m-Y')}.pdf";

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
            $periodo = Periodo::where('status', 'publicado')
                ->orderBy('periodo_inicio', 'desc')
                ->first();

            if (!$periodo) {
                return response()->json([
                    'error' => true,
                    'message' => 'Nenhum período publicado encontrado'
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

            return $pdf->stream('espelho_periodo.pdf');

        } catch (\Exception $e) {
            \Log::error('Erro ao visualizar PDF do espelho: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Erro ao visualizar PDF: ' . $e->getMessage()
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

        return [
            'periodo' => $periodo,
            'promotoriasPorMunicipio' => $promotoriasPorMunicipio,
            'dataGeracao' => Carbon::now(),
            'titulo' => 'Espelho do Período',
            'subtitulo' => "Período: {$periodo->periodo_inicio->format('d/m/Y')} a {$periodo->periodo_fim->format('d/m/Y')}"
        ];
    }
}
