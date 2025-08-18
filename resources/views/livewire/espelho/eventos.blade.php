<div class="w-full">
    <div class="mb-6 sm:mb-8">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Gestão de Eventos</h1>
            <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">Gerencie os eventos por período e promotoria</p>
        </div>
    </div>

    @if (session()->has('mensagem'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('mensagem') }}</span>
        </div>
    @endif

    @if (session()->has('erro'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('erro') }}</span>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                    <select wire:model.live="periodoSelecionadoId"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos os períodos</option>
                        @foreach ($this->periodos as $periodo)
                            <option value="{{ $periodo->id }}">
                                {{ $periodo->periodo_inicio->format('d/m/Y') }} -
                                {{ $periodo->periodo_fim->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" wire:model.live.debounce.300ms="termoBusca"
                        placeholder="Buscar por título, tipo ou promotoria..."
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    @if ($this->periodoSelecionado)
                        <label class="block text-sm font-medium text-gray-700 mb-2">Período Selecionado</label>
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                            <p class="text-sm text-blue-800 font-medium">
                                {{ $this->periodoSelecionado->periodo_inicio->format('d/m/Y') }} -
                                {{ $this->periodoSelecionado->periodo_fim->format('d/m/Y') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            @php
                $todasPromotorias = collect();
                foreach ($this->promotoriasListado as $g) {
                    foreach ($g->promotorias as $p) {
                        $todasPromotorias->push($p);
                    }
                }
                $todasPromotorias = $todasPromotorias->unique('id')->values();

                $promotoriasPorEntrancia = $todasPromotorias->groupBy(function ($p) {
                    $entrancia = optional(optional($p->grupoPromotoria)->municipio)->entrancia ?? 'inicial';
                    return $entrancia;
                });

                $promotoriasPorEntrancia = $promotoriasPorEntrancia->sortByDesc(function ($promotorias, $entrancia) {
                    return $entrancia === 'final' ? 1 : 0;
                });
            @endphp

            @forelse ($promotoriasPorEntrancia as $entrancia => $promotoriasEntrancia)
                <div class="bg-gray-200 px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-600 uppercase tracking-wide">
                        @if ($entrancia === 'final')
                            Promotorias de Justiça de Entrância Final
                        @else
                            Promotorias de Justiça de Entrância Inicial
                        @endif
                    </h2>
                </div>

                @php
                    $promotoriasPorMunicipio = $promotoriasEntrancia->groupBy(function ($p) {
                        $nome = optional(optional($p->grupoPromotoria)->municipio)->nome ?? 'Sem município';
                        return trim($nome);
                    });

                    $ordemPrioritariaMunicipios = ['Macapá', 'Santana'];
                    $prioridadesMunicipios = [];
                    foreach ($ordemPrioritariaMunicipios as $indicePrioridade => $nomePrioritario) {
                        $prioridadesMunicipios[trim($nomePrioritario)] = $indicePrioridade;
                    }

                    $promotoriasPorMunicipio = $promotoriasPorMunicipio->sortBy(function (
                        $promotorias,
                        $nomeMunicipio,
                    ) use ($prioridadesMunicipios) {
                        $nomeNormalizado = trim($nomeMunicipio);
                        $peso = $prioridadesMunicipios[$nomeNormalizado] ?? 9999;
                        return sprintf('%04d-%s', $peso, $nomeNormalizado);
                    });
                @endphp

                @foreach ($promotoriasPorMunicipio as $nomeMunicipio => $promotoriasMunicipio)
                    <div class="bg-gray-100 px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 uppercase tracking-wide">
                            Município: {{ $nomeMunicipio }}
                        </h3>
                    </div>

                    @php
                        $promotoriasPorGrupo = $promotoriasMunicipio->groupBy(function ($p) {
                            return optional($p->grupoPromotoria)->nome ?? 'Sem grupo';
                        });
                    @endphp

                    @foreach ($promotoriasPorGrupo as $nomeGrupo => $promotoriasDoGrupo)
                        <div class="bg-gray-50 px-4 sm:px-6 py-2 sm:py-3 border-b border-gray-200">
                            <h4 class="text-sm sm:text-base font-semibold text-gray-700 uppercase tracking-wide">
                                Grupo de Promotorias: {{ $nomeGrupo }}
                            </h4>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200 table-responsive">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                        Promotorias
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                        Promotores
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/5">
                                        Períodos
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($promotoriasDoGrupo as $promotoria)
                                    @php
                                        $eventosCount = $promotoria->eventos->count();
                                    @endphp

                                    @if ($eventosCount > 0)
                                        @foreach ($promotoria->eventos as $indexEvento => $evento)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                @if ($indexEvento === 0)
                                                    <td rowspan="{{ $eventosCount }}"
                                                        class="px-4 sm:px-6 py-6 align-top border-r w-1/5">
                                                        <div class="flex-1">
                                                            <h3
                                                                class="text-base sm:text-lg font-semibold text-gray-900 mb-2">
                                                                {{ $promotoria->nome }}
                                                            </h3>
                                                            <p class="text-xs sm:text-sm text-gray-600">
                                                                Município:
                                                                {{ optional(optional($promotoria->grupoPromotoria)->municipio)->nome ?? '—' }}
                                                            </p>

                                                            @if ($this->periodoSelecionado)
                                                                <div class="text-xs sm:text-sm text-gray-600 mb-3">
                                                                    <span class="font-medium text-gray-900">Período
                                                                        vigente:</span>
                                                                    <span class="block sm:inline">
                                                                        {{ $this->periodoSelecionado->periodo_inicio->format('d/m/Y') }}
                                                                        -
                                                                        {{ $this->periodoSelecionado->periodo_fim->format('d/m/Y') }}
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            <span
                                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                                {{ $eventosCount }}
                                                                {{ $eventosCount == 1 ? 'evento' : 'eventos' }}
                                                            </span>

                                                            <div class="mt-4">
                                                                <button
                                                                    wire:click="abrirModalCriarParaPromotoria({{ $promotoria->id }})"
                                                                    class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent shadow-sm text-xs sm:text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 4v16m8-8H4"></path>
                                                                    </svg>
                                                                    <span class="hidden sm:inline">Adicionar
                                                                        Evento</span>
                                                                    <span class="sm:hidden">Adicionar</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endif

                                                @if ($indexEvento === 0)
                                                    <td rowspan="{{ $eventosCount }}"
                                                        class="px-4 sm:px-6 py-6 align-top border-r w-1/5">
                                                        @if ($promotoria->promotorTitular)
                                                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                                                                <div class="flex items-center gap-2 sm:gap-3 mb-3">
                                                                    <div
                                                                        class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                                                        <span
                                                                            class="text-xs sm:text-sm font-bold text-blue-600">
                                                                            {{ substr($promotoria->promotorTitular->nome, 0, 1) }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="min-w-0 flex-1">
                                                                        <h4
                                                                            class="text-sm sm:text-lg font-semibold text-gray-900 truncate">
                                                                            {{ $promotoria->promotorTitular->nome }}
                                                                        </h4>
                                                                        <span
                                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                            Titular
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <!-- Informações Adicionais -->
                                                                <div class="space-y-2 text-xs sm:text-sm text-gray-600">
                                                                    @php
                                                                        $promotorTitular = $promotoria->promotorTitular;
                                                                        $cargosLista = [];

                                                                        if (
                                                                            $promotorTitular &&
                                                                            $promotorTitular->cargos
                                                                        ) {
                                                                            if (is_array($promotorTitular->cargos)) {
                                                                                $cargosLista = $promotorTitular->cargos;
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

                                                                        // Filtrar valores vazios
                                                                        $cargosLista = array_filter(
                                                                            $cargosLista,
                                                                            function ($cargo) {
                                                                                return !empty(trim($cargo));
                                                                            },
                                                                        );
                                                                    @endphp

                                                                    @if (!empty($cargosLista))
                                                                        <div>
                                                                            <span
                                                                                class="font-medium text-gray-900">Cargo(s):</span>
                                                                            <span
                                                                                class="break-words">{{ implode(', ', $cargosLista) }}</span>
                                                                        </div>
                                                                    @endif

                                                                    @if ($promotorTitular && $promotorTitular->zona_eleitoral)
                                                                        <div>
                                                                            <span class="font-medium text-gray-900">Zona
                                                                                Eleitoral:</span>
                                                                            {{ $promotorTitular->numero_da_zona_eleitoral ?? 'Sim' }}
                                                                        </div>
                                                                    @endif

                                                                    @if ($promotoria->titularidade_promotor_data_inicio)
                                                                        <div>
                                                                            <span
                                                                                class="font-medium text-gray-900">Início:</span>
                                                                            {{ \Carbon\Carbon::parse($promotoria->titularidade_promotor_data_inicio)->format('d/m/Y') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-center text-gray-500 italic py-6 sm:py-8">
                                                                <p class="text-xs sm:text-sm">Nenhum promotor titular
                                                                    designado</p>
                                                                @if ($promotoria->vacancia_data_inicio)
                                                                    <p class="mt-1 text-xs sm:text-sm text-red-600">
                                                                        Vacância desde
                                                                        {{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endif

                                                <!-- Coluna PERÍODOS (eventos) - Agora com 60% da largura -->
                                                <td class="px-4 sm:px-6 py-6 align-top w-3/5">
                                                    <div class="space-y-4">
                                                        <!-- Título e Info do Evento -->
                                                        <div class="border-l-4 border-blue-500 pl-3 sm:pl-4">
                                                            <div class="flex items-center gap-2 sm:gap-3 mb-2">
                                                                <h4
                                                                    class="text-sm sm:text-lg font-semibold text-gray-900 break-words">
                                                                    {{ $evento->titulo ?: ucfirst($evento->tipo ?: 'Evento') }}
                                                                </h4>
                                                            </div>

                                                            <!-- Período do Evento -->
                                                            @if ($evento->periodo_inicio || $evento->periodo_fim)
                                                                <div class="text-xs sm:text-sm text-gray-600 mb-3">
                                                                    <span
                                                                        class="font-medium text-gray-900">Período:</span>
                                                                    <span class="block sm:inline">
                                                                        @if ($evento->periodo_inicio)
                                                                            {{ $evento->periodo_inicio->format('d/m/Y') }}
                                                                        @endif
                                                                        @if ($evento->periodo_inicio && $evento->periodo_fim)
                                                                            -
                                                                        @endif
                                                                        @if ($evento->periodo_fim)
                                                                            {{ $evento->periodo_fim->format('d/m/Y') }}
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        @if ($evento->designacoes->count() > 0)
                                                            <div>
                                                                <h5
                                                                    class="text-xs sm:text-sm font-medium text-gray-900 mb-2">
                                                                    Promotores Designados:</h5>
                                                                <div class="space-y-2">
                                                                    @foreach ($evento->designacoes as $designacao)
                                                                        <div
                                                                            class="bg-gray-50 rounded px-2 sm:px-3 py-2">
                                                                            <div
                                                                                class="flex items-center justify-between">
                                                                                <div
                                                                                    class="flex items-center gap-2 min-w-0 flex-1">
                                                                                    <div
                                                                                        class="h-5 w-5 sm:h-6 sm:w-6 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                                                                        <span
                                                                                            class="text-xs font-medium text-green-600">
                                                                                            {{ substr($designacao->promotor->nome ?? '?', 0, 1) }}
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="min-w-0 flex-1">
                                                                                        <span
                                                                                            class="text-xs sm:text-sm font-medium text-gray-900 block truncate">{{ $designacao->promotor->nome ?? '—' }}</span>
                                                                                        <span
                                                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ ($designacao->tipo ?? 'titular') === 'titular' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                                                            {{ ucfirst($designacao->tipo ?? 'titular') }}
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            @if ($designacao->data_inicio_designacao || $designacao->data_fim_designacao)
                                                                                <div class="text-xs text-gray-600 mt-1">
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
                                                            </div>
                                                        @else
                                                            <p class="text-xs sm:text-sm text-gray-500 italic">Nenhum
                                                                promotor
                                                                designado para este evento</p>
                                                        @endif

                                                        <!-- Ações -->
                                                        <div
                                                            class="flex flex-col sm:flex-row items-start sm:items-center gap-2 pt-2 border-t">
                                                            <button wire:click="abrirModalEditar({{ $evento->id }})"
                                                                class="inline-flex items-center px-2 sm:px-3 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors w-full sm:w-auto justify-center">
                                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-1.5"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                    </path>
                                                                </svg>
                                                                Editar
                                                            </button>
                                                            <button
                                                                onclick="if(!confirm('Tem certeza que deseja deletar este evento? Esta ação não pode ser desfeita.')) { event.stopImmediatePropagation(); event.preventDefault(); }"
                                                                wire:click="deletar({{ $evento->id }})"
                                                                class="inline-flex items-center px-2 sm:px-3 py-2 text-xs sm:text-sm font-medium text-red-700 bg-white border border-red-300 rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors w-full sm:w-auto justify-center">
                                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-1.5"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                    </path>
                                                                </svg>
                                                                Deletar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <!-- Linha para promotoria sem eventos -->
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 sm:px-6 py-6 align-top border-r w-1/5">
                                                <div class="flex-1">
                                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">
                                                        {{ $promotoria->nome }}</h3>
                                                    <p class="text-xs sm:text-sm text-gray-600">
                                                        Município:
                                                        {{ optional(optional($promotoria->grupoPromotoria)->municipio)->nome ?? '—' }}
                                                    </p>
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                        0 eventos
                                                    </span>

                                                    <div class="mt-4">
                                                        <button
                                                            wire:click="abrirModalCriarParaPromotoria({{ $promotoria->id }})"
                                                            class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent shadow-sm text-xs sm:text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Adicionar Evento</span>
                                                            <span class="sm:hidden">Adicionar</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-6 align-top border-r w-1/5">
                                                @if ($promotoria->promotorTitular)
                                                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                                                        <div class="flex items-center gap-2 sm:gap-3 mb-3">
                                                            <div
                                                                class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                                                <span
                                                                    class="text-xs sm:text-sm font-bold text-blue-600">
                                                                    {{ substr($promotoria->promotorTitular->nome, 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <div class="min-w-0 flex-1">
                                                                <h4
                                                                    class="text-sm sm:text-lg font-semibold text-gray-900 truncate">
                                                                    {{ $promotoria->promotorTitular->nome }}</h4>
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Titular</span>
                                                            </div>
                                                        </div>

                                                        <div class="space-y-2 text-xs sm:text-sm text-gray-600">
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

                                                            @if (!empty($cargosLista))
                                                                <div>
                                                                    <span
                                                                        class="font-medium text-gray-900">Cargo(s):</span>
                                                                    <span
                                                                        class="break-words">{{ implode(', ', $cargosLista) }}</span>
                                                                </div>
                                                            @endif

                                                            @if ($promotorTitular && $promotorTitular->zona_eleitoral)
                                                                <div>
                                                                    <span class="font-medium text-gray-900">Zona
                                                                        Eleitoral:</span>
                                                                    {{ $promotorTitular->numero_da_zona_eleitoral ?? 'Sim' }}
                                                                </div>
                                                            @endif

                                                            @if ($promotoria->titularidade_promotor_data_inicio)
                                                                <div>
                                                                    <span
                                                                        class="font-medium text-gray-900">Início:</span>
                                                                    {{ \Carbon\Carbon::parse($promotoria->titularidade_promotor_data_inicio)->format('d/m/Y') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-center text-gray-500 italic py-6 sm:py-8">
                                                        <p class="text-xs sm:text-sm">Nenhum promotor titular designado
                                                        </p>
                                                        @if ($promotoria->vacancia_data_inicio)
                                                            <p class="mt-1 text-xs sm:text-sm text-red-600">Vacância
                                                                desde
                                                                {{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4 sm:px-6 py-6 text-center text-gray-500 w-3/5">
                                                <span class="text-xs sm:text-sm">Nenhum evento cadastrado</span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                @endforeach
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum grupo de promotorias encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Verifique os filtros aplicados</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal de Evento -->
    @if ($this->mostrarModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            wire:click="fecharModal">
            <div class="relative top-4 sm:top-20 mx-auto p-4 sm:p-5 border w-11/12 md:w-3/4 lg:w-1/2 max-w-4xl shadow-lg rounded-md bg-white modal-mobile"
                wire:click.stop>
                <div class="mt-3">
                    <!-- Header do Modal -->
                    <div class="flex justify-between items-center pb-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $this->modoEdicao ? 'Editar Designação' : 'Nova Designação' }}
                        </h3>
                        <button wire:click="fecharModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Formulário -->
                    <form wire:submit.prevent="salvar" class="mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Título -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Título
                                </label>
                                <input type="text" wire:model.defer="titulo"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Digite o título da designação">
                                @error('titulo')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Período -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Período <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.defer="periodo_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('periodo_id') border-red-300 @enderror">
                                    <option value="">Selecione um período</option>
                                    @foreach ($this->periodos as $periodo)
                                        <option value="{{ $periodo->id }}">
                                            {{ $periodo->periodo_inicio->format('d/m/Y') }} -
                                            {{ $periodo->periodo_fim->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('periodo_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo
                                </label>
                                <select wire:model.defer="tipo"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecione o tipo</option>
                                    <option value="audiencia">Audiência</option>
                                    <option value="custodia">Custódia</option>
                                    <option value="plantao">Plantão</option>
                                    <option value="sessao">Sessão</option>
                                    <option value="congresso">Congresso</option>
                                    <option value="ferias">Férias</option>
                                    <option value="outro">Outro</option>
                                </select>
                                @error('tipo')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Promotoria -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Promotoria <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.defer="promotoria_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecione a promotoria</option>
                                    @foreach ($this->promotorias as $promotoria)
                                        <option value="{{ $promotoria->id }}">{{ $promotoria->nome }}</option>
                                    @endforeach
                                </select>
                                @error('promotoria_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Período Início -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Data Início
                                </label>
                                <input type="date" wire:model.defer="periodo_inicio"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('periodo_inicio')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Período Fim -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Data Fim
                                </label>
                                <input type="date" wire:model.defer="periodo_fim"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('periodo_fim')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Removida a seção de Urgente -->
                        </div>

                        <!-- Designações de Promotores -->
                        <div class="mt-8">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-900">Membros designados</h4>
                                <button type="button" wire:click="adicionarLinhaPromotor"
                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                    + Adicionar membro
                                </button>
                            </div>

                            <div class="space-y-4">
                                @foreach ($this->promotoresDesignacoes as $i => $linha)
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end"
                                        wire:key="promotor-linha-{{ $linha['uid'] ?? $i }}">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Membro</label>
                                            <select
                                                wire:model.defer="promotoresDesignacoes.{{ $i }}.promotor_id"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Selecione</option>
                                                @foreach ($this->promotores as $promotor)
                                                    <option value="{{ $promotor->id }}">{{ $promotor->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('promotoresDesignacoes.' . $i . '.promotor_id')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                                            <select wire:model.defer="promotoresDesignacoes.{{ $i }}.tipo"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <option value="substituto">Substituindo</option>
                                                <option value="respondendo">Respondendo</option>
                                                <option value="auxiliando">Auxiliando</option>
                                            </select>
                                            @error('promotoresDesignacoes.' . $i . '.tipo')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Início</label>
                                            <input type="date"
                                                wire:model.defer="promotoresDesignacoes.{{ $i }}.data_inicio_designacao"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            @error('promotoresDesignacoes.' . $i . '.data_inicio_designacao')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Fim</label>
                                            <input type="date"
                                                wire:model.defer="promotoresDesignacoes.{{ $i }}.data_fim_designacao"
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            @error('promotoresDesignacoes.' . $i . '.data_fim_designacao')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="button"
                                                wire:click="removerLinhaPromotor({{ $i }})"
                                                class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Remover</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
                            <button type="button" wire:click="fecharModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                                Cancelar
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 transition duration-200">
                                <span wire:loading.remove>
                                    {{ $this->modoEdicao ? 'Atualizar' : 'Salvar' }}
                                </span>
                                <span wire:loading>
                                    Salvando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif


</div>
