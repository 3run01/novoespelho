<div class="ml-44 mt-24 w-full max-w-7xl p-4 sm:p-6 bg-white rounded-lg shadow-sm" x-data="{
    mostrarModal: @entangle('mostrarModal'),
    modoEdicao: @entangle('modoEdicao'),
    zonaEleitoral: @entangle('zona_eleitoral').live
}">
    <!-- Header com título e botão de criar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Promotores</h2>
            <p class="mt-1 text-sm text-gray-600">Gerencie os promotores do sistema</p>
        </div>
        <button wire:click="abrirModalCriar"
            class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Promotor
        </button>
    </div>

    <!-- Barra de busca e filtros -->
    <div class="mb-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Busca por nome -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="termoBusca" type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="Buscar promotores...">
            </div>

            <!-- Filtro por tipo -->
            <div>
                <select wire:model.live="filtroTipo"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Todos os tipos</option>
                    <option value="titular">Titular</option>
                    <option value="substituto">Substituto</option>

                </select>
            </div>

            <div>
                <button wire:click="limparFiltros"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Limpar Filtros
                </button>
            </div>
        </div>
    </div>

    @if (session()->has('mensagem'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md" x-data="{ show: true }" x-show="show"
            x-transition>
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('mensagem') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false"
                            class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('erro'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md" x-data="{ show: true }" x-show="show"
            x-transition>
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('erro') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false"
                            class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabela de promotores -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if ($this->promotores->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach ($this->promotores as $promotor)
                    <li class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $promotor->nome }}</div>
                                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($promotor->tipo) }}
                                        </span>
                                        @if ($promotor->is_substituto)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Substituto
                                            </span>
                                        @endif
                                        @if ($promotor->zona_eleitoral)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Zona Eleitoral {{ $promotor->numero_da_zona_eleitoral }}
                                            </span>
                                        @endif
                                        @php
                                            $cargosLista = is_array($promotor->cargos ?? null) ? $promotor->cargos : [];
                                        @endphp
                                        @if (!empty($cargosLista))
                                            <span class="text-gray-400">•</span>
                                            <span>{{ implode(', ', $cargosLista) }}</span>
                                        @endif
                                    </div>
                                    @if ($promotor->observacoes)
                                        <div class="mt-1 text-xs text-gray-400">
                                            {{ Str::limit($promotor->observacoes, 50) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button wire:click="abrirModalEditar({{ $promotor->id }})"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Editar
                                </button>
                                <button wire:click="deletar({{ $promotor->id }})"
                                    wire:confirm="Tem certeza que deseja deletar este promotor?"
                                    class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Deletar
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum promotor encontrado</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if ($termoBusca || $filtroTipo)
                        Nenhum promotor encontrado com os filtros aplicados.
                    @else
                        Comece criando um novo promotor.
                    @endif
                </p>
                @if (!$termoBusca && !$filtroTipo)
                    <div class="mt-6">
                        <button wire:click="abrirModalCriar"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Novo Promotor
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if ($this->promotores->hasPages())
        <div class="mt-6">
            {{ $this->promotores->links() }}
        </div>
    @endif

    <!-- Modal de criação/edição -->
    @if ($mostrarModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: true }" x-show="show" x-transition>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" x-show="show"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6"
                    x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <!-- Header do Modal -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $modoEdicao ? 'Editar Promotor' : 'Novo Promotor' }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $modoEdicao ? 'Atualize as informações do promotor' : 'Preencha os dados para criar um novo promotor' }}
                                </p>
                            </div>
                        </div>
                        <button wire:click="fecharModal" type="button"
                            class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="sr-only">Fechar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Formulário -->
                    <div class="mt-6">
                        <form wire:submit.prevent="salvar" class="space-y-6">
                            <!-- Nome -->
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nome do Promotor <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="nome" type="text" id="nome"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('nome') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Ex: Dr. João da Silva Santos">
                                @error('nome')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Grid para Cargo e Tipo -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Cargos (chips) -->
                                <div>
                                    <label for="novoCargo" class="block text-sm font-medium text-gray-700 mb-1">
                                        Cargos
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input wire:model.live="novoCargo" type="text" id="novoCargo"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('novoCargo') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                            placeholder="Digite um cargo e pressione Enter"
                                            wire:keydown.enter.prevent="addCargo" />
                                        <button type="button" wire:click="addCargo" title="Adicionar cargo"
                                            @if(empty($novoCargo)) disabled @endif
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center justify-center h-9 w-9 bg-blue-600 disabled:bg-blue-300 disabled:cursor-not-allowed text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 5v14M5 12h14" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('novoCargo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @error('cargos')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <!-- Lista de chips -->
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @forelse ($cargos as $i => $cargo)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $cargo }}
                                                <button type="button" wire:click="removeCargo({{ $i }})" title="Remover"
                                                    class="ml-1 text-blue-700 hover:text-blue-900 focus:outline-none">
                                                    &times;
                                                </button>
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400">Nenhum cargo adicionado</span>
                                        @endforelse
                                    </div>
                                    <!-- Sem limite de cargos e não obrigatório -->
                                </div>

                                <!-- Tipo -->
                                <div>
                                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tipo <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="tipo" id="tipo"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tipo') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="titular">Titular</option>
                                        <option value="substituto">Substituto</option>

                                    </select>
                                    @error('tipo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Checkbox Zona Eleitoral -->
                            <div>
                                <label class="flex items-center">
                                    <input wire:model="zona_eleitoral" type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Este promotor possui zona eleitoral</span>
                                </label>
                                @error('zona_eleitoral')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>



                            <!-- Campos de Zona Eleitoral (aparecem condicionalmente) -->
                            <div x-show="zonaEleitoral" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform translate-y-2" class="space-y-6">

                                <!-- Número da Zona Eleitoral -->
                                <div>
                                    <label for="numero_da_zona_eleitoral"
                                        class="block text-sm font-medium text-gray-700 mb-1">
                                        Número da Zona Eleitoral <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="numero_da_zona_eleitoral" type="text"
                                        id="numero_da_zona_eleitoral"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('numero_da_zona_eleitoral') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                        placeholder="Ex: 001, 002, 003">
                                    @error('numero_da_zona_eleitoral')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Grid para Datas -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Data de Início -->
                                    <div>
                                        <label for="periodo_inicio"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Data de Início
                                        </label>
                                        <input wire:model="periodo_inicio" type="date" id="periodo_inicio"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('periodo_inicio') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('periodo_inicio')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Data de Fim -->
                                    <div>
                                        <label for="periodo_fim" class="block text-sm font-medium text-gray-700 mb-1">
                                            Data de Fim
                                        </label>
                                        <input wire:model="periodo_fim" type="date" id="periodo_fim"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('periodo_fim') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('periodo_fim')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Observações -->
                            <div>
                                <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Observações
                                </label>
                                <textarea wire:model="observacoes" id="observacoes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('observacoes') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Observações adicionais sobre o promotor"></textarea>
                                @error('observacoes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Botões de Ação -->
                            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                                <button type="button" wire:click="fecharModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ $modoEdicao ? 'Atualizar' : 'Criar' }} Promotor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
