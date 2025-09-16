<div>
@if ($mostrarModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="fecharModal">
        <div class="relative top-4 mx-auto p-4 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 max-w-6xl shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3">
                <div class="flex justify-between items-center pb-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">
                        Gerar Portaria - E-Diário
                    </h3>
                    <button wire:click="fecharModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

            

                @if ($evento)
                    <div class="mt-4 mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Evento Selecionado:</h4>
                        <p class="text-sm text-blue-700">{{ $evento->titulo ?? 'Sem título' }}</p>
                        @if ($evento->promotoria)
                            <p class="text-sm text-blue-600 mt-1">Promotoria: {{ $evento->promotoria->nome }}</p>
                        @endif
                        @if ($periodo_inicio || $periodo_fim)
                            <p class="text-sm text-blue-600 mt-1">Período: {{ $periodo_inicio }} {{ $periodo_inicio && $periodo_fim ? '—' : '' }} {{ $periodo_fim }}</p>
                        @endif
                    </div>
                @endif

                @if (session()->has('mensagem'))
                    <div class="mt-4 mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                        <span class="block sm:inline">{{ session('mensagem') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="gerarPortaria" class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo Portaria <span class="text-red-500">*</span></label>
                            <input type="text" value="Gabinete PGJ" disabled class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600" />
                            <input type="hidden" wire:model="tipoPortaria" value="3" />
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assunto <span class="text-red-500">*</span></label>
                            <select wire:model.live="assunto" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('assunto') border-red-300 @enderror">
                                <option value="">Selecione</option>
                                <option value="alterar_escala_de_plantao">ALTERAR ESCALA DE PLANTÃO</option>
                                <option value="coordenacao">COORDENAÇÃO</option>
                                <option value="coordenacao_coletiva">COORDENAÇÃO COLETIVA</option>
                                <option value="cumulacao">CUMULAÇÃO</option>
                                <option value="cumulacao_coletivo">CUMULAÇÃO (COLETIVO)</option>
                                <option value="cursos_congressos_eventos">CURSOS/CONGRESSOS/EVENTOS</option>
                                <option value="cursos_congressos_eventos_coletivo">CURSOS/CONGRESSOS/EVENTOS (COLETIVO)</option>
                                <option value="designacao">DESIGNAÇÃO</option>
                                <option value="designacao_coletivo">DESIGNAÇÃO COLETIVO</option>
                                <option value="ferias_regulamentares">FÉRIAS REGULAMENTARES</option>
                                <option value="folgas_de_plantao">FOLGAS DE PLANTÃO</option>
                                <option value="gozo_de_ferias">GOZO DE FÉRIAS</option>
                                <option value="justica_eleitoral">JUSTIÇA ELEITORAL</option>
                                <option value="licenca_familiar">LICENÇA FAMILIAR</option>
                                <option value="licenca_medica">LICENÇA MÉDICA</option>
                                <option value="licenca_por_luto">LICENÇA POR LUTO</option>
                                <option value="licenca_premio">LICENÇA PRÊMIO</option>
                                <option value="licenca_recesso">LICENÇA RECESSO</option>
                                <option value="plantao_promotorias">PLANTÃO PROMOTORIAS</option>
                                <option value="portaria_de_teletrabalho">PORTARIA DE TELETRABALHO</option>
                                <option value="suspensao_de_ferias">SUSPENSÃO DE FÉRIAS</option>
                                <option value="suspensao_licenca_premio">SUSPENSÃO LICENÇA PRÊMIO</option>
                            </select>
                            @error('assunto')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mês <span class="text-red-500">*</span></label>
                            @if ($mes)
                                <input type="text" value="{{ $mes }}" disabled class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600" />
                            @else
                                <select wire:model.defer="mes" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mes') border-red-300 @enderror">
                                    <option value="">Selecione</option>
                                    <option value="Janeiro">Janeiro</option>
                                    <option value="Fevereiro">Fevereiro</option>
                                    <option value="Março">Março</option>
                                    <option value="Abril">Abril</option>
                                    <option value="Maio">Maio</option>
                                    <option value="Junho">Junho</option>
                                    <option value="Julho">Julho</option>
                                    <option value="Agosto">Agosto</option>
                                    <option value="Setembro">Setembro</option>
                                    <option value="Outubro">Outubro</option>
                                    <option value="Novembro">Novembro</option>
                                    <option value="Dezembro">Dezembro</option>
                                </select>
                            @endif
                            @error('mes')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ano Portaria <span class="text-red-500">*</span></label>
                            <input type="number" wire:model.defer="anoPortaria" min="1900" max="2100" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('anoPortaria') border-red-300 @enderror" placeholder="AAAA" />
                            @error('anoPortaria')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        
                       
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Processo</label>
                            @if ($processo)
                                <input type="text" value="{{ $processo }}" disabled class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600" />
                            @else
                                <input type="text" wire:model.defer="processo" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Número do processo" />
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                            <input type="text" value="Administrativo" disabled class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600" />
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data Expedição <span class="text-red-500">*</span></label>
                            @if ($dataExpedicao)
                                <input type="text" value="{{ $dataExpedicao }}" disabled class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600" />
                            @else
                                <input type="date" wire:model.defer="dataExpedicao" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('dataExpedicao') border-red-300 @enderror" />
                            @endif
                            @error('dataExpedicao')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Signatário</label>
                            <input type="text" value="(preenchido automaticamente)" disabled class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                            <textarea rows="5" wire:model.live="descricao" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descricao') border-red-300 @enderror" placeholder="Descrição detalhada">{{ $descricao }}</textarea>
                            @error('descricao')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button" wire:click="fecharModal" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Cancelar
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span wire:loading.remove>Gerar Portaria</span>
                            <span wire:loading>Gerando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
</div>
