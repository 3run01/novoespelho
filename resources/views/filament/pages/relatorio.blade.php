<x-filament::page>
    <div class="space-y-6">
        <!-- Lista de Períodos -->
        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <h3 class="text-lg font-medium">Selecione o Período</h3>
            <div class="grid gap-4">
                @foreach($periodos as $periodo)
                    <button 
                        wire:click="selecionarPeriodo({{ $periodo->id }})"
                        class="px-4 py-2 text-left {{ $periodoSelecionado == $periodo->id ? 'bg-primary-500 text-white' : 'bg-gray-100 hover:bg-gray-200' }} rounded-lg"
                    >
                        {{ Carbon\Carbon::parse($periodo->periodo_inicio)->format('d/m/Y') }} - 
                        {{ Carbon\Carbon::parse($periodo->periodo_fim)->format('d/m/Y') }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Filtro de Período -->
        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <h3 class="text-lg font-medium">Filtrar por Período</h3>
            <div class="grid grid-cols-2 gap-4">
                <x-filament::input
                    type="date"
                    wire:model="dataInicial"
                    label="Data Inicial"
                />

                <x-filament::input
                    type="date"
                    wire:model="dataFinal"
                    label="Data Final"
                />
            </div>
            <x-filament::button wire:click="gerarRelatorio">
                Filtrar
            </x-filament::button>
        </div>

        <!-- Resumo por Promotor -->
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium mb-4">Resumo por Promotor</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Promotor</th>
                            <th class="px-4 py-2 text-center">Total de Plantões</th>
                            <th class="px-4 py-2 text-center">Total de Dias</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($totalDiasPorPromotor ?? [] as $resumo)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $resumo->nome }}</td>
                                <td class="px-4 py-2 text-center">{{ $resumo->total_plantoes }}</td>
                                <td class="px-4 py-2 text-center">{{ $resumo->total_dias }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Plantões de Atendimento -->
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium mb-4">Plantões de Atendimento</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Promotor</th>
                            <th class="px-4 py-2 text-left">Período</th>
                            <th class="px-4 py-2 text-center">Dias</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plantoes ?? [] as $plantao)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $plantao->promotor_nome }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($plantao->periodo_inicio)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($plantao->periodo_fim)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2 text-center">{{ $plantao->total_dias }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Outros Eventos -->
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-medium mb-4">Eventos</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Tipo</th>
                            <th class="px-4 py-2 text-left">Promotoria</th>
                            <th class="px-4 py-2 text-left">Titular</th>
                            <th class="px-4 py-2 text-left">Designado</th>
                            <th class="px-4 py-2 text-left">Período</th>
                            <th class="px-4 py-2 text-center">Dias</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventos ?? [] as $evento)
                            <tr class="border-b">
                                <td class="px-4 py-2">
                                    @if($evento->is_urgente)
                                        <span class="text-red-600 font-semibold">Urgência</span>
                                    @else
                                        {{ $evento->tipo }}
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $evento->promotoria->nome }}</td>
                                <td class="px-4 py-2">{{ $evento->promotorTitular->nome }}</td>
                                <td class="px-4 py-2">{{ $evento->promotorDesignado->nome }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($evento->periodo_inicio)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($evento->periodo_fim)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2 text-center">{{ $evento->total_dias }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament::page>