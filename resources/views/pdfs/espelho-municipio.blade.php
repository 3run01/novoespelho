<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }} - {{ $municipio->nome ?? 'Município' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
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
        
        .header .municipio {
            font-size: 18px;
            color: #059669;
            font-weight: bold;
            margin: 0 0 15px 0;
        }
        
        .header .info {
            font-size: 12px;
            color: #9ca3af;
        }
        
        .grupo-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
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
        
        .promotoria-municipio {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .promotoria-periodo {
            font-size: 10px;
            color: #059669;
            background-color: #d1fae5;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
        }
        
        .promotor-info {
            background-color: #f9fafb;
            padding: 6px;
            border-radius: 3px;
            margin-bottom: 5px;
        }
        
        .promotor-nome {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }
        
        .promotor-cargo {
            font-size: 10px;
            color: #6b7280;
            background-color: #e5e7eb;
            padding: 1px 4px;
            border-radius: 2px;
            display: inline-block;
        }
        
        .evento-info {
            margin-bottom: 8px;
        }
        
        .evento-titulo {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }
        
        .evento-periodo {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 3px;
        }
        
        .evento-tipo {
            font-size: 10px;
            color: #059669;
            background-color: #d1fae5;
            padding: 1px 4px;
            border-radius: 2px;
            display: inline-block;
        }
        
        .designacao-info {
            background-color: #f0f9ff;
            padding: 4px 6px;
            border-radius: 3px;
            margin-bottom: 3px;
            border-left: 3px solid #3b82f6;
        }
        
        .designacao-promotor {
            font-weight: bold;
            color: #1e40af;
        }
        
        .designacao-tipo {
            font-size: 9px;
            color: #6b7280;
            background-color: #e5e7eb;
            padding: 1px 3px;
            border-radius: 2px;
            display: inline-block;
        }
        
        .designacao-periodo {
            font-size: 9px;
            color: #6b7280;
            margin-top: 2px;
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
        
        .resumo {
            background-color: #f0f9ff;
            border: 1px solid #3b82f6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .resumo h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 14px;
        }
        
        .resumo-stats {
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        
        .stat-item {
            flex: 1;
        }
        
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }
        
        .stat-label {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <div class="subtitulo">{{ $subtitulo }}</div>
        <div class="municipio">{{ $municipio->nome ?? 'Município' }}</div>
        <div class="info">
            Gerado em: {{ $dataGeracao->format('d/m/Y \à\s H:i') }}
        </div>
    </div>

    <!-- Resumo do Município -->
    @php
        $totalPromotorias = 0;
        $totalEventos = 0;
        $totalPromotores = 0;
        
        foreach ($promotoriasPorMunicipio as $nomeMunicipio => $promotoriasPorGrupo) {
            foreach ($promotoriasPorGrupo as $nomeGrupo => $promotoriasDoGrupo) {
                $totalPromotorias += $promotoriasDoGrupo->count();
                foreach ($promotoriasDoGrupo as $promotoria) {
                    $totalEventos += $promotoria->eventos->count();
                    if ($promotoria->promotorTitular) {
                        $totalPromotores++;
                    }
                }
            }
        }
    @endphp
    
    <div class="resumo">
        <h3>Resumo do Município</h3>
        <div class="resumo-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $totalPromotorias }}</div>
                <div class="stat-label">Promotorias</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalEventos }}</div>
                <div class="stat-label">Eventos</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalPromotores }}</div>
                <div class="stat-label">Promotores</div>
            </div>
        </div>
    </div>

    <!-- Conteúdo do Espelho -->
    @forelse ($promotoriasPorMunicipio as $nomeMunicipio => $promotoriasPorGrupo)
        @foreach ($promotoriasPorGrupo as $nomeGrupo => $promotoriasDoGrupo)
            <div class="grupo-section">
                <!-- Cabeçalho do Grupo -->
                <div class="grupo-header">
                    <h3>{{ strtoupper($nomeGrupo) }}</h3>
                </div>

                <!-- Tabela de Promotorias -->
                <table class="promotoria-table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Promotorias</th>
                            <th style="width: 30%;">Promotores</th>
                            <th style="width: 35%;">Eventos e Períodos</th>
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
                                        <!-- Coluna PROMOTORIAS -->
                                        @if ($indexEvento === 0)
                                            <td rowspan="{{ $eventosCount }}">
                                                <div class="promotoria-nome">{{ $promotoria->nome }}</div>
                                                <div class="promotoria-municipio">
                                                    Município: {{ optional(optional($promotoria->grupoPromotoria)->municipio)->nome ?? '—' }}
                                                </div>
                                                @if ($periodo)
                                                    <div class="promotoria-periodo">
                                                        Período vigente: {{ $periodo->periodo_inicio->format('d/m/Y') }} - {{ $periodo->periodo_fim->format('d/m/Y') }}
                                                    </div>
                                                @endif
                                                <div style="margin-top: 5px;">
                                                    <strong>{{ $eventosCount }} {{ $eventosCount == 1 ? 'evento' : 'eventos' }}</strong>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Coluna PROMOTORES -->
                                        @if ($indexEvento === 0)
                                            <td rowspan="{{ $eventosCount }}">
                                                @if ($promotoria->promotorTitular)
                                                    <div class="promotor-info">
                                                        <div class="promotor-nome">{{ $promotoria->promotorTitular->nome }}</div>
                                                        <div class="promotor-cargo">Titular</div>
                                                        @php
                                                            $cargosLista = is_array($promotoria->promotorTitular->cargos ?? null) ? $promotoria->promotorTitular->cargos : [];
                                                        @endphp
                                                        @if (!empty($cargosLista))
                                                            <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">
                                                                Cargo(s): {{ implode(', ', $cargosLista) }}
                                                            </div>
                                                        @endif
                                                        @if ($promotoria->promotorTitular->zona_eleitoral)
                                                            <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">
                                                                Zona Eleitoral: {{ $promotoria->promotorTitular->zona_eleitoral }}
                                                            </div>
                                                        @endif
                                                        @if ($promotoria->titularidade_promotor_data_inicio)
                                                            <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">
                                                                Início: {{ \Carbon\Carbon::parse($promotoria->titularidade_promotor_data_inicio)->format('d/m/Y') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="sem-eventos">
                                                        <div>Nenhum promotor titular designado</div>
                                                        @if ($promotoria->vacancia_data_inicio)
                                                            <div style="color: #dc2626; margin-top: 2px;">
                                                                Vacância desde {{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        @endif

                                        <!-- Coluna EVENTOS -->
                                        <td>
                                            <div class="evento-info">
                                                <div class="evento-titulo">
                                                    {{ $evento->titulo ?: ucfirst($evento->tipo ?: 'Evento') }}
                                                </div>
                                                
                                                @if ($evento->periodo_inicio || $evento->periodo_fim)
                                                    <div class="evento-periodo">
                                                        <strong>Período:</strong>
                                                        @if ($evento->periodo_inicio)
                                                            {{ $evento->periodo_inicio->format('d/m/Y') }}
                                                        @endif
                                                        @if ($evento->periodo_inicio && $evento->periodo_fim)
                                                            -
                                                        @endif
                                                        @if ($evento->periodo_fim)
                                                            {{ $evento->periodo_fim->format('d/m/Y') }}
                                                        @endif
                                                    </div>
                                                @endif

                                                @if ($evento->tipo)
                                                    <div class="evento-tipo">{{ ucfirst($evento->tipo) }}</div>
                                                @endif

                                                <!-- Designações de Promotores -->
                                                @if ($evento->designacoes->count() > 0)
                                                    <div style="margin-top: 8px;">
                                                        <div style="font-weight: bold; margin-bottom: 4px; color: #374151;">
                                                            Membros Designados:
                                                        </div>
                                                        @foreach ($evento->designacoes as $designacao)
                                                            <div class="designacao-info">
                                                                <div class="designacao-promotor">
                                                                    {{ $designacao->promotor->nome ?? '—' }}
                                                                </div>
                                                                <div class="designacao-tipo">
                                                                    @if(($designacao->tipo ?? 'titular') === 'substituto')
                                                                        Substituindo
                                                                    @else
                                                                        {{ ucfirst($designacao->tipo ?? 'titular') }}
                                                                    @endif
                                                                </div>
                                                                @if ($designacao->data_inicio_designacao || $designacao->data_fim_designacao)
                                                                    <div class="designacao-periodo">
                                                                        @if ($designacao->data_inicio_designacao)
                                                                            {{ optional($designacao->data_inicio_designacao)->format('d/m/Y') }}
                                                                        @endif
                                                                        @if ($designacao->data_inicio_designacao && $designacao->data_fim_designacao)
                                                                            -
                                                                        @endif
                                                                        @if ($designacao->data_fim_designacao)
                                                                            {{ optional($designacao->data_fim_designacao)->format('d/m/Y') }}
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div style="color: #9ca3af; font-style: italic; margin-top: 5px;">
                                                        Nenhum promotor designado para este evento
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <!-- Linha para promotoria sem eventos -->
                                <tr>
                                    <td>
                                        <div class="promotoria-nome">{{ $promotoria->nome }}</div>
                                        <div class="promotoria-municipio">
                                            Município: {{ optional(optional($promotoria->grupoPromotoria)->municipio)->nome ?? '—' }}
                                        </div>
                                        <div style="margin-top: 5px;">
                                            <strong>0 eventos</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($promotoria->promotorTitular)
                                            <div class="promotor-info">
                                                <div class="promotor-nome">{{ $promotoria->promotorTitular->nome }}</div>
                                                <div class="promotor-cargo">Titular</div>
                                            </div>
                                        @else
                                            <div class="sem-eventos">
                                                <div>Nenhum promotor titular designado</div>
                                                @if ($promotoria->vacancia_data_inicio)
                                                    <div style="color: #dc2626; margin-top: 2px;">
                                                        Vacância desde {{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="sem-eventos">
                                        Nenhum evento cadastrado
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
            <h3>Nenhum grupo de promotorias encontrado para este município</h3>
            <p>Verifique os filtros aplicados</p>
        </div>
    @endforelse

    <!-- Rodapé -->
    <div class="footer">
        <p>Documento gerado automaticamente pelo sistema de gestão de espelhos</p>
        <p>Município: {{ $municipio->nome ?? 'N/A' }}</p>
    </div>
</body>
</html>
