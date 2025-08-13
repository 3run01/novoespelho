<x-filament-panels::page>
    <div class="mb-6 flex gap-4">
        <input type="text" wire:model.live="search" placeholder="Buscar..." class="block w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600">
        
        <select wire:model.live="filter" class="rounded-lg shadow-sm border-gray-300 dark:border-gray-600">
            <option value="">Todos</option>
            <option value="created">Eventos Criados</option>
            <option value="deleted">Eventos Excluídos</option>
            <option value="updated">Eventos Atualizados</option>
        </select>
    </div>

    <div class="space-y-6">
        @foreach($logs as $log)
            <div class="p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 border {{ str_contains($log->action, 'Criou um novo evento') ? 'bg-green-50 border-green-100' : (str_contains($log->action, 'Excluiu') ? 'bg-red-50 border-red-100' : (str_contains($log->action, 'Atualizou') ? 'bg-blue-50 border-blue-100' : 'bg-white border-gray-100')) }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 {{ str_contains($log->action, 'Criou um novo evento') ? 'bg-green-100' : (str_contains($log->action, 'Excluiu') ? 'bg-red-100' : (str_contains($log->action, 'Atualizou') ? 'bg-blue-100' : 'bg-primary-100')) }} rounded-full flex items-center justify-center">
                            <span class="{{ str_contains($log->action, 'Criou um novo evento') ? 'text-green-700' : (str_contains($log->action, 'Excluiu') ? 'text-red-700' : (str_contains($log->action, 'Atualizou') ? 'text-blue-700' : 'text-primary-700')) }} font-semibold text-lg">{{ substr($log->nome_usuario, 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $log->nome_usuario }}</div>
                            <div class="text-sm {{ str_contains($log->action, 'Criou um novo evento') ? 'text-green-600' : (str_contains($log->action, 'Excluiu') ? 'text-red-600' : (str_contains($log->action, 'Atualizou') ? 'text-blue-600' : 'text-gray-600')) }}">{{ $log->action }}</div>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 bg-gray-50 px-3 py-1 rounded-full">
                        {{ $log->modificado }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>