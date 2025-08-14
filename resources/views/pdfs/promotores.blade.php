<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Relatório de Promotores' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 20px;
        }

        .header .info {
            margin-top: 8px;
            font-size: 10px;
            color: #666;
        }

        .content {
            margin: 15px 0;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            color: #34495e;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 10px;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }

        .badge-titular {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .badge-substituto {
            background-color: #fff3e0;
            color: #f57c00;
        }

        .badge-sim {
            background-color: #e8f5e8;
            color: #2e7d32;
        }

        .badge-nao {
            background-color: #ffebee;
            color: #c62828;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            padding-top: 15px;
        }

        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #2c3e50;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-number {
            font-size: 16px;
            font-weight: bold;
            color: #3498db;
        }

        .summary-label {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title ?? 'Relatório de Promotores' }}</h1>
        <div class="info">
            <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
            @if (isset($filtros) && !empty($filtros))
                <p>Filtros aplicados: {{ implode(', ', $filtros) }}</p>
            @endif
        </div>
    </div>

    <div class="content">
        @if (isset($data) && isset($data['promotores']))
            @php
                $promotores = $data['promotores'];
                $total = count($promotores);
                $titulares = collect($promotores)->where('tipo', 'Titular')->count();
                $substitutos = collect($promotores)->where('tipo', 'Substituto')->count();
                $comZona = collect($promotores)->where('zona_eleitoral', 'Sim')->count();
            @endphp

            <!-- Resumo -->
            <div class="summary">
                <h3>Resumo</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-number">{{ $total }}</div>
                        <div class="summary-label">Total de Promotores</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $titulares }}</div>
                        <div class="summary-label">Titulares</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-number">{{ $substitutos }}</div>
                        <div class="summary-label">Substitutos</div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Promotores -->
            <div class="section">
                <h2>Lista de Promotores</h2>

                @if (count($promotores) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 25%">Nome</th>
                                <th style="width: 15%">Cargo</th>
                                <th style="width: 10%">Tipo</th>
                                <th style="width: 8%">Zona Eleitoral</th>
                                <th style="width: 8%">Nº Zona</th>
                                <th style="width: 10%">Período Início</th>
                                <th style="width: 10%">Período Fim</th>
                                <th style="width: 14%">Observações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($promotores as $promotor)
                                <tr>
                                    <td><strong>{{ $promotor['nome'] }}</strong></td>
                                    <td>{{ $promotor['cargo'] }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $promotor['tipo'] == 'Titular' ? 'badge-titular' : 'badge-substituto' }}">
                                            {{ $promotor['tipo'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $promotor['zona_eleitoral'] == 'Sim' ? 'badge-sim' : 'badge-nao' }}">
                                            {{ $promotor['zona_eleitoral'] }}
                                        </span>
                                    </td>
                                    <td>{{ $promotor['numero_zona'] }}</td>
                                    <td>{{ $promotor['periodo_inicio'] }}</td>
                                    <td>{{ $promotor['periodo_fim'] }}</td>
                                    <td style="font-size: 9px;">{{ Str::limit($promotor['observacoes'], 50) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Nenhum promotor encontrado.</p>
                @endif
            </div>
        @else
            <div class="section">
                <h2>Dados não encontrados</h2>
                <p>Não foi possível carregar os dados dos promotores.</p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>Sistema de Gestão de Promotores - {{ date('Y') }}</p>
        <p>Página 1 de 1</p>
    </div>
</body>

</html>
