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
                        <span class="ml-3 inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-white border border-gray-300 text-gray-700 uppercase tracking-wider shadow-sm">
                            Período
                        </span>
                    </div>
                @endforeach
            </div>
            <div class="flex flex-wrap justify-center gap-4">
                @foreach ($this->periodos as $periodo)
                    <p class="text-xs text-gray-700 font-medium">
                        Duração: {{ $periodo->periodo_inicio->diffInDays($periodo->periodo_fim) + 1}} dias
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
                                            <span class="font-medium">Observações:</span> {{ $plantao->observacoes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($plantao->promotores->count() > 0)
                            <div>
                                <h4 class="text-xs font-medium text-gray-700 mb-2">Promotores Designados:</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach ($plantao->promotores as $promotor)
                                        <div class="flex items-center gap-2 text-sm bg-gray-50 px-3 py-2 rounded">
                                            <div
                                                class="h-6 w-6 rounded-full bg-red-100 flex items-center justify-center">
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
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Eventos por Município</h2>
            </div>
            <div class="space-y-8 p-6">
                @foreach ($this->promotoriasPorMunicipio as $nomeMunicipio => $promotoriasMunicipio)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
                            <h3 class="text-xl font-bold text-blue-900">
                                <i class="fas fa-map-marker-alt mr-2"></i>{{ $nomeMunicipio }}
                            </h3>
                            <p class="text-sm text-blue-700 mt-1">
                                {{ $promotoriasMunicipio->count() }}
                                {{ $promotoriasMunicipio->count() == 1 ? 'promotoria' : 'promotorias' }}
                            </p>
                        </div>









                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                            Promotorias
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                            Promotores
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                            Períodos
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $promotoriasPorGrupo = $promotoriasMunicipio->groupBy(function ($p) {
                                            return optional($p->grupo)->nome ?? 'Sem grupo';
                                        });
                                    @endphp

                                    @foreach ($promotoriasPorGrupo as $nomeGrupo => $promotoriasDoGrupo)
                                        @foreach ($promotoriasDoGrupo as $indexPromotoria => $promotoria)
                                            @php
                                                $eventos = $promotoria->eventos;
                                                $eventosCount = $eventos->count();
                                                $isFirstPromotoriaInGroup = $indexPromotoria === 0;
                                                $promotoriasInGroup = $promotoriasDoGrupo->count();
                                            @endphp

                                            @if ($eventosCount > 0)
                                                @foreach ($eventos as $indexEvento => $evento)
                                                    <tr class="hover:bg-gray-50 transition-colors">
                                                        @if ($indexEvento === 0)
                                                            <td rowspan="{{ $eventosCount }}" class="px-6 py-6 align-top border-r">
                                                                @if ($isFirstPromotoriaInGroup)
                                                                    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                                                                        <h2 class="text-sm font-bold text-blue-900 uppercase tracking-wide">
                                                                            {{ $nomeGrupo }}
                                                                        </h2>
                                                                        <p class="text-xs text-blue-700 mt-1">
                                                                            {{ $promotoriasInGroup }}
                                                                            {{ $promotoriasInGroup == 1 ? 'promotoria' : 'promotorias' }}
                                                                        </p>
                                                                    </div>
                                                                @endif

                                                                <div class="flex-1">
                                                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                                                        {{ $promotoria->nome }}
                                                                    </h4>
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                                        {{ $eventosCount }}
                                                                        {{ $eventosCount == 1 ? 'evento' : 'eventos' }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        @endif

                                                        @if ($indexEvento === 0)
                                                            <td rowspan="{{ $eventosCount }}" class="px-6 py-6 align-top border-r">
                                                                @if ($promotoria->promotorTitular)
                                                                    <div class="bg-gray-50 rounded-lg p-4">
                                                                        <div class="flex items-center gap-3 mb-3">
                                                                            <div
                                                                                class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                                                <span class="text-sm font-bold text-blue-600">
                                                                                    {{ substr($promotoria->promotorTitular->nome, 0, 1) }}
                                                                                </span>
                                                                            </div>
                                                                            <div>
                                                                                <h4 class="text-lg font-semibold text-gray-900">
                                                                                    {{ $promotoria->promotorTitular->nome }}
                                                                                </h4>
                                                                                <span
                                                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                                    Titular
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="text-center text-gray-500 italic py-8">
                                                                        <p>Nenhum promotor titular designado</p>
                                                                        @if ($promotoria->vacancia_data_inicio)
                                                                            <p class="mt-1 text-red-600">Vacância desde
                                                                                {{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        @endif

                                                        <td class="px-6 py-6 align-top">
                                                            <div class="space-y-4">
                                                                <div class="border-l-4 border-blue-500 pl-4">
                                                                    <div class="flex items-center gap-3 mb-2">
                                                                        <h4 class="text-lg font-semibold text-gray-900">
                                                                            {{ $evento->titulo ?: ucfirst($evento->tipo ?: 'Evento') }}
                                                                        </h4>
                                                                    </div>

                                                                    @if ($evento->periodo_inicio || $evento->periodo_fim)
                                                                        <div class="text-sm text-gray-600 mb-3">
                                                                            <span class="font-medium text-gray-900">Período:</span>
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
                                                                </div>

                                                                @php
                                                                    $designacoes = method_exists($evento, 'designacoes') ? $evento->designacoes : collect();
                                                                @endphp

                                                                @if (($designacoes->count() ?? 0) > 0)
                                                                    <div>
                                                                        <h5 class="text-sm font-medium text-gray-900 mb-2">Promotores
                                                                            Designados:</h5>
                                                                        <div class="space-y-2">
                                                                            @foreach ($designacoes as $designacao)
                                                                                <div class="bg-gray-50 rounded px-3 py-2">
                                                                                    <div class="flex items-center gap-2">
                                                                                        <div
                                                                                            class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center">
                                                                                            <span
                                                                                                class="text-xs font-medium text-green-600">
                                                                                                {{ substr($designacao->promotor->nome ?? '?', 0, 1) }}
                                                                                            </span>
                                                                                        </div>
                                                                                        <span
                                                                                            class="text-sm font-medium text-gray-900">{{ $designacao->promotor->nome ?? '—' }}</span>
                                                                                        <span
                                                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ ($designacao->tipo ?? 'titular') === 'titular' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                                                            {{ ucfirst($designacao->tipo ?? 'titular') }}
                                                                                        </span>
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
                                                                @elseif ($evento->promotores && $evento->promotores->count() > 0)
                                                                    <div>
                                                                        <h5 class="text-sm font-medium text-gray-900 mb-2">Promotores
                                                                            Designados:</h5>
                                                                        <div class="space-y-2">
                                                                            @foreach ($evento->promotores as $promotor)
                                                                                <div class="bg-gray-50 rounded px-3 py-2">
                                                                                    <div class="flex items-center gap-2">
                                                                                        <div
                                                                                            class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center">
                                                                                            <span
                                                                                                class="text-xs font-medium text-green-600">
                                                                                                {{ substr($promotor->nome, 0, 1) }}
                                                                                            </span>
                                                                                        </div>
                                                                                        <span
                                                                                            class="text-sm font-medium text-gray-900">{{ $promotor->nome }}</span>
                                                                                        <span
                                                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ ($promotor->pivot->tipo ?? 'titular') === 'titular' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                                                            {{ ucfirst($promotor->pivot->tipo ?? 'titular') }}
                                                                                        </span>
                                                                                    </div>
                                                                                    @if (($promotor->pivot->data_inicio_designacao ?? null) || ($promotor->pivot->data_fim_designacao ?? null))
                                                                                        <div class="text-xs text-gray-600 mt-1">
                                                                                            @if ($promotor->pivot->data_inicio_designacao ?? null)
                                                                                                {{ \Carbon\Carbon::parse($promotor->pivot->data_inicio_designacao)->format('d/m/Y') }}
                                                                                            @endif
                                                                                            @if (($promotor->pivot->data_inicio_designacao ?? null) && ($promotor->pivot->data_fim_designacao ?? null))
                                                                                                -
                                                                                            @endif
                                                                                            @if ($promotor->pivot->data_fim_designacao ?? null)
                                                                                                {{ \Carbon\Carbon::parse($promotor->pivot->data_fim_designacao)->format('d/m/Y') }}
                                                                                            @endif
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <p class="text-sm text-gray-500 italic">Nenhum promotor
                                                                        designado para este evento</p>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-6 py-6 align-top border-r">
                                                        @if ($isFirstPromotoriaInGroup)
                                                            <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                                                                <h2 class="text-sm font-bold text-blue-900 uppercase tracking-wide">
                                                                    {{ $nomeGrupo }}
                                                                </h2>
                                                                <p class="text-xs text-blue-700 mt-1">
                                                                    {{ $promotoriasInGroup }}
                                                                    {{ $promotoriasInGroup == 1 ? 'promotoria' : 'promotorias' }}
                                                                </p>
                                                            </div>
                                                        @endif

                                                        <div class="flex-1">
                                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                                                {{ $promotoria->nome }}</h4>
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                                0 eventos
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-6 align-top border-r">
                                                        @if ($promotoria->promotorTitular)
                                                            <div class="bg-gray-50 rounded-lg p-4">
                                                                <div class="flex items-center gap-3 mb-3">
                                                                    <div
                                                                        class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                                        <span class="text-sm font-bold text-blue-600">
                                                                            {{ substr($promotoria->promotorTitular->nome, 0, 1) }}
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <h4 class="text-lg font-semibold text-gray-900">
                                                                            {{ $promotoria->promotorTitular->nome }}</h4>
                                                                        <span
                                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Titular</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-center text-gray-500 italic py-8">
                                                                <p>Nenhum promotor titular designado</p>
                                                                @if ($promotoria->vacancia_data_inicio)
                                                                    <p class="mt-1">Vacância desde
                                                                        {{ \Carbon\Carbon::parse($promotoria->vacancia_data_inicio)->format('d/m/Y') }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-6 text-center text-gray-500">
                                                        Nenhum evento cadastrado
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
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
