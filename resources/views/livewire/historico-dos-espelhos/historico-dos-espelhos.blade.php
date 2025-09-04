<div class="flex mt-[35px]">
    <div class="w-[200px] flex-shrink-0"></div>
    <div class="flex-1">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Histórico dos Períodos</h2>
            <p class="mt-1 text-sm text-gray-600">Visualize todos os períodos e seus status no sistema</p>
        </div>
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
                    placeholder="Buscar períodos...">
            </div>

            <!-- Filtro por status do período -->
            <div>
                <select wire:model.live="filtroStatus"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Todos os status</option>
                    <option value="em_processo_publicacao">Em Processo de Publicação</option>
                    <option value="publicado">Publicado</option>
                    <option value="arquivado">Arquivado</option>
                </select>
            </div>

            <!-- Filtro por período -->
            <div>
                <select wire:model.live="filtroPeriodo"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Todos os períodos</option>
                    @foreach ($this->periodos as $periodo)
                        <option value="{{ $periodo->id }}">
                            {{ \Carbon\Carbon::parse($periodo->periodo_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($periodo->periodo_fim)->format('d/m/Y') }}
                        </option>
                    @endforeach
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

    <!-- Tabela de períodos -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if ($this->espelhos->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach ($this->espelhos as $periodo)
                    <li class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        Período {{ \Carbon\Carbon::parse($periodo->periodo_inicio)->format('m/Y') }}
                                    </div>
                                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                                        <!-- Status do período -->
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $this->getStatusPeriodoColor($periodo->status) }}">
                                            {{ $this->getStatusPeriodoLabel($periodo->status) }}
                                        </span>
                                        
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $periodo->eventos_count }} eventos</span>
                                        
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $periodo->espelhos_count }} espelhos</span>
                                    </div>
                                    
                                    <!-- Período -->
                                    <div class="mt-1 text-xs text-gray-500">
                                        <strong>Período:</strong> 
                                        {{ \Carbon\Carbon::parse($periodo->periodo_inicio)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($periodo->periodo_fim)->format('d/m/Y') }}
                                    </div>
                                    
                                    <!-- Data de criação -->
                                    <div class="mt-1 text-xs text-gray-400">
                                        Criado em: {{ $periodo->created_at ? $periodo->created_at->format('d/m/Y H:i') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('espelho.pdf.visualizar') }}?periodo_id={{ $periodo->id }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Visualizar
                                </a>
                                
                                <button wire:click="gerarPdf({{ $periodo->id }})"
                                    class="inline-flex items-center px-3 py-1.5 border border-green-300 shadow-sm text-xs font-medium rounded text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    PDF
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
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum período encontrado</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if ($termoBusca || $filtroStatus || $filtroPeriodo)
                        Nenhum período encontrado com os filtros aplicados.
                    @else
                        Ainda não há períodos criados no sistema.
                    @endif
                </p>
                @if (!$termoBusca && !$filtroStatus && !$filtroPeriodo)
                    <div class="mt-6">
                        <a href="{{ route('gestao-espelho') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Criar Primeiro Período
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if ($this->espelhos->hasPages())
        <div class="mt-6">
            <nav role="navigation" aria-label="Navegação de Páginas" class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">
                        Mostrando <span class="font-medium text-gray-700">{{ $this->espelhos->firstItem() }}</span>
                        até
                        <span class="font-medium text-gray-700">{{ $this->espelhos->lastItem() }}</span> de <span
                            class="font-medium text-gray-700">{{ $this->espelhos->total() }}</span> resultados
                    </p>
                </div>
                <div>
                    <span class="relative z-0 inline-flex rounded-lg">
                        @if ($this->espelhos->onFirstPage())
                            <span
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-default rounded-l-lg">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $this->espelhos->previousPageUrl() }}"
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-50 hover:text-gray-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif

                        @foreach ($this->espelhos->getUrlRange(1, $this->espelhos->lastPage()) as $page => $url)
                            @if ($page == $this->espelhos->currentPage())
                                <span
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 cursor-default">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-400">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($this->espelhos->hasMorePages())
                            <a href="{{ $this->espelhos->nextPageUrl() }}"
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
</div>
