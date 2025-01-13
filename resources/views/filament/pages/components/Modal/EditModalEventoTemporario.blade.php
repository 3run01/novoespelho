<x-filament::modal id="edit-temp-event-{{ $index }}" width="md">
    <x-slot name="trigger">
        <button wire:click="editEventoTemporario({{ $index }})" class="mr-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
        </button>
    </x-slot>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Tipo do Evento:</label>
            <select wire:model="tipo" class="mt-1 block w-full dark:bg-gray-700 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                <option value="">Selecione um tipo</option>
                <option value="Reunião">Reunião</option>
                <option value="licenca">Licença</option>
                <option value="ferias">Férias</option>
                <option value="outros">Outros</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Título do Evento:</label>
            <input wire:model="titulo" type="text" required 
                class="mt-1 block w-full dark:bg-gray-700 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Período Início:</label>
            <input wire:model="periodo_inicio" type="date" required 
                class="mt-1 block w-full dark:bg-gray-700 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Período Fim:</label>
            <input wire:model="periodo_fim" type="date" required 
                class="mt-1 block w-full dark:bg-gray-700 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-100">Promotor Designado:</label>
            <select wire:model="promotor_designado" required 
                class="mt-1 block w-full dark:bg-gray-700 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                <option value="">Selecione um promotor</option>
                @foreach ($promotorias->unique('promotor_id') as $item)
                    <option value="{{ $item->promotor_id }}">{{ $item->promotor }}</option>
                @endforeach
            </select>
        </div>

    </div>

    <x-slot name="footer" class="flex justify-between">
        <x-filament::button x-on:click="close">
            Cancelar
        </x-filament::button>

        <x-filament::button 
            wire:click="updateEventoPreview"
            x-on:click="close"
            wire:loading.attr="disabled"
            class="inline-flex items-center justify-center"
        >
            <span wire:loading.remove>Atualizar Preview</span>
            <span wire:loading>Atualizando...</span>
        </x-filament::button>
    </x-slot>
</x-filament::modal> 