<x-filament::page class="bg-gray-50 dark:bg-gray-800 ">
    <div x-data="{ 
        previewMode: @entangle('previewMode'),
        init() {
            $watch('previewMode', value => console.log('Preview mode:', value))
        }
    }" class="flex flex-col items-center mt-8">
        <div class="flex space-x-4 mb-4">
            <button 
                wire:click="$set('previewMode', false)" 
                class="px-4 py-2 text-white rounded-md" 
                :class="{ 'bg-blue-500 hover:bg-blue-600': !previewMode, 'bg-gray-500': previewMode }">
                Principal
            </button>
            <button 
                wire:click="togglePreview" 
                class="px-4 py-2 text-white rounded-md" 
                :class="{ 'bg-blue-500 hover:bg-blue-600': previewMode, 'bg-gray-500': !previewMode }">
                Preview
            </button>
        </div>
       
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden w-full"> 
            @if($this->hasEventosTemporarios())
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
                                wire:click="adicionarPeriodo" 
                                class="w-full mt-2 px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors dark:bg-blue-600 dark:hover:bg-blue-700">
                                Adicionar Período
                            </button>
                        </div>
                    </div>
                </div>
        
        </div>
        <div>
            @include('filament.pages.components.Preview.espelho-preview', ['xData' => '$data'])
        </div>
        @endif

        @if(!$previewMode)
        <div >
            @include('filament.pages.components.PlantaoUrgencia.PlantaoUrgencia', ['plantoes' => $this->plantoes])
        </div>
        @endif

        @if(!$previewMode)
        <div class="space-y-6 p-4 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
        
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
       <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Eventos</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead>
                        <tr>
                   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-white">
                                Município
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50 dark:bg-gray-600 dark:text-white">
                                Grupo Promotoria
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50 dark:bg-gray-600 dark:text-white">
                                Promotoria
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50 dark:bg-gray-600 dark:text-white">
                                Membro
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50 dark:bg-gray-600 dark:text-white">
                                Eventos
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-700 dark:divide-gray-600">
                        @foreach ($promotorias->groupBy('promotor_id') as $promotoriasGroup)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $promotoriasGroup->first()->municipio }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $promotoriasGroup->first()->grupo_promotoria }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $promotoriasGroup->first()->promotoria }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $promotoriasGroup->first()->promotor }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($promotoriasGroup->isEmpty())
                                    <button
                                        wire:click="addEvento({{ $promotoriasGroup->first()->promotor_id }})"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-lg shadow hover:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150"
                                    >
                                        Adicionar <span class="ml-1">+</span>
                                    </button>
                                @else
                                    <!-- Eventos Temporários -->
                                    @if($this->hasEventosTemporarios())
                                        @foreach ($eventosTemporarios as $index => $evento)
                                            @if(isset($evento['promotor_id']) && $evento['promotor_id'] == $promotoriasGroup->first()->promotor_id)
                                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-600 rounded-lg mb-2">
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                                        {{ $evento['titulo'] ?? 'Novo Evento' }} - {{ $evento['tipo'] ?? 'Tipo não definido' }}
                                                        <span class="ml-2 text-xs text-blue-500">(Preview)</span>
                                                    </span>
                                                    <div class="flex space-x-2">
                                                        @include('filament.pages.components.Modal.EditModalEventoTemporario', [
                                                            'index' => $index,
                                                            'evento' => $evento,
                                                            'isTemporary' => true
                                                        ])
                                                        <button wire:click="removeEventoTemporario({{ $index }})" class="text-red-600 hover:text-red-800">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-600 rounded-lg mb-2">
                                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $promotoria->evento }}</span>
                                                <div class="flex space-x-2">
                                                    @include('filament.pages.components.Modal.EditModalEvento', ['evento' => $promotoria])
                                                    <button
                                                        wire:click="deleteEvento({{ $promotoria->evento_id }})"
                                                        onclick="setTimeout(() => { location.reload(); }, 10);"
                                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-500"
                                                    >
                                                        Excluir
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                    <!-- Botão para adicionar novo evento -->
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
        @endif
        <!-- Fazer o preview dos eventos -->
        @if($previewMode)
        <div>
            @include('filament.pages.components.Preview.espelho-preview', ['xData' => '$data'])
        </div>
        @endif
         

    </div>
</x-filament::page>
<script>
    function updateEndDate() {
        const startDate = document.getElementById('periodo_inicio').value;
        const endDateInput = document.getElementById('periodo_fim');
        if (startDate) {
            endDateInput.value = startDate; 
        }
    }

    function highlightDates() {
        const startDate = document.getElementById('periodo_inicio').value;
        const endDateInput = document.getElementById('periodo_fim');
        if (startDate) {
            endDateInput.classList.add('bg-blue-100'); 
        }
    }


    function PreviewEventos() {
        console.log('PreviewEventos');
    }
</script>


