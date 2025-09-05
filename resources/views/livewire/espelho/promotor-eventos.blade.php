<div class="w-full max-w-none px-4 sm:px-6 lg:px-8 mt-10">
    <div class="mb-6 sm:mb-8">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Gestão de Promotores de Justiça Substitutos</h1>
            <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">Gerencie os eventos e designações dos promotores de justiça substitutos</p>
        </div>
    </div>

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

    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Período</label>
                    <select wire:model.live="periodoSelecionadoId"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos os períodos</option>
                        @foreach ($this->periodos as $periodo)
                            <option value="{{ $periodo->id }}">
                                {{ $periodo->periodo_inicio->format('d/m/Y') }} -
                                {{ $periodo->periodo_fim->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" wire:model.live.debounce.300ms="termoBusca"
                        placeholder="Buscar por nome do promotor..."
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    @if ($this->periodoSelecionado)
                        <label class="block text-sm font-medium text-gray-700 mb-2">Período Selecionado</label>
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                            <p class="text-sm text-blue-800 font-medium">
                                {{ $this->periodoSelecionado->periodo_inicio->format('d/m/Y') }} -
                                {{ $this->periodoSelecionado->periodo_fim->format('d/m/Y') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Promotores Substitutos e suas Designações -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            @php
                $designacoesSubstitutos = $this->listarDesignacoesPromotoresSubstitutos();
                $estatisticas = $this->obterEstatisticasDesignacoesSubstitutos();
            @endphp

            @if ($designacoesSubstitutos->isNotEmpty())
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">
                                Promotores de Justiça Substitutos - Designações por Período
                            </h3>
                            @if ($this->periodoSelecionado)
                                <p class="text-sm text-gray-600 mt-1">
                                    Período: {{ $this->periodoSelecionado->periodo_inicio->format('d/m/Y') }} - 
                                    {{ $this->periodoSelecionado->periodo_fim->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            {{ $estatisticas['promotores_com_designacoes'] }} promotores
                                        </span>
                                        <span class="text-gray-500">com designações</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            {{ $estatisticas['total_designacoes'] }} total
                                        </span>
                                        <span class="text-gray-500">designações</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                Promotores de Justiça Substitutos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                Informações
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                Eventos/Designações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($designacoesSubstitutos as $promotor)
                            @php
                                $eventosCount = $promotor['eventos']->count();
                            @endphp

                            @if ($eventosCount > 0)
                                @foreach ($promotor['eventos'] as $indexEvento => $evento)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <!-- Coluna PROMOTORES -->
                                        @if ($indexEvento === 0)
                                            <td rowspan="{{ $eventosCount }}" class="px-6 py-6 align-top border-r">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3 mb-3">
                                                        <div class="h-12 w-12 rounded-full bg-orange-100 flex items-center justify-center">
                                                            <span class="text-lg font-bold text-orange-600">
                                                                {{ substr($promotor['promotor_nome'], 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-lg font-semibold text-gray-900">
                                                                {{ $promotor['promotor_nome'] }}
                                                            </h3>
                                                            <div class="flex items-center gap-2 mt-1">
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                                    Substituto
                                                                </span>
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                    {{ ucfirst($promotor['promotor_tipo']) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if ($promotor['promotor_cargos'] !== 'N/A')
                                                        <div class="text-sm text-gray-600 mb-3">
                                                            <span class="font-medium text-gray-900">Cargo(s):</span>
                                                            {{ $promotor['promotor_cargos'] }}
                                                        </div>
                                                    @endif

                                                    <div class="text-sm text-gray-600 mb-3">
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <span class="font-medium text-gray-900">Total de Designações:</span>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                                {{ $promotor['total_eventos'] }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-xs">
                                                            <span class="text-gray-500">Manuais:</span>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                                {{ $promotor['total_manuais'] }}
                                                            </span>
                                                            <span class="text-gray-500">Automáticas:</span>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                                                {{ $promotor['total_automaticos'] }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Botão Adicionar -->
                                                    <div class="mt-4">
                                                        <button wire:click="abrirModalCriarParaPromotor({{ $promotor['promotor_id'] }})"
                                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                            </svg>
                                                            Adicionar Movimento
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Coluna INFORMAÇÕES -->
                                        @if ($indexEvento === 0)
                                            <td rowspan="{{ $eventosCount }}" class="px-6 py-6 align-top border-r">
                                                <div class="bg-gray-50 rounded-lg p-4">
                                                    <div class="space-y-2 text-sm text-gray-600">
                                                        <div>
                                                            <span class="font-medium text-gray-900">Tipo:</span>
                                                            {{ ucfirst($promotor['promotor_tipo']) }}
                                                        </div>
                                                        
                                                        @if ($this->periodoSelecionado)
                                                            <div>
                                                                <span class="font-medium text-gray-900">Período vigente:</span>
                                                                {{ $this->periodoSelecionado->periodo_inicio->format('d/m/Y') }} -
                                                                {{ $this->periodoSelecionado->periodo_fim->format('d/m/Y') }}
                                                            </div>
                                                        @endif

                                                        <div>
                                                            <span class="font-medium text-gray-900">Status:</span>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                                                Ativo
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif

                                        <!-- Coluna EVENTOS/DESIGNAÇÕES -->
                                        <td class="px-6 py-6 align-top">
                                            <div class="space-y-4">
                                                <!-- Título e Info do Evento -->
                                                <div class="border-l-4 pl-4 @if($evento['evento_do_substituto'] === true) border-orange-500 @else border-blue-500 @endif">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <h4 class="text-lg font-semibold text-gray-900">
                                                            {{ $evento['evento_titulo'] ?: ($evento['evento_tipo'] ? 
                                                                ($evento['evento_tipo'] === 'respondendo' ? 'Respondendo' : 
                                                                ($evento['evento_tipo'] === 'auxiliando' ? 'Auxiliando' : 
                                                                ($evento['evento_tipo'] === 'atuando' ? 'Atuando' : ucfirst($evento['evento_tipo'])))) : 'Evento') }}
                                                        </h4>
                                                        <div class="flex items-center gap-2">
                                                            <!-- Badge de tipo do evento -->
                                                            @if($evento['evento_tipo'])
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                                    @if($evento['evento_tipo'] === 'respondendo') bg-blue-100 text-blue-800
                                                                    @elseif($evento['evento_tipo'] === 'auxiliando') bg-green-100 text-green-800
                                                                    @elseif($evento['evento_tipo'] === 'atuando') bg-purple-100 text-purple-800
                                                                    @else bg-gray-100 text-gray-800
                                                                    @endif">
                                                                    @if($evento['evento_tipo'] === 'respondendo')
                                                                        Respondendo
                                                                    @elseif($evento['evento_tipo'] === 'auxiliando')
                                                                        Auxiliando
                                                                    @elseif($evento['evento_tipo'] === 'atuando')
                                                                        Atuando
                                                                    @else
                                                                        {{ ucfirst($evento['evento_tipo']) }}
                                                                    @endif
                                                                </span>
                                                            @endif
                                                            <!-- Badge de origem (manual/automática) -->
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                                @if($evento['evento_do_substituto'] === true) bg-green-100 text-green-800
                                                                @else bg-blue-100 text-blue-800
                                                                @endif">
                                                                @if($evento['evento_do_substituto'] === true)
                                                                    Manual
                                                                @else
                                                                    Automática
                                                                @endif
                                                            </span>
                                                            <!-- Badge de urgência -->
                                                            @if($evento['is_urgente'])
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                    Urgente
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Promotoria -->
                                                    @if ($evento['promotoria_nome'] && $evento['promotoria_nome'] !== 'N/A')
                                                        <div class="text-sm text-gray-600 mb-3">
                                                            <span class="font-medium text-gray-900">Promotoria:</span>
                                                            {{ $evento['promotoria_nome'] }}
                                                        </div>
                                                    @endif

                                                    <!-- Período da Designação -->
                                                    @if ($evento['data_inicio'] || $evento['data_fim'])
                                                        <div class="text-sm text-gray-600 mb-3">
                                                            <span class="font-medium text-gray-900">Período da Designação:</span>
                                                            @if ($evento['data_inicio'])
                                                                {{ $evento['data_inicio'] }}
                                                            @endif
                                                            @if ($evento['data_inicio'] && $evento['data_fim'])
                                                                -
                                                            @endif
                                                            @if ($evento['data_fim'])
                                                                {{ $evento['data_fim'] }}
                                                            @endif
                                                        </div>
                                                    @endif

                                                    <!-- Observações -->
                                                    @if ($evento['observacoes'])
                                                        <div class="text-sm text-gray-600 mb-3">
                                                            <span class="font-medium text-gray-900">Observações:</span>
                                                            {{ $evento['observacoes'] }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Ações -->
                                                <div class="flex items-center gap-2 pt-2 border-t">
                                                    @if($evento['evento_do_substituto'] === true)
                                                        <!-- Ações para designações manuais -->
                                                        <button wire:click="abrirModalEditar({{ $evento['evento_id'] }})"
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                            Editar
                                                        </button>
                                                        <button
                                                            onclick="if(!confirm('Tem certeza que deseja deletar este evento? Esta ação não pode ser desfeita.')) { event.stopImmediatePropagation(); event.preventDefault(); }"
                                                            wire:click="deletar({{ $evento['evento_id'] }})"
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                            Deletar
                                                        </button>
                                                    @else
                                                        <!-- Apenas visualização para designações automáticas -->
                                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            <span>Designação automática - apenas visualização</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-6 align-top border-r">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="h-12 w-12 rounded-full bg-orange-100 flex items-center justify-center">
                                                    <span class="text-lg font-bold text-orange-600">
                                                        {{ substr($promotor['promotor_nome'], 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900">
                                                        {{ $promotor['promotor_nome'] }}
                                                    </h3>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                            Substituto
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ ucfirst($promotor['promotor_tipo']) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($promotor['promotor_cargos'] !== 'N/A')
                                                <div class="text-sm text-gray-600 mb-3">
                                                    <span class="font-medium text-gray-900">Cargo(s):</span>
                                                    {{ $promotor['promotor_cargos'] }}
                                                </div>
                                            @endif

                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                0 eventos
                                            </span>

                                            <div class="mt-4">
                                                <button wire:click="abrirModalCriarParaPromotor({{ $promotor['promotor_id'] }})"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Adicionar Movimento
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 align-top border-r">
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="space-y-2 text-sm text-gray-600">
                                                <div>
                                                    <span class="font-medium text-gray-900">Tipo:</span>
                                                    {{ ucfirst($promotor['promotor_tipo']) }}
                                                </div>
                                                
                                                @if ($this->periodoSelecionado)
                                                    <div>
                                                        <span class="font-medium text-gray-900">Período vigente:</span>
                                                        {{ $this->periodoSelecionado->periodo_inicio->format('d/m/Y') }} -
                                                        {{ $this->periodoSelecionado->periodo_fim->format('d/m/Y') }}
                                                    </div>
                                                @endif

                                                <div>
                                                    <span class="font-medium text-gray-900">Status:</span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                                        Ativo
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center text-gray-500">
                                        Nenhum movimento cadastrado
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum promotor de justiça substituto encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Verifique se há promotores de justiça substitutos cadastrados no sistema</p>
                </div>
            @endif
        </div>
    </div>

    @if ($this->mostrarModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            wire:click="fecharModal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
                wire:click.stop>
                <div class="mt-3">
                    <div class="flex justify-between items-center pb-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $this->modoEdicao ? 'Editar Movimento' : 'Novo Movimento' }}
                        </h3>
                        <button wire:click="fecharModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="salvar" class="mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Título
                                </label>
                                <input type="text" wire:model.defer="titulo"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Digite o título do movimento">
                                @error('titulo')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Período <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.defer="periodo_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('periodo_id') border-red-300 @enderror">
                                    <option value="">Selecione um período</option>
                                    @foreach ($this->periodos as $periodo)
                                        <option value="{{ $periodo->id }}">
                                            {{ $periodo->periodo_inicio->format('d/m/Y') }} -
                                            {{ $periodo->periodo_fim->format('d/m/Y') }}
                                            ({{ $periodo->status_texto }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('periodo_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo
                                </label>
                                <select wire:model.defer="tipo"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecione o tipo</option>
                                    <option value="respondendo">Respondendo</option>
                                    <option value="auxiliando">Auxiliando</option>
                                    <option value="atuando">Atuando</option>
                                </select>
                                @error('tipo')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Promotoria -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Promotoria
                                </label>
                                <select wire:model.defer="promotoria_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecione a promotoria (opcional)</option>
                                    @foreach ($this->promotorias as $promotoria)
                                        <option value="{{ $promotoria->id }}">{{ $promotoria->nome }}</option>
                                    @endforeach
                                </select>
                                @error('promotoria_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Promotor Substituto -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Promotor Substituto <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.defer="promotor_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecione o promotor substituto</option>
                                    @foreach ($this->promotores as $promotor)
                                        <option value="{{ $promotor->id }}">{{ $promotor->nome }}</option>
                                    @endforeach
                                </select>
                                @error('promotor_id')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Período Início -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Data Início
                                </label>
                                <input type="date" wire:model.defer="periodo_inicio"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('periodo_inicio')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Período Fim -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Data Fim
                                </label>
                                <input type="date" wire:model.defer="periodo_fim"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('periodo_fim')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
                            <button type="button" wire:click="fecharModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                                Cancelar
                            </button>
                            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 transition duration-200">
                                <span wire:loading.remove>
                                    {{ $this->modoEdicao ? 'Atualizar' : 'Salvar' }}
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
</div>
