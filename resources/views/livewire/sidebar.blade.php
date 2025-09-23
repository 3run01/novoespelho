    <div>
    <nav
        class="fixed top-0 left-0 right-0 h-20 bg-gradient-to-br from-blue-50 via-white to-white border-b border-gray-200 shadow-md z-50 transition-all duration-300">
        <div class="px-4 sm:px-6 h-full flex items-center justify-between">
            <div class="flex items-center space-x-6">


                <div class="flex items-center space-x-4">
                    <img src="{{ asset('logo.png') }}" alt="MP Logo" class="w-24 h-14 object-contain"
                        style="image-rendering: auto; image-rendering: crisp-edges;">
                    <div class="flex flex-col justify-center">
                        <span class="text-lg sm:text-xl font-extrabold text-gray-800 leading-tight font-sans"
                            style="font-family: 'Inter', 'Segoe UI', Arial, sans-serif;">
                            Gestão do Espelho
                        </span>
                        <span class="text-xs sm:text-sm font-medium text-blue-700 leading-tight">
                            Ministério Público do Amapá
                        </span>
                    </div>
                </div>
            </div>
             <div class="flex items-center space-x-4">
                            <div class="border border-gray-300 rounded-lg px-3 py-2 flex items-center space-x-3">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="8" r="4" />
                                    <path d="M6 20v-2a6 6 0 0112 0v2" />
                                </svg>
                                <span class="font-medium text-gray-700 text-sm">
                                    {{ $usuario ?? 'Usuário' }}
                                </span>
                                <span class="text-gray-400">|</span>
                                <svg class="w-6 h-6 text-red-600 cursor-pointer" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" wire:click="logout">
                                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                             </svg>
                        </div>
                 </div>
        </nav>
    <div
 class="fixed inset-0 bg-blue-600 bg-opacity-50 z-40 lg:hidden">
    </div>

    <aside
        class="fixed left-0 top-20 bottom-0 w-[224px] bg-gradient-to-br from-blue-50 via-white to-white shadow-xl border-r border-gray-200 z-40 transform transition-all duration-300 ease-in-out lg:translate-x-0">
        <div class="flex flex-col h-full">

            <nav
                class="flex-1 px-2 py-4 space-y-1 overflow-y-auto scrollbar-thin scrollbar-track-blue-100 scrollbar-thumb-blue-300">
                <a href="{{ route('gestao-espelho') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('gestao-espelho') ? 'bg-blue-100 text-blue-800 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-900 hover:translate-x-1' }}">
                    <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="2" class="fill-blue-100/30" />
                        <path d="M3 7h18M7 3v4M17 3v4" stroke-linecap="round" />
                    </svg>
                    <span class="ml-3 transition-opacity group-hover:opacity-80"
                        >
                        Gestão Espelho
                    </span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('comarca', 'grupo-promotores', 'promotorias', 'promotores') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-700 rounded-lg transition-all duration-200 group hover:bg-blue-50 hover:text-blue-900 focus:outline-none">
                        <svg class="w-5 h-5 mr-2 text-gray-500 group-hover:text-blue-700 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <span>Configurações</span>    <svg :class="{'rotate-180': open}" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="space-y-1 ml-6 mt-1" style="display: none;">
                        <a href="{{ route('comarca') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-all duration-200 group {{ request()->routeIs('comarca') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                            Comarcas
                        </a>
                        <a href="{{ route('grupo-promotores') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-all duration-200 group {{ request()->routeIs('grupo-promotores') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                            Grupos de Promotorias
                        </a>
                        <a href="{{ route('promotorias') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-all duration-200 group {{ request()->routeIs('promotorias') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                            Promotorias
                        </a>
                        <a href="{{ route('promotores') }}" class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-all duration-200 group {{ request()->routeIs('promotores') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                            Promotores
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </aside>
</div>
