<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento PDF</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }

        .content {
            margin: 20px 0;
        }

        .section {
            margin-bottom: 25px;
        }

        .section h2 {
            color: #34495e;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            font-size: 18px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
            min-width: 150px;
        }

        .info-value {
            flex: 1;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            padding-top: 20px;
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
        <h1>{{ $title ?? 'Documento PDF' }}</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="content">
        @if (isset($data))
            @foreach ($data as $section => $content)
                <div class="section">
                    <h2>{{ ucfirst($section) }}</h2>

                    @if (is_array($content))
                        @if (isset($content[0]) && is_array($content[0]))
                            <!-- Tabela -->
                            <table class="table">
                                <thead>
                                    <tr>
                                        @foreach (array_keys($content[0]) as $header)
                                            <th>{{ ucfirst($header) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($content as $row)
                                        <tr>
                                            @foreach ($row as $value)
                                                <td>{{ $value }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <!-- Lista simples -->
                            <ul>
                                @foreach ($content as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @else
                        <p>{{ $content }}</p>
                    @endif
                </div>
            @endforeach
        @else
            <div class="section">
                <h2>Conteúdo</h2>
                <p>Este é um exemplo de template de PDF. Passe dados através do controller para personalizar o conteúdo.
                </p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>Sistema de Gestão - {{ date('Y') }}</p>
    </div>
</body>

</html>
