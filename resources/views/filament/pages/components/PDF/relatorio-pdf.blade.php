<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório do Período</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        h1, h2 {
            color: #333;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .periodo-info {
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <h1>Relatório do Período</h1>
    
    <div class="periodo-info">
        <strong>Período:</strong> 
        {{ Carbon\Carbon::parse($periodo->periodo_inicio)->format('d/m/Y') }} - 
        {{ Carbon\Carbon::parse($periodo->periodo_fim)->format('d/m/Y') }}
    </div>

    <div class="section">
        <h2>Plantões de Atendimento</h2>
        <table>
            <thead>
                <tr>
                    <th>Promotor</th>
                    <th>Período</th>
                    <th>Dias</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plantoes as $plantao)
                    <tr>
                        <td>{{ $plantao->promotor_nome }}</td>
                        <td>
                            {{ Carbon\Carbon::parse($plantao->periodo_inicio)->format('d/m/Y') }} - 
                            {{ Carbon\Carbon::parse($plantao->periodo_fim)->format('d/m/Y') }}
                        </td>
                        <td>{{ $plantao->total_dias }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Eventos</h2>
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Promotoria</th>
                    <th>Titular</th>
                    <th>Designado</th>
                    <th>Período</th>
                    <th>Dias</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventos as $evento)
                    <tr>
                        <td>
                            @if($evento->is_urgente)
                                Urgência
                            @else
                                {{ $evento->tipo }}
                            @endif
                        </td>
                        <td>{{ $evento->promotoria->nome }}</td>
                        <td>{{ $evento->promotorTitular->nome }}</td>
                        <td>{{ $evento->promotorDesignado->nome }}</td>
                        <td>
                            {{ Carbon\Carbon::parse($evento->periodo_inicio)->format('d/m/Y') }} - 
                            {{ Carbon\Carbon::parse($evento->periodo_fim)->format('d/m/Y') }}
                        </td>
                        <td>{{ $evento->total_dias }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

   
</body>
</html>