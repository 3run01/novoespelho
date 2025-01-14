<x-filament::page id="PlantaoUrgencia" width="full">
       <main class="space-y-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm w-full">
           <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden w-full">
               <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                   <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Plantao de Carater de Urgencia</h2>
                   
                   <div class="space-y-6 mb-8">
                       <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                           <div>
                               <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome do Promotor</label>
                               <select wire:model="promotor_designado" class="w-full rounded-md dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                   <option>Selecione o promotor</option>
                                   @foreach($promotorias->pluck('promotor', 'promotor_id')->unique() as $id => $nome)
                                       <option value="{{ $id }}">{{ $nome }}</option>
                                   @endforeach
                               </select>
                           </div>
                           <div class="grid grid-cols-2 gap-4">
                               <div>
                                   <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 dark:text-gray-100">Data Inicial</label>
                                   <input type="date" wire:model="periodo_inicio" class="w-full dark:bg-gray-700 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                               </div>
                               <div>
                                   <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data Final</label>
                                   <input type="date" wire:model="periodo_fim" class="w-full rounded-md dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                               </div>
                           </div>
                       </div>
                       <div class="flex space-x-4">
                           <button wire:click="adicionarPlantaoUrgente" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-700 dark:hover:bg-blue-800">
                               Adicionar ao Preview
                           </button>
                       </div>
                   </div>

                   <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                       <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Plantões em Preview</h3>
                       @if($previewModePlantao)
                           <div class="space-y-4 mb-8">
                               @foreach($plantoesTemporarios as $index => $plantao)
                                   <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 flex items-center justify-between">
                                       <div>
                                           <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                               {{ $promotorias->where('promotor_id', $plantao['promotor_designado_id'])->first()->promotor }}
                                               <span class="ml-2 text-xs text-blue-500">(Preview)</span>
                                           </h4>
                                           <p class="text-sm text-gray-500 dark:text-gray-400">
                                               {{ $plantao['periodo_inicio'] }} até {{ $plantao['periodo_fim'] }}
                                           </p>
                                       </div>
                                       <button wire:click="removePlantaoTemporario({{ $index }})" class="text-red-600 hover:text-red-800">
                                           Excluir
                                       </button>
                                   </div>
                               @endforeach
                           </div>
                       @endif

                       <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Plantões Cadastrados</h3>
                       <div class="space-y-4">
                           @foreach($plantoes as $plantao)
                               <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                   <div>
                                       @if(isset($plantao->promotor_designado_id) && $plantao->promotor_designado_id)
                                           <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $promotorias->where('promotor_id', $plantao->promotor_designado_id)->first()->promotor ?? 'Promotor não encontrado' }}</h4>
                                       @else
                                           <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Promotor não designado</h4>
                                       @endif
                                       <p class="text-sm text-gray-500 dark:text-gray-400">{{ $plantao->periodo_inicio }} até {{ $plantao->periodo_fim }}</p>
                                   </div>
                                   <button wire:click="deletePlantaoUrgente({{ $plantao->plantao_id }})" onclick="setTimeout(() => { location.reload(); }, 10);" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium transition-colors">
                                       Excluir
                                   </button>
                               </div>
                           @endforeach
                       </div>
                   </div>
               </div>
           </div>
       </main>
</x-filament::page>