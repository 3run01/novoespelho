<x-filament::modal id="add-event-{{ $promotoria->municipio }}" width="md">
    <x-slot name="trigger">
        <x-filament::button wire:click="setPromotorTitular({{ $promotoria->promotor_id }}, {{ $promotoria->promotoria_id }})">
            Adicionar Evento
        </x-filament::button>
    </x-slot>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo do Evento:</label>
            <select wire:model="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                <option value="">Selecione um tipo</option>
                <option value="Reunião">Reunião</option>
                <option value="licenca">Licença</option>
                <option value="ferias">Férias</option>
                <option value="outros">Outros</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Título do Evento:</label>
            <input wire:model="titulo" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Período Início:</label>
            <input wire:model="periodo_inicio" type="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Período Fim:</label>
            <input wire:model="periodo_fim" type="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Promotor Titular:</label>
            <input type="text" disabled value="{{ $promotoria->promotor }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Promotor Designado:</label>
            <select wire:model="promotor_designado" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                <option value="">Selecione um promotor</option>
                @foreach ($promotorias as $item)
                    <option value="{{ $item->promotor_id }}">{{ $item->promotor }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Promotoria:</label>
            <input type="text" disabled value="{{ $promotoria->promotoria }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
        </div>

        <input type="hidden" wire:model="promotoria_id" value="{{ $promotoria->promotoria_id }}">
    </div>

    <x-slot name="footer" class="flex justify-between">
        <x-filament::button x-on:click="close">
            Cancelar
        </x-filament::button>

        <x-filament::button wire:click="salvarEvento">
            Salvar
        </x-filament::button>
    </x-slot>
</x-filament::modal>