<div class="w-full pl-[46px] flex justify-end">
    <div class="space-y-6 sm:space-y-8">
        <div class="text-center">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 mb-4 tracking-tight">
                Prévia do Espelho do Período
            </h1>
            @if ($this->periodos->count() > 0)
                <div class="flex flex-wrap justify-center gap-2 sm:gap-4 mb-2">
                    @foreach ($this->periodos as $periodo)
                        <div
                            class="inline-flex items-center gap-1 sm:gap-2 border border-gray-300 rounded-lg px-3 sm:px-5 py-2 sm:py-3 shadow">
                            <span
                                class="inline-block text-sm sm:text-base font-semibold text-gray-800 px-2 sm:px-3 py-1 rounded">
                                {{ $periodo->periodo_inicio->format('d/m/Y') }}
                            </span>
                            <span class="text-gray-700 font-bold text-sm sm:text-base">até</span>
                            <span
                                class="inline-block text-sm sm:text-base font-semibold text-gray-800 px-2 sm:px-3 py-1 rounded">
                                {{ $periodo->periodo_fim->format('d/m/Y') }}
                            </span>
                            <span
                                class="ml-2 sm:ml-3 inline-flex items-center px-2 sm:px-3 py-1 rounded text-xs font-semibold bg-white border border-gray-300 text-gray-700 uppercase tracking-wider shadow-sm">
                                Período
                            </span>
                            @if ($periodo->status === 'publicado')
                                <span
                                    class="ml-1 sm:ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Publicado
                                </span>
                            @elseif ($periodo->status === 'em_processo_publicacao')
                                <span
                                    class="ml-1 sm:ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                    Em Processo
                                </span>
                            @elseif ($periodo->status === 'arquivado')
                                <span
                                    class="ml-1 sm:ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    Arquivado
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="flex flex-wrap justify-center gap-2 sm:gap-4">
                    @foreach ($this->periodos as $periodo)
                        <p class="text-xs text-gray-700 font-medium">
                            Duração: {{ $periodo->periodo_inicio->diffInDays($periodo->periodo_fim) + 1 }} dias
                        </p>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($this->promotoriasPorMunicipio->count() > 0 || $this->plantoesPorMunicipio->count() > 0)
            <div class="space-y-6 sm:space-y-8">
                @php
                    // Combinar todos os municípios (promotorias e plantões)
                    $todosMunicipios = collect($this->promotoriasPorMunicipio->keys())
                        ->merge(collect($this->plantoesPorMunicipio->keys()))
                        ->unique()
                        ->sort();
                @endphp

                @foreach ($todosMunicipios as $nomeMunicipio)
                    <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm bg-white">

                        <!-- Plantões de Urgência do Município (se houver) -->
                        @if ($this->plantoesPorMunicipio->has($nomeMunicipio) && $this->plantoesPorMunicipio[$nomeMunicipio]->count() > 0)
                            <div class="bg-orange-50 border-b border-orange-200 px-4 sm:px-6 py-3 sm:py-4">
                                <div class="flex items-center gap-2 sm:gap-3 mb-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <h4 class="text-sm sm:text-base font-bold text-orange-800 uppercase tracking-wide">
                                        Plantões de Urgência
                                    </h4>
                                </div>

                                <div class="grid grid-cols-1 xl:grid-cols-2 gap-3 sm:gap-4">
                                    @foreach ($this->plantoesPorMunicipio[$nomeMunicipio] as $plantao)
                                        <div class="bg-white border border-orange-200 rounded-lg p-3 sm:p-4">
                                            <div class="flex items-start justify-between mb-2">
                                                <div class="flex-1">
                                                    <h5
                                                        class="text-sm font-semibold text-gray-900 flex items-center gap-2 mb-2">
                                                        {{ $plantao->nome ?? 'Plantão de Urgência' }}
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                                            Urgência
                                                        </span>
                                                    </h5>

                                                    <div class="text-xs text-gray-600 space-y-1 mb-3">
                                                        @if ($plantao->periodo)
                                                            <p>
                                                                <span class="font-medium">Período:</span>
                                                                {{ $plantao->periodo->periodo_inicio->format('d/m/Y') }}
                                                                - {{ $plantao->periodo->periodo_fim->format('d/m/Y') }}
                                                            </p>
                                                        @endif
                                                        @if ($plantao->observacoes)
                                                            <p>
                                                                <span class="font-medium">Observações:</span>
                                                                {{ $plantao->observacoes }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($plantao->promotores->count() > 0)
                                                <div>
                                                    <h6 class="text-xs font-medium text-gray-700 mb-2">Promotores
                                                        Designados:</h6>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach ($plantao->promotores as $promotor)
                                                            <div
                                                                class="inline-flex items-center gap-2 text-xs bg-orange-50 border border-orange-200 px-3 py-1 rounded-full">
                                                                <div
                                                                    class="h-4 w-4 rounded-full bg-orange-100 flex items-center justify-center">
                                                                    <span class="text-xs font-medium text-orange-600">
                                                                        {{ substr($promotor->nome, 0, 1) }}
                                                                    </span>
                                                                </div>
                                                                <div class="flex flex-col">
                                                                    <span
                                                                        class="font-medium text-gray-900">{{ $promotor->nome }}</span>
                                                                    <div
                                                                        class="flex items-center gap-1 text-xs text-gray-500">
                                                                        <span>({{ ucfirst($promotor->pivot->tipo_designacao) }})</span>
                                                                        @if ($promotor->pivot->data_inicio_designacao && $promotor->pivot->data_fim_designacao)
                                                                            <span>•</span>
                                                                            <span>{{ \Carbon\Carbon::parse($promotor->pivot->data_inicio_designacao)->format('d/m/Y') }}
                                                                                -
                                                                                {{ \Carbon\Carbon::parse($promotor->pivot->data_fim_designacao)->format('d/m/Y') }}</span>
                                                                        @endif
                                                                    </div>
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

                        <!-- Cabeçalho do Município -->
                        <div class="bg-gray-100 px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                            <h3 class="text-base sm:text-lg font-bold text-gray-800 uppercase tracking-wide">
                                Município: {{ $nomeMunicipio }}
                            </h3>
                        </div>

                        <!-- Promotorias do Município (se houver) -->
                        @if (isset($this->promotoriasPorMunicipio[$nomeMunicipio]) &&
                                $this->promotoriasPorMunicipio[$nomeMunicipio]->count() > 0)
                            @php
                                $promotoriasMunicipio = $this->promotoriasPorMunicipio[$nomeMunicipio];
                                $promotoriasPorGrupo = $promotoriasMunicipio->groupBy(function ($p) {
                                    return optional($p->grupoPromotoria)->nome ?? 'Sem grupo';
                                });
                            @endphp

                            @foreach ($promotoriasPorGrupo as $nomeGrupo => $promotoriasDoGrupo)
                                <!-- Cabeçalho do Grupo de Promotorias -->
                                <div class="bg-gray-50 px-4 sm:px-6 py-2 sm:py-3 border-b border-gray-200">
                                    <h4
                                        class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wide">
                                        Grupo de Promotorias: {{ $nomeGrupo }}
                                    </h4>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 table-responsive">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/6">
                                                    Promotorias
                                                </th>
                                                <th
                                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/4">
                                                    Promotores
                                                </th>
                                                <th
                                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
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
                                                                    class="px-4 sm:px-6 py-4 align-top border-r border-gray-200">
                                                                    <div
                                                                        class="text-xs sm:text-sm font-medium text-gray-900 break-words">
                                                                        {{ $promotoria->nome }}
                                                                    </div>
                                                                </td>
                                                            @endif

                                                            @if ($indexEvento === 0)
                                                                <td rowspan="{{ $eventosCount }}"
                                                                    class="px-4 sm:px-6 py-4 align-top border-r border-gray-200">
                                                                    @if ($promotoria->promotorTitular)
                                                                        @php
                                                                            $promotorTitular =
                                                                                $promotoria->promotorTitular;
                                                                            $cargosLista = [];

                                                                            if (
                                                                                $promotorTitular &&
                                                                                $promotorTitular->cargos
                                                                            ) {
                                                                                if (
                                                                                    is_array($promotorTitular->cargos)
                                                                                ) {
                                                                                    $cargosLista =
                                                                                        $promotorTitular->cargos;
                                                                                } elseif (
                                                                                    is_string($promotorTitular->cargos)
                                                                                ) {
                                                                                    $cargosLista =
                                                                                        json_decode(
                                                                                            $promotorTitular->cargos,
                                                                                            true,
                                                                                        ) ?? [];
                                                                                }
                                                                            }

                                                                            $cargosLista = array_filter(
                                                                                $cargosLista,
                                                                                function ($cargo) {
                                                                                    return !empty(trim($cargo));
                                                                                },
                                                                            );
                                                                        @endphp
                                                                        <div class="text-xs sm:text-sm">
                                                                            <div class="mb-1">
                                                                                <span
                                                                                    class="font-medium text-red-600">{{ $promotorTitular->nome }}</span>
                                                                                <span
                                                                                    class="text-xs text-gray-500">Titular</span>
                                                                            </div>
                                                                            @if (!empty($cargosLista))
                                                                                <div class="space-y-1">
                                                                                    @foreach ($cargosLista as $cargo)
                                                                                        <div class="text-gray-900">
                                                                                            {{ $cargo }}</div>
                                                                                    @endforeach
                                                                                </div>
                                                                            @endif
                                                                            @if ($promotorTitular && $promotorTitular->zona_eleitoral)
                                                                                <div class="text-gray-700">Zona
                                                                                    Eleitoral:
                                                                                    {{ $promotorTitular->numero_da_zona_eleitoral ?? 'Sim' }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @else
                                                                        <div class="text-xs sm:text-sm text-gray-500">
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

                                                            <td class="px-4 sm:px-6 py-4">
                                                                <h5
                                                                    class="text-sm sm:text-base font-bold text-blue-700 mb-1">
                                                                    {{ $evento->titulo ?: ucfirst($evento->tipo ?: 'Evento') }}
                                                                </h5>
                                                                @php
                                                                    $promotores = $evento->promotores ?? collect();
                                                                @endphp
                                                                @if ($promotores->count() > 0)
                                                                    <div class="flex flex-wrap items-center gap-2">
                                                                        @foreach ($promotores as $promotor)
                                                                            @php
                                                                                $dataInicio =
                                                                                    $promotor->pivot
                                                                                        ->data_inicio_designacao ??
                                                                                    null;
                                                                                $dataFim =
                                                                                    $promotor->pivot
                                                                                        ->data_fim_designacao ?? null;
                                                                            @endphp
                                                                            <span
                                                                                class="text-[11px] sm:text-xs text-gray-800">
                                                                                <span
                                                                                    class="font-medium">{{ $promotor->nome }}</span>
                                                                                @if ($promotor->pivot->tipo)
                                                                                    @php $t = $promotor->pivot->tipo; @endphp
                                                                                    <span class="text-gray-500">
                                                                                        ({{ $t === 'substituto' ? 'Substituindo' : ucfirst($t) }})
                                                                                    </span>
                                                                                @endif
                                                                                @if ($dataInicio || $dataFim)
                                                                                    <span class="text-gray-500"> (
                                                                                        {{ $dataInicio ? \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') : '' }}
                                                                                        @if ($dataInicio && $dataFim)
                                                                                            —
                                                                                        @endif
                                                                                        {{ $dataFim ? \Carbon\Carbon::parse($dataFim)->format('d/m/Y') : '' }}
                                                                                        )
                                                                                    </span>
                                                                                @endif
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <div class="text-[11px] sm:text-xs text-gray-500">
                                                                        Nenhum promotor designado</div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                        <td class="px-4 sm:px-6 py-4 border-r border-gray-200">
                                                            <div
                                                                class="text-xs sm:text-sm font-medium text-gray-900 break-words">
                                                                {{ $promotoria->nome }}
                                                            </div>
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-4 border-r border-gray-200">
                                                            @if ($promotoria->promotorTitular)
                                                                @php
                                                                    $promotorTitular = $promotoria->promotorTitular;
                                                                    $cargosLista = [];

                                                                    if ($promotorTitular && $promotorTitular->cargos) {
                                                                        if (is_array($promotorTitular->cargos)) {
                                                                            $cargosLista = $promotorTitular->cargos;
                                                                        } elseif (is_string($promotorTitular->cargos)) {
                                                                            $cargosLista =
                                                                                json_decode(
                                                                                    $promotorTitular->cargos,
                                                                                    true,
                                                                                ) ?? [];
                                                                        }
                                                                    }

                                                                    $cargosLista = array_filter($cargosLista, function (
                                                                        $cargo,
                                                                    ) {
                                                                        return !empty(trim($cargo));
                                                                    });
                                                                @endphp
                                                                <div class="text-xs sm:text-sm">
                                                                    <div class="mb-1">
                                                                        <span
                                                                            class="font-medium text-red-600">{{ $promotorTitular->nome }}</span>
                                                                        <span
                                                                            class="text-xs text-gray-500">Titular</span>
                                                                    </div>
                                                                    @if (!empty($cargosLista))
                                                                        <div class="space-y-1">
                                                                            @foreach ($cargosLista as $cargo)
                                                                                <div class="text-gray-900">
                                                                                    {{ $cargo }}</div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                    @if ($promotorTitular && $promotorTitular->zona_eleitoral)
                                                                        <div class="text-gray-700">Zona Eleitoral:
                                                                            {{ $promotorTitular->numero_da_zona_eleitoral ?? 'Sim' }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="text-xs sm:text-sm text-gray-500">
                                                                    @if ($promotoria->vacancia_data_inicio)
                                                                        Vacante desde
                                                                        {{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}
                                                                    @else
                                                                        Promotoria Vacante
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-4">
                                                            <div class="text-xs sm:text-sm text-gray-500">
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
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if (
            $this->promotoriasPorMunicipio->count() === 0 &&
                $this->plantoesPorMunicipio->count() === 0 &&
                $this->periodos->count() === 0)
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
