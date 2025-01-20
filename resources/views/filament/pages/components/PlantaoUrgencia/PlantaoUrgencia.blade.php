<x-filament::page id="PlantaoUrgencia" width="full">
    <main class="space-y-4 p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm w-full">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow w-full">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100 mb-4">Plantao de Carater de Urgencia</h2>
                
                <div class="space-y-4 mb-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome do Membro</label>
                            <select wire:model="plantao_promotor_designado" required class="w-full rounded-md dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecione o membro</option>
                                @foreach($promotorias->pluck('promotor', 'promotor_id')->unique() as $id => $nome)
                                    <option value="{{ $id }}">{{ $nome }}</option>
                                @endforeach
                            </select>
                            @error('plantao_promotor_designado')
                                <span class="text-red-500 text-sm">O campo "Membro" é obrigatório.</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-2 lg:col-span-1">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Inicial</label>
                                <input 
                                    type="date" 
                                    wire:model="plantao_periodo_inicio" 
                                    required 
                                    placeholder="{{ $ultimoPeriodo ? \Carbon\Carbon::parse($ultimoPeriodo->periodo_inicio)->format('Y-m-d') : '' }}"
                                    class="w-full dark:bg-gray-700 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('plantao_periodo_inicio')
                                    <span class="text-red-500 text-sm">O campo "Data Inicial" é obrigatório.</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Final</label>
                                <input 
                                    type="date" 
                                    wire:model="plantao_periodo_fim" 
                                    required 
                                    placeholder="{{ $ultimoPeriodo ? \Carbon\Carbon::parse($ultimoPeriodo->periodo_fim)->format('Y-m-d') : '' }}"
                                    class="w-full rounded-md dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('plantao_periodo_fim')
                                    <span class="text-red-500 text-sm">O campo "Data Final" é obrigatório.</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        @if($editando)
                            <button wire:click="atualizarPlantaoUrgente" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-700 dark:hover:bg-green-800">
                                Atualizar
                            </button>
                            <button wire:click="cancelarEdicao" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                Cancelar
                            </button>
                        @else
                            <button wire:click="adicionarPlantaoUrgente" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                                <span>Adicionar ao Preview</span>
                                <svg wire:loading wire:target="adicionarPlantaoUrgente" class="animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Plantões em Preview</h3>
                            @if($previewModePlantao)
                                <div class="space-y-2">
                                    @foreach($plantoesTemporarios as $index => $plantao)
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $promotorias->where('promotor_id', $plantao['promotor_designado_id'])->first()->promotor }}
                                                    <span class="ml-2 text-xs text-blue-500">(Preview)</span>
                                                </h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $plantao['periodo_inicio'] }} até {{ $plantao['periodo_fim'] }}
                                                </p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button wire:click="editarPlantaoTemporario({{ $index }})" class="text-blue-600 hover:text-blue-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </button>
                                                <button wire:click="removePlantaoTemporario({{ $index }})" class="text-red-600 hover:text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Plantões Cadastrados</h3>
                            <div class="space-y-2">
                                @foreach($plantoes as $plantao)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <div>
                                            @if(isset($plantao->promotor_designado_id) && $plantao->promotor_designado_id)
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $promotorias->where('promotor_id', $plantao->promotor_designado_id)->first()->promotor ?? 'Promotor não encontrado' }}</h4>
                                            @else
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Promotor não designado</h4>
                                            @endif
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $plantao->periodo_inicio }} até {{ $plantao->periodo_fim }}</p>
                                        </div>
                                        <button 
                                            wire:click="deletePlantaoUrgente({{ $plantao->plantao_id }})" 
                                            class="text-red-600 hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-filament::page>