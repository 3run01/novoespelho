<x-filament::modal class="z-50" id="add-event-{{ $promotoria->municipio }}" width="md">
    <x-slot name="trigger">
        <button wire:click="setPromotorTitular({{ $promotoria->promotor_id }}, {{ $promotoria->promotoria_id }})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Adicionar Evento
        </button>
    </x-slot>

    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Tipo do Evento:</label>
                <select wire:model="tipo" class="mt-1 block w-full dark:bg-gray-700 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                    <option value="">Selecione um tipo</option>
                    <option value="Reunião">Reunião</option>
                    <option value="Licença">Licença</option>
                    <option value="Férias">Férias</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-100  dark:bg-gray-700">Título do Evento:</label>
                <input wire:model="titulo" type="text" required class="mt-1 block w-full  dark:bg-gray-700 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700  dark:text-gray-100 ">Período Início:</label>
            <input wire:model="periodo_inicio" type="date" required class="mt-1  dark:bg-gray-700 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Período Fim:</label>
            <input wire:model="periodo_fim" type="date" required class="mt-1  dark:bg-gray-700 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Promotor Titular:</label>
            <input type="text" disabled value="{{ $promotoria->promotor }}" class="mt-1  dark:bg-gray-700 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Promotor Designado:</label>
            <select wire:model="promotor_designado" required class="mt-1   dark:bg-gray-700 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                <option value="">Selecione um promotor</option>
                @foreach ($promotorias->unique('promotor_id') as $item)
                    <option value="{{ $item->promotor_id }}">{{ $item->promotor }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Promotoria:</label>
            <input type="text" disabled value="{{ $promotoria->promotoria }}" class="mt-1  dark:bg-gray-700 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <input type="hidden" wire:model="promotoria_id" value="{{ $promotoria->promotoria_id }}">
    </div>

    <x-slot name="footer" class="flex justify-between">
        <x-filament::button x-on:click="close">
            Cancelar
        </x-filament::button>

        <x-filament::button wire:click="salvarEvento">
            {{ $previewMode ? 'Adicionar ao Preview' : 'Salvar' }}
        </x-filament::button>
    </x-slot>
</x-filament::modal>