<div x-data="{
    sidebarOpen: @entangle('sidebarOpen'),
    sidebarCollapsed: @entangle('sidebarCollapsed')
}" x-on:sidebar-state-reset.window="sidebarCollapsed = false">
    <nav
        class="fixed top-0 left-0 right-0 h-20 bg-gradient-to-br from-blue-50 via-white to-white border-b border-gray-200 shadow-md z-50 transition-all duration-300">
        <div class="px-4 sm:px-6 h-full flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <button wire:click="toggleMobileSidebar"
                    class="lg:hidden p-2 rounded-full text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-all duration-200">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <rect x="3" y="5" width="18" height="2" rx="1" class="fill-blue-200/60" />
                        <rect x="3" y="11" width="18" height="2" rx="1" class="fill-blue-200/60" />
                        <rect x="3" y="17" width="18" height="2" rx="1" class="fill-blue-200/60" />
                    </svg>
                </button>

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
            <div class="flex items-center space-x-2">
                <!-- Botão de Teste -->
                <button wire:click="testeSimples"
                    class="p-2 rounded-full text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 transition-all duration-200"
                    title="Teste">
                    Teste
                </button>

                <button wire:click="logoutFromSidebar"
                    class="p-2 rounded-full text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500 transition-all duration-200"
                    title="Sair">
                    sair
                </button>
            </div>
        </div>
    </nav>

    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
        class="fixed inset-0 bg-blue-600 bg-opacity-50 z-40 lg:hidden">
    </div>

    <aside
        class="fixed left-0 top-20 bottom-0 bg-gradient-to-br from-blue-50 via-white to-white shadow-xl border-r border-gray-200 z-40 transform transition-all duration-300 ease-in-out lg:translate-x-0"
        :class="[
            sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            sidebarCollapsed ? 'w-16' : 'w-64'
        ]">
        <div class="flex flex-col h-full">
            <div class="flex justify-end p-2 border-b border-gray-200">
                <button wire:click="toggleSidebar"
                    class="p-2 rounded-full text-gray-500 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-all duration-200 group"
                    :title="sidebarCollapsed ? 'Expandir sidebar' : 'Encolher sidebar'">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" :class="{ 'rotate-180': sidebarCollapsed }">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>
            </div>

            <nav
                class="flex-1 px-2 py-4 space-y-1 overflow-y-auto scrollbar-thin scrollbar-track-blue-100 scrollbar-thumb-blue-300">
                <!-- Links com padrão unificado -->
                <a href="{{ route('gestao-espelho') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 group {{ request()->routeIs('gestao-espelho') ? 'bg-blue-100 text-blue-800 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-900 hover:translate-x-1' }}">
                    <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="2" class="fill-blue-100/30" />
                        <path d="M3 7h18M7 3v4M17 3v4" stroke-linecap="round" />
                    </svg>
                    <span class="ml-3 transition-opacity group-hover:opacity-80"
                        :class="{ 'lg:hidden': sidebarCollapsed }">
                        Gestão Espelho
                    </span>
                </a>

                <!-- Collapse com padrão unificado -->
                <div x-data="{ open: {{ request()->routeIs('gestao-espelho', 'municipios', 'grupo-promotores', 'promotorias', 'promotores') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-all duration-200 group">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 flex-shrink-0 transition-transform group-hover:scale-110" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="3" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.65 1.65 0 0 0 15 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 8.6 15a1.65 1.65 0 0 0-1.82-.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 15 8.6a1.65 1.65 0 0 0 1.82.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 15z" />
                            </svg>
                            <span class="ml-3 transition-opacity group-hover:opacity-80"
                                :class="{ 'lg:hidden': sidebarCollapsed }">
                                Configurações
                            </span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110"
                            :class="{ 'rotate-180': open && !sidebarCollapsed, 'lg:hidden': sidebarCollapsed }"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="6 9 12 15 18 9" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div x-show="open && !sidebarCollapsed" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2" class="space-y-1 ml-6">

                        <a href="{{ route('gestao-espelho') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-all duration-200 group {{ request()->routeIs('gestao-espelho') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                            Gestão Espelho
                        </a>

                        <a href="{{ route('comarca') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-all duration-200 group {{ request()->routeIs('municipios') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                            Municípios
                        </a>

                        <a href="{{ route('grupo-promotores') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-all duration-200 group {{ request()->routeIs('grupo-promotores') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                            Grupos de Promotorias
                        </a>

                        <a href="{{ route('promotorias') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-all duration-200 group {{ request()->routeIs('promotorias') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                            Promotorias
                        </a>

                        <a href="{{ route('promotores') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-all duration-200 group {{ request()->routeIs('promotores') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                            Promotores
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </aside>
</div>
