<div class="w-full max-w-none px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Gestão de Eventos</h1>
            <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">Gerencie os eventos por período e promotoria</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('mensagem'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('mensagem') }}</span>
        </div>
    @endif

    @if (session()->has('erro'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('erro') }}</span>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Seleção de Período -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                    <select wire:model.live="periodoSelecionado.id" wire:change="selecionarPeriodo($event.target.value)"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos os períodos</option>
                        @foreach($periodos as $periodo)
                            <option value="{{ $periodo->id }}">
                                {{ $periodo->periodo_inicio->format('d/m/Y') }} - {{ $periodo->periodo_fim->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Campo de Busca -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" wire:model.live.debounce.300ms="termoBusca" 
                           placeholder="Buscar por título, tipo ou promotoria..."
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Info do Período Selecionado -->
                <div>
                    @if($periodoSelecionado)
                        <label class="block text-sm font-medium text-gray-700 mb-2">Período Selecionado</label>
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                            <p class="text-sm text-blue-800 font-medium">
                                {{ $periodoSelecionado->periodo_inicio->format('d/m/Y') }} - 
                                {{ $periodoSelecionado->periodo_fim->format('d/m/Y') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela Principal -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                            Promotorias
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                            Promotores
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                            Períodos
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($promotoriasListado as $promotoria)
                        @php
                            $eventosCount = $promotoria->eventos->count();
                        @endphp
                        @if($eventosCount > 0)
                            @foreach($promotoria->eventos as $index => $evento)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Coluna PROMOTORIAS -->
                                    @if($index === 0)
                                        <td rowspan="{{ $eventosCount }}" class="px-6 py-6 align-top">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $promotoria->nome }}</h3>
                                                @if($periodoSelecionado)
                                                    <div class="text-sm text-gray-600 mb-3">
                                                        <span class="font-medium text-gray-900">Período vigente:</span> 
                                                        {{ $periodoSelecionado->periodo_inicio->format('d/m/Y') }} - 
                                                        {{ $periodoSelecionado->periodo_fim->format('d/m/Y') }}
                                                    </div>
                                                @endif
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                    {{ $eventosCount }} {{ $eventosCount == 1 ? 'evento' : 'eventos' }}
                                                </span>
                                                
                                                <!-- Botão Adicionar (apenas na primeira linha da promotoria) -->
                                                <div class="mt-4">
                                                    <button
                                                        wire:click="abrirModalCriarParaPromotoria({{ $promotoria->id }})"
                                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                        Adicionar Evento
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    @endif

                                    <!-- Coluna PROMOTORES -->
                                    <td class="px-6 py-6 align-top">
                                        <div class="space-y-3">
                                            <!-- Título do Evento -->
                                            <div class="flex items-center gap-3 mb-3">
                                                <h4 class="text-lg font-semibold text-gray-900">{{ $evento->titulo ?: ucfirst($evento->tipo) }}</h4>
                                                @if($evento->is_urgente)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Urgente
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Promotores Designados -->
                                            @if($evento->promotores->count() > 0)
                                                <div class="space-y-2">
                                                    @foreach($evento->promotores as $promotor)
                                                        <div class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded-lg">
                                                            <div class="flex items-center gap-2">
                                                                <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center">
                                                                    <span class="text-xs font-medium text-blue-600">
                                                                        {{ substr($promotor->nome, 0, 1) }}
                                                                    </span>
                                                                </div>
                                                                <span class="text-sm font-medium text-gray-900">{{ $promotor->nome }}</span>
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $promotor->pivot->tipo === 'titular' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                                    {{ ucfirst($promotor->pivot->tipo) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-500 italic">Nenhum promotor designado</p>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Coluna PERÍODOS -->
                                    <td class="px-6 py-6 align-top">
                                        <div class="space-y-3">
                                            <!-- Período do Evento -->
                                            <div class="text-sm text-gray-600">
                                                <span class="font-medium text-gray-900">Período:</span> 
                                                {{ $evento->periodo_inicio->format('d/m/Y') }} - 
                                                {{ $evento->periodo_fim->format('d/m/Y') }}
                                            </div>
                                            
                                            <!-- Designações Detalhadas -->
                                            @if($evento->promotores->count() > 0)
                                                <div>
                                                    <h5 class="text-sm font-medium text-gray-900 mb-2">Designações:</h5>
                                                    <div class="space-y-1">
                                                        @foreach($evento->promotores as $promotor)
                                                            <div class="text-xs text-gray-700 bg-gray-50 rounded px-2 py-1">
                                                                <strong>{{ $promotor->nome }}</strong><br>
                                                                {{ \Carbon\Carbon::parse($promotor->pivot->data_inicio_designacao)->format('d/m/Y') }} - 
                                                                {{ \Carbon\Carbon::parse($promotor->pivot->data_fim_designacao)->format('d/m/Y') }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- Ações -->
                                            <div class="flex items-center gap-2">
                                                <button 
                                                    wire:click="abrirModalEditar({{ $evento->id }})"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                                >
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Editar
                                                </button>
                                                <button 
                                                    onclick="confirmarDelecao({{ $evento->id }})"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                                                >
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Deletar
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <!-- Linha para promotoria sem eventos -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-6 align-top">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $promotoria->nome }}</h3>
                                        @if($periodoSelecionado)
                                            <div class="text-sm text-gray-600 mb-3">
                                                <span class="font-medium text-gray-900">Período vigente:</span> 
                                                {{ $periodoSelecionado->periodo_inicio->format('d/m/Y') }} - 
                                                {{ $periodoSelecionado->periodo_fim->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            0 eventos
                                        </span>
                                        
                                        <div class="mt-4">
                                            <button
                                                wire:click="abrirModalCriarParaPromotoria({{ $promotoria->id }})"
                                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Adicionar Evento
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-center text-gray-500 italic">
                                    Nenhum evento cadastrado
                                </td>
                                <td class="px-6 py-6 text-center text-gray-500">
                                    -
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma promotoria encontrada</h3>
                                <p class="mt-1 text-sm text-gray-500">Verifique os filtros aplicados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Evento -->
    @if($mostrarModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
             wire:click="fecharModal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
                 wire:click.stop>
                <div class="mt-3">
                    <!-- Header do Modal -->
                    <div class="flex justify-between items-center pb-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $modoEdicao ? 'Editar Evento' : 'Novo Evento' }}
                        </h3>
                        <button wire:click="fecharModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Formulário -->
                    <form wire:submit.prevent="salvar" class="mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Título -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Título
                                </label>
                                <input type="text" wire:model="titulo" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Digite o título do evento">
                                @error('titulo') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="tipo" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecione o tipo</option>
                                    <option value="audiencia">Audiência</option>
                                    <option value="custodia">Custódia</option>
                                    <option value="plantao">Plantão</option>
                                    <option value="sessao">Sessão</option>
                                    <option value="congresso">Congresso</option>
                                    <option value="ferias">Férias</option>
                                    <option value="outro">Outro</option>
                                </select>
                                @error('tipo') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Promotoria -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Promotoria <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="promotoria_id" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecione a promotoria</option>
                                    @foreach($promotorias as $promotoria)
                                        <option value="{{ $promotoria->id }}">{{ $promotoria->nome }}</option>
                                    @endforeach
                                </select>
                                @error('promotoria_id') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Período Início -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Data Início <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model="periodo_inicio" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('periodo_inicio') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Período Fim -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Data Fim <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model="periodo_fim" 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('periodo_fim') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Urgente -->
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="is_urgente" 
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Marcar como urgente</span>
                                </label>
                            </div>
                        </div>

                        <!-- Designações de Promotores -->
                        <div class="mt-8">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-900">Promotores designados</h4>
                                <button type="button" wire:click="adicionarLinhaPromotor" class="text-blue-600 hover:text-blue-800 text-sm">
                                    + Adicionar promotor
                                </button>
                            </div>

                            <div class="space-y-4">
                                @foreach($promotoresDesignacoes as $i => $linha)
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Promotor</label>
                                            <select wire:model="promotoresDesignacoes.{{ $i }}.promotor_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Selecione</option>
                                                @foreach($promotores as $promotor)
                                                    <option value="{{ $promotor->id }}">{{ $promotor->nome }}</option>
                                                @endforeach
                                            </select>
                                            @error('promotoresDesignacoes.' . $i . '.promotor_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                                            <select wire:model="promotoresDesignacoes.{{ $i }}.tipo" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <option value="titular">Titular</option>
                                                <option value="substituto">Substituto</option>
                                                <option value="plantao">Plantão</option>
                                                <option value="outro">Outro</option>
                                            </select>
                                            @error('promotoresDesignacoes.' . $i . '.tipo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Início</label>
                                            <input type="date" wire:model="promotoresDesignacoes.{{ $i }}.data_inicio_designacao" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            @error('promotoresDesignacoes.' . $i . '.data_inicio_designacao')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Fim</label>
                                            <input type="date" wire:model="promotoresDesignacoes.{{ $i }}.data_fim_designacao" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            @error('promotoresDesignacoes.' . $i . '.data_fim_designacao')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="button" wire:click="removerLinhaPromotor({{ $i }})" class="px-3 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Remover</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
                            <button type="button" wire:click="fecharModal"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50"
                                    class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 transition duration-200">
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
    @endif

    <script>
    function confirmarDelecao(eventoId) {
        if (confirm('Tem certeza que deseja deletar este evento? Esta ação não pode ser desfeita.')) {
            @this.call('deletar', eventoId);
        }
    }
    </script>
</div>