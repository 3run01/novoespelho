<x-filament::page class="bg-gray-50 dark:bg-gray-800 ">
    <div x-data="{ 
        previewMode: @entangle('previewMode'),
        init() {
            $watch('previewMode', value => console.log('Preview mode:', value))
        }
    }" class="flex flex-col items-center mt-8">
        <div class="relative inline-flex mb-4">
            <div class="w-64 h-12 bg-gray-200 dark:bg-gray-700 rounded-full p-1 flex overflow-hidden">
                <button 
                    wire:click="$set('previewMode', false)"
                    class="flex-1 relative z-10 flex items-center justify-center text-sm font-medium transition-colors duration-300"
                    :class="!previewMode ? 'text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'">
                    Principal
                </button>
                <button 
                    wire:click="togglePreview"
                    class="flex-1 relative z-10 flex items-center justify-center text-sm font-medium transition-colors duration-300"
                    :class="previewMode ? 'text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'">
                    Preview
                </button>
                <div class="absolute inset-0 w-1/2 h-full bg-blue-500 rounded-full transition-transform duration-300"
                     :class="{ 'translate-x-full': previewMode }">
                </div>
            </div>
        </div>
       
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden w-full"> 
            @if($this->hasAlteracoesPendentes())
                <div class="fixed bottom-4 right-4 z-50">
                    <x-filament::button
                        color="success"
                        size="lg"
                        wire:click="confirmarAlteracoes"
                        class="shadow-lg"
                    >
                        <span class="flex items-center gap-2">
                            <x-heroicon-s-check class="w-5 h-5" />
                            Salvar Todas as Alterações
                        </span>
                    </x-filament::button>
                </div>
            @endif

            @if(!$previewMode)
                <div class="flex justify-center mt-4">
                    <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-md w-1/3">
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Adicionar Período</label>
                        <div class="space-y-2">
                            <div x-data="{ 
                                    inicio: localStorage.getItem('periodo_inicio') || '',
                                    fim: localStorage.getItem('periodo_fim') || '',
                                    updatePreview() {
                                        this.inicio = $wire.novo_periodo_inicio;
                                        this.fim = $wire.novo_periodo_fim;
                                        localStorage.setItem('periodo_inicio', this.inicio);
                                        localStorage.setItem('periodo_fim', this.fim);
                                    }
                                }" x-on:date-updated.window="updatePreview()">
                                <div class="relative">
                                    <input 
                                            type="date" 
                                            wire:model="novo_periodo_inicio" 
                                            x-on:change="updatePreview(); $dispatch('date-updated')"
                                            :value="inicio"
                                            required 
                                        class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-sm">
                                    
                                    <input 
                                    type="date" 
                                    wire:model="novo_periodo_fim" 
                                    x-on:change="updatePreview(); $dispatch('date-updated')"
                                    :value="fim"
                                    required 
                                        class="mt-2 w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-sm">
                                </div>
                               
                            </div>
                            
                            <button 
                                wire:click="adicionarPeriodoTemporario" 
                                class="w-full mt-2 px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors dark:bg-blue-600 dark:hover:bg-blue-700">
                                Adicionar Período
                            </button>
                        </div>
                    </div>
                </div>
        
        </div>
        @endif

        @if(!$previewMode)
        <div >
            @include('filament.pages.components.PlantaoUrgencia.PlantaoUrgencia', ['plantoes' => $this->plantoes])
        </div>
        @endif

        @if(!$previewMode)
        <div class="space-y-6 p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Eventos</h2>
            </div>
            
            <!-- Container responsivo para a tabela -->
            <div class="max-w-full -mx-4 sm:mx-0">
                <div class="min-w-full overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead>
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                        Município
                                    </th>
                                    <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                        Grupo Promotoria
                                    </th>
                                    <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                        Promotoria
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                        Membro
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                        Eventos
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-700 dark:divide-gray-600">
                                @foreach ($promotorias->groupBy('promotor_id') as $promotoriasGroup)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-3 sm:px-6 py-4 whitespace-normal text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $promotoriasGroup->first()->municipio }}
                                    </td>
                                    <td class="hidden sm:table-cell px-3 sm:px-6 py-4 whitespace-normal text-sm text-gray-600 dark:text-gray-300">
                                        {{ $promotoriasGroup->first()->grupo_promotoria }}
                                    </td>
                                    <td class="hidden md:table-cell px-3 sm:px-6 py-4 whitespace-normal text-sm text-gray-600 dark:text-gray-300">
                                        {{ $promotoriasGroup->first()->promotoria }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-normal text-sm text-gray-600 dark:text-gray-300">
                                        {{ $promotoriasGroup->first()->promotor }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-4">
                                        @if ($promotoriasGroup->isEmpty())
                                            <button
                                                wire:click="addEvento({{ $promotoriasGroup->first()->promotor_id }})"
                                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-lg shadow hover:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 text-sm"
                                            >
                                                Adicionar <span class="ml-1">+</span>
                                            </button>
                                        @else
                                            <!-- Eventos Temporários -->
                                            @if($this->hasEventosTemporarios())
                                                @foreach ($eventosTemporarios as $index => $evento)
                                                    @if(isset($evento['promotor_id']) && $evento['promotor_id'] == $promotoriasGroup->first()->promotor_id)
                                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 bg-gray-50 dark:bg-gray-600 rounded-lg mb-2">
                                                            <span class="text-sm text-gray-600 dark:text-gray-300 break-words w-full sm:w-auto">
                                                                {{ $evento['titulo'] ?? 'Novo Evento' }} - {{ $evento['tipo'] ?? 'Tipo não definido' }}
                                                                <span class="ml-2 text-xs text-blue-500">(Preview)</span>
                                                            </span>
                                                            <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                                                                @include('filament.pages.components.Modal.EditModalEventoTemporario', [
                                                                    'index' => $index,
                                                                    'evento' => $evento,
                                                                    'isTemporary' => true
                                                                ])
                                                                <button wire:click="removeEventoTemporario({{ $index }})" class="text-red-600 hover:text-red-800 p-2 flex items-center justify-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif

                                            <!-- Eventos Existentes -->
                                            @foreach ($promotoriasGroup as $promotoria)
                                                @if($promotoria->evento)
                                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 bg-gray-50 dark:bg-gray-600 rounded-lg mb-2">
                                                        <span class="text-sm text-gray-600 dark:text-gray-300 break-words w-full sm:w-auto">
                                                            {{ $promotoria->evento }}
                                                        </span>
                                                        <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                                                            @include('filament.pages.components.Modal.EditModalEvento', ['evento' => $promotoria])
                                                            <button wire:click="deleteEvento({{ $promotoria->evento_id }})" onclick="setTimeout(() => { location.reload(); }, 10);" class="text-red-600 hover:text-red-800 p-2 flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                            <!-- Modal de Add novo evento -->
                                            <div class="mt-2">
                                                @include('filament.pages.components.Modal.ModalEvento', ['promotoria' => $promotoria])
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif



        
        <!-- Preview dos Eventos -->
        @if($previewMode)
            <div class="space-y-6 p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                <!-- Período Selecionado -->
                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-md" 
                    x-data="{ 
                        inicio: localStorage.getItem('periodo_inicio') ? new Date(localStorage.getItem('periodo_inicio')).toLocaleDateString('pt-BR') : '-',
                        fim: localStorage.getItem('periodo_fim') ? new Date(localStorage.getItem('periodo_fim')).toLocaleDateString('pt-BR') : '-'
                    }">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Período Selecionado</label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                De: <span x-text="inicio" class="font-medium"></span> - Até: <span x-text="fim" class="font-medium"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Plantões de Atendimento Emergenciais -->
                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Plantões de Atendimento Emergenciais</h3>
                    <div class="space-y-4">
                        @if($plantoesTemporarios && count($plantoesTemporarios) > 0)
                            @foreach($plantoesTemporarios as $plantao)
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Promotor Designado: {{ $promotorias->where('promotor_id', $plantao['promotor_designado_id'])->first()->promotor ?? 'Não definido' }}
                                                <span class="ml-2 text-xs text-blue-500">(Preview)</span>
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                Período: {{ \Carbon\Carbon::parse($plantao['periodo_inicio'])->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($plantao['periodo_fim'])->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum plantão emergencial em preview.</p>
                        @endif
                    </div>
                </div>

                <!-- Tabela de eventos -->

                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Preview dos Eventos</h2>
                </div>
                    <div class="max-w-full -mx-4 sm:mx-0">
                    <div class="min-w-full overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead>
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                            Município
                                        </th>
                                        <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                            Grupo Promotoria
                                        </th>
                                        <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                            Promotoria
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                            Membro
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                            Eventos Previstos
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-700 dark:divide-gray-600">
                                    @foreach ($promotorias->groupBy('promotor_id') as $promotoriasGroup)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-3 sm:px-6 py-4 whitespace-normal text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $promotoriasGroup->first()->municipio }}
                                        </td>
                                        <td class="hidden sm:table-cell px-3 sm:px-6 py-4 whitespace-normal text-sm text-gray-600 dark:text-gray-300">
                                            {{ $promotoriasGroup->first()->grupo_promotoria }}
                                        </td>
                                        <td class="hidden md:table-cell px-3 sm:px-6 py-4 whitespace-normal text-sm text-gray-600 dark:text-gray-300">
                                            {{ $promotoriasGroup->first()->promotoria }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-normal text-sm text-gray-600 dark:text-gray-300">
                                            {{ $promotoriasGroup->first()->promotor }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-4">
                                            @if($this->hasEventosTemporarios())
                                                @foreach ($eventosTemporarios as $evento)
                                                    @if(isset($evento['promotor_id']) && $evento['promotor_id'] == $promotoriasGroup->first()->promotor_id)
                                                        <div class="mb-2 p-3 bg-gray-50 dark:bg-gray-600 rounded-lg">
                                                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                                                <span class="font-medium">{{ $evento['titulo'] }}</span>
                                                                <span class="ml-2 text-xs text-blue-500">(Preview)</span>
                                                            </p>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                Tipo: {{ $evento['tipo'] }}
                                                            </p>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                Período: {{ \Carbon\Carbon::parse($evento['periodo_inicio'])->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($evento['periodo_fim'])->format('d/m/Y') }}
                                                            </p>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                Membro Designado: 
                                                                @if(isset($promotorias))
                                             {{ $promotorias->where('promotor_id', $evento['promotor_designado'])->first()->promotor ?? 'Não definido' }}
                                                            @endif
                                                            </p>
                                                           
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif

                                            @foreach ($promotoriasGroup as $promotoria)
                                                @if($promotoria->evento)
                                                    <div class="mb-2 p-3 bg-gray-50 dark:bg-gray-600 rounded-lg">
                                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                                            {{ $promotoria->evento }}
                                                        </p>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
         

    </div>
</x-filament::page>
<script>
  

</script>


