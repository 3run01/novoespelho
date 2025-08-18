<div x-data="{ sidebarOpen: false }">
    <!-- Navbar fixa no topo -->
    <nav class="fixed top-0 left-0 right-0 h-20 bg-white border-b border-gray-200 z-50">
        <div class="px-4 sm:px-6 h-full flex items-center justify-between">
            <!-- Logo e título -->
            <div class="flex items-center">
                <!-- Botão de menu mobile -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden mr-4 p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <img src="{{ asset('logo.png') }}" alt="MP Logo" class="w-10 h-10 sm:w-12 sm:h-12 flex-shrink-0">
                <h1 class="ml-2 sm:ml-4 text-lg sm:text-xl font-bold text-gray-800 truncate">
                    Ministério Público do Estado do Amapá
                </h1>
            </div>
        </div>
    </nav>

    <!-- Overlay para mobile -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
        class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden">
    </div>

    <!-- Sidebar -->
    <aside
        class="fixed left-0 top-20 bottom-0 w-64 bg-white shadow-lg border-r border-gray-200 z-40 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex flex-col h-full">
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('gestao-espelho') }}" @click="sidebarOpen = false"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('gestao-espelho') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="ml-3">
                        Gestão Espelho
                    </span>
                </a>

                <!-- Configurações Dropdown -->
                <div x-data="{ open: true }" class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-all duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="ml-3">
                                Configurações
                            </span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <!-- Dropdown Links -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2" class="ml-6 space-y-1">

                        <a href="{{ route('municipios') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-colors duration-200 {{ request()->routeIs('municipios') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Municípios
                        </a>

                        <a href="{{ route('grupo-promotores') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-colors duration-200 {{ request()->routeIs('grupo-promotores') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            Grupos de Promotorias
                        </a>

                        <a href="{{ route('promotorias') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-colors duration-200 {{ request()->routeIs('promotorias') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            Promotorias
                        </a>

                        <a href="{{ route('promotores') }}" @click="sidebarOpen = false"
                            class="flex items-center px-3 py-2 text-sm text-gray-600 rounded-lg transition-colors duration-200 {{ request()->routeIs('promotores') ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-600' : 'hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Promotores
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Footer -->
            <div class="px-4 py-4 border-t border-gray-200 mt-auto bg-gray-50">
                <p class="text-xs text-gray-600 text-center font-medium">
                    Powered by DSIS
                </p>
            </div>
        </div>
    </aside>
</div>
