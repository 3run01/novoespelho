<x-layouts.app>
    <div x-data="{ modoVisualizacao: 'gestao' }">
        <div class="mb-6 sm:mb-8">
            <div class="flex justify-center">
                <div class="bg-gray-100 p-1 rounded-lg inline-flex w-full sm:w-auto">
                    <button @click="modoVisualizacao = 'gestao'"
                        :class="modoVisualizacao === 'gestao' ? 'bg-white text-gray-900 shadow-sm' :
                            'text-gray-500 hover:text-gray-700'"
                        class="flex-1 sm:flex-none px-4 sm:px-6 py-2 text-sm font-medium rounded-md transition-all duration-200">
                        <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="hidden sm:inline">Gestão Espelho</span>
                        <span class="sm:hidden">Gestão</span>
                    </button>
                    <button @click="modoVisualizacao = 'previa'"
                        :class="modoVisualizacao === 'previa' ? 'bg-white text-gray-900 shadow-sm' :
                            'text-gray-500 hover:text-gray-700'"
                        class="flex-1 sm:flex-none px-4 sm:px-6 py-2 text-sm font-medium rounded-md transition-all duration-200">
                        <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Prévia
                    </button>
                </div>
            </div>
        </div>

        <div x-show="modoVisualizacao === 'gestao'" x-transition class="space-y-6 sm:space-y-8">
            <livewire:periodos />
            <livewire:plantao-urgencia />
            <livewire:eventos />
        </div>

        <div x-show="modoVisualizacao === 'previa'" x-transition>
            <livewire:espelho />
        </div>
    </div>
</x-layouts.app>
