<div class="w-full max-w-7xl mx-auto pl-4 sm:pl-8 md:pl-16">
    <div class="space-y-8">
        <div class="text-center">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 tracking-tight">
                Prévia do Espelho do Período
            </h1>
            @if ($this->periodos->count() > 0)
                <div class="flex flex-wrap justify-center gap-4 mb-2">
                    @foreach ($this->periodos as $periodo)
                        <div class="inline-flex items-center gap-2  border border-gray-300 rounded-lg px-5 py-3 shadow">
                            <span class="inline-block text-base font-semibold text-gray-800 px-3 py-1 rounded">
                                {{ $periodo->periodo_inicio->format('d/m/Y') }}
                            </span>
                            <span class="text-gray-700 font-bold">até</span>
                            <span class="inline-block text-base font-semibold text-gray-800 px-3 py-1 rounded">
                                {{ $periodo->periodo_fim->format('d/m/Y') }}
                            </span>
                            <span
                                class="ml-3 inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-white border border-gray-300 text-gray-700 uppercase tracking-wider shadow-sm">
                                Período
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    @foreach ($this->periodos as $periodo)
                        <p class="text-xs text-gray-700 font-medium">
                            Duração: {{ $periodo->periodo_inicio->diffInDays($periodo->periodo_fim) + 1 }} dias
                        </p>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($this->plantoes->count() > 0)
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Plantões de Urgência</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach ($this->plantoes as $plantao)
                        <div class="px-6 py-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                        {{ $plantao->nome ?? 'Plantão de Urgência' }}
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            Urgência
                                        </span>
                                    </h3>
                                    <div class="mt-1 space-y-1">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Município:</span> {{ $plantao->municipio->nome }}
                                        </p>
                                        @if ($plantao->periodo)
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Período:</span>
                                                {{ $plantao->periodo->periodo_inicio->format('d/m/Y') }} -
                                                {{ $plantao->periodo->periodo_fim->format('d/m/Y') }}
                                            </p>
                                        @endif
                                        @if ($plantao->observacoes)
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Observações:</span>
                                                {{ $plantao->observacoes }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($plantao->promotores->count() > 0)
                                <div>
                                    <h4 class="text-xs font-medium text-gray-700 mb-2">Promotores Designados:</h4>
                                    <div
                                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2">
                                        @foreach ($plantao->promotores as $promotor)
                                            <div class="flex items-center gap-2 text-xs bg-gray-50 px-3 py-2 rounded">
                                                <div
                                                    class="h-5 w-5 rounded-full bg-red-100 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-red-600">
                                                        {{ substr($promotor->nome, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="flex-1">
                                                    <span class="font-medium text-gray-900">{{ $promotor->nome }}</span>
                                                    <span
                                                        class="text-xs text-gray-500 ml-1">({{ ucfirst($promotor->pivot->tipo_designacao) }})</span>
                                                    @if ($promotor->pivot->data_inicio_designacao && $promotor->pivot->data_fim_designacao)
                                                        <div class="text-xs text-gray-600">
                                                            {{ \Carbon\Carbon::parse($promotor->pivot->data_inicio_designacao)->format('d/m/Y') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($promotor->pivot->data_fim_designacao)->format('d/m/Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($this->promotorias->count() > 0)
            <div class="">
                @foreach ($this->promotoriasPorMunicipio as $nomeMunicipio => $promotoriasMunicipio)
                    <div class="border border-gray-200 rounded-lg overflow-hidden mb-8 shadow-sm bg-white">
                        <!-- Cabeçalho do Município -->
                        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">
                                Município: {{ $nomeMunicipio }}
                            </h3>
                        </div>

                        @php
                            $promotoriasPorGrupo = $promotoriasMunicipio->groupBy(function ($p) {
                                return optional($p->grupoPromotoria)->nome ?? 'Sem grupo';
                            });
                        @endphp

                        @foreach ($promotoriasPorGrupo as $nomeGrupo => $promotoriasDoGrupo)
                            <!-- Cabeçalho do Grupo de Promotorias -->
                            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                <h4 class="text-base font-semibold text-gray-700 uppercase tracking-wide">
                                    Grupo de Promotorias: {{ $nomeGrupo }}
                                </h4>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/6">
                                                Promotorias
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/4">
                                                Promotores
                                            </th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Período de Designação
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach ($promotoriasDoGrupo as $indexPromotoria => $promotoria)
                                            @php
                                                $eventos = $promotoria->eventos;
                                                $eventosCount = $eventos->count();
                                            @endphp

                                            @if ($eventosCount > 0)
                                                @foreach ($eventos as $indexEvento => $evento)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                        @if ($indexEvento === 0)
                                                            <td rowspan="{{ $eventosCount }}"
                                                                class="px-6 py-4 align-top border-r border-gray-200">
                                                                <div
                                                                    class="text-sm font-medium text-gray-900 break-words">
                                                                    {{ $promotoria->nome }}
                                                                </div>
                                                            </td>
                                                        @endif

                                                        @if ($indexEvento === 0)
                                                            <td rowspan="{{ $eventosCount }}"
                                                                class="px-6 py-4 align-top border-r border-gray-200">
                                                                @if ($promotoria->promotorTitular)
                                                                    <div class="text-sm">
                                                                        <span
                                                                            class="font-medium text-red-600">{{ $promotoria->promotorTitular->nome }}</span>
                                                                        <span
                                                                            class="text-xs text-gray-500 block">Promotor
                                                                            Titular</span>
                                                                    </div>
                                                                @else
                                                                    <div class="text-sm text-gray-500">
                                                                        @if ($promotoria->vacancia_data_inicio)
                                                                            Vacante desde
                                                                            {{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}
                                                                        @else
                                                                            Promotoria Vacante
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        @endif

                                                        <td class="px-6 py-4">
                                                            @php
                                                                $designacoes = method_exists($evento, 'designacoes')
                                                                    ? $evento->designacoes
                                                                    : collect();
                                                                $promotores = $evento->promotores ?? collect();
                                                                $allDesignacoes =
                                                                    $designacoes->count() > 0
                                                                        ? $designacoes
                                                                        : $promotores;
                                                            @endphp

                                                            @if ($allDesignacoes->count() > 0)
                                                                <div class="flex flex-wrap gap-3">
                                                                    @foreach ($allDesignacoes as $designacao)
                                                                        @php
                                                                            $promotor =
                                                                                $designacao->promotor ?? $designacao;
                                                                            $dataInicio =
                                                                                $designacao->data_inicio_designacao ??
                                                                                ($designacao->pivot
                                                                                    ->data_inicio_designacao ??
                                                                                    null);
                                                                            $dataFim =
                                                                                $designacao->data_fim_designacao ??
                                                                                ($designacao->pivot
                                                                                    ->data_fim_designacao ??
                                                                                    null);
                                                                            $tipo =
                                                                                $designacao->tipo ??
                                                                                ($designacao->pivot->tipo ?? 'titular');
                                                                        @endphp
                                                                        <div
                                                                            class="inline-flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-md">
                                                                            <div>
                                                                                <span
                                                                                    class="text-sm font-medium text-red-600">{{ $promotor->nome }}</span>
                                                                                <div
                                                                                    class="flex items-center gap-2 text-xs text-gray-500">
                                                                                    <span
                                                                                        class="uppercase">{{ $tipo }}</span>
                                                                                    @if ($dataInicio || $dataFim)
                                                                                        <span>•</span>
                                                                                        @if ($dataInicio)
                                                                                            {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }}
                                                                                        @endif
                                                                                        @if ($dataFim)
                                                                                            até
                                                                                            {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div class="text-sm text-gray-500">
                                                                    Nenhum promotor designado
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                    <td class="px-6 py-4 border-r border-gray-200">
                                                        <div class="text-sm font-medium text-gray-900 break-words">
                                                            {{ $promotoria->nome }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 border-r border-gray-200">
                                                        @if ($promotoria->promotorTitular)
                                                            <div class="text-sm">
                                                                <span
                                                                    class="font-medium text-red-600">{{ $promotoria->promotorTitular->nome }}</span>
                                                                <span class="text-xs text-gray-500 block">Promotor
                                                                    Titular</span>
                                                            </div>
                                                        @else
                                                            <div class="text-sm text-gray-500">
                                                                @if ($promotoria->vacancia_data_inicio)
                                                                    Vacante desde
                                                                    {{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}
                                                                @else
                                                                    Promotoria Vacante
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm text-gray-500">
                                                            Nenhum período cadastrado
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        @if ($this->promotorias->count() === 0 && $this->plantoes->count() === 0 && $this->periodos->count() === 0)
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma informação disponível</h3>
                <p class="mt-1 text-sm text-gray-500">Configure os dados no modo Gestão Espelho primeiro.</p>
            </div>
        @endif
    </div>
</div>
