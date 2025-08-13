<div class="space-y-8">
    <div class="text-center">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Prévia do Espelho</h1>
        <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">Visualização completa das informações organizadas</p>
    </div>

    @if($this->periodos->count() > 0)
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Períodos</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($this->periodos as $periodo)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-medium text-gray-900">
                                    {{ $periodo->periodo_inicio->format('d/m/Y') }} - {{ $periodo->periodo_fim->format('d/m/Y') }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    Duração: {{ $periodo->periodo_inicio->diffInDays($periodo->periodo_fim) + 1 }} dias
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                Período
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($this->plantoes->count() > 0)
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Plantões de Urgência</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($this->plantoes as $plantao)
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                    {{ $plantao->nome ?? 'Plantão de Urgência' }}
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        Urgência
                                    </span>
                                </h3>
                                <div class="mt-1 space-y-1">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Município:</span> {{ $plantao->municipio->nome }}
                                    </p>
                                    @if($plantao->periodo)
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Período:</span> 
                                            {{ $plantao->periodo->periodo_inicio->format('d/m/Y') }} - 
                                            {{ $plantao->periodo->periodo_fim->format('d/m/Y') }}
                                        </p>
                                    @endif
                                    @if($plantao->observacoes)
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Observações:</span> {{ $plantao->observacoes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($plantao->promotores->count() > 0)
                            <div>
                                <h4 class="text-xs font-medium text-gray-700 mb-2">Promotores Designados:</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($plantao->promotores as $promotor)
                                        <div class="flex items-center gap-2 text-sm bg-gray-50 px-3 py-2 rounded">
                                            <div class="h-6 w-6 rounded-full bg-red-100 flex items-center justify-center">
                                                <span class="text-xs font-medium text-red-600">
                                                    {{ substr($promotor->nome, 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <span class="font-medium text-gray-900">{{ $promotor->nome }}</span>
                                                <span class="text-xs text-gray-500 ml-1">({{ ucfirst($promotor->pivot->tipo_designacao) }})</span>
                                                @if($promotor->pivot->data_inicio_designacao && $promotor->pivot->data_fim_designacao)
                                                    <div class="text-xs text-gray-600">
                                                        {{ \Carbon\Carbon::parse($promotor->pivot->data_inicio_designacao)->format('d/m/Y') }} - 
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

    @if($this->promotorias->count() > 0)
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Eventos por Município</h2>
            </div>
            <div class="space-y-8 p-6">
                @foreach($this->promotoriasPorMunicipio as $nomeMunicipio => $promotoriasMunicipio)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
                            <h3 class="text-xl font-bold text-blue-900">
                                <i class="fas fa-map-marker-alt mr-2"></i>{{ $nomeMunicipio }}
                            </h3>
                            <p class="text-sm text-blue-700 mt-1">
                                {{ $promotoriasMunicipio->count() }} {{ $promotoriasMunicipio->count() == 1 ? 'promotoria' : 'promotorias' }}
                            </p>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                            Promotorias
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                            Promotores
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                            Períodos
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($promotoriasMunicipio as $promotoria)
                                        @php
                                            $eventosCount = $promotoria->eventos->count();
                                        @endphp
                                        @if($eventosCount > 0)
                                            @foreach($promotoria->eventos as $index => $evento)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    @if($index === 0)
                                                        <td rowspan="{{ $eventosCount }}" class="px-6 py-6 align-top">
                                                            <div class="flex-1">
                                                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $promotoria->nome }}</h4>
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                                    {{ $eventosCount }} {{ $eventosCount == 1 ? 'evento' : 'eventos' }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                    @endif

                                                    <td class="px-6 py-6 align-top">
                                                        <div class="space-y-3">
                                                            <div class="flex items-center gap-3 mb-3">
                                                                <h5 class="text-base font-semibold text-gray-900">{{ $evento->titulo ?: ucfirst($evento->tipo) }}</h5>
                                                                @if($evento->is_urgente)
                                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                        Urgente
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            
                                                            @if($evento->promotores->count() > 0)
                                                                <div class="space-y-2">
                                                                    @foreach($evento->promotores as $promotor)
                                                                        <div class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded-lg">
                                                                            <div class="flex items-center gap-2">
                                                                                <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center">
                                                                                    <span class="text-xs font-medium text-blue-600">
                                                                                        {{ substr($promotor->nome, 0, 1) }}
                                                                                    </span>
                                                                                </div>
                                                                                <span class="text-sm font-medium text-gray-900">{{ $promotor->nome }}</span>
                                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $promotor->pivot->tipo === 'titular' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                                                    {{ ucfirst($promotor->pivot->tipo) }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <p class="text-sm text-gray-500 italic">Nenhum promotor designado</p>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-6 align-top">
                                                        <div class="space-y-3">
                                                            <div class="text-sm text-gray-600">
                                                                <span class="font-medium text-gray-900">Período:</span> 
                                                                {{ $evento->periodo_inicio->format('d/m/Y') }} - 
                                                                {{ $evento->periodo_fim->format('d/m/Y') }}
                                                            </div>
                                                            
                                                            @if($evento->promotores->count() > 0)
                                                                <div>
                                                                    <h6 class="text-sm font-medium text-gray-900 mb-2">Designações:</h6>
                                                                    <div class="space-y-1">
                                                                        @foreach($evento->promotores as $promotor)
                                                                            <div class="text-xs text-gray-700 bg-gray-50 rounded px-2 py-1">
                                                                                <strong>{{ $promotor->nome }}</strong><br>
                                                                                {{ \Carbon\Carbon::parse($promotor->pivot->data_inicio_designacao)->format('d/m/Y') }} - 
                                                                                {{ \Carbon\Carbon::parse($promotor->pivot->data_fim_designacao)->format('d/m/Y') }}
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-6 align-top">
                                                    <div class="flex-1">
                                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $promotoria->nome }}</h4>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                            0 eventos
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-6 text-center text-gray-500 italic">
                                                    Nenhum evento cadastrado
                                                </td>
                                                <td class="px-6 py-6 text-center text-gray-500">
                                                    -
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($this->promotorias->count() === 0 && $this->plantoes->count() === 0 && $this->periodos->count() === 0)
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma informação disponível</h3>
            <p class="mt-1 text-sm text-gray-500">Configure os dados no modo Gestão Espelho primeiro.</p>
        </div>
    @endif
</div>
