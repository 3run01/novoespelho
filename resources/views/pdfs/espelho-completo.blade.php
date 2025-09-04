<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .cabecalho-institucional {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #1f2937;
            padding-bottom: 15px;
        }

        .logo-container {
            display: inline-block;
            vertical-align: top;
            margin-right: 20px;
            width: 80px;
            height: 80px;
        }

        .logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .instituicao-info {
            display: inline-block;
            vertical-align: top;
            text-align: left;
        }

        .instituicao-nome {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #1f2937;
            text-transform: uppercase;
        }

        .promotorias-nome {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #374151;
            text-transform: uppercase;
        }

        .estado-nome {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            color: #6b7280;
            text-transform: uppercase;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #1f2937;
        }

        .header .subtitulo {
            font-size: 16px;
            color: #6b7280;
            margin: 0 0 15px 0;
        }

        .header .info {
            font-size: 12px;
            color: #9ca3af;
        }

        .municipio-section {
            margin-bottom: 30px;
        }

        .municipio-header {
            background-color: #f3f4f6;
            padding: 10px 15px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .municipio-header h2 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #374151;
            text-transform: uppercase;
        }

        .grupo-section {
            margin-bottom: 20px;
        }

        .grupo-header {
            background-color: #f9fafb;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 3px;
            margin-bottom: 12px;
        }

        .grupo-header h3 {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            color: #4b5563;
            text-transform: uppercase;
        }

        .promotoria-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .promotoria-table th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            color: #374151;
            text-transform: uppercase;
        }

        .promotoria-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        .promotoria-nome {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .promotoria-competencia {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
            font-style: italic;
        }

        .promotor-nome {
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 3px;
        }

        .promotor-cargo {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 3px;
        }

        .promotor-cargos {
            font-size: 9px;
            color: #374151;
            margin-bottom: 2px;
        }

        .evento-titulo {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 3px;
        }

        .evento-info {
            color: #374151;
            margin-bottom: 3px;
        }

        .data-info {
            color: #dc2626;
            font-weight: bold;
        }

        .vacante {
            color: #dc2626;
            font-weight: bold;
        }

        .sem-eventos {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 20px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }

        .municipio-subtitulo {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        /* Estilos para seção de promotores substitutos */
        .substitutos-section {
            margin-top: 40px;
            page-break-before: always;
        }

        .substitutos-header {
            background-color: #1f2937;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .substitutos-header h2 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .substitutos-subtitulo {
            font-size: 14px;
            margin-top: 5px;
            opacity: 0.9;
        }

        .substitutos-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .substitutos-table th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #374151;
            text-transform: uppercase;
        }

        .substitutos-table td {
            border: 1px solid #d1d5db;
            padding: 10px;
            vertical-align: top;
        }

        .substituto-nome {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }

        .substituto-cargos {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .substituto-tipo {
            font-size: 9px;
            color: #dc2626;
            font-weight: bold;
            text-transform: uppercase;
        }

        .evento-substituto-titulo {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 3px;
        }

        .evento-substituto-info {
            color: #374151;
            margin-bottom: 3px;
        }

        .evento-substituto-promotoria {
            color: #6b7280;
            font-size: 10px;
            margin-bottom: 3px;
        }

        .evento-substituto-datas {
            color: #dc2626;
            font-weight: bold;
            font-size: 10px;
        }

        .evento-manual {
            background-color: #fef3c7;
            border-left: 3px solid #f59e0b;
        }

        .evento-automatico {
            background-color: #f0f9ff;
            border-left: 3px solid #3b82f6;
        }

        .sem-designacoes {
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            padding: 20px;
        }

    </style>
</head>

<body>
    <div class="cabecalho-institucional">
        <div class="logo-container">
            <img src="{{ Storage::url('logo.png') }}" alt="Logo Ministério Público" class="logo">
        </div>
        <div class="instituicao-info">
            <div class="instituicao-nome">Procuradoria Geral de Justiça</div>
            <div class="promotorias-nome">Promotorias de Justiça</div>
            <div class="estado-nome">Estado do Amapá</div>
        </div>
    </div>

    <div class="header">
        <div class="subtitulo">{{ $subtitulo }}</div>
        <div class="info">
            Gerado em: {{ $dataGeracao->format('d/m/Y \à\s H:i') }}
        </div>
    </div>

    @forelse ($promotoriasPorMunicipio as $nomeMunicipio => $promotoriasPorGrupo)
        <!-- Plantões de Urgência do Município (se houver) -->
        @if(isset($plantoesPorMunicipio[$nomeMunicipio]) && $plantoesPorMunicipio[$nomeMunicipio]->count() > 0)
            <div class="municipio-section">
                <div class="municipio-header">
                    <h2>Plantões de Urgência - {{ $nomeMunicipio }}</h2>
                </div>

                <table class="promotoria-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Plantão</th>
                            <th style="width: 40%;">Promotores Designados</th>
                            <th style="width: 35%;">Período / Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plantoesPorMunicipio[$nomeMunicipio] as $plantao)
                            <tr>
                                <td>
                                    <div class="promotoria-nome">{{ $plantao->nome ?? 'Plantão de Urgência' }}</div>
                                </td>
                                <td>
                                    @if($plantao->promotores->count() > 0)
                                        @foreach($plantao->promotores as $promotor)
                                            <div class="evento-info" style="margin-bottom: 5px;">
                                                <div class="promotor-nome">{{ $promotor->nome }}</div>
                                                <div class="evento-info">
                                                    ({{ ucfirst($promotor->pivot->tipo_designacao) }})
                                                    @if($promotor->pivot->data_inicio_designacao && $promotor->pivot->data_fim_designacao)
                                                        - {{ \Carbon\Carbon::parse($promotor->pivot->data_inicio_designacao)->format('d/m/Y') }}
                                                        até {{ \Carbon\Carbon::parse($promotor->pivot->data_fim_designacao)->format('d/m/Y') }}
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="sem-eventos">Nenhum promotor designado</div>
                                    @endif
                                </td>
                                <td>
                                    @if($plantao->periodo)
                                        <div class="evento-info data-info">
                                            <strong>Período:</strong>
                                            {{ $plantao->periodo->periodo_inicio->format('d/m/Y') }}
                                            - {{ $plantao->periodo->periodo_fim->format('d/m/Y') }}
                                        </div>
                                    @endif
                                    @if($plantao->observacoes)
                                        <div class="evento-info">
                                            <strong>Obs:</strong> {{ $plantao->observacoes }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @foreach ($promotoriasPorGrupo as $nomeGrupo => $promotoriasDoGrupo)
            <div class="grupo-section">
                <div class="grupo-header">
                    <h3>{{ $nomeGrupo }}</h3>
                    <p class="municipio-subtitulo">Município: {{ $nomeMunicipio }}</p>
                </div>

                <table class="promotoria-table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Promotorias</th>
                            <th style="width: 30%;">Promotores</th>
                            <th style="width: 35%;">Período de Designação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($promotoriasDoGrupo as $promotoria)
                            @php
                                $eventosCount = $promotoria->eventos->count();
                            @endphp

                            @if ($eventosCount > 0)
                                @foreach ($promotoria->eventos as $indexEvento => $evento)
                                    <tr>
                                        @if ($indexEvento === 0)
                                            <td rowspan="{{ $eventosCount }}">
                                                <div class="promotoria-nome">{{ $promotoria->nome }}</div>
                                                @if ($promotoria->competencia)
                                                    <div class="promotoria-competencia">
                                                        {{ $promotoria->competencia }}</div>
                                                @endif
                                            </td>
                                        @endif

                                        @if ($indexEvento === 0)
                                            <td rowspan="{{ $eventosCount }}">
                                                @if ($promotoria->promotorTitular)
                                                    <div class="promotor-nome">
                                                        {{ $promotoria->promotorTitular->nome }}</div>
                                                    <div class="promotor-cargo">Titular</div>
                                                    @php
                                                        $cargosLista = [];
                                                        if ($promotoria->promotorTitular->cargos) {
                                                            if (is_array($promotoria->promotorTitular->cargos)) {
                                                                $cargosLista = $promotoria->promotorTitular->cargos;
                                                            } elseif (is_string($promotoria->promotorTitular->cargos)) {
                                                                $cargosLista =
                                                                    json_decode(
                                                                        $promotoria->promotorTitular->cargos,
                                                                        true,
                                                                    ) ?? [];
                                                            }
                                                        }
                                                        $cargosLista = array_filter($cargosLista, function ($cargo) {
                                                            return !empty(trim($cargo));
                                                        });
                                                    @endphp
                                                    @if (!empty($cargosLista))
                                                        <div style="margin-top: 3px;">
                                                            @foreach ($cargosLista as $cargo)
                                                                <div class="promotor-cargos">{{ $cargo }}
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="vacante">
                                                        @if ($promotoria->vacancia_data_inicio)
                                                            Vacante a partir de <span
                                                                class="data-info">{{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}</span>
                                                        @else
                                                            Promotoria Vacante
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        @endif

                                        <td>
                                            @php
                                                $eventoTitulo = $evento->titulo ?: ucfirst($evento->tipo ?: 'Evento');
                                                $isEventoGenerico = $eventoTitulo === 'Evento';
                                            @endphp

                                            @if (!$isEventoGenerico)
                                                <div class="evento-titulo">{{ $eventoTitulo }}</div>
                                            @endif

                                            @if ($evento->data)
                                                <div class="evento-info data-info">
                                                    Data:
                                                    {{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}
                                                </div>
                                            @endif

                                            @if ($evento->periodo_inicio || $evento->periodo_fim)
                                                <div class="evento-info data-info">
                                                    {{ $evento->periodo_inicio ? \Carbon\Carbon::parse($evento->periodo_inicio)->format('d/m/Y') : '' }}
                                                    @if ($evento->periodo_inicio && $evento->periodo_fim)
                                                        —
                                                    @endif
                                                    {{ $evento->periodo_fim ? \Carbon\Carbon::parse($evento->periodo_fim)->format('d/m/Y') : '' }}
                                                </div>
                                            @endif

                                            @if ($evento->designacoes->count() > 0)
                                                @foreach ($evento->designacoes as $designacao)
                                                    <div class="evento-info">
                                                        {{ $designacao->promotor->nome ?? '—' }}
                                                        @if ($designacao->tipo && $designacao->tipo !== 'titular')
                                                            ({{ $designacao->tipo === 'substituto' ? 'Respondendo' : ucfirst($designacao->tipo) }})
                                                        @endif
                                                        @if ($designacao->data_inicio_designacao || $designacao->data_fim_designacao)
                                                            <span class="data-info">(
                                                                @if ($designacao->data_inicio_designacao)
                                                                    {{ optional($designacao->data_inicio_designacao)->format('d/m/Y') }}
                                                                @endif
                                                                @if ($designacao->data_inicio_designacao && $designacao->data_fim_designacao)
                                                                    —
                                                                @endif
                                                                @if ($designacao->data_fim_designacao)
                                                                    {{ optional($designacao->data_fim_designacao)->format('d/m/Y') }}
                                                                @endif
                                                                )
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>
                                        <div class="promotoria-nome">{{ $promotoria->nome }}</div>
                                        @if ($promotoria->competencia)
                                            <div class="promotoria-competencia">{{ $promotoria->competencia }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($promotoria->promotorTitular)
                                            <div class="promotor-nome">{{ $promotoria->promotorTitular->nome }}
                                            </div>
                                            <div class="promotor-cargo">Titular</div>
                                            @php
                                                $cargosLista = [];
                                                if ($promotoria->promotorTitular->cargos) {
                                                    if (is_array($promotoria->promotorTitular->cargos)) {
                                                        $cargosLista = $promotoria->promotorTitular->cargos;
                                                    } elseif (is_string($promotoria->promotorTitular->cargos)) {
                                                        $cargosLista =
                                                            json_decode($promotoria->promotorTitular->cargos, true) ??
                                                            [];
                                                    }
                                                }
                                                $cargosLista = array_filter($cargosLista, function ($cargo) {
                                                    return !empty(trim($cargo));
                                                });
                                            @endphp
                                            @if (!empty($cargosLista))
                                                <div style="margin-top: 3px;">
                                                    @foreach ($cargosLista as $cargo)
                                                        <div class="promotor-cargos">{{ $cargo }}</div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @else
                                            <div class="vacante">
                                                @if ($promotoria->vacancia_data_inicio)
                                                    Vacante a partir de <span
                                                        class="data-info">{{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}</span>
                                                @else
                                                    Promotoria Vacante
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="sem-eventos">
                                        Nenhum período cadastrado
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @empty
        <div class="sem-eventos">
            <h3>Nenhum grupo de promotorias encontrado</h3>
            <p>Verifique os filtros aplicados</p>
        </div>
    @endforelse

    <!-- Seção de Promotores Substitutos -->
    @if(isset($promotoresSubstitutos) && $promotoresSubstitutos->isNotEmpty())
        <div class="substitutos-section">
            <div class="substitutos-header">
                <h2>Promotores Substitutos</h2>
                <div class="substitutos-subtitulo">
                    Designações para o período: {{ $periodo->periodo_inicio->format('d/m/Y') }} a {{ $periodo->periodo_fim->format('d/m/Y') }}
                </div>
            </div>

            <table class="substitutos-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Promotor</th>
                        <th style="width: 75%;">Designações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promotoresSubstitutos as $promotor)
                        <tr>
                            <td>
                                <div class="substituto-nome">{{ $promotor['promotor_nome'] }}</div>
                                @if($promotor['promotor_cargos'] !== 'N/A')
                                    <div class="substituto-cargos">{{ $promotor['promotor_cargos'] }}</div>
                                @endif
                                <div class="substituto-tipo">{{ ucfirst($promotor['promotor_tipo']) }}</div>
                            </td>
                            <td>
                                @if($promotor['total_eventos'] > 0)
                                    @foreach($promotor['eventos'] as $evento)
                                        <div class="evento-substituto-info {{ $evento['is_manual'] ? 'evento-manual' : 'evento-automatico' }}">
                                            @if($evento['evento_titulo'])
                                                <div class="evento-substituto-titulo">{{ $evento['evento_titulo'] }}</div>
                                            @endif
                                            
                                            <div class="evento-substituto-promotoria">
                                                <strong>Promotoria:</strong> {{ $evento['promotoria_nome'] }}
                                            </div>
                                            
                                            @if($evento['tipo_designacao'] && $evento['tipo_designacao'] !== 'substituto')
                                                <div class="evento-substituto-info">
                                                    <strong>Tipo:</strong> {{ ucfirst($evento['tipo_designacao']) }}
                                                </div>
                                            @endif
                                            
                                            @if($evento['data_inicio'] || $evento['data_fim'])
                                                <div class="evento-substituto-datas">
                                                    <strong>Período:</strong>
                                                    @if($evento['data_inicio'])
                                                        {{ $evento['data_inicio'] }}
                                                    @endif
                                                    @if($evento['data_inicio'] && $evento['data_fim'])
                                                        —
                                                    @endif
                                                    @if($evento['data_fim'])
                                                        {{ $evento['data_fim'] }}
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            @if($evento['observacoes'])
                                                <div class="evento-substituto-info">
                                                    <strong>Observações:</strong> {{ $evento['observacoes'] }}
                                                </div>
                                            @endif
                                            
                                            @if($evento['is_urgente'])
                                                <div class="evento-substituto-info" style="color: #dc2626; font-weight: bold;">
                                                    ⚠️ URGENTE
                                                </div>
                                            @endif
                                            
                                            <div class="evento-substituto-info" style="font-size: 9px; color: #6b7280; margin-top: 3px;">
                                                {{ $evento['is_manual'] ? 'Designação Manual' : 'Designação Automática' }}
                                            </div>
                                        </div>
                                        
                                        @if(!$loop->last)
                                            <hr style="margin: 8px 0; border: none; border-top: 1px solid #e5e7eb;">
                                        @endif
                                    @endforeach
                                @else
                                    <div class="sem-designacoes">
                                        Nenhuma designação para este período
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <p>Documento gerado automaticamente pelo sistema de gestão de espelhos</p>
        <p>Página 1</p>
    </div>
</body>

</html>
