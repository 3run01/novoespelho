<x-filament::page class="bg-gray-50 dark:bg-gray-900">
    <div x-data="{ 
        previewMode: @entangle('previewMode'),
        init() {
            $watch('previewMode', value => console.log('Preview mode:', value))
        }
    }" class="flex flex-col items-center space-y-8 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Cabeçalho com Toggle e Botão PDF -->
        <div class="w-full flex justify-between items-center mt-6">
            <!-- Toggle Preview -->
            <div class="relative inline-flex max-w-xs">
                <div class="h-12 bg-white dark:bg-gray-800 rounded-2xl p-1 flex shadow-lg">
                    <button 
                        wire:click="$set('previewMode', false)"
                        class="flex-1 relative z-10 flex items-center justify-center text-sm font-medium transition-all duration-300 ease-in-out rounded-xl px-6"
                        :class="!previewMode ? 'text-white bg-primary-600 shadow-sm transform scale-[1.02]' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'"
                    >
                        <span class="flex items-center gap-2">
                            <x-heroicon-s-pencil-square class="w-4 h-4" />
                            Principal
                        </span>
                    </button>
                    <button 
                        wire:click="togglePreview"
                        class="flex-1 relative z-10 flex items-center justify-center text-sm font-medium transition-all duration-300 ease-in-out rounded-xl px-6"
                        :class="previewMode ? 'text-white bg-primary-600 shadow-sm transform scale-[1.02]' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'"
                    >
                        <span class="flex items-center gap-2">
                            <x-heroicon-s-eye class="w-4 h-4" />
                            Preview
                        </span>
                    </button>
                </div>
            </div>

            <!-- Botão Exportar PDF -->
            <a 
                href="{{ route('download-pdf') }}"
                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl transition-colors duration-200 shadow-lg"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exportar Espelho
            </a>
        </div>

        <div class="w-full space-y-8">
            @if($this->hasAlteracoesPendentes())
                <div class="fixed bottom-4 right-4 bg-transparent">
                    <x-filament::button
                        color="success"
                        size="lg"
                        wire:click="confirmarAlteracoes"
                        class="shadow-lg hover:shadow-xl transition-shadow duration-200"
                    >
                        <span class="flex items-center gap-2">
                            <x-heroicon-s-check class="w-5 h-5" />
                            Salvar Todas as Alterações
                        </span>
                    </x-filament::button>
                </div>
            @endif

            @if(!$previewMode)
                <!-- Seção de Período -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="max-w-md mx-auto">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Adicionar Período</h3>
                        
                        <!-- Adicionando informação do último período -->
                        @if($ultimoPeriodo)
                            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Último período cadastrado:</span> 
                                    {{ \Carbon\Carbon::parse($ultimoPeriodo->periodo_inicio)->format('d/m/Y') }} 
                                    até 
                                    {{ \Carbon\Carbon::parse($ultimoPeriodo->periodo_fim)->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Inicial</label>
                                <input 
                                    type="date" 
                                    wire:model="novo_periodo_inicio" 
                                    required 
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 transition-colors duration-200">
                                @error('novo_periodo_inicio')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Final</label>
                                <input 
                                    type="date" 
                                    wire:model="novo_periodo_fim" 
                                    required 
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 transition-colors duration-200">
                                @error('novo_periodo_fim')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <button 
                                wire:click="adicionarPeriodoTemporario"
                                wire:loading.attr="disabled"
                                wire:target="adicionarPeriodoTemporario"
                                class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span wire:loading.remove wire:target="adicionarPeriodoTemporario">
                                    Adicionar Período
                                </span>
                                <span wire:loading wire:target="adicionarPeriodoTemporario" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Adicionando...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Plantão de Urgência -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    @include('filament.pages.components.PlantaoUrgencia.PlantaoUrgencia', ['plantoes' => $this->plantoes])
                </div>

                <!-- Seção de Eventos -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Eventos</h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Município</th>
                                    <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grupo Promotoria</th>
                                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Promotoria</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Membro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Eventos</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach ($promotorias->groupBy('promotor_id') as $promotoriasGroup)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $promotoriasGroup->first()->municipio }}
                                    </td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-normal text-sm text-gray-500 dark:text-gray-400">
                                        {{ $promotoriasGroup->first()->grupo_promotoria }}
                                    </td>
                                    <td class="hidden md:table-cell px-6 py-4 whitespace-normal text-sm text-gray-500 dark:text-gray-400">
                                        {{ $promotoriasGroup->first()->promotoria }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-500 dark:text-gray-400">
                                        {{ $promotoriasGroup->first()->promotor }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($promotoriasGroup->isEmpty())
                                            <button
                                                wire:click="addEvento({{ $promotoriasGroup->first()->promotor_id }})"
                                                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                            >
                                                Adicionar <span class="ml-1">+</span>
                                            </button>
                                        @else
                                            <!-- Eventos Temporários -->
                                            @if($this->hasEventosTemporarios())
                                                @foreach ($eventosTemporarios as $index => $evento)
                                                    @if(isset($evento['promotor_id']) && $evento['promotor_id'] == $promotoriasGroup->first()->promotor_id)
                                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-3">
                                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                                {{ $evento['titulo'] ?? 'Novo Evento' }} - {{ $evento['tipo'] ?? 'Tipo não definido' }}
                                                                <span class="ml-2 text-xs text-primary-500">(Preview)</span>
                                                            </span>
                                                            <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                                                                @include('filament.pages.components.Modal.EditModalEventoTemporario', [
                                                                    'index' => $index,
                                                                    'evento' => $evento,
                                                                    'isTemporary' => true
                                                                ])
                                                                <button wire:click="removeEventoTemporario({{ $index }})" 
                                                                    class="text-red-600 hover:text-red-800 p-2">
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
                                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-3">
                                                        <span class="text-sm text-gray-700 dark:text-gray-300">
                                                            {{ $promotoria->evento }}
                                                        </span>
                                                        <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                                                            @include('filament.pages.components.Modal.EditModalEvento', ['evento' => $promotoria])
                                                            <button wire:click="deleteEvento({{ $promotoria->evento_id }})" 
                                                                class="text-red-600 hover:text-red-800 p-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                            <div class="mt-3">
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

            <!-- Preview Mode -->
            @if($previewMode)
                <div class="space-y-8 w-full max-w-none">
                    <!-- Período Preview -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-none">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Período Selecionado</h3>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                @if(!empty($periodosTemporarios))
                                    @foreach($periodosTemporarios as $periodo)
                                        De: {{ \Carbon\Carbon::parse($periodo['periodo_inicio'])->format('d/m/Y') }}
                                        - Até: {{ \Carbon\Carbon::parse($periodo['periodo_fim'])->format('d/m/Y') }}
                                        <span class="ml-2 text-xs text-primary-500">(Preview)</span>
                                        @if(!$loop->last)
                                            <br>
                                        @endif
                                    @endforeach
                                @else
                                    Nenhum período selecionado
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Plantões Preview -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-none">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Plantões de Atendimento Emergenciais</h3>
                        <div class="space-y-4">
                            @if($plantoesTemporarios && count($plantoesTemporarios) > 0)
                                @foreach($plantoesTemporarios as $plantao)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="space-y-2">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                Promotor Designado: {{ $promotorias->where('promotor_id', $plantao['promotor_designado_id'])->first()->promotor ?? 'Não definido' }}
                                                <span class="ml-2 text-xs text-primary-500">(Preview)</span>
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Período: {{ \Carbon\Carbon::parse($plantao['periodo_inicio'])->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($plantao['periodo_fim'])->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum plantão emergencial em preview.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Eventos Preview -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden w-full max-w-none">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Preview dos Eventos</h2>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Município</th>
                                        <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grupo Promotoria</th>
                                        <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Promotoria</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Membro</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Eventos Previstos</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach ($promotorias->groupBy('promotor_id') as $promotoriasGroup)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $promotoriasGroup->first()->municipio }}
                                        </td>
                                        <td class="hidden sm:table-cell px-6 py-4 whitespace-normal text-sm text-gray-500 dark:text-gray-400">
                                            {{ $promotoriasGroup->first()->grupo_promotoria }}
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-4 whitespace-normal text-sm text-gray-500 dark:text-gray-400">
                                            {{ $promotoriasGroup->first()->promotoria }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-500 dark:text-gray-400">
                                            {{ $promotoriasGroup->first()->promotor }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($this->hasEventosTemporarios())
                                                @foreach ($eventosTemporarios as $evento)
                                                    @if(isset($evento['promotor_id']) && $evento['promotor_id'] == $promotoriasGroup->first()->promotor_id)
                                                        <div class="mb-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                            <div class="space-y-2">
                                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $evento['titulo'] }}
                                                                    <span class="ml-2 text-xs text-primary-500">(Preview)</span>
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
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif

                                            @foreach ($promotoriasGroup as $promotoria)
                                                @if($promotoria->evento)
                                                    <div class="mb-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">
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
            @endif
        </div>
    </div>
</x-filament::page>