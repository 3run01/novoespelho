<div class="w-full max-w-none px-4 sm:px-6 lg:px-8" x-data="plantaoUrgenciaData()">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Plantões de Urgência</h1>
                <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">Gerencie os plantões de urgência por município</p>
            </div>
            <div class="flex-shrink-0">
                <button 
                    wire:click="abrirModalCriar"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Novo Plantão
                </button>
            </div>
        </div>
    </div>

    <!-- Filtros e busca -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Busca por nome -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input 
                wire:model.live.debounce.300ms="termoBusca"
                type="text" 
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                placeholder="Buscar plantões..."
            >
        </div>

        <!-- Filtro por município -->
        <div>
            <select 
                wire:model.live="filtroMunicipio"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            >
                <option value="">Todos os municípios</option>
                @foreach($municipios as $municipio)
                    <option value="{{ $municipio->id }}">{{ $municipio->nome }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filtro por período -->
        <div>
            <select 
                wire:model.live="filtroPeriodo"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            >
                <option value="">Todos os períodos</option>
                @foreach($periodos as $periodo)
                    <option value="{{ $periodo->id }}">
                        {{ \Carbon\Carbon::parse($periodo->periodo_inicio)->format('d/m/Y') }} - 
                        {{ \Carbon\Carbon::parse($periodo->periodo_fim)->format('d/m/Y') }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Mensagens de feedback -->
    @if (session()->has('mensagem'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md" x-data="{ show: true }" x-show="show" x-transition>
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('mensagem') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('erro'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md" x-data="{ show: true }" x-show="show" x-transition>
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('erro') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Lista de plantões -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        @if($plantoes->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($plantoes as $plantao)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $plantao->nome ?? 'Plantão de Urgência' }}</h3>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        Urgência
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Informações básicas -->
                                    <div class="space-y-2">
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium text-gray-900">Município:</span> {{ $plantao->municipio->nome }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium text-gray-900">Período:</span> 
                                            {{ \Carbon\Carbon::parse($plantao->periodo->periodo_inicio)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($plantao->periodo->periodo_fim)->format('d/m/Y') }}
                                        </div>
                                        @if($plantao->observacoes)
                                            <div class="text-sm text-gray-600">
                                                <span class="font-medium text-gray-900">Observações:</span> {{ $plantao->observacoes }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Promotores designados -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Promotores Designados</h4>
                                        @if($plantao->promotores->count() > 0)
                                            <div class="space-y-2">
                                                @foreach($plantao->promotores as $promotor)
                                                    <div class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded-lg">
                                                        <div class="flex items-center gap-2">
                                                            <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center">
                                                                <span class="text-xs font-medium text-blue-600">
                                                                    {{ substr($promotor->nome, 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900">{{ $promotor->nome }}</span>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $promotor->pivot->tipo_designacao == 'titular' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                                {{ ucfirst($promotor->pivot->tipo_designacao) }}
                                                            </span>
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($promotor->pivot->data_inicio_designacao)->format('d/m') }} - 
                                                            {{ \Carbon\Carbon::parse($promotor->pivot->data_fim_designacao)->format('d/m') }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 italic">Nenhum promotor designado</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ações -->
                            <div class="flex items-center gap-2 ml-6">
                                <button 
                                    wire:click="abrirModalEditar({{ $plantao->id }})"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Editar
                                </button>
                                <button 
                                    wire:click="deletar({{ $plantao->id }})"
                                    wire:confirm="Tem certeza que deseja deletar este plantão de urgência?"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Deletar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum plantão de urgência encontrado</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($termoBusca || $filtroMunicipio || $filtroPeriodo)
                        Nenhum plantão encontrado com os filtros aplicados.
                    @else
                        Comece criando um novo plantão de urgência.
                    @endif
                </p>
                @if(!$termoBusca && !$filtroMunicipio && !$filtroPeriodo)
                    <div class="mt-6">
                        <button 
                            wire:click="abrirModalCriar"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Novo Plantão
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if($plantoes->hasPages())
        <div class="mt-6">
            {{ $plantoes->links() }}
        </div>
    @endif

    <!-- Modal de criação/edição -->
    @if($mostrarModal)
        <div class="fixed inset-0 overflow-y-auto" style="z-index: 9998 !important;" x-data="{ show: true }" x-show="show" x-transition>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg px-6 pt-6 pb-6 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full" x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $modoEdicao ? 'Editar Plantão de Urgência' : 'Novo Plantão de Urgência' }}
                        </h3>
                        <button 
                            wire:click="fecharModal"
                            type="button" 
                            class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1"
                        >
                            <span class="sr-only">Fechar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="w-full">
                        <div class="">
                                <form wire:submit.prevent="salvar">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Informações básicas -->
                                        <div class="space-y-4">
                                            <h4 class="text-md font-medium text-gray-900">Informações Básicas</h4>
                                            
                                            <div>
                                                <label for="periodo_id" class="block text-sm font-medium text-gray-700">Período</label>
                                                <select 
                                                    wire:model="periodo_id"
                                                    id="periodo_id"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('periodo_id') border-red-300 @enderror"
                                                >
                                                    <option value="">Selecione um período</option>
                                                    @foreach($periodos as $periodo)
                                                        <option value="{{ $periodo->id }}">
                                                            {{ \Carbon\Carbon::parse($periodo->periodo_inicio)->format('d/m/Y') }} - 
                                                            {{ \Carbon\Carbon::parse($periodo->periodo_fim)->format('d/m/Y') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('periodo_id')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="municipio_id" class="block text-sm font-medium text-gray-700">Município</label>
                                                <select 
                                                    wire:model="municipio_id"
                                                    id="municipio_id"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('municipio_id') border-red-300 @enderror"
                                                >
                                                    <option value="">Selecione um município</option>
                                                    @foreach($municipios as $municipio)
                                                        <option value="{{ $municipio->id }}">{{ $municipio->nome }}</option>
                                                    @endforeach
                                                </select>
                                                @error('municipio_id')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Plantão</label>
                                                <input 
                                                    wire:model="nome"
                                                    type="text" 
                                                    id="nome"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('nome') border-red-300 @enderror"
                                                    placeholder="Ex: Plantão de Urgência - Final de Semana"
                                                >
                                                @error('nome')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="observacoes" class="block text-sm font-medium text-gray-700">Observações</label>
                                                <textarea 
                                                    wire:model="observacoes"
                                                    id="observacoes"
                                                    rows="3"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('observacoes') border-red-300 @enderror"
                                                    placeholder="Observações sobre o plantão..."
                                                ></textarea>
                                                @error('observacoes')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Gestão de promotores -->
                                        <div class="space-y-4">
                                            <h4 class="text-md font-medium text-gray-900">Promotores Designados</h4>
                                            
                                            <!-- Formulário para adicionar promotor -->
                                            <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                                                <div class="grid grid-cols-1 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700">Promotor</label>
                                                        <select 
                                                            wire:model="promotorSelecionado"
                                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                        >
                                                            <option value="">Selecione um promotor</option>
                                                            @foreach($promotores as $promotor)
                                                                <option value="{{ $promotor->id }}">{{ $promotor->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700">Data Início</label>
                                                            <input 
                                                                wire:model="dataInicioDesignacao"
                                                                type="date" 
                                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                            >
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700">Data Fim</label>
                                                            <input 
                                                                wire:model="dataFimDesignacao"
                                                                type="date" 
                                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                            >
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700">Tipo</label>
                                                        <select 
                                                            wire:model="tipoDesignacao"
                                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                        >
                                                            <option value="titular">Titular</option>
                                                            <option value="substituto">Substituto</option>
                                                        </select>
                                                    </div>

                                                    <button 
                                                        type="button"
                                                        wire:click="adicionarPromotor"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                    >
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                        Adicionar
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Lista de promotores adicionados -->
                                            @if(count($promotoresPlantao) > 0)
                                                <div class="space-y-2">
                                                    <h5 class="text-sm font-medium text-gray-700">Promotores no plantão:</h5>
                                                    @foreach($promotoresPlantao as $index => $promotorPlantao)
                                                        <div class="flex items-center justify-between bg-white border border-gray-200 rounded-lg p-3">
                                                            <div class="flex items-center space-x-3">
                                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                                    <span class="text-xs font-medium text-blue-600">
                                                                        {{ substr($promotorPlantao['nome'], 0, 1) }}
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $promotorPlantao['nome'] }}</p>
                                                                    <p class="text-xs text-gray-500">
                                                                        {{ \Carbon\Carbon::parse($promotorPlantao['data_inicio'])->format('d/m/Y') }} - 
                                                                        {{ \Carbon\Carbon::parse($promotorPlantao['data_fim'])->format('d/m/Y') }}
                                                                        | {{ ucfirst($promotorPlantao['tipo']) }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <button 
                                                                type="button"
                                                                wire:click="removerPromotor({{ $index }})"
                                                                class="text-red-600 hover:text-red-800 focus:outline-none"
                                                            >
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-500 italic">Nenhum promotor designado ainda</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-8 flex justify-end gap-3">
                                        <button 
                                            type="button"
                                            wire:click="fecharModal"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                        >
                                            Cancelar
                                        </button>
                                        <button 
                                            type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                        >
                                            {{ $modoEdicao ? 'Atualizar' : 'Criar' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
    function plantaoUrgenciaData() {
        return {
            init() {
                this.$watch('show', value => {
                    if (!value) {
                        this.$wire.fecharModal();
                    }
                });
            }
        }
    }
    </script>
</div>