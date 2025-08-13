<x-filament::page>
    <div class="space-y-6">
      
        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <h3 class="text-lg font-medium">Selecione o Período</h3>
            <div class="grid gap-4">
                @foreach($periodos->sortByDesc('created_at') as $periodo)
                    <button 
                        wire:click="selecionarPeriodo({{ $periodo->id }})"
                        class="px-4 py-2 text-left transition duration-200 ease-in-out flex justify-between items-center {{ $periodoSelecionado == $periodo->id ? 'bg-primary-500 text-white shadow-lg scale-102' : 'bg-gray-50 hover:bg-gray-100' }} rounded-lg border border-gray-200"
                    >
                        <span>
                            {{ Carbon\Carbon::parse($periodo->periodo_inicio)->format('d/m/Y') }} - 
                            {{ Carbon\Carbon::parse($periodo->periodo_fim)->format('d/m/Y') }}
                        </span>
                        @if($periodoSelecionado == $periodo->id)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <h3 class="text-lg font-medium">Filtrar por Período Específico</h3>
            <div class="grid grid-cols-2 gap-4">
                <x-filament::input
                    type="date"
                    wire:model.defer="dataInicial"
                    label="Data Inicial"
                />

                <x-filament::input
                    type="date"
                    wire:model.defer="dataFinal"
                    label="Data Final"
                />
            </div>
            <div class="flex justify-end">
                <x-filament::button 
                    wire:click="filtrarPorPeriodo"
                    class="mt-2"
                >
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Aplicar Filtro
                    </span>
                </x-filament::button>
            </div>
        </div>


        <div class="flex justify-end">
            <x-filament::button
                tag="a"
                href="{{ route('download-relatorio-pdf', ['periodoId' => $periodoSelecionado]) }}"
                target="_blank"
            >
                Baixar Relatório
            </x-filament::button>
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