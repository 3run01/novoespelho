<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use App\Models\Espelho;
use App\Models\Evento;
use App\Models\PlantaoAtendimento;
use App\Models\Promotoria;
use App\Models\GrupoPromotoria;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EspelhoPublicadoController
{
    /**
     * Retorna o espelho publicado com todos os seus itens em formato JSON
     */
    public function index(): JsonResponse
    {
        try {
            $periodoPublicado = Periodo::where('status', 'publicado')
                ->orderBy('periodo_inicio', 'desc')
                ->first();

            if (!$periodoPublicado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum período publicado encontrado',
                    'data' => null
                ], 404);
            }

            // Buscar todos os espelhos do período publicado
            $espelhos = Espelho::where('periodo_id', $periodoPublicado->id)
                ->with([
                    'eventos.designacoes.promotor',
                    'eventos.promotoria',
                    'plantaoAtendimento.promotores',
                    'plantaoAtendimento.municipio',
                    'grupoPromotorias.promotorias.promotorTitular',
                    'municipio'
                ])
                ->get();

            // Buscar todos os eventos do período publicado
            $eventos = Evento::where('periodo_id', $periodoPublicado->id)
                ->with([
                    'designacoes.promotor',
                    'promotoria',
                    'espelhos'
                ])
                ->get();

            // Buscar todos os plantões de urgência do período publicado
            $plantoesUrgencia = PlantaoAtendimento::where('periodo_id', $periodoPublicado->id)
                ->with([
                    'promotores',
                    'municipio'
                ])
                ->get();

            // Buscar grupos de promotorias com suas promotorias
            $gruposPromotorias = GrupoPromotoria::with([
                'promotorias.promotorTitular',
                'municipio'
            ])
            ->get();

            $promotoriasSemGrupo = Promotoria::whereNull('grupo_promotoria_id')
                ->with(['promotorTitular'])
                ->get();

            $espelhoData = [
                'periodo' => [
                    'id' => $periodoPublicado->id,
                    'inicio' => $periodoPublicado->periodo_inicio->format('Y-m-d'),
                    'fim' => $periodoPublicado->periodo_fim->format('Y-m-d'),
                    'status' => $periodoPublicado->status,
                    'status_texto' => $periodoPublicado->status_texto,
                    'created_at' => $periodoPublicado->created_at->toISOString(),
                    'updated_at' => $periodoPublicado->updated_at->toISOString(),
                ],
                'espelhos' => $espelhos->map(function ($espelho) {
                    return [
                        'id' => $espelho->id,
                        'nome' => $espelho->nome,
                        'status' => $espelho->status,
                        'observacoes' => $espelho->observacoes,
                        'municipio' => $espelho->municipio ? [
                            'id' => $espelho->municipio->id,
                            'nome' => $espelho->municipio->nome,
                            'entrancia' => $espelho->municipio->entrancia,
                            'nucleo' => $espelho->municipio->nucleo,
                        ] : null,
                        'grupo_promotorias' => $espelho->grupoPromotorias ? [
                            'id' => $espelho->grupoPromotorias->id,
                            'nome' => $espelho->grupoPromotorias->nome,
                            'municipio' => $espelho->grupoPromotorias->municipio ? [
                                'id' => $espelho->grupoPromotorias->municipio->id,
                                'nome' => $espelho->grupoPromotorias->municipio->nome,
                            ] : null,
                        ] : null,
                        'plantao_atendimento' => $espelho->plantaoAtendimento ? [
                            'id' => $espelho->plantaoAtendimento->id,
                            'nome' => $espelho->plantaoAtendimento->nome,
                            'nucleo' => $espelho->plantaoAtendimento->nucleo,
                            'observacoes' => $espelho->plantaoAtendimento->observacoes,
                            'municipio' => $espelho->plantaoAtendimento->municipio ? [
                                'id' => $espelho->plantaoAtendimento->municipio->id,
                                'nome' => $espelho->plantaoAtendimento->municipio->nome,
                                'entrancia' => $espelho->plantaoAtendimento->municipio->entrancia,
                                'nucleo' => $espelho->plantaoAtendimento->municipio->nucleo,
                            ] : null,
                            'promotores' => $espelho->plantaoAtendimento->promotores->map(function ($promotor) {
                                return [
                                    'id' => $promotor->id,
                                    'nome' => $promotor->nome,
                                    'tipo_designacao' => $promotor->pivot->tipo_designacao,
                                    'data_inicio_designacao' => $promotor->pivot->data_inicio_designacao,
                                    'data_fim_designacao' => $promotor->pivot->data_fim_designacao,
                                    'ordem' => $promotor->pivot->ordem,
                                    'status' => $promotor->pivot->status,
                                ];
                            }),
                        ] : null,
                        'eventos' => $espelho->eventos->map(function ($evento) {
                            return [
                                'id' => $evento->id,
                                'titulo' => $evento->titulo,
                                'tipo' => $evento->tipo,
                                'periodo_inicio' => $evento->periodo_inicio ? $evento->periodo_inicio->format('Y-m-d') : null,
                                'periodo_fim' => $evento->periodo_fim ? $evento->periodo_fim->format('Y-m-d') : null,
                                'is_urgente' => $evento->is_urgente,
                                'evento_do_substituto' => $evento->evento_do_substituto,
                                'promotoria' => $evento->promotoria ? [
                                    'id' => $evento->promotoria->id,
                                    'nome' => $evento->promotoria->nome,
                                    'promotor_titular' => $evento->promotoria->promotorTitular ? [
                                        'id' => $evento->promotoria->promotorTitular->id,
                                        'nome' => $evento->promotoria->promotorTitular->nome,
                                    ] : null,
                                ] : null,
                                'designacoes' => $evento->designacoes->map(function ($designacao) {
                                    return [
                                        'id' => $designacao->id,
                                        'promotor' => [
                                            'id' => $designacao->promotor->id,
                                            'nome' => $designacao->promotor->nome,
                                        ],
                                        'tipo' => $designacao->tipo,
                                        'data_inicio_designacao' => $designacao->data_inicio_designacao ? $designacao->data_inicio_designacao->format('Y-m-d') : null,
                                        'data_fim_designacao' => $designacao->data_fim_designacao ? $designacao->data_fim_designacao->format('Y-m-d') : null,
                                        'ordem' => $designacao->ordem,
                                        'observacoes' => $designacao->observacoes,
                                    ];
                                }),
                                'ordem_espelho' => $evento->pivot->ordem ?? null,
                                'observacoes_evento' => $evento->pivot->observacoes_evento ?? null,
                            ];
                        }),
                        'created_at' => $espelho->created_at->toISOString(),
                        'updated_at' => $espelho->updated_at->toISOString(),
                    ];
                }),
                'grupos_promotorias' => $gruposPromotorias->map(function ($grupo) {
                    return [
                        'id' => $grupo->id,
                        'nome' => $grupo->nome,
                        'municipio' => $grupo->municipio ? [
                            'id' => $grupo->municipio->id,
                            'nome' => $grupo->municipio->nome,
                        ] : null,
                        'promotorias' => $grupo->promotorias->map(function ($promotoria) {
                            return [
                                'id' => $promotoria->id,
                                'nome' => $promotoria->nome,
                                'promotor_titular' => $promotoria->promotorTitular ? [
                                    'id' => $promotoria->promotorTitular->id,
                                    'nome' => $promotoria->promotorTitular->nome,
                                ] : null,
                            ];
                        }),
                        'created_at' => $grupo->created_at->toISOString(),
                        'updated_at' => $grupo->updated_at->toISOString(),
                    ];
                }),
                'promotorias_avulsas' => $promotoriasSemGrupo->map(function ($promotoria) {
                    return [
                        'id' => $promotoria->id,
                        'nome' => $promotoria->nome,
                        'promotor_titular' => $promotoria->promotorTitular ? [
                            'id' => $promotoria->promotorTitular->id,
                            'nome' => $promotoria->promotorTitular->nome,
                        ] : null,
                        'created_at' => $promotoria->created_at->toISOString(),
                        'updated_at' => $promotoria->updated_at->toISOString(),
                    ];
                }),
                'plantoes_urgencia' => $plantoesUrgencia->map(function ($plantao) {
                    return [
                        'id' => $plantao->id,
                        'nome' => $plantao->nome,
                        'nucleo' => $plantao->nucleo,
                        'observacoes' => $plantao->observacoes,
                        'municipio' => $plantao->municipio ? [
                            'id' => $plantao->municipio->id,
                            'nome' => $plantao->municipio->nome,
                            'entrancia' => $plantao->municipio->entrancia,
                            'nucleo' => $plantao->municipio->nucleo,
                        ] : null,
                        'promotores' => $plantao->promotores->map(function ($promotor) {
                            return [
                                'id' => $promotor->id,
                                'nome' => $promotor->nome,
                                'tipo_designacao' => $promotor->pivot->tipo_designacao,
                                'data_inicio_designacao' => $promotor->pivot->data_inicio_designacao,
                                'data_fim_designacao' => $promotor->pivot->data_fim_designacao,
                                'ordem' => $promotor->pivot->ordem,
                                'status' => $promotor->pivot->status,
                            ];
                        }),
                        'created_at' => $plantao->created_at->toISOString(),
                        'updated_at' => $plantao->updated_at->toISOString(),
                    ];
                }),
                'eventos_gerais' => $eventos->map(function ($evento) {
                    return [
                        'id' => $evento->id,
                        'titulo' => $evento->titulo,
                        'tipo' => $evento->tipo,
                        'periodo_inicio' => $evento->periodo_inicio ? $evento->periodo_inicio->format('Y-m-d') : null,
                        'periodo_fim' => $evento->periodo_fim ? $evento->periodo_fim->format('Y-m-d') : null,
                        'is_urgente' => $evento->is_urgente,
                        'evento_do_substituto' => $evento->evento_do_substituto,
                        'promotoria' => $evento->promotoria ? [
                            'id' => $evento->promotoria->id,
                            'nome' => $evento->promotoria->nome,
                            'promotor_titular' => $evento->promotoria->promotorTitular ? [
                                'id' => $evento->promotoria->promotorTitular->id,
                                'nome' => $evento->promotoria->promotorTitular->nome,
                            ] : null,
                        ] : null,
                        'designacoes' => $evento->designacoes->map(function ($designacao) {
                            return [
                                'id' => $designacao->id,
                                'promotor' => [
                                    'id' => $designacao->promotor->id,
                                    'nome' => $designacao->promotor->nome,
                                ],
                                'tipo' => $designacao->tipo,
                                'data_inicio_designacao' => $designacao->data_inicio_designacao ? $designacao->data_inicio_designacao->format('Y-m-d') : null,
                                'data_fim_designacao' => $designacao->data_fim_designacao ? $designacao->data_fim_designacao->format('Y-m-d') : null,
                                'ordem' => $designacao->ordem,
                                'observacoes' => $designacao->observacoes,
                            ];
                        }),
                        'espelhos_associados' => $evento->espelhos->pluck('id')->toArray(),
                        'created_at' => $evento->created_at->toISOString(),
                        'updated_at' => $evento->updated_at->toISOString(),
                    ];
                }),
                'metadata' => [
                    'gerado_em' => now()->toISOString(),
                    'versao_api' => '1.0',
                    'periodo_publicado_em' => $periodoPublicado->updated_at->toISOString(),
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'Espelho publicado recuperado com sucesso',
                'data' => $espelhoData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao recuperar espelho publicado: ' . $e->getMessage(),
                'data' => null,
                'error' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }
}