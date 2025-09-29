<div class="w-full max-w-none px-4 sm:px-6 lg:px-8" x-data="{}">
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Períodos</h1>
                <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">Gerencie os períodos de início e fim dos
                    espelhos</p>
            </div>
            <div class="flex-shrink-0">
                <button wire:click="abrirModalCriar"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Novo Período
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

    <!-- Lista de períodos -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        @if ($this->periodos->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach ($this->periodos as $periodo)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Período: {{ $periodo->periodo_inicio->format('d/m/Y') }} -
                                        {{ $periodo->periodo_fim->format('d/m/Y') }}
                                    </h3>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $periodo->periodo_inicio->diffInDays($periodo->periodo_fim) }} dias
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <div class="text-sm text-gray-600">
                                                <span class="font-medium text-gray-900">Data de Início:</span>
                                                <div class="mt-1">{{ $periodo->periodo_inicio->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                <span class="font-medium text-gray-900">Data de Fim:</span>
                                                <div class="mt-1">{{ $periodo->periodo_fim->format('d/m/Y') }}</div>
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                <span class="font-medium text-gray-900">Duração:</span>
                                                <div class="mt-1">
                                                    {{ $periodo->periodo_inicio->diffInDays($periodo->periodo_fim) }}
                                                    dias</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col justify-center">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Status</h4>
                                        @php
                                            $statusConfig = [
                                                'em_processo_publicacao' => [
                                                    'class' => 'bg-yellow-100 text-yellow-800',
                                                    'text' => 'Em Processo de Publicação',
                                                ],
                                                'publicado' => [
                                                    'class' => 'bg-green-100 text-green-800',
                                                    'text' => 'Publicado',
                                                ],
                                                'arquivado' => [
                                                    'class' => 'bg-gray-100 text-gray-800',
                                                    'text' => 'Arquivado',
                                                ],
                                            ];

                                            $config =
                                                $statusConfig[$periodo->status] ??
                                                $statusConfig['em_processo_publicacao'];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config['class'] }} w-fit">
                                            {{ $config['text'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 lg:flex-shrink-0">
                                @if ($periodo->status !== 'publicado')
                                    <button wire:click="abrirModalEditar({{ $periodo->id }})"
                                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Editar
                                    </button>
                                    @if ($periodo->status !== 'em_processo_publicacao')
                                        <button
                                            onclick="if(!confirm('Tem certeza que deseja deletar este período?')) { event.preventDefault(); return false; }"
                                            wire:click="deletar({{ $periodo->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Deletar
                                        </button>
                                    @else
                                  
                                    @endif
                                @else
                                    <div class="text-sm text-gray-500 italic px-3 py-2">
                                        Período publicado
                                    </div>
                                @endif

                                @if ($periodo->status === 'em_processo_publicacao')
                                    <button
                                        onclick="if(!confirm('Tem certeza que deseja publicar este período? O período atual publicado será arquivado.')) { event.preventDefault(); return false; }"
                                        wire:click="publicar({{ $periodo->id }})"
                                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Publicar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v1h16v-1a4 4 0 11-8 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum período encontrado</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Comece criando um novo período para os espelhos.
                </p>
                <div class="mt-6">
                    <button wire:click="abrirModalCriar"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Novo Período
                    </button>
                </div>
            </div>
        @endif
    </div>


    @if ($this->mostrarModal)
        <div class="fixed inset-0 overflow-y-auto" style="z-index: 9998 !important;" x-data="{ show: true }"
            x-show="show" x-transition>
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" x-show="show"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg px-4 sm:px-6 pt-5 sm:pt-6 pb-4 sm:pb-6 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full mx-4 sm:mx-0"
                    x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $modoEdicao ? 'Editar Período' : 'Novo Período' }}
                        </h3>
                        <button wire:click="fecharModal" type="button"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1">
                            <span class="sr-only">Fechar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="w-full">
                        <form wire:submit.prevent="salvar">
                            <div class="space-y-4">
                                <div>
                                    <label for="periodo_inicio" class="block text-sm font-medium text-gray-700">Data
                                        de Início</label>
                                    <input wire:model="periodoInicio" type="date" id="periodo_inicio"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('periodoInicio') border-red-300 @enderror">
                                    @error('periodoInicio')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="periodo_fim" class="block text-sm font-medium text-gray-700">Data de
                                        Fim</label>
                                    <input wire:model="periodoFim" type="date" id="periodo_fim"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('periodoFim') border-red-300 @enderror">
                                    @error('periodoFim')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" wire:click="fecharModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    Cancelar
                                </button>
                                <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    <span wire:loading.remove>
                                        {{ $modoEdicao ? 'Atualizar' : 'Salvar' }}
                                    </span>
                                    <span wire:loading>
                                        Salvando...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif


</div>
