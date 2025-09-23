<div class="flex mt-[35px] ml-[125px]">
    <div class="flex-1">
        <!-- Header com título e botão de criar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Promotorias</h2>
                <p class="mt-1 text-sm text-gray-600">Gerencie as promotorias e seus promotores titulares</p>
            </div>
            <button wire:click="abrirModalCriar"
                class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nova Promotoria
            </button>
        </div>

        <!-- Barra de busca e filtros -->
        <div class="mb-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                        placeholder="Buscar por nome da promotoria...">
                </div>

                <!-- Filtro por grupo -->
                <div>
                    <select wire:model.live="filtroGrupo"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Todos os grupos</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por promotor -->
                <div>
                    <select wire:model.live="filtroPromotor"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Todos os promotores</option>
                        @foreach ($promotores as $promotor)
                            <option value="{{ $promotor->id }}">{{ $promotor->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botão limpar filtros -->
                <div>
                    <button wire:click="limparFiltros"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        Limpar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Mensagens de feedback -->
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

        <!-- Tabela de promotorias -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            @if ($this->promotorias->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach ($this->promotorias as $promotoria)
                        <li class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-lg font-semibold text-gray-900">{{ $promotoria->nome }}</div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            {{ $promotoria->promotorTitular->nome ?? 'Promotor não definido' }}</div>
                                        <div class="flex items-center space-x-2 mt-2 text-sm text-gray-500">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $promotoria->grupoPromotoria->nome ?? 'Grupo não definido' }}
                                            </span>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $promotoria->grupoPromotoria->municipio->nome ?? 'Município não definido' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button wire:click="abrirModalEditar({{ $promotoria->id }})"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Editar
                                    </button>
                                    <button wire:click="deletar({{ $promotoria->id }})"
                                        wire:confirm="Tem certeza que deseja deletar esta promotoria?"
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
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma promotoria encontrada</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if ($termoBusca || $filtroGrupo || $filtroPromotor)
                            Nenhuma promotoria encontrada com os filtros aplicados.
                        @else
                            Comece criando uma nova promotoria.
                        @endif
                    </p>
                    @if (!$termoBusca && !$filtroGrupo && !$filtroPromotor)
                        <div class="mt-6">
                            <button wire:click="abrirModalCriar"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nova Promotoria
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Paginação -->
        @if ($this->promotorias->hasPages())
            <div class="mt-6">
                <nav role="navigation" aria-label="Navegação de Páginas" class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Mostrando <span
                                class="font-medium text-gray-700">{{ $this->promotorias->firstItem() }}</span>
                            até
                            <span class="font-medium text-gray-700">{{ $this->promotorias->lastItem() }}</span> de
                            <span class="font-medium text-gray-700">{{ $this->promotorias->total() }}</span>
                            resultados
                        </p>
                    </div>
                    <div>
                        <span class="relative z-0 inline-flex rounded-lg">
                            @if ($this->promotorias->onFirstPage())
                                <span
                                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-default rounded-l-lg">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $this->promotorias->previousPageUrl() }}"
                                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-50 hover:text-gray-400">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif

                            @foreach ($this->promotorias->getUrlRange(1, $this->promotorias->lastPage()) as $page => $url)
                                @if ($page == $this->promotorias->currentPage())
                                    <span
                                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 cursor-default">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-400">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($this->promotorias->hasMorePages())
                                <a href="{{ $this->promotorias->nextPageUrl() }}"
                                    class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-r-lg hover:bg-gray-50 hover:text-gray-400">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @else
                                <span
                                    class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-default rounded-r-lg">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </span>
                    </div>
                </nav>
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
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $modoEdicao ? 'Editar Promotoria' : 'Nova Promotoria' }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $modoEdicao ? 'Atualize as informações da promotoria' : 'Preencha os dados para criar uma nova promotoria' }}
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
                                <!-- Nome da Promotoria -->
                                <div>
                                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nome da Promotoria <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="nome" type="text" id="nome"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('nome') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                        placeholder="Ex: 1ª Promotoria de Justiça Cível">
                                    @error('nome')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Grid para Promotor e Grupo -->
                                <div x-data="{
                                    promotorSelecionado: @entangle('promotor_id').live
                                }">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="promotor_id"
                                                class="block text-sm font-medium text-gray-700 mb-1">
                                                Membro Titular
                                            </label>
                                            <select wire:model.live="promotor_id" x-model="promotorSelecionado"
                                                id="promotor_id"
                                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('promotor_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                                <option value="">Selecione uma opção</option>
                                                <option value="sem_titular">Não tem titularidade</option>
                                                @foreach ($promotores as $promotor)
                                                    <option value="{{ $promotor->id }}">{{ $promotor->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('promotor_id')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="grupo_promotoria_id"
                                                class="block text-sm font-medium text-gray-700 mb-1">
                                                Grupo de Promotoria <span class="text-red-500">*</span>
                                            </label>
                                            <select wire:model="grupo_promotoria_id" id="grupo_promotoria_id"
                                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('grupo_promotoria_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                                <option value="">Selecione um grupo</option>
                                                @foreach ($grupos as $grupo)
                                                    <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                                                @endforeach
                                            </select>
                                            @error('grupo_promotoria_id')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Campos de Titularidade -->
                                    <div x-show="promotorSelecionado && promotorSelecionado !== 'sem_titular' && promotorSelecionado !== ''"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                        x-transition:leave-end="opacity-0 transform translate-y-2" class="mt-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="titularidade_promotor_data_inicio"
                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                    Data de Início da Titularidade <span
                                                        class="text-gray-400 font-normal">(opcional)</span>
                                                </label>
                                                <input wire:model="titularidade_promotor_data_inicio" type="date"
                                                    id="titularidade_promotor_data_inicio"
                                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('titularidade_promotor_data_inicio') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                                @error('titularidade_promotor_data_inicio')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="titularidade_promotor_data_fim"
                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                    Data Fim da Titularidade <span
                                                        class="text-gray-400 font-normal">(opcional)</span>
                                                </label>
                                                <input wire:model="titularidade_promotor_data_final" type="date"
                                                    id="titularidade_promotor_data_fim"
                                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('titularidade_promotor_data_fim') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                                @error('titularidade_promotor_data_fim')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- N da Portaria em linha separada -->
                                        <div class="mt-4">
                                            <label for="data_pga"
                                                class="block text-sm font-medium text-gray-700 mb-1">
                                                N da Portaria <span class="text-gray-400 font-normal">(opcional)</span>
                                            </label>
                                            <input wire:model="data_pga" type="text" id="data_pga"
                                                placeholder="Número do PGA"
                                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('data_pga') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('data_pga')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Vacância -->
                                    <div x-show="promotorSelecionado === 'sem_titular'"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                        x-transition:leave-end="opacity-0 transform translate-y-2" class="mt-3">
                                        <label for="vacancia_data_inicio"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Data de Início da Vacância <span
                                                class="text-gray-400 font-normal">(opcional)</span>
                                        </label>
                                        <input wire:model="vacancia_data_inicio" type="date"
                                            id="vacancia_data_inicio"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('vacancia_data_inicio') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('vacancia_data_inicio')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Competência -->
                                <div>
                                    <label for="competencia" class="block text-sm font-medium text-gray-700 mb-1">
                                        Competência <span class="text-gray-400 font-normal">(opcional)</span>
                                    </label>
                                    <textarea wire:model="competencia" id="competencia" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('competencia') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                        placeholder="Ex: 1ª, 2ª, 3ª Cíveis e de Fazenda Pública"></textarea>
                                    @error('competencia')
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
                                        {{ $modoEdicao ? 'Atualizar' : 'Criar' }} Promotoria
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
