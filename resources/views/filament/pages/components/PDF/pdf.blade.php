<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Espelho de Eventos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #000;
            margin: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 14px;
            margin: 5px 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 16px;
            margin: 10px 0;
            text-transform: uppercase;
        }
        .periodo-destaque {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
            font-size: 13px;
        }
        .section {
            margin-bottom: 25px;
            border: 1px solid #000;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 8px;
            font-weight: bold;
            font-size: 13px;
            text-align: center;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .mensagem-vazio {
            padding: 15px;
            text-align: center;
            font-style: italic;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo.png') }}" class="logo" alt="Logo">
        <h1>Procuradoria Geral de Justiça</h1>
        <h2>Promotorias de Justiça do Estado do Amapá</h2>
        <div class="periodo-destaque">
            Espelho de {{ \Carbon\Carbon::parse($periodo->periodo_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($periodo->periodo_fim)->format('d/m/Y') }}
        </div>
    </div>

    <!-- Seção de Plantões de Urgência -->
    <div class="section">
        <div class="section-title">Plantão para Atendimentos em Caráter de Urgência</div>
        @if($plantoes->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Membro Designado</th>
                        <th>Período Início</th>
                        <th>Período Fim</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plantoes as $plantao)
                    <tr>
                        <td>{{ $plantao->promotor }}</td>
                        <td>{{ \Carbon\Carbon::parse($plantao->periodo_inicio)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($plantao->periodo_fim)->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="mensagem-vazio">Nenhum plantão de urgência encontrado para este período.</div>
        @endif
    </div>

    <!-- Seção de Eventos -->
    <div class="section">
        <div class="section-title">Eventos</div>
        <table>
            <thead>
                <tr>
                    <th>Promotoria</th>
                    <th>Membro Titular</th>
                    <th>Membro Designado</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Período</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventos as $evento)
                <tr>
                    <td>{{ optional($evento->promotoria)->nome }}</td>
                    <td>{{ optional($evento->promotorTitular)->nome }}</td>
                    <td>{{ optional($evento->promotorDesignado)->nome }}</td>
                    <td>{{ $evento->titulo }}</td>
                    <td>{{ $evento->tipo }}</td>
                    <td>{{ \Carbon\Carbon::parse($evento->periodo_inicio)->format('d/m/Y') }} - 
                        {{ \Carbon\Carbon::parse($evento->periodo_fim)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>