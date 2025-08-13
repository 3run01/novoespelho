<div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-md center mt-4" 
    x-data="{ 
        inicio: localStorage.getItem('periodo_inicio') || '-',
        fim: localStorage.getItem('periodo_fim') || '-'
    }">
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Período Selecionado</label>
        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                De: <span x-text="inicio" class="font-medium"></span> - Até: <span x-text="fim" class="font-medium"></span>
            </p>
        </div>
    </div>

    @if(!empty($eventosTemporarios))
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Eventos Previstos</h3>
            <div class="space-y-4">
                @foreach($eventosTemporarios as $evento)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex flex-col">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $evento['titulo'] }}
                                <span class="ml-2 text-xs text-blue-500">(Preview)</span>
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Tipo: {{ $evento['tipo'] }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Período: {{ $evento['periodo_inicio'] }} até {{ $evento['periodo_fim'] }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if(isset($promotorias))
                                    Promotor Designado: {{ $promotorias->where('promotor_id', $evento['promotor_designado'])->first()->promotor ?? 'Não definido' }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(!empty($plantoesTemporarios))
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Plantões Previstos</h3>
            <div class="space-y-4">
                @foreach($plantoesTemporarios as $plantao)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex flex-col">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                @if(isset($promotorias))
                                    {{ $promotorias->where('promotor_id', $plantao['promotor_designado_id'])->first()->promotor ?? 'Não definido' }}
                                @endif
                                <span class="ml-2 text-xs text-blue-500">(Preview)</span>
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Período: {{ $plantao['periodo_inicio'] }} até {{ $plantao['periodo_fim'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>