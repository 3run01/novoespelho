<div class="w-full pl-[46px] flex justify-end">
    <div class="space-y-6 sm:space-y-8">
        <div class="text-center">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 mb-4 tracking-tight">
                Prévia do Espelho do Período
            </h1>

            @if ($this->periodos->count() > 0)
                @php
                    $periodo = $this->periodos->first();
                @endphp
                <div class="flex flex-wrap justify-center gap-2 sm:gap-4 mb-2">
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
                </div>
                <div class="flex flex-wrap justify-center gap-2 sm:gap-4">
                    <p class="text-xs text-gray-700 font-medium">
                        Duração: {{ $periodo->periodo_inicio->diffInDays($periodo->periodo_fim) + 1 }} dias
                    </p>
                </div>
            @endif
        </div>

        @if ($this->promotoriasPorMunicipio->count() > 0 || $this->plantoes->count() > 0)
            <div class="space-y-6 sm:space-y-8">
                @php
                    $plantoesPorMunicipio = [];
                    foreach ($this->plantoes as $plantao) {
                        $nomeMunicipio = 'Sem município';

                        if ($plantao->municipio) {
                            $nomeMunicipio = $plantao->municipio->nome;
                        } elseif ($plantao->nucleo) {
                            $nomeMunicipio = 'Entrância Inicial - ' . $plantao->nucleo . 'º Núcleo';
                        }

                        if (!isset($plantoesPorMunicipio[$nomeMunicipio])) {
                            $plantoesPorMunicipio[$nomeMunicipio] = collect();
                        }

                        $plantoesPorMunicipio[$nomeMunicipio]->push($plantao);
                    }
                @endphp

                @php
                    $todosMunicipios = collect(array_keys($plantoesPorMunicipio ?? []))
                        ->merge($this->promotoriasPorMunicipio->keys())
                        ->unique()
                        ->sort(function ($a, $b) {
                            if ($a === 'Macapá') {
                                return -1;
                            }
                            if ($b === 'Macapá') {
                                return 1;
                            }

                            return strcasecmp($a, $b);
                        });
                @endphp

                @foreach ($todosMunicipios as $nomeMunicipio)
                    @if (isset($plantoesPorMunicipio[$nomeMunicipio]) && $plantoesPorMunicipio[$nomeMunicipio]->count() > 0)
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm bg-white ">
                            <div class=" bg-gray-50 px-6 sm:px-8 py-4 sm:py-5 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4
                                            class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wide">
                                            Plantões de Urgência
                                        </h4>
                                        <p class="text-base text-gray-700 mt-2 font-semibold">{{ $nomeMunicipio }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 table-responsive">
                                    <thead class="">
                                        <tr>
                                            <th
                                                class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/4">
                                                Plantão
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/3">
                                                Promotores Designados
                                            </th>
                                            <th
                                                class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                Período / Observações
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class=" ">
                                        @foreach ($plantoesPorMunicipio[$nomeMunicipio] as $plantao)
                                            <tr class=" transition-colors duration-200">
                                                <td class="px-4 sm:px-6 py-5 border-r align-top">
                                                    <div class="text-xs sm:text-sm font-medium text-gray-900">
                                                        {{ $plantao->nome ?? 'Plantão de Urgência' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 sm:px-6 py-5 border-r border-gray-200 align-top">
                                                    @if ($plantao->promotores->count() > 0)
                                                        <div class="space-y-2">
                                                            @foreach ($plantao->promotores as $promotor)
                                                                <div class="text-xs sm:text-sm">
                                                                    <div class="font-medium text-gray-900">
                                                                        {{ $promotor->nome }}</div>
                                                                    <div class="text-gray-500">
                                                                        ({{ ucfirst($promotor->pivot->tipo_designacao) }})
                                                                        @if ($promotor->pivot->data_inicio_designacao && $promotor->pivot->data_fim_designacao)
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($promotor->pivot->data_inicio_designacao)->format('d/m/Y') }}
                                                                            até
                                                                            {{ \Carbon\Carbon::parse($promotor->pivot->data_fim_designacao)->format('d/m/Y') }}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-xs sm:text-sm text-gray-500">Nenhum promotor
                                                            designado</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 sm:px-6 py-5 align-top">
                                                    <div class="text-xs sm:text-sm space-y-2">
                                                        @if ($plantao->periodo)
                                                            <div class="text-gray-700">
                                                                <span class="font-medium">Período:</span>
                                                                {{ $plantao->periodo->periodo_inicio->format('d/m/Y') }}
                                                                - {{ $plantao->periodo->periodo_fim->format('d/m/Y') }}
                                                            </div>
                                                        @endif
                                                        @if ($plantao->observacoes)
                                                            <div class="text-gray-700">
                                                                <span class="font-medium">Obs:</span>
                                                                {{ $plantao->observacoes }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if (isset($this->promotoriasPorMunicipio[$nomeMunicipio]) &&
                            $this->promotoriasPorMunicipio[$nomeMunicipio]->count() > 0)
                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm bg-white">
                            @php
                                $promotoriasMunicipio = $this->promotoriasPorMunicipio[$nomeMunicipio];
                                $promotoriasPorGrupo = $promotoriasMunicipio
                                    ->groupBy(function ($p) {
                                        return optional($p->grupoPromotoria)->nome ?? 'Sem grupo';
                                    })
                                    ->sortKeys();

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
                                            '3ª PJ Defesa do Patrimônio Público e Fundações' => 39,
                                        ];

                                        if (isset($ordemMacapa[$a->nome]) && isset($ordemMacapa[$b->nome])) {
                                            return $ordemMacapa[$a->nome] - $ordemMacapa[$b->nome];
                                        }

                                        if (isset($ordemMacapa[$a->nome])) {
                                            return -1;
                                        }
                                        if (isset($ordemMacapa[$b->nome])) {
                                            return 1;
                                        }

                                        return strcasecmp($a->nome, $b->nome);
                                    });
                                }
                            @endphp

                            @foreach ($promotoriasPorGrupo as $nomeGrupo => $promotoriasDoGrupo)
                                <div class="bg-gray-50 px-4 sm:px-6 py-2 sm:py-3 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4
                                                class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wide">
                                                {{ $nomeGrupo }}
                                            </h4>
                                            <p class="text-base text-gray-700 mt-1 font-semibold">{{ $nomeMunicipio }}
                                            </p>
                                        </div>

                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 table-responsive">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/6">
                                                    Promotorias</th>
                                                <th
                                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/4">
                                                    Promotores</th>
                                                <th
                                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                                    Período de Designação</th>
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
                                                                        {{ $promotoria->nome }}</div>
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
                                                                        <div
                                                                            class="text-xs text-bold sm:text-sm text-red-500 font-semibold">
                                                                            @if ($promotoria->vacancia_data_inicio)
                                                                                Vacante a partir de
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
                                                                    {{ $evento->titulo ?: ucfirst($evento->tipo ?: '') }}
                                                                </h5>
                                                                @php $promotores = $evento->promotores ?? collect(); @endphp
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
                                                                                    <span
                                                                                        class="text-gray-500">({{ $t === 'substituto' ? 'Substituindo' : ucfirst($t) }})</span>
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
                                                                @endif
                                                                @if ($evento->periodo_inicio || $evento->periodo_fim)
                                                                    <div
                                                                        class="text-[11px] sm:text-xs text-gray-600 mt-1">
                                                                        {{ $evento->periodo_inicio ? \Carbon\Carbon::parse($evento->periodo_inicio)->format('d/m/Y') : '' }}
                                                                        @if ($evento->periodo_inicio && $evento->periodo_fim)
                                                                            —
                                                                        @endif
                                                                        {{ $evento->periodo_fim ? \Carbon\Carbon::parse($evento->periodo_fim)->format('d/m/Y') : '' }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                        <td class="px-4 sm:px-6 py-4 border-r border-gray-200">
                                                            <div
                                                                class="text-xs sm:text-sm font-medium text-gray-900 break-words">
                                                                {{ $promotoria->nome }}</div>
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
                                                                <div
                                                                    class="text-xs font-semibold  sm:text-sm text-red-500">
                                                                    @if ($promotoria->vacancia_data_inicio)
                                                                        Vacante a partir de
                                                                        {{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}
                                                                    @else
                                                                        Promotoria Vacante
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 sm:px-6 py-4">
                                                            <div class="text-xs sm:text-sm text-gray-500">Nenhum período
                                                                cadastrado</div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        @if (
            $this->promotoriasPorMunicipio->count() === 0 &&
                $this->plantoesPorMunicipio->count() === 0 &&
                $this->periodos->count() === 0)
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma informação disponível</h3>
                <p class="mt-1 text-sm text-gray-500">Configure os dados no modo Gestão Espelho primeiro.</p>
            </div>
        @endif

        <!-- Seção de Preview dos Promotores Substitutos -->
        @if ($this->promotoresSubstitutos->isNotEmpty())
            <div class="space-y-6 sm:space-y-8 mt-8">
                <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm bg-white">
                    <div class="bg-gray-50 px-4 sm:px-6 py-2 sm:py-3 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wide">
                                    Promotores Substitutos
                                </h4>
                                @if ($this->periodos->count() > 0)
                                    <p class="text-base text-gray-700 mt-1 font-semibold">
                                        Período: {{ $this->periodos->first()->periodo_inicio->format('d/m/Y') }} -
                                        {{ $this->periodos->first()->periodo_fim->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-responsive">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/6">
                                        Promotores
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/4">
                                        Cargos
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Eventos/Designações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($this->promotoresSubstitutos as $promotor)
                                    @php
                                        $eventosCount = $promotor->eventos->count();
                                    @endphp

                                    @if ($eventosCount > 0)
                                        @foreach ($promotor->eventos as $indexEvento => $evento)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                @if ($indexEvento === 0)
                                                    <td rowspan="{{ $eventosCount }}"
                                                        class="px-4 sm:px-6 py-4 align-top border-r border-gray-200">
                                                        <div class="text-xs sm:text-sm">
                                                            <div class="mb-1">
                                                                <span
                                                                    class="font-medium text-red-600">{{ $promotor->promotor_nome }}</span>
                                                                <span class="text-xs text-gray-500">Substituto</span>
                                                            </div>
                                                            @if ($promotor->promotor_cargos !== 'N/A')
                                                                <div class="space-y-1">
                                                                    <div class="text-gray-900">
                                                                        {{ $promotor->promotor_cargos }}</div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                @endif

                                                @if ($indexEvento === 0)
                                                    <td rowspan="{{ $eventosCount }}"
                                                        class="px-4 sm:px-6 py-4 align-top border-r border-gray-200">
                                                        <div class="text-xs sm:text-sm text-gray-500">
                                                            {{ ucfirst($promotor->promotor_tipo) }}
                                                        </div>
                                                    </td>
                                                @endif

                                                <td class="px-4 sm:px-6 py-4">
                                                    <h5 class="text-sm sm:text-base font-bold text-blue-700 mb-1">
                                                        {{ $evento->evento_titulo ?:
                                                            ($evento->evento_tipo
                                                                ? ($evento->evento_tipo === 'respondendo'
                                                                    ? 'Respondendo'
                                                                    : ($evento->evento_tipo === 'auxiliando'
                                                                        ? 'Auxiliando'
                                                                        : ($evento->evento_tipo === 'atuando'
                                                                            ? 'Atuando'
                                                                            : ucfirst($evento->evento_tipo))))
                                                                : 'Evento') }}
                                                    </h5>

                                                    @if ($evento->evento_tipo)
                                                        <div class="text-[11px] sm:text-xs text-gray-600 mb-1">
                                                            <span class="font-medium">Tipo:</span>
                                                            @if ($evento->evento_tipo === 'respondendo')
                                                                Respondendo
                                                            @elseif($evento->evento_tipo === 'auxiliando')
                                                                Auxiliando
                                                            @elseif($evento->evento_tipo === 'atuando')
                                                                Atuando
                                                            @else
                                                                {{ ucfirst($evento->evento_tipo) }}
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if ($evento->promotoria_nome && $evento->promotoria_nome !== 'N/A')
                                                        <div class="text-[11px] sm:text-xs text-gray-600 mb-1">
                                                            <span class="font-medium">Promotoria:</span>
                                                            {{ $evento->promotoria_nome }}
                                                        </div>
                                                    @endif

                                                    @if ($evento->data_inicio || $evento->data_fim)
                                                        <div class="text-[11px] sm:text-xs text-gray-600">
                                                            {{ $evento->data_inicio ? $evento->data_inicio : '' }}
                                                            @if ($evento->data_inicio && $evento->data_fim)
                                                                —
                                                            @endif
                                                            {{ $evento->data_fim ? $evento->data_fim : '' }}
                                                        </div>
                                                    @endif

                                                    @if ($evento->observacoes)
                                                        <div class="text-[11px] sm:text-xs text-gray-600 mt-1">
                                                            <span class="font-medium">Obs:</span>
                                                            {{ $evento->observacoes }}
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-4 sm:px-6 py-4 border-r border-gray-200">
                                                <div class="text-xs sm:text-sm font-medium text-gray-900 break-words">
                                                    {{ $promotor->promotor_nome }}
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 border-r border-gray-200">
                                                <div class="text-xs sm:text-sm text-gray-500">
                                                    {{ ucfirst($promotor->promotor_tipo) }}
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4">
                                                <div class="text-xs sm:text-sm text-gray-500">
                                                    Nenhum evento cadastrado
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
